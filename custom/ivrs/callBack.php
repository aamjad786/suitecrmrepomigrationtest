<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
require_once '../../config.php';
require_once '../../config_override.php';

$fp = fopen('../../Logs/Request.log', 'a');
fwrite($fp, "\n\n--------------callBack----------");
fwrite($fp, "\n".date('Y-m-d H:i:s'));


//$_REQUEST['data'] = '{"HangupBy": "AgentHangup", "Location": "", "Status": "NotAnswered", "CallerConfAudioFile": "", "TransferType": "No Transfers", "AudioFile": "opop.mp3", "AgentName": "nikhil", "EndTime": "2017-05-25 20:32:41", "Did": "912267304969", "DialStatus": "answered", "DialedNumber": "9916343619", "Apikey": "KK6c2a74f7da9381fa80451cd0b0650de5", "FallBackRule": "AgentDial", "TransferredTo": "", "CallerID": "8073268616", "CustomerStatus": "answered", "AgentPhoneNumber": "9916343619", "monitorUCID": "650999572453711", "PhoneName": "nikhil", "CampaignStatus": "ONLINE", "CampaignName": "Inbound_CS_912262587409", "AgentUniqueID": "49606", "CallDuration": "00:00:18", "Type": "Manual", "UserName": "neogrowth", "StartTime": "2020-02-03 20:32:23", "Skill": "None", "UUI": "Manual Dial", "Comments": "", "Duration": "00:00:08", "AgentStatus": "Answered", "TimeToAnswer": "00:00:10", "AgentID": "ng377", "ConfDuration": "00:00:00", "Disposition": ""}';


fwrite($fp, "\n\n".print_r($_REQUEST, TRUE));

$databasehost = $sugar_config['dbconfig']['db_host_name'];
$databasename = $sugar_config['dbconfig']['db_name'];
$databasetable = "call_details";
$databaseusername = $sugar_config['dbconfig']['db_user_name'];
$databasepassword = $sugar_config['dbconfig']['db_password'];

mysql_connect($databasehost, $databaseusername, $databasepassword);
mysql_select_db($databasename) or die(mysql_error());


$arr = json_decode($_REQUEST['data'], true);

$uui = $arr['UUI'];
$str_arr = explode('|',$uui);
if (count($str_arr)>1){
	$parent_type = $str_arr[0];
	$parent_id = $str_arr[1];
}
if($parent_type == 'AS'){
	fwrite($fp, "\nIts AS Triggered call ");

	if(empty($arr['AudioFile'] )){
		fwrite($fp,"\nNot storing as no recording available");
		sendHttpStatusCode('200', 'OK');
		echo "Not storing as no recording available";
		return;
	}
	if( empty($arr['AgentStatus'])){
		fwrite($fp,"\nNot storing as no AgentStatus available");
		sendHttpStatusCode('200', 'OK');
		echo "Not storing as agent status not available";
		return;
	}

}
else
{
	# Multiple Call details duplicate rows not accepting logic -->Start <---
if($arr['CampaignName']=='Inbound_CS_912262587409' && $arr['Status']=='NotAnswered')
{
	$date=date('Y-m-d');
	$query = "select count(*) as count from call_details where CallerID = '".$arr['CallerID']."' and StartTime like '%$date%'";

	$results = mysql_query($query);

	
	if (mysql_errno()) { 
		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$query\n<br>"; 
		echo $error;
		fwrite($fp, "\nquery result error:".$error);
		sendHttpStatusCode('500', 'Internal Server Error');
	  }
	  else{
		$row = mysql_fetch_assoc($results);
		if($row['count']>0){
			sendHttpStatusCode('200', 'OK');
			echo "Already added this customer call details for today";
			return;
		}

	  }
}
#---> End <---
}

$query = "insert into call_details (";
$values = "";
foreach($arr as $key=>$value){
	$query .= $key .',';
	$values  .= "'$value',";
}
if(!empty($arr))
	$query = substr($query, 0, -1);
$query .= ') values (';
$query .= $values;

if(!empty($arr))
	$query = substr($query, 0, -1);
$query .= ')';
fwrite($fp,"\nCall Details Query=$query");

$result = mysql_query($query);

if (mysql_errno()) { 
  $error = "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$query\n<br>"; 
  echo $error;
  fwrite($fp, "\nquery result error:".$error);
  sendHttpStatusCode('500', 'Internal Server Error');
}else{
	fwrite($fp,"\nCall added successfully");
	sendHttpStatusCode('200', 'OK');
	echo "Call Added successfully";
}
fwrite($fp, "\n\n--------------callBack end----------");

function sendHttpStatusCode($httpStatusCode, $httpStatusMsg) {
    $phpSapiName    = substr(php_sapi_name(), 0, 3);
    if ($phpSapiName == 'cgi' || $phpSapiName == 'fpm') {
        header('Status: '.$httpStatusCode.' '.$httpStatusMsg);
    } else {
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
        header($protocol.' '.$httpStatusCode.' '.$httpStatusMsg);
    }
}