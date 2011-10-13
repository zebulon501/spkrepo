<?php
require_once 'spkrepo-conf.php';

function getDbConnection () {
	$db = Databases::getDefaultSource();
	
	$sql = 'CREATE TABLE IF NOT EXISTS `packages` (
  			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  			`package` varchar(50) NOT NULL,
  			`version` varchar(20) NOT NULL,
  			`dname` varchar(50) NOT NULL,
  			`desc` text NOT NULL,
  			`arch` varchar(10) DEFAULT NULL,
  			`link` varchar(255) NOT NULL,
  			`md5` varchar(255) NOT NULL,
  			`icon` blob,
  			`size` bigint(20) NOT NULL,
  			`qinst` tinyint(1) NOT NULL,
  			`depsers` varchar(255) DEFAULT NULL,
  			`deppkgs` varchar(255) DEFAULT NULL,
  			`start` tinyint(1) NOT NULL,
  			`maintainer` varchar(60) NOT NULL,
  			`changelog` text,
  			`beta` tinyint(1) NOT NULL,
  			PRIMARY KEY (`id`),
  			UNIQUE KEY `package` (`package`,`version`,`arch`,`beta`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;';
	$q = $db->prepare($sql);
	$q->execute();
	
	$sql = 'CREATE TABLE IF NOT EXISTS `package_descriptions` (
  			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  			`package_id` bigint(20) unsigned NOT NULL,
  			`language` char(3) NOT NULL,
  			`description` text NOT NULL,
  			PRIMARY KEY (`id`),
  			UNIQUE KEY `description` (`package_id`,`language`),
  			KEY `package_id` (`package_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;';
	$q = $db->prepare($sql);
	$q->execute();

	/*$sql = 'ALTER TABLE `package_descriptions`
			ADD CONSTRAINT `package_descriptions_ibfk_1`
			FOREIGN KEY (`package_id`)
			REFERENCES `packages` (`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE;';
	$q = $db->prepare($sql);
	$q->execute(); */
	
	return $db;
}

function cleanUp ($db, $info) {
	$sql = 'DELETE FROM packages WHERE package = :package AND arch = :arch';
	$q = $db->prepare($sql);
	$q->bindParam(':package', $info['package'], PDO::PARAM_STR);
	$q->bindParam(':arch', $info['arch'], PDO::PARAM_STR);
	$q->execute();
}

function insertPackage(&$info, $tmpFileName, $destFileName) {
	// Get a connection to the default DB
	$db = getDbConnection();

	// clean up database
	cleanUp ($db, $info);
	
	$spkDir = getcwd().'/'.SpkRepo::$spkDir;
	rename($tmpFileName, $spkDir.'/'.$destFileName);

	$info['url'] = SpkRepo::$baseUrl.'/'.SpkRepo::$spkDir.'/'.$destFileName;
	$info['md5'] = md5_file($spkDir.'/'.$destFileName);
	$info['size'] = filesize($spkDir.'/'.$destFileName);
	$qinst = true;
	$start = true;
	//$beta = ($_POST['beta'] == 'on' ? 1 : 0);
	$beta = 0;
	$changelog = (isset($_POST['changelog']) && $_POST['changelog'] != '' ? $_POST['changelog'] : null);

	// insert
	$sql = 'INSERT INTO packages (package,version,dname,`desc`,arch,link,md5,icon,size,qinst,depsers,deppkgs,start,maintainer,changelog,beta)
			VALUES (:package,:version,:dname,:desc,:arch,:link,:md5,:icon,:size,:qinst,:depsers,:deppkgs,:start,:maintainer,:changelog,:beta)';
	$q = $db->prepare($sql);
	$q->bindParam(':package', $info['package'], PDO::PARAM_STR);
	$q->bindParam(':version', $info['version'], PDO::PARAM_STR);
	$q->bindParam(':dname', $info['displayname'], PDO::PARAM_STR);
	$q->bindParam(':desc', $info['description'], PDO::PARAM_STR);
	$q->bindParam(':arch', $info['arch'], PDO::PARAM_STR);
	$q->bindParam(':link', $info['url'], PDO::PARAM_STR);
	$q->bindParam(':md5', $info['md5'], PDO::PARAM_STR);
	$q->bindParam(':icon', $info['package_icon'], PDO::PARAM_LOB);
	$q->bindParam(':size', $info['size'], PDO::PARAM_STR);
	$q->bindParam(':qinst', $qinst, PDO::PARAM_BOOL);
	$q->bindParam(':depsers', $info['install_dep_services'], PDO::PARAM_STR);
	$q->bindParam(':deppkgs', $info['install_dep_packages'], PDO::PARAM_STR);
	$q->bindParam(':start', $start, PDO::PARAM_BOOL);
	$q->bindParam(':maintainer', $info['maintainer'], PDO::PARAM_STR);
	$q->bindParam(':changelog', $changelog, PDO::PARAM_STR);
	$q->bindParam(':beta', $beta, PDO::PARAM_BOOL);
	$q->execute();
	$package_id = $db->lastInsertId();

	// add descriptions
	$sql = 'INSERT INTO package_descriptions (package_id,language,description) VALUES (:package_id,:language,:description)';
	$q = $db->prepare($sql);
	foreach ($info as $k => $v) {
		if (preg_match('/^description_(\w{3})$/', $k, $matches)) {
			$q->bindParam(':package_id', $package_id, PDO::PARAM_INT);
			$q->bindParam(':language', $matches[1], PDO::PARAM_STR, 3);
			$q->bindParam(':description', $info[$matches[0]], PDO::PARAM_STR);
			$q->execute();
		}
	}
}
?>
