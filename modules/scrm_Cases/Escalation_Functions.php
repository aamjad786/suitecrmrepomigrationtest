<?php
require_once('include/entryPoint.php');

class Escalation_Functions{
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
        $myfile = fopen("Logs/CasesEscalationMail.log", "a");
        $sms = new SendSMS();
        if(in_array($env,array('dev','local'))){
            fwrite($myfile, "\nSending Notification sms to  ". 'Balayeswanth');
            $sms->send_sms_to_user($tag_name="Cust_CRM_1","7373267373", $message);
            fwrite($myfile, "\nSms sent\n");
            return;
        }
        fwrite($myfile, "\nSending Notification sms to  ". 'NG1647 Manisha Agarwal 9820018638');
        fwrite($myfile, "\nSending Notification sms to  ". 'NG637 Sumeet Thanekar 7666855666');
        $sms->send_sms_to_user($tag_name="Cust_CRM_1","9820018638", $message);
        $sms->send_sms_to_user($tag_name="Cust_CRM_1","7666855666", $message);
        fwrite($myfile, "\nSms sent");
    }

    function sendUpdateEmail($body){
    	require_once('custom/include/SendEmail.php');
    	$myfile = fopen("Logs/CasesEscalationMail.log", "a");
        //Send email to the service manager.
        $emailId = $userData->email1;
        $subject = "Case Escalation Matrix Modified";
        $env = getenv('SCRM_ENVIRONMENT');
        if(in_array($env,array('prod'))){
            $to = array("manisha.agarwal@neogrowth.in","sumeet.thanekar@neogrowth.in");
            fwrite($myfile, "\nSending Notification mail to  ". "manisha.agarwal@neogrowth.in, sumeet.thanekar@neogrowth.in");
        }
        else{
            fwrite($myfile, "\nSending Notification mail to  ". 'Balayeswanth');
            $to = array("crmteam@neogrowth.in"); 
        }
        $email = new SendEmail();
        $email->send_email_to_user($subject, $body, $to);
        fwrite($myfile, "\nEmail sent");
    }

	function sendUpdateNotification(&$bean, $event, $arguments){

		global $sugar_config;
		global $timedate;
		$myfile = fopen("Logs/CasesEscalationMail.log", "a");
		fwrite($myfile, "\n----------------- Logic hook case escalation :: sendUpdateNotification() starts -----------");
		fwrite($myfile, "\ntime :: " . $timedate->now());
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
        fwrite($myfile, "\nNotification Message : ".$message);
        $this->sendUpdateSms($message);
        $this->sendUpdateEmail($message);
		fwrite($myfile, "\n----------------- Logic hook case escalation :: sendUpdateNotification() ends -----------");


	}
}


?>