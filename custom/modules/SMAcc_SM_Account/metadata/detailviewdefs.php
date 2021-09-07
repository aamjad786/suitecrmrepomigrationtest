<?php
$module_name = 'SMAcc_SM_Account';
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
        'LBL_EDITVIEW_PANEL1' => 
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
            'name' => 'app_id',
            'label' => 'LBL_APP_ID',
          ),
          1 => 
          array (
            'name' => 'merchant_name',
            'label' => 'LBL_MERCHANT_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'email_id',
            'label' => 'LBL_EMAIL_ID',
          ),
          1 => 
          array (
            'name' => 'contact',
            'label' => 'LBL_CONTACT',
          ),
        ),
        2 => 
        array (
          0 => 'assigned_user_name',
          1 => 
          array (
            'name' => 'welcome_call_status',
            'studio' => 'visible',
            'label' => 'LBL_WELCOME_CALL_STATUS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'call_updation',
            'studio' => 'visible',
            'label' => 'LBL_CALL_UPDATION',
          ),
          1 => 
          array (
            'name' => 'Part_2_Status',
            'studio' => 'visible',
            'label' => 'Part-2 Status',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'part_2_attempt',
            'studio' => 'visible',
            'label' => 'Part-2 Attempt',
          ),
          1 => 
          array (
            'name' => 'customer_query',
            'label' => 'LBL_CUSTOMER_QUERY',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'omc_name',
            'label' => 'OMC Name',
          ),
          1 => 
          array (
            'name' => 'dealer_code',
            'label' => 'Dealer code',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'call_remark',
            'label' => 'LBL_CALL_REMARK',
          ),
          1 => 
          array (
            'name' => 'call_attempt_status',
            'studio' => 'visible',
            'label' => 'LBL_CALL_ATTEMPT_STATUS',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'insurance',
            'studio' => 'visible',
            'label' => 'Insurance',
          ),
          1 => 
          array (
            'name' => 'insurance_remarks',
            'comment' => 'Remarks',
            'label' => 'Insurance Remarks',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'advance_amount',
            'label' => 'LBL_ADVANCE_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'total_repayment_amount',
            'label' => 'LBL_TOTAL_REPAYMENT_AMOUNT',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'loan_tenure',
            'label' => 'LBL_LOAN_TENURE',
          ),
          1 => 
          array (
            'name' => 'rate_of_interest',
            'label' => 'LBL_RATE_OF_INTEREST',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'repayment_mode',
            'label' => 'LBL_REPAYMENT_MODE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'funded_date',
            'label' => 'LBL_FUNDED_DATE',
          ),
          1 => 
          array (
            'name' => 'processing_fee',
            'label' => 'LBL_PROCESSING_FEE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'repayment_frequency',
            'label' => 'LBL_REPAYMENT_FREQUENCY',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
?>
