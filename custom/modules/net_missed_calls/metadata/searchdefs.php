<?php
$module_name = 'net_missed_calls';
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
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'user_mobile_number' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_USER_MOBILE_NUMBER',
        'width' => '10%',
        'default' => true,
        'name' => 'user_mobile_number',
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
        'name' => 'name',
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
      'call_received_at' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_CALL_RECEIVED_AT',
        'width' => '10%',
        'default' => true,
        'name' => 'call_received_at',
      ),
      'circle' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_CIRCLE',
        'width' => '10%',
        'default' => true,
        'name' => 'circle',
      ),
      'receiving_number' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_RECEIVING_NUMBER',
        'width' => '10%',
        'default' => true,
        'name' => 'receiving_number',
      ),
      'operator' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_OPERATOR',
        'width' => '10%',
        'default' => true,
        'name' => 'operator',
      ),
      'user_mobile_number' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_USER_MOBILE_NUMBER',
        'width' => '10%',
        'default' => true,
        'name' => 'user_mobile_number',
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
