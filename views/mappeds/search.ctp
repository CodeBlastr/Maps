<div class="maps form">
<?php echo $this->Form->create('Map');?>
	<fieldset>
 		<legend><?php echo __('Search Location'); ?></legend>
	<?php
		echo $this->Form->input('Mapped.street');
		echo $this->Form->input('Mapped.city');
		echo $this->Form->input('Mapped.state');
		echo $this->Form->input('Mapped.country');
		echo $this->Form->input('Mapped.postal');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>

<?php 
	if(isset($locations)) {
?>

<!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>Google Maps JavaScript API</title>

	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/base/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js" type="text/javascript"></script>
	<script src="http://jquery-ui.googlecode.com/svn/tags/latest/external/jquery.bgiframe-2.1.1.js" type="text/javascript"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/i18n/jquery-ui-i18n.min.js" type="text/javascript"></script>

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
			<?php  foreach($locations as $location) { ?>
			location_address[i] = new Address('', '', "<?php echo $location['Mapped']['state']?>", '<?php echo $location['Mapped']['country']?>', '');		
			i++;		
			<?php }?>
			
			center_address = new Address('', '', "<?php echo $search_locations['Mapped']['state']?>", '<?php echo $search_locations['Mapped']['country']?>', '');
			
			address = new Array();
			for(k = 0;  k < location_address.length; k++ ) {
				address[k]= location_address[k];
			}
			
			//address[0]=	location_address[1];
			//address[1]=	location_address[2];

			//other_address = new Array();
			//other_address[0]= location_address[location_address.length-2];
			//other_address[1]= location_address[location_address.length-1];


			plot(center_address, address);
	    }

	    /*
	    *	Plot
	    *		center_address_obj : Object of class address with center address
	    *		address_obj : Arrat of object of class address with distance calculation points
	    *		address_other_obj : Array of object of class Address with  address of plottable points
	    */
	    function plot(center_address_obj, address_obj)  {

			geocoder = new google.maps.Geocoder();
			center_address = center_address.getAddress();
			center_result = new Array(2);

			result = new Array(address.length);
			address = new Array(address_obj.length);
			for (i in address_obj) {
				address[i] = address_obj[i].getAddress();
				}
			
			
			//address_other=new Array(address_other_obj.length);
			//for (i in address_other_obj) {
			//	address_other[i] = address_other_obj[i].getAddress();
			//}

			if (geocoder) {
				geocoder.geocode( { 'address': center_address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {

						center_result[0] = parseFloat(results[0].geometry.location.lat());
						center_result[1] = parseFloat(results[0].geometry.location.lng());

						result = geocode(address, center_result);

						alert('please wait... drawing map');
						//result_other = geocode(address_other, center_result);
						//alert('step2 completed.. drawing map');

				 		newLat = center_result[0];
				 		newLng = center_result[1];

				 		center = new google.maps.LatLng(newLat, newLng);
				 		var myOptions = {center: center, mapTypeId: google.maps.MapTypeId.ROADMAP}
				 		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

				 		//var marker = new google.maps.Marker({
				 	    //    		position: center, 
				 	    //    		map: map
				 	    //	});
				 		//attachMessage(marker, 2);
				 							
				 		// var mapZoom = map.getZoom();
						//map.setZoom(8);
						
				 		var index = 0;
				 		radius = result[index][2];

				 		//setMarker('center', 0, center, center_address, 'Red', map);

						// draw blue points
				 		for (i in result) {
				 			if (parseFloat(result[i][2]) < parseFloat(radius)) {
						 		radius = parseFloat(result[i][2]);
								index = i;
					 		}
				 			max_distance[i] = result[i][2];
							var add = new google.maps.LatLng(result[i][0], result[i][1]);
							setMarker('result', i , add, address[i], 'Blue',map);
				 		}
				 		setMarker('center', 0, center, center_address, 'Red', map);
						var	temp = max_distance[0];
						for(i = 0; i < max_distance.length; i++){

							if(temp > max_distance[i]) {
								temp = max_distance[i];
							} 
						}
//						1 mile = 1609.344 meters
						bound_radius = radius * 1609.34; // in mts
						var circle = new google.maps.Circle({radius: bound_radius, center: center});
						map.fitBounds(circle.getBounds()); 
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
			  google.maps.event.addListener(marker, 'click', function() {
			    infowindow.open(map,marker);
			  });
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
					//if (j == address.length) state = true;

				}); // geo coder add find
				//pauseJS(1000);
				//do {}while(state != true); // benefit of this while outside is all the requests will be fired without waiting for first one to complete.
			} // for

			return result_a;
		}

		function pauseJS(timeInMilliS) {
			var date = new Date();
			var curDate = null;
			do { curDate = new Date(); }
			while(curDate-date < timeInMilliS);
			}

 		function setMarker(type, index, pos, title, icon, map) {
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
			attachMessage(marker, title);
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
						//for (i in result_other) {
						//	if (result_other[i][2] > new_radius) {
						//		result_other[i][3].setIcon("http://www.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png");
						//	}
						//	else
						//		result_other[i][3].setIcon("http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png");
						//}
					}

				});

			});

	--></script>

	</head>
	<body onLoad="initialize()" >
	
	</br>
	<div id="map_canvas" style="width: 600px; height: 600px">
	</div>
	</body>
</html>
<script type="text/javascript">
function get_radius() {
	return radius;
}
	</script>

<script type="text/javascript">
function Address(street, city, state, country, postal)
{
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
}

Address.prototype.getAddress=function()
{
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
</script>
<?php 
	} else {
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<div id=":1b"><script type="text/javascript">
 var geocoder;
 var map;
 var addresses;
 var results;
 var dist;
 
 function initialize() {
	 geocoder = new google.maps.Geocoder();
 	(distance("<?php echo ($locations_db['Mapped']['address1'].' '.$locations_db['Mapped']['address2'].','.$locations_db['Mapped']['city'].','.$locations_db['Mapped']['country'].','.$locations_db['Mapped']['pin']) ?>",
		   "<?php echo ($search_locations['Mapped']['address1'].' '.$search_locations['Mapped']['address2'].','.$search_locations['Mapped']['city'].','.$search_locations['Mapped']['country'].','.$search_locations['Mapped']['pin']) ?>"));
 }
 
 function distance(add1, add2) {
 	if (!geocoder)
	 return 'Error, no geocoder';
 
	 addresses = new Array(2);
	 addresses[0] = add1;
	 addresses[1] = add2;
	 results = new Array(2);
	 results[0] = new Array(2);
	 results[1] = new Array(2);
 
	 results[0][0] = 0; results[0][1] = 0; results[1][0] = 0; results[1][1] = 0.87;
	 geocoded(1); 
 }
 
 function geocoded(i) {
	 geocoder.geocode( { 'address': addresses[i] }, function(res, status) {
		 if (status == google.maps.GeocoderStatus.OK) {
			 results[i][0] = parseFloat(res[0].geometry.location.lat());
			 results[i][1] = parseFloat(res[0].geometry.location.lng());
			 i--;
	 
			 if (i >= 0)
				 geocoded(i);
			 else
				 dist = distances(results[0][0], results[0][1], results[1][0], results[1][1]);
		 }// if ok
	 }); // geo coder add find
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
 
	 alert(distance + ' miles');
	 return distance;
 }
 
 function deg2rad(val) {
	 var pi = Math.PI;
	 var de_ra = ((eval(val))*(pi/180));
	 return de_ra;
 }
 </script>
 
</head>
<body onLoad="initialize()" >
</body></div>
<?php } ?>