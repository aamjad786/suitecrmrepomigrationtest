<?php 
$dictionary['contact_sms_sms'] =  array(
  'relationships' => array (
	'contact_sms_sms' => 
	array (
	'lhs_module'=> 'Contacts', 
	'lhs_table'=> 'contacts', 
	'lhs_key' => 'id',
	'rhs_module'=> 'SMS_SMS', 
	'rhs_table'=> 'sms_sms', 
	'rhs_key' => 'parent_id',
	'relationship_type'=>'one-to-many', 
	'relationship_role_column'=>'parent_type',
	'relationship_role_column_value'=>'Contacts',
		),
	  ),
	'fields' => '',
  'indices' => '',
  'table' => '',								
);
?>