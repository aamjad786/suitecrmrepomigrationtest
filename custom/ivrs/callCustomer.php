<?php
//if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once '../../CurlReq.php';
global $db;
global $sugar_config, $app_list_strings, $GLOBALS;
global $current_user;


//&agentID="+username+"&campaignName=Inbound_912267304969&customerNumber="+phoneNumber+"&uui="+moduleNm+"&pid="+pid;
$agentID 		= $_GET['agentID'];
//$agentID = "kserve_2";
$customerNumber = $_GET['customerNumber'];
$uui 			= $_GET['uui'];
$pid 			= $_GET['pid'];
$response 		= "";

//$GLOBALS['log']->debug("Request to initiate IVR call");
//$GLOBALS['log']->debug("agentID : $agentID, campaignName : $campaignName, customerNumber : $customerNumber, uui : $uui, pid:$pid ");
$cl  = new CurlReq();
$url = "https://api1.cloudagent.in/CAServices/AgentManualDial.php?api_key=KK6c2a74f7da9381fa80451cd0b0650de5&username=neogrowth&agentID=".$agentID."&campaignName=Inbound_CS_912262587409&customerNumber=".$customerNumber."&uui=".$uui."|".$pid;
//$GLOBALS['log']->debug("url : $url");
$response = $cl->curl_req($url);
//$GLOBALS['log']->debug("Response :: " . $response);

if(empty($response)){
	//$GLOBALS['log']->fatal("api call failed to initiate IVR, Response is empty");
	//$GLOBALS['log']->fatal("agentID : $agentID, campaignName : $campaignName,	 customerNumber : $customerNumber, uui : $uui, pid:$pid ");
}
if(empty($agentID) || empty($campaignName) || empty($customerNumber) || empty($uui) || empty($pid)){
	//$GLOBALS['log']->fatal("Some data are missing to make IVR calls");
	//$GLOBALS['log']->fatal("agentID : $agentID, campaignName : $campaignName,customerNumber : $customerNumber, uui : $uui, pid:$pid ");
}
echo $response;
?>