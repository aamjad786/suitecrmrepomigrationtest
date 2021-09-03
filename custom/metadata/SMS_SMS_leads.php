<?php 
// created: 2014-01-22 04:52:51
$dictionary['lead_sms_sms'] =  array(
  'relationships' => array (
	'lead_sms_sms' => 
	array (
	'lhs_module'=> 'Leads', 
	'lhs_table'=> 'leads', 
	'lhs_key' => 'id',
	'rhs_module'=> 'SMS_SMS', 
	'rhs_table'=> 'sms_sms', 
	'rhs_key' => 'parent_id',
	'relationship_type'=>'one-to-many', 
	'relationship_role_column'=>'parent_type',
	'relationship_role_column_value'=>'Leads',
		),
	  ),
	'fields' => '',
  'indices' => '',
  'table' => '',								
);
?>