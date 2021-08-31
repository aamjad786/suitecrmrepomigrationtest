<?php
$module_name = 'scrm_Targets_History';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'MONTH' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_MONTH',
    'width' => '10%',
    'default' => true,
  ),
  'TARGET' => 
  array (
    'type' => 'int',
    'label' => 'LBL_TARGET',
    'width' => '10%',
    'default' => true,
  ),
  'ACHIEVED' => 
  array (
    'type' => 'int',
    'label' => 'LBL_ACHIEVED',
    'width' => '10%',
    'default' => true,
  ),
  'PENDING' => 
  array (
    'type' => 'int',
    'label' => 'LBL_PENDING',
    'width' => '10%',
    'default' => true,
  ),
  'SALES_TARGET' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_SALES_TARGET',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'TARGET_AMOUNT_ACHIEVED' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_TARGET_AMOUNT_ACHIEVED',
    'currency_format' => true,
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
);
?>
