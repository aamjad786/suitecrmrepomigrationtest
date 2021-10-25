<?php
$popupMeta = array (
    'moduleMain' => 'Prospect',
    'varName' => 'PROSPECT',
    'orderBy' => 'prospects.last_name, prospects.first_name',
    'whereClauses' => array (
  'phone' => 'prospects.phone',
  'email' => 'prospects.email',
  'do_not_call' => 'prospects.do_not_call',
  'source_c' => 'prospects_cstm.source_c',
  'assigned_user_id' => 'prospects.assigned_user_id',
  'primary_address_city' => 'prospects.primary_address_city',
  'name' => 'prospects.name',
  'dq_score_c' => 'prospects_cstm.dq_score_c',
  'ps_score_c' => 'prospects_cstm.ps_score_c',
  'call_back_c' => 'prospects_cstm.call_back_c',
  'industry_c' => 'prospects_cstm.industry_c',
),
    'searchInputs' => array (
  2 => 'phone',
  3 => 'email',
  7 => 'do_not_call',
  8 => 'source_c',
  11 => 'assigned_user_id',
  13 => 'primary_address_city',
  16 => 'name',
  17 => 'dq_score_c',
  18 => 'ps_score_c',
  19 => 'call_back_c',
  20 => 'industry_c',
),
    'create' => array (
  'formBase' => 'ProspectFormBase.php',
  'formBaseClass' => 'ProspectFormBase',
  'getFormBodyParams' => 
  array (
    0 => '',
    1 => '',
    2 => 'ProspectSave',
  ),
  'createButton' => 'LNK_NEW_PROSPECT',
),
    'searchdefs' => array (
  'name' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '10%',
    'name' => 'name',
  ),
  'phone' => 
  array (
    'name' => 'phone',
    'label' => 'LBL_ANY_PHONE',
    'type' => 'name',
    'width' => '10%',
  ),
  'email' => 
  array (
    'name' => 'email',
    'label' => 'LBL_ANY_EMAIL',
    'type' => 'name',
    'width' => '10%',
  ),
  'call_back_c' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_CALL_BACK',
    'width' => '10%',
    'name' => 'call_back_c',
  ),
  'primary_address_city' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRIMARY_ADDRESS_CITY',
    'width' => '10%',
    'name' => 'primary_address_city',
  ),
  'source_c' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'Target Source',
    'width' => '10%',
    'name' => 'source_c',
  ),
  'do_not_call' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_DO_NOT_CALL',
    'width' => '10%',
    'name' => 'do_not_call',
  ),
  'dq_score_c' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_DQ_SCORE',
    'width' => '10%',
    'name' => 'dq_score_c',
  ),
  'ps_score_c' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_PS_SCORE',
    'width' => '10%',
    'name' => 'ps_score_c',
  ),
  'industry_c' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_INDUSTRY',
    'width' => '10%',
    'name' => 'industry_c',
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'type' => 'enum',
    'label' => 'LBL_ASSIGNED_TO',
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
),
    'listviewdefs' => array (
  'FULL_NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'related_fields' => 
    array (
      0 => 'salutation',
      1 => 'first_name',
      2 => 'last_name',
    ),
    'orderBy' => 'last_name',
    'default' => true,
    'name' => 'full_name',
  ),
  'EMAIL1' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_EMAIL_ADDRESS',
    'sortable' => false,
    'link' => false,
    'default' => true,
    'name' => 'email1',
  ),
  'PHONE_MOBILE' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'default' => true,
    'name' => 'phone_mobile',
  ),
  'DISPOSITION_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_DISPOSITION',
    'width' => '10%',
    'name' => 'disposition_c',
  ),
  'SUB_DISPOSITION_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_SUB_DISPOSITION',
    'width' => '10%',
    'name' => 'sub_disposition_c',
  ),
  'PRIMARY_ADDRESS_CITY' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRIMARY_ADDRESS_CITY',
    'width' => '10%',
    'default' => true,
    'name' => 'primary_address_city',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
    'name' => 'assigned_user_name',
  ),
  'TARGET_DATE_ASSIGNED_C' => 
  array (
    'type' => 'datetimecombo',
    'default' => true,
    'label' => 'LBL_TARGET_DATE_ASSIGNED',
    'width' => '10%',
    'name' => 'target_date_assigned_c',
  ),
),
);
