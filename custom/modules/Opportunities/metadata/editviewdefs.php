<?php
$viewdefs ['Opportunities'] = 
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
      'javascript' => '{$PROBABILITY_SCRIPT}',
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
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
      'syncDetailEditViews' => false,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
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
            'name' => 'loan_amount_c',
          ),
          1 => 
          array (
            'name' => 'amount',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'pickup_appointment_date_c',
            'label' => 'LBL_PICKUP_APPOINTMENT_DATE',
          ),
          1 => 
          array (
            'name' => 'loan_amount_sanctioned_c',
            'label' => 'LBL_LOAN_AMOUNT_SANCTIONED',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'pickup_appointment_address_c',
            'label' => 'LBL_PICKUP_APPOINTMENT_ADDRESS',
          ),
          1 => 
          array (
            'name' => 'pickup_appointment_contact_c',
            'label' => 'LBL_PICKUP_APPOINTMENT_CONTACT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'pickup_appointment_city_c',
            'label' => 'LBL_PICKUP_APPOINTMENT_CITY',
          ),
          1 => 
          array (
            'name' => 'pickup_appointment_pincode_c',
            'label' => 'LBL_PICKUP_APPOINTMENT_PINCODE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'pickup_appointment_feedback_c',
            'studio' => 'visible',
            'label' => 'LBL_PICKUP_APPOINTMENT_FEEDBACK',
          ),
          1 => 
          array (
            'name' => 'remarks_c',
            'comment' => 'Remarks',
            'label' => 'LBL_REMARKS',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'eos_disposition_c',
            'comment' => 'Disposition updated by EOS',
            'label' => 'EOS Disposition',
          ),
          1 => 
          array (
            'name' => 'eos_sub_disposition_c',
            'comment' => 'Sub-Disposition updated by EOS',
            'label' => 'EOS Sub-Disposition',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'eos_opportunity_status_c',
            'comment' => 'Second level bucketing of opportunity sub status by EOS',
            'label' => 'EOS Opportunity Status',
          ),
          1 => 
          array (
            'name' => 'eos_sub_status_c',
            'comment' => 'Second level bucketing of opportunity sub status by EOS',
            'label' => 'EOS Opportunity Sub-Status',
          ),
        ),
        8 => 
        array (
          0 => 'assigned_user_name',
          1 => 
          array (
            'name' => 'acspm_c',
            'studio' => 'visible',
            'label' => 'LBL_ACSPM',
          ),
        ),
        9 => 
        array (
          0 => array (
            'name' => 'cam_c',
            'label' => 'LBL_CAM_C',
          ),
          1 => array (
            'name' => 'cam_auto_assign',
            'label' => 'LBL_CAM_AUTO_ASSIGN',
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 'sales_stage',
          1 => 
          array (
            'name' => 'opportunity_status_c',
            'studio' => 'visible',
            'label' => 'LBL_OPPORTUNITY_STATUS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'sub_status_c',
            'comment' => 'Second level bucketing of opportunity sub status',
            'label' => 'Opportunity Sub-Status',
          ),
          1 => 
          array (
            'name' => 'alliance_opportunities_status',
            'comment' => 'Alliance Portal opportunities status',
            'label' => 'Alliance Opportunities Status',
          ),
        ),
        2 => 
        array (
          0 => 'opportunity_type',
          1 => 
          array (
            'name' => 'pre_operations_status_c',
            'studio' => 'visible',
            'label' => 'LBL_PRE_OPERATIONS_STATUS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'scheme_c',
            'comment' => 'Scheme',
            'label' => 'Scheme',
          ),
          1 => 
          array (
            'name' => 'Alliance_Lead_Docs_shared_c',
            'comment' => 'Alliance_Lead_Docs_shared',
            'label' => 'Alliance Lead Docs shared',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'dsa_code_c',
            'label' => 'LBL_DSA_CODE',
          ),
          1 => 'lead_source',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'advanced_suite_id_c',
            'label' => 'LBL_ADVANCED_SUITE_ID',
          ),
          1 => 
          array (
            'name' => 'application_id_c',
            'label' => 'LBL_APPLICATION_ID',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'product_type_c',
            'comment' => 'product_type',
            'label' => 'Product Type',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'seller_id_online_platform_c',
            'label' => 'Seller ID on Online Marketplace /Platform',
          ),
          1 => 
          array (
            'name' => 'seller_customer_rating_online_platform_c',
            'label' => 'Seller&#039;s Customer Rating on Online Marketplace /Platform',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'seller_partner_rating_online_platform_c',
            'label' => 'Seller&#039;s Partner Rating on Online Marketplace /Platform',
          ),
          1 => 
          array (
            'name' => 'settlement_cycle_in_days_c',
            'label' => 'Settlement Cycle',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'partner_id_c',
            'label' => 'Partner Id',
          ),
          1 => 
          array (
            'name' => 'business_age_in_months_c',
            'label' => 'Doing business with Online Marketplace /Platform since ? (no.of months)',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'industry_c',
            'label' => 'Industry',
          ),
          1 => 
          array (
            'name' => 'sales_3_month_c',
            'label' => 'Average monthly sales for last 3 months',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'referral_agent_id_c',
            'label' => 'LBL_REFERRAL_AGENT_ID',
          ),
          1 => 
          array (
            'name' => 'escalation_name_c',
            'label' => 'LBL_ESCALATION_NAME',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'escalation_time_c',
            'label' => 'LBL_ESCALATION_TIME',
          ),
          1 => 
          array (
            'name' => 'escalation_to_c',
            'label' => 'LBL_ESCALATION_TO',
          ),
        ),
      ),
    ),
  ),
);
;
?>
