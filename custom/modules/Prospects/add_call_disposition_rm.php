<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/entryPoint.php');
 
ini_set("display_errors","On");
class Class_Disposition_Add {

	
    function fun_Disposition_Save($bean, $event, $arguments){
      	
      	    
        
        global $db;
        global $current_user;
		$ID = $bean->id; 
	
 $disposition_c = $bean->disposition_c;
 $sub_disposition_c = $bean->sub_disposition_c;
     $check_disposition_c = $bean->check_disposition_c;
	$call_back_date_time_c = $bean->call_back_c;
     $pickup_appointmnet_c = $bean->pickup_appointmnet_c;
	
     $agent_id = $current_user->id;

	if($check_disposition_c=='1'){
		$prospect = new scrm_Disposition_History();
		$prospect->disposition_c = $disposition_c;
		$prospect->sub_disposition_c = $sub_disposition_c;  
		$prospect->assigned_user_id = $agent_id;  
		//~ $prospect->prospects_scrm_disposition_history_1prospects_ida =$ID;   
		$prospect->save();
		$bean->load_relationship('prospect_scrm_disposition_history_1');
	//~ echo	$bean->prospect_scrm_disposition_history_1->$ID;
		
	
	if(($disposition_c == 'Call_back')||($disposition_c == 'Not contactable')||($disposition_c == 'Follow_up')||($disposition_c == 'Interested')){
		
		if(empty($call_back_date_time_c)){
			$call_back_date_time_c = $bean->pickup_appointmnet_c;
		}
		
		$name=$GLOBALS['app_list_strings']['cstm_disposition_list'][$disposition_c];
		$call = new Call();
		$call->name = $name;
		$call->date_start = $call_back_date_time_c;
		$call->prospect_id = $ID;   
		$call->parent_type = "Prospects";   
		$call->parent_id =$ID;   
		$call->assigned_user_id = $agent_id;   
		$call->save();	
	}
	if(($disposition_c == 'Pickup generation_Appointment')||($disposition_c == 'Pickup fulfillment')){
		
		$name=$GLOBALS['app_list_strings']['cstm_disposition_list'][$disposition_c];
		$meeting = new Meeting();
		$meeting->name = $name;
		$meeting->date_start = $pickup_appointmnet_c;
		$meeting->prospect_id = $ID;   
		$meeting->parent_type = "Prospects";   
		$meeting->parent_id =$ID;   
		$meeting->assigned_user_id = $agent_id;   
		$meeting->save();	
	}
	
		
	if(($disposition_c != 'Interested')&&($sub_disposition_c != 'Lead generated')){
		$db->query("UPDATE `prospects_cstm` SET check_disposition_c='0' WHERE`id_c`='$ID'");
	}
	
	
} 
    }
}

?>
