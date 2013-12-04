<div class="maps nearby">

		<!-- <div class="row rank">
			<div class="col-xs-12 col-sm-6 col-md-12">
				ddfgdgdg
			</div>
		</div>
		<div class="row winnings">
			<div class="col-xs-12 col-sm-6 col-md-12">
				dgdgdfgdg
			</div>
		</div>
		<div class="row plays_wins">
			<div class="col-xs-12 col-sm-6 col-md-12">
				dgdgdfgdfg
			</div>
		</div> -->
		

	<div class="row">
    	<div class="col-md-12">
    		<div class="personal-stats-wrapper text-center">
    			<div class="personal-info-wrapper">
					<div class="person-image row">
						<div class="col-md-12"><img src="..." alt="..." class="img-circle"></div>
					</div>
					<div class="row name-rank">
						<div class="personal-name col-md-12">Ann Perkins <br>(RANK 500)</div>
					</div>
				</div>
				<div class="winnings-wrapper row">
					<div class="total-winnings col-md-12">
						<span class="dollar">$10</span><span class="cents">.30</span>
					</div>
					<div class="winnings col-md-12">
						WINNINGS
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

	
	<ul class="list-group">
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
