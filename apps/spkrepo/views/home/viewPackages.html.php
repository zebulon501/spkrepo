<?php 
Layout::extend('layouts/master');
$title = 'SPK List';

require_once "apps/spkrepo/models/Package.php";
$db = getDbConnection();
$sql = 'SELECT * FROM `packages`';
$q = $db->prepare($sql);
$q->execute();
$result = $q->fetchAll(PDO::FETCH_ASSOC);
?>
<table>
<?php
foreach ($result as $index => $package) {
?>
	<tr>
	  <td>
	    <img src="data:;base64,<?php echo $package['icon']?>" />
	  </td>
	  <td>
	    <table>
<tr><td>Package:</td><td><?php echo $package['package']?></td></tr>
<tr><td>Version:</td><td><?php echo $package['version']?></td></tr>
<tr><td>Arch:</td><td><?php echo $package['arch']?></td></tr>
<tr><td>URL:</td><td><a href="<?php echo $package['link']?>"><?php echo $package['link']?></a></td></tr>
        </table>
	  </td>
	</tr>
<?php
}
?>
</table>
