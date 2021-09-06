<?php
if(!defined('sugarEntry') || !sugarEntry) {
	define('sugarEntry', true);
}
require_once('include/entryPoint.php');

class SendSMS {

	public function send_sms_to_user($tag_name, $mobile_no, $message, $relatedBean=null, $messageid=null){
		try{
			//Message Details
			$myfile = fopen("Logs/send_sms.log", 'a');
				fwrite($myfile, date("d/m/Y H:i:s"));
			$env = getenv('SCRM_ENVIRONMENT');
			if($env == 'prod'){
				if(empty($mobile_no) || empty($message))
					return "mobile or message blank";

			
				if(strlen((string)$mobile_no)<10){
					return "Mobile number is not valid";
				}
			}
			$feedid = getenv('NETCORE_FEEDID');
			
			$sms_message = urlencode($message);

	        
	        if($env != 'prod'){
	        	$mobile_no =  getenv('SCRM_TEST_MOBILE');
	        	if(empty($mobile_no)){
	        		$mobile_no = '9131952467';
	        	}
	        }


	        $mobile_no = "91" . substr($mobile_no, -10);
			// Login details
			$username = getenv('NETCORE_USERNAME');
			$pwd = getenv('NETCORE_PASSWORD');

			// Sending SMS by using the above values
			$url= "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=$feedid&username=$username&password=$pwd&To=$mobile_no&Text=$sms_message&jobname=$tag_name";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$output = curl_exec($ch);
			curl_close($ch);
			$xml = simplexml_load_string($output);




			$output = trim($output);
			$delivery_status = "sent";
			$print_message = "\nSuccessfully sent SMS\n";
			$xml_error = $xml->MID->ERROR->ERROR;
			fwrite($myfile,print_r($output,true));
		    if(!empty($output)) {
				if(!empty($xml_error)){
					$delivery_status = "failed";
					$print_message = "\nSMS sending failed\n";
					fwrite($myfile,"\n".$print_message."\n");
					// echo "\nError Code : ". $xml_error->CODE . "\n";
					// echo "\nError Description : " . $xml_error->DESC. "\n";
					$GLOBALS['log']->fatal("Error Code $xml_error->CODE");
					$GLOBALS['log']->fatal("Error Description $xml_error->DESC");
					$GLOBALS['log']->fatal("Notifications: error sending SMS");
				}
				// echo $print_message;
				
				
	            $sms_sms = new SMS_SMS();
	            $sms_sms->name = substr($mobile_no, -10);
	            $sms_sms->description = $message;
	            $sms_sms->smsreceivedon = TimeDate::getInstance()->nowDb();
	            $sms_sms->delivery_status = "Sent";
	            $sms_sms->msg_response = $output;
	            if ($relatedBean instanceOf SugarBean && !empty($relatedBean->id) ) {
	                $sms_sms->assigned_user_id = $relatedBean->assigned_user_id;
	                $sms_sms->parent_type = $relatedBean->module_dir;
	                $sms_sms->parent_id = $relatedBean->id;
	                $sms_sms->messageid=$parent_type;
	                $sms_sms->save();
	                if ($relatedBean->module_dir == 'Cases') {
	                	$sms_sms->load_relationship('cases_sms_sms_1');
	                	if (method_exists($sms_sms->cases_sms_sms_1, 'add')) {
		            		$sms_sms->cases_sms_sms_1->add($relatedBean->id);
	                	}
	                }
	                else if ($relatedBean->module_dir == 'Neo_Customers') {
	                	$sms_sms->load_relationship('neo_customers_sms_sms_1');
	                	if (method_exists($sms_sms->neo_customers_sms_sms_1, 'add')) {
		            		$sms_sms->neo_customers_sms_sms_1->add($relatedBean->id);
	                	}
	                }	                
	            }
	            if (!empty($messageid)) {
	            	$sms_sms->messageid=$messageid;
	            }
	            $sms_sms->save();
	            return true;
		    } else {
		    	// echo "\nSome problem occurred in sending SMS\n";
		    	$GLOBALS['log']->fatal("Notifications: error sending SMS");
		    	return false;
			}
			

		    // return $output;
		}
		catch(Exception $e){
			// echo '\nCaught exception while sending SMS : ',  $e->getMessage(), "\n";
			$GLOBALS['log']->fatal("Caught exception while sending SMS");
			$GLOBALS['log']->fatal($e->getMessage());
		}
	}

}
