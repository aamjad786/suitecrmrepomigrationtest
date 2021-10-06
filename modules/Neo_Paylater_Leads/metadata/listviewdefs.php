<?php
$module_name = 'Neo_Paylater_Leads';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_NAME',
    'link' => true,
    'orderBy' => 'last_name',
    'default' => true,
    'related_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
      2 => 'salutation',
    ),
  ),
  'BUSINESS_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_BUSINESS_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'PHONE_MOBILE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_MOBILE_PHONE',
    'default' => true,
  ),
  'ADDRESS_CITY' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PRIMARY_ADDRESS_CITY',
    'default' => true,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => true,
  ),
  'DISPOSITION' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_DISPOSITION',
    'width' => '10%',
    'default' => true,
  ),
  'SUBDISPOSITION' => 
  array (
    'type' => 'dynamicenum',
    'studio' => 'visible',
    'label' => 'LBL_SUBDISPOSITION',
    'width' => '10%',
    'default' => true,
  ),
  'FE_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_FE_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'CALLBACK' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_CALLBACK',
    'width' => '10%',
    'default' => true,
  ),
  'MEETING' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_MEETING',
    'width' => '10%',
    'default' => true,
  ),
  'MISSING_DOCUMENTS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_MISSING_DOCUMENTS',
    'width' => '10%',
    'default' => true,
  ),
  'PHONE_HOME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_HOME_PHONE',
    'default' => false,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'PHONE_OTHER' => 
  array (
    'width' => '10%',
    'label' => 'LBL_WORK_PHONE',
    'default' => false,
  ),
  'MERCHANT_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_MERCHANT_TYPE',
    'width' => '10%',
    'default' => false,
  ),
  'PRIMARY_ADDRESS_POSTALCODE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
    'width' => '10%',
    'default' => false,
  ),
  'PRE_APPROVED_LIMIT' => 
  array (
    'type' => 'int',
    'label' => 'LBL_PRE_APPROVED_LIMIT',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_STREET' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PRIMARY_ADDRESS_STREET',
    'default' => false,
  ),
  'PARTNER_NAME' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_PARTNER_NAME',
    'width' => '10%',
    'default' => false,
  ),
  'CAMPAIGN' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CAMPAIGN',
    'width' => '10%',
    'default' => false,
  ),
  'ADDRESS_STATE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PRIMARY_ADDRESS_STATE',
    'default' => false,
  ),
  'ADDRESS_POSTALCODE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_CREATED',
    'default' => false,
  ),
  'EMAIL1' => 
  array (
    'width' => '15%',
    'label' => 'LBL_EMAIL_ADDRESS',
    'sortable' => false,
    'link' => true,
    'customCode' => '{$EMAIL1_LINK}{$EMAIL1}</a>',
    'default' => false,
  ),
);
?>
