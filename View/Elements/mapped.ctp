<?php
/**
 * Mapped View
 * 
 * A map that displays markers when given the locations variable
 */
$mapWidth = !empty($mapWidth) ? $mapWidth : '100%';
$mapHeight = !empty($mapHeight) ? $mapHeight : '500px';
$mapZoom = !empty($mapZoom) ? $mapZoom : 8;
$autoZoomMultiple = !empty($autoZoomMultiple) ? $autoZoomMultiple : false;
$locations = !empty($locations) ? $locations : array(array('Map' => array('latitude' => '36', 'longitude' => '-50', 'marker_text' => 'No results found')));
?>

<div id="map_canvas"> No results found. </div>

<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array('inline' => false)); ?>
	<style type="text/css">
		#map_canvas {
  			height: <?php echo $mapHeight; ?>;
  			width: <?php echo $mapWidth; ?>;
		}
	</style>
	<script type="text/javascript">		
      	function initialize() {
       		var locations = [
       			<?php 
       			$i = 0;
       			foreach ($locations as $location) {
       				if (!empty($location['Map']['latitude'])) {
       					echo '[\''.addslashes($location['Map']['marker_text']).'\', '.$location['Map']['latitude'].', '.$location['Map']['longitude'].', '.$i.'],'; // canopy index and product view
						$center = $location['Map']['latitude'].', '.$location['Map']['longitude'];
						$i++;
					}
				} ?>
		    ];
			
			<?php if ($autoZoomMultiple) { ?>
			//  Make an array of the LatLng's of the markers, only, so we can autozoom
       		var LatLngList = [
       			<?php 
				$latLngArray = '';
       			foreach ($locations as $location) {
       				if (!empty($location['Map']['latitude'])) {
						$latLngArray .= 'new google.maps.LatLng ('.$location['Map']['latitude'].','.$location['Map']['longitude'].'), ';
					}
				}
				$latLngArray = rtrim($latLngArray, ', ');
				echo $latLngArray;
				?>
		    ];
			<?php } //($autoZoomMultiple) ?>
		
		    var map = new google.maps.Map(document.getElementById('map_canvas'), {
		      zoom: <?php echo $mapZoom; ?>,
		      center: new google.maps.LatLng(<?php echo $center; ?>),
		      mapTypeId: google.maps.MapTypeId.ROADMAP
		    });
		
		
			<?php if ($autoZoomMultiple) { ?>
			// Set an optimal zoom when there are multiple items
			if ( LatLngList.length > 1) {
				//  Create a new viewpoint bound
				var bounds = new google.maps.LatLngBounds ();
				//  Go through each...
				for (var i = 0, LtLgLen = LatLngList.length; i < LtLgLen; i++) {
				  //  And increase the bounds to take this point
				  bounds.extend (LatLngList[i]);
				}
				//  Fit these bounds to the map
				map.fitBounds (bounds);
			}
			<?php } // ($autoZoomMultiple) ?>

		
		    var infowindow = new google.maps.InfoWindow();
		
		    var marker, i;
		
		    for (i = 0; i < locations.length; i++) {
		      marker = new google.maps.Marker({
		        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		        map: map
		      });
				console.log(locations[i])
		      google.maps.event.addListener(marker, 'click', (function(marker, i) {
		        return function() {
		          infowindow.setContent(locations[i][0]);
		          infowindow.open(map, marker);
		        }
		      })(marker, i));
		    }
		}
	
	
		$(document).ready(function() {
			initialize();
		});
</script>