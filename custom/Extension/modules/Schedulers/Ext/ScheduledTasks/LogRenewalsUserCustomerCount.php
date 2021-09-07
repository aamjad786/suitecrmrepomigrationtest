<?php 
$job_strings[] = 'LogRenewalsUserCustomerCount';
date_default_timezone_set('Asia/Kolkata');


function LogRenewalsUserCustomerCount(){
    require_once('modules/Neo_Customers/Renewals_functions.php');
    $renewals = new Renewals_functions();
    $response = $renewals->maxCustomerCount();
    return $response;
}