<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
$date = new DateTime();
global $sugar_config;

$filename = "Logs/entryPointLog.log";
$fh = fopen($filename, "a");
fwrite($fh, date('Y-m-d h:i:s')."\n");
fwrite($fh, print_r($_REQUEST, true)."\n");

if(array_key_exists('paytm',$_REQUEST)) {
	#Outdated and no longer used
 	$paytm_passcode = getenv('SCRM_PAYTM_PASSCODE');
 	if($_SERVER['PHP_AUTH_USER']=='payTM' && $_SERVER['PHP_AUTH_PW'] == $paytm_passcode){
	 	$databasehost = $sugar_config['dbconfig']['db_host_name'];
		$databasename = $sugar_config['dbconfig']['db_name'];
		$databasetable = "OBD Leads";
		$databaseusername = $sugar_config['dbconfig']['db_user_name'];
		$databasepassword = $sugar_config['dbconfig']['db_password'];

		mysql_connect($databasehost, $databaseusername, $databasepassword);
		mysql_select_db($databasename) or die(mysql_error());

		$phone = getVar($_REQUEST['phone']);
		$start_time = getVar($_REQUEST['start_time']);
		$end_time = getVar($_REQUEST['end_time']);
		$status = getVar($_REQUEST['status']);
		$keypress = getVar($_REQUEST['keypress']);
		if (intval($keypress)==1){
			createLead($phone,"OBD Campaign");
		}
		$show_result = "insert into paytm_leads (phone,start_time,end_time,status,keypress,created_at,updated_at) values ('$phone','$start_time',
			'$end_time','$status','$keypress',NOW(),NOW())";
		// die($show_result);
		$qry_result = mysql_query($show_result) or die(mysql_error());

		// echo $qry_result;
		if ($qry_result){

			sendHttpStatusCode('200', 'OK');
			echo "Success";
		}else{
			sendHttpStatusCode('500', 'Internal Server Error');
			echo "Failed";
		}
	}else{
	 	sendHttpStatusCode('401', 'Unauthorized');
	 	echo "wrong passcode";
	}
}else if(array_key_exists('fetchCamDetails',$_REQUEST)) {
	// echo "here";
	if(!empty($_REQUEST['opportunity_id'])){
		$op_id = $_REQUEST['opportunity_id'];
		// echo $op_id;
		$op = new Opportunity();
		$op->retrieve($op_id);
		$user = new User();
		$user->retrieve($op->assigned_user_id);
		$msg = array();
		$msg['name'] = $user->first_name.' '.$user->last_name;
		$msg['phone'] = $user->phone_mobile;
		$msg['email'] = $user->email1;
		echo json_encode($msg);
		// echo $op->name;
	}
}
// PRI integration
else if(array_key_exists('pri',$_REQUEST)) { 
	$cid = $_REQUEST['cid'];
	$did = $_REQUEST['did'];
	if (!empty($cid) && !empty($did)) {

		#Sticky feature no longer required so just returning response of no agent case
		$response['CallerNumber'] = $cid;
		$response['CalledNumber'] = $did;
		$response['AgentID'] = null;
		$response['SkillName'] = null;
		$response['Process'] = null;
		echo json_encode($response);
		fwrite($fh, "PRI: Agent not found in list. CID:$cid DID: $did\n");

	}else{
		fwrite($fh, "PRI: Empty CID or DID.\n");
	}
}
//to fetch customer info from CRM during ivrs call
else if(array_key_exists('fetch',$_REQUEST)) { 
	// $uui =  $_REQUEST['uui'];
	// $cloud_agent_passcode = getenv('SCRM_CLOUD_AGENT_PASSCODE');
 	// if($_SERVER['PHP_AUTH_USER']=='cloud_agent' && $_SERVER['PHP_AUTH_PW'] == $cloud_agent_passcode){
		if($_REQUEST['type']=='Inbound'){
			$input = $_REQUEST['uui'];
			if(!empty($input)){
				fwrite($fh, "Fetch: Received UUI: $input.\n");
				if (strrpos($input, ",") > 0) {
					$language = explode(",", $input)[0];
					$input = explode(",", $input)[1];
					echo "<h4>Language:  <i>$language</i></h4>";
				}
				$app_id = 0;
				$mobile = 0;
				$user_input=0;
				if(!ctype_digit($input)){
					// echo "Fetch: Input is not a valid number\n";
					echo "<h3> Input is not a valid number.</h3>";
					fwrite($fh, "Fetch: Input is not a valid number.\n");
				}
				else{
				if(strlen($input)<9){
		 			$user_input=1;//app_id
		 			$app_id = $input;
		 		}else{
		 			if (strlen($input) == 12) {
		 				$input = substr($input, -10);
		 			}
		 			$mobile = $input;
		 			$user_input=2;//phone_no.
		 		}
				$app_host = getenv('SCRM_APP_HOST');
				$as_api_base_url = getenv('SCRM_AS_API_BASE_URL');
				// fwrite($fh, "Fetch: url  is $app_id.\n");
			    if (strpos($app_host, 'crm') === false) 
			        $app_host = 'crm.advancesuite.in';
			    if($user_input==2){
			    	$mobile1 = substr($mobile, -10);
					$url = $as_api_base_url."/get_applications_by_phone?mobile=$mobile1";
					fwrite($fh, "Fetch: url for query is  $url.\n");
					$response = curl_req($url);
					$app_id ="";
					fwrite($fh, "Fetch: Response is  ".print_r($response,true)."\n");
					if($response){
						$json_response = json_decode($response);
						if(!empty($json_response) && count($json_response)>0){
							// $json_response = rsort($json_response);
							$app_id = $json_response[0];
							// if($app)
						}
						// var_dump($json_response);
						// die();
					}
				}
				fwrite($fh, "Fetch: Application id got is $app_id.\n");
				if(!empty($app_id)){
					$url = $as_api_base_url."/get_application_basic_details?ApplicationID=$app_id";
					$response = curl_req($url);
					if(!empty($response)){
						$json_response = json_decode($response);
						if(!empty($json_response) && count($json_response)>0)
							$json_response = $json_response[0];
						// var_dump($json_response[0]);
						if(!empty($json_response)){
							$phone_no = $json_response->{'Contact Number'};
							$email_id = $json_response->{'Contact Email ID'};
							$name = $json_response->{'Contact Person Name'};
							$company = $json_response->{'Company Name'};
							$industry = $json_response->{'Industry'};
							$address = $json_response->{'BusinessAddress'};
							echo "<h3>Name:  <i>$name</i></h3>";
							echo "<h3>Email:  <i>$email_id</i></h3>";
							echo "<h3>Phone:  <i>$phone_no</i></h3>";
							echo "<h3>Company:  <i>$company</i></h3>";
							echo "<h3>Industry:  <i>$industry</i></h3>";
							echo "<h3>Address:  <i>$address</i></h3>";
							$link = getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=customer_application_profile&applicationID=".$app_id;
							echo "<p><a target='_blank' href=$link>Click here</a> to view Customer Profile</p>";
							fwrite($fh, "Fetch: Displaying response ".print_r($json_response,true)."\n");
				 		}else{
				 			echo "<h3> No information found in CRM for app id $app_id.</h3>";
				 			fwrite($fh, "Fetch: No information found in CRM for app id $app_id.\n");
				 		}
				 	}
				 	$bean = BeanFactory::getBean('Cases');
				 	if ($mobile != 0) {
						$query = "cases.deleted=0 and cases_cstm.merchant_contact_number_c='$mobile'";	
				 	}else{
						$query = "cases.deleted=0 and cases_cstm.merchant_app_id_c='$app_id'";	
				 	}
					$items = $bean->get_full_list('',$query);
					// var_dump($items);
					// echo $items[0]->case_number;
					$count=0;   
					if ($items){
				        foreach($items as $key=>$item){
				        	// var_dump($item);
				        	$key +=1;
				        	echo "$key.  <b>Case: </b><a href='https://$app_host/SuiteCRM/index.php?module=Cases&offset=1&stamp=1496743294080648400&return_module=Cases&action=DetailView&record=$item->id'>$item->name</a><br/>";
				        }
					}
				}else{
				 	echo "<h3> Application id got is empty.</h3>";
				 	fwrite($fh, "Fetch: Application id got is empty.\n");
				}
				}
			}else{
				echo "<h3> UUI paramteter not received.</h3>";
				fwrite($fh, "Fetch: UUI paramteter not received.\n");
			}
		}
	// }
}

//IVRS inbound call
else if(array_key_exists('ca',$_REQUEST)) {  
 	$ca_passcode = "Basic Q0E6Q0EwUEEkUw=="; //getenv('SCRM_CA_PASSCODE');
 	$authorization = $_REQUEST['Authorization'];
 	// if($_SERVER['PHP_AUTH_USER']=='CA' && $_SERVER['PHP_AUTH_PW'] == $ca_passcode){
 	if ($authorization == $ca_passcode) {

 		$databasehost = $sugar_config['dbconfig']['db_host_name'];
		$databasename = $sugar_config['dbconfig']['db_name'];
		$databaseusername = $sugar_config['dbconfig']['db_user_name'];
		$databasepassword = $sugar_config['dbconfig']['db_password'];
		
		mysql_connect($databasehost, $databaseusername, $databasepassword);
		mysql_select_db($databasename) or die(mysql_error());
		
 		global $db;
 		$user_id = getVar($_REQUEST['user_id_number']);
 		$request_code = getVar($_REQUEST['request_code']);
 		$request_code = floatval(($request_code));
 		$app_id = 0;
		
 		if(strlen($user_id)<9){
 			$user_input=1;//app_id
 			$app_id = $user_id;
 		}else{
 			$user_input=2;//phone_no.
 		}

 		//Dummy code. Need to populate these values by calling ng_as apis
 		$phone_no = '0';
 		$email_id = '';	
 		$loan_balance = 0;
 		$if_verified = false;

 		$msg = array();
 		$msg['if_verified'] = $if_verified;
		$msg['email_id'] = $email_id;
		$msg['request_status'] = '1';
		$msg['loan_balance'] = $loan_balance;
		// var_dump($msg);
		
 		$app_host = getenv('SCRM_APP_HOST');
 		$as_api_base_url = getenv('SCRM_AS_API_BASE_URL');
		$as_api_base_url ='https://app.advancesuite.in:3003';
		$adr_api_base_url = getenv('SCRM_ADR_API_BASE_URL');
        if (strpos($app_host, 'crm') === false) 
            $app_host = 'crm.advancesuite.in';
 		if ($request_code == 0){
 			if($user_input==2){
 				//mobile=9886041159

				if(strlen($user_id)>10){
					$user_id = substr($user_id, -10);
				}
 				$url = $as_api_base_url."/get_applications_by_phone?mobile=$user_id";
				$response = curl_req($url);
		
 				if($response){
 					$json_response = json_decode($response);
 					if(!empty($json_response) && count($json_response)>0){
						$app_id = $json_response[0];
					} 
 					// var_dump($json_response);
 					// die();
 				} else {
					$user_id = getVar($_REQUEST['user_id_number']);
					$url = $as_api_base_url."/get_applications_by_phone?mobile=$user_id";
					 $response = curl_req($url);
					 if($response){
						$json_response = json_decode($response);
						if(!empty($json_response) && count($json_response)>0){
						   $app_id = $json_response[0];
						}
					}
				}
 			}
 			$msg['account'] = $app_id;
 			//app_id=1000592
			$url = $as_api_base_url."/get_application_basic_details?ApplicationID=$app_id";
			// echo $url;
			$response = curl_req($url);
			// var_dump($response);
			if(!empty($response) && !empty(json_decode($response))){
				$json_response = json_decode($response);
				$json_response = $json_response[0];
				if(!empty($json_response)){
					$phone_no = $json_response->{'Contact Number'};
					$email_id = $json_response->{'Contact Email ID'};
					$if_verified = true;
					$msg['if_verified'] = $if_verified;
		 			$msg['email_id'] = $email_id;
		 			$msg['request_status'] = '1';
		 		
		 		}
			}
			$url = $adr_api_base_url."/applications/$app_id/get_loan_account_details";

			$response = curl_req($url);

			if(!empty($response) && !empty(json_decode($response)) && trim($response) != null && trim($response) != 'null'){
				$json_response = json_decode($response);
				// $loan_balance = $json_response->remianing_amount;
				$loan_balance = property_exists($json_response, 'remianing_amount')?$json_response->remianing_amount:0;
				$msg['loan_balance'] = $loan_balance;
				$if_verified = true;
				$msg['if_verified'] = $if_verified;
			}
 				// die(var_dump($msg));
 			
	 			//Merchant verification using account number or phone number
 			
	 		try{
	 			if($if_verified){
		 			$sql_query = "insert into ivr_requests (request_id, request_code, app_id, phone_no, email_id, loan_balance, user_input, date_entered)
		 							values (UUID(), '$request_code', '$app_id', '$phone_no', '$email_id', '$loan_balance', '$user_input', NOW())";
		 			$result = mysql_query($sql_query) or die(mysql_error());
		 			if ($result){
		 				$msg['request_status'] = '1';
		 				sendHttpStatusCode('200', 'OK');
		 				// echo "Success";
		 			}
		 			else{
		 				$msg['request_status'] = '0';
						sendHttpStatusCode('500', 'Internal Server Error');
						// echo "Failed";
					}
				}else{
					// $msg['if_verified'] = 'false';
					$msg['request_status'] = '1';
		 			sendHttpStatusCode('200', 'User input not valid');
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}else if ($request_code >= 2.1 && $request_code <= 2.4){
			/*Statement Requests codes
				1: Merchant Statement
  				2: Interest Certificates 
				3: Loan Repayment schedule
				4: Sanction letter
				5: No dues Certificate
			*/
			/*
			- We sending as Below . Could you pls check at your backend once & let us know?
			loan balance  : 1 
			loan statement : 2.1
			interest certificate : 2.2
			Sanction letter : 2.3
			repayment schedule : 2.4
			*/
			
			if($request_code==2.1){
				$request_code=1;
			}else if($request_code==2.2){
				$request_code=2;
			}else if($request_code==2.3){
				$request_code=4;	//These are intentionally interchanged
			}else if($request_code==2.4){
				$request_code=3;
			}

			$field = '';
			if($user_input==1){
				$field = 'app_id';
			}else{
				$field = 'phone_no';
			}
			
			$msg = array();
			// die(date('Y-m-d'));
			$date_today = date('Y-m-d');
			$sql_query = "select * from ivr_requests where $field='$user_id' and date(date_entered)='$date_today' and request_code='0' order by date_entered desc limit 1";
			// die($sql_query);
			$result = mysql_query($sql_query) or die(mysql_error());
			 while($row = mysql_fetch_array($result)) {
			 	$app_id =  $row['app_id'];
			 	$phone_no = $row['phone_no'];
			 	$email_id = $row['email_id'];
			 	$loan_balance = $row['loan_balance'];
			 	// echo $email_id;

			 	if($request_code == 4){
					$c = new aCase();
					$c->description = 'Customer requested for sanction letter through IVRS';
					// $c->assigned_user_id = $userId;
					$c->name = 'Customer requested for sanction letter through IVRS';
					$c->status = 'New';
					$c->priority = 'P1';
					$c->merchant_app_id_c = $app_id;
					$c->case_source_c='merchant';
					$c->merchant_email_id_c = $email_id;
					$c->merchant_contact_number_c = $phone_no;
					$c->save();
				}
			
	 			$sql_query = "insert into ivr_requests (request_id, request_code, app_id, phone_no, email_id, loan_balance, user_input, date_entered)
	 							values (UUID(), '$request_code', '$app_id', '$phone_no', '$email_id', '$loan_balance', '$user_input', NOW())";
	 			$result = mysql_query($sql_query) or die(mysql_error());
	 			// var_dump(expression)
	 			if (!empty($result)){
	 				// var_dump($result);
	 				$msg['request_status'] = '1';
	 				sendHttpStatusCode('200', 'OK');
	 				// echo "Success";
	 			}
	 			else{
	 				$msg['request_status'] = '0';
					sendHttpStatusCode('500', 'Internal Server Error');
					// echo "Failed";
				}
			}
  		}else{
  			// echo "Bad Request ID or user not verified";
  			$msg['description'] = "Bad Request ID or user not verified";
  		}

 		echo json_encode($msg);
  	}else{
 		sendHttpStatusCode('401', 'Unauthorized');
	 	echo "wrong passcode";
 	}
}else{
	$Missed_calls = new net_missed_calls();
	$Missed_calls->name = getVar($_REQUEST['msisdn']);
	$Missed_calls->circle = getVar($_REQUEST['circle']);
	$Missed_calls->operator = getVar($_REQUEST['operator']);
	$Missed_calls->call_received_at = getVar($_REQUEST['time']);
	
	$user_mobile_number = getVar($_REQUEST['msisdn']);
	if(!empty($user_mobile_number)){
		if (array_key_exists('receiving_number', $_REQUEST)) {
		    $receiving_number = trim(urldecode($_REQUEST['receiving_number']));
		    //Lead Creation for Missed Calls
		    if ($receiving_number == '9152007511') {
		    	// echo "here";die();
		    	require_once('modules/Neo_Customers/Renewals_functions.php');
		        $renewals = new Renewals_functions();
		    	$renewals->createHotLeadPhonebased($user_mobile_number);
		    }else{
				createLead($user_mobile_number,"Missed Calls");
		    }
		} else {
			$receiving_number = "9222272881";//trim(urldecode($_REQUEST['receiving_number']));
			echo "sending thank you message to ".$user_mobile_number."<br>";
			$message = "Thank you for enquiring for Business Loan with NeoGrowth. Request you to please fill the details in link https://bit.ly/312cYTq to apply & proceed further T&C*";
			require_once('SendSMS.php');    
		    $sms = new SendSMS();
		    $sms->send_sms_to_user($tag_name="Cust_CRM_14",$user_mobile_number, $message, null, 'NetCoreEntryPoint');
		    //Lead Creation for Missed Calls
			createLead($user_mobile_number,"missed_calls_sms");
	 	
		}
		$Missed_calls->receiving_number = $receiving_number;
		$Missed_calls->save();
	}
}


function curl_req($url, $headers=""){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	if(!empty($headers)){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
function getVar($var){
	if(!empty($var))
		return trim(urldecode($var));
	return 0;
}

function createLead($phone,$source){
	$lead_id = checkDuplicateLead($source, $phone);
	if(!empty($lead_id)){
		return "Lead already exist with similar details  id = '$lead_id'";
	}
	$user_id = "";
	$city  = "";
	$phone = substr($phone, -10);
	$bean = BeanFactory::getBean('Prospects');
    $query = "prospects.deleted=0 and prospects.phone_mobile = '$phone'";
    $item = "";

	if ($bean) {
        $items = $bean->get_full_list('',$query);
        if(!empty($items)){
            $item=$items[0];
            $item->load_relationship('prospects_leads_1');
            
            $city = $item->primary_address_city;
            
        }
    }

	$lead = new Lead();
	$lead->last_name = $phone;
	$lead->phone_mobile = $phone;
	$lead->assigned_user_id = $user_id;
	$lead->lead_source = $source;
	$lead->primary_address_city = $city;
	$lead->save();
	$item->prospects_leads_1->add($lead->id);
   	
   	$myfile = fopen("Logs/NetCoreEntryPointLog.log", "a");
	fwrite($myfile, "\nphone no : $phone, source : $source");
	fwrite($myfile, "\nFrom Bean phone no : $lead->phone_mobile, source : $lead->lead_source");


	echo "<br/>lead saved ".$lead->id;
	echo "<br/>Lead linked to prospect ".$prospect->id;
		
	
 }
 

function checkDuplicateLead($lead_source, $mobile,$date_entered=null) {
    global $db;
    if(empty($date_entered))
        $date_entered = date("Y-m-d");

    $query  = "select id from leads where deleted = 0 and phone_mobile = '$mobile' and date_entered> CURDATE() - INTERVAL 30 DAY order by date_entered desc limit 1";
    $lead_id = false;
    $result = $db->query($query);
    $row    = $db->fetchByAssoc($result);
    $lead_id = $row['id'];

    return $lead_id;
}

function getOppID($lead_id) {
    global $db;
    $query  = "select opportunity_id as opp_id from leads where deleted = 0 and id = '$lead_id'";
    $result = $db->query($query);
    $row    = $db->fetchByAssoc($result);
    $opp_id = $row['opp_id'];

    return $opp_id;
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
if(!empty($fh)){
	fclose($fh);
}

