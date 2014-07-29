<?php

App::uses('Map', 'Maps.Model');

class MapableBehavior extends ModelBehavior {

	public $settings = array(
		//'url' => 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=',
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
 * After Save Callback
 *
 * @param Model $Model
 * @param bool $created
 */
	public function afterSave(\Model $Model, $created, $options = array()) {
		$id = $this->Map->field('id', array('Map.foreign_key' => $Model->id, 'Map.model' => $Model->name));
		$id = !empty($id) ? $id : null;
		$data = array(
			'Map' => array(
				'id' => $id,
				'foreign_key' => $Model->id,
				'model' => $Model->name,
				'street' => $this->parseAddress('street',$Model->data[$Model->alias][$this->settings['streetField']]),
				'city' => $this->parseAddress('city',$Model->data[$Model->alias][$this->settings['cityField']]),
				'state' => $this->parseAddress('state',$Model->data[$Model->alias][$this->settings['stateField']]),
				'country' => $this->parseAddress('country',$Model->data[$Model->alias][$this->settings['countryField']]),
				'postal' => $Model->data[$Model->alias][$this->settings['postalField']],
				'marker_text' => $Model->data[$Model->alias][$this->settings['markerTextField']],
				//'latitude' => $response['results'][0]['geometry']['location']['lat'],
				//'longitude' => $response['results'][0]['geometry']['location']['lng'],
				//'response' => serialize($response),
				'search_tags' => $Model->data[$Model->alias][$this->settings['searchTagsField']]
				)
			);
		$this->Map->create();
		return $this->Map->save($data);
	}

    private function parseAddress($key,$value){
        $parts = explode(',',$this->address);
        if(!empty($parts) && empty($value)){
            switch($key){
                case 'street': $value = $parts[0];break;
                case 'city' : $value =  $parts[1]; break;
                case 'state' : $value =  $parts[2]; break;
                case 'country' : $value =  'United States'; break;
            }
        }

        return trim($value);
    }

}
