<?php
$module_name = 'neo_Paylater_Open';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'application_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_APPLICATION_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'application_id',
      ),
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'status',
      ),
      'product' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PRODUCT',
        'width' => '10%',
        'default' => true,
        'name' => 'product',
      ),
      'escalation_level' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ESCALATION_LEVEL',
        'width' => '10%',
        'default' => true,
        'name' => 'escalation_level',
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
      'application_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_APPLICATION_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'application_id',
      ),
      'phone_number' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_PHONE_NUMBER',
        'width' => '10%',
        'default' => true,
        'name' => 'phone_number',
      ),
      'email_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_EMAIL_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'email_id',
      ),
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
      'escalation_level' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ESCALATION_LEVEL',
        'width' => '10%',
        'default' => true,
        'name' => 'escalation_level',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'status',
      ),
      'city' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_CITY',
        'width' => '10%',
        'default' => true,
        'name' => 'city',
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
