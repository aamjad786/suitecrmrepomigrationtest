<?php 
$job_strings[] = 'uploadOzontelAutoScheduleCalls';
date_default_timezone_set('Asia/Kolkata');


function uploadOzontelAutoScheduleCalls(){
    require_once('custom/modules/Cases/Cases_functions.php');
    $cases_functions = new Cases_functions();
    $response = false;
    $response = $cases_functions->uploadOzontelAutoScheduleCalls();
    return $response;
}
?>