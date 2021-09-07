<?php
$module_name = 'SMAcc_SM_Account';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'app_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_APP_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'app_id',
      ),
      'welcome_call_status' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_WELCOME_CALL_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'welcome_call_status',
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
        'default' => true,
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'funded_date' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_FUNDED_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'funded_date',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
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
        'default' => true,
        'width' => '10%',
      ),
      'app_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_APP_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'app_id',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'funded_date' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_FUNDED_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'funded_date',
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
