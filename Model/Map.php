<?php
App::uses('MapsAppModel', 'Maps.Model');

class Map extends MapsAppModel {

	public $name = 'Map'; 
	
	
	public function find($type = 'first', $params = array()) {
		if (!empty($params['contain']) && is_array($params['contain'])) {
			if(array_search('_auto', $params['contain']) !== false) {
				//Passes params to autobind.
				$params = $this->_autoBind($params);
			}
		}
		
		return parent::find($type, $params);
	}
	
	/**
	 * This is another version of autobind. This is more of an all or nothing approach
	 * this does add another query and could cause some memory and performance issues
	 * params can be passed in the contain array
	 * 
	 * @param $params = find $params
	 * @return $params
	 */
	protected function _autoBind($params) {
		$associations = $this->find('all', array(
				'fields' => array('DISTINCT model'),
		));
		if($associations) {
			$associations = Hash::extract($associations, '{n}.{s}.model');
			foreach ($associations as $association) {
				$this->bindModel(array('belongsTo' => array($association => array('foreignKey' => 'foreign_key'))));
				if(!isset($params['contain'][$association])) {
					$params['contain'][] = $association;
				}
			}
		}
		unset($params['contain'][array_search('_auto', $params['contain'])]);
		return $params;
	}
	
	
	public function findLocation($currentLat, $currentLong, $radius){
		
		$coords = array('minLat' => $this->minLatitude($currentLat, $radius), 
				'maxLat' => $this->maxLatitude($currentLat, $radius),
				'minLong' => $this->minLongitude($currentLong, $radius),
				'maxLong' => $this->maxLongitude($currentLong, $radius));
			
		 $results = $this->find('all', array('conditions' => array('Map.latitude BETWEEN ? AND ?' => array($coords['minLat'], $coords['maxLat']), 
					'Map.longitude BETWEEN ? and ?' => array($coords['maxLong'], $coords['minLong']))));
		 return $results;
			
	}
	
	public function minLatitude($currentLat, $radius){
		//45 - (0.009 * 2) = $minLat
		$minLat = $currentLat - (0.009 * $radius);
		return $minLat;
	}
	
	public function maxLatitude($currentLat, $radius){
		//45 + (0.009 * 2) = $maxLat
		// $radius = 2; //miles
		// $currentLat = 37.386339;
		$maxLat = $currentLat + (0.009 * $radius);
		return $maxLat;
	}
	
	public function minLongitude($currentLong, $radius){
		//45 - (0.0125 * 2) = $minLong
		$minLong = $currentLong - (0.0125 * $radius);
		return $minLong;
	}
	
	public function maxLongitude($currentLong, $radius){
		//45 + (0.0125 * 2) = $maxLong
		$maxLong = $currentLong + (0.0125 * $radius);
		return $maxLong;
	}
	

}