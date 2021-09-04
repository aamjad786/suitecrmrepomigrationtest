<?php
$viewdefs ['Leads'] = 
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
          2 => 'DELETE',
          3 => 
          array (
            'customCode' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}<input title="{$MOD.LBL_CONVERTLEAD_TITLE}" accessKey="{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}" type="button" class="button" onClick="document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'" name="convert" value="{$MOD.LBL_CONVERTLEAD}">{/if}',
            'sugar_html' => 
            array (
              'type' => 'button',
              'value' => '{$MOD.LBL_CONVERTLEAD}',
              'htmlOptions' => 
              array (
                'title' => '{$MOD.LBL_CONVERTLEAD_TITLE}',
                'accessKey' => '{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}',
                'class' => 'button',
                'onClick' => 'document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'',
                'name' => 'convert',
                'id' => 'convert_lead_button',
              ),
              'template' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}[CONTENT]{/if}',
            ),
          ),
          4 => 'FIND_DUPLICATES',
        ),
        'headerTpl' => 'modules/Leads/tpls/DetailViewHeader.tpl',
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
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/Leads/Lead.js',
        ),
      ),
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
      'syncDetailEditViews' => true,
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
            'name' => 'product_type',
            'comment' => 'product_type',
            'label' => 'Product Type',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'bank_account_name',
            'comment' => 'Name of Bank',
            'label' => 'Bank Account Name',
          ),
          1 => 
          array (
            'name' => 'bank_account_count',
            'comment' => 'number of accounts',
            'label' => 'No. of Bank Accounts',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'bank_account_type',
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
            'name' => 'accept_online',
            'label' => 'accept Debit/Credit/Online payments',
          ),
          1 => 
          array (
            'name' => 'indicative_deal_amount',
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
        1 => 
        array (
          0 => 
          array (
            'name' => 'business_address_owned_c',
            'label' => 'LBL_BUSINESS_ADDRESS_OWNED',
          ),
          1 => 
          array (
            'name' => 'residential_address_owned_c',
            'label' => 'LBL_RESIDENTIAL_ADDRESS_OWNED',
          ),
        ),
      ),
      'lbl_editview_panel4' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'pushed_lead',
            'comment' => 'If the lead is pushed or not.',
            'label' => 'Lead Pushed',
          ),
          1 => 
          array (
            'name' => 'legal_entity_name_c',
            'label' => 'LBL_LEGAL_ENTITY_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'date_of_incorporation_c',
            'label' => 'LBL_DATE_OF_INCORPORATION',
          ),
          1 => 
          array (
            'name' => 'nature_of_business_c',
            'label' => 'LBL_NATURE_OF_BUSINESS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'digital_payment_accept_c',
            'studio' => 'visible',
            'label' => 'LBL_DIGITAL_PAYMENT_ACCEPT',
          ),
          1 => 
          array (
            'name' => 'average_business_credits_per_c',
            'label' => 'LBL_AVERAGE_BUSINESS_CREDITS_PER',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'average_digital_credits_per_c',
            'label' => 'LBL_AVERAGE_DIGITAL_CREDITS_PER',
          ),
          1 => 
          array (
            'name' => 'second_stage_lead_date_c',
            'label' => 'LBL_SECOND_STAGE_LEAD_DATE',
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
            'name' => 'dsa_id',
            'label' => 'DSA Id',
          ),
          1 => 
          array (
            'name' => 'digital',
            'label' => 'LBL_DIGITAL',
          ),
        ),
        2 => 
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
        3 => 
        array (
          0 => 
          array (
            'name' => 'average_total_monthly_sales_c',
            'studio' => 'visible',
            'label' => 'LBL_AVERAGE_TOTAL_MONTHLY_SALES',
          ),
          1 => 
          array (
            'name' => 'control_program',
            'comment' => 'Control Program',
            'label' => 'Control Program',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'hear_about_us',
            'comment' => 'How did you hear about us?',
            'label' => 'LBL_HEAR_ABOUT_US',
          ),
          1 => 
          array (
            'name' => 'mention_the_detail',
            'comment' => 'mention_the_other_detail',
            'label' => 'LBL_OTHERS',
          ),
        ),
        5 => 
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
        6 => 
        array (
          0 => 
          array (
            'name' => 'app_form_link',
            'comment' => 'form link',
            'label' => 'App form Link',
          ),
          1 => 
          array (
            'name' => 'stage_drop_off',
            'comment' => 'Stage Drop-off',
            'label' => 'Stage Drop-off',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'dsa_code_c',
            'label' => 'LBL_DSA_CODE',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'scheme',
            'comment' => 'Scheme',
            'label' => 'Scheme',
          ),
          1 => 
          array (
            'name' => 'Alliance_Lead_Docs_shared',
            'comment' => 'Alliance_Lead_Docs_shared',
            'label' => 'Alliance Lead Docs shared',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'business_age_in_months',
            'label' => 'Doing business with Online Marketplace /Platform since ? (no.of months)',
          ),
          1 => 
          array (
            'name' => 'seller_id_online_platform',
            'label' => 'Seller ID on Online Marketplace /Platform',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'seller_customer_rating_online_platform',
            'label' => 'Seller&#039;s Customer Rating on Online Marketplace /Platform',
          ),
          1 => 
          array (
            'name' => 'seller_partner_rating_online_platform',
            'label' => 'Seller&#039;s Partner Rating on Online Marketplace /Platform',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'settlement_cycle_in_days',
            'label' => 'Settlement Cycle',
          ),
          1 => 
          array (
            'name' => 'partner_id',
            'label' => 'Partner Id',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'turnover',
            'label' => 'Previous Year Turnover',
          ),
          1 => 
          array (
            'name' => 'average_monthly_sales',
            'comment' => 'Average monthly sales',
            'label' => 'Average Monthly Sales',
          ),
        ),
        13 => 
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
        14 => 
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
        15 => 
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
        16 => 
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
        17 => 
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
        18 => 
        array (
          0 => 
          array (
            'name' => 'merchant_type_c',
            'studio' => 'visible',
            'label' => 'LBL_MERCHANT_TYPE',
          ),
          1 => 
          array (
            'name' => 'gst_registration',
            'label' => 'GST registration',
          ),
        ),
        19 => 
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
        20 => 
        array (
          0 => 
          array (
            'name' => 'loan_moratorium',
            'label' => 'Did you opt for loan moratorium?',
          ),
          1 => 
          array (
            'name' => 'sales_3_month',
            'label' => 'Average monthly sales for last 3 months',
          ),
        ),
        21 => 
        array (
          0 => 
          array (
            'name' => 'has_shop',
            'label' => 'Do you own a house/shop?',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
