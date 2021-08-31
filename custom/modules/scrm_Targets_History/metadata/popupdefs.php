<?php
$popupMeta = array (
    'moduleMain' => 'scrm_Targets_History',
    'varName' => 'scrm_Targets_History',
    'orderBy' => 'scrm_targets_history.name',
    'whereClauses' => array (
  'name' => 'scrm_targets_history.name',
),
    'searchInputs' => array (
  0 => 'scrm_targets_history_number',
  1 => 'name',
  2 => 'priority',
  3 => 'status',
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'MONTH' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_MONTH',
    'width' => '10%',
    'default' => true,
    'name' => 'month',
  ),
  'TARGET' => 
  array (
    'type' => 'int',
    'label' => 'LBL_TARGET',
    'width' => '10%',
    'default' => true,
    'name' => 'target',
  ),
  'ACHIEVED' => 
  array (
    'type' => 'int',
    'label' => 'LBL_ACHIEVED',
    'width' => '10%',
    'default' => true,
    'name' => 'achieved',
  ),
  'PENDING' => 
  array (
    'type' => 'int',
    'label' => 'LBL_PENDING',
    'width' => '10%',
    'default' => true,
    'name' => 'pending',
  ),
  'SALES_TARGET' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_SALES_TARGET',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
    'name' => 'sales_target',
  ),
  'TARGET_AMOUNT_ACHIEVED' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_TARGET_AMOUNT_ACHIEVED',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
    'name' => 'target_amount_achieved',
  ),
  'TARGET_AMOUNT_PENDING' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_TARGET_AMOUNT_PENDING',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
    'name' => 'target_amount_pending',
  ),
),
);
