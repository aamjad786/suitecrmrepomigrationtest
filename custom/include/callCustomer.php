<?php
if (!defined('sugarEntry'))
define('sugarEntry', true);
require_once('include/entryPoint.php');
require_once 'CurlReq.php';
global $db;
global $sugar_config, $app_list_strings, $GLOBALS;
global $current_user;
$ozontel_api_key = getenv('SCRM_OZONTEL_API_KEY');

//&agentID="+username+"&campaignName=Inbound_912267304969&customerNumber="+phoneNumber+"&uui="+moduleNm+"&pid="+pid;
$myfile = fopen("Logs/callCustomer.log", "a");
fwrite($myfile, "\n".date('Y-m-d h:i:s'));
$agentID = strtoupper($_GET['agentID']);
fwrite($myfile, "\n Agent $agentID");

$queryToGetCloudAgent = "SELECT cloud_agent_user from agent_bucket_mapping where agent_id = '$agentID'";
$result = $db->query($queryToGetCloudAgent);
while ($row = $db->fetchByAssoc($result)) {
    $cloudAgent = $row['cloud_agent_user'];
}

fwrite($myfile, "\n Clud agent $cloudAgent");

//$agentID = "kserve_2";
$customerNumber = $_GET['customerNumber'];
$uui 			= $_GET['uui'];
$campaignName = empty($_GET['campaign'])?"Inbound_CS_912262587409":$_GET['campaign'];
$pid            = $_GET['pid'];
if(!empty($uui)){
    $moduleNm = $uui;
    if($moduleNm == "Cases"){
        $campaignName = "Outbound_Case_Specific";
    }else if($moduleNm == "SocialImpact"){
        $campaignName = "Outbound_Social";
    }else if($moduleNm == "Calls"){
        $callBean = BeanFactory::getBean('Calls',$pid);
        $callType = $callBean->calls_type_c;
        if($callType=="voice_mail"){
            $campaignName = "Outbound_Voice_Mails";
        }else{
            $campaignName = "Outbound_NPS";
        }
    }else if($moduleNm == "neo_Paylater_Open"){
        $campaignName = "Outbound_Paylater_WC";
    }else if($moduleNm == "SMAcc_SM_Account"){
        $campaignName = "Outbound_WelcomeNC";
    }

}


$response 		= "";
    if($env != 'prod'){
        echo "Campaign name is $campaignName. ";
    }
$GLOBALS['log']->debug("Request to initiate IVR call");
$GLOBALS['log']->debug("agentID : $agentID, campaignName : $campaignName, customerNumber : $customerNumber, uui : $uui, pid:$pid ");
if(!empty($cloudAgent)){
    
$cl  = new CurlReq();

    $url = "https://api1.cloudagent.in/CAServices/AgentManualDial.php?api_key=KK6c2a74f7da9381fa80451cd0b0650de5&username=neogrowth&agentID=$cloudAgent&campaignName=$campaignName&customerNumber=.$customerNumber&uui=$uui|$pid";
    fwrite($myfile, "\n Clud agent URL $url");

    $GLOBALS['log']->debug("url : $url");
    $response = $cl->curl_req($url);
    $env = getenv('SCRM_ENVIRONMENT');

} else {
    $message = "There is no cloud agent user exists for this agent. Please update the data.";
    echo $message;
    $GLOBALS['log']->debug("ERROR :: " . $message);
}

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
