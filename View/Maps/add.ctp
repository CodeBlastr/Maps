<div class="maps form">
	<?php echo $this->Form->create('Map');?>
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
// jquery scripts for this page
$script = <<< EOT
	jQuery(function() {
		jQuery('#MapAddForm').on('click', '.toggleClick', function() {
			jQuery('#MapAddress').val('');
			jQuery('#MapAddress').closest('div').hide();
		});
	});
EOT;

$this->Html->scriptBlock($script, array('inline' => false));
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Maps',
		'items' => array(
			$this->Html->link(__('Search'), array('controller' => 'maps', 'action' => 'search'))
			)
		)
	)));