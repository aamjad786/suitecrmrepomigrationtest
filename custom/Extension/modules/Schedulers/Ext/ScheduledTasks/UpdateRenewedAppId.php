<?php
require_once 'custom/CustomLogger/CustomLogger.php';
array_push($job_strings, 'UpdateRenewedAppId');
global $logger;
$logger= new CustomLogger('updaterenewedAppid');
	//Scheculer Starting point 
	function UpdateRenewedAppId(){
		global $logger;
		$response = true;
		try{
			$logger->log('debug', '<======================UpdateRenewedAppId::Starts==========================>');
		    require_once('modules/Neo_Customers/Renewals_functions.php');
		    $last_run_date = fetchLastRunDate("function::UpdateRenewedAppId"); 
			$logger->log('debug', "Last Run Date :: $last_run_date");
		    $renewals = new Renewals_functions();
		    $response = $renewals->checkRenewedAppIdsFromAudit($last_run_date);
		    $logger->log('debug', '<======================UpdateRenewedAppId::end==========================>');
		}
		catch(Exception $e){
			fwrite($myfile, "Exception in UpdateRenewedAppId scheduler :: " . $e->getMessage());
			$response = false;
		}
		return $response;
	}
?>