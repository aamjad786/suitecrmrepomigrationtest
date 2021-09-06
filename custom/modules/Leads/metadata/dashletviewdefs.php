<?php
$dashletData['LeadsDashlet']['searchFields'] = array (
  'date_entered' => 
  array (
    'default' => '',
  ),
  'status' => 
  array (
    'default' => '',
  ),
  'disposition_c' => 
  array (
    'default' => '',
  ),
  'call_back_date_time_c' => 
  array (
    'default' => '',
  ),
);
$dashletData['LeadsDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'link' => true,
    'default' => true,
    'related_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
      2 => 'salutation',
    ),
    'name' => 'name',
  ),
  'status' => 
  array (
    'width' => '10%',
    'label' => 'LBL_STATUS',
    'name' => 'status',
    'default' => true,
  ),
  'disposition_c' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_DISPOSITION',
    'width' => '10%',
    'name' => 'disposition_c',
  ),
  'call_disposition_history_c' => 
  array (
    'type' => 'text',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_CALL_DISPOSITION_HISTORY',
    'sortable' => false,
    'width' => '20%',
    'name' => 'call_disposition_history_c',
  ),
  'phone_mobile' => 
  array (
    'width' => '10%',
    'label' => 'LBL_MOBILE_PHONE',
    'name' => 'phone_mobile',
    'default' => true,
  ),
  'call_back_date_time_c' => 
  array (
    'type' => 'datetimecombo',
    'default' => true,
    'label' => 'LBL_CALL_BACK_DATE_TIME',
    'width' => '10%',
    'name' => 'call_back_date_time_c',
  ),
  'lead_source' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LEAD_SOURCE',
    'name' => 'lead_source',
    'default' => false,
  ),
  'phone_work' => 
  array (
    'width' => '20%',
    'label' => 'LBL_OFFICE_PHONE',
    'default' => false,
    'name' => 'phone_work',
  ),
  'title' => 
  array (
    'width' => '20%',
    'label' => 'LBL_TITLE',
    'default' => false,
    'name' => 'title',
  ),
  'account_name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_ACCOUNT_NAME',
    'name' => 'account_name',
    'default' => false,
  ),
  'email1' => 
  array (
    'width' => '10%',
    'label' => 'LBL_EMAIL_ADDRESS',
    'sortable' => false,
    'customCode' => '{$EMAIL1_LINK}{$EMAIL1}</a>',
    'default' => false,
    'name' => 'email1',
  ),
  'phone_home' => 
  array (
    'width' => '10%',
    'label' => 'LBL_HOME_PHONE',
    'name' => 'phone_home',
    'default' => false,
  ),
  'phone_other' => 
  array (
    'width' => '10%',
    'label' => 'LBL_OTHER_PHONE',
    'name' => 'phone_other',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_DATE_ENTERED',
    'name' => 'date_entered',
    'default' => false,
  ),
  'date_modified' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_MODIFIED',
    'name' => 'date_modified',
    'default' => false,
  ),
  'created_by' => 
  array (
    'width' => '8%',
    'label' => 'LBL_CREATED',
    'name' => 'created_by',
    'default' => false,
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => false,
  ),
);
