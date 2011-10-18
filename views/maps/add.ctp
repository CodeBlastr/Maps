<div class="maps form">
<?php echo $this->Form->create('Map');?>
	<fieldset>
 		<legend><?php echo __('Add Location'); ?></legend>
	<?php
		echo $this->Form->input('Map.street');
		echo $this->Form->input('Map.city');
		echo $this->Form->input('Map.state');
		echo $this->Form->input('Map.country');
		echo $this->Form->input('Map.postal');
		echo $this->Form->input('Map.marker_text');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
