<?php
require_once "apps/spkrepo/models/Package.php";
$db = getDbConnection();
$sql = 'SELECT p.*, d.description AS desc_local FROM packages AS p LEFT JOIN package_descriptions AS d ON (p.id = d.package_id AND d.language = :language) WHERE p.arch = :arch OR p.arch = "noarch"';
$q = $db->prepare($sql);
$q->bindParam(':language', $params['language'], PDO::PARAM_STR, 3);
$q->bindValue(':arch', $params['arch'], PDO::PARAM_STR);
$q->execute();
$result = $q->fetchAll(PDO::FETCH_ASSOC);

// make the result nicer
foreach ($result as $index => $package) {
    unset($result[$index]['id']);
    unset($result[$index]['arch']);
    $result[$index]['qinst'] = ($package['qinst'] == 1 ? true : false);
    $result[$index]['start'] = ($package['start'] == 1 ? true : false);
    $result[$index]['beta'] = ($package['beta'] == 1 ? true : false);
    $result[$index]['desc'] = (isset($package['desc_local']) ? $package['desc_local'] : $package['desc']);
    unset($result[$index]['desc_local']);
}

echo json_encode ($result);
?>
