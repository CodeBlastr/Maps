<div class="list-group">
	<?php foreach ($map['Map'] as $key => $value) : ?>
		<div class="list-group-item"> &nbsp; <?php echo $value; ?> <span class="badge"><?php echo $key; ?></span></div>
	<?php endforeach; ?>
</div>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Maps',
		'items' => array(
			$this->Html->link(__('Edit'), array('controller' => 'maps', 'action' => 'edit', $map['Map']['id'])),
			$this->Html->link(__('Delete'), array('controller' => 'maps', 'action' => 'delete', $map['Map']['id']), array(), 'Are you sure you want to delete ' . $map['Map']['street']),
			)
		)
	)));