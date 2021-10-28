<?php

if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

ini_set("display_errors","Off");

class AfterSaveTarget {
	
	
	public function convert_target_lead(&$bean, $event, $args) {
		
		
		 
        global $db;
        global $current_user;
		$ID = $bean->id; 
	   
	     $Old_disposition_c         = $bean->fetched_row['disposition_c'];
         $Old_sub_disposition_c     = $bean->fetched_row['sub_disposition_c'];
         $disposition_c = $bean->disposition_c;
		 $sub_disposition_c = $bean->sub_disposition_c;
		 $check_disposition_c = $bean->check_disposition_c;
		 $call_back_date_time_c = $bean->call_back_c;
		 $pickup_appointmnet_c = $bean->pickup_appointmnet_c;
		
		if(($disposition_c == 'Call_back')||($disposition_c == 'Not contactable')||($disposition_c == 'Follow_up')){
		  $remarks_c = $bean->remarks_c;
		 }else{
				$remarks_c ='';
			  }
		
	
		$agent_id = $current_user->id;
     if (($check_disposition_c == '1') && (strcmp($Old_sub_disposition_c,$sub_disposition_c)!=0)){
		$prospect = new scrm_Disposition_History();
		$prospect->disposition_c = $disposition_c;
		$prospect->sub_disposition_c = $sub_disposition_c;  
		$prospect->assigned_user_id = $agent_id; 
		$prospect->remarks_c = $remarks_c; 
		$prospect->call_pickup_datetime_c = $call_back_date_time_c; 
		$prospect->assigned_user_id = $agent_id; 
		$prospect->save();
		$bean->load_relationship('prospect_scrm_disposition_history_1');
	
	if(($disposition_c == 'Call_back')||($disposition_c == 'Not contactable')||($disposition_c == 'Follow_up')){
		
		
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
	
		     global $db;
	 $db->query("UPDATE `prospects_cstm` SET check_disposition_c='0' WHERE`id_c`='$ID'");
	
	
	}
		
	}	

		
		global $db;
		global $current_user;

		$old_disposition_c = $bean->fetched_row['disposition_c'];
		$old_sub_disposition_c = $bean->fetched_row['sub_disposition_c']; 
		
		$new_disposition_c = $bean->disposition_c;
		$new_sub_disposition_c = $bean->sub_disposition_c;
		$email1 = $bean->email1;
		
		$userID=$current_user->id;
		$target_id = $bean->id;

		
		if((strcmp($old_disposition_c, $new_disposition_c) != 0) && ($new_disposition_c == 'Interested') && (strcmp($old_sub_disposition_c, $new_sub_disposition_c) != 0) && ($new_sub_disposition_c == 'Lead generated')) {
			
			$myfile = fopen("Logs/a_rel.log","a");
			fwrite($myfile, print_r($_REQUEST, true));
			fclose($myfile);
			
			$lead_bean = new Lead();		
			$lead_bean->salutation = $_REQUEST['salutation'];	
			$lead_bean->first_name = $_REQUEST['first_name'];	
			$lead_bean->last_name = $_REQUEST['last_name'];	
			$lead_bean->phone_mobile = $_REQUEST['phone_mobile'];	
			$lead_bean->business_vintage_years_c = $_REQUEST['business_vintage_years_c'];	
			$lead_bean->merchant_name_c = $_REQUEST['merchant_name_c'];	
			$lead_bean->loan_amount_c = $_REQUEST['loan_amount_c'];	
			$lead_bean->acspm_c = $_REQUEST['acspm_c'];	
			$lead_bean->status = "New";
			$lead_bean->call_back_date_time_c = $_REQUEST['call_back_c'];
			$lead_bean->pickup_appointmnet_c = $_REQUEST['pickup_appointmnet_c'];
			$lead_bean->alt_landline_number_c = $_REQUEST['alt_landline_number_c'];
			$lead_bean->birthdate = $_REQUEST['birthdate'];
			$lead_bean->gender_c = $_REQUEST['gender_c'];
			$lead_bean->industry_type_c = $_REQUEST['industry_type_c'];
			$lead_bean->dq_score_c = $_REQUEST['dq_score_c'];
			$lead_bean->ps_score_c = $_REQUEST['ps_score_c'];
			$lead_bean->total_sales_per_month_c = $_REQUEST['total_sales_per_month_c'];
			$lead_bean->business_ownership_c = $_REQUEST['business_ownership_c'];
			$lead_bean->business_vintage_c = $_REQUEST['business_vintage_c'];
			$lead_bean->business_type_c = $_REQUEST['business_type_c'];
			$lead_bean->has_edc_machine_c = $_REQUEST['has_edc_machine_c'];
			$lead_bean->edc_vintage_c = $_REQUEST['edc_vintage_c'];
			$lead_bean->average_settlements_c = $_REQUEST['average_settlements_c'];
			$lead_bean->right_party_contact_c = $_REQUEST['right_party_contact_c'];
			$lead_bean->product_pitched_c = $_REQUEST['product_pitched_c'];
			$lead_bean->email1 = $email1;
			$lead_bean->assigned_user_id = $userID;
			$lead_bean->residence_ownership_c= $_REQUEST['residence_ownership_c'];
			$lead_bean->merchant_type_c= $_REQUEST['merchant_type_c'];
			$lead_bean->phone_work= $_REQUEST['phone_work'];
			$lead_bean->primary_address_street= $_REQUEST['primary_address_street'];
			$lead_bean->primary_address_postalcode= $_REQUEST['primary_address_postalcode'];
			$lead_bean->primary_address_city= $_REQUEST['primary_address_city'];
			
			$lead_bean->alt_address_postalcode= $_REQUEST['alt_address_postalcode'];
			$lead_bean->alt_address_city= $_REQUEST['alt_address_city'];
			$lead_bean->alt_address_street= $_REQUEST['alt_address_street'];
			$lead_bean->campaign_id= $bean->campaign_id;
					
				
				$lead_bean->save();
				
				$lead_id = $lead_bean->id;

				$query = "update prospects set lead_id = '$lead_id' where id = '$target_id' and deleted = 0";
				$result = $db->query($query);
				
				$email_id=create_guid();
				$sql1 ="INSERT INTO `email_addresses` (`id`, `email_address`, `email_address_caps`, `invalid_email`, `opt_out`, `date_created`, `date_modified`, `deleted`) VALUES ('$email_id','$email1','$email1', '0', '0', NOW(), NOW(), '0')";	
				$result=$db->query($sql1);		
											
				$email_bean_id=create_guid();
				$sql2 ="INSERT INTO `email_addr_bean_rel` (`id`, `email_address_id`, `bean_id`, `bean_module`,`primary_address`, `reply_to_address`, `date_created`, `date_modified`, `deleted`) VALUES ('$email_bean_id', '$email_id', '$lead_id', 'Leads', '0', '0', NOW(), NOW(), '0')";
				$result=$db->query($sql2);
				
		}
		
		//START Attempt Code By Vivek
		//Calculate # of attmepts done
		$attempts_done = $bean->attempts_done_c;
		
		$query1 = "select count(sdh.id) as attempt  from scrm_disposition_history sdh left join prospects_scrm_disposition_history_1_c lsdh on lsdh.prospects_scrm_disposition_history_1scrm_disposition_history_idb = sdh.id left join prospects l on l.id = lsdh.prospects_scrm_disposition_history_1prospects_ida where l.id = '$target_id' and l.deleted = 0 and lsdh.deleted = 0 and sdh.deleted = 0";
		
		$result1 = $db->query($query1);
		
		while($row = $db->fetchByAssoc($result1)){
			$count = $row['attempt'];
		}
		if(strcmp($attempts_done,$count) != 0){
			$update = 1;
			$parameters .= "attempts_done_c = '$count'" . ",";
		}


		
		//End Attempts count

		//update lead if there is any change in attempts
		if($update == 1){
			$parameters = substr($parameters, 0, -1);
		
			$query = ("update prospects_cstm set $parameters where id_c = '$target_id'");
			$result = $db->query($query);
		}
	

		//END Attempt Code By Vivek
		
		$attempts_done = $bean->attempts_done_c;
		
		$query1 = "select count(sdh.id) as attempt  from scrm_disposition_history sdh left join prospects_scrm_disposition_history_1_c lsdh on lsdh.prospects_scrm_disposition_history_1scrm_disposition_history_idb = sdh.id left join prospects l on l.id = lsdh.prospects_scrm_disposition_history_1prospects_ida where l.id = '$target_id' and l.deleted = 0 and lsdh.deleted = 0 and sdh.deleted = 0";
		
		$result1 = $db->query($query1);
		
		while($row = $db->fetchByAssoc($result1)){
			$count = $row['attempt'];
		}
		if(strcmp($attempts_done,$count) != 0){
			$update = 1;
			$parameters .= "attempts_done_c = '$count'" . ",";

		}
		
		if($update == 1){
			$parameters = substr($parameters, 0, -1);
		
			$query = ("update prospects_cstm set $parameters where id_c = '$target_id'");
			$result = $db->query($query);
		}
		
		
	}	
}
