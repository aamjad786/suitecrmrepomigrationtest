<?php
$module_name = 'SMAcc_SM_Account';
$listViewDefs [$module_name] = 
array (
  'APP_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_APP_ID',
    'width' => '10%',
    'default' => true,
    'link' => true,
  ),
  'MERCHANT_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_MERCHANT_NAME',
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
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'FUNDED_DATE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_FUNDED_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'WELCOME_CALL_STATUS' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_WELCOME_CALL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
);
?>
