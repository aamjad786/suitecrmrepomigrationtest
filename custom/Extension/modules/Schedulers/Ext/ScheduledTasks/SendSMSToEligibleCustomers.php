<?php
$job_strings[] = 'SendSMSToEligibleCustomers';

function SendSMSToEligibleCustomers(){
		try{
			$myfile = fopen("Logs/RenewalsJob.log", "a");
			fwrite($myfile, "\n-------------SendSMSToEligibleCustomers::Starts------------\n");
		    require_once('modules/Neo_Customers/Renewals_functions.php');
		    $last_run_date = fetchLastRunDate("function::SendSMSToEligibleCustomers"); 
		    fwrite($myfile, "last_run_date :: " . $last_run_date);
		    $renewals = new Renewals_functions();
		    $response = $renewals->sendSMSToEligibleCustomerFromAudit($last_run_date);
		    fwrite($myfile, "\n-------------SendSMSToEligibleCustomers::end------------\n");
		}
		catch(Exception $e){
			fwrite($myfile, "Exception in SendSMSToEligibleCustomers scheduler :: " . $e->getMessage());
			$response = false;
		}
		return $response;
}