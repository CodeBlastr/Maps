<?php
class MapsController extends MapsAppController {

	public $name = 'Maps';
	public $uses = 'Maps.Map';
	
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
		if (!empty($this->request->data)) {
			$this->request->data['Map']['user_id'] = $this->Auth->user('id');
			$this->Map->create();
			if ($this->Map->save($this->request->data)) {
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
		if(!empty($this->request->data)){
			$locations = $this->Map->find('all');
			if(!empty($locations))  {  	
				$this->set('locations', $locations);
				$this->set('search_locations', $this->request->data);
			}
			else {
				$locations_db = $this->Map->find('first'); 
				$search_location = $this->request->data;							
				$this->set('locations_db', $locations_db);
				$this->set('search_locations', $search_location);
			}
				
		} 
	}	
		
}
?>