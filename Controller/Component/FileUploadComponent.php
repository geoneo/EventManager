<?php
/**
 * FileUpload Component
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Ivan Mattoni <iwmattoni@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     https://github.com/geoneo/EventManager.git
 */
class FileUploadComponent extends Component
{
	public $settings;

/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param object $controller Controller with components to startup
 * @return void
 */
	/*public function startup(Controller $controller) {
		$controller->set('exampleComponent', 'ExampleComponent startup');
	}*/
/**
 * The initialize method is called before the controller’s beforeFilter method.
 *
 * @param object $controller Controller with components to startup
 * @param array $settings
 * @return void
 */
	public function initialize(Controller $controller, $settings = array())
	{
		$_settings = array(
			'path' => '/uploads' . DS
		);
		$this->settings = Set::merge($_settings, $settings);
		$this->settings['path_full'] = WWW_ROOT . substr($this->settings['path'], 1);
		define('PATH_FULL_UPLOAD',  $this->settings['path_full']);
		define('PATH_UPLOAD',  $this->settings['path']);
	}

/**
 * Upload file
 *
 * @param array $file
 * @param array $filename_old
 * @access public
 * @return booelan
 */
	public function upload($file, $filename_old = null)
	{
		if (!is_array($file)) {
			return false;
		}
		$filename = $this->getFileName($file);
		if ($filename) {
			move_uploaded_file($file['tmp_name'], $this->settings['path_full'] . $filename);
			if (!empty($filename_old)) {
				$src = $this->settings['path_full'] . $filename_old;
				if (file_exists($src)) {
					unlink($src);
				}
			}
			return true;
		}
		return false;
	}

/**
 * Remove a file
 *
 * @param string $filename
 * @access public
 * @return boolean
 */
	public function delete($filename)
	{
		if (empty($filename)) {
			return false;
		}
		$src = $this->settings['path_full'] . $filename;
		if (file_exists($src)) {
			return @unlink($src);
		}
		return false;
	}

/**
 * Get file name
 *
 * @param array $file
 * @access public
 * @return mixed (string|boolean)
 */
	public function getFileName($file)
	{
		if (!is_array($file)) {
			return false;
		}
		if (!empty($this->filename)) {
			return $this->filename;
		}
		if (is_uploaded_file($file['tmp_name'])) {
			$pathinfo = pathinfo($file['name']);
			$this->filename = md5($pathinfo['filename']) . '.' . $pathinfo['extension'];
			return $this->filename;
		} else {
			return false;
		}
	}

}
