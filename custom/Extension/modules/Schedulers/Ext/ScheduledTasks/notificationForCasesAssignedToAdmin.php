<?php 
$job_strings[] = 'notificationForCasesAssignedToAdmin';
date_default_timezone_set('Asia/Kolkata');


function notificationForCasesAssignedToAdmin(){
    require_once('custom/modules/Cases/Cases_functions.php');
    $cases_functions = new Cases_functions();
    $response = false;
    $response = $cases_functions->notificationForCasesAssignedToAdmin();
    return $response;
}
?>