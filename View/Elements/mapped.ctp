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
$locations = !empty($locations) ? $locations : array();
$api_key = unserialize(__GOOGLE_MAP_API_KEY); 
?>
<?php debug($api_key); ?>
<div id="map_canvas"> No results found. </div>

<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key='.$api_key['google_api_key'].'&sensor=FALSE', array('inline' => false)); ?>
	<style type="text/css">
		#map_canvas {
  			height: <?php echo $mapHeight; ?>;
  			width: <?php echo $mapWidth; ?>;
		}
	</style>
	<script type="text/javascript">
		var locations = [];
		var center = false;
		
      	function initialize() {
       		locations = [
       			<?php
       			if(!empty($locations)) {
       			$i = 0;
       			foreach ($locations as $location) {
       				if (!empty($location['Map']['latitude'])) {
       					echo '[\''.addslashes($location['Map']['marker_text']).'\', '.$location['Map']['latitude'].', '.$location['Map']['longitude'].', '.$i.'],'; // canopy index and product view
						$center = $location['Map']['latitude'].', '.$location['Map']['longitude'];
						echo 'center = "'.$center.'";';
						$i++;
					}
				}} ?>
		    ];
			
		    <?php if(empty($locations)): ?>

		   	var result = getLocation();
		   	console.log(result);
		    
		    <?php endif; ?>
			
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
			console.log(center);
		    var map = new google.maps.Map(document.getElementById('map_canvas'), {
		      zoom: <?php echo $mapZoom; ?>,
		      center: new google.maps.LatLng(center),
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

		function getLocation() {
		  if (navigator.geolocation) {
		    	navigator.geolocation.getCurrentPosition(getCoords);
		    	return true;
		  }else{
			  return false;
			}
		}

		function getCoords(position) {
			console.log(position);
			center = position.coords.latitude+","+position.coords.longitude;
			console.log(center);
		}
		
</script>