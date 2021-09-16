<?php
$popupMeta = array (
    'moduleMain' => 'SMAcc_SM_Account',
    'varName' => 'SMAcc_SM_Account',
    'orderBy' => 'smacc_sm_account.name',
    'whereClauses' => array (
  'name' => 'smacc_sm_account.name',
  'assigned_user_id' => 'smacc_sm_account.assigned_user_id',
  'app_id' => 'smacc_sm_account.app_id',
  'date_entered' => 'smacc_sm_account.date_entered',
  'funded_date' => 'smacc_sm_account.funded_date',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'assigned_user_id',
  5 => 'app_id',
  6 => 'date_entered',
  7 => 'funded_date',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'label' => 'LBL_ASSIGNED_TO',
    'type' => 'enum',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
    'width' => '10%',
  ),
  'app_id' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_APP_ID',
    'width' => '10%',
    'name' => 'app_id',
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'name' => 'date_entered',
  ),
  'funded_date' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_FUNDED_DATE',
    'width' => '10%',
    'name' => 'funded_date',
  ),
),
);
