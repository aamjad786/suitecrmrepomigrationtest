<?php
$module_name = 'reg_regularization';
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
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'app_id',
            'label' => 'Application ID',
          ),
          1 => 
          array (
            'name' => 'merchant_name',
            'label' => 'Merchant Name',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'branch',
            'comment' => 'branch',
            'label' => 'Branch',
          ),
          1 => 
          array (
            'name' => 'phone',
            'comment' => 'Phone no.',
            'label' => 'Contact No.',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'ppt_date',
            'comment' => 'PPT Date',
            'label' => 'PTP Date',
          ),
          1 => 
          array (
            'name' => 'ppt_amount',
            'comment' => 'PPT Date',
            'label' => 'PTP Amount',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'ppt_mode',
            'comment' => 'PPT Mode',
            'label' => 'PTP Mode',
          ),
          1 => 
          array (
            'name' => 'welcome_call_status',
            'studio' => 'visible',
            'label' => 'LBL_WELCOME_CALL_STATUS',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'remark',
            'comment' => 'Remarks',
            'label' => 'Remarks',
          ),
          1 => 
          array (
            'name' => 'insurance',
            'studio' => 'visible',
            'label' => 'Insurance',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'insurance_remarks',
            'comment' => 'Remarks',
            'label' => 'Insurance Remarks',
          ),
          1 => 
          array (
            'name' => 'regularization_category',
            'studio' => 'visible',
            'label' => 'Regularization Category',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'call_attempt_status',
            'studio' => 'visible',
            'label' => 'LBL_CALL_ATTEMPT_STATUS',
          ),
          1 => 
          array (
            'name' => 'call_updation',
            'studio' => 'visible',
            'label' => 'LBL_CALL_UPDATION',
          ),
        ),
      ),
    ),
  ),
);
?>
