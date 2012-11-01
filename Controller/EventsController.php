<?php
/**
 * Events Controller
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
App::uses('EventsAppController', 'EventManager.Controller');
class EventsController extends EventsAppController
{
/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'Events';
/**
 * Helpers
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'EventManager.Image'
	);
/**
 * beforeFilter
 *
 * @return void
 * @access public
 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Security->unlockedFields += array('with_photo');
		if (!isset($this->params['requested'])) {
			$this->FileUpload = $this->Components->load('EventManager.FileUpload');
			$this->FileUpload->initialize($this, array('path' => '/upload/events/'));
		}
	}

/**
 * admin_index
 *
 * @return void
 */
	public function admin_index()
	{
		$this->Event->recursive = -1;
		$this->paginate = array(
			'limit' => 10,
			'order' => 'Event.date DESC'
		);
		$this->set('events', $this->paginate());
		$this->set('title_for_layout', __d('event_manager', 'List of events'));
	}

/**
 * admin_add
 *
 * @return void
 */
	public function admin_add()
	{
		if ($this->request->is('post')) {
			if ($this->request->data['Event']['with_photo'] == 1) {
				if (isset($this->request->data['Event']['photo'])) {
					$file = $this->request->data['Event']['photo'];
					$this->request->data['Event']['photo'] = $this->FileUpload->getFileName($file);
					if (!$this->request->data['Event']['photo']) {
						unset($file, $this->request->data['Event']['photo']);
					}
				}
			} else {
				$this->request->data['Event']['photo'] = '';
			}
			$this->Event->create();
			if ($this->Event->save($this->request->data)) {
				if (isset($file) && $this->request->data['Event']['with_photo'] == 1) {
					$this->FileUpload->upload($file);
				}
				$this->Session->setFlash(__d('event_manager', 'Event is saved.'));
				if (isset($this->request->data['apply'])) {
					$this->redirect(array('action' => 'add'));
				} else {
					$this->redirect(array('action' => 'index'));
				}
			} else {
				$this->Session->setFlash(__d('event_manager', 'Event could not be saved. Please try again.'));
			}
		}
		$this->set('title_for_layout', __d('event_manager', 'Add event'));
		$this->render('admin_form');
	}

/**
 * admin_edit
 *
 * @param int $id Event id
 * @return void
 */
	public function admin_edit($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid event.'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->request->data['Event']['with_photo'] == 1) {
				if (isset($this->request->data['Event']['photo'])) {
					$file = $this->request->data['Event']['photo'];
					$this->request->data['Event']['photo'] = $this->FileUpload->getFileName($file);
					if (!$this->request->data['Event']['photo']) {
						unset($file, $this->request->data['Event']['photo']);
					}
				}
			} else {
				$this->request->data['Event']['photo'] = '';
			}
			$this->request->data['Event']['id'] = $id;
			if ($this->Event->save($this->request->data)) {
				if ($this->request->data['Event']['with_photo'] == 1) {
					if (isset($file)) {
						$this->FileUpload->upload($file, $this->request->data['Event']['photo_old']);
					}
				} else {
					$this->FileUpload->delete($this->request->data['Event']['photo_old']);
				}
				$this->Session->setFlash(__d('event_manager', 'Event is saved.'));
				if (isset($this->request->data['apply'])) {
					$this->redirect(array('action' => 'edit', $id));
				} else {
					$this->redirect(array('action' => 'index'));
				}
			} else {
				$this->Session->setFlash(__d('event_manager', 'Event could not be saved. Please try again.'));
			}
		} else {
			$this->request->data = $this->Event->read(null, $id);
		}

		$this->set('title_for_layout', __d('event_manager', 'Edit event'));
		$this->render('admin_form');
	}

/**
 * admin_delete
 *
 * @return void
 */
	public function admin_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__d('event_manager', 'Invalid ID.'));
			$this->redirect(array('action' => 'index'));
		}
		$event = $this->Event->findById($id);
		if ($this->Event->delete($id, true)) {
			$this->FileUpload->delete($event['Event']['photo']);
			$this->Session->setFlash(__d('event_manager', 'Event is deleted, and whole directory with images.'));
			$this->redirect(array('action' => 'index'));
		}
		$this->render(false);
	}

/**
 * index
 *
 * @return void
 */
	public function index()
	{
		$this->paginate = array(
			'conditions' => array(
				'Event.status' => 1,
				'DATE_FORMAT(Event.date, \'%Y-%m-%d\') >= \''.date('Y-m-d').'\''
			),
			'order' => 'Event.date DESC',
			'limit' => Configure::read('EventManager.limit_pagination_view')
		);
		if (isset($this->params['requested'])) {
			$this->paginate['limit'] = Configure::read('EventManager.limit_pagination_element');
			return $this->paginate();
		}
		$this->set('events', $this->paginate());
		$this->set('title_for_layout', __('Event list'));
	}

/**
 * view
 *
 * @param string $slug Event slug
 * @return void
 */
	public function view($slug)
	{
		$event = $this->Event->find('first', array(
			'conditions' => array('Event.slug' => $slug, 'Event.status' => 1)
		));
		if (isset($this->params['requested'])) {
			return $event;
		}
		if (empty($event)) {
			$this->Session->setFlash(__('Invalid content'), 'default', array('class' => 'error'));
			$this->redirect('/');
		}
		$this->set(compact('event'));
	}

}
