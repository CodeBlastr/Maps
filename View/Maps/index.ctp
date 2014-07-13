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
					<?php echo $location['Map']['street']; ?>
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
				<td>
					<?php echo $this->Html->link('View', array('action' => 'view', $location['Map']['id']), array('class' => 'btn btn-success btn-xs')); ?>
					<?php echo $this->Html->link('Edit', array('action' => 'edit', $location['Map']['id']), array('class' => 'btn btn-warning btn-xs')); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php echo $this->Element('paging'); ?>


<?php
// set the contextual breadcrumb items
$this->set('context_crumbs', array('crumbs' => array(
	$this->Html->link(__('Admin Dashboard'), '/admin'),
	'Map Dashboard'
)));
// set contextual search options
$this->set('forms_search', array(
    'url' => $this->request->admin == true ? '/admin/maps/maps/index' : '/maps/maps/index/', 
	'inputs' => array(
		array(
			'name' => 'contains:marker_text', 
			'options' => array(
				'label' => '', 
				'placeholder' => 'Type Your Search and Hit Enter',
				'value' => !empty($this->request->params['named']['contains']) ? substr($this->request->params['named']['contains'], strpos($this->request->params['named']['contains'], ':') + 1) : null,
				)
			),
		)
	));
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
