<?php
$module_name = 'scrm_Cases';
$listViewDefs [$module_name] = 
array (
  'SPOC_TEAM' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SPOC_TEAM',
    'width' => '10%',
    'default' => true,
  ),
  'SCRM_CASES_USERS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_SCRM_CASES_USERS_FROM_USERS_TITLE',
    'id' => 'SCRM_CASES_USERSUSERS_IDA',
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
