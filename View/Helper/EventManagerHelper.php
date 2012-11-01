<?php
/**
 * EventManager Helper
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
class EventManagerHelper extends AppHelper
{
/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'Layout',
	);

/**
 * Before render callback. Called before the view file is rendered.
 *
 * @return void
 */
	/*public function beforeRender($viewFile) {
	}*/

/**
 * After render callback. Called after the view file is rendered
 * but before the layout has been rendered.
 *
 * @return void
 */
	public function afterRender($viewFile)
	{
		if (isset($this->_View) && !Configure::read('EventManager.disabled_css')) {
			echo $this->_View->element('includes', array(), array('plugin' => 'event_manager'));
		}
	}

/**
 * Before layout callback. Called before the layout is rendered.
 *
 * @return void
 */
	/*public function beforeLayout($layoutFile) {
	}*/

/**
 * After layout callback. Called after the layout has rendered.
 *
 * @return void
 */
	/*public function afterLayout($layoutFile) {
	}*/

/**
 * Called after LayoutHelper::setNode()
 *
 * [EventManager:index]
 * [EventManager:view(slug)]
 * [EventManager:index|view(slug):element]
 *
 * @return void
 */
	public function afterSetNode()
	{
		// field values can be changed from hooks
		$body = $this->Layout->node('body');
		preg_match('/\[EventManager:(?<action>index|view\([a-z0-9_-]+\))(:(?<element>[a-z0-9_-]+))?\]/', $body, $matches);
		if (!empty($matches)) {
			$options = array('plugin' => 'event_manager', 'cache' => array('key' => 'eventmanager_'.$matches['action'], 'time' => '5 mins'));
			$data = array();
			if ($matches['action'] == 'index') {
				$element = 'event_index';
			} else {
				preg_match('/view\((?<slug>.*)\)/', $matches['action'], $slug);
				if (!empty($slug['slug'])) {
					$data['slug'] = $slug['slug'];
					$element = 'event_view';
				} else {
					return;
				}
			}
			if (!empty($matches['element'])) {
				$element = $matches['element'];
				unset($options['plugin']);
			}
			$out = $this->_View->element($element, $data, $options);
			$value = preg_replace('/\[EventManager:.*\]/', $out, $body);
			$this->Layout->setNodeField('body', $value);
		}
	}

/**
 * Get formatted date
 *
 * @param string $format
 * @param string $date
 * @access public
 */
	public function getDate($format, $date)
	{
		preg_match_all('/(%[a-zA-Z])/', $format, $matches);
		$time = strtotime($date);
		$month = date('n', $time);
		if ($month == 1) {
			$month = __('January');
		} elseif ($month == 2) {
			$month = __('February');
		} elseif ($month == 3) {
			$month = __('March');
		} elseif ($month == 4) {
			$month = __('April');
		} elseif ($month == 5) {
			$month = __('May');
		} elseif ($month == 6) {
			$month = __('June');
		} elseif ($month == 7) {
			$month = __('July');
		} elseif ($month == 8) {
			$month = __('Agust');
		} elseif ($month == 9) {
			$month = __('September');
		} elseif ($month == 10) {
			$month = __('October');
		} elseif ($month == 11) {
			$month = __('November');
		} elseif ($month == 12) {
			$month = __('December');
		}
		foreach ($matches as $match) {
			foreach ($match as $f) {
				if ($f == '%F') {
					$value = $month;
				} elseif ($f == '%D') {
					$value = substr($month, 0, 3);
				} else {
					$value = date(substr($f, 1), $time);
				}
				$format = str_replace($f, $value, $format);
			}
		}
		return $format;
	}

/**
 * Called before LayoutHelper::nodeInfo()
 *
 * @return string
 */
	/*public function beforeNodeInfo() {
		return '<p>beforeNodeInfo</p>';
	}*/

/**
 * Called after LayoutHelper::nodeInfo()
 *
 * @return string
 */
	/*public function afterNodeInfo() {
		return '<p>afterNodeInfo</p>';
	}*/

/**
 * Called before LayoutHelper::nodeBody()
 *
 * @return string
 */
	/*public function beforeNodeBody() {
		return '<p>beforeNodeBody</p>';
	}*/

/**
 * Called after LayoutHelper::nodeBody()
 *
 * @return string
 */
	/*public function afterNodeBody() {
		return '<p>afterNodeBody</p>';
	}*/

/**
 * Called before LayoutHelper::nodeMoreInfo()
 *
 * @return string
 */
	/*public function beforeNodeMoreInfo() {
		return '<p>beforeNodeMoreInfo</p>';
	}*/

/**
 * Called after LayoutHelper::nodeMoreInfo()
 *
 * @return string
 */
	/*public function afterNodeMoreInfo() {
		return '<p>afterNodeMoreInfo</p>';
	}*/
}
