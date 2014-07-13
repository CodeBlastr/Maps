<?php

class AppMapsController extends MapsAppController {

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
	
	public function nearby($currentLat = null, $currentLong = null, $radius = 1) {
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
		if(!empty($this->request->query['q'])) {
			$query =  array_values(array_filter(preg_split("/( |,)/", $this->request->query['q'])));
			$states = states();
			for ($i=0; $i < count($query); $i++) {
				$or['OR'][$i]['OR']['Map.marker_text LIKE'] = '%' . $query[$i] . '%';
				$or['OR'][$i]['OR']['Map.search_tags LIKE'] = '%' . $query[$i] . '%';
				$or['OR'][$i]['OR']['Map.postal LIKE'] = '%' . $query[$i] . '%';
				$or['OR'][$i]['OR']['Map.state LIKE'] = '%' . $query[$i] . '%';
				$or['OR'][$i]['OR']['Map.street LIKE'] = '%' . $query[$i] . '%';
				$or['OR'][$i]['OR']['Map.city LIKE'] = '%' . $query[$i] . '%';
				$or['OR'][$i]['OR']['Map.country LIKE'] = '%' . $query[$i] . '%';
			}
			if ($abbr = array_search($this->request->query['q'], $states)) {
				$or['OR'][$i]['OR']['Map.state LIKE'] = '%' . $abbr . '%' ;
			}
			$locations = $this->Map->find('all', array('conditions' => $or, 'contain' => array('_auto')));
		}
		if(!empty($locations))  {  	
			$this->set('locations', $locations);
			$this->set('search_locations', $this->request->data);
		} else {
			//left in case it needs to come back 6/29/2014 rk
			//$locations_db = $this->Map->find('all', array('limit' => 100)); 
			//$search_location = $this->request->data						
			//$this->set('locations_db', $locations_db);
			$this->set('locations', $locations = $this->Map->find('all', array('limit' => 250)));
			$this->set('search_locations', $this->request->data);
		}
		
		$this->set('page_title_for_layout', 'Location Search');
		$this->set('title_for_layout', 'Location Search');
	}	
		
}

if (!isset($refuseInit)) {
	class MapsController extends AppMapsController {
	}

}