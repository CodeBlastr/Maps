<?php
App::uses('Map', 'Maps.Model');

/**
 * MapTest Case
 *
 */
class MapTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
        'plugin.Maps.Map'
        );

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Map = ClassRegistry::init('Maps.Map');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Map);

		parent::tearDown();
	}
    
    
	public function testSave() {
		$data = array(
             'Location' => array(
                'name' => 'Frankie Johnnie & Luigo Too',
                'latitude' => '37.386339', 
                'longitude' => '-122.085823', 
                'city' => 'Mountain View', 
                'state' => 'California'
                )
            );
       $result = $this->Map->saveAll($data);
       $this->assertTrue(!empty($this->Map->id));
	}

/**
 *  find latitude and longitude within a set radius
 */
	// public function testDistance(){
		// $distance = 0.5;
		// $lat =  37.386339;
		// $long = -122.085823;
// 		
		 // $query = SELECT * FROM maps
			// WHERE ACOS( SIN( RADIANS( $lat ) ) * SIN( RADIANS(  `latitude` ) ) + COS( RADIANS( $lat ) ) * 
			// COS( RADIANS(  `latitude` ) ) * COS( RADIANS(  `longitude` ) - ( RADIANS( $long ) ) ) ) *3959 <= $distance;
// 													
		   // $data = array(
             // 'Location' => array(
                 // 'name' => 'Frankie Johnnie & Luigo Too',
                 // 'latitude' => '37.386339', 
                 // 'longitude' => '-122.085823', 
                 // 'city' => 'Mountain View', 
                 // 'state' => 'California'
                 // )
             // );
// 
// 		
//  		
	 // }

/**
 * Find a location within our givin radius and which is whithin our min/max latitude and longitude points
 */
 
 	public function testFindLocation(){
 		$data = array( 
			array(
			 	'Map' => array(
	                'marker_text' => 'Pizzeria Serio',
	                'latitude' => '41.939996', 
	                'longitude' => '-87.67149', 
	                'city' => 'Chicago', 
	                'state' => 'IL'
                )),
             array(
	             'Map' => array(
	                'marker_text' => 'Late Night Thai',
	                'latitude' => '41.940204', 
	                'longitude' => '-87.670686', 
	                 'city' => 'Chicago', 
	                'state' => 'IL'
                )),
              array(
	             'Map' => array(
	                'marker_text' => 'The Pony',
	                'latitude' => '41.940052', 
	                'longitude' => '-87.67016', 
	                 'city' => 'Chicago', 
	                'state' => 'IL'
                )),
             array(
	             'Map' => array(
	                'name' => 'Tony & Albas Pizza & Pasta',
	                'latitude' => '37.394339', 
	                'longitude' => '-122.080823', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                ))
			
		);
		$radius = 1;
		$currentLat = 41.939996;
		$currentLong = -87.67149;
		
		$this->Map->saveAll($data);
		$return = $this->Map->findLocation($currentLat, $currentLong, $radius);
		//moved to the Map model
		// $display = $this->Map->find('all', array('conditions' => array('Map.latitude BETWEEN ? AND ?' => array($result['minLat'], $result['maxLat']), 
									// 'Map.longitude BETWEEN ? and ?' => array($result['maxLong'], $result['minLong']))));
									
		$this->assertTrue(!empty($return));
		// $this->assertTrue($coords['minLat'] <= $currentLat);
		// $this->assertTrue($coords['maxLat'] >= $currentLat);
		// $this->assertTrue($coords['minLong'] <= $currentLong);
		// $this->assertTrue($coords['maxLong'] >= $currentLong);
		
 	}
	 
/**
 * Find the Max Latutude from a latitude point within a given radius
 */
	public function testMaxlatitude(){
		$data = array( 
			array(
			 	'Map' => array(
	                'name' => 'Frankie Johnnie & Luigo Too',
	                'latitude' => '37.386339', 
	                'longitude' => '-122.085823', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
             array(
	             'Map' => array(
	                'name' => 'Amicis East Coast Pizzeria',
	                'latitude' => '37.38714', 
	                'longitude' => '-122.083235', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
              array(
	             'Map' => array(
	                'name' => 'Kapps Pizza Bar & Grill',
	                'latitude' => '37.393885', 
	                'longitude' => '-122.078916', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
             array(
	             'Map' => array(
	                'name' => 'Tony & Albas Pizza & Pasta',
	                'latitude' => '40.394011', 
	                'longitude' => '-122.095528', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                ))
			
		);
		  $radius = 2; //miles
		  $currentLat = 37.386339;
		  
		  $this->Map->saveAll($data);
		  $result = $this->Map->maxLatitude($currentLat, $radius);
		  $display = $this->Map->find('all', array('conditions' => array('Map.latitude <=' => $result)));

		  $this->assertTrue($result > $currentLat);
	}

/**
 * Find the Min Latutude from a latitude point within a given radius
 */	
	public function testMinlatitude(){
		$data = array( 
			array(
			 	'Map' => array(
	                'name' => 'Frankie Johnnie & Luigo Too',
	                'latitude' => '37.386339', 
	                'longitude' => '-122.085823', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
             array(
	             'Map' => array(
	                'name' => 'Amicis East Coast Pizzeria',
	                'latitude' => '37.38714', 
	                'longitude' => '-122.083235', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
              array(
	             'Map' => array(
	                'name' => 'Kapps Pizza Bar & Grill',
	                'latitude' => '37.393885', 
	                'longitude' => '-122.078916', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
             array(
	             'Map' => array(
	                'name' => 'Tony & Albas Pizza & Pasta',
	                'latitude' => '36.394011', 
	                'longitude' => '-122.095528', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                ))
			
		);
		  $radius = 2; //miles
		  $currentLat = 37.386339;
		  
		  $this->Map->saveAll($data);
		  $result = $this->Map->minLatitude($currentLat, $radius);
		  $display = $this->Map->find('all', array('conditions' =>array('Map.latitude >=' => $result)));
		  
		  $this->assertTrue($result < $currentLat);
		
	}

/**
 * Find the Max Longitude from a laongitude point within a given radius
 */
	public function testMaxLongitude(){
		$data = array( 
			array(
			 	'Map' => array(
	                'name' => 'Frankie Johnnie & Luigo Too',
	                'latitude' => '37.386339', 
	                'longitude' => '-122.085823', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
             array(
	             'Map' => array(
	                'name' => 'Amicis East Coast Pizzeria',
	                'latitude' => '37.38714', 
	                'longitude' => '-122.083235', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
              array(
	             'Map' => array(
	                'name' => 'Kapps Pizza Bar & Grill',
	                'latitude' => '37.393885', 
	                'longitude' => '-122.078916', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
             array(
	             'Map' => array(
	                'name' => 'Tony & Albas Pizza & Pasta',
	                'latitude' => '38.394011', 
	                'longitude' => '-122.095528', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                ))
			
		);
	    $radius = 2; //miles
	    $currentLong = -122.095528;
		
		$this->Map->saveAll($data);
	    $result = $this->Map->maxLongitude($currentLong, $radius);
		$display = $this->Map->find('all', array('conditions' => array('Map.longitude <=' => $result)));
	 	$this->assertTrue($result > $currentLat);
	}

/**
 * Find the Min longitude from a longitude point within a given radius
 */	
	public function testMinLongitude(){
		$data = array( 
			array(
			 	'Map' => array(
	                'name' => 'Frankie Johnnie & Luigo Too',
	                'latitude' => '37.386339', 
	                'longitude' => '-122.085823', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
             array(
	             'Map' => array(
	                'name' => 'Amicis East Coast Pizzeria',
	                'latitude' => '37.38714', 
	                'longitude' => '-122.083235', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
              array(
	             'Map' => array(
	                'name' => 'Kapps Pizza Bar & Grill',
	                'latitude' => '37.361359', 
	                'longitude' => '-122.078916', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                )),
             array(
	             'Map' => array(
	                'name' => 'Tony & Albas Pizza & Pasta',
	                'latitude' => '35.394011', 
	                'longitude' => '-122.095528', 
	                'city' => 'Mountain View', 
	                'state' => 'California'
                ))
			
		);
		  $radius = 2; //miles
		  $currentLong = -122.095528;
		  
		  $this->Map->saveAll($data);
		  $result = $this->Map->minLongitude($currentLong, $radius);
		  $display = $this->Map->find('all', array('conditions' => array('Map.longitude >=' => $result)));
		  $this->assertTrue($result < $currentLong);
		
	}
	
}
