<?php
if (!defined('sugarEntry') || !sugarEntry) {
	define('sugarEntry', true);
}
require_once('include/entryPoint.php');
require_once ('custom/CustomLogger/CustomLogger.php');
class SendSMS {

	public function send_sms_to_user($tag_name, $mobile_no, $message, $relatedBean = null, $messageid = null) {
		
		$logger = new CustomLogger('OutgoingSMS');
		
		try {
			
			$logger->log('debug', '<=============== OutgoingSMS Details =================>');
		
			$env = getenv('SCRM_ENVIRONMENT');
			$feedid = getenv('NETCORE_FEEDID');
			$username = getenv('NETCORE_USERNAME');
			$pwd = getenv('NETCORE_PASSWORD');

			$sms_message = urlencode($message);

			if ($env == 'prod') {
				if (empty($mobile_no) || empty($message))
					return "mobile or message blank";

				if (strlen((string)$mobile_no) < 10) {
					return "Mobile number is not valid";
				}
			} else {
				$mobile_no =  getenv('SCRM_TEST_MOBILE');
				if (empty($mobile_no)) {
					$mobile_no = '919987054547';
				}
			}

			$mobile_no = "91" . substr($mobile_no, -10);
			
			$logger->log('debug', 'Mobile No: '.$mobile_no);
			
			// Sending SMS by using the above values
			$url = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=$feedid&username=$username&password=$pwd&To=$mobile_no&Text=$sms_message&jobname=$tag_name";
			$logger->log('debug', 'Curl URL: '.$url);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$output = curl_exec($ch);
			$xml = simplexml_load_string($output);
			$output = trim($output);
			
			$logger->log('debug', 'SMS Curl Response: '.print_r($output, true));
			
			curl_close($ch);

			if (!empty($output)) {
				
				$xml_error = $xml->MID->ERROR->ERROR;
				
				if (!empty($xml_error)) {
					
					$logger->log('fatal', 'Error Occured While Sending SMS!');
					$logger->log('fatal',"Error Code $xml_error->CODE");
					$logger->log('fatal',"Error Description $xml_error->DESC");
					$logger->log('fatal',"Notifications: error sending SMS");
					
					$delivaryStatus="Failed";

				}
		
				$sms_sms = new SMS_SMS();
				$sms_sms->name = substr($mobile_no, -10);
				$sms_sms->description = $message;
				$sms_sms->smsreceivedon = TimeDate::getInstance()->nowDb();
				$sms_sms->delivery_status = $delivaryStatus = "Failed" ? $delivaryStatus : "Send";
				$sms_sms->msg_response = $output;

				if ($relatedBean instanceof SugarBean && !empty($relatedBean->id)) {
					$sms_sms->assigned_user_id = $relatedBean->assigned_user_id;
					$sms_sms->parent_type = $relatedBean->module_dir;
					$sms_sms->parent_id = $relatedBean->id;
					$sms_sms->messageid = $relatedBean->parent_type;
					$sms_sms->save();
					if ($relatedBean->module_dir == 'Cases') {
						$sms_sms->load_relationship('cases_sms_sms_1');
						if (method_exists($sms_sms->cases_sms_sms_1, 'add')) {
							$sms_sms->cases_sms_sms_1->add($relatedBean->id);
						}
					} else if ($relatedBean->module_dir == 'Neo_Customers') {
						$sms_sms->load_relationship('neo_customers_sms_sms_1');
						if (method_exists($sms_sms->neo_customers_sms_sms_1, 'add')) {
							$sms_sms->neo_customers_sms_sms_1->add($relatedBean->id);
						}
					}
				}
				if (!empty($messageid)) {
					$sms_sms->messageid = $messageid;
				}
				$sms_sms->save();
				return true;

			} else {
				
				$logger->log('fatal', 'Error Occured While Sending SMS!');
				return false;
			}

		} catch (Exception $e) {
			
			$logger->log('fatal', 'Error Occured While Sending SMS!');
			$logger->log('fatal','Exception: '.$e->getMessage());
			return false;
		}
	}
}
