<?php
$module_name = 'scrm_Escalation_Matrix';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '25%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'EMAIL_TEMPLATE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_EMAIL_TEMPLATE',
    'width' => '25%',
    'default' => true,
  ),
  'ESCALATION_HOURS' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_ESCALATION_HOURS',
    'width' => '20%',
    'default' => true,
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '20%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
?>
