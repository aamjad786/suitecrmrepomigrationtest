<?php
$module_name = 'net_missed_calls';
$listViewDefs [$module_name] = 
array (
  'RECEIVING_NUMBER' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_RECEIVING_NUMBER',
    'width' => '10%',
    'default' => true,
  ),
  'CIRCLE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CIRCLE',
    'width' => '10%',
    'default' => true,
  ),
  'OPERATOR' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_OPERATOR',
    'width' => '10%',
    'default' => true,
  ),
  'USER_MOBILE_NUMBER' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_USER_MOBILE_NUMBER',
    'width' => '10%',
    'default' => true,
  ),
  'CALL_RECEIVED_AT' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CALL_RECEIVED_AT',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => false,
    'link' => true,
  ),
);
?>
