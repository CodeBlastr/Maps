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
             'Map' => array(
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
 *  Fast calculation to find latitude and longitude within a radius
 * 
 */
	// public function testFastDistance(){
// 		
	// }

/**
 * Slower calculation to find latitude and longitude within a radius
 */
	//public function testSlowDistance($radius, $long, $lat){
			// $query = SELECT id, street, marker_text, city, state, ( 3959 * ACOS( COS( RADIANS( 37 ) ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS( -122 ) ) + SIN( RADIANS( 37 ) ) * SIN( RADIANS( latitude ) ) ) ) AS distance
					// FROM maps
					// HAVING distance <50
					// ORDER BY distance
					// LIMIT 0 , 20
									
		  // $data = array(
            // 'Map' => array(
                // 'name' => 'Frankie Johnnie & Luigo Too',
                // 'latitude' => '37.386339', 
                // 'longitude' => '-122.085823', 
                // 'city' => 'Mountain View', 
                // 'state' => 'California'
                // ),
            // 'Alias' => array(
                // 'name' => 'lorem-ipsum'
                // )
            // );

		
// 		
	// }

/**
 * Find the Max Latutude from a latitude point within a given radius
 */
	//public function testMaxlatitude(){
			// $radius = 6371; //km
			// $distance = 1000; //km
			// $r = 0.1570; //angle between points
			// $lat = 32.53; //static latitude point
// 			
			// $latMax = $lat + $r; 
			// echo $latMax;
			
		
//	}

/**
 * Find the Min Latutude from a latitude point within a given radius
 */	
	//public function testMinlatitude(){
			// $radius = 6371; //km
			// $distance = 1000; //km
			// $r = 0.1570; //angle between points
			// $lat = 32.53; //static latitude point
// 			
			// $latMin = $lat - $r; 
			// echo $latMin;
		
		
	//}

/**
 * Find the Max Longitude from a laongitude point within a given radius
 */
//	public function testMaxLongitude(){
		
	//}

/**
 * Find the Min longitude from a longitude point within a given radius
 */	
	//public function testMinLongitude(){
		
	//}
	
}
