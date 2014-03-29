<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?sensor=false', array('inline' => false)); ?>
<script type="text/javascript">
	$('.map_canvas').each(function(index, Element) {
		
		var locations = [];
		var content = [];
		var center = false;
		
        $infotext = $(Element).children('.map-item');
        
        $infotext.each(function(key, item) {
        	locations[key] = [];
	        locations[key]['latitude'] = $(item).children('.latitude').text();
	        locations[key]['longitude'] = $(item).children('.longitude').text();
	        locations[key]['marker_text'] = $(item).children('.marker_text').html();
        });
        var infowindow;

		// don't use "var map" we need this to be global (see blockshares property view)
	    var map = new google.maps.Map(Element, {
			zoom: parseInt($(Element).children('.zoom').text()),
	      	center: new google.maps.LatLng(locations[0].latitude, locations[0].longitude), //This assumes that the best match is first in the locations array
	      	mapTypeId: google.maps.MapTypeId.ROADMAP
	    });
	
	    var infowindow = new google.maps.InfoWindow();
	
	    var marker, i;
	
	    for (i = 0; i < locations.length; i++) {
			marker = new google.maps.Marker({
	        	position: new google.maps.LatLng(locations[i].latitude, locations[i].longitude),
	        	map: map
	      	});
	      	google.maps.event.addListener(marker, 'click', (function(marker, i) {
	        	return function() {
	          		infowindow.setContent(locations[i].marker_text);
	          		infowindow.open(map, marker);
	        	}
	      	})(marker, i));
	   	}
	});
</script>



<?php /*
$mapWidth = !empty($mapWidth) ? $mapWidth : '100%';
$mapHeight = !empty($mapHeight) ? $mapHeight : '400px'; // this cannot be a percentage by default
$mapZoom = !empty($mapZoom) ? $mapZoom : 8;
$autoZoomMultiple = !empty($autoZoomMultiple) ? $autoZoomMultiple : true;
$mapped = !empty($locations) ? $locations : array();
// get rid of empty map values
unset($locations);
foreach ($mapped as $map) {
	if (!empty($map['Map'])) {
		$locations[]['Map'] = $map['Map']; 
	}
} ?>
<?php debug($mapped); ?>
<div class="block maps first">
    <div class="content">
        <div class="map_canvas" style="width: <?php echo $mapWidth; ?>; height: <?php echo $mapHeight; ?>;">
            <div class="infotext">
                <div class="location">Middle East Bakery & Grocery</div>
                <div class="address">327 5th St</div>
                <div class="city">West Palm Beach</div>
                <div class="state">FL</div>
                <div class="zip">33401-3995</div>
                <div class="country">USA</div>
                <div class="phone">(561) 659-4050</div>
                <div class="zoom"><?php echo $mapZoom; ?></div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key='.$api_key['google_api_key'].'&sensor=false', array('inline' => true)); ?>

<script>
$(document).ready(function() {
    $maps = $('.block.maps .content .map_canvas');
    $maps.each(function(index, Element) {
        $infotext = $(Element).children('.infotext');

        var myOptions = {
            'zoom': parseInt($infotext.children('.zoom').text()),
            'mapTypeId': google.maps.MapTypeId.ROADMAP
        };
        var map;
        var geocoder;
        var marker;
        var infowindow;
        var address = $infotext.children('.address').text() + ', '
                + $infotext.children('.city').text() + ', '
                + $infotext.children('.state').text() + ' '
                + $infotext.children('.zip').text() + ', '
                + $infotext.children('.country').text()
        ;
        var content = '<strong>' + $infotext.children('.location').text() + '</strong><br />'
                + $infotext.children('.address').text() + '<br />'
                + $infotext.children('.city').text() + ', '
                + $infotext.children('.state').text() + ' '
                + $infotext.children('.zip').text()
        ;
        if (0 < $infotext.children('.phone').text().length) {
            content += '<br />' + $infotext.children('.phone').text();
        }

        geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                myOptions.center = results[0].geometry.location;
                map = new google.maps.Map(Element, myOptions);
                marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    title: $infotext.children('.location').text()
                });
                infowindow = new google.maps.InfoWindow({'content': content});
                google.maps.event.addListener(map, 'tilesloaded', function(event) {
                    infowindow.open(map, marker);
                });
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map, marker);
                });
            } else {
                alert('The address could not be found for the following reason: ' + status);
            }
        });
    });
});
</script> */ ?>