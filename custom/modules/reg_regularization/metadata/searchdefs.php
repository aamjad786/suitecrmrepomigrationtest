<?php
$module_name = 'reg_regularization';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'app_id' => 
      array (
        'type' => 'varchar',
        'label' => 'Application ID',
        'width' => '10%',
        'default' => true,
        'name' => 'app_id',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'regularization_date' => 
      array (
        'type' => 'date',
        'label' => 'Regularization Date',
        'width' => '10%',
        'default' => true,
        'name' => 'regularization_date',
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
      'app_id' => 
      array (
        'type' => 'varchar',
        'label' => 'Application ID',
        'width' => '10%',
        'default' => true,
        'name' => 'app_id',
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
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'regularization_date' => 
      array (
        'type' => 'date',
        'label' => 'Regularization Date',
        'width' => '10%',
        'default' => true,
        'name' => 'regularization_date',
      ),
      'insurance' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'Insurance',
        'width' => '10%',
        'default' => true,
        'name' => 'insurance',
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
