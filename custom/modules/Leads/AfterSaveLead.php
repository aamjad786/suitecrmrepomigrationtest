<?php

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class AfterSaveLead
{
	//
	public function convert_lead_acc_con_opp(&$bean, $event, $args)
	{

		global $db, $app_list_strings;
		$old_status = $bean->fetched_row['status'];
		$new_status = $bean->status;
		//$disposition_c = $bean->disposition_c;
		//$sub_disposition_c = $bean->sub_disposition_c;

		if (empty($bean->fetched_row) && (strcmp($old_status, $new_status) != 0) && ($new_status == 'Verified')) {
			$myfile = fopen("Logs/convertlead.log", 'a');
			fwrite($myfile, "Status=$new_status\n");
			fwrite($myfile, "Status=$old_status\n");
			fwrite($myfile, "con\n");
			$opportunity_bean = $this->create_opportunity($bean);
		}
	}

	public function update_opp(&$bean, $event, $args)
	{
		if (!empty($bean->opportunity_id) || $bean->opportunity_id != "") {
			$myfile = fopen("Logs/convertlead.log", 'a');
			fwrite($myfile, "convertlead\n");
			$opportunity_bean = $this->create_opportunity($bean);
		}
	}



	public function save_on_acc_con_opp(&$bean, $event, $args)
	{

		global $db, $current_user;
		$myfile = fopen("Logs/convertlead.log", 'a');
		$old_disposition_c = $bean->stored_fetched_row_c['disposition_c'];
		//$old_sub_disposition_c = $bean->stored_fetched_row_c['sub_disposition_c']; 

		$new_disposition_c = $bean->disposition_c;
		//$new_sub_disposition_c = $bean->sub_disposition_c;
		fwrite($myfile, "$new_disposition_c\n");
		fwrite($myfile, "$old_disposition_c\n");
		$sources = array('Alliances', 'Digital Journey', 'Web Site', 'missed_calls_sms', 'Missed Calls', 'Marketing');
		if (((strcmp($old_disposition_c, $new_disposition_c) != 0)
				&&
				(($new_disposition_c == 'interested')  || ($new_disposition_c == 'pick_up'))) || in_array($bean->lead_source, $sources)
		) {


			fwrite($myfile, "save\n");
			$opportunity_bean = $this->create_opportunity($bean);
		}
	}

	public function audit_first_assigned(&$bean, $event, $args)
	{
		global $db, $current_user;
		$myfile = fopen("Logs/convertlead.log", 'a');
		fwrite($myfile, "\n" . "audit" . "\n");
		$date_audit = date("Y-m-d h:i:s");
		$timestamp = strtotime($date_audit);
		$time = $timestamp - (5 * 60 * 60 + 30 * 60); //subtract 5h 30min from current time;
		$datetime = date("Y-m-d H:i:s", $time);
		fwrite($myfile, "\n" . "yes" . "\n");
		$auditid = create_guid();
		fwrite($myfile, "\n" . $auditid . "\n");
		$id = $bean->id;
		$old_assigned_user = $bean->fetched_row['assigned_user_id'];
		$created_by = $current_user->id;
		if (empty($created_by) || !isset($created_by)) {
			$created_by = "1";
		}
		if ((empty($old_assigned_user) || !isset($old_assigned_user)) && (!empty($bean->assigned_user_id) && isset($bean->assigned_user_id))) {
			$query = "insert into leads_audit values ('$auditid','$id','$datetime','$created_by','assigned_user_id','relate','$old_assigned_user','$bean->assigned_user_id',null,null)";
			$result = $db->query($query);
			fwrite($myfile, "\n" . $query . "\n");
		}
	}

	public function create_opportunity($bean)
	{

		$first_name = $bean->first_name;
		$last_name = $bean->last_name;
		$salutation = $bean->salutation;
		$email1 = $bean->email1;
		$phone_mobile = $bean->phone_mobile;
		$phone_work = $bean->phone_work;
		$description = $bean->description;
		//$assigned_user_id = $bean->assigned_user_id;
		$merchant_name_c = $bean->merchant_name_c;
		$acspm = $bean->acspm_c;
		$seller_id_online_platform = $bean->seller_id_online_platform;
		$seller_partner_rating_online_platform = $bean->seller_partner_rating_online_platform;
		$seller_customer_rating_online_platform = $bean->seller_customer_rating_online_platform;
		$business_age_in_months = $bean->business_age_in_months;
		$settlement_cycle_in_days = $bean->settlement_cycle_in_days;
		$partner_id = $bean->partner_id;
		$sales_3_month_c = $bean->sales_3_month_c;
		$industry_c = $bean->industry_c;
		$merchant_name_c = $bean->merchant_name_c;
		$business_vintage_years_c = $bean->business_vintage_years_c;
		$loan_amount_c = $bean->loan_amount_c;
		$avg_sales_per_month_c = $bean->avg_sales_per_month_c;
		$lead_source = $bean->lead_source;
		$scheme_c = $bean->scheme_c;
		$control_program_c = $bean->control_program_c;
		$Alliance_Lead_Docs_shared_c = $bean->Alliance_Lead_Docs_shared_c;
		$stage_drop_off_c = $bean->stage_drop_off_c;
		$app_form_link_c = $bean->app_form_link_c;
		$dsa_id = $bean->dsa_id;

		$postalcode = $bean->primary_address_postalcode;
		$city = $bean->primary_address_city;
		$street = $bean->primary_address_street;
		$dsa_code_c = $bean->dsa_code_c;
		$sub_source_c = $bean->sub_source_c;
		$referral_agent = $bean->refered_by;
		$source_type_c = $bean->source_type_c;
		$digital = strtolower($bean->digital);
		$opportunity_bean = new Opportunity();
		$new = 1;
		if (!empty($bean->opportunity_id)) {
			$new = 0;
			$opportunity_bean->retrieve($bean->opportunity_id);
		}

		if ($bean->dsa_code_c == 'Nineroot Technologies Private Limited') {

			$opportunity_bean->opportunity_status_c = 'appointment_done_cam_to_visit_customer';
		}

		$opportunity_bean->name = $salutation . " " . $first_name . " " . $last_name;
		//~ $opportunity_bean->name = "Opportunity for - $merchant_name_c";
		$opportunity_bean->email1 = $email1;
		$opportunity_bean->description = $description;

		//$opportunity_bean->created_by = $assigned_user_id;
		$opportunity_bean->merchant_name_c = $merchant_name_c;
		//~ $opportunity_bean->pickup_appointment_city_c = $city_val;
		$opportunity_bean->acspm_c = $acspm;
		$opportunity_bean->sales_stage = "Open";
		$opportunity_bean->dsa_code_c = $dsa_code_c;
		$opportunity_bean->sub_source_c = $sub_source_c;
		$opportunity_bean->lead_source = $lead_source;
		$opportunity_bean->scheme_c = $scheme_c;
		$opportunity_bean->Alliance_Lead_Docs_shared_c = $Alliance_Lead_Docs_shared_c;
		$opportunity_bean->referral_agent_id_c = $referral_agent;
		$opportunity_bean->original_app_id_c = $bean->original_app_id_c;
		$opportunity_bean->is_renewal_c = $bean->is_renewal_c;
		$opportunity_bean->product_type_c = $bean->product_type_c;
		$opportunity_bean->seller_id_online_platform = $seller_id_online_platform;
		$opportunity_bean->seller_customer_rating_online_platform = $seller_customer_rating_online_platform;
		$opportunity_bean->seller_partner_rating_online_platform = $seller_partner_rating_online_platform;
		$opportunity_bean->business_age_in_months = $business_age_in_months;
		$opportunity_bean->settlement_cycle_in_days = $settlement_cycle_in_days;
		$opportunity_bean->partner_id = $partner_id;
		$opportunity_bean->sales_3_month_c = $sales_3_month_c;
		$opportunity_bean->industry = $industry_c;
		$opportunity_bean->source_type_c = $source_type_c;
		$opportunity_bean->control_program_c = $control_program_c;
		$opportunity_bean->app_form_link_c = $app_form_link_c;
		$opportunity_bean->stage_drop_off_c = $stage_drop_off_c;
		$opportunity_bean->dsa_id = $dsa_id;
		$datetime = $_REQUEST['pickup_appointment_date_time_c'];
		global $current_user, $db;
		$opportunity_bean->digital = $this->first_val_if_present(strtolower($_REQUEST['digital']), $digital);
		if (!empty($datetime)) {

			$datef = $current_user->getPreference('datef');
			$timef =  $current_user->getPreference('timef');
			$dateFormat = $datef . " " . $timef;
			$datetime = DateTime::createFromFormat("$dateFormat", $datetime)->format('Y-m-d H:i');
			$datetime = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($datetime)));
			$opportunity_bean->pickup_appointment_date_c = $datetime;
		}

		// $opportunity_bean->loan_amount_c = $loan_amount_c;

		$opportunity_bean->loan_amount_c = $this->first_val_if_present($_REQUEST['loan_amount_required_c'], $loan_amount_c);
		$opportunity_bean->pickup_appointment_contact_c = $this->first_val_if_present($_REQUEST['pickup_contact_number_c'], $phone_mobile);
		$opportunity_bean->pickup_appointment_address_c = $this->first_val_if_present($_REQUEST['pickup_appointment_address_c'], $street);

		$opportunity_bean->pickup_appointment_pincode_c = $this->first_val_if_present($_REQUEST['pickup_appointment_pincode_c'], $postalcode);
		$opportunity_bean->pickup_appointment_city_c = $this->first_val_if_present($_REQUEST['pickup_appointment_city_c'], $city);
		//$opportunity_bean->assigned_user_id = $this->first_val_if_present($bean->user_id_c,$bean->assigned_user_id); 
		//$opportunity_bean->user_id_c = $bean->assigned_user_id; 


		$disposition = $bean->disposition_c;

		$opportunityCity = ucfirst(strtoupper($_REQUEST['pickup_appointment_city_c']));
		if (($disposition == 'interested' || $disposition == 'pick_up') && ($lead_source == "Marketing" || $lead_source == "Alliances" || $lead_source == "missed_calls_sms" || $lead_source == "Missed Calls" || $lead_source == "Web Site" || $lead_source == "Facebook" || $lead_source == "Tele marketing")) {
			$userToBeAssigned = $this->assignUserToOpportunity($opportunityCity);
			$opportunity_bean->assigned_user_id =  $userToBeAssigned;
			$opportunity_bean->user_id_c = $userToBeAssigned;
		} else {
			$opportunity_bean->assigned_user_id = $this->first_val_if_present($bean->user_id_c, $bean->assigned_user_id);
			$opportunity_bean->user_id_c = $bean->assigned_user_id;
		}
		#GET leads id in opportunities for duplicate check

		$query = "SELECT opportunity_id FROM leads WHERE opportunity_id='$bean->id'";

		$result = $db->query($query);

		$row = $db->fetchByAssoc($result);

		if (empty($row['opportunity_id'])) {

			$opportunity_bean->save();
		}



		$opp_id = $opportunity_bean->id;
		$lead_id = $bean->id;
		$query = "update leads set status = 'Verified' , opportunity_id = '$opp_id' where id = '$lead_id' and deleted = 0";
		$result = $db->query($query);

		$save_contact_account = true;

		if ($save_contact_account) {
			$contact_bean = new Contact();
			if (!empty($bean->contact_id)) {
				$contact_bean->retrieve($bean->contact_id);
			}
			$contact_bean->first_name = $first_name;
			$contact_bean->last_name = $last_name;
			$contact_bean->email1 = $email1;
			$contact_bean->phone_mobile = $phone_mobile;
			$contact_bean->phone_work = $phone_work;
			$contact_bean->description = $description;
			//$contact_bean->assigned_user_id = $assigned_user_id;
			//$contact_bean->created_by = $assigned_user_id;
			$contact_bean->primary_address_street = $street;
			$contact_bean->primary_address_city = $city;
			$contact_bean->primary_address_postalcode = $postalcode;
			$contact_bean->save();


			$account_bean = new Account();
			if (!empty($bean->account_id)) {
				$account_bean->retrieve($bean->account_id);
			}
			$account_bean->name = $merchant_name_c;
			$account_bean->email1 = $email1;
			$account_bean->phone_mobile = $phone_mobile;
			$account_bean->phone_work = $phone_work;
			$account_bean->description = $description;
			//$account_bean->assigned_user_id = $assigned_user_id;
			//$account_bean->created_by = $assigned_user_id;
			$account_bean->billing_address_street = $street;
			$account_bean->billing_address_city = $city;
			$account_bean->billing_address_postalcode = $postalcode;
			$account_bean->save();

			$acc_id = $account_bean->id;
			$con_id = $contact_bean->id;

			$query = "update leads set status = 'Verified' , account_id = '$acc_id' , contact_id = '$con_id' , opportunity_id = '$opp_id' where id = '$lead_id' and deleted = 0";
			$result = $db->query($query);

			$ac_id = create_guid();
			$ao_id = create_guid();

			$ac_query = "insert into accounts_contacts(id,contact_id,account_id,date_modified,deleted) values ('$ac_id','$con_id','$acc_id',NOW(),'0')";
			$db->query($ac_query);


			if ($new == 1) {
				$ao_query = "insert into accounts_opportunities(id,opportunity_id,account_id,date_modified,deleted) values ('$ao_id','$opp_id','$acc_id',NOW(),'0')";
			} else {
				$ao_query = "update accounts_opportunities set account_id='$acc_id',date_modified=NOW(),deleted='0' where opportunity_id='$opp_id'";
			}
			$db->query($ao_query);
		}
		return $opportunity_bean;
	}

	function first_val_if_present($a, $b)
	{
		return ((!empty($a)) ? ($a) : ($b));
	}

	public function business_vintage_years(&$bean, $event, $args)
	{
		global $db;

		$lead_id = $bean->id;
		$business_vintage = $bean->business_vintage_c;
		// $bv_years = $bean->business_vintage_years_c;

		$parameters = '';

		//Calculate Business Vintage Years
		if (empty($business_vintage) && !empty($bv_years)) {
			$update = 1;
			$years = date("Y") - $bv_years;
			$parameters .= "business_vintage_c = '$years'" . ",";
		}
		//End vintage

		//Calculate # of attmepts done
		$attempts_done = $bean->attempts_done_c;

		$query1 = "select count(sdh.id) as attempt  from scrm_disposition_history sdh left join leads_scrm_disposition_history_1_c lsdh on lsdh.leads_scrm_disposition_history_1scrm_disposition_history_idb = sdh.id left join leads l on l.id = lsdh.leads_scrm_disposition_history_1leads_ida where l.id = '$lead_id' and l.deleted = 0 and lsdh.deleted = 0 and sdh.deleted = 0";

		$result1 = $db->query($query1);

		while ($row = $db->fetchByAssoc($result1)) {
			$count = $row['attempt'];
		}
		if (strcmp($attempts_done, $count) != 0) {
			$update = 1;
			$parameters .= "attempts_done_c = '$count'" . ",";
		}
		//End Attempts count

		//update lead if there is any change in vintage or attempts
		if ($update == 1) {
			$parameters = substr($parameters, 0, -1);

			$query = "update leads_cstm set $parameters where id_c = '$lead_id'";
			$result = $db->query($query);
		}


		//Update Account name if merchant name changed
		$merchant_name = $bean->merchant_name_c;
		$fetched_merchant_name = $bean->fetched_row['merchant_name_c'];

		if (strcmp($merchant_name, $fetched_merchant_name) != 0) {
			$account_id = $bean->account_id;
			$opportunity_id = $bean->opportunity_id;
			$db->query("update accounts set name = '$merchant_name' where id = '$account_id'");
			$db->query("update opportunities_cstm set merchant_name_c = '$merchant_name' where id_c = '$opportunity_id'");
		}
		//end update
	}

}
