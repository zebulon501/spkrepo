<?php 
Layout::extend('layouts/master');
$title = 'SPK Upload';
?>
<form method="POST" enctype="multipart/form-data" action="<?php echo Url::action('SpkrepoHomeController::uploadPost')?>">
		<label for="spk">SPK (Max: <?php echo ini_get('upload_max_filesize'); ?>):</label>
		<input type="file" name="spk" id="spk" /><br />
		<label for="spk">INFO:</label>
		<input type="file" name="info" id="info" /><br />
		<input type="checkbox" name="beta" id="beta" />
		<label for="beta">beta</label><br />
		<label for="changelog">changelog</label>
		<textarea id="changelog" name="changelog"></textarea><br />
		<input type="submit" name="upload" value="OK" />
</form>
