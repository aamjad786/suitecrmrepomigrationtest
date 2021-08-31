<?php
$module_name = 'scrm_Cases';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'ISSUE_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_ISSUE_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'SUB_ISSUE_TYPE' => 
  array (
    'type' => 'dynamicenum',
    'studio' => 'visible',
    'label' => 'LBL_SUB_ISSUE_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'SPOC_TEAM' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SPOC_TEAM',
    'width' => '10%',
    'default' => true,
  ),
  'TAT_1' => 
  array (
    'type' => 'int',
    'label' => 'LBL_TAT_1',
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
  'SCRM_CASES_USERS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_SCRM_CASES_USERS_FROM_USERS_TITLE',
    'id' => 'SCRM_CASES_USERSUSERS_IDA',
    'width' => '10%',
    'default' => false,
  ),
);
?>
