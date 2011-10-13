<?php
Library::import('recess.framework.controllers.Controller');
require_once 'spkrepo-conf.php';
require_once "apps/spkrepo/models/Package.php";

/**
 * !RespondsWith Layouts
 * !Prefix Views: home/, Routes: /
 */
class SpkrepoHomeController extends Controller {
	
	public $spkDirFound;
	public $spkDirWritable;
	public $setupNeeded;
	public $spkDir;
	
	function setupNeeded() {
		// Assume that every thing is fine
		$this->spkDirFound = TRUE;
		$this->spkDirWritable = TRUE;
		$this->setupNeeded = FALSE;
		$this->spkDir = getcwd().'/'.SpkRepo::$spkDir;
		
		// Search for issues
		if (!is_dir($this->spkDir)) {
			$this->spkDirFound = FALSE;
			$this->setupNeeded = TRUE;
		} 
		if (!is_writable($this->spkDir)) {	
			$this->spkDirWritable = FALSE;
			$this->setupNeeded = TRUE;
		}
		return $this->setupNeeded;
	}

	/** !Route GET
	 *  !Route GET, /
	*/
	function index() {
		if ($this->setupNeeded()) {
			return $this->ok('setupError'); 
		} else {
			return $this->ok('welcome'); 
		}	
	}

	/** !Route GET, upload
	 */
	function uploadForm() {
		if ($this->setupNeeded()) {
			return $this->ok('setupError'); 
		} else {
			// The HTML form will be built using the uploadGet View
			return $this->ok('uploadForm');
		}
	}
	
	/** !Route POST, upload
	 */
	function uploadPost() {
		if ($this->setupNeeded()) {
			$this->error = "Server not set up correctly";
			return $this->ok('uploadError');
		} elseif (!isset ($_POST['publishingKey']) || $_POST['publishingKey'] != SpkRepo::$publishingKey) {
			$this->error = "Incorrect publishing key";
			return $this->ok('uploadError');
		} elseif (isset($_FILES['spk']['tmp_name']) && $_FILES['spk']['tmp_name'] != '') {
			if (FALSE /* Phar support for tar file */) {
				// Add tar suffix to the uploaded file, so it can be opened using phar:
				$inFileName= $_FILES['spk']['tmp_name'];
				$tmpFileName = $_FILES['spk']['tmp_name'].'.tar';
				move_uploaded_file($inFileName, $tmpFileName);
				$info = parse_ini_file ('phar://'.$tmpFileName.'/INFO');
				$destFileName = $_FILES['spk']['name'];
			} else {
				if (!isset($_FILES['info']['tmp_name']) || $_FILES['info']['tmp_name'] == '') {
					$this->error = "INFO not provided";
					return $this->ok('uploadError');
				}
				$tmpFileName = $_FILES['spk']['tmp_name'];
				$destFileName = $_FILES['spk']['name'];
				$info = parse_ini_file($_FILES['info']['tmp_name']);
			}
			
			// Add the package to the database
			insertPackage($info, $tmpFileName, $destFileName);
			$this->info = $info;
			return $this->ok('uploadDone');
		} else {
			$this->error = "Can't open SPK (upload error)";
			return $this->ok('uploadError');
		}
	}

	/** !Route POST, packages
	 */
	function packages() {	
		$this->params = $_POST;
		return $this->ok('packages');
	}
		
	/** !Route GET, packages
	 */
	function viewPackages() {
		return $this->ok('viewPackages');
	}
}
?>
