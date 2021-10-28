<?php
$viewdefs ['Prospects'] = 
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
          3 => 
          array (
            'customCode' => '<input title="{$MOD.LBL_CONVERT_BUTTON_TITLE}" class="button" onclick="this.form.return_module.value=\'Prospects\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\';this.form.module.value=\'Leads\';this.form.action.value=\'EditView\';" type="submit" name="CONVERT_LEAD_BTN" value="{$MOD.LBL_CONVERT_BUTTON_LABEL}"/>',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => '{$MOD.LBL_CONVERT_BUTTON_LABEL}',
              'htmlOptions' => 
              array (
                'class' => 'button',
                'name' => 'CONVERT_LEAD_BTN',
                'id' => 'convert_target_button',
                'title' => '{$MOD.LBL_CONVERT_BUTTON_TITLE}',
                'onclick' => 'this.form.return_module.value=\'Prospects\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\';this.form.module.value=\'Leads\';this.form.action.value=\'EditView\';',
              ),
            ),
          ),
          4 => 
          array (
            'customCode' => '<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Prospects\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}"/>',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              'htmlOptions' => 
              array (
                'class' => 'button',
                'id' => 'manage_subscriptions_button',
                'name' => 'Manage Subscriptions',
                'title' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                'onclick' => 'this.form.return_module.value=\'Prospects\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\';',
              ),
            ),
          ),
        ),
        'hidden' => 
        array (
          0 => '<input type="hidden" name="prospect_id" value="{$fields.id.value}">',
        ),
        'headerTpl' => 'modules/Prospects/tpls/DetailViewHeader.tpl',
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_PROSPECT_INFORMATION' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_MORE_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'lbl_prospect_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
            'comment' => 'First name of the contact',
            'label' => 'LBL_FIRST_NAME',
          ),
          1 => 
          array (
            'name' => 'last_name',
            'comment' => 'Last name of the contact',
            'label' => 'LBL_LAST_NAME',
          ),
        ),
        1 => 
        array (
          0 => 'phone_mobile',
          1 => 
          array (
            'name' => 'business_vintage_years_c',
            'label' => 'LBL_BUSINESS_VINTAGE_YEARS',
          ),
        ),
        2 => 
        array (
          0 => 'email1',
          1 => 
          array (
            'name' => 'acspm_c',
            'studio' => 'visible',
            'label' => 'LBL_ACSPM',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'merchant_name_c',
            'label' => 'LBL_ MERCHANT_NAME',
          ),
          1 => 
          array (
            'name' => 'loan_amount_c',
            'label' => 'LBL_LOAN_AMOUNT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'source_c',
            'studio' => 'visible',
            'label' => 'LBL_SOURCE',
          ),
          1 => 
          array (
            'name' => 'type_c',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'check_disposition_c',
            'label' => 'LBL_CHECK_DISPOSITION',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'disposition_c',
            'studio' => 'visible',
            'label' => 'LBL_DISPOSITION',
          ),
          1 => 
          array (
            'name' => 'sub_disposition_c',
            'studio' => 'visible',
            'label' => 'LBL_SUB_DISPOSITION',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'prospects_leads_1_name',
          ),
        ),
      ),
      'LBL_MORE_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'label' => 'LBL_PRIMARY_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'primary',
            ),
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
            'label' => 'LBL_ALTERNATE_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'alt',
            ),
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'alt_landline_number_c',
            'label' => 'LBL_ALT_LANDLINE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'phone_work',
            'label' => 'LBL_OFFICE_PHONE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'birthdate',
            'label' => 'LBL_BIRTHDATE',
          ),
          1 => 
          array (
            'name' => 'gender_c',
            'studio' => 'visible',
            'label' => 'LBL_GENDER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'industry_type_c',
            'studio' => 'visible',
            'label' => 'LBL_INDUSTRY_TYPE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'residence_ownership_c',
            'studio' => 'visible',
            'label' => 'LBL_RESIDENCE_OWNERSHIP',
          ),
          1 => 
          array (
            'name' => 'residence_vintage_c',
            'label' => 'LBL_RESIDENCE_VINTAGE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'business_ownership_c',
            'studio' => 'visible',
            'label' => 'LBL_BUSINESS_OWNERSHIP',
          ),
          1 => 
          array (
            'name' => 'business_vintage_c',
            'label' => 'LBL_BUSINESS_VINTAGE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'business_type_c',
            'studio' => 'visible',
            'label' => 'LBL_BUSINESS_TYPE',
          ),
          1 => 
          array (
            'name' => 'has_edc_machine_c',
            'studio' => 'visible',
            'label' => 'LBL_HAS_EDC_MACHINE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'edc_vintage_c',
            'label' => 'LBL_EDC_VINTAGE',
          ),
          1 => 
          array (
            'name' => 'average_settlements_c',
            'label' => 'LBL_AVERAGE_SETTLEMENTS',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'right_party_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_RIGHT_PARTY_CONTACT',
          ),
          1 => 
          array (
            'name' => 'product_pitched_c',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT_PITCHED',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'dq_score_c',
            'label' => 'LBL_DQ_SCORE',
          ),
          1 => 
          array (
            'name' => 'ps_score_c',
            'label' => 'LBL_PS_SCORE',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'business_premise_type_c',
            'studio' => 'visible',
            'label' => 'LBL_BUSINESS_PREMISE_TYPE',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'called_date_c',
            'label' => 'LBL_CALLED_DATE',
          ),
          1 => 
          array (
            'name' => 'called_time_c',
            'label' => 'LBL_CALLED_TIME',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'caller_remark_c',
            'label' => 'LBL_CALLER_REMARK',
          ),
          1 => 
          array (
            'name' => 'contact_person_c',
            'label' => 'LBL_CONTACT_PERSON',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'credit_status_c',
            'label' => 'LBL_CREDIT_STATUS',
          ),
          1 => 
          array (
            'name' => 'finance_status_c',
            'label' => 'LBL_FINANCE_STATUS',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'jjwg_maps_geocode_status_c',
            'label' => 'LBL_JJWG_MAPS_GEOCODE_STATUS',
          ),
          1 => 
          array (
            'name' => 'operations_status_c',
            'label' => 'LBL_OPERATIONS_STATUS',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'pickup_executive_cam_rematks_c',
            'label' => 'LBL_PICKUP_EXECUTIVE_CAM_REMATKS',
          ),
          1 => 
          array (
            'name' => 'pre_operations_status_c',
            'label' => 'LBL_PRE_OPERATIONS_STATUS',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'cravy_c',
            'label' => 'LBL_CRAVY',
          ),
          1 => 
          array (
            'name' => 'cbavy_c',
            'label' => 'LBL_CBAVY',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'record_assigned_to_c',
            'studio' => 'visible',
            'label' => 'LBL_RECORD_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'pickup_lead_feedback_c',
            'label' => 'LBL_PICKUP_LEAD_FEEDBACK',
          ),
        ),
        17 => 
        array (
          0 => 
          array (
            'name' => 'residence_premise_type_c',
            'studio' => 'visible',
            'label' => 'LBL_RESIDENCE_PREMISE_TYPE',
          ),
          1 => 
          array (
            'name' => 'year_established_c',
            'label' => 'LBL_YEAR_ESTABLISHED',
          ),
        ),
        18 => 
        array (
          0 => 
          array (
            'name' => 'campaign_code_c',
            'label' => 'LBL_CAMPAIGN_CODE',
          ),
          1 => 
          array (
            'name' => 'caller_name_c',
            'label' => 'LBL_CALLER_NAME',
          ),
        ),
        19 => 
        array (
          0 => 
          array (
            'name' => 'merchant_type_c',
            'studio' => 'visible',
            'label' => 'LBL_MERCHANT_TYPE',
          ),
          1 => 
          array (
            'name' => 'num_yr_in_business_c',
            'label' => 'LBL_NUM_YR_IN_BUSINESS',
          ),
        ),
        20 => 
        array (
          0 => 
          array (
            'name' => 'date_record_uploaded_c',
            'label' => 'LBL_DATE_RECORD_UPLOADED',
          ),
        ),
      ),
    ),
  ),
);
?>
