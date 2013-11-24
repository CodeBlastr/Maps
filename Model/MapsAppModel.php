<?php
App::uses('AppModel', 'Model');

class MapsAppModel extends AppModel {
	
/**
 * Menu Init method
 * Used by WebpageMenuItem to initialize when someone creates a new menu item for the users plugin.
 * 
 */
 	public function menuInit($data = null) {
 		// link to users index, login, register, and my
		$data['WebpageMenuItem']['item_url'] = '/maps/maps/search';
		$data['WebpageMenuItem']['item_text'] = 'Maps Search';
		$data['WebpageMenuItem']['name'] = 'Maps Search';
 		return $data;
 	}

}