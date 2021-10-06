<?php
$module_name = 'Neo_Paylater_Leads';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 
      array (
        'name' => 'search_name',
        'label' => 'LBL_NAME',
        'type' => 'name',
      ),
      1 => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
      ),
    ),
    'advanced_search' => 
    array (
      'business_name' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_BUSINESS_NAME',
        'width' => '10%',
        'default' => true,
        'name' => 'business_name',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'primary_address_city' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_PRIMARY_ADDRESS_CITY',
        'width' => '10%',
        'default' => true,
        'name' => 'primary_address_city',
      ),
      'callback' => 
      array (
        'type' => 'datetimecombo',
        'label' => 'LBL_CALLBACK',
        'width' => '10%',
        'default' => true,
        'name' => 'callback',
      ),
      'phone_mobile' => 
      array (
        'type' => 'phone',
        'label' => 'LBL_MOBILE_PHONE',
        'width' => '10%',
        'default' => true,
        'name' => 'phone_mobile',
      ),
      'meeting' => 
      array (
        'type' => 'datetimecombo',
        'label' => 'LBL_MEETING',
        'width' => '10%',
        'default' => true,
        'name' => 'meeting',
      ),
      'disposition' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_DISPOSITION',
        'width' => '10%',
        'default' => true,
        'name' => 'disposition',
      ),
      'email' => 
      array (
        'name' => 'email',
        'default' => true,
        'width' => '10%',
      ),
      'partner_name' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PARTNER_NAME',
        'width' => '10%',
        'default' => true,
        'name' => 'partner_name',
      ),
      'pre_approved_limit' => 
      array (
        'type' => 'int',
        'label' => 'LBL_PRE_APPROVED_LIMIT',
        'width' => '10%',
        'default' => true,
        'name' => 'pre_approved_limit',
      ),
      'as_application_id_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_AS_APPLICATION_ID',
        'width' => '10%',
        'name' => 'as_application_id_c',
      ),
      'as_lead_status_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_AS_LEAD_STATUS',
        'width' => '10%',
        'name' => 'as_lead_status_c',
      ),
      'customer_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_CUSTOMER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'customer_id',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
?>