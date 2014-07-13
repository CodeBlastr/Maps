<div class="maps form">
	<?php echo $this->Form->create('Map');?>
	<?php echo $this->Form->input('Map.id'); ?>
	<div class="row">
		<div class="col-md-6">
			<fieldset>
		 		<legend><?php echo __('Address Details'); ?></legend>
				<?php echo $this->Form->input('Map.address', array('type' => 'text')); ?>
			</fieldset>
			<fieldset>
		 		<legend class="toggleClick"><?php echo __('Address Components'); ?></legend>
				<?php echo $this->Form->input('Map.street'); ?>
				<?php echo $this->Form->input('Map.city'); ?>
				<?php echo $this->Form->input('Map.state'); ?>
				<?php echo $this->Form->input('Map.country'); ?>
				<?php echo $this->Form->input('Map.postal'); ?>
				
				<?php echo $this->Form->input('Map.is_manual', array('label' => 'Manual? <small>To edit latitude and longitude this must be checked.</small>')); ?>
				<?php echo $this->Form->input('Map.latitude'); ?>
				<?php echo $this->Form->input('Map.longitude'); ?>
				<?php echo $this->Form->input('Map.formatted', array('type' => 'text')); ?>
				
			</fieldset>
		</div>
		<div class="col-md-6">
			<fieldset>
		 		<legend><?php echo __('Address Display'); ?></legend>
				<?php echo $this->Form->input('Map.marker_icon'); ?>
				<?php echo $this->Form->input('Map.marker_text'); ?>
				<?php echo $this->Form->input('Map.search_tags'); ?>
			</fieldset>
		</div>
	</div>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Maps',
		'items' => array(
			$this->Html->link(__('Search'), array('controller' => 'maps', 'action' => 'search')),
			$this->Html->link(__('List'), array('controller' => 'maps', 'action' => 'index')),
			$this->Html->link(__('Add'), array('controller' => 'maps', 'action' => 'add'))
			)
		)
	)));