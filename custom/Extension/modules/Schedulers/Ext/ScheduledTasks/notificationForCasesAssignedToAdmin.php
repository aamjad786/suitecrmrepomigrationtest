<?php 
require_once 'custom/CustomLogger/CustomLogger.php';
require_once('custom/modules/Cases/Cases_functions.php');

$job_strings[] = 'notificationForCasesAssignedToAdmin';
date_default_timezone_set('Asia/Kolkata');


function notificationForCasesAssignedToAdmin(){
    $logger = new CustomLogger('CasesAssignedToAdmin');
    $logger->log('debug', "--- START In notificationForCasesAssignedToAdmin in ScheduledTasks---");

    $cases_functions = new Cases_functions();
    $response = false;
    $response = $cases_functions->notificationForCasesAssignedToAdmin();
    $logger->log('debug', "--- END notificationForCasesAssignedToAdmin in ScheduledTasks---");
    return $response;
}
?>