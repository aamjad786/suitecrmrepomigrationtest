<?php
$job_strings[] = 'AssignCaseType';
date_default_timezone_set('Asia/Kolkata');

function AssignCaseType(){
$details=array(
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
    global $db;
   
    $bean = BeanFactory::getBean('Cases');
    $one_month_before_date = date('Y-m-d', strtotime('-1 months'));

    $query = "cases.deleted=0 and cases.state in ('Open','In_progress') and cases.date_entered>='$one_month_before_date'";

    $items = $bean->get_full_list('',$query);

    if ($items){

        foreach($items as $item){
           
            $subcat = $item->case_subcategory_c;
            $index=getdetail($details,$subcat);
            $detail=$details[$index];
            $type=$detail['qrc'];
            $action_code=$detail['ftr'];
            
              $query = "update cases set type = '$type' where id='$item->id'";
              $results = $db->query($query);
              $query="update cases_cstm set case_action_code_c = '$action_code' where id_c='$item->id'";
              $results = $db->query($query);
            }
           
            
        }
  
    return true;
}


function getdetail($a,$subcat)
{
    foreach($a as $key => $i)
    {
        if(array_search($subcat,$i))
        {
            return $key;
        }
    }
}