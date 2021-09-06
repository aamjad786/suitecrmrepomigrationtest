<?php
$module_name = 'Neo_Customers';
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
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
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
            'name' => 'customer_id',
            'label' => 'LBL_CUSTOMER_ID',
          ),
          1 => 'name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'mobile',
            'label' => 'LBL_MOBILE',
          ),
          1 => 
          array (
            'name' => 'location',
            'studio' => 'visible',
            'label' => 'LBL_LOCATION',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'loan_amount',
            'label' => 'LBL_LOAN_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'queue_type',
            'studio' => 'visible',
            'label' => 'LBL_QUEUE_TYPE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'loan_status',
            'label' => 'LBL_LOAN_STATUS',
          ),
          1 => 
          array (
            'name' => 'app_id_list',
            'label' => 'LBL_APP_ID_LIST',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'disposition',
            'label' => 'LBL_DISPOSITION',
          ),
          1 => 
          array (
            'name' => 'subdisposition',
            'studio' => 'visible',
            'label' => 'LBL_SUBDISPOSITION',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'half_paid_up',
            'label' => 'LBL_HALF_PAID_UP',
          ),
          1 => 
          array (
            'name' => 'ever_30_dpd',
            'label' => 'LBL_EVER_30_DPD',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'renewal_eligible',
            'label' => 'LBL_RENEWAL_ELIGIBLE',
          ),
          1 => 
          array (
            'name' => 'instant_renewal_eligibility',
            'label' => 'LBL_INSTANT_RENEWAL_ELIGIBILITY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'priority',
            'studio' => 'visible',
            'label' => 'LBL_PRIORITY',
          ),
          1 => 
          array (
            'name' => 'renewal_eligiblity_amount',
            'label' => 'LBL_RENEWAL_ELIGIBLITY_AMOUNT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'risk_grade',
            'label' => 'LBL_RISK_GRADE',
          ),
          1 => 
          array (
            'name' => 'loan_status_list',
            'label' => 'LBL_LOAN_STATUS_LIST',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'source',
            'studio' => 'visible',
            'label' => 'LBL_SOURCE',
          ),
          1 => 
          array (
            'name' => 'initiated_by',
            'studio' => 'visible',
            'label' => 'Initiated by',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'hot_lead_trigger_time',
            'studio' => 'visible',
            'label' => 'LBL_HOT_LEAD_TRIGGER_TIME',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'blacklisted',
            'label' => 'LBL_BLACKLISTED',
          ),
          1 => 
          array (
            'name' => 'credit_reject',
            'label' => 'Credit Reject',
          ),
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'renewed_app_id',
            'label' => 'Renewed App ID',
          ),
          1 => 
          array (
            'name' => 'as_stage',
            'label' => 'AS stage',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'as_remarks',
            'label' => 'AS remarks',
          ),
          1 => 
          array (
            'name' => 'upfront_deduction_app_list',
            'label' => 'LBL_UPFRONT_DEDUCTION_APP_LIST',
          ),
        ),
      ),
    ),
  ),
);
?>
