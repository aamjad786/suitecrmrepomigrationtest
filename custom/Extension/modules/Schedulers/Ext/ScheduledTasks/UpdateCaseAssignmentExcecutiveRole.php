<?php
require_once('custom/modules/Cases/Cases_functions.php');
require_once 'custom/CustomLogger/CustomLogger.php';

$job_strings[] = 'UpdateCaseAssignmentExcecutiveRole';
date_default_timezone_set('Asia/Kolkata');

function UpdateCaseAssignmentExcecutiveRole(){
    $logger = new CustomLogger('UpdateCaseAssignmentExcecutiveRole');
    $logger->log('debug', "--- START In UpdateCaseAssignmentExcecutiveRole in  ScheduledTasks---");

    $cases_functions = new Cases_functions();
    $response = false;
    $response = $cases_functions->updateCaseAssignmentExcecutiveRole();
    if(!$response){
        $logger->log('debug', "PROD CRM UpdateCaseAssignmentExcecutiveRole Scheduler failed sending mail to DEVs");
        sendNotificationToDevs("PROD CRM Scheduler failed", "UpdateCaseAssignmentExcecutiveRole");
    }
    $logger->log('debug', "--- END In UpdateCaseAssignmentExcecutiveRole in ScheduledTasks---");
    return $response;
}

function sendNotificationToDevs($subject = "Error in PROD", $body = "Error in PROD"){
    global $sugar_config;
    $env = getenv('SCRM_ENVIRONMENT');
    if(true or in_array($env,array('prod'))){
		require_once('custom/include/SendEmail.php');
		$email = new SendEmail();    
		$email->send_email_to_user( $subject, 
                                $body, 
                                $sugar_config['DEVs_emails'], // to
                                array(), // cc
                                null,
                                null,
                                1
                              );
    }
}

?>