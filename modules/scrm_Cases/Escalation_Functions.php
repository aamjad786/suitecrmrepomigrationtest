<?php
require_once('include/entryPoint.php');
require_once 'custom/CustomLogger/CustomLogger.php';
class Escalation_Functions{
    function __construct() {
        $this->logger = new CustomLogger('CasesEscalationMail');
    }
	function UpdateNameAndEmail(&$bean, $event, $arguments){
        //UPDATE ESCALATION LEVEL 1 USER ONLY IF PROVIDED. IF NOT IT WILL BE FETCHED DYNAMICALLY FROM SPOC/ASSIGNED USER'S REPORTING MANAGER
        if(!empty($bean->scrm_cases_usersusers_ida)){
            $esc_1_user = $this->getUser($bean->scrm_cases_usersusers_ida);
            $bean->esc_1_user = $esc_1_user->first_name . ' ' . $esc_1_user->last_name . '|' . $esc_1_user->email1;
        }		
        $esc_2_user = $this->getUser($bean->scrm_cases_users_1users_ida);
		$esc_3_user = $this->getUser($bean->scrm_cases_users_2users_ida);
		$bean->esc_2_user = $esc_2_user->first_name . ' ' . $esc_2_user->last_name . '|' . $esc_2_user->email1;
		$bean->esc_3_user = $esc_3_user->first_name . ' ' . $esc_3_user->last_name . '|' . $esc_3_user->email1;
	}
	function getUser($user_id){
	    $user = BeanFactory::getBean('Users',$user_id);
	    return $user;
	}
    function sendUpdateSms($message){
        require_once('custom/include/SendSMS.php');
        $env = getenv('SCRM_ENVIRONMENT');
        $sms = new SendSMS();
        if(in_array($env,array('dev','local'))){
            $this->logger->log('debug', "Sending Notification sms to  ". $sugar_config['esc_mat_non_prod_sms_name']);
            $sms->send_sms_to_user($tag_name="Cust_CRM_1",$sugar_config['esc_mat_non_prod_sms_no'], $message);
            $this->logger->log('debug', "Sms sent\n");
            return;
        }
        $this->logger->log('debug', "Sending Notification sms to  ". $sugar_config['esc_mat_prod_sms_name1']);
        $this->logger->log('debug', "Sending Notification sms to  ". $sugar_config['esc_mat_prod_sms_name2']);
        $sms->send_sms_to_user($tag_name="Cust_CRM_1",$sugar_config['esc_mat_prod_sms_no1'], $message);
        $sms->send_sms_to_user($tag_name="Cust_CRM_1",$sugar_config['esc_mat_prod_sms_no2'], $message);
        $this->logger->log('debug', "Sms sent");
    }

    function sendUpdateEmail($body){
    	require_once('custom/include/SendEmail.php');
        //Send email to the service manager.
        $emailId = $userData->email1;
        $subject = "Case Escalation Matrix Modified";
        $env = getenv('SCRM_ENVIRONMENT');
        if(in_array($env,array('prod'))){
            $to = $sugar_config['esc_mat_prod_emails'];
            $this->logger->log('debug', "Sending Notification mail to  ". $sugar_config['esc_mat_prod_emails_names']);
        }
        else{
            $this->logger->log('debug', "Sending Notification mail to  ". $sugar_config['esc_mat_non_prod_email_name']);
            $to = array($sugar_config['esc_mat_non_prod_email']); 
        }
        $email = new SendEmail();
        $email->send_email_to_user($subject, $body, $to);
        $this->logger->log('debug', "Email sent");
    }

	function sendUpdateNotification(&$bean, $event, $arguments){

		global $sugar_config;
		global $timedate;
		$this->logger->log('debug', "----------------- Logic hook case escalation :: sendUpdateNotification() starts -----------");
		$this->logger->log('debug', "time :: " . $timedate->now());
        $parsedSiteUrl = parse_url($sugar_config['site_url']);
        $host = $parsedSiteUrl['host'];
        if (!isset($parsedSiteUrl['port'])) {
            $parsedSiteUrl['port'] = 80;
        }
        $port = ($parsedSiteUrl['port'] != 80) ? ":" . $parsedSiteUrl['port'] : '';
        $path = !empty($parsedSiteUrl['path']) ? $parsedSiteUrl['path'] : "";
        $cleanUrl = "{$parsedSiteUrl['scheme']}://{$host}{$port}{$path}";
        $url = $cleanUrl . "/index.php?module={$bean->module_dir}&action=DetailView&record={$bean->id}";
        $message = "Changes Have Been Made in Escalation Matrix for Issue Type : $bean->issue_type Sub Issue Type : $bean->sub_issue_type. Check this at " . $url ."<br><hr>";   
        $this->logger->log('debug', "Notification Message : ".$message);
        $this->sendUpdateSms($message);
        $this->sendUpdateEmail($message);
		$this->logger->log('debug', "----------------- Logic hook case escalation :: sendUpdateNotification() ends -----------");


	}
}


?>