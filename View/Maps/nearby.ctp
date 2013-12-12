<div class="maps nearby">
	<div class="row">
    	<div class="col-md-12">
    		<div class="personal-stats-wrapper text-center">
    			<div class="personal-info-wrapper">
					<div class="person-image row">
						<div class="col-md-12"><img src="..." alt="..." class="img-circle"></div>
					</div>
					<div class="name-rank row">
						<div class="col-md-12">
							<div class="personal-name col-md-12">Ann Perkins</div>
						</div>
						<div class="col-md-12">
							<div class="personal-rank col-md-12">(RANK 500)</div>
						</div>
					</div>
				</div>
				<div class="winnings-wrapper row">
					<div class="col-md-12">
						<div class="total-ammount">
							<span class="dollar">$10</span><span class="cents">.30</span>
						</div>
					</div>
					<div class="col-md-12">
						<div class="winning">WINNINGS</div>
					</div>
				</div>
							
				<div class="games-wrapper row">
					<div class="plays col-md-6">
						<span class="total">20</span> PLAYS
					</div>
					<div class="wins col-md-6">
						<span class="total">8</span> WINS
					</div>
				</div>
			</div>
		</div>
	</div>

	<p id="location">Find Me:</p>
	<button class="btn btn-primary" onclick="getLocation()">Locate</button>

	<ul class="list-group">
		<?php debug($locations);?>
		<?php foreach($locations as $location): ?>
		  <li class="list-group-item"><span class="truncate" data-truncate="50">
		  	<?php echo strip_tags($location['Map']['marker_text']); ?></span>
		  </li>
  		<?php endforeach ?>
	</ul>

</div>
<?php echo $this->Element('paging'); ?>


<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Maps',
		'items' => array(
			$this->Html->link(__('Search'), array('controller' => 'maps', 'action' => 'search')),
			$this->Html->link(__('Add'), array('controller' => 'maps', 'action' => 'add'))
			)
		)
	)));
?>
<script>
var x=document.getElementById("location");
function getLocation()
  {
  if (navigator.geolocation)
    {
    navigator.geolocation.getCurrentPosition(showPosition); //returns users position
	//navigator.geolocation.watchPosition(showPosition); //Returns the current position of the user and continues to return updated position as the user moves
    }
  else{x.innerHTML="Geolocation is not supported by this browser.";}
  }
function showPosition(position)
  {
  x.innerHTML="Latitude: " + position.coords.latitude + 
  "<br>Longitude: " + position.coords.longitude;
  nearbyLocations(position);

  }
    function nearbyLocations(position) {
    	
	    $.get('/maps/maps/nearby/' + position.coords.latitude + '/' + position.coords.longitude, function(data) {
	   		console.log(data); 
	    }); 
   }	         
  // console.log(results);
  
</script>
