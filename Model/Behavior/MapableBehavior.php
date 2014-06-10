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
 * @param Model $Model
 * @param array $query
 * @return array
 * @todo optimize by flattening and searching for Alias.
 */
	public function beforeFind(\Model $Model, $query) {
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
 * After find callback
 *
 * Unserialize the response from Google Maps
 *
 * @param Model $Model
 * @param array $results
 * @param boolean $primary
 * @return array
 */
	public function afterFind(\Model $Model, $results, $primary = false) {
		// handles many
		for ($i = 0; $i < count($results); $i++) {
			if (!empty($results[$i]['Map']['response'])) {
				$results[$i]['Map']['response'] = unserialize($results[$i]['Map']['response']);
				$results[$i]['Map']['_address_components'] = Set::combine($results[$i]['Map']['response']['results'][0], 'address_components.{n}.types.0', 'address_components');
			}
		}
		// handles one
		if (!empty($results['Map']['response'])) {
			$results['Map']['response'] = unserialize($results['Map']['response']);
			$results['Map']['_address_components'] = Set::combine($results['Map']['response']['results'][0], 'address_components.{n}.types.0', 'address_components');
		}
		return $results;
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
	public function beforeSave(\Model $Model, $options = array()) {
		if (is_array($this->settings['addressField']) && !empty($this->settings['addressField'])) {
			// merge multiple address fields into a single field for mapping
			$Model->data[$Model->name]['_compiled'] = '';
			foreach ($this->settings['addressField'] as $field) {
				$Model->data[$Model->name]['_compiled'] .= $Model->data[$Model->name][$field] . ',';
			}
			$Model->data[$Model->name]['_compiled'] = rtrim($Model->data[$Model->name]['_compiled'], ',');
			$this->settings['addressField'] = '_compiled';
		}

		if (isset($Model->data[$Model->name][$this->settings['addressField']])) {
			$this->address = $Model->data[$Model->name][$this->settings['addressField']];
		}
		return true;
	}

    /**
     * @param array $response geo response
     * @param $default if default is not empty then use default
     * @param string $type must be one of those(case insensitive)
     * @param string $name can be only short_name or long_name
     * Street,
     * City,
     * State,
     * ZipCode or Postal
     * Country,
     * Formatted_Address, which is No. Street Name, City, State ZipCode, Country
     * @return \if|string $string
     */
    private function parseGEOResponse(array $response,$default,$type = 'Street',$name = 'long_name'){
        if(!empty($default)){
            return $default;
        }
        $name = strtolower($name);
        if(!in_array($name,array('long_name','short_name'))){
            $name = 'long_name';
        }
        $result = '';
        $compoment = $response['results'][0]['address_components'];
        $stateIndex = count($compoment) == 8 ? 5 : 4;
        $countryIndex = $stateIndex+1;
        switch(strtolower($type)){
            case 'street':
                $result = sprintf('%s %s',$compoment[0]['short_name'],$compoment[1][$name]);
                break;
            case 'city':
                $result =$compoment[2][$name];
                break;
            case 'state':
                $result =$compoment[$stateIndex][$name];
                break;

            case 'postal':
            case 'zipcode':
                $result = $compoment[count($compoment)-1];
                break;

            case 'country':
                $result = $compoment[$countryIndex][$name];
                break;
            case 'formatted_address':
                $result = $response['results'][0]['formatted_address'];
                break;

        }

        return $result;
    }
/**
 * After Save Callback
 *
 * @param Model $Model
 * @param bool $created
 */
	public function afterSave(\Model $Model, $created, $options = array()) {
		$this->Map->create();
		if ($response = $this->geocode($Model)) {
			$id = $this->Map->field('id', array('Map.foreign_key' => $Model->id, 'Map.model' => $Model->name));
			$id = !empty($id) ? $id : null;
			$data = array('Map' => array(
					'id' => $id,
					'foreign_key' => $Model->id,
					'model' => $Model->name,
					'street' => $this->parseGEOResponse($response,$Model->data[$Model->alias][$this->settings['streetField']],'street'),
					'city' => $this->parseGEOResponse($response,$Model->data[$Model->alias][$this->settings['cityField']],'city'),
					'state' => $this->parseGEOResponse($response,$Model->data[$Model->alias][$this->settings['stateField']],'state'),
					'country' => $this->parseGEOResponse($response,$Model->data[$Model->alias][$this->settings['countryField']],'country'),
					'postal' => $this->parseGEOResponse($response,$Model->data[$Model->alias][$this->settings['postalField']],'zipcode'),
					'marker_text' => $Model->data[$Model->alias][$this->settings['markerTextField']],
					'latitude' => $response['results'][0]['geometry']['location']['lat'],
					'longitude' => $response['results'][0]['geometry']['location']['lng'],
					'response' => serialize($response),
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
		$url = $this->settings['url'] . urlencode($this->address);
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
