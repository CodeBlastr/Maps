<div class="maps form">
<?php echo $this->Form->create('Map');?>
	<fieldset>
 		<legend><?php echo __('Add Location'); ?></legend>
	<?php
		echo $this->Form->input('street');
		echo $this->Form->input('city');
		echo $this->Form->input('state');
		echo $this->Form->input('country');
		echo $this->Form->input('postal');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
