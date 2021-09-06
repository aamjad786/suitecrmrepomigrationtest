<?php
$module_name = 'scrm_Disposition_History';
$listViewDefs [$module_name] = 
array (
  'CALL_PICKUP_DATETIME_C' => 
  array (
    'type' => 'datetimecombo',
    'default' => true,
    'label' => 'LBL_CALL_PICKUP_DATETIME',
    'width' => '10%',
  ),
  'DISPOSITION_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_DISPOSITION',
    'width' => '10%',
  ),
  'SUB_DISPOSITION_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_SUB_DISPOSITION',
    'width' => '40%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'REMARKS_C' => 
  array (
    'type' => 'text',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_REMARKS',
    'sortable' => false,
    'width' => '10%',
  ),
  'CALL_DISPOSITION_HISTORY_C' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_CALL_DISPOSITION_HISTORY',
    'width' => '10%',
  ),
);
?>
