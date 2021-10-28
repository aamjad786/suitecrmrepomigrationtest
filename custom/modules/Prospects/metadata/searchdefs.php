<?php
$searchdefs ['Prospects'] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'search_name' => 
      array (
        'name' => 'search_name',
        'label' => 'LBL_NAME',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'shop_name_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_SHOP_NAME',
        'width' => '10%',
        'name' => 'shop_name_c',
      ),
      'primary_address_city' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_PRIMARY_ADDRESS_CITY',
        'width' => '10%',
        'default' => true,
        'name' => 'primary_address_city',
      ),
      'primary_address_state' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_PRIMARY_ADDRESS_STATE',
        'width' => '10%',
        'default' => true,
        'name' => 'primary_address_state',
      ),
      'phone' => 
      array (
        'name' => 'phone',
        'label' => 'LBL_ANY_PHONE',
        'type' => 'name',
        'width' => '10%',
        'default' => true,
      ),
      'email' => 
      array (
        'name' => 'email',
        'label' => 'LBL_ANY_EMAIL',
        'type' => 'name',
        'width' => '10%',
        'default' => true,
      ),
      'year_established_c' => 
      array (
        'type' => 'int',
        'default' => true,
        'label' => 'LBL_YEAR_ESTABLISHED',
        'width' => '10%',
        'name' => 'year_established_c',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'type' => 'name',
        'link' => true,
        'label' => 'LBL_NAME',
        'width' => '10%',
        'default' => true,
        'name' => 'name',
      ),
      'phone' => 
      array (
        'name' => 'phone',
        'label' => 'LBL_ANY_PHONE',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'email' => 
      array (
        'name' => 'email',
        'label' => 'LBL_ANY_EMAIL',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'address_state' => 
      array (
        'name' => 'address_state',
        'label' => 'LBL_STATE',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'primary_address_city' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_PRIMARY_ADDRESS_CITY',
        'width' => '10%',
        'default' => true,
        'name' => 'primary_address_city',
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
        'default' => true,
        'width' => '10%',
      ),
      'call_back_c' => 
      array (
        'type' => 'datetimecombo',
        'default' => true,
        'label' => 'LBL_CALL_BACK',
        'width' => '10%',
        'name' => 'call_back_c',
      ),
      'target_date_assigned_c' => 
      array (
        'type' => 'datetimecombo',
        'default' => true,
        'label' => 'LBL_TARGET_DATE_ASSIGNED',
        'width' => '10%',
        'name' => 'target_date_assigned_c',
      ),
      'industry_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_INDUSTRY',
        'width' => '10%',
        'name' => 'industry_c',
      ),
      'do_not_call' => 
      array (
        'name' => 'do_not_call',
        'default' => true,
        'width' => '10%',
      ),
      'dq_score_c' => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_DQ_SCORE',
        'width' => '10%',
        'name' => 'dq_score_c',
      ),
      'ps_score_c' => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_PS_SCORE',
        'width' => '10%',
        'name' => 'ps_score_c',
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
