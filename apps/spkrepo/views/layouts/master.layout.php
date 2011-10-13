<?php
Layout::input($title, 'string');
Layout::input($body, 'Block');
Layout::input($navigation, 'Block', new HtmlBlock());
Layout::input($style, 'Block', new HtmlBlock());
require_once 'spkrepo-conf.php';
?>
<html>
	<head>
		<?php
		if(!$style->draw()) {
			Part::draw('parts/style');
		}
		?>
		<title><?php echo SpkRepo::$deploymentName; ?> - <?php echo $title; ?></title> 
	</head>
	<body>
	<div class="container">
		<div class="span-24">
			<h1>SPK Repository</h1>
		</div>
		<?php
		if(!$style->draw()) {
			Part::draw('parts/navigation');
		}
		?>
		<div class="span-24 last">
			<div class="navigation">
			<?php echo $navigation; ?>
			</div>
			<?php echo $body; ?>
		</div>
		<div class="span-24 footer">
		  <p class="quiet bottom">
		  	 <?php echo Html::anchor(Url::action('SpkrepoHomeController::index'), 'SPK Repository') ?> is &copy; <?php echo date('Y'); ?> Zebulon. All rights reserved.
		  </p>
		</div>
		</div>
	</body>
</html>
