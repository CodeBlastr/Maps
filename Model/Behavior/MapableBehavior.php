<?php
App::uses('Map', 'Maps.Model');
	
class MapableBehavior extends ModelBehavior {
	

	public $settings = array(
		'url' => 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=',
		'streetField' => 'street',
		'cityField' => 'city',
		'stateField' => 'state',
		'countryField' => 'country',
		'postalField' => 'zip',
		'addressField' => 'address',
		'markerTextField' => 'description',
		'searchTagsField' => 'description'
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
	public function beforeFind(Model $Model, array $query) {
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
 * $this->actsAs['Maps.Mapable'] = array(
		'modelAlias' => 'Campaign',
		'markerTextField' => 'description',
		'streetField' => 'address_1',
		'cityField' => 'city',
		'stateField' => 'state',
		'countryField' => null,
		'postalField' => 'zip',
		'addressField' => array('address_1', 'address_2', 'city', 'state', 'zip'),
		'markerTextField' => 'description',
		'searchTagsField' => 'description'
	);
 */
 	public function beforeSave(Model $Model) {
 		if (is_array($this->settings['addressField']) && !empty($this->settings['addressField'])) {
 			// merge multiple address fields into a single field for mapping
 			$Model->data[$Model->name]['_compiled'] = '';
 			foreach ($this->settings['addressField'] as $field) {
 				$Model->data[$Model->name]['_compiled'] .= $Model->data[$Model->name][$field] . ',';
 			}
 			$Model->data[$Model->name]['_compiled'] = rtrim ( $Model->data[$Model->name]['_compiled'], ',' );
 			$this->settings['addressField'] = '_compiled';
 		}
		
		if (isset($Model->data[$Model->name][$this->settings['addressField']])) {
			$this->address = $Model->data[$Model->name][$this->settings['addressField']];
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
		$this->Map->create();
		if ($coords = $this->geocode($Model)) {
			$id = $this->Map->field('id', array('Map.foreign_key' => $Model->id, 'Map.model' => $Model->name));
			$id = !empty($id) ? $id : null;
			$data = array('Map' => array(
				'id' => $id,
				'foreign_key' => $Model->id,
				'model' => $Model->name,
				'street' => $Model->data[$Model->alias][$this->settings['streetField']],
				'city' => $Model->data[$Model->alias][$this->settings['cityField']],
				'state' => $Model->data[$Model->alias][$this->settings['stateField']],
				'country' => $Model->data[$Model->alias][$this->settings['countryField']],
				'postal' => $Model->data[$Model->alias][$this->settings['postalField']],
				'marker_text' => $Model->data[$Model->alias][$this->settings['markerTextField']],
				'latitude' => $coords['lat'],
				'longitude' => $coords['lng'],
				'search_tags' => $Model->data[$Model->alias][$this->settings['searchTagsField']]
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