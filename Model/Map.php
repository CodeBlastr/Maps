<?php
App::uses('MapsAppModel', 'Maps.Model');

class Map extends MapsAppModel {

	public $name = 'Map'; 
	
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