<?php 
$job_strings[] = 'UpdateCaseAssignmentExcecutiveRole';
date_default_timezone_set('Asia/Kolkata');


function UpdateCaseAssignmentExcecutiveRole(){
    require_once('custom/modules/Cases/Cases_functions.php');
    $cases_functions = new Cases_functions();
    $response = false;
    $response = $cases_functions->updateCaseAssignmentExcecutiveRole();
    if(!$response){
    	$this->sendNotificationToDevs("PROD CRM Scheduler failed", "UpdateCaseAssignmentExcecutiveRole");
    }
    return $response;
}

function sendNotificationToDevs($subject = "Error in PROD", $body = "Error in PROD"){
    $env = getenv('SCRM_ENVIRONMENT');
    if(true or in_array($env,array('prod'))){
		require_once('custom/include/SendEmail.php');
		$email = new SendEmail();    
		$to_email = array('balayeswanth.b@neogrowth.in','nikhil.kumar@neogrowth.in','gowthami.gk@neogrowth.in');
		$cc_email = array();
		$email->send_email_to_user($subject, $body, $to_email, $cc_email,null,null,1);
    }
}

?>