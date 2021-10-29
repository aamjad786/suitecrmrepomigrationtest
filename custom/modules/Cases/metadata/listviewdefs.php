<?php
$listViewDefs ['Cases'] = 
array (
  'CASE_NUMBER' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_NUMBER',
    'default' => true,
    'link' => true,
  ),
  'NAME' => 
  array (
    'width' => '25%',
    'label' => 'LBL_LIST_SUBJECT',
    'link' => true,
    'default' => true,
  ),
  'MERCHANT_ESTABLISMENT_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_MERCHANT_ESTABLISMENT',
    'width' => '10%',
  ),
  'MERCHANT_NAME_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_MERCHANT_NAME',
    'width' => '10%',
  ),
  'MERCHANT_APP_ID_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_MERCHANT_APP_ID',
    'width' => '10%',
  ),
  'PRIORITY' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_PRIORITY',
    'default' => true,
  ),
  'STATE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_STATE',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
  ),
  'EMAIL_SOURCE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_EMAIL_SOURCE',
    'width' => '10%',
    'default' => true,
  ),
  'COMPLAINTAINT_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_COMPLAINTAINT',
    'width' => '10%',
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => true,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'STATUS' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_STATUS',
    'default' => false,
  ),
  'ATTENDED_BY_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_ATTENDED_BY',
    'width' => '10%',
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => false,
  ),
  'AGE_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_AGE',
    'width' => '10%',
  ),
  'ESCALATION_LEVEL_C' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_ESCALATION_LEVEL',
    'width' => '10%',
  ),
  'MERCHANT_EMAIL_ID_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_MERCHANT_EMAIL_ID',
    'width' => '10%',
  ),
  'DATE_RESOLVED_C' => 
  array (
    'type' => 'datetimecombo',
    'default' => false,
    'label' => 'LBL_DATE_RESOLVED',
    'width' => '10%',
  ),
  'CASE_LOCATION_C' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_CASE_LOCATION',
    'width' => '10%',
  ),
  'DATE_ATTENDED_C' => 
  array (
    'type' => 'datetimecombo',
    'default' => false,
    'label' => 'LBL_DATE_ATTENDED',
    'width' => '10%',
  ),
  'MERCHANT_CONTACT_NUMBER_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_MERCHANT_CONTACT_NUMBER',
    'width' => '10%',
  ),
  'CASE_SUBCATEGORY_C' => 
  array (
    'type' => 'dynamicenum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_CASE_SUBCATEGORY',
    'width' => '10%',
  ),
  'CASE_CATEGORY_C' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_CASE_CATEGORY',
    'width' => '10%',
  ),
  'CASE_SOURCE_C' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_CASE_SOURCE',
    'width' => '10%',
  ),
);
?>
