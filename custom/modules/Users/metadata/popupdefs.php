<?php
global $current_user;
$popupMeta = array (
    'moduleMain' => 'User',
    'varName' => 'USER',
    'orderBy' => 'user_name',
    'whereClauses' => array (
  'first_name' => 'users.first_name',
  'last_name' => 'users.last_name',
  'user_name' => 'users.user_name',
  'is_group' => 'users.is_group',
  'reports_to_name' => 'users.reports_to_name',
  'status' => 'users.status',
  'is_admin' => 'users.is_admin',
  'title' => 'users.title',
  'department' => 'users.department',
  'phone' => 'users.phone',
  'address_street' => 'users.address_street',
  'email' => 'users.email',
  'address_city' => 'users.address_city',
  'address_state' => 'users.address_state',
  'address_postalcode' => 'users.address_postalcode',
  'address_country' => 'users.address_country',
),
	'whereStatement'=> " users.status = 'Active' and users.portal_only= '0'",
	'whereStatement'=> " users.status = 'Active' and users.portal_only= '0' and users.delated = '0'",
    'searchInputs' => array (
  0 => 'first_name',
  1 => 'last_name',
  2 => 'user_name',
  3 => 'is_group',
  4 => 'reports_to_name',
  5 => 'status',
  6 => 'is_admin',
  7 => 'title',
  8 => 'department',
  9 => 'phone',
  10 => 'address_street',
  11 => 'email',
  12 => 'address_city',
  13 => 'address_state',
  14 => 'address_postalcode',
  15 => 'address_country',
),
    'searchdefs' => array (
  'first_name' => 
  array (
    'name' => 'first_name',
    'width' => '10%',
  ),
  'last_name' => 
  array (
    'name' => 'last_name',
    'width' => '10%',
  ),
  'user_name' => 
  array (
    'name' => 'user_name',
    'width' => '10%',
  ),
  'reports_to_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_REPORTS_TO_NAME',
    'id' => 'REPORTS_TO_ID',
    'width' => '10%',
    'name' => 'reports_to_name',
   'displayParams' => array(
            'initial_filter' => '&reports_to_name_advanced='.$current_user->user_name,
        ),
  ),
  'status' => 
  array (
    'name' => 'status',
    'width' => '10%',
  ),
  'is_admin' => 
  array (
    'name' => 'is_admin',
    'width' => '10%',
  ),
  'title' => 
  array (
    'name' => 'title',
    'width' => '10%',
  ),
  'is_group' => 
  array (
    'name' => 'is_group',
    'width' => '10%',
  ),
  'department' => 
  array (
    'name' => 'department',
    'width' => '10%',
  ),
  'phone' => 
  array (
    'name' => 'phone',
    'label' => 'LBL_ANY_PHONE',
    'type' => 'name',
    'width' => '10%',
  ),
  'address_street' => 
  array (
    'name' => 'address_street',
    'label' => 'LBL_ANY_ADDRESS',
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
  'address_city' => 
  array (
    'name' => 'address_city',
    'label' => 'LBL_CITY',
    'type' => 'name',
    'width' => '10%',
  ),
  'address_state' => 
  array (
    'name' => 'address_state',
    'label' => 'LBL_STATE',
    'type' => 'name',
    'width' => '10%',
  ),
  'address_postalcode' => 
  array (
    'name' => 'address_postalcode',
    'label' => 'LBL_POSTAL_CODE',
    'type' => 'name',
    'width' => '10%',
  ),
  'address_country' => 
  array (
    'name' => 'address_country',
    'label' => 'LBL_COUNTRY',
    'type' => 'name',
    'width' => '10%',
  ),
),
);
