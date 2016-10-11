<#1>
<?php
	$fields = array(
		'name'=>array(
			'type'=>'text',
			'length'=>50,
			'fixed'=>true
		),
		'value'=>array(
			'type'=>'blob',
			'length'=>3000
//			'fixed'=>true
		),
	);
	
	$ilDB->createTable('ui_uihk_pdinfo_config', $fields);
	$ilDB->addPrimaryKey('ui_uihk_pdinfo_config', array('name'));
?>
