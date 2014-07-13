<?php
App::uses('MapsAppModel', 'Maps.Model');

class Map extends MapsAppModel {

	public $name = 'Map';
	
	public $api = array(
		'url' => 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=',
		);

	public function beforeSave($created, $options = array()) {
		if (!empty($this->data[$this->alias])) {
			$address = $this->data[$this->alias]['address'] . ', ';
			$address .= $this->data[$this->alias]['street'] . ', ';
			$address .= $this->data[$this->alias]['city'] . ', ';
			$address .= $this->data[$this->alias]['state'] . ', ';
			$address .= $this->data[$this->alias]['country'] . ', ';
			$address .= $this->data[$this->alias]['postal'];
			if ($response = $this->geocode($address)) {
				$components = Set::combine($response['results'][0]['address_components'], '{n}.types.0', '{n}.long_name');
				$this->data[$this->alias]['street'] = !empty($this->data[$this->alias]['street']) ? $this->data[$this->alias]['street'] : $components['street_number'] . ' ' . $components['route'];
				$this->data[$this->alias]['city'] = !empty($this->data[$this->alias]['city']) ? $this->data[$this->alias]['city'] : $components['locality'];
				$this->data[$this->alias]['state'] = !empty($this->data[$this->alias]['state']) ? $this->data[$this->alias]['state'] : $components['administrative_area_level_1'];
				$this->data[$this->alias]['country'] = !empty($this->data[$this->alias]['country']) ? $this->data[$this->alias]['country'] : $components['country'];
				$this->data[$this->alias]['postal'] = !empty($this->data[$this->alias]['postal']) ? $this->data[$this->alias]['postal'] : $components['postal_code'];
				$this->data[$this->alias]['formatted'] = $response['results'][0]['formatted_address'];
				$this->data[$this->alias]['latitude'] = !empty($this->data[$this->alias]['is_manual']) ? $this->data[$this->alias]['latitude'] : $response['results'][0]['geometry']['location']['lat'];
				$this->data[$this->alias]['longitude'] = !empty($this->data[$this->alias]['is_manual']) ? $this->data[$this->alias]['longitude'] : $response['results'][0]['geometry']['location']['lng'];
				$this->data[$this->alias]['response'] = json_encode($response);
			}
		}
		// debug($this->data);
		// exit;
		return true;
	}

/**
 * After find callback
 *
 * Decode the response from Google Maps
 *
 * @param Model $Model
 * @param array $results
 * @param boolean $primary
 * @return array
 */
	public function afterFind($results, $primary = false) {
		// handles many
		for ($i = 0; $i < count($results); $i++) {
			if (!empty($results[$i]['Map']['response'])) {
				$results[$i]['Map']['response'] = json_decode($results[$i]['Map']['response'], true);
				$results[$i]['Map']['_address_components'] = Set::combine($results[$i]['Map']['response']['results'][0], 'address_components.{n}.types.0', 'address_components');
			}
		}
		// handles one
		if (!empty($results['Map']['response'])) {
			$results['Map']['response'] = json_decode($results['Map']['response'], true);
			$results['Map']['_address_components'] = Set::combine($results['Map']['response']['results'][0], 'address_components.{n}.types.0', 'address_components');
		}
		return $results;
	}

/**
 * Find method
 */
	public function find($type = 'first', $params = array()) {
		if (!empty($params['contain'])) {
			if($params['contain'] === '_auto' || array_search('_auto', $params['contain']) !== false) {
				if(is_string($params['contain'])) {
					$params['contain'] = array($params['contain']);
				}
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
		
		$coords = array(
			'minLat' => $this->minLatitude($currentLat, $radius),
			'maxLat' => $this->maxLatitude($currentLat, $radius),
			'minLong' => $this->minLongitude($currentLong, $radius),
			'maxLong' => $this->maxLongitude($currentLong, $radius));
		
		$query = "SELECT *, ( 3959 * acos( cos( radians({$currentLat}) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians({$currentLong}) ) + sin( radians({$currentLat}) ) * sin( radians( latitude ) ) ) ) AS distance FROM maps AS Map HAVING distance < {$radius} ORDER BY distance";
		 
		 $results = $this->query($query);
		 //$results = $this->find('all', array('conditions' => array('Map.latitude BETWEEN ? AND ?' => array($coords['minLat'], $coords['maxLat']), 
		 //			'Map.longitude BETWEEN ? and ?' => array($coords['maxLong'], $coords['minLong']))));
		 
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

/**
 * Geocode
 *
 * Get the latitude and longitude of an address
 */
	public function geocode($address = null) {
		$url = $this->api['url'] . urlencode($address);
		$resp_json = self::curl_file_get_contents($url);
		$resp = json_decode($resp_json, true);
		if ($resp['status'] = 'OK') {
			return $resp;
		} else {
			return false;
		}
	}

/**
 * Curl file get contents
 *
 * Curl to get the results of a url
 */
	static private function curl_file_get_contents($url) {
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_URL, $url);
		$contents = curl_exec($c);
		curl_close($c);

		if ($contents) {
			return $contents;
		} else {
			return false;
		}
	}
	

}