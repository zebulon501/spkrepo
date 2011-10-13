<?php
require_once 'spkrepo-conf.php';
Layout::extend('layouts/master');
$title = 'Home';
?>
Welcome to <?php echo SpkRepo::$deploymentName; ?><br/>
Use this URL to configure your Synology NAS:
<?php
$url ='http://'.$_SERVER['HTTP_HOST'].Url::action('SpkrepoHomeController::packages');
echo Html::anchor($url , $url);
?>
