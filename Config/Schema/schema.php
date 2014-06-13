<?php 
class MapsSchema extends CakeSchema {

	public $renames = array();

	public function before($event = array()) {
	    $db = ConnectionManager::getDataSource('default');
	    $db->cacheSources = false;
		App::uses('UpdateSchema', 'Model'); 
		$this->UpdateSchema = new UpdateSchema;
		$before = $this->UpdateSchema->before($event);
		return $before;
	}

	public function after($event = array()) {
		$this->_installData($event);
		$this->UpdateSchema->rename($event, $this->renames);
		$this->UpdateSchema->after($event);
	}

	public $maps = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'street' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'city' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'country' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'postal' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 25, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'marker_icon' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 25, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'marker_text' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'latitude' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'longitude' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'response' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'search_tags' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'creator_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'modifier_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	
/**
 * Install Data Method
 * 
 * @param string $event
 */
	protected function _installData($event) {
		if (isset($event['create'])) {
			switch ($event['create']) {
	            case 'maps':
	                $Model = ClassRegistry::init('Maps.Map');
					$Model->create();
					$Model->saveAll(array(
						'Map' => array(
							'street' => '400 Ocean View Blvd',
							'city' => 'San Diego',
							'state' => 'California',
							'country' => 'United States',
							'postal' => '92113',
							'marker_text' => '<h4>Customized</h4>This a custom marker to demo infowindow customization.',
							'latitude' => '32.53',
							'longitude' => '-117.112698',
							'search_tags' => 'san diego'
						)
					));
				break;
			}
		}
	}

}
