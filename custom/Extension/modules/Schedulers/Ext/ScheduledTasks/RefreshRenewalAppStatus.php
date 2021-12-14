<?php
require_once 'custom/CustomLogger/CustomLogger.php';
global $logger;
array_push($job_strings, 'RefreshRenewalAppStatus');
date_default_timezone_set('Asia/Kolkata');
$logger = new CustomLogger('RenewalJob');

function getASRejectionReasons($rejections,$app_id){
	$logger = new CustomLogger('RenewalJob');
	$logger->log('debug', "----------getASRejectionReasons--------------");
    $logger->log('debug', "Rejections: $rejections");
	
	if(empty($rejections)) 
		return "";

	require_once 'custom/include/CurlReq.php';
    $cl 			= new CurlReq();
    $rejection_ids 	= explode(',',$rejections);
	$reason 		= "";
    $logger->log('debug', "Rejection IDs: ".print_r($rejection_ids,true));
    if (!empty($rejection_ids)){
        foreach($rejection_ids as $reject_id){
        	$as_api_url 		= getenv('SCRM_AS_API_BASE_URL')."/applications/$app_id/get_rejection_reason?rejection_id=".$reject_id;
        	$rejection_reason 	= $cl->curl_req($as_api_url,'get');

			$logger->log('debug', "curl URL : $as_api_url");
			$logger->log('debug', "Response : " . var_export($rejection_reason, true));

        	if(!empty($rejection_reason)) {
		        $rejection_array = json_decode($rejection_reason);
		        $reason 		.= $rejection_array->RejectionReason.", ";
		    }
     	}
    }
	$logger->log('debug', "Reason  : " . $reason);
    return $reason;
}
function getASAppStageDetails($app_id){
	$logger = new CustomLogger('RenewalJob');
	$logger->log('debug',"------------getASAppStageDetails--------------");
	require_once 'custom/include/CurlReq.php';
    $cl 				= new CurlReq();
	$as_api_url 		= getenv('SCRM_AS_API_BASE_URL')."/external_interfaces/get_application_status?application_id=".$app_id;
    $as_api_response 	= json_decode($cl->curl_req($as_api_url,'get'));
    $code 				= 205;
    $stage 				= "Open";

    $logger->log('debug',"curl URL : $as_api_url");
	$logger->log('debug', "Response for $app_id: ".print_r($as_api_response,true));

    if (property_exists($as_api_response,"sp")) {
    	$sp 				= $as_api_response->sp;
		$reject_reason 		= "";
    	if (property_exists($sp,"StageId")) {
    		$stage_id 		= (int)$sp->StageId;
 			$sub_stage_id 	= (int)$sp->StageSubId;
 			$code 			= $stage_id*100+$sub_stage_id; 
 			$stageName 		= $sp->StageName;
 			$stageSubName 	= $sp->StageSubName;
 		}
 		$stage 				= getASStage($code);
 		
		$logger->log('debug', "StageID:$stage_id,SubstageId:$sub_stage_id,Code=$code,Stage=$stage");
 		
 		if(in_array($code, array(311,417)))
 			$reject_reason 	= getASRejectionReasons($sp->RejectionIds,$app_id);
 		return array($code,$stage,$reject_reason,$stageName,$stageSubName,$sub_stage_id);
 		
 	}
	$logger->log('debug',"------------end-------------");	
 	return null;
	 
}
function getASStage($code=205){
	global $sugar_config;
	$stage_mapping = $sugar_config['AS_renewal_stage_mapping'];

 	if(array_key_exists($code, $stage_mapping))
   		$stage = $stage_mapping[$code];
   	return $stage;
}

// RefreshApplicationStatus();
function RefreshRenewalAppStatus() {
	global $timedate;
	$logger = new CustomLogger('RenewalJob');
    //neo_customers api call doesnt authenticate user and so audit logs failed to get created
    //so id is hard coded
    global $current_user;
    $current_user->id = 1;
	$logger->log('debug', '<======================function::refreshRenewedAppIds() starts==========================>');
	$logger->log('debug',  "time :: " . $timedate->now());
	$bean 		= BeanFactory::getBean('Neo_Customers');
    $query 		= "neo_customers.deleted=0 and renewed_app_id is not null and renewed_app_id != '' ";
    $items 		= $bean->get_full_list('',$query);
    if(empty($items)) {
    	$items_size = 0;
    }
    else{
    	$items_size = sizeof($items);	
    }
	$logger->log('debug', "Size of fetched bean with renewed_app_id - " . $items_size);
    if (!empty($items)){
	    foreach($items as $item){
	    	$app_id_list = $item->renewed_app_id;
	    	$app_id_list = explode(",", $app_id_list);
			$logger->log('debug', "# of renewed_app_id for $item->name is " . sizeof($app_id_list));
	    	if(!empty($app_id_list)){
	    		$stage_list 		= array();
	    		$sub_stage_list 	= array();
	    		$reject_reason_list = array();
	    		foreach ($app_id_list as $app_id) {
	    			$details = getASAppStageDetails($app_id);	
					$sub_stage_id = -1;
					$logger->log('debug', "details: ".print_r($details,true));
	    			if(isset($details[3]) && !empty($details[3])){//3,4
	    				$stage = trim($details[3]);
	    				if(isset($details[4]) && !empty($details[4])){
	    					$sub_stage = trim($details[4]);
							$logger->log('debug', "sub_stage: ".print_r($sub_stage,true));
	    				}
	    			}
	    			else{
	    				$stage = "Open";
	    			}
	    			
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
								$Curl_Req 	= new CurlReq();
								$url 		= getenv('SCRM_AS_API_BASE_URL')."/applications/$app_id/get_funded_amount";
								$output 	= $Curl_Req->curl_req($url);
								$output 	= json_decode($output);
								if(!empty($output) && property_exists($output, 'funding_amount')) {
									$amount = $output->funding_amount;
								}
								$logger->log('debug', "curl URL : $url");
								$logger->log('debug', "Response : " . var_export($output, true));
							}
							require_once('custom/include/SendSMS.php');
	    					$sms 	= getSMS($custom_status,$amount);
	    					$send 	= new SendSMS();
	    					$env 	= getenv('SCRM_ENVIRONMENT');
	    					if($env == 'prod'){
	    						// TODO: SEND sms in prod
								$logger->log('debug', "Sending sms $sms for stage change to customer");
	    						if(!empty($sms))
	    							$send->send_sms_to_user($tag_name="Cust_CRM_12", $item->mobile,$sms,$item);
	    					}else{
								$logger->log('debug', "Sending sms $sms for stage change to customer");
	    						if(!empty($sms))
	    							$send->send_sms_to_user($tag_name="Cust_CRM_12", $item->mobile,$sms,$item);
	    					}
	    					
	    				}
	    				$item->custom_status = $custom_status;
	    			}
					$logger->log('debug', "sub_stage_id=$sub_stage_id,custom_status=$custom_status");
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
					$logger->log('debug', " Stage : $stage, Reject Reason : $reject_reason ,renewed_app_id : $app_id");	    			
	    			array_push($reject_reason_list, $reject_reason);
	    		}
	    		$stage_list 			= implode(",", $stage_list);
	    		$sub_stage_list 		= implode(",", $sub_stage_list);
	    		$reject_reason_list 	= implode(",", $reject_reason_list);
	    		if($stage_list != $item->as_stage)
                	$item->as_stage 	= $stage_list;
                if($sub_stage_list != $item->as_sub_stage)
                	$item->as_sub_stage = $sub_stage_list;
                if($item->as_remarks != $reject_reason_list)
                	$item->as_remarks 	= $reject_reason_list;
                $item->save();
            }
        }
    }
    $current_user = null;
	$logger->log('debug', "-----------------------function::refreshRenewedAppIds() ends------------------------");
    return true;
}

function getSMS($status,$amount){
	if($status=="Loan disbursed" && empty($amount)){
		return null;
	}
	$sms_arr = array(
		"Loan applied"			=> "Dear Customer, your loan application has been submitted successfully. Our representative will get in touch with you shortly. Thank You.",
		"Doc pick up completed"	=> "Dear Customer, we have received your documents for loan processing. Thank You",
		"Loan sanctioned"		=> "Dear Customer, Your loan has been approved. Our representative will get in touch for further process. Thank You.",
		"Loan disbursed"		=> "Dear Customer, Welcome to NeoGrowth Family. Rs. $amount has been credited to your bank account. Thank You for choosing NeoGrowth."
	);
	if(array_key_exists($status, $sms_arr))
		return $sms_arr[$status];
	return null;
}

function getCustomisedMapping($sub_stage_id){
	global $sugar_config;
	$sub_stage_id 	= (int)$sub_stage_id;
	$prod 			= $sugar_config['prod_renewal_sub_stage'];
	$env 			= getenv('SCRM_ENVIRONMENT');
	$arr 			= $sugar_config['uat_renewal_sub_stage'];
	if($env == 'prod')
		$arr = $prod;
	
	if(array_key_exists($sub_stage_id, $arr)) 
		return $arr[$sub_stage_id];

	return null;
}
