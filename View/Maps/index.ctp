<div class="maps index">
<h2><?php echo __('Maps');?></h2>
<table cellpadding="0" cellspacing="0">
<tr>
	<th>Street</th>
	<th><?php echo $this->Paginator->sort('city');?></th>
	<th><?php echo $this->Paginator->sort('state');?></th>
	<th><?php echo $this->Paginator->sort('country');?></th>
	<th><?php echo $this->Paginator->sort('postal');?></th>
	<th><?php echo $this->Paginator->sort('marker_text');?></th>	
</tr>
<?php
$i = 0;
foreach ($locations as $location):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
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
			<?php echo $location['Map']['marker_text']; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->Element('paging'); ?>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Search Location', true), array('action' => 'search'));?></li>
		<li><?php echo $this->Html->link(__('Add Location', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
