<div class="maps form">
	<?php echo $this->Form->create('Map');?>
	<fieldset>
 		<legend><?php echo __('Add Location'); ?></legend>
		<?php echo $this->Form->input('Map.id'); ?>
		<?php echo $this->Form->input('Map.street'); ?>
		<?php echo $this->Form->input('Map.city'); ?>
		<?php echo $this->Form->input('Map.state'); ?>
		<?php echo $this->Form->input('Map.country'); ?>
		<?php echo $this->Form->input('Map.postal'); ?>
		<?php echo $this->Form->input('Map.marker_text'); ?>
		<?php echo $this->Form->input('Map.search_tags'); ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>


<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Maps',
		'items' => array(
			$this->Html->link(__('Search'), array('controller' => 'maps', 'action' => 'search')),
			$this->Html->link(__('List'), array('controller' => 'maps', 'action' => 'list')),
			$this->Html->link(__('Add'), array('controller' => 'maps', 'action' => 'add'))
			)
		)
	)));