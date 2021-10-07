<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
require_once('include/entryPoint.php');
require_once('SendSMS.php');

class Class_Disposition_Add {
    static $run = false;
    function fun_Disposition_Save($bean, $event, $arguments) {
        global $db;
        global $current_user;
        // if($_REQUEST['module'] == 'Import'){  
        //     return;
        // }
        $ID                = $bean->id;
        $disposition     = $bean->disposition;
        $sub_disposition = $bean->subdisposition;
        
        $lead_source = $bean->lead_source;
        if($bean->lead_type != 'Hot'){
            if ($disposition == "meeting_fixed" ) {
                $db->query("UPDATE `neo_paylater_leads` SET lead_type='Hot' WHERE`id`='$ID'");
            } else{
                if ($lead_source == "Self Generated") 
                    $db->query("UPDATE `neo_paylater_leads` SET lead_type='Cold' WHERE`id`='$ID'");
                else
                    $db->query("UPDATE `neo_paylater_leads` SET lead_type='Warm' WHERE`id`='$ID'");
            }
        }
        $disposition_c_old = $bean->fetched_row['disposition'];
        $sub_disposition_c_old = $bean->fetched_row['subdisposition'];

        $check_disposition = $bean->check_disposition;
        $agent_id = $bean->assigned_user_id;

		

        $remarks = empty($bean->description)?"":$bean->description;
        if(empty($bean->lead_source)){
    		$bean->lead_source = "Self Generated";
    	} else if(($bean->lead_source == 'Lead Page') || ($bean->lead_source == 'NG Portal')){
    		$bean->campaign = $bean->lead_source;
    	}
        if($bean->partner_name == "Metro Cash and Carry"){
            $check_disposition = 1;
        }
            
        $datetime = NULL;
        if ($check_disposition) {
            
            if ($sub_disposition == 'follow_up_rescheduled' || $subdisposition == 'missing_documents_followup') {
                
                $datetime = $bean->callback;
                $name                   = $GLOBALS['app_list_strings']['paylater_disposition_list'][$disposition];
                
                $call                   = new Call();
                $call->name             = $name;
                $call->date_start       = (empty($call_back_date_time) ? "" : $call_back_date_time);
                $call->lead_id          = $ID;
                $call->parent_type      = "Neo_Paylater_Leads";
                $call->parent_id        = $ID;
                $call->assigned_user_id = $agent_id;
                $call->save();
            }
            
            if (($disposition == 'meeting_fixed' || $subdisposition == 'missing_documents_meeting')) {
            	$datetime = $bean->meeting;
                $name                      = $GLOBALS['app_list_strings']['paylater_disposition_list'][$disposition];
                $meeting                   = new Meeting();
                $meeting->name             = $name;
                $meeting->date_start       = $bean->meeting;
                $meeting->lead_id          = $ID;
                $meeting->parent_type      = "Neo_Paylater_Leads";
                $meeting->parent_id        = $ID;
                $meeting->assigned_user_id = $agent_id;
                $meeting->save();
            }
            
            if (strcmp($disposition, $disposition_c_old) != 0 or strcmp($sub_disposition, $sub_disposition_c_old) != 0) {
	        	$partner = "";
	        	$date = "";
	        	$time = "";
	        	if(!empty($bean->partner_name)) {
		            $partner = $GLOBALS['app_list_strings']['partner_name_list'][$bean->partner_name];
		        }
	            $name = $bean->first_name . " " . $bean->last_name;
	            $merchant_name = $bean->business_name;
	            if(!empty($datetime)){
		            $date = date("d/m/Y",strtotime($datetime));
		            $time = date("H:i",strtotime($datetime));
	        	}
	            $sub_disp_list = array('follow_up_rescheduled','meeting_fixed_meeting');
	            $sms_list = array(
	        		"As requested, we will call you on $date at $time regarding the credit to purchase from $partner. Thank you, NeoGrowth Team.",
	        		
	        		"Dear $name, our executive will be visiting on $date at $time to collect the required documents for the credit to purchase from $partner. Kindly be ready with it by then. Thanks, NeoGrowth.");
	            $ind = array_search($sub_disposition,$sub_disp_list);
	            if($ind > -1) { 
	            	$message =  $sms_list[$ind];
	            }
	            $mobile_no = $bean->phone_mobile;
	            $mobile_no = "91" . substr($mobile_no, -10);
	            if (!empty($message)) {
	            	$sms = new SendSMS();
	            	$sms->send_sms_to_user($tag_name="Cust_CRM_9", $mobile_no, $message, $bean);
	            }
	        

        
                if (($disposition != 'sent_to_ng_login') ) {
                    $db->query("UPDATE `neo_paylater_leads` SET check_disposition='0' WHERE`id`='$ID'");
                }
                $disp_history = new scrm_Disposition_History();
                $disp_history->name = $GLOBALS['app_list_strings']['paylater_disposition_list'][$disposition];
                $disp_history->description = $GLOBALS['app_list_strings']['paylater_subdisposition_list'][$sub_disposition];
                $disp_history->assigned_user_id  = $agent_id;
                // $disp_history->remarks  = $remarks;
                $disp_history->save();
                $bean->load_relationship('neo_paylater_leads_scrm_disposition_history');
                // die(var_dump($bean));
                $bean->neo_paylater_leads_scrm_disposition_history->add($disp_history->id);
            }
        }
    }
}

?>
