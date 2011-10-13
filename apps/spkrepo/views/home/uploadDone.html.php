<?php
Layout::extend('layouts/master');
$title = 'Upload Done';
?>
<div style="float:left;">
<img src="data:;base64,<?php echo $info['package_icon']?>" />
</div>
<div>
Package <?php echo $info['package']?> uploaded.<br>
Version: <?php echo $info['version']?><br>
Arch:  <?php echo $info['arch']?><br>
URL: <a href="<?php echo $info['url']?>"><?php echo $info['url']?></a>.
</div>
