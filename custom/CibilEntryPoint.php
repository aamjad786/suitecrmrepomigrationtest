<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
$date = new DateTime();
global $sugar_config;

$filename = "Logs/CibilentryPointLog.log";
$fh = fopen($filename, "a");
fwrite($fh, date('Y-m-d h:i:s')."\n");

$keyMapping = array("Triggers_UID"=>"triggers_uid","Member_Code"=>"member_code","File_name"=>"file_name","Group_Name"=>"group_name","Group_Member_Reference"=>"group_member_reference","Group_Start_Date"=>"group_start_date","Group_End_Date"=>"group_end_date","Real_Time_Delivery"=>"real_time_delivery","Alert_Report_Frequency"=>"alert_report_frequency","Trigger_Type"=>"trigger_type","Trigger_P1"=>"trigger_p1","Trigger_P2"=>"trigger_p2","Trigger_P3"=>"trigger_p3","Trigger_P4"=>"trigger_p4","Trigger_P5"=>"trigger_p5","Trigger_P6"=>"trigger_p6","Addon_Info"=>"addon_info","Account_Number"=>"as_app_id","Account_Type"=>"account_type","Ownership_Indicator"=>"ownership_indicator","Alert_Generation_Date_Time"=>"alert_generation_timestamp","Acct_Info_Account_Type"=>"acct_account_type","Acct_Info_Account_Ownership"=>"acct_account_ownership",   
"Contact_Info_Name1"=>"name_1","Contact_Info_Name2"=>"name_2","Contact_Info_Name3"=>"name_3","Contact_Info_Name4"=>"name_4","Contact_Info_Name5"=>"name_5","Contact_Info_Gender"=>"gender","Contact_Info_DOB"=>"dob","Contact_Info_Latest_Address___Address_Line_1"=>"latest_address_1","Contact_Info_Latest_Address___Address_Line_2"=>"latest_address_2","Contact_Info_Latest_Address___Address_Line_3"=>"latest_address_3","Contact_Info_Latest_Address___Address_Line_4"=>"latest_address_4","Contact_Info_Latest_Address___Address_Line_5"=>"latest_address_5","Contact_Info_Latest_Address___State_Code=>latest_state_code","Contact_Info_Latest_Address___Pin_Code"=>"latest_pin_code","Contact_Info_Latest_Address___Address_Category"=>"latest_address_category","Contact_Info_Latest_Address___Residence_Code"=>"latest_address_residence_code", 
"Contact_Info_Second_Address___Address_Line_1"=>"second_address_1",
"Contact_Info_Second_Address___Address_Line_2"=>"second_address_2",       
"Contact_Info_Second_Address___Address_Line_3"=>"second_address_3","Contact_Info_Second_Address___Address_Line_4"=>"second_address_4",
"Contact_Info_Second_Address___Address_Line_5"=>"second_address_5","Contact_Info_Second_Address___State_Code"=>"second_state_code",   
"Contact_Info_Second_Address___Pin_Code"=>"second_pin_code","Contact_Info_Second_Address___Address_Category"=>"second_address_category","Contact_Info_Second_Address___Residence_Code"=>"second_address_residence_code", 
"Contact_Info_Latest_Phone_Number"=>"latest_phone","Contact_Info_Latest_Phone_Extension"=>"latest_phone_extension","Contact_Info_Latest_Phone_Type"=>"latest_phone_type","Contact_Info_Second_Phone_Number"=>"second_phone","Contact_Info_Second_Phone_Extension"=>"second_phone_extension","Contact_Info_Second_Phone_Type"=>"second_phone_type","Contact_Info_Latest_ID_Number"=>"latest_id_no","Contact_Info_Latest_ID_Type"=>"latest_id_type","Contact_Info_Second_ID_Number"=>"second_id_no","Contact_Info_Second_ID_Type"=>"second_id_type","Email"=>"email","Enquiry_Info__Enquiry_Type"=>"enquiry_type","Enquiry_Info__Enquiry_Amount"=>"enquiry_amt");

$cols = array();
$values = array();
$paytm_passcode = getenv('SCRM_PAYTM_PASSCODE');

if($_SERVER['PHP_AUTH_USER']=='cibil' && $_SERVER['PHP_AUTH_PW'] == 'PNAEYO'){
	// $params = ($_REQUEST['params']);
	 $fp      = fopen('php://input', 'r');
    $params = json_decode(stream_get_contents($fp),true);
    // var_dump($params);
	if(!empty($params)){
		foreach($keyMapping as $k=>$v){
			if(array_key_exists($k, $params)){
				$cols[] = $v;
				$values[] = $params[$k];

			}
		}
		fwrite($fh, "\ncols values: ".print_r($cols,true));
		fwrite($fh, "\nvalues values: ".print_r($values,true));
		$query = "insert into renewal_cibil_trigger (";
		foreach($cols as $i=>$v){
			if($i>0){
				$query .= ",";
			}
			$query .= $v;
		}
		$query .= ") values (";
		foreach($values as $i=>$v){
			if($i>0){
				$query .= ",";
			}
			$query .= "'".$v."'";
		}
		$query .= ");";
		fwrite($fh, "\nquery = $query\n");
		try{
			global $db;
			$db->query($query);
			sendHttpStatusCode(200,'ok');
			echo "Record inserted";
		}catch(Exception $e){
			fwrite($fh, "\nException message = ".$e->getMessage());
			sendHttpStatusCode(500,'Internal Error');
			echo "Some error occured";
		}
	}else{
		sendHttpStatusCode(400,'Bad request');
		echo "Params not found";
	}
}else{
	sendHttpStatusCode(401,'Unauthorized');
	echo "Authentication failed";
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

// print_r($keyMapping);
