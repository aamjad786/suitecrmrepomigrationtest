<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);

require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
require_once('custom/include/SendEmail.php');
require_once('modules/ACLRoles/ACLRole.php');
require_once 'data/Relationships/SugarRelationship.php';

class afterSave {

    public function noteCreation($bean) {
        global $db;
        if (!empty($bean->id) && !empty($bean->name)) {
            $note = $bean->name;
            $noteBean = new Note();
            $noteBean->description = $note;
            $noteBean->save();

            $queryToFetchUserId = "SELECT * from smacc_sm_account_notes_1_c where smacc_sm_account_notes_1smacc_sm_account_ida = '$bean->id'";
            $result = $db->query($queryToFetchUserId);
            while ($row = $db->fetchByAssoc($result)) {
                $userId = $row['smacc_sm_account_notes_1smacc_sm_account_ida'];
                if (!empty($userId)) {
                    $updateAssignedUserId = "UPDATE smacc_sm_account SET assigned_user_id = '$userId' where id = '$bean->id'";
                    print_r($updateAssignedUserId);
//                    die();
                    $updateResult = $db->query($updateAssignedUserId);
                    if (!$updateResult) {
                        $GLOBALS['log']->debug("Assigned user id update for the SM account '$bean->id' failed.");
                    }
                }
            }
        } else {
            $GLOBALS['log']->debug("Bean ID is empty");
        }
    }

    public function npsSurveyAutomationLink($bean, $event, $arguments) {
        
        $myfile = fopen("Logs/NPSServey.log", "a");

        fwrite($myfile, "\n".date('Y-m-d h:i:s'));
        $sms = new SendSMS();
        
        $beforeSaveData = $bean->fetched_row;

        $prviousStatus = $beforeSaveData['welcome_call_status'];
       
        $currentStatus = $bean->welcome_call_status;
        
        if (($prviousStatus != $currentStatus) && 
            ($currentStatus == 'CLOSED')) {
               
                $smsContent = $this->getNPSSurveyDetailsSMS($bean);

                fwrite($myfile, $smsContent);

                $env = getenv('SCRM_ENVIRONMENT');

                if ($env == 'prod') {
                    $sms = new SendSMS();
                    $sms->send_sms_to_user($tag_name="Cust_CRM_42",$bean->contact, $smsContent, $bean);
                } else {
                    $sms = new SendSMS();
                    $sms->send_sms_to_user($tag_name="Cust_CRM_42","919131952467", $smsContent, $bean);
                }
        }

    }

    public function getNPSSurveyDetailsSMS($bean){
      
        $body = "We would love to hear your feedback on a recent interaction with our team so that we can continue to improve our service to you. Please click on the link below to share your valuable feedback https://survey.zohopublic.com/zs/zlCsno?mobile=".$bean->contact." Regards, Team Neogrowth";
        return $body;
        
    }

}

?>