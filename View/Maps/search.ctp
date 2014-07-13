<div class="maps form">
	<?php echo $this->Form->create('Map', array('class' => 'form-inline', 'type' => 'get', 'url' => array('plugin' => 'maps', 'controller' => 'maps', 'action' => 'search'))); ?>
	<?php echo $this->Form->input('Map.q', array('label' => 'City State or Zip', 'value' => $this->request->query['q'], 'placeholder' => 'Location Search')); ?>
	<?php echo $this->Form->end(__('Search'));?>
	<?php echo __('<small>%s Location(s) Found</small>', count($locations)); ?>
</div>

<hr />

<?php echo $this->element('mapped', array('locations' => $locations)); ?>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Maps',
		'items' => array(
			$this->Html->link(__('List'), array('controller' => 'maps', 'action' => 'index')),
			$this->Html->link(__('Add'), array('controller' => 'maps', 'action' => 'add'))
			)
		)
	)));