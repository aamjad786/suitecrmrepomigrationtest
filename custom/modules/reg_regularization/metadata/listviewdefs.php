<?php
$module_name = 'reg_regularization';
$listViewDefs [$module_name] = 
array (
  'APP_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'Application ID',
    'width' => '10%',
    'default' => true,
    'link' => true,
  ),
  'MERCHANT_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'Merchant Name',
    'width' => '10%',
    'default' => true,
  ),
  'REGULARIZATION_DATE' => 
  array (
    'type' => 'date',
    'label' => 'Regularization Date',
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
);
?>
