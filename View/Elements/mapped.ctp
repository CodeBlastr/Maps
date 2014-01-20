  <?php
/**
 * Mapped View
 * 
 * A map that displays markers when given the locations variable
 */
$mapWidth = !empty($mapWidth) ? $mapWidth : '100%';
$mapHeight = !empty($mapHeight) ? $mapHeight : '100%;';
$mapZoom = !empty($mapZoom) ? $mapZoom : 8;
$autoZoomMultiple = !empty($autoZoomMultiple) ? $autoZoomMultiple : true;
$locations = !empty($locations) ? $locations : array();
//$locations = isset($this->request->data['Map']) ? $this->request->data['Map'] : array();
//$api_key = defined('__APP_GOOGLE_MAP_API_KEY') ? __APP_GOOGLE_MAP_API_KEY : false;
?>

<?php if(!api_key): ?>
	<div class="alert alert-danger">No API key provided</div>
<?php endif; ?>

<div id="map_canvas"> No results found. </div>

<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key='.$api_key['google_api_key'].'&sensor=false', array('inline' => true)); ?>
	<style type="text/css">
		#map_canvas {
  			height: <?php echo $mapHeight; ?>;
  			width: <?php echo $mapWidth; ?>;
		}
	</style>
	<script type="text/javascript">
		var locations = [];
		var center = false;
		var LatLngList = [];
		
      	function initialize() {

			console.log(locations);
			<?php if ($autoZoomMultiple) { ?>
			//  Make an array of the LatLng's of the markers, only, so we can autozoom
       		for(i = 0 ; i < locations.length ; i++) {
				LatLngList.push(new google.maps.LatLng(locations[i].Map.latitude, locations[i].Map.longitude));
           	}
           	console.log(LatLngList);
			<?php } //($autoZoomMultiple) ?>
		    var map = new google.maps.Map(document.getElementById('map_canvas'), {
		      zoom: <?php echo $mapZoom; ?>,
		      center: new google.maps.LatLng(center.latitude, center.longitude),
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
		        position: new google.maps.LatLng(locations[i].Map.latitude, locations[i].Map.longitude),
		        map: map
		      });
		      
		      google.maps.event.addListener(marker, 'click', (function(marker, i) {
		        return function() {
		          infowindow.setContent(locations[i].Map.marker_text);
		          infowindow.open(map, marker);
		        }
		      })(marker, i));
		    }
		}
	
	
		$(document).ready(function() {
			getLocation();
		});

		function getLocation() {
			<?php if (empty($locations)) : ?>
				if (navigator.geolocation) {
				    navigator.geolocation.getCurrentPosition(getCoords);
			  	} else {
				 	alert('Your browser does not support location services');
				  	initialize();
				}
			<?php else : ?>
				locations = <?php echo json_encode($locations); ?>;
				//This assumes that the best match is first in the locations array
				center = {latitude: locations[0].Map.latitude, longitude: locations[0].Map.longitude};
				initialize();
			<?php endif; ?>
		}

		function getCoords(position) {
			center = position.coords;
			$.get('/maps/maps/nearby/' + center.latitude + '/' + center.longitude).done(function( data ) {
			    locations = JSON.parse(data);
			    initialize();
			});
		}
		
</script>