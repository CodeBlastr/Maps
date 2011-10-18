<?php 
if(isset($locations)) {
	echo $this->Html->script('http://maps.google.com/maps/api/js?sensor=false', array('inline' => false));
	echo $this->Html->css('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/base/jquery-ui.css', array('inline' => false));
	echo $this->Html->css('http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css', array('inline' => false));
	echo $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js', array('inline' => false));
	
	echo $this->Html->script('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js', array('inline' => false));
	echo $this->Html->script('http://jquery-ui.googlecode.com/svn/tags/latest/external/jquery.bgiframe-2.1.1.js', array('inline' => false));
	echo $this->Html->script('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/i18n/jquery-ui-i18n.min.js', array('inline' => false));
}

echo $this->Element('map');
?>
