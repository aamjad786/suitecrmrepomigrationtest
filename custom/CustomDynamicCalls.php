<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
$date = new DateTime();
global $sugar_config;

$myfile = fopen("Logs/CustomDynamicCalls.log", "a");
fwrite($myfile, "\n"."----------------CustomDynamicCalls::starts-----------");
fwrite($myfile, "\n".date('Y-m-d h:i:s'));
fwrite($myfile, "\n".print_r($_REQUEST, true));

if(array_key_exists('payment_link',$_REQUEST)) {
	fwrite($myfile, "\n"."----------------CustomDynamicCalls::payment_link-starts-----------");
	$payment_url 		= getenv('PAYMENT_PORTAL');
	$contact_details 	= $_REQUEST['contact_details'];
	$email_id			= array();
	$env = getenv('SCRM_ENVIRONMENT');
    
    if(in_array($env,array('prod'))){
        array_push($email_id, $contact_details['email_id']);
        $mobile_number 		= $contact_details['mobile_number'];
    }
    else{
		$mobile_number 		= '7373267373';
		$email_id[0]		= 'balayeswanth.b@neogrowth.in';
		$email_id[1]		= "ansul.gupta@neogrowth.in";
    }

	if(empty($payment_url)){
		fwrite($myfile, "\n"."Error/Fatal :: PAYMENT URL IS NOT UPDATED IN CONFIG/ENV. CONTACT ANSUL & TEAM");
		echo "<br>Payment URL is not updated in System. Please contact the system administrator.";
		sendHttpStatusCode('500', 'Internal Server Error');
		return;
	}
	if(empty($contact_details) || (empty($mobile_number) && empty($email_id))){
		fwrite($myfile, "\n"."Error/Fatal :: Contact details are empty");
		echo "<br>Contact details are empty.";
		sendHttpStatusCode('200', 'User input not valid');
		return;
	}

	$body = "Dear Customer, Kindly proceed with the payment at <br>" . $payment_url ."<br><hr>"; 

	if(!empty($mobile_number)){
		require_once('SendSMS.php');    
	    $sms = new SendSMS();
	    //uncomment this once sms template is whitelisted by anusl
	    //$sms_status = $sms->send_sms_to_user($mobile_number, $body, null, 'CustomDynamicCalls::payment_link');	
	    if(!$sms_status){
	    	sendHttpStatusCode('500', 'Internal Server Error');
	    }    
	}

	if(!empty($email_id)){
	    require_once('SendEmail.php');
    	$email = new SendEmail();
    	$email_status = $email->send_email_to_user("NeoGrowth Payment Link", $body, $email_id);
	    if(!$email_status){
	    	sendHttpStatusCode('500', 'Internal Server Error');
	    }
	}
    fwrite($myfile, "\n"."----------------CustomDynamicCalls::payment_link-ends-----------");
}
	
function sendHttpStatusCode($httpStatusCode, $httpStatusMsg) {
    $phpSapiName    = substr(php_sapi_name(), 0, 3);
    if ($phpSapiName == 'cgi' || $phpSapiName == 'fpm') {
        header('Status: '.$httpStatusCode.' '.$httpStatusMsg);
    } else {
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
        header($protocol.' '.$httpStatusCode.' '.$httpStatusMsg);
    }
}
fwrite($myfile, "\n"."----------------CustomDynamicCalls::ends-----------");
if(!empty($myfile)){
	fclose($myfile);
}

?>