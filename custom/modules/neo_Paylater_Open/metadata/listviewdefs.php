<?php
$module_name = 'neo_Paylater_Open';
$listViewDefs [$module_name] = 
array (
  'APPLICATION_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_APPLICATION_ID',
    'width' => '10%',
    'default' => true,
    'link' => true,
  ),
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
      ),
  'PHONE_NUMBER' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PHONE_NUMBER',
    'width' => '10%',
    'default' => true,
  ),
  'EMAIL_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_EMAIL_ID',
    'width' => '10%',
    'default' => true,
  ),
  'ALTERNATE_EMAIL_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ALTERNATE_EMAIL_ID',
    'width' => '10%',
    'default' => true,
  ),
  'ESCALATION_LEVEL' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ESCALATION_LEVEL',
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
  'STATUS' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
);
?>
