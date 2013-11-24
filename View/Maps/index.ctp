<div class="maps index">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Street</th>
				<th><?php echo $this->Paginator->sort('city');?></th>
				<th><?php echo $this->Paginator->sort('state');?></th>
				<th><?php echo $this->Paginator->sort('country');?></th>
				<th><?php echo $this->Paginator->sort('postal');?></th>
				<th><?php echo $this->Paginator->sort('marker_text');?></th>	
			</tr>
		</thead>
		<tbody>
			<?php foreach ($locations as $location): ?>
			<tr>
				<td>
					<?php echo $this->Html->link($location['Map']['street'], array('action' => 'view', $location['Map']['id'])); ?>
				</td>
				<td>
					<?php echo $location['Map']['city']; ?>
				</td>
				<td>
					<?php echo $location['Map']['state']; ?>
				</td>
				<td>
					<?php echo $location['Map']['country']; ?>
				</td>
				<td>
					<?php echo $location['Map']['postal']; ?>
				<td>
					<span class="truncate" data-truncate="50">
						<?php echo strip_tags($location['Map']['marker_text']); ?>
					</span>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
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
