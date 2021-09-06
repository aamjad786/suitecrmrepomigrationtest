<?php

/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 * ****************************************************************************** */

/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once('modules/SMAcc_SM_Account/SMAcc_SM_Account_sugar.php');

class SMAcc_SM_Account extends SMAcc_SM_Account_sugar {

    function __construct() {
        parent::__construct();
    }

    function create_export_query($order_by, $where, $relate_link_join = '') {
        $query = "select
   id,
   user_name as 'Assigned To',
   app_id as 'App ID',
   branch as 'Location',
   if(establishment_name = 1, 'CONFIRMED', if(establishment_name = 2, 'PENDING', '')) as 'Establishment Name',
   if(phone_number = 1, 'CONFIRMED', if(phone_number = 2, 'PENDING', '')) as 'Phone Number',
   if(onboarding_email_id = 1, 'CONFIRMED', if(onboarding_email_id = 2, 'PENDING', '')) as 'Email ID',
   if(business_address = 1, 'CONFIRMED', if(business_address = 2, 'PENDING', '')) as 'Business Address',
   if(pan_card = 1, 'CONFIRMED', if(pan_card = 2, 'PENDING', '')) as 'PAN Card',
   if(aadhar_card = 1, 'CONFIRMED', if(aadhar_card = 2, 'PENDING', '')) as 'Aadhar Card',
   if(bank_account_registered = 1, 'CONFIRMED', if(bank_account_registered = 2, 'PENDING', '')) as 'Bank Account registered',
   if(ckyc_number = 1, 'CONFIRMED', if(ckyc_number = 2, 'PENDING', 'NOT AWARE')) as 'CKYC Number',
   if(tan_number = 1, 'CONFIRMED', if(tan_number = 2, 'PENDING', 'NOT AWARE')) as 'TAN Number',
   advance_amount as 'Advance Amonut',
   total_repayment_amount as 'Total Repayment Amount',
   repayment_mode as 'Repayment Mode',
   if(loan_tenure_in_days = 3, 'INFORMED', if(loan_tenure_in_days = 2, 'PENDING', '')) as 'Loan Tenure in days',
   if(emi_amount_as_per_frequency = 3, 'INFORMED', if(emi_amount_as_per_frequency = 2, 'PENDING', '')) as 'Emi amount as per frequency',
   if(rate_of_interest_roi = 3, 'INFORMED', if(rate_of_interest_roi = 2, 'PENDING', '')) as 'Rate of Interest - ROI',
   if(processing_fees = 3, 'INFORMED', if(processing_fees = 2, 'PENDING', 'NOT APPLICABLE')) as 'Processing fees',
   if(terminal_monthly_rental = 3, 'INFORMED', if(terminal_monthly_rental = 2, 'PENDING', 'NOT APPLICABLE')) as 'Terminal Monthly Rental',
   if(merchant_portal = 3, 'PITCHED', if(merchant_portal = 2, 'PENDING', '')) as 'Merchant Portal',
   if(nps_survey_link = 3, 'PITCHED', if(nps_survey_link = 2, 'PENDING', '')) as 'NPS Survey Link',
   if(virtual_account = 3, 'PITCHED', if(virtual_account = 2, 'PENDING', '')) as 'Virtual Account',
   if(deferral_cheque = 3, 'PITCHED', if(deferral_cheque = 2, 'PENDING', '')) as 'Deferral Cheque',
   if(delayed_payment_charges = 3, 'PITCHED', if(delayed_payment_charges = 2, 'PENDING', '')) as 'Delayed Payment Charges',
   if(information_nach_registration = 3, 'PITCHED', if(information_nach_registration = 2, 'PENDING', '')) as 'Information NACH Registration',
   if(welcome_letter = 4, 'TRIGGERED', if(welcome_letter = 2, 'PENDING', '')) as 'Welcome Letter',
   if(sanction_letter = 4, 'TRIGGERED', if(sanction_letter = 2, 'PENDING', '')) as 'Sanction Letter',
   if(repayment_schedule = 4, 'TRIGGERED', if(repayment_schedule = 2, 'PENDING', '')) as 'Repayment Schedule',
   if(loan_agreement = 4, 'TRIGGERED', if(loan_agreement = 2, 'PENDING', '')) as 'Loan Agreement',
   if(insurance_copy = 4, 'TRIGGERED', if(insurance_copy = 2, 'PENDING', 'NOT APPLICABLE')) as 'Insurance Copy',
   if(receipt_of_deferral_cheques = 7, 'RECEIVED', if(receipt_of_deferral_cheques = 2, 'PENDING', '')) as 'Receipt of Deferral Cheque',
   if(terminal_installation = 9, 'INSTALLED', if(terminal_installation = 2, 'PENDING', '')) as 'Terminal Installation',
   if(nach_activation_status = 10, 'ACTIVATED', if(nach_activation_status = 2, 'PENDING', 'REJECTED')) as 'NACH Activation Status',
   if(onboarding_activity_status = 6, 'COMPLETED', if(onboarding_activity_status = 2, 'PENDING', '')) as 'Onboarding Activity Status',
   app_id as 'App ID',
   smacc_sm_account_status as 'SM Account Status',
   merchant_name as 'Merchant Name',
   contact as 'Contact',
   email_id as 'E-mail ID',
   user_name as 'Assigned User',
   constitution as 'Constitution',
   welcome_call_status as 'Welcome Call Status',
   call_attempt_status as 'Call Attempt Status',
   call_updation as 'Call Updation',
   call_remark as 'Call Remark',
   advance_amount as 'Advance Amonut',
   total_repayment_amount as 'Total Repayment Amount',
   loan_tenure as 'Loan Tenure',
   repayment_mode as 'Repayment Mode',
   repayment_frequency as 'Repayment Frequency',
   rate_of_interest as 'Rate of Interesting',
   funded_date as 'Funded Date', 
   call_remark as 'Call Remark',
   insurance as 'Insurance',
   insurance_remarks as 'Insurance Remarks'
FROM
   (
      select
         id,
         MAX(
         case
            when
               list = 'establishment_name' 
            then
               status 
         end
)as establishment_name,MAX(
         case
            when
               list = 'phone_number' 
            then
               status 
         end
)as phone_number, MAX(
         case
            when
               list = 'onboarding_email_id' 
            then
               status 
         end
) as onboarding_email_id, MAX(
         case
            when
               list = 'business_address' 
            then
               status 
         end
) as business_address, MAX(
         case
            when
               list = 'pan_card' 
            then
               status 
         end
) as pan_card, MAX(
         case
            when
               list = 'aadhar_card' 
            then
               status 
         end
) as aadhar_card, MAX(
         case
            when
               list = 'bank_account_registered' 
            then
               status 
         end
) as bank_account_registered, MAX(
   case
      when
         list = 'ckyc_number' 
      then
         status 
   end
) as ckyc_number, MAX(
   case
      when
         list = 'tan_number' 
      then
         status 
   end
) as tan_number, MAX(
   case
      when
         list = 'loan_tenure_in_days' 
      then
         status 
   end
) as loan_tenure_in_days, MAX(
   case
      when
         list = 'emi_amount_as_per_frequency' 
      then
         status 
   end
) as emi_amount_as_per_frequency, MAX(
   case
      when
         list = 'rate_of_interest_roi' 
      then
         status 
   end
) as rate_of_interest_roi,MAX(
   case
      when
         list = 'processing_fees' 
      then
         status 
   end
) as processing_fees,MAX(
   case
      when
         list = 'terminal_monthly_rental' 
      then
         status 
   end
) as terminal_monthly_rental, MAX(
         case
            when
               list = 'merchant_portal' 
            then
               status 
         end
) as merchant_portal, MAX(
         case
            when
               list = 'nps_survey_link' 
            then
               status 
         end
) as nps_survey_link,MAX(
   case
      when
         list = 'virtual_account' 
      then
         status 
   end
) as virtual_account, MAX(
         case
            when
               list = 'bill_desk_payment' 
            then
               status 
         end
) as bill_desk_payment, MAX(
         case
            when
               list = 'deferral_cheque' 
            then
               status 
         end
) as deferral_cheque, MAX(
         case
            when
               list = 'delayed_payment_charges' 
            then
               status 
         end
) as delayed_payment_charges, MAX(
         case
            when
               list = 'information_nach_registration' 
            then
               status 
         end
) as information_nach_registration, MAX(
         case
            when
               list = 'welcome_letter' 
            then
               status 
         end
) as welcome_letter, MAX(
         case
            when
               list = 'sanction_letter' 
            then
               status 
         end
) as sanction_letter, MAX(
         case
            when
               list = 'repayment_schedule' 
            then
               status 
         end
) as repayment_schedule, MAX(
         case
            when
               list = 'loan_agreement' 
            then
               status 
         end
) as loan_agreement, MAX(
         case
            when
               list = 'insurance_copy' 
            then
               status 
         end
) as insurance_copy, MAX(
         case
            when
               list = 'onboarding_welcome_call_status' 
            then
               status 
         end
) as onboarding_welcome_call_status, MAX(
         case
            when
               list = 'receipt_of_deferral_cheques' 
            then
               status 
         end
) as receipt_of_deferral_cheques, MAX(
         case
            when
               list = 'nach_form_upload' 
            then
               status 
         end
) as nach_form_upload, MAX(
         case
            when
               list = 'ims_documents' 
            then
               status 
         end
) as ims_documents, MAX(
         case
            when
               list = 'terminal_installation' 
            then
               status 
         end
) as terminal_installation, MAX(
         case
            when
               list = 'nach_activation_status' 
            then
               status 
         end
) as nach_activation_status, MAX(
         case
            when
               list = 'ims_flagging' 
            then
               status 
         end
) as ims_flagging, MAX(
         case
            when
               list = 'onboarding_activity_status' 
            then
               status 
         end
) as onboarding_activity_status, app_id, branch, smacc_sm_account_status, merchant_name,insurance,insurance_remarks, contact, email_id, constitution, welcome_call_status, call_attempt_status, call_updation, call_remark, advance_amount, total_repayment_amount, loan_tenure, repayment_mode, repayment_frequency, rate_of_interest, funded_date,user_name 
      from
         (
            select
               smacc_sm_account.id,
               app_id,
               branch,
               merchant_name,
               contact,
               smacc_sm_account.status as smacc_sm_account_status,
               smacc_sm_account.insurance as 'insurance',
               smacc_sm_account.insurance_remarks as 'insurance_remarks', 
               email_id,
               constitution,
               welcome_call_status,
               call_attempt_status,
               call_updation,
               call_remark,
               advance_amount,
               total_repayment_amount,
               loan_tenure,
               repayment_mode,
               repayment_frequency,
               rate_of_interest,
               funded_date,
               assigned_user_id,
               onboarding_checklist.list,
               smaccount_onboarding_mapping.status,
               users.user_name as user_name
            from
               smacc_sm_account 
               LEFT join
                  smaccount_onboarding_mapping 
                  on smaccount_onboarding_mapping.smacc_sm_account_id = smacc_sm_account.id 
               INNER JOIN 
                  users 
                  on users.id = smacc_sm_account.assigned_user_id
               left join
                  onboarding_checklist 
                  on smaccount_onboarding_mapping.onboarding_checklist_id = onboarding_checklist.id";
        $where_auto = "  smacc_sm_account.deleted=0";
        if ($where != "") {
            $query .= " where $where AND " . $where_auto;
        }
       $query .= " )
         a 
         group by
         id
        )
        b;";
        //echo $query;exit;
        return $query;
    }

}

?>