<?php

if (!defined('sugarEntry')) define('sugarEntry', true);

require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
require_once('custom/include/SendSMS.php');
require_once('custom/include/SendEmail.php');
require_once('modules/ACLRoles/ACLRole.php');
require_once('custom/CustomLogger/CustomLogger.php');
require_once('include/SugarQueue/SugarJobQueue.php');
require_once('modules/AOW_WorkFlow/aow_utils.php');
class AfterSaveOpportunity {

    static $assignOpportunityAlreadyRan=false;
    static $sendSmsToCustomerBasedOnOppStatusAlreadyRan=false;
    static $assignmentEmailToCAMAlreadyRan=false;
    static $assignmentSmsToCustomerAlreadyRan=false;
    


    function __construct() {
		$this->logger =new CustomLogger('AfterSaveOpportunity');
	}
    
    public function assignOpportunity(&$bean, $event, $args) {
        
        $this->logger->log('debug', '<============ AssignOpportunity Logic hook called ===========>');
        $this->logger->log('debug', 'Opp ID: '.$bean->id);

        // Skipping Logic Hooks While Using Bean Inside Logic Hook
        // Reference Link: https://www.sugaroutfitters.com/blog/prevent-a-logic-hook-from-running-multiple-times
        $this->logger->log('debug', 'assignOpportunityAlreadyRan Value: '.self::$assignOpportunityAlreadyRan);
        if(self::$assignOpportunityAlreadyRan == true) return;
        self::$assignOpportunityAlreadyRan = true;

        // Assignment Conditions
        global $db,$current_user;
        $assignedUserId = 1;
        $isAutoAssignedToCam=$bean->cam_auto_assign;
        
        $isMarketingOpp = ($bean->lead_source == 'Marketing' || $bean->stored_fetched_row_c["lead_source"] == 'Marketing');
        $isAlliancesOpp = ($bean->lead_source == 'Alliances' || $bean->stored_fetched_row_c["lead_source"] == 'Alliances');
        $isNeoCashInstaOpp = ($bean->control_program_c == "NeoCash Insta" || $bean->stored_fetched_row_c["control_program_c"] == "NeoCash Insta");

        $this->logger->log('debug', 'Conditions For Opportunity Type===>');
        $this->logger->log('debug', 'cam_auto_assign: '.$isAutoAssignedToCam);
        $this->logger->log('debug', 'isMarketingOpp: '.$isMarketingOpp);
        $this->logger->log('debug', 'isAlliancesOpp: '.$isAlliancesOpp);
        $this->logger->log('debug', 'isNeoCashInstaOpp: '.$isNeoCashInstaOpp);

        // Extra Conditions For NeoCash Insta Opportunity
        if($isNeoCashInstaOpp) {
            
            $isApplicationIdGenerated = (!empty($bean->application_id_c) || !empty($bean->stored_fetched_row_c['application_id_c']));
            $isSalesStageSanctioned = !empty($bean->sales_stage) && $bean->sales_stage == 'Sanctioned';
            $idDigitalNo=(strtolower($bean->digital_c) == "no"); // CSI-1117

            $isNeoCashInstaOppCanAssign = $isApplicationIdGenerated &&  $isSalesStageSanctioned && $idDigitalNo;

            $this->logger->log('debug', 'NeoCash Insta Opportunity Conditions: ');
            $this->logger->log('debug', 'isApplicationIdGenerated: '.$isApplicationIdGenerated);
            $this->logger->log('debug', 'isSalesStageSanctioned: '.$isSalesStageSanctioned);
            $this->logger->log('debug', 'isNeoCashInstaOppCanAssign: '.$isNeoCashInstaOppCanAssign);

            if (!$isNeoCashInstaOppCanAssign) {
                $this->logger->log('debug', 'Skippng Assignment For NeoCash Insta Opportunity!');
                $this->logger->log('debug', '<============= AssignOpportunity Logic Hook END! =================>');
                return;
            } 
        }

        // Defining Table Name For CM Assignment Based On Opportunity Type
        ($isMarketingOpp && !$isNeoCashInstaOpp ? $table = 'cluster_city_mapping' : '');
        ($isAlliancesOpp && !$isNeoCashInstaOpp ? $table = 'cluster_city_mapping_alliance' : '');
        ($isNeoCashInstaOpp ? $table = 'cluster_city_mapping_insta' : '');

        $this->logger->log('debug', 'Table Name Bases on Opportunity Type: '.$table);

        // Manual Assignment From UI or API
        if(!empty($bean->assigned_user_id) && !$isAutoAssignedToCam){

            $assignedUserId = $bean->assigned_user_id;
            $this->logger->log('debug', 'Manually Assigned SPOC Id: ' . $assignedUserId);
        } 
        // Auto Assignment To CAM In Round Robin Method
        else if ($isAutoAssignedToCam){
            
            $this->logger->log('debug', 'Assigning Opp To CAM In Round Robin Method ');

            $users = array();
            
            $query = "SELECT id FROM users u join users_cstm ucstm on u.id=ucstm.id_c  WHERE deleted = 0 and status = 'Active' and (reports_to_id='" . $bean->assigned_user_id . "' AND designation_c LIKE '%Customer Acquisition%')";
    
            $results = $db->query($query);
            $i = 0;
            
            while ($row = $db->fetchByAssoc($results)) {

                if (!empty($row['id'])) {

                    $users[$i++] = $row['id'];
                }
            }
    
            $this->logger->log('debug', 'User Array For Round Robin Selection: '.var_export($users,true));

            // ==== Round-Robin ====
            // $value = getRoundRobinUser($users, "opportunity_assignment");
            // setLastUser($value,'opportunity_assignment');
            
            // ==== Least-Beasy ====
            $value = getLeastBusyUser($users, 'assigned_user_id', $bean);
            
            $assignedUserId =$value;

            $this->logger->log('debug', 'Selected User From Round Robin Method: '.var_export($value,true));
        }
        // Auto Assignment To CM Based On Opportunity City
        else if (empty($bean->stored_fetched_row_c['assigned_user_id'])) {

            $city = !empty($bean->pickup_appointment_city_c) ? $bean->pickup_appointment_city_c : $bean->stored_fetched_row_c['pickup_appointment_city_c'];

            $query = "select u.id as spoc_id from $table LEFT JOIN users u ON $table.spoc_id=u.user_name where city='" . $city . "'";
            $this->logger->log('debug', 'SPOC Id Fetch Query: ' . $query);

            $row = $db->fetchOne($query);

            $assignedUserId = !empty($row['spoc_id']) ? $row['spoc_id'] : 1;
            $this->logger->log('debug', 'Auto Assigned SPOC Id: ' . $assignedUserId);            
            
        }

        $this->logger->log('debug', 'Final Assigned SPOC Id: ' . $assignedUserId);
        
        // Actual Updated On assigned_user_id Filed on Opportunity
        $oppBean = new Opportunity();
        $oppBean = $oppBean->retrieve($bean->id);

        $oppBean->assigned_user_id = $assignedUserId;
        $oppBean->save();

        $this->logger->log('debug', '<============= AssignOpportunity Logic Hook END! =================>');
    }

    public function sendSmsToCustomerBasedOnOppStatus(&$bean, $event, $args) {
        
        $this->logger->log('debug', '<============= SendSmsToCustomerBasedOnOppStatus Logic Hook START! =================>');

        // Skipping Logic Hooks While Using Bean Inside Logic Hook
        $this->logger->log('debug', 'sendSmsToCustomerBasedOnOppStatusAlreadyRan Value: '.self::$sendSmsToCustomerBasedOnOppStatusAlreadyRan);
        if(self::$sendSmsToCustomerBasedOnOppStatusAlreadyRan == true) return;
        self::$sendSmsToCustomerBasedOnOppStatusAlreadyRan = true;

        $oppStatus = isset($bean->opportunity_status_c) ? $bean->opportunity_status_c : "";
        $userId   = $bean->assigned_user_id;
        
        $userBean = new User();
        $userBean->retrieve($userId);
        $oldOppStatus = $bean->fetched_row['opportunity_status_c'];
        
        $isAssignedUserIsCAM = preg_match("/Customer Acquisition/i", $userBean->designation_c);
        $notDeletedAndStatusChanged = ($bean->deleted == 0) && (strcmp($oldOppStatus, $oppStatus) != 0);

        $this->logger->log('debug', 'Opportunity Conditions: ');
        $this->logger->log('debug', 'isAssignedUserIsCAM: '.$isAssignedUserIsCAM);
        $this->logger->log('debug', 'notDeletedAndStatusChanged: '.$notDeletedAndStatusChanged);

        if ($isAssignedUserIsCAM && $notDeletedAndStatusChanged) {
            
            $camMobileNo = $userBean->phone_mobile;
            $custMobileNo = $bean->pickup_appointment_contact_c;
            $camName     = $userBean->full_name;

            $followUpMsg="Dear Customer, We urge you to complete the application process at the earliest to serve you better. You can reach your RM, $camName at $camMobileNo. Regards, Team NeoGrowth";

            $notContactableMsg="Dear Customer, Thank you for applying at NeoGrowth. Your RM, $camName has been trying to reach you. You can call him at $camMobileNo. Regards, Team NeoGrowth";

            if ($oppStatus == "Follow up") {
                $messageToSend = $followUpMsg;
            } else if ($oppStatus == "Not Contactable") {
                $messageToSend = $notContactableMsg;
            }

            if(!empty($messageToSend)){

                // Adding SMS In Queue To Run Send SMS Asynchronously

                $data = array(
                    "custMobileNo" => $custMobileNo,
                    "messageToSend" => $messageToSend,
                    "tag_name" => "Cust_CRM_6",
                    "beanID" => $bean->id,
                );
        
                $jsonData=json_encode($data); 

                $job = new SchedulersJob();
                $job->name = "Send SMS To: $custMobileNo";
                $job->data = $jsonData; //Data to be passed to Job Queue
                $job->target = "function::sendSmsJobQueue";
                $job->assigned_user_id = 1;//user the job runs as

                $jobQueue = new SugarJobQueue();
                $jobid = $jobQueue->submitJob($job);

                $this->logger->log('debug', 'SMS Is Pushed To Job Queue Wit ID: '.$jobid);
                $this->logger->log('debug', 'SMS Job Queue Data: '.$jsonData);
            }

            $this->logger->log('debug', '<============= SendSmsToCustomerBasedOnOppStatus Logic Hook END! =================>');
        }
    }

    public function assignmentEmailToCAM(&$bean, $event, $args) {

        $this->logger->log('debug', '<============= AssignmentEmailToCAM Logic Hook START! =================>');

        // Skipping Logic Hooks While Using Bean Inside Logic Hook
        $this->logger->log('debug', 'assignmentEmailToCAMAlreadyRan Value: '.self::$assignmentEmailToCAMAlreadyRan);
        if(self::$assignmentEmailToCAMAlreadyRan == true) return;
        self::$assignmentEmailToCAMAlreadyRan = true;
        
        $userBean = BeanFactory::getBean('Users', $bean->assigned_user_id);
        $email = new SendEmail();
        // $this->logger->log('debug', 'Assigned user bean data: '.var_export($userBean,true));
        ($bean->control_program_c == "NeoCash Insta") ? $type = " NeoCash Insta" : $type = "";
        $custMobileNo = $bean->pickup_appointment_contact_c;
        $customerName = $bean->name;
        
        (!empty($userBean->email1)) ? $to = array($userBean->email1) : $to = array();
        $this->logger->log('debug', 'To Array: '.var_export($to,true));
        
        $cc = array();

        $subject = "New$type Opportunity Assigned - $custMobileNo (Do not reply)";

        $body = "
		<pre>Hi,</br>
        You have been assigned a new<b>$type</b> Opportunity - $customerName/$custMobileNo. Please check your Sales App to start working on this assignment.</br>
		
			Thanks,</br>
			Team NeoGrowth";

        if (!empty($to)) {
            $email->send_email_to_user($subject, $body, $to, $cc);
            $this->logger->log('debug', 'Email is sending to: '.$userBean->email1);
        } else {
            $this->logger->log('error', 'Assignment Email To CAM Not Send As Email Id Empty!');
        }

    }

    public function assignmentSmsToCustomer(&$bean, $event, $args) {

        $this->logger->log('debug', '<============= assignmentSmsToCustomer Logic Hook START! =================>');

        // Skipping Logic Hooks While Using Bean Inside Logic Hook
        $this->logger->log('debug', 'assignmentSmsToCustomerAlreadyRan Value: '.self::$assignmentSmsToCustomerAlreadyRan);
        if(self::$assignmentSmsToCustomerAlreadyRan == true) return;
        self::$assignmentSmsToCustomerAlreadyRan = true;


        $userId = $bean->assigned_user_id;
        $userBean = new User();
        $userBean->retrieve($userId);

        $oldAssignedUser = $bean->stored_fetched_row_c['assigned_user_id'];
        $newAssignedUser = $bean->assigned_user_id;

        $isAssignedUserIsCAM = preg_match("/Customer Acquisition/i", $userBean->designation_c);
        $isAssignmentChanged = !empty($bean->assigned_user_id) && strcmp($newAssignedUser, $oldAssignedUser) != 0;
        
        $this->logger->log('debug', '$isAssignedUserIsCAM: '.$isAssignedUserIsCAM);
        $this->logger->log('debug', '$isAssignmentChanged: '.$isAssignmentChanged);
        
        if ($isAssignedUserIsCAM && $isAssignmentChanged) {

            $assignedUserBean = BeanFactory::getBean('Users', $bean->assigned_user_id);

            $camName = $assignedUserBean->first_name . ' ' . $assignedUserBean->last_name;
            $camMobileNo = !empty($assignedUserBean->phone_mobile) ? $assignedUserBean->phone_mobile : '';

            $custMobileNo = $bean->pickup_appointment_contact_c;
            $custMobileNo = "91" . substr($custMobileNo, -10);

            $message = 'Dear Customer,
            Thank you for applying at NeoGrowth. Your RM, "' . $camName . '" will call you shortly. You can reach him at "' . $camMobileNo . '" Regards, Team NeoGrowth’';

            // Adding SMS In Queue To Run Send SMS Asynchronously

            $data = array(
                "custMobileNo" => $custMobileNo,
                "messageToSend" => $message,
                "tag_name" => "Cust_CRM_41",
                "beanID" => $bean->id,
            );
    
            $jsonData=json_encode($data); 

            $job = new SchedulersJob();
            $job->name = "Send SMS To: $custMobileNo";
            $job->data = $jsonData; //Data to be passed to Job Queue
            $job->target = "function::sendSmsJobQueue";
            $job->assigned_user_id = 1;//user the job runs as

            $jobQueue = new SugarJobQueue();
            $jobid = $jobQueue->submitJob($job);

            $this->logger->log('debug', 'SMS Is Pushed To Job Queue Wit ID: '.$jobid);
            $this->logger->log('debug', 'SMS Job Queue Data: '.$jsonData);

            return true;
        } else {
            return true;
        }
    }

}
