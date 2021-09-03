<?php
$viewdefs ['Cases'] = 
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
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'include/javascript/bindWithDelay.js',
        ),
        1 => 
        array (
          'file' => 'modules/AOK_KnowledgeBase/AOK_KnowledgeBase_SuggestionBox.js',
        ),
        2 => 
        array (
          'file' => 'include/javascript/qtip/jquery.qtip.min.js',
        ),
        3 => 
        array (
          'file' => 'custom/modules/Cases/custom_js.js',
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
      ),
      'form' => 
      array (
        'enctype' => 'multipart/form-data',
      ),
      'syncDetailEditViews' => false,
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
            'name' => 'LBAL_c',
            'label' => 'LBAL',
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
            'name' => 'min_preclosure_amount_c',
            'label' => 'Minimum pre-closure amount requested',
          ),
          1 => 
          array (
            'name' => 'proposed_preclosure_amount_c',
            'label' => 'Proposed pre-closure amount',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'case_location_c',
            'studio' => 'visible',
            'label' => 'LBL_CASE_LOCATION',
          ),
          1 => 'type',
        ),
        9 => 
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
        10 => 
        array (
          0 => 
          array (
            'name' => 'case_action_code_c',
            'studio' => 'visible',
            'label' => 'LBL_CASE_ACTION_CODE',
          ),
          1 => 
          array (
            'name' => 'attended_by_c',
            'label' => 'LBL_ATTENDED_BY',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'auto_email_status_c',
            'comment' => 'Automation Email Status',
            'label' => 'LBL_BOT_REJECT_REASON',
          ),
          1 => 
          array (
            'name' => 'reassigned_user_id_c',
            'studio' => 'visible',
            'label' => 'LBL_RE_ASSIGNED_TO_NAME',
          ),
        ),
        12 => 
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
          0 => '',
          1 => '',
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
          0 => 'assigned_user_name',
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
            'displayParams' => 
            array (
            ),
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
          0 => 
          array (
            'name' => 'description',
            'nl2br' => true,
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'resolution',
            'nl2br' => true,
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'update_text',
            'studio' => 'visible',
            'label' => 'LBL_UPDATE_TEXT',
          ),
          1 => 
          array (
            'name' => 'internal',
            'studio' => 'visible',
            'label' => 'LBL_INTERNAL',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'case_update_form',
            'studio' => 'visible',
          ),
          1 => '',
        ),
        9 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
