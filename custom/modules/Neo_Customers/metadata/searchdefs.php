<?php
$module_name = 'Neo_Customers';
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
      'queue_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_QUEUE_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'queue_type',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'assigned_user_name' => 
      array (
        'link' => true,
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'assigned_user_name',
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
      'customer_id' => 
      array (
        'type' => 'int',
        'label' => 'LBL_CUSTOMER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'customer_id',
      ),
      'as_stage' => 
      array (
        'type' => 'varchar',
        'label' => 'AS stage',
        'width' => '10%',
        'default' => true,
        'name' => 'as_stage',
      ),
      'mobile' => 
      array (
        'type' => 'phone',
        'label' => 'LBL_MOBILE',
        'width' => '10%',
        'default' => true,
        'name' => 'mobile',
      ),
      'app_id_list' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_APP_ID_LIST',
        'width' => '10%',
        'default' => true,
        'name' => 'app_id_list',
      ),
      'assigned_user_name' => 
      array (
        'link' => true,
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'width' => '10%',
        'default' => true,
        'id' => 'ASSIGNED_USER_ID',
        'name' => 'assigned_user_name',
      ),
      'renewal_eligiblity_amount' => 
      array (
        'type' => 'int',
        'label' => 'LBL_RENEWAL_ELIGIBLITY_AMOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'renewal_eligiblity_amount',
      ),
      'loan_amount' => 
      array (
        'type' => 'int',
        'label' => 'LBL_LOAN_AMOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'loan_amount',
      ),
      'queue_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'default' => true,
        'label' => 'LBL_QUEUE_TYPE',
        'width' => '10%',
        'name' => 'queue_type',
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
      'location' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_LOCATION',
        'width' => '10%',
        'default' => true,
        'name' => 'location',
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
