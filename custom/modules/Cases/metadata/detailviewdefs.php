<?php
$viewdefs ['Cases'] = 
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
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_CASE_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'merchant_app_id_c',
            'label' => 'LBL_MERCHANT_APP_ID',
          ),
          1 => 
          array (
            'name' => 'merchant_name_c',
            'label' => 'LBL_MERCHANT_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'merchant_email_id_c',
            'label' => 'LBL_MERCHANT_EMAIL_ID',
          ),
          1 => 
          array (
            'name' => 'merchant_contact_number_c',
            'label' => 'LBL_MERCHANT_CONTACT_NUMBER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'merchant_establisment_c',
            'label' => 'LBL_MERCHANT_ESTABLISMENT',
          ),
          1 => 
          array (
            'name' => 'age_c',
            'label' => 'LBL_AGE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'tat_in_days_c',
            'label' => 'TAT in Days',
          ),
          1 => 
          array (
            'name' => 'tat_status_c',
            'label' => 'TAT Status',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'case_category_c',
            'studio' => 'visible',
            'label' => 'LBL_CASE_CATEGORY',
          ),
          1 => 
          array (
            'name' => 'case_subcategory_c',
            'studio' => 'visible',
            'label' => 'LBL_CASE_SUBCATEGORY',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'processor_name_c',
            'comment' => 'Processor Name',
            'label' => 'LBL_PROCESSOR_NAME',
          ),
          1 => 
          array (
            'name' => 'not_apply_c',
            'comment' => 'case details check box',
            'label' => 'Case Details are not applicable',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'case_details_c',
            'comment' => 'Third category of case categories',
            'label' => 'LBL_CASE_DETAILS',
          ),
          1 => 
          array (
            'name' => 'maker_comment_c',
            'label' => 'LBL_MAKERCOMMENT',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'LBAL_c',
            'label' => 'LBAL',
          ),
          1 => 
          array (
            'name' => 'checker_comment_c',
            'label' => 'Checker comment',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'min_preclosure_amount_c',
            'label' => 'Minimum pre-closure amount requested',
          ),
          1 => 
          array (
            'name' => 'proposed_preclosure_amount_c',
            'label' => 'Proposed pre-closure amount',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'case_location_c',
            'studio' => 'visible',
            'label' => 'LBL_CASE_LOCATION',
          ),
          1 => 'type',
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'case_source_c',
            'studio' => 'visible',
            'label' => 'LBL_CASE_SOURCE',
          ),
          1 => 
          array (
            'name' => 'case_sub_source_c',
            'studio' => 'visible',
            'label' => 'LBL_CASE_SUB_SOURCE',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'complaintaint_c',
            'label' => 'LBL_COMPLAINTAINT',
          ),
          1 => 
          array (
            'name' => 'attended_by_c',
            'label' => 'LBL_ATTENDED_BY',
          ),
        ),
        12 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'reassigned_user_id_c',
            'studio' => 'visible',
            'label' => 'LBL_RE_ASSIGNED_TO_NAME',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'date_attended_c',
            'label' => 'LBL_DATE_ATTENDED',
          ),
          1 => 
          array (
            'name' => 'date_resolved_c',
            'label' => 'LBL_DATE_RESOLVED',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'case_action_code_c',
            'studio' => 'visible',
            'label' => 'LBL_CASE_ACTION_CODE',
          ),
          1 => 
          array (
            'name' => 'escalation_level_c',
            'studio' => 'visible',
            'label' => 'LBL_ESCALATION_LEVEL',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'auto_email_status_c',
            'comment' => 'Automation Email Status',
            'label' => 'LBL_BOT_REJECT_REASON',
          ),
          1 => '',
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'closed_by_c',
            'label' => 'closed_by',
          ),
          1 => 
          array (
            'name' => 'tid_c',
            'comment' => 'TID',
            'label' => 'TID',
          ),
        ),
        17 => 
        array (
          0 => 
          array (
            'name' => 'financial_year_c',
            'label' => 'Financial Year',
          ),
          1 => 
          array (
            'name' => 'quarter_c',
            'label' => 'Quarter',
          ),
        ),
      ),
      'lbl_case_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'case_number',
            'label' => 'LBL_CASE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'bot_comment_c',
            'comment' => 'Automation Bot comment',
            'label' => 'Bot comment',
          ),
        ),
        1 => 
        array (
          0 => 'priority',
          1 => 
          array (
            'name' => 'sub_priority_c',
            'comment' => 'The sub priority of the case',
            'label' => 'LBL_SUB_PRIORITY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'state',
            'comment' => 'The state of the case (i.e. open/closed)',
            'label' => 'LBL_STATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_SUBJECT',
          ),
          1 => 
          array (
            'name' => 'scheme_c',
            'comment' => 'Covid 19 scheme',
            'label' => 'LBL_SCHEME',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'summary_c',
            'comment' => 'The summary of the case',
            'label' => 'LBL_SUMMARY',
          ),
        ),
        5 => 
        array (
          0 => 'description',
        ),
        6 => 
        array (
          0 => 'resolution',
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'partner_name_c',
            'comment' => 'Partner Name coming from AS',
            'label' => 'LBL_PARTNER_NAME',
          ),
          1 => 
          array (
            'name' => 'fi_business_c',
            'comment' => 'FI Business Coming from AS',
            'label' => 'LBL_FI_BUSINESS',
          ),
        ),
      ),
      'lbl_detailview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'aop_case_updates_threaded',
            'studio' => 'visible',
            'label' => 'LBL_AOP_CASE_UPDATES_THREADED',
          ),
        ),
      ),
    ),
  ),
);
?>
