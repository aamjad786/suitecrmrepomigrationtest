<?php
require_once 'custom/CustomLogger/CustomLogger.php';
require_once('custom/include/CurlReq.php');

$job_strings[] = 'PushInstaLeads';

function PushInstaLeads()
{

	$logger = new CustomLogger('PushInstaLeadsToEOS');
	$logger->log('debug', 'PushInstaLeadsToEOS Started at ' . date('Y-M-d H:i:s'));


	$bean = BeanFactory::getBean('Opportunities');

	$DayDate = date('Y-m-d H:i:s', strtotime('-360 minutes'));
	$lead_list = $bean->get_full_list("", "opportunities.date_entered>'2021-04-15' and
										   opportunities.date_entered<'$DayDate' and 
										   opportunities_cstm.control_program_c='NeoCash Insta' and 
										   opportunities.deleted=0 and 
										   (opportunities_cstm.push_count_c<=5 or opportunities_cstm.push_count_c is null) and
										   (opportunities_cstm._c<=2 or opportunities_cstm.sent_count_c is null)");

	$logger->log('debug', 'Total Insta leads fetched to process ' . count($lead_list));
	
	foreach ($lead_list as $lead) {
		
		$id = $lead->id;
		$lead_bean = BeanFactory::getBean('Leads');
		$list = $lead_bean->get_full_list("", "leads.opportunity_id='$id'");
	
		if (empty($list[0]->id)) {
			$logger->log('debug', 'Skipping as lead is missing for opportunity ' . $id);
			$lead->push_count_c = 100;
			$lead->save();
			continue;
		}
		
		if ((($lead->sales_stage != "Open" && !empty($lead->sales_stage)) || !empty($lead->application_id_c)) && $lead->sent_count == 0) {

			$logger->log('debug', 'Skipping as sales stage is ' . $lead->sales_stage . 'and application is ' . $lead->application_id_c . 'and sent_count is ' . $lead->sent_count);
			$lead->push_count_c = 50;
			$lead->save();
			continue;
		}

		if ((($lead->sales_stage == "Open" || empty($lead->sales_stage)) || empty($lead->application_id_c)) && $lead->sent_count == 1) {
			$logger->log('debug', 'Skipping as sales stage is' . $lead->sales_stage . ' and application is' . $lead->application_id_c . ' and sent_count is ' . $lead->sent_count);
			continue;
		}


		$arr = array();
		$arr['Lead_Source'] = $lead->lead_source;
		$arr['Sub_Source'] = $list[0]->sub_source_c;
		$arr['DSA_code'] = $lead->dsa_code_c;
		$arr['First_Name'] = $lead->name;
		$arr['Last_Name'] = "";
		$arr['Mobile_Number'] = $lead->pickup_appointment_contact_c;
		$arr['EmailID'] = $list[0]->email1;
		$arr['Business_Trading_Name'] = $lead->merchant_name_c;
		$arr['Lead_ID'] = $lead->id;
		$arr['City'] = $lead->pickup_appointment_city_c;
		$arr['product'] = "NeoCash Insta";
		$arr['remarks'] = $lead->remarks;
		$arr['Loan_amount'] = $lead->loan_amount_c;
		$arr['stage_drop_off'] = $lead->stage_drop_off;
		$arr['Address_Street'] = $list[0]->primary_address_street;
		$arr['Address_pin'] = $list[0]->primary_address_postalcode;
		$arr['stage_drop_off'] = $lead->sales_stage == "Sanctioned" ? 'Customer Deal Generated' : '';
		$arr['app_form_link'] = $lead->app_form_link;
		$arr['product_type'] = "NeoCash Insta";
		$arr['loan_amount_c'] = $lead->loan_amount_c;

		$logger->log('debug', 'Payload: ' . print_r($arr, true));

		$json_body = json_encode($arr);


		$curl = new CurlReq();
		$headers  = null;
		global $sugar_config;
		$url = $sugar_config['EOS_API_URL_PRIMARY'];

		$output = $curl->curl_req($url, "post", $json_body, $headers);

		$lead->push_count_c += 1;
		$lead->save();
		$message = json_decode($output);

		if (empty($message) || $message == "") {
			$url = $sugar_config['EOS_API_URL_SECONDARY'];
			$output = $curl->curl_req($url, "post", $json_body, $headers);
			$lead->push_count_c_c += 1;
			$lead->save();
			$message = json_decode($output);
		}
		if (preg_match("/Success/", $message->{'Message'})) {
			$lead->pushed_lead_c = explode("_", $message->{'Message'})[1];
			$time = date("Y-m-d H:i:s");
			$time = strtotime($time) - (330 * 60);
			$lead->date_sent_to_EOS_c = date("Y-m-d H:i:s", $time);
			$lead->sent_count += 1;
			$lead->save();
		}
		if ($message->{'Message'} == "Mobile Number already Exist.") {
			$lead->pushed_lead_c = -1;
			$lead->save();
		}
		if ($message->{'Message'} == "The field Mobile_Number must be a string or array type with a minimum length of '10'.") {
			$lead->pushed_lead_c = -2;
			$lead->save();
		}

		$logger->log('debug', 'Final Json Response: ' . print_r($message, true));
	}

	//return true for completed
	return true;
}
