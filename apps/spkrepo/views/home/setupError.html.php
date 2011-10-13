<?php
Layout::extend('layouts/master');
$title = 'Setup Error';
?>
<div>
The following errors prevent this application to run properly:
<ul>
<?php if (!$spkDirFound) { ?>
	<li>The spk directory '<?php echo $spkDir;?>' does not exist.<br/>
	Please create it on the server with write permission for the server process.</li>
<?php }?>
<?php if (!$spkDirWritable) { ?>
	<li>The spk directory '<?php echo $spkDir;?>' is not writable.<br/>
	Please correct the file permissions on the server to grant write permission to the server process.</li>
<?php }?>
</ul>
</div>
