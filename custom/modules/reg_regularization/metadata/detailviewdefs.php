<?php
$module_name = 'reg_regularization';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
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
        'LBL_DETAILVIEW_PANEL1' => 
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
            'name' => 'regularization_date',
            'comment' => 'Regularization Date',
            'label' => 'Regularization Date',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'phone',
            'comment' => 'Phone no.',
            'label' => 'Contact No.',
          ),
          1 => 
          array (
            'name' => 'branch',
            'comment' => 'branch',
            'label' => 'Branch',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'ppt_date',
            'comment' => 'PPT Date',
            'label' => 'PTP Date',
          ),
          1 => 
          array (
            'name' => 'email',
            'comment' => 'email',
            'label' => 'Email Id',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'ppt_amount',
            'comment' => 'PPT Date',
            'label' => 'PTP Amount',
          ),
          1 => 
          array (
            'name' => 'ppt_mode',
            'comment' => 'PPT Mode',
            'label' => 'PTP Mode',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'remark',
            'comment' => 'Remarks',
            'label' => 'Remarks',
          ),
          1 => 
          array (
            'name' => 'welcome_call_status',
            'studio' => 'visible',
            'label' => 'LBL_WELCOME_CALL_STATUS',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'insurance',
            'studio' => 'visible',
            'label' => 'Insurance',
          ),
          1 => 'assigned_user_name',
        ),
        7 => 
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
        8 => 
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
      'lbl_detailview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'mid',
            'label' => 'MID',
          ),
          1 => 
          array (
            'name' => 'tid',
            'label' => 'TID',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'processor_name',
            'comment' => 'Processor Name',
            'label' => 'Processor Name',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'cam_name',
            'comment' => 'CAM Name',
            'label' => 'CAM Name',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'terminal_maker',
            'comment' => 'terminal maker',
            'label' => 'Terminal Maker',
          ),
          1 => 
          array (
            'name' => 'emi',
            'comment' => 'emi',
            'label' => 'EMI',
          ),
        ),
      ),
    ),
  ),
);
?>
