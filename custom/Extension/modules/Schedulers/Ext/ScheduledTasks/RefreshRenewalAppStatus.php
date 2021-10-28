<?php
require_once 'custom/CustomLogger/CustomLogger.php';
global $logger;
array_push($job_strings, 'RefreshRenewalAppStatus');
date_default_timezone_set('Asia/Kolkata');
$logger= new CustomLogger('RenewalJob');
function getASRejectionReasons($rejections,$app_id){
	// $rejections = $sp->RejectionIds;
	$logger= new CustomLogger('RenewalJob');
	$logger->log('debug', "----------getASRejectionReasons--------------");
	if(empty($rejections))return "";
	
	require_once 'custom/include/CurlReq.php';
    $cl = new CurlReq();
    $logger->log('debug', "\nRejections: $rejections");
    $rejection_ids = explode(',',$rejections);
    $rejection_reasons = array();
	$reason = "";
    $logger->log('debug', "\nRejection IDs: ".print_r($rejection_ids,true));
    if (!empty($rejection_ids)){
        foreach($rejection_ids as $reject_id){
        	$as_api_url = getenv('SCRM_AS_API_BASE_URL')."/applications/".$app_id."/get_rejection_reason?rejection_id=".$reject_id;
        	// var_dump($as_api_url);
        	$rejection_reason = $cl->curl_req($as_api_url,'get');
        	if(!empty($rejection_reason)) {
		        $rejection_array = json_decode($rejection_reason);
		        $reason .= $rejection_array->RejectionReason.", ";
		        $logger->log($myfile, "\nRejection Reason: $reason");
		    }
     	}
    }
    return $reason;
}
function getASAppStageDetails($app_id){
	$logger= new CustomLogger('RenewalJob');
	$logger->log('debug',"------------getASAppStageDetails--------------");
	require_once 'custom/include/CurlReq.php';
    $cl = new CurlReq();
	$as_api_url = getenv('SCRM_AS_API_BASE_URL')."/external_interfaces/get_application_status?application_id=".$app_id;
    // echo $as_api_url;
    $logger->log('debug',"\nAPI URL is $as_api_url");
    $as_api_response = json_decode($cl->curl_req($as_api_url,'get'));
    // var_dump($as_api_url);
    $code = 205;
    $stage = "Open";
	$logger->log('debug', "\n\nResponse for $app_id: ".print_r($as_api_response,true));

    if (property_exists($as_api_response,"sp")) {
    	$sp = $as_api_response->sp;
    	if (property_exists($sp,"StageId")) {
    		$stage_id = (int)$sp->StageId;
 			$sub_stage_id = (int)$sp->StageSubId;
 			$code = $stage_id*100+$sub_stage_id; 
 			$stageName = $sp->StageName;
 			$stageSubName = $sp->StageSubName;
 		}
 		$stage = getASStage($code);
 		$logger->log('debug', "\nStageID:$stage_id,SubstageId:$sub_stage_id,Code=$code,Stage=$stage");
 		$reject_reason = "";
 		if(in_array($code, array(311,417)))
 			$reject_reason = getASRejectionReasons($sp->RejectionIds,$app_id);
 		return array($code,$stage,$reject_reason,$stageName,$stageSubName,$sub_stage_id);
 		
 	}
	$logger->log('debug',"------------end-------------");	
 	return null;
	 
}
function getASStage($code=205){
	$stage_mapping = array(
		205=>"Open",
		206=>"Submitted",
		207=>"Rejected",
		308=>"Sent to Login", //Kanika
		309=>"Sent to Login",	//Kanika
		310=>"Credit",	//Kanika
		311=>"Rejected by Ops",	//Kanika
        327 => "Sent to Login", 
        328 => "Sent to Login",
		412=>"Credit",
		413=>"Credit",
		414=>"Credit",
		415=>"Sanctioned",
		416=>"Credit",
		417=>"Rejected by Credit",	//Kanika
		425=>"Approved",	//Kanika
		426=>"Credit",
		444=>"Credit",
		445=>"Credit",
		467=>"Credit",
		475=>"Credit",
		490=>"Pending Post approval",	//Kanika
        503 =>"Post Ops Rejected",
        504 =>"Post Ops Approved",
		518=>"Sanctioned",
		519=>"Sanctioned",
		520=>"Sanctioned",
		521=>"Sanctioned",
		522=>"Rejected",
		623=>"Disbursed",
		624=>"Disbursed");

 	if(array_key_exists($code, $stage_mapping))
   		$stage = $stage_mapping[$code];
   	return $stage;
}

// RefreshApplicationStatus();
function RefreshRenewalAppStatus() {
    // echo "hi";
	global $timedate;
	$logger= new CustomLogger('RenewalJob');
    //neo_customers api call doesnt authenticate user and so audit logs failed to get created
    //so id is hard coded
    global $current_user;
    $current_user->id = 1;
	$logger->log('debug', '<======================function::refreshRenewedAppIds() starts==========================>');
	$logger->log('debug',  "\ntime :: " . $timedate->now());
	$bean = BeanFactory::getBean('Neo_Customers');
    $query = "neo_customers.deleted=0 and renewed_app_id is not null and renewed_app_id != '' ";
    $items = $bean->get_full_list('',$query);
    if(empty($items)){
    	$items_size = 0;
    }
    else{
    	$items_size = sizeof($items);	
    }
	$logger->log('debug', "\nSize of fetched bean with renewed_app_id - " . $items_size);
    // var_dump($items);
    if (!empty($items)){
	    foreach($items as $item){
	    	// echo $item->name;
	    	$app_id_list = $item->renewed_app_id;
	    	$app_id_list = explode(",", $app_id_list);
			$logger->log('debug', "\n# of renewed_app_id for $item->name is " . sizeof($app_id_list));
	    	if(!empty($app_id_list)){
	    		$stage_list 		= array();
	    		$sub_stage_list = array();
	    		$reject_reason_list = array();
	    		foreach ($app_id_list as $app_id) {
	    			$details = getASAppStageDetails($app_id);	
					$logger->log('debug', "\n" . "details: ".print_r($details,true));
	    			if(isset($details[3]) && !empty($details[3])){//3,4
	    				$stage = trim($details[3]);
	    				if(isset($details[4]) && !empty($details[4])){
	    					$sub_stage = trim($details[4]);
							$logger->log('debug', "\n" . "sub_stage: ".print_r($sub_stage,true));
	    				}
	    			}
	    			else{
	    				$stage = "Open";
	    			}
	    			$sub_stage_id = -1;
	    			if(!empty($details[5])){
	    				$sub_stage_id=$details[5];
	    			}
	    			if($sub_stage_id!=-1)
	    				$custom_status = getCustomisedMapping($sub_stage_id);
	    			if(!empty($custom_status)){
	    				if($custom_status != $item->custom_status){
	    					$amount = '';
	    					if($custom_status=="Loan disbursed"){
								require_once 'custom/include/CurlReq.php';
								$Curl_Req = new CurlReq();
								$url = getenv('SCRM_AS_API_BASE_URL')."/applications/$app_id/get_funded_amount";
								$output = $Curl_Req->curl_req($url);
								$output = json_decode($output);
								if(!empty($output) && property_exists($output, 'funding_amount')) {
									$amount = $output->funding_amount;
								}

							}
	    					$sms = getSMS($custom_status,$amount);
	    					require_once('custom/include/SendSMS.php');
	    					$send = new SendSMS();
	    					$env = getenv('SCRM_ENVIRONMENT');
	    					if($env == 'prod'){
	    						// TODO: SEND sms in prod
								$logger->log('debug', "\nSending sms $sms for stage change to customer\n");
	    						if(!empty($sms))
	    							$send->send_sms_to_user($tag_name="Cust_CRM_12", $item->mobile,$sms,$item);
	    					}else{
								$logger->log('debug', "\nSending sms $sms for stage change to customer\n");
	    						if(!empty($sms))
	    							$send->send_sms_to_user($tag_name="Cust_CRM_12", $item->mobile,$sms,$item);
	    					}
	    					
	    				}
	    				$item->custom_status = $custom_status;
	    			}
					$logger->log('debug', "\nsub_stage_id=$sub_stage_id,custom_status=$custom_status\n");
	    			if(!empty($stage))
	    				array_push($stage_list, $stage);
	    			if(!empty($sub_stage)){
	    				array_push($sub_stage_list, $sub_stage);
	    			}
	    			if(isset($details[2]) && !empty($details[2])){
	    				$reject_reason = $details[2];
	    			}
	    			else{
	    				$reject_reason = "N/A";
	    			}
					$logger->log('debug', "\n Stage : $stage, Reject Reason : $reject_reason ,renewed_app_id : $app_id");	    			
	    			array_push($reject_reason_list, $reject_reason);
	    		}
	    		$stage_list 		= implode(",", $stage_list);
	    		$sub_stage_list 	= implode(",", $sub_stage_list);
	    		$reject_reason_list = implode(",", $reject_reason_list);
	    		if($stage_list != $item->as_stage)
                	$item->as_stage 	= $stage_list;
                if($sub_stage_list != $item->as_sub_stage)
                	$item->as_sub_stage = $sub_stage_list;
                if($item->as_remarks != $reject_reason_list)
                	$item->as_remarks 	= $reject_reason_list;
                $item->save();
            }
        }
      //  die();
    }
    $current_user = null;
	$logger->log('debug', "\n-----------------------function::refreshRenewedAppIds() ends------------------------");
    return true;
}

function getSMS($status,$amount){
	if($status=="Loan disbursed" && empty($amount)){
		return null;
	}
	$sms_arr=array(
	"Loan applied"=>"Dear Customer, your loan application has been submitted successfully. Our representative will get in touch with you shortly. Thank You.",
	"Doc pick up completed"=>"Dear Customer, we have received your documents for loan processing. Thank You",
	"Loan sanctioned"=>"Dear Customer, Your loan has been approved. Our representative will get in touch for further process. Thank You.",
	"Loan disbursed"=>"Dear Customer, Welcome to NeoGrowth Family. Rs. $amount has been credited to your bank account. Thank You for choosing NeoGrowth.");
	if(array_key_exists($status, $sms_arr))
		return $sms_arr[$status];
	return null;

}

function getCustomisedMapping($sub_stage_id){
	$sub_stage_id = (int)$sub_stage_id;
	$prod=array(
		27=>"Loan Applied",
		28=>"Loan applied",
		29=>"Doc pick up completed",
		30=>"Doc pick up completed",
		31=>"Doc pick up completed",
		32=>"Doc pick up completed",
		33=>"Doc pick up completed",
		34=>"Doc pick up completed",
		35=>"Loan sanctioned",
		36=>"Loan disbursed",
		63=>"Loan applied",
		64=>"Doc pick up completed",
		79=>"Doc pick up completed",
		80=>"Doc pick up completed",
		81=>"Doc pick up completed",
		82=>"Doc pick up completed",
		86=>"Loan sanctioned",
		99=>"Loan sanctioned",
		100=>"Loan sanctioned",
		105=>"Doc pick up completed",
		106=>"Doc pick up completed");


	$uat = array(
		27=>"Loan applied",
		28=>"Loan applied",
		29=>"Doc pick up completed",
		30=>"Doc pick up completed",
		31=>"Doc pick up completed",
		32=>"Doc pick up completed",
		33=>"Doc pick up completed",
		34=>"Doc pick up completed",
		35=>"Loan sanctioned",
		36=>"Loan disbursed",
		61=>"Loan sanctioned",
		81=>"Loan sanctioned",
		82=>"Loan sanctioned",
		85=>"Doc pick up completed",
		100=>"Loan applied",
		101=>"Loan applied",
		102=>"Doc pick up completed",
		103=>"Doc pick up completed",
		107=>"Loan sanctioned");
	$env = getenv('SCRM_ENVIRONMENT');
	$arr = "";
	if($env == 'prod'){
		$arr=$prod;
	}else{
		$arr=$uat;
	}
	if(array_key_exists($sub_stage_id, $arr)){
		return $arr[$sub_stage_id];
	}else{
		return null;
	}

}
