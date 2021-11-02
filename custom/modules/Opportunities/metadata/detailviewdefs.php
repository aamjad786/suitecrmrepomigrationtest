<?php
$viewdefs ['Opportunities'] = 
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
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
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
            'label' => '{$MOD.LBL_LOAN_AMOUNT}',
          ),
          1 => 
          array (
            'name' => 'amount',
            'label' => '{$MOD.LBL_AMOUNT}',
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
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'acspm_c',
            'studio' => 'visible',
            'label' => 'LBL_ACSPM',
          ),
        ),
        7 => 
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
        8 => 
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
        9 => 
        array (
          0 => 
          array (
            'name' => 'date_updated_by_EOS_c',
            'comment' => 'Date on which opportunity was updated by EOS',
            'label' => 'Date updated by EOS',
          ),
          1 => 
          array (
            'name' => 'date_sent_to_EOS_c',
            'comment' => 'Date on which opportunity sent to EOS',
            'label' => 'Date sent to EOS',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'sales_remark',
            'comment' => 'sales_remarks',
            'label' => 'Sales Remarks',
          ),
          1 => 
          array (
            'name' => 'sales_opportunity_status',
            'comment' => 'Sales team udpate opportunity  status via sales app',
            'label' => 'Sales Opportunity Status',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'digital_c',
            'studio' => 'visible',
            'label' => 'LBL_DIGITAL',
          ),
          1 => 
          array (
            'name' => 'alliance_opp_status_c',
            'comment' => 'Alliance Portal opportunities status',
            'label' => 'Alliance Opportunities Status',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'leads_description_c',
            'comment' => 'Lead Description',
            'label' => 'LBL_LEAD_DESCRIPTION',
          ),
          1 => '',
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
            'name' => 'sub_status',
            'comment' => 'Second level bucketing of opportunity sub status',
            'label' => 'Opportunity Sub-Status',
          ),
          1 => 
          array (
            'name' => 'control_program_c',
            'comment' => 'Control Program',
            'label' => 'Control Program',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'stage_drop_off_c',
            'comment' => 'Stage Drop-off',
            'label' => 'Stage Drop-off',
          ),
          1 => 
          array (
            'name' => 'app_form_link_c',
            'comment' => 'form link',
            'label' => 'App form Link',
          ),
        ),
        3 => 
        array (
          0 => 'opportunity_type',
          1 => 
          array (
            'name' => 'pre_operations_status_c',
            'studio' => 'visible',
            'label' => 'LBL_PRE_OPERATIONS_STATUS',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'dsa_id',
            'label' => 'DSA Id',
          ),
          1 => 
          array (
            'name' => 'is_eligible',
            'comment' => 'Source type',
            'label' => 'Is Eligible',
          ),
        ),
        5 => 
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
        6 => 
        array (
          0 => 
          array (
            'name' => 'dsa_code_c',
            'label' => 'LBL_DSA_CODE',
          ),
          1 => 'lead_source',
        ),
        7 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'sub_source_c',
            'studio' => 'visible',
            'label' => 'LBL_SUB_SOURCE',
          ),
        ),
        8 => 
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
        9 => 
        array (
          0 => 
          array (
            'name' => 'product_type_c',
            'comment' => 'product_type',
            'label' => 'Product Type',
          ),
          1 => 
          array (
            'name' => 'reject_reason_c',
            'comment' => 'Reject Reason',
            'label' => 'Reject Reason',
          ),
        ),
        10 => 
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
        11 => 
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
        12 => 
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
        13 => 
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
        14 => 
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
        15 => 
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
        16 => 
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
        17 => 
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
        18 => 
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
