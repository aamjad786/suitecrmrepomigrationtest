<?php
if (! defined ( 'sugarEntry' ))
	define ( 'sugarEntry', true );

require_once ('modules/Configurator/Forms.php');
include_once ('modules/Configurator/Configurator.php');
require_once ('modules/SMS_SMS/firesms.php');
require_once ('modules/SMS_SMS/sms_config_meta.php');
require_once ('modules/SMS_SMS/license/OutfittersLicense.php');
include_once ('sugar_version.php');
//$global_template_id = '';
function curl_request_async($url, $params, $type='POST')
  {
      // foreach ($params as $key => &$val) {
      //   if (is_array($val)) $val = implode(',', $val);
      //   $post_params[] = $key.'='.urlencode($val);
      // }
      // $post_string = implode('&', $post_params);

      $parts=parse_url($url);

      $fp = fsockopen($parts['host'],
          isset($parts['port'])?$parts['port']:80,
          $errno, $errstr, 30);

      // Data goes in the path for a GET request
      if('GET' == $type) $parts['path'] .= '?'.$parts['query'];
      echo $parts['path'];
      $out = "$type ".$parts['path']." HTTP/1.1\r\n";
      $out.= "Host: ".$parts['host']."\r\n";
      $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
      $out.= "Content-Length: ".strlen($parts['query'])."\r\n";
      $out.= "Connection: Close\r\n\r\n";
      // Data goes in the request body for a POST request
      //if ('POST' == $type && isset($post_string)) $out.= $post_string;

      fwrite($fp, $out);
      fclose($fp);
  }

function callurl($url) {
	echo "calling url ".$url;
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	$output = curl_exec ( $ch );
	//var_dump($output);//($output);
	curl_close ( $ch );
	$xml = simplexml_load_string($output); 
	print_r($xml);
	// echo $output;
	return $xml;
}
function callurl2($url,$fields) {
	$filename = "file".$fields['time'].".txt";
	 //echo $filename;
	$myfile = fopen($filename,"a");
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt($ch,CURLOPT_POST,count($fields));
	$fields_string="";
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	$fields_string = rtrim($fields_string,'&');
	//print_r($fields_string);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
	$output = curl_exec ( $ch );
	curl_close ( $ch );
	$xml = simplexml_load_string($output); 
	fwrite($myfile, print_r($fields,true));
	fwrite($myfile, $xml);
	fwrite($myfile, "Post done\n");
	fclose($myfile);
	print_r($xml);
	// echo $output;
	return $xml;
}
function save($id,$to,$message,$req_id,$status){
	// # START Send Message Entry Log in SMS Module
	$list_to = explode(",",$to);
	
	foreach($list_to as $l) {
		//echo $l;
		$SMS_SMS = new SMS_SMS ();
		$SMS_SMS->parent_type = 'prospects';
		$SMS_SMS->parent_id = $id;
		$SMS_SMS->name = $l;
		$SMS_SMS->description = $message;
		$SMS_SMS->delivery_status = $status;//$sent;
		$SMS_SMS->msg_response = $req_id;//$phone_mobile;//null;//$smsResponse->response;
		$SMS->smsreceivedon = date('Y-m-d H:i:s');
		$SMS_SMS->save();
	}
}
function sendnetcore($to,$message,$time,$contactid){
	// $filename = "file".$time.".txt";
	// $myfile ($filename,"a");
	echo "Schduled ".$message." to \n";
	try {
		if ($to != "" && $message != "") {
			$welcome = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi";
			$fields = array(
				'username'=>getenv('NETCORE_USERNAME'),
				'password'=>urlencode(getenv('NETCORE_PASSWORD')),
				'feedid'=>getenv('NETCORE_FEEDID'),
				'Text'=>urlencode($message),
				'To'=>$to,
				'time'=>$time

			);
			//var_dump($fields);
			$xml = callurl2($welcome,$fields);
			//save($contactid,$to,$message,null,'queued');
			
		}
		echo "<br/><a href='?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSMS_SMS%26action%3Dindex%26parentTab%3DAll'>Check SMS Delivery Report</a>";
	} catch (Exception $e) {
		//var_dump($e->getMessage());
	    echo $e->getMessage();
	}
}

global $sugar_config, $current_user;
foreach ( $config_meta as $key => $value ) {
	if (! isset ( $sugar_config [$key] )) {
		$sugar_config [$key] = '';
		$GLOBALS ['sugar_config'] [$key] = '';
	}
}
// smsProvider
$configurator = new Configurator ();
$focus_admin = new Administration ();

if (isset ( $_REQUEST ['option'] )) {
	
	switch ($_REQUEST ['option']) {
		// FOR SMS Credit
		case "smsCredit" :
			// # START Checking SMS Username and Password
			if (empty ( $sugar_config ['firesms_username'] ) || empty ( $sugar_config ['firesms_password'] )) {
				echo "<div style='background-color:#fff;padding:10px;text-align:center;'>Please check your '<b>Username</b>' and '<b>Password</b>'. ";
				echo "Click <a href='./index.php?module=Administration&action=FireTextSMS'>here</a> to setup your SMS account.</div>";
				break;
			}
			// # END Checking SMS Username and Password
			
			// # START Authenticate SMS Username and Password -> Checking Credit Available or not
			FireText::SetAuth ( $sugar_config ['firesms_username'], $sugar_config ['firesms_password'] );
			$credit = FireText::credits ();
			if ($credit->code == 0) {
				echo "<div style='color:green'>Account has " . $credit->data . " credit(s)</div>";
			} else {
				echo "<div style='color:red'>Error: " . $credit->response . "</div>";
				echo "<div style='background-color:#fff;'>Please check your '<b>Username</b>' and '<b>Password</b>'. Once Update Username and password Save it First, then check credit.";
			}
			// # END Authenticate SMS Username and Password -> Checking Credit Available or not
			
			break;
		
		// SAVE SMS SETTING
		case "save" :
			
		
		// SEND SMS (Only used by Custom Reports module)
		case "send" :
			$to = $_REQUEST["to"];
			$message = $_REQUEST['sms'];
			if(!empty($to) && !empty($message)){
				require_once('SendSMS.php');
				$send_sms = new SendSMS();
				echo $send_sms->send_sms_to_user($tag_name="Cust_CRM_40",$to, $message, null, 'scrm_Custom_Reports');
			}
			
		// SMS TEMPLATE
		case "netcore" :
			$d = new DateTime($_REQUEST['datetime-l']);
			$filename = "file".$d->format('YmdHi').".txt";
			//echo $filename;
			// $myfile = fopen($filename, "w");
			// fwrite($myfile, "Hello\n");
			// fwrite($myfile, print_r($_REQUEST,true));
			if($_REQUEST['masssms']=='1'){
					$sms_message = $_REQUEST ["sms_messag"];
					
					// # START Contact Send SMS Related Prospect List
					$d = new DateTime($_REQUEST['datetime-l']);
					$bean = new sms_campaign();
					$bean->sms_template_id = $_REQUEST['template_id'];
					$bean->target_list_id = $_REQUEST ["pid"];
					$bean->name  = $_REQUEST['campaign-name'];
					$bean->description = $sms_message;
					$bean->scheduled_time = $d->format('Y-m-d H:i:s');
					$bean->is_processed = 0;
					
					if (isset ( $_REQUEST ['personalizeh'] ) && $_REQUEST ['personalizeh']=='1') {
						$bean->is_personalized = 1;
						echo "Personalized message scheduled";
						// fwrite($myfile, "\nPersonalized message scheduled\n");
					}else{
						$bean->is_personalized = 0;
						echo "message scheduled";
						// fwrite($myfile, "\nmessage scheduled\n");
					}
					$bean->save();
			// fclose($myfile);
			}else{
				echo "Functionality blocked, contact Administrator.";
				break;
				try{
					$_REQUEST ['phone_no'] = "91".str_replace ( " ", "", trim ( $_REQUEST ["number"] ) );
							$_REQUEST ['msg'] = urlencode( $_REQUEST ["sms_messag"] );
							try {
								if ($_REQUEST ['phone_no'] != "" && $_REQUEST ['msg'] != "") {
									$username = getenv('NETCORE_USERNAME');
									$password = getenv('NETCORE_PASSWORD');
									$feedid = getenv('NETCORE_FEEDID');
									$welcome = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=$feedid&username=$username&password=$password&To=" . $_REQUEST ['phone_no'] . "&Text=" . $_REQUEST ['msg']."jobname=CustomerSupport" ;
									//echo $welcome;
									//break;
									$resp =  callurl($welcome);
									//print_r( $resp);
									//echo "Message sent Successfully using netcore to ".$_REQUEST ['phone_no']."<br/>";
								}
							
			
							} catch (exception $e) {
							    echo $e->getMessage();
							}	

				}catch (Exception $e) {
				    echo $e->getMessage();
				}
			}
			break;
			
		case "smstemplate" :
			// # START SMS Template Get
			if (isset ( $_REQUEST ['id'] )) {

				//$global_template_id = $_REQUEST ['id'];
				//echo ($global_template_id);
				// # START SMS Template Fetch -> EmailTemplate Module
				$emailTemp = new EmailTemplate ();
				$emailTemp->retrieve ( $_REQUEST ['id'] );
				$message = '';
				if (strlen ( $emailTemp->id ) > 0) {
					
					$message = $emailTemp->body;
					$message = preg_replace ( '/\{DATE\s+(.*?)\}/e', "date('\\1')", $message );
					if (! $_REQUEST ['masssms']) {
						// # START Template Variable Replace Using Related Module Contacts or Leads
						$object_arr = array ();
						$object_arr [$_REQUEST ['moduleNm']] = $_REQUEST ["pid"];
						$message = $emailTemp::parse_template ( $message, $object_arr );
						// # END Template Variable Replace Using Related Module Contacts or Leads
					}
				}
				echo $message;
			}
			// # END SMS Template Get
			break;
		
		case "editor" :
			// # START Module License Checking
			$validate_license = '1';//OutfittersLicense::isValid ( 'SMS_SMS', $user_id, false );
			if ($validate_license != '1') {
				$error_msg = "<div style='background-color:#fff;padding:10px;text-align:center;'>Your license key is Invalid or has expired.
	<br /><br />
	Please contact FibreCRM (licensing@fibrecrm.com) to obtain a new license key or buy one now at <a href=\"http://www.fibrecrm.com/crm-plugins/sugarsms\">http://www.fibrecrm.com/crm-plugins/sugarsms</a>. If you already have a valid key, <a href=\"index.php?module=SMS_SMS&action=license\">click here to enter</a> it now.</div>";
				echo $error_msg;
				break;
			}
			// # END Module License Checking
			
			// # START Checking SMS Username and Password
			$sugar_config ['firesms_username'] = "q";
			$sugar_config ['firesms_password'] = "r";
			if (empty ( $sugar_config ['firesms_username'] ) || empty ( $sugar_config ['firesms_password'] )) {
				echo "<div style='background-color:#fff;padding:10px;text-align:center;'>You have not specified an SMS gateway for this purpose. ";
				echo "Click <a href='./index.php?module=Administration&action=FireTextSMS'>here</a> to setup your gateway account.</div>";
				break;
			}
			// # END Checking SMS Username and Password
			
			$mod_key_sing = $GLOBALS ["beanList"] [$_REQUEST ['moduleNm']];
			$mod_bean_files = $GLOBALS ["beanFiles"] [$mod_key_sing];
			
			$msg = "";
			$pid = $_REQUEST ['pid'];
			$moduleNm = $_REQUEST ['moduleNm'];
			$phoneNumber = $_REQUEST ['phoneNumber'];
			$masssms = $_REQUEST ['masssms'];
			if ($masssms) {
				$phoneNumber = '123456';
				$massHide = ' display:none';
			}
			if (! $sugar_config ['firesms_msg_length'])
				$sms_msg_length = '160';
			else
				$sms_msg_length = $sugar_config ['firesms_msg_length'];

			/*global $beanList, $beanFiles;
			$bean = $beanList [$_REQUEST ['moduleNm']];
			require_once ($beanFiles [$bean]);
			$prospectlist_obj = new $bean ();
			$prospectlist_obj->retrieve ( $_REQUEST ["pid"] );
			$to_str = "";
			$prospectlist_obj->load_relationship ( 'prospects' );
			foreach ( $prospectlist_obj->prospects->getBeans () as $contact ) {
				$phone_mobile = str_replace(" ","",(trim ( $contact->phone_mobile )));
				$phone_mobile = "91".substr($phone_mobile, -10);
				$to_str .= $phone_mobile.",";
			}
			$to_str = rtrim($to_str, ',');*/
			//$my_var = "hello";
				// # SMS Editor Open
			include_once ("custom/modules/Administration/firesmsbox.php");
			
			break;
		
		default :
			echo "";
	}
} else { // just draw the gateway settings panel
          
	// # START Only Admin User Allow for Save Settings
	if (! is_admin ( $current_user )) {
		sugar_die ( 'Admin Only' );
		break;
	}
	// # END Only Admin User Allow for Save Settings
	
	$focus_admin->retrieveSettings ();
	if (! empty ( $_POST ['restore'] )) {
		$configurator->restoreConfig ();
	}
	
	// Build the $gotopage_config array which stores all the default values used in smart template below if
	// they aren't already set
	$sms_config = array ();
	foreach ( $config_meta as $key => $value ) {
		$sms_config [$key] = $value ['default'];
	}
	
	require_once ('include/Sugar_Smarty.php');
	$sugar_smarty = new Sugar_Smarty ();
	$sugar_smarty->assign ( 'MOD', $mod_strings );
	$sugar_smarty->assign ( 'APP', $app_strings );
	$sugar_smarty->assign ( 'APP_LIST', $app_list_strings );
	$sugar_smarty->assign ( 'config', $configurator->config );
	$sugar_smarty->assign ( 'sms_config', $sms_config );
	$sugar_smarty->assign ( 'error', $configurator->errors );
	$sms_sms_webhook_url = $sugar_config ['site_url'] . '/index.php?module=SMS_SMS&entryPoint=Quick2SMSFireTexthook';
	$sugar_smarty->assign ( 'sms_sms_webhook_url', $sms_sms_webhook_url );
	$sugar_smarty->display ( 'custom/modules/Administration/smsdisplay_configurator.tpl' );
	
	require_once ("include/javascript/javascript.php");
	$javascript = new javascript ();
	$javascript->setFormName ( "ConfigureSettings" );
	
	foreach ( $config_meta as $key => $value ) {
		$type = "varchar";
		$required = TRUE;
		
		if (isset ( $value ['required'] )) {
			$required = $value ['required'];
		}
		if (isset ( $value ['type'] )) {
			$type = $value ['type'];
		}
		
		$javascript->addFieldGeneric ( $key, $type, $mod_strings ['LBL_' . strtoupper ( $key )], $required, "" );
	}
	
	echo $javascript->getScript ();
	
	print <<<ENDJS

ENDJS;
}
?>

