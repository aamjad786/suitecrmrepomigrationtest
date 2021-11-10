<?php
/***CONFIGURATOR***/
$sugar_config['disable_persistent_connections'] = false;
$sugar_config['CAS_context'] = '';
$sugar_config['CAS_host'] = 'uat.advancesuite.in';
$sugar_config['CAS_port'] = '3053';
$sugar_config['authenticationClass'] = 'CASAuthenticate';
$sugar_config['developerMode'] = true;
$sugar_config['logger']['level'] = 'error';
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
$sugar_config['Adrenalin Api']="http://192.168.11.186:3001/WebAPI/NEOGROWTH/D26E59DDF39740B2B6789C26A1BBFBC5/DT_668/API0001/20080505010101";
$sugar_config['AS_CRM_Domain'] = 'crm.advancesuite.in';
$sugar_config['prod_case_link'] = 'https://crm.advancesuite.in/SuiteCRM/index.php?module=Cases&action=DetailView&record=';
$sugar_config['uat_case_link'] = 'https://uat.advancesuite.in/SuiteCRM/index.php?module=Cases&action=DetailView&record=';
$sugar_config['dev_case_link'] = 'https://localhost/index.php?module=Cases&action=DetailView&record=';
    

$sugar_config['ng_khatal_jay'] = 'khataljay@gmail.com';

// Maker checker history permitted users
$sugar_config['maker_checker_h_permitted_user'] = array("NG377", "NG855", "NG950", "NG1007", "NG660", "NG894","NG478","NG866","NG1647","NG536","NG2029","NG2064","NG2054",'nucsoft5');

// Maker checker permitted users
$sugar_config['maker_checker_permitted_user'] = array("NG377", "NG855", "NG950", "NG1007", "NG660", "NG894","NG478","NG866","NG1647","NG536","NG2029","NG2054","NG2064",'nucsoft5');

// Maker checker menu permitted users
$sugar_config['maker_checker_menu_permitted_user'] = array('ng1647','ng1273','ng619','ng478','ng866','ng1273','ng1274','ng2054','ng2064','ng2155','ng2054','nucsoft5');

// Macker_checker PROD checkers
$sugar_config['prod_checker_user'] = array('ng1647','ng536','ng478'); // Manisha,Yogesh

// Macker_checker NON PROD checkers
$sugar_config['non_prod_checker_user'] = array('ng1273','ng619','ng1275','ng2155','ng1647','ng536','nucsoft5'); // Nikhil, GOPI

// agent_attendance_upload permitted users
$sugar_config['agent_attendance_up_permitted_user'] = array("NG377", "NG855", "NG950", "NG1007", "NG660", "NG894","NG478","NG866","NG1647","NG2029","NG2054","NG2064");

// social_impact_score_upload permitted users
$sugar_config['social_impact_score_permitted_user'] = array("NG377", "NG855", "NG950", "NG1007", "NG660", "NG894","NG478","NG866","NG1647","NG2029");

// scrm_Custom_Reports assign_user permitted users
$sugar_config['CR_assign_user_permitted_user'] = array("NG377","NG894","NG619","NG618","NG538","NG1039","NG171");

// Neo_Customers renewals_user_analytics permitted users
$sugar_config['renewals_user_analytics_permitted_user'] = array("NG377","NG894");

// Neo_Customers renewals permitted users
$sugar_config['renewals_permitted_user'] = array("NG618","NG586","NG660","NG894");

// Cases_functions casesAuthentication permitted users
$sugar_config['casesAuthentication_permitted_user'] = array("NG2064","NG2054","NG377", "NG855", "NG950", "NG1007", "NG660", "NG894", "NG690", "NG316", "Gaurav.Bavkar", "NG478", "NG417", "NG828", "Anuraj.Sharma", "NG866", "Karthik.Chakravarthy", "kserve_11", "NG1190", "Rushikesh.Gawde", "Faisel.Waghu", "Faisal.Ansari","Dimple.Boricha");

// scrm_Custom_Reports cloud_agent permitted users
$sugar_config['CR_cloud_agent_permitted_user'] = array("NG618","NG417","Kserve_1");

// scrm_Custom_Reports customer_profile permitted users
$sugar_config['CR_cust_profile_permitted_user'] = array("NG618","NG586","NG660");

//  update_case_escalation_matrix permitted users
$sugar_config['up_case_esc_matrix_permitted_user'] = array("NG377","NG690","NG478","NG894","NG660","NG637","NG1647","NG2029","NG2054","NG2064");

// handleCaseAssignment
$sugar_config['assigned_user_id_MineField'] = '1B866D74-0D07-4B27-80A2-688347ECF864';

// handleCreateCase
$sugar_config['skip_handleCreateCase_from_addrs'][0] = 'communications@neogrowth.in';
$sugar_config['skip_handleCreateCase_from_addrs'][1] = 'mis@neogrowth.in';
$sugar_config['skip_handleCreateCase_from_addrs'][2] = 'info@cibil.com';
$sugar_config['skip_handleCreateCase_from_addrs'][3] = 'bpo-dipika.vala@neogrowth.in';
$sugar_config['skip_handleCreateCase_from_domain'] = '@neogrowth.onmicrosoft.com';
$sugar_config['skip_handleCreateCase_email_name'] = 'Re : Auto Acknowledgement for Service';

// data_sync edit_case closed state
$sugar_config['not_prod_user_email'] = array('nikhil.kumar@neogrowth.in');
$sugar_config['not_prod_netcore_number'] = '9743473424';

$sugar_config['helpdesk_email'] = 'helpdesk@neogrowth.in';
$sugar_config['helpdesk_email_arr'] = array('helpdesk@neogrowth.in');
$sugar_config['neogrowth_in_domain'] = 'neogrowth.in';

// updateCaseAssignmentExcecutiveRole, data_sync create_case
$sugar_config['user_ng171'] = 'ng171';
$sugar_config['Customer_support_executive_Assignment_Dynamic'] = 'Customer support executive Assignment Dynamic';
$sugar_config['Customer_support_executive_Assignment'] = 'Customer support executive Assignment';

// data_sync checkInsertedFields
$sugar_config['tdsrefund_email'] = array('tdsrefund@neogrowth.in');

// data_sync tempCategoryStore
$sugar_config['category_change_notification_emails'] = array('manisha.agarwal@neogrowth.in','yogesh.nakhwa@neogrowth.in');

// sendNotificationToDevs
$sugar_config['DEVs_emails'] = array('balayeswanth.b@neogrowth.in','nikhil.kumar@neogrowth.in','gowthami.gk@neogrowth.in');

// AOPInboundEmail arrayOfMineFieldElements and listOfEmailsListedUnderMineField
$sugar_config['arrayOfMineFieldElements'] = array('CXO', 'Legal', 'Media', 'Newspaper', 'Press', 'Court', 'lawyers', 'Grievance Redressal Officer', 'Ombudsman');
$sugar_config['listOfEmailsListedUnderMineField'] = array('pk@khaitan.in', 'dk@khaitan.in', 'deepak.goswami@neogrowth.in', 'ravi.kumar@neogrowth.in', 'sumit.mukherjee@neogrowth.in', 'arun.nayyar@neogrowth.in', 'vivek.r@neogrowth.in', 'rajan.pundhir@neogrowth.in', 'sorabh.malhotra@neogrowth.in', 'sachin.bawari@neogrowth.in', 'gkshettigar@neogrowth.in', 'yogesh.nakhwa@neogrowth.in', 'tanushri.yewale@neogrowth.in', 'sanjay.kapse@neogrowth.in', 'grievanceofficer@neogrowth.in', 'nodalofficer@neogrowth.in');

// notificationForCasesAssignedToAdmin
$sugar_config['ng_mangal_sarang'] = 'mangal.sarang@neogrowth.in';
$sugar_config['ng_dipali_londhe'] = 'dipali.londhe@neogrowth.in';

// InterimResponseToCustomer, SendingCallbackReminderEmail, sendCallReminderToServiceManager in serviceManagerAdmin and onboarding 
$sugar_config['ng_gowthami_gk'] = 'gowthami.gk@neogrowth.in';

// custom\modules\scrm_Custom_Reports\views\view.assign_user.php - SendSuccessEmail
$sugar_config['ng_hemanth_vaddi'] = 'hemanth.vaddi@neogrowth.in';
$sugar_config['ng_nikhil.kumar'] = 'nikhil.kumar@neogrowth.in';
$sugar_config['ng_ramesh_a'] = 'ramesh.a@neogrowth.in';

// Escalation_Functions Escalation matrix update 
$sugar_config['esc_mat_non_prod_sms_name'] = 'Balayeswanth';
$sugar_config['esc_mat_non_prod_sms_no'] = '7373267373';
$sugar_config['esc_mat_non_prod_email'] = 'crmteam@neogrowth.in';
$sugar_config['esc_mat_non_prod_email_name'] = 'Balayeswanth';

$sugar_config['esc_mat_prod_sms_name1'] = 'NG1647 Manisha Agarwal 9820018638';
$sugar_config['esc_mat_prod_sms_no1'] = '9820018638';
$sugar_config['esc_mat_prod_sms_name2'] = 'NG637 Sumeet Thanekar 7666855666';
$sugar_config['esc_mat_prod_sms_no2'] = '7666855666';

$sugar_config['esc_mat_prod_emails'] = array("manisha.agarwal@neogrowth.in","sumeet.thanekar@neogrowth.in");
$sugar_config['esc_mat_prod_emails_names'] = array('manisha.agarwal@neogrowth.in, sumeet.thanekar@neogrowth.in');

// CallBackFlow CC emails
$sugar_config['callbackflow_cc_emails'] = array('sumeet.thanekar@neogrowth.in','mangal.sarang@neogrowth.in','dipali.londhe@neogrowth.in');

// Renewals_Functions CC emails
$sugar_config['RF_non_prod_TATUsers_emails'] = array('nikhil.kumar@neogrowth.in','balayeswanth.b@neogrowth.in');

// CallBackFlow non prod merchant email, crm api create user
$sugar_config['non_prod_merchant_email'] = 'balayeswanth.b@neogrowth.in';
$sugar_config['non_prod_merchant_CC_email'] = 'v.gopi@neogrowth.in';

// CasesEscalationMail
$sugar_config['ng_sachin_bawari'] = 'sachin.bawari@neogrowth.in';
$sugar_config['ng_sachin_bawari_name'] = 'Sachin Bawari';
$sugar_config['ng_ravi_sarpangala'] = 'ravi.sarpangala@neogrowth.in';
$sugar_config['ng_ravi_sarpangala_name'] = 'Ravi Sarpangala';
$sugar_config['ng_ravi_kumar'] = 'ravi.kumar@neogrowth.in';
$sugar_config['ng_ravi_kumar_name'] = 'B Ravikumar';
$sugar_config['ng_yogesh_nakhwa'] = 'yogesh.nakhwa@neogrowth.in';
$sugar_config['ng_yogesh_nakhwa_name'] = 'Yogesh Suresh Nakhwa';
$sugar_config['ng_sorabh_malhotra'] = 'Sorabh.Malhotra@neogrowth.in';
$sugar_config['ng_sorabh_malhotra_name'] = 'Sorabh Malhotra';
$sugar_config['ng_arun_nayyar'] = 'arun.nayyar@neogrowth.in';
$sugar_config['ng_arun_nayyar_name'] = 'Arun Nayyar';
$sugar_config['ng_piyush_khaitan_khaitan'] = 'pk@khaitan.in';
$sugar_config['ng_piyush_khaitan_neogrowth'] = 'pk@neogrowth.in';
$sugar_config['ng_piyush_khaitan_name'] = 'Piyush Khaitan';

// CallProcess, callCustomer
$sugar_config['default_campaign_name'] ='Inbound_CS_912262587409';

// uploadOzontelAutoScheduleCalls
$sugar_config['ScheduleCalls_campaign_name'] ='Outbound_912262587414';

// AssignCaseType
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