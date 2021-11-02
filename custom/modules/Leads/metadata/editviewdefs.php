<?php
$viewdefs ['Leads'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'hidden' => 
        array (
          0 => '<input type="hidden" name="prospect_id" value="{if isset($smarty.request.prospect_id)}{$smarty.request.prospect_id}{else}{$bean->prospect_id}{/if}">',
          1 => '<input type="hidden" name="account_id" value="{if isset($smarty.request.account_id)}{$smarty.request.account_id}{else}{$bean->account_id}{/if}">',
          2 => '<input type="hidden" name="contact_id" value="{if isset($smarty.request.contact_id)}{$smarty.request.contact_id}{else}{$bean->contact_id}{/if}">',
          3 => '<input type="hidden" name="opportunity_id" value="{if isset($smarty.request.opportunity_id)}{$smarty.request.opportunity_id}{else}{$bean->opportunity_id}{/if}">',
        ),
        'buttons' => 
        array (
          0 => 'SAVE',
          1 => 'CANCEL',
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
      'javascript' => '<script type="text/javascript" language="Javascript">function copyAddressRight(form)  {ldelim} form.alt_address_street.value = form.primary_address_street.value;form.alt_address_city.value = form.primary_address_city.value;form.alt_address_state.value = form.primary_address_state.value;form.alt_address_postalcode.value = form.primary_address_postalcode.value;form.alt_address_country.value = form.primary_address_country.value;return true; {rdelim} function copyAddressLeft(form)  {ldelim} form.primary_address_street.value =form.alt_address_street.value;form.primary_address_city.value = form.alt_address_city.value;form.primary_address_state.value = form.alt_address_state.value;form.primary_address_postalcode.value =form.alt_address_postalcode.value;form.primary_address_country.value = form.alt_address_country.value;return true; {rdelim} </script>',
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ADVANCED' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL4' => 
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
      'syncDetailEditViews' => false,
    ),
    'panels' => 
    array (
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
            'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;<input name="first_name"  id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
          ),
          1 => 'last_name',
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
          1 => 
          array (
            'name' => 'attempts_done_c',
            'label' => 'LBL_ATTEMPTS_DONE',
          ),
        ),
        3 => 
        array (
          0 => 'lead_source',
          1 => 
          array (
            'name' => 'sub_source_c',
            'studio' => 'visible',
            'label' => 'LBL_SUB_SOURCE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'merchant_name_c',
            'label' => 'LBL_MERCHANT_NAME',
          ),
          1 => 
          array (
            'name' => 'loan_amount_c',
            'label' => 'LBL_LOAN_AMOUNT',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'check_disposition_c',
            'label' => 'LBL_CHECK_DISPOSITION',
          ),
          1 => 
          array (
            'name' => 'acspm_c',
            'studio' => 'visible',
            'label' => 'LBL_ACSPM',
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
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'call_disposition_history_c',
            'studio' => 'visible',
            'label' => 'LBL_CALL_DISPOSITION_HISTORY',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'call_back_date_time_c',
            'label' => 'LBL_CALL_BACK_DATE_TIME',
          ),
          1 => 
          array (
            'name' => 'pickup_appointment_c',
            'label' => 'LBL_PICKUP_APPOINTMENT',
          ),
        ),
        9 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'product_type_c',
            'comment' => 'product_type_c',
            'label' => 'Product Type',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'bank_account_name_c',
            'comment' => 'Name of Bank',
            'label' => 'Bank Account Name',
          ),
          1 => 
          array (
            'name' => 'bank_account_count_c',
            'comment' => 'number of accounts',
            'label' => 'No. of Bank Accounts',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'bank_account_type_c',
            'comment' => 'Type of Bank Account',
            'label' => 'Bank Account Type',
          ),
          1 => 
          array (
            'name' => 'fe_name_c',
            'label' => 'LBL_FE_NAME',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'fse_name_c',
            'label' => 'LBL_FSE_NAME',
          ),
          1 => 
          array (
            'name' => 'fse_number_c',
            'label' => 'LBL_FSE_NUMBER',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'accept_online_c',
            'label' => 'accept Debit/Credit/Online payments',
          ),
          1 => 
          array (
            'name' => 'indicative_deal_amount_c',
            'comment' => 'Indicative Deal Amount',
            'label' => 'Indicative Deal Amount',
          ),
        ),
      ),
      'LBL_EDITVIEW_PANEL3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'loan_amount_required_c',
            'comment' => 'Unconverted amount of the opportunity',
            'studio' => 
            array (
              'visible' => true,
              'searchview' => true,
            ),
            'label' => 'LBL_LOAN_AMOUNT_REQUIRED_C',
          ),
          1 => 
          array (
            'name' => 'pickup_contact_number_c',
            'comment' => 'pickup contact number',
            'studio' => 
            array (
              'visible' => true,
              'searchview' => true,
            ),
            'label' => 'LBL_PICKUP_CONTACT_NUMBER_C',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'pickup_appointment_address_c',
            'comment' => 'pickup appointment address c',
            'studio' => 
            array (
              'visible' => true,
              'searchview' => true,
            ),
            'label' => 'LBL_PICKUP_APPOINTMENT_ADDRESS_C',
          ),
          1 => 
          array (
            'name' => 'pickup_appointment_date_time_c',
            'comment' => 'Pickup date time field',
            'studio' => 
            array (
              'visible' => true,
              'searchview' => true,
            ),
            'label' => 'LBL_PICKUP_APPOINTMENT_DATE_TIME_C',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'assign_manaully_c',
            'comment' => 'assign record manually',
            'studio' => 
            array (
              'visible' => true,
              'searchview' => true,
            ),
            'label' => 'LBL_ASSIGN_MANUALLY_C',
          ),
          1 => 
          array (
            'name' => 'pickup_appointment_pincode_c',
            'comment' => 'pickup appointment pincode',
            'studio' => 
            array (
              'visible' => true,
              'searchview' => true,
            ),
            'label' => 'LBL_PICKUP_APPOINTMENT_PINCODE_C',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'pickup_appointment_city_c',
            'comment' => 'pickup appointment CITY',
            'studio' => 
            array (
              'visible' => true,
              'searchview' => true,
            ),
            'label' => 'LBL_PICKUP_APPOINTMENT_CITY_C',
          ),
          1 => 
          array (
            'name' => 'pickup_appointment_user_c',
            'studio' => 'visible',
            'label' => 'LBL_PICKUP_APPOINTMENT_USER',
          ),
        ),
      ),
      'LBL_PANEL_ADVANCED' => 
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
      'lbl_editview_panel4' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'pushed_lead_c',
            'comment' => 'If the lead is pushed or not.',
            'label' => 'Lead Pushed',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
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
            'comment' => 'The birthdate of the contact',
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
            'name' => 'hear_about_us_c',
            'comment' => 'How did you hear about us?',
            'label' => 'LBL_HEAR_ABOUT_US',
          ),
          1 => 
          array (
            'name' => 'mention_the_detail_c',
            'comment' => 'mention_the_other_detail',
            'label' => 'LBL_OTHERS',
          ),
        ),
        3 => 
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
        4 => 
        array (
          0 => 
          array (
            'name' => 'average_total_monthly_sales_c',
            'studio' => 'visible',
            'label' => 'LBL_AVERAGE_TOTAL_MONTHLY_SALES',
          ),
          1 => 
          array (
            'name' => 'dsa_code_c',
            'label' => 'LBL_DSA_CODE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'scheme_c',
            'comment' => 'scheme_c',
            'label' => 'Scheme',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'turnover_c',
            'label' => 'Previous Year Turnover',
          ),
        ),
        7 => 
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
        8 => 
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
        9 => 
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
        10 => 
        array (
          0 => 
          array (
            'name' => 'edc_vintage_c',
            'label' => 'LBL_EDC_VINTAGE',
          ),
          1 => 
          array (
            'name' => 'edc_tenure_c',
            'label' => 'LBL_EDC_TENURE',
          ),
        ),
        11 => 
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
        12 => 
        array (
          0 => 
          array (
            'name' => 'merchant_type_c',
            'studio' => 'visible',
            'label' => 'LBL_MERCHANT_TYPE',
          ),
          1 => 
          array (
            'name' => 'gst_registration_c',
            'label' => 'GST registration',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'partner_name_c',
            'studio' => 'visible',
            'label' => 'LBL_PARTNER_NAME',
          ),
          1 => 
          array (
            'name' => 'average_settlements_c',
            'label' => 'LBL_AVERAGE_SETTLEMENTS',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'campaignid_c',
            'label' => 'LBL_CAMPAIGN_ID',
          ),
          1 => 
          array (
            'name' => 'campaign_name_c',
            'label' => 'LBL_CAMPAIGN_NAME',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'campaign_medium_c',
            'label' => 'LBL_CAMPAIGN_MEDIUM',
          ),
          1 => 
          array (
            'name' => 'campaign_content_c',
            'label' => 'LBL_CAMPAIGN_CONTENT',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'campaign_source_c',
            'label' => 'LBL_CAMPAIGN_SOURCE',
          ),
          1 => 
          array (
            'name' => 'campaign_term_c',
            'label' => 'LBL_CAMPAIGN_TERM',
          ),
        ),
      ),
    ),
  ),
);
;
?>
