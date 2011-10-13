<?php
Library::import('recess.framework.Application');

class SpkrepoApplication extends Application {
	public function __construct() {
		
		$this->name = 'SPK Repository';
		
		$this->viewsDir = $_ENV['dir.apps'] . 'spkrepo/views/';
		
		$this->assetUrl = $_ENV['url.assetbase'] . 'apps/spkrepo/public/';
		
		$this->modelsPrefix = 'spkrepo.models.';
		
		$this->controllersPrefix = 'spkrepo.controllers.';
		
		$this->routingPrefix = 'spkrepo/';
		
	}
}
?>