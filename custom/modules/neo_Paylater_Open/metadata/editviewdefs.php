<?php
$module_name = 'neo_Paylater_Open';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'application_id',
            'label' => 'LBL_APPLICATION_ID',
          ),
          1 => 'name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'entity_name',
            'label' => 'LBL_ENTITY_NAME',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'email_id',
            'label' => 'LBL_EMAIL_ID',
          ),
          1 => 
          array (
            'name' => 'alternate_email_id',
            'label' => 'LBL_ALTERNATE_EMAIL_ID',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'phone_number',
            'label' => 'LBL_PHONE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'alternate_phone_number',
            'label' => 'LBL_ALTERNATE_PHONE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'city',
            'label' => 'LBL_CITY',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'product',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT',
          ),
          1 => 
          array (
            'name' => 'customer_query',
            'label' => 'Customer query',
          ),
        ),
        6 => 
        array (
          0 => 'assigned_user_name',
          1 => 
          array (
            'name' => 'escalation_level',
            'label' => 'LBL_ESCALATION_LEVEL',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'transaction_status',
            'studio' => 'visible',
            'label' => 'LBL_TRANSACTION_STATUS',
          ),
          1 => 
          array (
            'name' => 'transaction_status_remarks',
            'studio' => 'visible',
            'label' => 'LBL_TRANSACTION_STATUS_REMARKS',
          ),
        ),
        8 => 
        array (
          0 => 'description',
          1 => '',
        ),
      ),
    ),
  ),
);
?>
