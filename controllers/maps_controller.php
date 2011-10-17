<?php
class MapsController extends MapsAppController {

	var $name = 'Maps';
	
	function index() {
		$this->Map->recursive = 0;
		$this->set('locations', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Location', true));
			$this->redirect(array('action' => 'index'));
		}
	}

	function add() {
		if (!empty($this->data)) {
			$this->data['Map']['user_id'] = $this->Auth->user('id');
			$this->Map->create();
			if ($this->Map->save($this->data)) {
				$this->flash(__('Location saved.', true), array('action'=>'index'));
				$this->redirect(array('action' => 'index'));
			} else {
			}
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->flash(__('Invalid Location', true), array('action'=>'index'));
		}
		if ($this->Invoice->delete($id)) {
			$this->flash(__('Location deleted', true), array('action'=>'index'));
		}
	}
	
	function search(){
		if(!empty($this->data)){
			$locations = $this->Map->find('all');
			if(!empty($locations))  {  	
				$this->set('locations', $locations);
				$this->set('search_locations', $this->data);
			}
			else {
				$locations_db = $this->Map->find('first'); 
				$search_location = $this->data;							
				$this->set('locations_db', $locations_db);
				$this->set('search_locations', $search_location);
			}
				
		} 
	}
	
	function admin_index() {
		$this->Map->recursive = 0;
		$this->set('locations', $this->paginate());
	}
	
		
}
?>