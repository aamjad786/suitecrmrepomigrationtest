<?php

if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once('custom/include/SendEmail.php');;
require_once('modules/EmailTemplates/EmailTemplate.php');

class Neo_CustomersViewupfront_deduction_update extends SugarView {

	private $chartV;

    function __construct(){    
        parent::SugarView();
    }

    function display() {
		// $GLOBALS['log']->debug("check upfront_deduction:: " . $_REQUEST['upfront_deduction_list']);
		// $upfront_deduction_list = $_REQUEST['upfront_deduction_list'];
		$id = $_REQUEST['id']; 
		$apps = $_REQUEST['apps']; 
		// $customer_id = '7267';
		$log = fopen("Logs/RenewalsJob.log", "a");
		fwrite($log, "\nid=$id\n");
		if(!empty($id)){
			echo "<div>";
			$bean = BeanFactory::getBean('Neo_Customers',$id);
			if(!empty($bean)){
				if(empty($apps))
					$upfront_deduction_app_list = $bean->upfront_deduction_app_list;
				else
					$upfront_deduction_app_list = $apps;
				if(empty($upfront_deduction_app_list)){
					print_r("No upfront deduction apps found for the customer id");
				}else{
					$apps_list = explode(',', $upfront_deduction_app_list);
					fwrite($log,"\nApps = $upfront_deduction_app_list");
					foreach($apps_list as $app){
						// http://182.72.61.150:85/api/Renewal/GetRenewalQueueForCRM?ApplicationId=1001279
						require_once('custom/include/CurlReq.php');
						$CurlReq = new CurlReq();
						$headers  = [
			                    'Content-Type: application/json'
			                ];
			            $url = getenv('SCRM_AS_URL')."/api/Renewal/GetRenewalQueueForCRM?ApplicationId=$app";
			            fwrite($log,"url = $url");
						$output = $CurlReq->curl_req($url);
						fwrite($log,"output = ".print_r($output,true));
						// var_dump($output);
						echo "<br/><hr>	Response received from AS:<br/>";
						// print_r($output);
						require_once('modules/Neo_Customers/Renewals_functions.php');
	                    $renewals = new Renewals_functions();
	                    // $renewals->printJson(($output));
	                    $arr = json_decode($output);
	                    $arr1 = $arr[0];
	                    foreach($arr1 as $k=>$v){
	                    	if($k=='Status'){
	                    		switch($v){
	                    			case 'A':
	                    				$v = 'Approved';
	                    				break;
	                    			case 'P':
	                    				$v= 'Pending';
	                    				break;
	                    			case 'R':
	                    				$v = 'Reject';
	                    				break;
	                    			case 'RA':
	                    				$v='Re-Appeal';
	                    				break;
	                    			default:
	                    				break;
	                    		}//end switch($v){
	                    	}//end if($k=='Status'){
				            echo "<b>$k:</b> <i>$v</i><br/>";
				        }//end foreach($arr1 as $k=>$v){
					}//end foreach($apps_list as $app){
				}//end if(empty($upfront_deduction_app_list)){
			}//end if(!empty($bean)){
			else{
				fwrite($log, "\nData not found for the customer id\n");
				$GLOBALS['log']->error("Data not found for the customer id");
				echo "Data not found for the customer id";
			}//end if(!empty($bean)){ else
		}//if(!empty($id)){ else
		else{
			$GLOBALS['log']->error("Empty customer_id");
			echo "Unable to update data. Please contact IT team";
		}//if(!empty($id)){ else
		echo "</div>";
		fclose($log);
	}

}

?>