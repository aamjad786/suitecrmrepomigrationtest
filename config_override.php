<?php
/***CONFIGURATOR***/
$sugar_config['disable_persistent_connections'] = false;
$sugar_config['CAS_context'] = '';
$sugar_config['CAS_host'] = 'uat.advancesuite.in';
$sugar_config['CAS_port'] = '3053';
$sugar_config['authenticationClass'] = 'CASAuthenticate';
$sugar_config['developerMode'] = true;
$sugar_config['logger']['level'] = 'debug';
$sugar_config['default_module_favicon'] = false;
$sugar_config['dashlet_auto_refresh_min'] = '30';
$sugar_config['stack_trace_errors'] = false;
$sugar_config['stackTrace'] = false;
$sugar_config['AD_CRM_TOKEN'] = '';
$sugar_config['scrm_key'] = 'N30gr0wth';
$sugar_config['scrm_api_url'] = '/service/v4_1/rest.php';
$sugar_config['addAjaxBannedModules'][0] = 'SecurityGroups';
$sugar_config['addAjaxBannedModules'][1] = 'Neo_Customers';
$sugar_config['addAjaxBannedModules'][2] = 'scrm_Cases';
$sugar_config['addAjaxBannedModules'][3] = 'Cases';
$sugar_config['addAjaxBannedModules'][4] = 'scrm_Escalation_Matrix';
$sugar_config['EOS_API_URL_PRIMARY'] = 'http://192.168.31.95:3001/Neogroth_API/api/leads';
$sugar_config['EOS_API_URL_SECONDARY'] = 'http://192.168.31.95:3001/Neogroth_API/api/leads';
$sugar_config['email_confirm_opt_in_email_template_id'] = '';
$sugar_config['email_allow_send_as_user'] = false;
$sugar_config['email_xss'] = 'YToxMzp7czo2OiJhcHBsZXQiO3M6NjoiYXBwbGV0IjtzOjQ6ImJhc2UiO3M6NDoiYmFzZSI7czo1OiJlbWJlZCI7czo1OiJlbWJlZCI7czo0OiJmb3JtIjtzOjQ6ImZvcm0iO3M6NToiZnJhbWUiO3M6NToiZnJhbWUiO3M6ODoiZnJhbWVzZXQiO3M6ODoiZnJhbWVzZXQiO3M6NjoiaWZyYW1lIjtzOjY6ImlmcmFtZSI7czo2OiJpbXBvcnQiO3M6ODoiXD9pbXBvcnQiO3M6NToibGF5ZXIiO3M6NToibGF5ZXIiO3M6NDoibGluayI7czo0OiJsaW5rIjtzOjY6Im9iamVjdCI7czo2OiJvYmplY3QiO3M6MzoieG1wIjtzOjM6InhtcCI7czo2OiJzY3JpcHQiO3M6Njoic2NyaXB0Ijt9';
$sugar_config['verify_client_ip'] = false;
$sugar_config['assigned_user_id_MineField'] = '1B866D74-0D07-4B27-80A2-688347ECF864';
$sugar_config['skip_handleCreateCase_from_addrs'][0] = 'communications@neogrowth.in';
$sugar_config['skip_handleCreateCase_from_addrs'][1] = 'mis@neogrowth.in';
$sugar_config['skip_handleCreateCase_from_addrs'][2] = 'info@cibil.com';
$sugar_config['skip_handleCreateCase_from_addrs'][3] = 'bpo-dipika.vala@neogrowth.in';
$sugar_config['skip_handleCreateCase_from_domain'] = '@neogrowth.onmicrosoft.com';
$sugar_config['not_prod_user_email'] = array('nikhil.kumar@neogrowth.in');
$sugar_config['not_prod_netcore_number'] = '9743473424';
$sugar_config['user_ng171'] = 'ng171';
$sugar_config['helpdesk_email'] = 'Helpdesk@neogrowth.in';
$sugar_config['helpdesk_email_arr'] = array('helpdesk@neogrowth.in');
$sugar_config['neogrowth_in_domain'] = 'neogrowth.in';
$sugar_config['Customer_support_executive_Assignment_Dynamic'] = 'Customer support executive Assignment Dynamic';
$sugar_config['Customer_support_executive_Assignment'] = 'Customer support executive Assignment';
$sugar_config['tdsrefund_email'] = array('tdsrefund@neogrowth.in');
$sugar_config['category_change_notification_emails'] = array('manisha.agarwal@neogrowth.in','yogesh.nakhwa@neogrowth.in');
$sugar_config['DEVs_emails'] = array('balayeswanth.b@neogrowth.in','nikhil.kumar@neogrowth.in','gowthami.gk@neogrowth.in');
$sugar_config['ng_mangal_sarang'] = 'mangal.sarang@neogrowth.in';
$sugar_config['ng_dipali_londhe'] = 'dipali.londhe@neogrowth.in';
$sugar_config['case_types'] = array(
    array ( 'parent' => 'alteration_address' ,                     'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'alteration_bank_account' ,                'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'alteration_contact_no' ,                  'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'alteration_email_id' ,                    'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'alteration_guarator' ,                    'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'alteration_repayment' ,                   'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'alteration_gst_updation' ,                'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'bureaus_update' ,                         'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'bureaus_rectification' ,                  'qrc'=> 'complaint',  'ftr'=>'non_ftr' ),
    array ( 'parent' => 'bureaus_details_requested_by_bureaus' ,   'qrc'=> 'query',  'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_batchwise_settlement' ,     'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_no_due_certificate' ,       'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'documentation_gst_invoice' ,              'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_interest_certificate' ,     'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_loan_agreement' ,           'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_repayment_schedule' ,       'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_sanction_letter' ,          'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_welcome_letter' ,           'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_statement_hard' ,           'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_statement_soft' ,           'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_insurance_copy' ,           'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'documentation_customer_unused_cheques',   'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'financial_live_excess_recovery' ,         'qrc'=> 'complaint',   'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_refund_excess_fee' ,       'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_loan_amount_not_received', 'qrc'=> 'query',       'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_non_receipt_payout' ,      'qrc'=> 'query',       'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_incorrect_loan_amount' ,   'qrc'=> 'query',       'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_ach_deactivation' ,        'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_ach_activation' ,          'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_variance_recovery' ,       'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_tds_refund_adjustment' ,   'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_tds_refund' ,              'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_payment_confirmation' ,    'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_represent_bounce_check' ,  'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_loan_restructure' ,        'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'financial_live_loan_defund' ,             'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_clarification_on_delayed_payment_charges' ,  'qrc'=> 'query',     'ftr'=>'ftr' ),
    array ( 'parent' => 'financial_live_delay_Charges_waiver' ,    'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_live_settlement_waiver_request', 'qrc'=> 'request',    'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_closed_excess_recovery' ,       'qrc'=> 'complaint',   'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_closed_excess_terminal_fees_recovery' ,       'qrc'=> 'request',   'ftr'=>'non_ftr' ),
    array ( 'parent' => 'financial_closed_tds_refund' ,            'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'settlement_discount_update' ,             'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'information_variance_recovery' ,          'qrc'=> 'request',     'ftr'=>'' ),
    array ( 'parent' => 'information_ng_fees' ,                    'qrc'=> 'request',     'ftr'=>'' ),
    array ( 'parent' => 'information_pan' ,                        'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'information_ex_gratia' ,                        'qrc'=> 'query',     'ftr'=>'ftr' ),
    array ( 'parent' => 'information_disbursement_request_guidance', 'qrc'=> 'query',     'ftr'=>'ftr' ),
    array ( 'parent' => 'information_loan_breakup' ,               'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'information_closure_process' ,            'qrc'=> 'query',       'ftr'=>'non_ftr' ),
    array ( 'parent' => 'information_loan_outstanding' ,           'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'information_merchant_app_login' ,         'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'information_check_bounce_reason' ,        'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'information_variance_amount' ,            'qrc'=> 'request',     'ftr'=>'' ),
    array ( 'parent' => 'information_statement_discrepancy' ,      'qrc'=> 'complaint',   'ftr'=>'non_ftr' ),
    array ( 'parent' => 'information_discrepancy_statement' ,      'qrc'=> 'query',       'ftr'=>'non_ftr' ),
    array ( 'parent' => 'information_loan_rejection_reason' ,      'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'information_customer_unclear_issue' ,     'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'information_website_call_back' ,          'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'information_branch_office_details' ,      'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'information_suspicious_transaction' ,     'qrc'=> 'system',      'ftr'=>'non_ftr' ),
    array ( 'parent' => 'information_credit_limit' ,               'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'information_account_status' ,             'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'information_account_purchases' ,          'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'information_payment_guidance' ,           'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'information_clarification_of_details',    'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'legal_withdrawl' ,                        'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'legal_clarification' ,                    'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'pos_machine_card' ,                       'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'pos_machine_technical_issue' ,            'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'pos_machine_termainal_handover' ,         'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'pos_machine_paper_rolls' ,                'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'pos_machine_terminal_deactivation' ,      'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'pos_machine_terminal_installation' ,      'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'pos_machine_reversal_ng_fees',            'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'sales_fresh_loan' ,                       'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'sales_renewal' ,                          'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'sales_misselling' ,                       'qrc'=> 'complaint',   'ftr'=>'non_ftr' ),
    array ( 'parent' => 'suggestion_feedback' ,                    'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'others_others' ,                          'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'others_merchant_portal_access' ,          'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'others_account_setup_issue_in_plms' ,     'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'others_spam_email_ack' ,                  'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'others_DSA_Payout' ,                      'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'others_mails_incorrectly_marked' ,        'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'penalty_cheque_bounce' ,                  'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'penalty_interest' ,                  'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'penalty_ach_bounce' ,                     'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'penalty_legal_charge' ,                   'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'penalty_late_payment' ,                   'qrc'=> 'query',       'ftr'=>'ftr' ), 
    array ( 'parent' => 'paylater_welcome_call' ,                  'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'paylater_registered_email' ,              'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_interest_penalty_charges_calculation' ,  'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_business_residence_address' ,    'qrc'=> 'request',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'paylater_credit_period' ,                 'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_transferring_repayment' ,        'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_registered_mobile' ,             'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_due_amount_paid_getting_follow_up_calls' ,  'qrc'=> 'query',  'ftr'=>'non_ftr' ),
    array ( 'parent' => 'paylater_statement_not_received' ,        'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_purchasing_on_partner' ,         'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_updates_available_limit' ,       'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_bill_due_date' ,                 'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_billing_address' ,               'qrc'=> 'request',     'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_payment_through_neft' ,          'qrc'=> 'query',       'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_current_available_balance_not_correct' ,    'qrc'=> 'query',     'ftr'=>'ftr' ),
    array ( 'parent' => 'paylater_sms_received_not_purchased' ,    'qrc'=> 'query',     'ftr'=>'non_ftr' ),
    array ( 'parent' => 'paylater_otp_not_received' ,              'qrc'=> 'query',     'ftr'=>'non_ftr' )
);
/***CONFIGURATOR***/