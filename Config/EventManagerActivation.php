<?php
/**
 * EventManager Activation
 *
 * Activation class for Example plugin.
 * This is optional, and is required only if you want to perform tasks when your plugin is activated/deactivated.
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
class EventManagerActivation
{
/**
 * onActivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
	public function beforeActivation(&$controller)
	{
		$sql = file_get_contents(APP.'Plugin'.DS.'EventManager'.DS.'Config'.DS.'event_manager.sql');
		if (!empty($sql)) {
			App::import('Model', 'ConnectionManager');
			$db = ConnectionManager::getDataSource('default');
			$statements = explode(';', $sql);
			foreach ($statements as $statement) {
				if (trim($statement) != '') {
					$db->query($statement);
				}
			}
		}
		if (is_dir(APP.'webroot'.DS.'uploads'.DS) && !is_dir(APP.'webroot'.DS.'uploads'.DS.'events'.DS)) {
			umask(0);
			mkdir(APP.'webroot'.DS.'uploads'.DS.'events', 0777);
			umask(0);
			mkdir(APP.'webroot'.DS.'uploads'.DS.'events'.DS.'resized', 0777);
		}
		return true;
	}

/**
 * Called after activating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
	public function onActivation(&$controller)
	{
		// ACL: set ACOs with permissions
		$controller->Croogo->addAco('Events');
		$controller->Croogo->addAco('Events/admin_index');
		$controller->Croogo->addAco('Events/admin_add');
		$controller->Croogo->addAco('Events/admin_edit');
		$controller->Croogo->addAco('Events/index', array('registered', 'public'));
		$controller->Croogo->addAco('Events/view', array('registered', 'public'));

		$controller->Setting->write('EventManager.limit_pagination_view', '10', array('editable' => 1, 'title' => __d('event_manager', 'Events per page in a view')));
		$controller->Setting->write('EventManager.limit_pagination_element', '4', array('editable' => 1, 'title' => __d('event_manager', 'Events per page in a element')));
		$controller->Setting->write('EventManager.disabled_css', false, array('editable' => 1, 'title' => __d('event_manager', 'Use external css'), 'input_type' => 'checkbox'));
	}

/**
 * onDeactivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
	public function beforeDeactivation(&$controller)
	{
		$sql = file_get_contents(APP.'Plugin'.DS.'EventManager'.DS.'Config'.DS.'event_manager_deactivate.sql');
		if (!empty($sql)) {
			App::import('Model', 'ConnectionManager');
			$db = ConnectionManager::getDataSource('default');
			$statements = explode(';', $sql);
			foreach ($statements as $statement) {
				if (trim($statement) != '') {
					$db->query($statement);
				}
			}
		}
		if (is_dir(APP.'webroot'.DS.'uploads'.DS.'events'.DS)) {
			$this->__recursiveDelete(APP.'webroot'.DS.'uploads'.DS.'events'.DS);
		}
		return true;
	}

/**
 * Called after deactivating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
	public function onDeactivation(&$controller)
	{
		// ACL: remove ACOs with permissions
		$controller->Croogo->removeAco('Events'); // EventsController ACO and it's actions will be removed
	}

 /**
  * Delete a file or recursively delete a directory
  *
  * @param string $str Path to file or directory
  * @access private
  */
	private function __recursiveDelete($str)
	{
		if (is_file($str)) {
			return @unlink($str);
		} elseif (is_dir($str)) {
			$scan = glob(rtrim($str, '/') . '/*');
			foreach ($scan as $index => $path) {
				$this->__recursiveDelete($path);
			}
			return @rmdir($str);
		}
	}
}
