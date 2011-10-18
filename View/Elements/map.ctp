<?php
// this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
// it allows a database driven way of configuring elements, and having multiple instances of that configuration.
	if(!empty($instance) && defined('__ELEMENT_MAPS_MAP_'.$instance)) {
		extract(unserialize(constant('__ELEMENT_MAPS_MAP_'.$instance)));
	} else if (defined('__ELEMENT_MAPS_MAP')) {
		extract(unserialize(__ELEMENT_MAPS_MAP));
	}
// setup defaults

$mapWidth = !empty($mapWidth) ? $mapWidth : 500;
$mapHeight = !empty($mapHeight) ? $mapHeight : 500;
?>
    
<div class="maps form">
<?php echo $this->Form->create('Map', array('url' => array('plugin' => 'maps', 'controller' => 'maps', 'action' => 'search')));?>
	<fieldset>
 		<legend><?php # __('Search Location'); ?></legend>
	<?php
		echo $this->Form->input('Map.search_loc', array('label' => __('Location', true)));
		/*echo $this->Form->input('Map.street');
		echo $this->Form->input('Map.city');
		echo $this->Form->input('Map.state');
		echo $this->Form->input('Map.country');
		echo $this->Form->input('Map.postal');*/
	?>
	</fieldset>
<?php echo $this->Form->end(__('Search', true));?>
</div>

<?php 
	if(isset($locations)) {
?>
	

	<script type="text/javascript"><!--

		var geocoder;
	    var map;
	    var result;
	    //var result_other;
	    var radius;
	    var center;
	    var draw_circle = null;
	    max_distance = new Array();


	    //center_address, adresses
		function initialize() {
			 i = 0;	
			 location_address = new Array();
			<?php foreach($locations as $location) { ?>
			location_address[i] = new Address('<?php echo $location['Map']['street']?>', 
						'<?php echo $location['Map']['city']?>', 
						"<?php echo $location['Map']['state']?>", 
						'<?php echo $location['Map']['country']?>', 
						'<?php echo $location['Map']['postal']?>', 
						'<?php echo $location['Map']['marker_text']?>');		
			i++;		
			<?php }?>
			
			//center_address = new Address('', '', "<?php //echo $search_locations['Map']['state']?>", '<?php //echo $search_locations['Map']['country']?>', '', '');
			center_add = "<?php echo $search_locations['Map']['search_loc']?>" ;
			address = new Array();
			for(k = 0;  k < location_address.length; k++ ) {
				address[k]= location_address[k];
			}
			plot(center_add, address);
	    }

	    /*
	    *	Plot
	    *		center_address_obj : Object of class address with center address
	    *		address_obj : Arrat of object of class address with distance calculation points
	    *		address_other_obj : Array of object of class Address with  address of plottable points
	    */
	    function plot(center_address_obj, address_obj)  {

			geocoder = new google.maps.Geocoder();
			center_address = center_address_obj ; 
			center_address_info = center_address_obj ;
			center_result = new Array(2);

			result = new Array(address.length);
			address = new Array(address_obj.length);
			address_marker_info = new Array(address_obj.length);
			for (i in address_obj) {
				address[i] = address_obj[i].getAddress();
				address_marker_info[i] = address_obj[i].getMarkerInfo();
			}
			
			if (geocoder) {
				geocoder.geocode( { 'address': center_address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {

						center_result[0] = parseFloat(results[0].geometry.location.lat());
						center_result[1] = parseFloat(results[0].geometry.location.lng());


						result = new Array(address.length);

						state = false;
						for ( i = 0, j= 0; i < address.length; i++) {
							result[i] = new Array(4); // 0 = lat, 1 = lng, 2 = distance from center point, 3 for marker

							geocoder.geocode( { 'address': address[i]}, function(results, status) {
								if (status == google.maps.GeocoderStatus.OK) {
									result[j][0] = parseFloat(results[0].geometry.location.lat());
									result[j][1] = parseFloat(results[0].geometry.location.lng());
									result[j][2] = distances(center_result[0], center_result[1], result[j][0], result[j][1]);
									}// if ok
								j++;

								if (j == address.length) // this means all the iterations are done
								{
									 		newLat = center_result[0];
									 		newLng = center_result[1];
		
									 		center = new google.maps.LatLng(newLat, newLng);
									 		var myOptions = {center: center, mapTypeId: google.maps.MapTypeId.TERRAIN}
									 		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		
									 		var index = 0;
									 		radius = result[index][2];
		
											// draw blue points
									 		for (i in result) {
									 			if (parseFloat(result[i][2]) < parseFloat(radius)) {
											 		radius = parseFloat(result[i][2]);
													index = i;
										 		}
												var add = new google.maps.LatLng(result[i][0], result[i][1]);
												setMarker('result', i , add, address[i], 'Blue', map, address_marker_info[i]);
									 		}
									 		setMarker('center', 0, center, center_address, 'Red', map, center_address_info);
		
									 		
		//									1 mile = 1609.344 meters
											if(radius == 0){
												for (i in result) {
														if ( parseFloat(result[i][2]) != 0)
															radius = parseFloat(result[i][2]);
												}
		
												for (j in result) {
														if ( result[j][2] < parseFloat(radius) && parseFloat(result[j][2]) != 0 )
															radius = parseFloat(result[j][2]);
												}
												bound_radius = radius * 1609.34; // in mts
											} else {
												bound_radius = radius * 1609.34; // in mts
												//alert(bound_radius);
											}
											var circle = new google.maps.Circle({radius: bound_radius, center: center});
											map.fitBounds(circle.getBounds()); 
									
								}
								

							}); // geo coder add find
						} // for
					} // if
				}); // fiind center_address
			}// geo coder
		} // function

		Number.prototype.toRad = function() {  // convert degrees to radians
			return this * Math.PI / 180;
		}

		function attachMessage(marker, message) {
			  //var message = ["This","is","the","secret","message"];
			  var infowindow = new google.maps.InfoWindow(
			      { content: message,
			        size: new google.maps.Size(50,50)
			      });
			  //google.maps.event.addListener(marker, 'click', function() {
			    infowindow.open(map,marker);
			  //});
			}
		
		function geocode(address, center_result) {
			result_a = new Array(address.length);

			state = false;
			for ( i = 0, j= 0; i < address.length; i++) {
				result_a[i] = new Array(4); // 0 = lat, 1 = lng, 2 = distance from center point, 3 for marker

				geocoder.geocode( { 'address': address[i]}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						result_a[j][0] = parseFloat(results[0].geometry.location.lat());
						result_a[j][1] = parseFloat(results[0].geometry.location.lng());
						result_a[j][2] = distances(center_result[0], center_result[1], result_a[j][0], result_a[j][1]);
						}// if ok
					j++;

				}); // geo coder add find
			} // for

			return result_a;
		}

		function pauseJS(timeInMilliS) {
			var date = new Date();
			var curDate = null;
			do { curDate = new Date(); }
			while(curDate-date < timeInMilliS);
			}

 		function setMarker(type, index, pos, title, icon, map, marker_info) {
 	 		switch (icon) {
 	 			case 'Yellow' : marker_icon= "./icons/orangeIcon.gif"; break;
				case 'Red' : marker_icon= "http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png"; break;
				case 'Green' : marker_icon= "http://www.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png";break;
				case 'Blue' : marker_icon= "http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png";break;
				default: break;
			}
			var marker = new google.maps.Marker({
			      position: pos,
			      title:title,
			      icon:marker_icon
			  });
			marker.setMap(map);
			attachMessage(marker, marker_info);
			switch (type) {
				case 'result' : result[index][3] = marker; break;
				//case 'result_other' : result_other[index][3] = marker; break;
				default: break;
			}
 		}


		function distances(lat1, lon1, lat2, lon2) {
				// ACOS(SIN(lat1)*SIN(lat2)+COS(lat1)*COS(lat2)*COS(lon2-lon1))*6371
				// Convert lattitude/longitude (degrees) to radians for calculations
				var R = 3963.189; // meters


				// Find the deltas
				delta_lon = deg2rad(lon2) - deg2rad(lon1);

				// Find the Great Circle distance
				distance = Math.acos(Math.sin(deg2rad(lat1)) * Math.sin(deg2rad(lat2)) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
							Math.cos(delta_lon)) * 3963.189;
				return distance;
			}

			function deg2rad(val) {
				var pi = Math.PI;
				var de_ra = ((eval(val))*(pi/180));
				return de_ra;
			}
			
			$(function() {
				$("#slider").slider({
					//orientation: "vertical",
					range: "min",
					min: 0,
					max: 100,
					value: 50,
					slide: function(event, ui) {
						//$("#amount").val(ui.value);
					},
					change: function(event, ui) {
						$("#amount").val(ui.value);
						per_change = (ui.value - 50) / 50;

						new_radius = radius * (1+per_change);
						$('#new_rad').val(new_radius);
						$('#rad').show();
						$('#rad_val').val(radius);

						for (i in result) {
							if (result[i][2] > new_radius)
								result[i][3].setIcon("http://www.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png");
							else
								result[i][3].setIcon("http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png");
						}
					}

				});

			});

	--></script>

	<div id="map_canvas" style="width: <?php echo $mapWidth; ?>px; height: <?php echo $mapHeight; ?>px"></div>
    
<script type="text/javascript">
function get_radius() {
	return radius;
}
	</script>

<script type="text/javascript">
function Address(street, city, state, country, postal, info) {
	///class members
	if (street)
			this.Street__c = street;
	if (city)
		this.City__c = city;

	if (state)
		this.State__c = state;

	if (country)
		this.Country__c = country;

	if (postal)
		this.Postal__c = postal;
	if (info)
		this.Info__c = info;
}

Address.prototype.getAddress=function() {
	add = '';
	if (this.Street__c)
		add += this.Street__c + ' ,';
	if (this.City__c)
		add += this.City__c  + ' ,';
	if	(this.State__c)
		add += this.State__c + ',';
	if	(this.Country__c)
		add += this.Country__c + ',';
	if	(this.Postal__c)
		add += this.Postal__c + ',';

	return add;
}
Address.prototype.getMarkerInfo=function() {
	info = '';
	if (this.Info__c)
		info = this.Info__c ;

	return info;
}
</script>
	<script type="text/javascript">
		$(document).ready(function() {
			initialize();
		});
	</script>
<?php 
	} else { } ?>