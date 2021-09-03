<?php

if(!defined('sugarEntry') || !sugarEntry) {
	define('sugarEntry', true);
}
require_once('include/entryPoint.php');
require_once('custom/include/SendEmail.php');

class CallBackFlow {

    private $log;
    function __construct() {
        $this->log = fopen("Logs/WebsiteCallBack.log", "a");
    }
    function __destruct() {
        fclose($this->log);
    }

	/**
	 *	create invite for the user for provided remainders.
	 *	@param reainder bean, assigned user id
	 */	
	function setReminders_Invitees($reminderBean, $user_id){
		$newInvitee = BeanFactory::newBean('Reminders_Invitees');
		$newInvitee->reminder_id = $reminderBean->id;
		$newInvitee->related_invitee_module = 'Users';
		$newInvitee->related_invitee_module_id = $user_id;
		$newInvitee->save();
		// echo "after newInvitee $newInvitee->id <br>";
	}

	/**
	 *	creates a new remainder
	 *	@param various options to set popup, enable emails, pop up timer before event, email timer before event, any related *	bean, timers are in seconds
	 *	@return email subject
	 */	
	function setRemainderAndInvite($show_popup = 1, $send_email = 1, $popup_timer = 1, $email_timer = 1, $related_bean){
		$reminderBean 					= BeanFactory::newBean('Reminders');
		$reminderBean->popup 			= $show_popup;
		$reminderBean->email 			= $send_email;
		$reminderBean->email_sent 		= 0;
		$reminderBean->timer_popup 		= $popup_timer;
		$reminderBean->timer_email 		= $email_timer;
		$reminderBean->related_event_module 	= $related_bean->module_name;
		$reminderBean->related_event_module_id 	= $related_bean->id;
		$reminderBean->save();
		// echo "after reminderBean $reminderBean->id <br>";
		return $reminderBean;
	}

	/**
	 *	Called by crmapi on website call back case creation
	 *	@param case id, call back starting time, duration of the call
	 */
	function createCallBackFlow($case_id, $call_back_start_time_c, $call_back_duration_c){
		fwrite($this->log, "\n--------------CallBackFlow::createCallBackFlow start ".date('Y-m-d H:i:s')."----------------\n");
		fwrite($this->log, "\n" . "Entry case id : " . $case_id);
		$case_bean = BeanFactory::getBean('Cases', $case_id); 
		fwrite($this->log, "\n" . "existing assigned user : " . $case_bean->assigned_user_id);
        $assigned_user = new User;
        $assigned_user->retrieve($case_bean->assigned_user_id);
        fwrite($this->log, "\n" . "assigned_user user id : " . $assigned_user->id);

		$call = new Call();
		$call->name = "Website Call Back : " . $case_bean->merchant_name_c . "-" . $case_bean->merchant_contact_number_c;
		$call->description = "Website Call Back : " . $case_bean->merchant_name_c . "-" . $case_bean->merchant_contact_number_c;
		fwrite($this->log, "\n" . "Given local start time: " . $call_back_start_time_c);
		$call_back_start_time_c_db = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes",strtotime($call_back_start_time_c)));
		fwrite($this->log, "\n" . "After converting to DB format : " . $call_back_start_time_c_db);
		$call->date_start = $call_back_start_time_c_db;
		$call_back_end_time_c = date("Y-m-d H:i:s", strtotime("+$call_back_duration_c minutes",strtotime($call_back_start_time_c_db)));
		fwrite($this->log, "\n" . "DB end time: " . $call_back_start_time_c_db);
		$call->date_end = $call_back_end_time_c;
		$call->lead_id = $case_bean->id;
		$call->parent_type = "Cases";
		$call->parent_id = $case_bean->id;
		$call->assigned_user_id = $case_bean->assigned_user_id;
		$call->direction = "Outbound";
		$call->status = "Planned";
                $call->calls_type_of_activity = "website_call_back";
		$call->duration_minutes = $call_back_duration_c;
		$call->modified_user_id = 1;
		$call->created_by = 1;
		$call->save();

		$case_bean->load_relationship('calls');
		$case_bean->calls->add($call->id);
		
		$alert_bean = BeanFactory::newBean('Alerts');
		$alert_bean->name = 'Case Created';
		$alert_bean->description = "Website Call Back";
		$alert_bean->url_redirect = "index.php?module=Cases&action=DetailView&record=".$case_bean->id;
		$alert_bean->target_module = 'Cases';
		$alert_bean->is_read = 0;
		$alert_bean->assigned_user_id = $case_bean->assigned_user_id;
		$alert_bean->type = 'info';
		$alert_bean->save();
		fwrite($this->log, "\n" . "after alert_bean creation: " . $alert_bean->id);


		if(isset($assigned_user->id) && !empty($assigned_user->id)){
			fwrite($this->log, "\n" . "Assigned Users found");
			//For 15 & 1 MinPrior set remainder and add invites to assigned user and his/her reporting
			$reminderBean_15 = $this->setRemainderAndInvite(1, 1, 15*60, 15*60, $call);
			$reminderBean_1 = $this->setRemainderAndInvite(1, 1, 60, 60, $call);
			fwrite($this->log, "\n" . "reminderBean_15 id : " . $reminderBean_15->id);
			fwrite($this->log, "\n" . "reminderBean_1 id : " . $reminderBean_1->id);
			$assigned_user->load_relationship('calls');
			$assigned_user->calls->add($call->id);	
			$this->setReminders_Invitees($reminderBean_15, $assigned_user->id);
			$this->setReminders_Invitees($reminderBean_1, $assigned_user->id);

        	$assigned_user_manager = new User;
        	$assigned_user_manager->retrieve($assigned_user->reports_to_id);
			$assigned_user_manager->load_relationship('calls');
			$assigned_user_manager->calls->add($call->id);
			fwrite($this->log, "\n" . "assigned_user_manager id : " . $assigned_user_manager->id);
			if(isset($assigned_user_manager->id) && !empty($assigned_user_manager->id)){
				$this->setReminders_Invitees($reminderBean_15, $assigned_user->reports_to_id);
				$this->setReminders_Invitees($reminderBean_1, $assigned_user->reports_to_id);
			}
		  	$call_back_time_range = date('d/m/Y h:i A', strtotime($call->date_start.'+330 minutes')) 
				. ' - ' . date('h:i A', strtotime($call->date_end.'+330 minutes'));
			fwrite($this->log, "\n" . "call_back_time_range : " . $call_back_time_range);
			$this->sendMails($case_bean,$call_back_time_range,$assigned_user,$assigned_user_manager,"- Creation");
			$this->sendMailsToMerchant($case_bean, $call);
		}

	}

	/**
	 *	Send mails and SMS to merchant with unique case creation template 
	 *	@param case bean, call bean
	 */
	function sendMailsToMerchant($case_bean, $call_bean){
		$email = new SendEmail();
        $c = new aCase;
        $c->retrieve($case_bean->id);
        require_once('custom/modules/Cases/data_sync.php');
        $datasync = new DataSync();
        $templ_array = $datasync->getEmailTemplate($c, 'Case Creation Template');
		$sub = $templ_array['subject'];
		$body = $this->getMerchantCaseCreationEmailBody($case_bean, $call_bean);
       	$app_host = getenv('SCRM_ENVIRONMENT');
       	fwrite($this->log, "\n" . "Sending Mail status :: " . $email_result);
        $requestNumber = $c->case_number;
        $betweenFrom = $call_bean->date_start;
        $initialTime = substr($betweenFrom, strpos($betweenFrom, " ") + 1);
        $dateAlone = strtok($betweenFrom, ' ');
        $date = date('F j, Y',strtotime($dateAlone));
        $fromTime = date("g:i A", strtotime("$initialTime UTC"));
        $betweenTo = $call_bean->date_end;
        $endTime = substr($betweenTo, strpos($betweenTo, " ") + 1);
        $toTime = date("g:i A", strtotime("$endTime UTC"));
        $message = "Dear Customer, We have registered your request under service request number $requestNumber. Our executive will call you between $fromTime & $toTime on $date. Team NeoGrowth";
        fwrite($this->log, "\n" . "SMS :: " . $message . "to ". $case_bean->merchant_contact_number_c);
        require_once('SendSMS.php');    
        $sms = new SendSMS();
        if($app_host == 'prod'){
            $email_result = $email->send_email_to_user($sub,$body,array($case_bean->merchant_email_id_c),null,$case_bean,array('helpdesk@neogrowth.in'), array(),1);
            $to = $case_bean->merchant_contact_number_c;
        } else{
            $email_result = $email->send_email_to_user($sub,$body,array('balayeswanth.b@neogrowth.in'),null,$case_bean,array(),1);
            $to = "9743473424";
            $reponse = $sms->send_sms_to_user($tag_name="Cust_CRM_10", $to, $message, $case_bean);
        }
        fwrite($this->log, "\n" . "Sending Mail status :: " . $email_result);
	}

	/**
	 * called by emailremainder.php, for remainders
	 */
	function sendMailAdapter($call_bean){
		global $timedate;
        fwrite($this->log, "\n-------------sendMailAdapter() starts------------\n");
        fwrite($this->log, "\n--------------" . $timedate->now() . "-----------\n");
		require_once('custom/include/SendEmail.php');
		$email = new SendEmail();
		$email_result = false;
		fwrite($this->log, "\n" . "sendMailAdapter: call id :: " . $call_bean->id);
		$case_id 	= $call_bean->parent_id;
		$case_bean 	= BeanFactory::getBean('Cases', $case_id); 
		if($case_bean->is_call_back_c != 1){
			return -1;	
		}
		$body = $this->getEmailBodyForCallBackRemainder($case_bean,$call_bean);
		fwrite($this->log, "\n" . "body: ".print_r($body,true));
		// $subject = $this->getEmailSubForCallBackRemainderFromCall($call_bean);
		$to_email = $this->getToMailForAcase($case_bean);
		$subject = $this->getEmailSubForCallBackRemainder($case_bean)."- reminder";
		fwrite($this->log, "\n" . "Subject : " . $subject);
		$cc_email = array();
        if(!empty($to_email)){
        	fwrite($this->log, "\n" . "Sending Mail from adapter......" . "\n");
    	    $email_result = $email->send_email_to_user($subject, $body, $to_email, $cc_email,$case_bean,null,1);
    	    fwrite($this->log, "\n" . "Sending Mail status :: " . $email_result);
        }
        else{
        	fwrite($this->log, "\n" . "To Mail address is empty :: case_bean id - ", $case_bean->id);
        }
        return $email_result;
	}

	/**
	 *	used for creation, assignment : used from crmapi and datasync
	 *	@param case_bean, call_back_time_range, assigned_user, assigned_user_manager
	 */
	function sendMails($case_bean,$call_back_time_range,$assigned_user=null,$assigned_user_manager=null,$subject_arg=""){
		global $timedate;
        fwrite($this->log, "\n-------------sendMails() starts------------\n");
        fwrite($this->log, "\n--------------" . $timedate->now() . "-----------\n");
		require_once('custom/include/SendEmail.php');
        $email = new SendEmail();
        $body = $this->getEmailBody($case_bean, $assigned_user, $assigned_user_manager, $call_back_time_range);
        // fwrite($this->log, "\n" . "body: ".print_r($body,true));
        $subject = $this->getEmailSubForCallBackRemainder($case_bean);
        if(!empty($subject_arg)){
        	$subject .= $subject_arg;
        }
        fwrite($this->log, "\n" . "Subject : " . $subject);
        $to_email = array();
        $to_email = $this->getToMailArray($case_bean, $assigned_user, $assigned_user_manager);
        $cc_email = array('sumeet.thanekar@neogrowth.in','mangal.sarang@neogrowth.in','dipali.londhe@neogrowth.in');    
        // $cc_email = array('balayeswanth.b@neogrowth.in', 'gowthami.gk@neogrowth.in');
        if(!empty($to_email)){
        	fwrite($this->log, "\n" . "Sending Mail from sendMails() ......" . "\n");
    	    $email_result = $email->send_email_to_user($subject, $body, $to_email, $cc_email, $case_bean,null,1);
    	    fwrite($this->log, "\n" . "Sending Mail status :: " . $email_result);

        }
        else{
        	fwrite($this->log, "\n" . "To Mail address is empty :: assigned_user id - ", $assigned_user->id);
        	fwrite($this->log, "\n" . "To Mail address is empty :: assigned_user_manager id - ", $assigned_user_manager->id);
        }
	}

	function getEmailBody($case_bean, $assigned_user, $assigned_user_manager, $call_back_time_range){
		$assigned_user_name = $assigned_user->first_name . " " . $assigned_user->last_name;
		$manager_name = $assigned_user_manager->first_name . " " . $assigned_user_manager->last_name;
		fwrite($this->log, "\n" . "Sending Mail Assigned user name : " . $assigned_user_name);
		fwrite($this->log, "\n" . "Sending Mail Manager user name : " . $manager_name);
        $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$case_bean->id);
	    $table = '<style>
	            table, th, td {
	                border: 1px solid black;
	                border-collapse: collapse;
	            }
	            th, td {
	                padding: 10px;
	            }
	            </style>
	        <table style="width:100%; text-align:center">
	        <tbody>
	        <tr>
	          <th>Case Number</th>
	          <th>Merchant Name</th> 
	          <th>Contact Number</th>
	          <th>Call Back Time</th>
	          <th>Assigned To</th>
	          <th>Supervisor</th>
	        </tr>';
	    if($case_bean) {
	        $table .= "<tr>"
	        		. "<td>" . $case_bean->case_number . "</td>"
	                . "<td>" . $case_bean->merchant_name_c . "</td>"
	                . "<td>" . $case_bean->merchant_contact_number_c . "</td>"
	                . "<td>" . $call_back_time_range . "</td>"
	                . "<td>" . $assigned_user_name . "</td>"
	                . "<td>" . $manager_name . "</td>"
	                . "</tr>";
	    }
	    $table .= '</tbody></table>';
	    $desc = "<br/>You may review this Case at:<br/><a href='".$url."'>".$url."</a>";
	    $body = "Hi, </br></br>"
	            . "The following is the details of call back case assigned to you.</br></br>"
	            . "$table $desc"
	            . "</br>Be sure to note your comments in the comments section in the CRM. </br></br>"
	            . " Thanks, </br>CRM Technology Team</br>";
	    return $body;	
	}

	/**
	 *	Support function.	
	 *	Returns website callback case bean
	 *	@param call bean
	 *	@return case bean
	 */
	function getCaseFromCall($call_bean){
		// echo "call id '$call_bean->id':: parent case id ::";print_r($case_id);echo "<br>";
		if(empty($case_id)){
			// echo "<br>inside null";echo "<br>";
			return null;
		}
		$case_bean 	= BeanFactory::getBean('Cases', $case_id); 
		if($case_bean->is_call_back_c != 1){
			return null;	
		}
		return $case_bean;		
	}

	/**
	 *	Returns website callback case creation mail body for agents when assigned user id changes
	 *	@param call bean
	 *	@return email body
	 */
	function getEmailBodyForCallBackRemainderFromCall($call_bean){
		fwrite($this->log, "\n" . "getEmailBodyForCallBackRemainderFromCall: call id :: " . $call_bean->id);
		$case_id 	= $call_bean->parent_id;
		$case_bean = $this->getCaseFromCall($call_bean);
		return $this->getEmailBodyForCallBackRemainder($case_bean,$call_bean);
	}

	/**
	 *	Returns website callback case creation mail body for agents when assigned user id changes
	 *	@param case bean
	 *	@return email body
	 */
	function getEmailBodyForCallBackRemainder($case_bean,$call_bean=null){
		$body = "";
        $assigned_user = new User;
        if(empty($call_bean))
        	$call_bean = $this->getCallFromCase($case_bean);
        $assigned_user->retrieve($case_bean->assigned_user_id);
    	$assigned_user_manager = new User;
    	$assigned_user_manager->retrieve($assigned_user->reports_to_id);
    	$call_back_start_time_c = $call_bean->date_start;
	  	$call_back_end_time_c = $call_bean->date_end;
	  	$call_back_time_range = date('d/m/Y h:i A', strtotime($call_back_start_time_c . " +0 minutes")) 
			. ' - ' . date('h:i A', strtotime($call_back_end_time_c . " +0 minutes"));
		$body = $this->getEmailBody($case_bean, $assigned_user, $assigned_user_manager, $call_back_time_range);
		return $body;
	}

	/**
	 *	Returns website callback case creation mail subject
	 *	@param call bean
	 *	@return email subject
	 */
	function getEmailSubForCallBackRemainderFromCall($call_bean){
		$case_id 	= $call_bean->parent_id;
		fwrite($this->log, "\n" . "getEmailSubForCallBackRemainderFromCall: call id :: " . $call_bean->id);
		if(empty($case_id)){
			return null;
		}
		$case_bean 	= BeanFactory::getBean('Cases', $case_id); 
		if($case_bean->is_call_back_c != 1){
			return null;	
		}
		return $this->getEmailSubForCallBackRemainder($case_bean);
	}

	/**
	 *	Returns website callback case creation mail subject
	 *	@param case bean
	 *	@return email subject
	 */
	function getEmailSubForCallBackRemainder($case_bean){
		if(empty($case_bean) || empty($case_bean->id) || $case_bean->is_call_back_c != 1){
			return null;
		}
		return "Call Back Request Reminder for " . $case_bean->case_number;
	}

	/**
	 *	Returns website callback case creation mail to array
	 *	@param case bean
	 *	@return email to array
	 */
	function getToMailForAcase($case_bean){
		$to_email = array();
		fwrite($this->log, "\n" . "getToMailForAcase: inside");
		if($case_bean->is_call_back_c != 1){
			return $to_email;	
		}
        $assigned_user = new User;
        $assigned_user->retrieve($case_bean->assigned_user_id);
        if(!empty($assigned_user->id)){
        	$assigned_user_manager = new User;
        	$assigned_user_manager->retrieve($assigned_user->reports_to_id);
        }
        fwrite($this->log, "\n" . "assigned_user id : " . $assigned_user->id);
        fwrite($this->log, "\n" . "assigned_user_manager id : " . $assigned_user_manager->id);
        return $this->getToMailArray($case_bean, $assigned_user, $assigned_user_manager);
	}

	/**
	 *	Returns website callback assigned_user mail id, his/her manager mail_id
	 *	@param case bean, assigned_user bean, assigned_user_manager bean
	 *	@return email to array
	 */
	function getToMailArray($case_bean, $assigned_user = null, $assigned_user_manager = null){
		$to_email = array();
		$env = getenv('SCRM_ENVIRONMENT');
		fwrite($this->log, "\n" . "env: ". $env);
        if(in_array($env,array('prod'))){
	        if(!empty($assigned_user->email1)){
	        	array_push($to_email, $assigned_user->email1);
	        }
	        if(!empty($assigned_user_manager->email1)){
	        	array_push($to_email, $assigned_user_manager->email1);
	        }
        }
        else{
        	array_push($to_email, "balayeswanth.b@neogrowth.in");
        }
        fwrite($this->log, "\n" . "to_email: ".print_r($to_email,true));
        return $to_email;
	}
	/**
	 *	Kserve agents have username, first name, last name as 'kserve_', this function fetches actual user name 
	 *	from AgentPRIMapping table. This table is updated by the excel uploaded by the user on timely basis
	 *	@param kserve user bean
	 *	@return actual user name
	 */
	function getKserverName($kserve_user){
		fwrite($this->log, "\n" . "Fetching actual_name for $kserve_user->user_name");
		$actual_name = "";
		global $db;
		if(empty($kserve_user->user_name)){
			fwrite($this->log, "\n" . "Empty kserve_user name. user_id :: $kserve_user->id");
			return $actual_name;
		}
		$query = "
			SELECT agent_name
			FROM AgentPRIMapping
			WHERE agent_id = '$kserve_user->user_name'
			AND deleted = 0
			ORDER BY date_entered desc
			LIMIT 1
		";
		$results = $db->query($query);
		while($row = $db->fetchByAssoc($results)){
			fwrite($this->log, "\n" . "actual_name for $kserve_user->user_name - " . $row['agent_name'] );
			$actual_name = $row['agent_name'];
		}
		return $actual_name;
	}
	/**
	 *	Prepares email body for provided list of case beans
	 */
	function getEmailBodyListCases($case_bean_list, $assigned_user, $assigned_user_manager){
		$is_call_available = false;
		$assigned_user_name = $assigned_user->first_name . " " . $assigned_user->last_name;
		$manager_name = $assigned_user_manager->first_name . " " . $assigned_user_manager->last_name;
		fwrite($this->log, "\n" . "Sending Mail Assigned user name : " . $assigned_user_name);
		if(preg_match("/kserve/", strtolower($assigned_user_name))){
			$actual_name = $this->getKserverName($assigned_user);
			if(!empty($actual_name)){
				$assigned_user_name = $actual_name;
				fwrite($this->log, "\n" . "Sending Mail Assigned user name (actual): " . $assigned_user_name);
			}
		}
		fwrite($this->log, "\n" . "Sending Mail Manager user name : " . $manager_name);
    	$date = strtotime("+1 day");
    	$date = date('Y-m-d', $date);
    	$start_date = $date . ' 00:00:00';
    	$end_date = $date . ' 23:59:59';
    	fwrite($this->log, "\n" . "Calls start date : " . $start_date);
    	fwrite($this->log, "\n" . "Calls end date : " . $end_date);
	    $table = '<style>
	            table, th, td {
	                border: 1px solid black;
	                border-collapse: collapse;
	            }
	            th, td {
	                padding: 10px;
	            }
	            </style>
	        <table style="width:100%; text-align:center">
	        <tbody>
	        <tr>
	          <th>Case Number</th>
	          <th>Merchant Name</th> 
	          <th>Contact Number</th>
	          <th>Call Back Time</th>
	          <th>Call Back Duration</th>
	          <th>Assigned To</th>
	          <th>Supervisor</th>
	          <th>Link</th>
	        </tr>';
	    foreach ($case_bean_list as $case_bean) {
	    	$url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$case_bean->id);
	    	$bean = BeanFactory::getBean('Calls');
 			$query = "calls.deleted=0 and calls.parent_type = 'Cases' and calls.parent_id = '$case_bean->id'
 				and calls.date_start between '$start_date' and '$end_date'";
			fwrite($this->log, "\n" . "query for fetching calls : " . $query);
 			$items = $bean->get_full_list('',$query);
 			foreach ($items as $call_bean) {
		    	if(empty($call_bean->id)){
		    		continue;
		    	}
		    	$is_call_available = true;
				fwrite($this->log, "\n" . "call_bean id : " . $call_bean->id);
				$call_back_start_time = date("Y-m-d H:i:s", strtotime("+5 hours, +30 minutes",strtotime($call_bean->date_start)));
			    $link = "<a target='_blank' href='".$url."'>Open</a>";
		        $table .= "<tr>"
		        		. "<td>" . $case_bean->case_number . "</td>"
		                . "<td>" . $case_bean->merchant_name_c . "</td>"
		                . "<td>" . $case_bean->merchant_contact_number_c . "</td>"
		                . "<td>" . $call_back_start_time . "</td>"
		                . "<td>" . $call_bean->duration_minutes . "</td>"
		                . "<td>" . $assigned_user_name . "</td>"
		                . "<td>" . $manager_name . "</td>"
		                . "<td>" . $link . "</td>"
		                . "</tr>";
		    }
	    }
	    $table .= '</tbody></table>';
	    $body = "Hi, </br></br>"
	            . "The following is the details of call back case assigned to you.</br></br>"
	            . "$table "
	            . "</br>Be sure to note your comments in the comments section in the CRM. </br></br>"
	            . " Thanks, </br>CRM Technology Team</br>";
	    //If calls are not available no need for body
	    if($is_call_available){
	    	return $body;
	    }
	    else{
	    	return "";
	    }
	}
	/**
	 *	Called by scheduler to send remainder mail with full list of web call back dumbs for next day
	 *	@return bool status
	 */
	function sendCallBackRemaindersDump(){
        fwrite($this->log, "\n-------------sendCallBackRemaindersDump() starts------------\n");
        global $timedate;
        fwrite($this->log, "\n--------------" . $timedate->now() . "-----------\n");
		global $db;
		$return_status = true;
		$query = "
			SELECT DISTINCT assigned_user_id FROM cases
			JOIN cases_cstm on cases.id = cases_cstm.id_c
			WHERE is_call_back_c = 1
			AND state != 'Closed'
			AND deleted = 0
		";
		$results = $db->query($query);
		fwrite($this->log, "\n" . "results : " . print_r($results, true));
		while ($row = $db->fetchByAssoc($results)) {
			$assigned_user_id = $row['assigned_user_id'];
			if(empty($row['assigned_user_id'])){
				continue;
			}
			// Precaution to avoid trying to access deleted user.
	        $assigned_user = new User;
	        $assigned_user->retrieve($assigned_user_id);
			if(empty($assigned_user->id)){
				continue;
			}
			// print_r($assigned_user_id); echo "<br>";
			fwrite($this->log, "\n" . "row assigned_user id : " . $row['assigned_user_id']);
			$bean = BeanFactory::getBean('Cases');
			$query = "cases.deleted=0 and cases.state!='Closed' and cases.is_call_back_c = 1 and cases.assigned_user_id = '$assigned_user_id'";
			$case_bean_list = $bean->get_full_list('',$query);
	        fwrite($this->log, "\n" . "assigned_user id : " . $assigned_user->id);
	        if(!empty($assigned_user->id)){
	    		$assigned_user_manager = new User;
	    		$assigned_user_manager->retrieve($assigned_user->reports_to_id);
	    		fwrite($this->log, "\n" . "assigned_user_manager id : " . $assigned_user_manager->id);
	        }
	        if(sizeof($case_bean_list)>0){
	        	$body = $this->getEmailBodyListCases($case_bean_list, $assigned_user, $assigned_user_manager);
	        	// print_r($body);
		        $subject = "Call Back Reminder For Tommrrow";
		        $to_email = array();
		        if(!empty($assigned_user->email1))
		        	array_push($to_email, $assigned_user->email1);
		        if(!empty($assigned_user_manager->email1))
		        	array_push($to_email, $assigned_user_manager->email1);
		        $cc_email = array();

		        if(!empty($body) && !empty($to_email)){
					require_once('custom/include/SendEmail.php');
					$email = new SendEmail();
		        	fwrite($this->log, "\n" . "Sending Mail for remainders dump......" . "\n");
		    	    $status = $email->send_email_to_user($subject, $body, $to_email, $cc_email,null,null,1);
		    	    fwrite($this->log, "\n" . "Status of sending remainders dump mail :: " . $status);
		    	    $return_status = $return_status and $status;

		        }
		        else{
		        	fwrite($this->log, "\n" . "To Mail address is empty :: assigned_user id - ", $assigned_user->id);
		        	fwrite($this->log, "\n" . "To Mail address is empty :: assigned_user_manager id - ", $assigned_user_manager->id);
		        }
	        }
		}
		return $return_status;
	}

	/**
	 *	get immediate next call for the provided case. Not used
	 *	@param case bean
	 *	@return email subject
	 */	
	function getCallFromCase($case_bean){
		$bean = BeanFactory::getBean('Calls');
			$query = "calls.deleted=0 and calls.parent_type = 'Cases' and calls.parent_id = '$case_bean->id'
			and calls.date_start > NOW() - INTERVAL 330 MINUTE";
		fwrite($this->log, "\n" . "query for fetching calls : " . $query);
			$items = $bean->get_full_list('',$query);
			if(!empty($items[0]->id)){
				fwrite($this->log, "\n" . "Call ID  : " . $items[0]->id);
				return $items[0];		
			}
			else{
				$GLOBALS['log']->fatal("function::getCallFromCase. Case ID - $case_bean->id, Call bean are empty. 
					query - $query");
				return "";
			}
	}

	/**
	 *	Not implemented
	 *	Called by logic hooks, returns website callback case creation mail subject for merchant
	 *	@param case bean
	 *	@return email subject
	 */	
	function getMerchantCaseCreationEmailSub($case_bean){
		$subject = "";
		return $subject;
	}

	/**
	 *	Called by logic hooks, returns website callback case creation mail body for merchant
	 *	@param case bean
	 *	@return email subject
	 */	
	function getMerchantCaseCreationEmailBody($case_bean, $call_bean){
		fwrite($this->log, "\n" . "function::getMerchantCaseCreationEmailBody()----Starts");
		fwrite($this->log, "\n" . "case id: " . $case_bean->id);
		if(!empty($call_bean->id)){
			$call_back_start_time_c = $call_bean->date_start;
			fwrite($this->log, "\n" . "call_back_start_time_c: " . $call_back_start_time_c);
			$call_back_date = date('d-m-Y', strtotime($call_back_start_time_c));
			fwrite($this->log, "\n" . "call_back_date: " . $call_back_date);
			$call_back_end_time_c = $call_bean->date_end;
			fwrite($this->log, "\n" . "call_back_end_time_c: " . $call_back_end_time_c);
			$call_back_time = date('h:i A', strtotime($call_back_start_time_c . " +330 minutes")) 
				. ' - ' . date('h:i A', strtotime($call_back_end_time_c . " +330 minutes"));
			fwrite($this->log, "\n" . "call_back_time: " . $call_back_time);
		}
		else{
			$GLOBALS['log']->fatal("function::getMerchantCaseCreationEmailBody. Case ID - $case_bean->id, Call bean are empty");
		}
		$body = "<p>
					Dear Sir/Madam,<br>
					<br>
					Greetings from NeoGrowth!<br>
					<br>
					Thank you for registering a call back.<br>
					<br>
					Our customer service representative will call you as requested.<br>
					Call time between $call_back_time<br>
					Dated:- $call_back_date <br>
					We look forward to the call.<br>
					<br>
					Thanks & Regards<br>
					Team Customer Service<br>
					NeoGrowth Credit Pvt. Ltd<br>
					Customer Care Number|1800-4195565/9820655655<br>
					Email: helpdesk@neogrowth.in<br>
					Business Timing: 10.00 am to 6.00 Pm, Monday-Friday<br><hr>
					</p>
					";
		return $body;
	}












}
?>