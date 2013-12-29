<?php
class MapsController extends MapsAppController {

	public $name = 'Maps';

	public $uses = 'Maps.Map';
	
	public function index() {
		$this->Map->recursive = 0;
		$this->set('locations', $this->paginate());
		$this->set('page_title_for_layout', 'Locations');
		$this->set('title_for_layout', 'Locations');
	}

	public function view($id = null) {
		$this->Map->id = $id;
		if (!$this->Map->exists()) {
			throw new NotFoundException(__('Map not found'));
		}
		$this->set('map', $this->request->data = $this->Map->read());
		$this->set('page_title_for_layout', $this->request->data['Map']['street']);
		$this->set('title_for_layout', $this->request->data['Map']['street']);
		return $this->request->data;
	}
	
	public function nearby($currentLat = null, $currentLong = null, $radius = 1){
		$this->Map->recursive = 0;
	    $locations = $this->Map->findLocation($currentLat, $currentLong, $radius);
		if ($this->request->is('ajax')){
			$this->response->body(json_encode($locations));
			return $this->response;
		} else{
			$this->set('locations', $locations);
		}

	}

	public function add() {
		if (!empty($this->request->data)) {
			$this->request->data['Map']['user_id'] = $this->Auth->user('id');
			$this->Map->create();
			if ($this->Map->save($this->request->data)) {
				$this->flash(__('Location saved.', true), array('action'=>'index'));
				$this->redirect(array('action' => 'search'));
			}
		}
		$this->set('page_title_for_layout', 'Add Location');
		$this->set('title_for_layout', 'Add Location');
	}

	public function edit($id = null) {
		$this->Map->id = $id;
		if (!$this->Map->exists()) {
			throw new NotFoundException(__('Map not found'));
		}
		if (!empty($this->request->data)) {
			$this->request->data['Map']['user_id'] = $this->Auth->user('id');
			$this->Map->create();
			if ($this->Map->save($this->request->data)) {
				$this->flash(__('Location saved.', true), array('action'=>'index'));
				$this->redirect(array('action' => 'search'));
			}
		}
		$this->set('map', $this->request->data = $this->Map->read());
		$this->set('page_title_for_layout', 'Edit ' . $this->request->data['Map']['street']);
		$this->set('title_for_layout', 'Edit ' . $this->request->data['Map']['street']);
		return $this->request->data;
	}

	public function delete($id = null) {
		$this->Map->id = $id;
		if (!$this->Map->exists()) {
			throw new NotFoundException(__('Map not found'));
		}
		if ($this->Map->delete($id)) {
			$this->Session->setFlash(__('Location deleted')); 
			$this->redirect(array('action'=>'index'));
		}
	}
	
	public function search() {
		if(!empty($this->request->query['q'])){
			$query = $this->request->query['q'];
			
			$locations = $this->Map->find('all', array(
					'conditions' => array(
						'OR' => array(
							'Map.search_tags LIKE' => "%$query%",
							'Map.postal LIKE' => "%$query%",
							'Map.street LIKE' => "%$query%",
							'Map.city LIKE' => "%$query%",
							'Map.country LIKE' => "%$query%",			
						)
					),
					'contain' => array('_auto'),
			));
			if(!empty($locations))  {  	
				$this->set('locations', $locations);
				$this->set('search_locations', $this->request->data);
			} else {
				$locations_db = $this->Map->find('first'); 
				$search_location = $this->request->data;							
				$this->set('locations_db', $locations_db);
				$this->set('search_locations', $search_location);
			}
		}
		
		$this->set('page_title_for_layout', 'Location Search');
		$this->set('title_for_layout', 'Location Search');
	}	
		
}