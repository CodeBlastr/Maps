<?php
/**
 * Mapped View
 * 
 * A map that displays markers when given the locations variable
 * 
 * @todo This should save geocoding to the database so that we don't have to pull it a million times
 */
$mapWidth = !empty($mapWidth) ? $mapWidth : '100%';
$mapHeight = !empty($mapHeight) ? $mapHeight : '500px';
$mapZoom = !empty($mapZoom) ? $mapZoom : 8;

if(isset($locations)) { ?>
	
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
       					echo '[\''.$location['Map']['name'].'\', '.$location['Map']['latitude'].', '.$location['Map']['longitude'].', '.$i.'],'; // canopy index and product view
						$center = $location['Map']['latitude'].', '.$location['Map']['longitude'];
						$i++;
					}
				} ?>
		    ];
		
		    var map = new google.maps.Map(document.getElementById('map_canvas'), {
		      zoom: <?php echo $mapZoom; ?>,
		      center: new google.maps.LatLng(<?php echo $center; ?>),
		      mapTypeId: google.maps.MapTypeId.ROADMAP
		    });
		
		    var infowindow = new google.maps.InfoWindow();
		
		    var marker, i;
		
		    for (i = 0; i < locations.length; i++) {  
		      marker = new google.maps.Marker({
		        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		        map: map
		      });
		
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
	<div id="map_canvas"></div>
<?php 
} ?>