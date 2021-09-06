<?php
	array_push($job_strings, 'UpdateRenewedAppId');

	//Scheculer Starting point 
	function UpdateRenewedAppId(){
		$response = true;
		try{
			$myfile = fopen("Logs/RenewalsJob.log", "a");
			fwrite($myfile, "\n-------------UpdateRenewedAppId::Starts------------\n");
		    require_once('modules/Neo_Customers/Renewals_functions.php');
		    $last_run_date = fetchLastRunDate("function::UpdateRenewedAppId"); 
		    fwrite($myfile, "last_run_date :: " . $last_run_date);
		    $renewals = new Renewals_functions();
		    $response = $renewals->checkRenewedAppIdsFromAudit($last_run_date);
		    fwrite($myfile, "\n-------------UpdateRenewedAppId::end------------\n");
		}
		catch(Exception $e){
			fwrite($myfile, "Exception in UpdateRenewedAppId scheduler :: " . $e->getMessage());
			$response = false;
		}
		return $response;
	}
?>