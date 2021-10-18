<?php 
$job_strings[] = 'sendCallBackRemaindersDump';
date_default_timezone_set('Asia/Kolkata');

/**
 *	Called by scheduler to send remainder mail with full list of web call back dumbs for next day
 */
function sendCallBackRemaindersDump(){
    require_once('custom/modules/Cases/CallBackFlow.php');
    $call_back_flow = new CallBackFlow();
    $response = false;
    $response = $call_back_flow->sendCallBackRemaindersDump();
    return $response;
}
?>