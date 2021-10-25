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
            'name' => 'attempts_done_c',
            'label' => 'LBL_ATTEMPTS_DONE',
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
            'name' => 'acspm_c',
            'studio' => 'visible',
            'label' => 'LBL_ACSPM',
          ),
          1 => 'assigned_user_name',
        ),
        5 => 
        array (
          0 => 'account_name',
          1 => 
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
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'pickup_appointmnet_c',
            'label' => 'LBL_PICKUP_APPOINTMNET',
          ),
          1 => '',
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
            'type' => 'address',
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
            'type' => 'address',
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
          1 => 
          array (
            'name' => 'alt_mobile_number_c',
            'label' => 'LBL_ALT_MOBILE_NUMBER',
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
      ),
    ),
  ),
);
?>
