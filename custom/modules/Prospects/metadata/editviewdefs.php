<?php
$viewdefs ['Prospects'] = 
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
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => false,
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
            'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;<input name="first_name"  id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
          ),
          1 => 
          array (
            'name' => 'last_name',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'phone_mobile',
            'label' => 'LBL_MOBILE_PHONE',
            'customCode' => '<input name="phone_code_c"  id="phone_code_c" size="2" maxlength="3" type="text" value="{$fields.phone_code_c.value}" >&nbsp;<input name={$fields.phone_mobile.name}  id="{$fields.phone_mobile.name}"  type="text" value="{$fields.phone_mobile.value}" size="10" maxlength="10" >',
          ),
          1 => 
          array (
            'name' => 'business_vintage_years_c',
            'label' => 'LBL_BUSINESS_VINTAGE_YEARS',
          ),
        ),
        2 => 
        array (
          0 => 'email1',
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
            'name' => 'acspm_c',
            'studio' => 'visible',
            'label' => 'LBL_ACSPM',
          ),
          1 => 
          array (
            'name' => 'assigned_user_name',
            'displayParams' => 
            array (
              'initial_filter' => '&reports_to_name_advanced=admin',
            ),
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
            'name' => 'call_back_c',
            'label' => 'LBL_CALL_BACK',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'pickup_appointmnet_c',
            'label' => 'LBL_PICKUP_APPOINTMNET',
          ),
          1 => 
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
            'hideLabel' => true,
            'type' => 'CustomAddress',
            'displayParams' => 
            array (
              'key' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
            'hideLabel' => true,
            'type' => 'CustomAddress',
            'displayParams' => 
            array (
              'key' => 'alt',
              'copy' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
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
          1 => 'phone_work',
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
          1 => 
          array (
            'name' => 'total_sales_per_month_c',
            'label' => 'LBL_TOTAL_SALES_PER_MONTH',
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
            'name' => 'merchant_type_c',
            'studio' => 'visible',
            'label' => 'LBL_MERCHANT_TYPE',
          ),
        ),
        9 => 
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
        16 => 
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
        17 => 
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
        18 => 
        array (
          0 => 
          array (
            'name' => 'date_record_uploaded_c',
            'label' => 'LBL_DATE_RECORD_UPLOADED',
          ),
          1 => 
          array (
            'name' => 'num_yr_in_business_c',
            'label' => 'LBL_NUM_YR_IN_BUSINESS',
          ),
        ),
      ),
    ),
  ),
);
?>
