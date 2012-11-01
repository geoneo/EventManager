<?php
/**
 * Event
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
App::uses('EventAppModel', 'EventManager.Model');
class Event extends EventAppModel
{
/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */	
	public $actsAs = array(
		'EventManager.Sluggable' => array(
			'overwrite' => true,  // If true, slugs should be re-generated when updating, false otherwise.
			'separator' => '-',
			'label' => 'title'
		),
	);
/**
 * beforeValidate callback
 *
 * @return boolean
 */
	public function beforeValidate()
	{
		$this->validate = array(
			'title' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __d('event_manager', 'val_not_empty'),
				),
			),
			'body' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __d('event_manager', 'val_not_empty'),
				),
			)
		);
		parent::beforeValidate();
		return true;
	}
/**
 * beforeSave callback
 *
 * @return boolean
 */
	public function beforeSave()
	{
		return true;
	}

}
