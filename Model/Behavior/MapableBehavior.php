<?php
App::uses('Map', 'Maps.Model');
	
class MapableBehavior extends ModelBehavior {

	public $settings = array(
		'url' => 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=',
		'nameField' => 'name',
		'markerTextField' => 'description',
		'addressField' => 'address'
		);

/**
 * Setup 
 * 
 * @param Model $Model
 * @param array $settings
 */
	public function setup(Model $Model, $settings = array()) {
		$this->settings = array_merge($this->settings, $settings);
		$this->Map = new Map;
	}
	
	
/**
 * Before find callback
 * 
 * Remove and save metaConditions for use in the afterFind
 * 
 * @param Model $Model
 * @param array $query
 * @return array
 * @todo optimize by flattening and searching for Alias.
 */
	public function beforeFind(Model $Model, array $query) {//die('x');break;
        $Model->bindModel(array(
        	'hasOne' => array(
				'Map' => array(
					'className' => 'Maps.Map',
					'foreignKey' => 'foreign_key',
                    'conditions' => array('Map.model' => $Model->name),
                    //'dependent' => false, // we'll manually handle deletes in afterDelete()
                    //'fields' => array('Meta.model', 'Meta.foreign_key', 'Meta.value')
					)
				)
			), false);
        $query['contain'][] = 'Map'; //$Model->contain('Meta');
       	return $query;
	}
	
	
/**
 * Before Save Callback
 * 
 */
 	public function beforeSave(Model $Model) {
		if ( isset($Model->data[$Model->name][$this->settings['addressField']]) ) {
			$this->address = $Model->data[$Model->name][$this->settings['addressField']];// original
		} elseif ( isset($Model->data[$Model->name]['Meta'][ltrim ($this->settings['addressField'],'!')]) ) {
			$this->address = $Model->data[$Model->name]['Meta'][ltrim ($this->settings['addressField'],'!')]; // support !location
		}
		return true;
 	}
	
/**
 * After Save Callback
 * 
 * @param Model $Model
 * @param bool $created
 */
	public function afterSave(Model $Model, $created) {
		if ($coords = $this->geocode($Model)) {
			$id = $this->Map->field('id', array('Map.foreign_key' => $Model->id, 'Map.model' => $Model->name));
			$id = !empty($id) ? $id : null;
			$data = array('Map' => array(
				'id' => $id,
				'name' => $Model->data[$Model->alias][$this->settings['nameField']],
				'model' => $Model->name,
				'foreign_key' => $Model->id,
				'latitude' => $coords['lat'],
				'longitude' => $coords['lng'], 
				'marker_text' => $Model->data[$Model->alias][$this->settings['markerTextField']]
				));
		}
		return !empty($data) ? $this->Map->save($data) : null;
	}
	
	
/**
 * Geocode
 * 
 * Get the latitude and longitude of an address
 */
	public function geocode(Model $Model) {
        $url = $this->settings['url'].urlencode($this->address);
       
        $resp_json = self::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);
        if ($resp['status']='OK'){
            return $resp['results'][0]['geometry']['location'];
        } else {
            return false;
        }
    }

/**
 * Curl file get contents
 *
 * Curl to get the results of a url
 */
    static private function curl_file_get_contents($url){
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