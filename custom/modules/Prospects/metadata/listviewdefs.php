<?php
$listViewDefs ['Prospects'] = 
array (
  'FULL_NAME' => 
  array (
    'width' => '10%',
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
  ),
  'PHONE_MOBILE' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'default' => true,
  ),
  'EMAIL1' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_EMAIL_ADDRESS',
    'sortable' => false,
    'link' => false,
    'default' => true,
  ),
  'PRIMARY_ADDRESS_CITY' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRIMARY_ADDRESS_CITY',
    'width' => '10%',
    'default' => true,
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
    'width' => '10%',
  ),
  'CALL_BACK_C' => 
  array (
    'type' => 'datetimecombo',
    'default' => true,
    'label' => 'LBL_CALL_BACK',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'TARGET_DATE_ASSIGNED_C' => 
  array (
    'type' => 'datetimecombo',
    'default' => true,
    'label' => 'LBL_TARGET_DATE_ASSIGNED',
    'width' => '10%',
  ),
  'PHONE_WORK' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_PHONE',
    'link' => false,
    'default' => false,
  ),
  'DO_NOT_CALL' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_DO_NOT_CALL',
    'width' => '10%', 
  ),
  'TYPE_C' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
  'INDUSTRY_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_INDUSTRY',
    'width' => '10%',
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
  'TITLE' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_TITLE',
    'link' => false,
    'default' => false,
  ),
);
?>
