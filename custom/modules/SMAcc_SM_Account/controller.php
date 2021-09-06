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
 * controller.php
 * @author SalesAgility (Andrew Mclaughlan) <support@salesagility.com>
 * Date: 06/03/15
 * Comments
 */
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class SMAcc_SM_AccountController extends SugarController {

    function action_test() {
        die('test');
    }

    function action_Auto_schedule_calls_upload() {
        $this->view = 'auto_schedule_calls_upload';
    }
    
    function action_onboardingListUpdate() {
       
        if (!empty($_REQUEST['beanID'])) {
            global $db, $current_user, $app_list_strings;
            $currentUserId = $current_user->id;
            
            $onboardingChecklistResponse = $app_list_strings['onboarding_checklist_status'];
            $smaccountId = $_REQUEST['beanID'];
            $inputData = array();
            $onboardingListArray = array(); //Getting onboarding checklist
            $queryToGetOnboardingList = "SELECT * from onboarding_checklist";
            $onboardingList = $db->query($queryToGetOnboardingList);
            while ($row = $db->fetchByAssoc($onboardingList)) {
                $onboardingListArray[$row['list']] = $row['id'];
            }

            $existingData = array(); //Getting existing data for the bean
            $queryToGetSavedOnboardingList = "SELECT * from smaccount_onboarding_mapping where  smacc_sm_account_id= '$smaccountId'";
            $onboardingListSavedOptions = $db->query($queryToGetSavedOnboardingList);
            while ($row = $db->fetchByAssoc($onboardingListSavedOptions)) {
                $existingData[$row['onboarding_checklist_id']][0] = $row['status'];
                $existingData[$row['onboarding_checklist_id']][1] = $row['id'];
            }
            $db_insert_data_list = array();
            foreach ($_REQUEST as $key => $value) {
                
                $db_insert_data = "";
                if (($key != 'module') && ($key != 'action') && ($key != 'beanID')) {

                    $onboardingChecklistId = $onboardingListArray[$key];
                    $onboardingChecklistResponseId = $onboardingChecklistResponse[$value];
                    $inputData[$onboardingChecklistId] = $onboardingChecklistResponseId;
                    $newStatusCode = $inputData[$onboardingChecklistId];

                    if ($existingData[$onboardingChecklistId]) {

                        $existingStatusCode = $existingData[$onboardingChecklistId][0];
                        
                        if (!empty($existingStatusCode) && (($newStatusCode != $existingStatusCode) || !empty($_REQUEST['description_data'.array_search($onboardingListArray[$key],$onboardingListArray)]))) {
                           $description = $_REQUEST['description_data'.array_search($onboardingListArray[$key],$onboardingListArray)];
                           
                            $onboardingChecklistMappingId = $existingData[$onboardingChecklistId][1];
                            $updateQuery = "UPDATE smaccount_onboarding_mapping set status = '$newStatusCode', user_id = '$currentUserId',description='$description' where id = $onboardingChecklistMappingId";
                            $db->query($updateQuery);

                        } else {
                            // value already exists so, do nothing
                        }
                    } else {
                        $description = !empty($_REQUEST['description_data'.array_search($onboardingListArray[$key],$onboardingListArray)])?$_REQUEST['description_data'.array_search($onboardingListArray[$key],$onboardingListArray)]:" ";
                        
                        if(!empty($newStatusCode)){
                            // Insert
                            $db_insert_data = "(
                                '" . $onboardingChecklistId . "',
                                '" . $smaccountId . "',
                                '" . (!empty($newStatusCode)?$newStatusCode:0). "',
                                '" . $currentUserId . "',
                                NOW(),NOW()	,
                                '".$description."'						
                            )";
                            array_push($db_insert_data_list, $db_insert_data);
                        }
                    }
                    $insert_data_list = implode(",", $db_insert_data_list);
                }
            }
           
            $query = "INSERT INTO smaccount_onboarding_mapping (onboarding_checklist_id, smacc_sm_account_id, status, user_id, date_entered, date_modified,description) 
                VALUES $insert_data_list";
            
            $db->query($query);
            SugarApplication::redirect("index.php?module=SMAcc_SM_Account&action=DetailView&record=".$smaccountId);
        } else {
            echo "Id is empty.";
            return false;
        }
    }
    function welcomeCallFailureEmail($bean, $event, $arguments) {
        
        $myfile = fopen("Logs/welcomeCall.log", "a");
        fwrite($myfile, "\n".date('Y-m-d h:i:s'));

        require_once('custom/include/SendEmail.php');
        require_once('custom/include/SendSMS.php');
        require_once('custom/modules/neo_Paylater_Open/payLaterOpenAfterSaveEvents.php');
        $email = new SendEmail();
        $sms = new SendSMS();
        
        $beforeSaveData = $bean->fetched_row;
        $prviousStatus = $beforeSaveData['welcome_call_status'];
        $currentStatus = $bean->welcome_call_status;

        if (($prviousStatus != $currentStatus) && ($currentStatus == 'NON_CONTACTABLE')) {
            $emailId = $bean->email_id;
            $to = array($emailId);
            $subject = 'APP ID : '.$bean->app_id .' - ' .$bean->merchant_name .' - Welcome Call Failure Intimation';
            $body = $this->getWelcomeCallFailureEmail($bean);
            $email->send_email_to_user($subject, $body, $to, $cc);
            $paylaterOpenAfterSave = new payLaterOpenAfterSaveEvents();
            $smsContent = $paylaterOpenAfterSave->getSmsContent();
            $sms->send_sms_to_user($tag_name="Cust_CRM_40",$bean->phone_number,$smsContent);
            
        }
    }
    
    public function getWelcomeCallFailureEmail($bean){
      
        $body = 'Dear Sir/Madam,</br></br>
            Greetings from NeoGrowth! </br> </br>
            We have made several attempts to get in touch with you on your registered mobile number ('.$bean->phone_number.') for welcoming you to the NeoGrowth Family and for reconfirming some important details regarding your loan account. Please let us know a convenient time and alternate contact number for reaching you.</br></br>
            Meanwhile, we urge you to refer to your loan related documents such as the Welcome Letter, Sanction Letter and Loan Repayment Schedule. If you have any queries, please write to us at helpdesk@neogrowth.in. You can also call our customer service numbers, 1800-419-5565 or 9820655655, between 10 A.M. – 6 P.M. from Monday to Saturday.</br></br>
            Thank you for choosing NeoGrowth Credit Private Limited!</br></br></br>
            Thanks & Regards </br>
            Customer Service Team</br>
            NeoGrowth Credit Pvt. Ltd</br>
            Customer Care Number|1800-4195565 & 9820655655</br> 
            Email: helpdesk@neogrowth.in</br>
            Business Timing: 10 A.M. – 6 P.M. from Monday to Saturday</br> </br>';

        return $body;
        
    }


   


}
