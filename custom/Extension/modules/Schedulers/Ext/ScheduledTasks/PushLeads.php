<?php
require_once 'custom/CustomLogger/CustomLogger.php';
require_once('custom/include/CurlReq.php');

$job_strings[] = 'PushLeads';


function PushLeads()
{

	$logger = new CustomLogger('PushLeadsToEOS');
	$logger->log('debug', 'PushLeadsToEOS Started at ' . date('Y-M-d H:i:s'));

	$bean = BeanFactory::getBean('Opportunities');
	$lead_list = $bean->get_full_list("", "opportunities.date_entered>'2021-02-15' and 
										   opportunities.deleted=0 and 
										   (opportunities_cstm.control_program_c!='NeoCash Insta' or opportunities_cstm.control_program_c is null) and 
										   (opportunities_cstm.pushed_lead_c=0 or opportunities_cstm.pushed_lead_c is null) and 
										   (opportunities_cstm.push_count_c<=5 or opportunities_cstm.push_count_c is null)");
	// $lead_list = $bean->get_full_list("", "opportunities.date_entered>'2021-02-15'");

	// $logger->log('debug', '');
	$logger->log('debug', 'Total leads fetched to process ' . count($lead_list));

	foreach ($lead_list as $lead) {

		//  Skipping Lead if some fields are already present

		$lead_id = $lead->id;
		$lead_bean = BeanFactory::getBean('Leads');
		$list = $lead_bean->get_full_list("", "leads.opportunity_id='$lead_id'");

		if (empty($list[0]->id)) {
			$logger->log('debug', 'Skipping as lead is missing for opportunity ' . $lead_id);
			$lead->push_count = 100;
			$lead->save();
			continue;
		}

		if (($lead->lead_source != 'Marketing') && ($lead->lead_source != 'Alliances')) {
			$lead->push_count = 100;
			$lead->save();
			$logger->log('debug', 'Skipping as lead source is ' . $lead->lead_source);
			continue;
		}

		if (!empty($lead->opportunity_status_c) || ($lead->opportunity_status_c != "" && $lead->opportunity_status_c != " ")) {
			$logger->log('debug', 'Skipping as lead source is ' . $lead->lead_source . ' and Status is not empty!');
			$lead->push_count = 100;
			$lead->save();
			continue;
		}
		if (!empty($lead->application_id_c) || ($lead->application_id_c != "" && $lead->application_id_c != " ")) {
			$logger->log('debug', 'Skipping as lead source is ' . $lead->lead_source . ' and Application id is not empty!');
			$lead->push_count = 100;
			$lead->save();
			continue;
		}
		if (!empty($lead->sub_status) || ($lead->sub_status != "" && $lead->sub_status != " ")) {
			$logger->log('debug', 'Skipping as lead source is ' . $lead->lead_source . ' and Sub-Status is not empty!');
			$lead->push_count = 100;
			$lead->save();
			continue;
		}

		if (!empty($lead->remarks) || ($lead->remarks != "" && $lead->remarks != " ")) {
			$logger->log('debug', 'Skipping as lead source is ' . $lead->lead_source . ' and remarks is not empty!');
			$lead->push_count = 100;
			$lead->save();
			continue;
		}
		if ($lead->sales_stage != "Open" && !empty($lead->sales_stage) && $lead->sales_stage != " ") {
			$logger->log('debug', 'Skipping as lead source is ' . $lead->lead_source . ' and Sales Stage is not empty!');
			$lead->push_count = 100;
			$lead->save();
			continue;
		}

		//  Skipping END;


		// Payload Creation

		$payload = array();
		$payload['Lead_Source'] = $lead->lead_source;
		$payload['Sub_Source'] = $list[0]->sub_source_c;
		$payload['DSA_code'] = $lead->dsa_code_c;
		$payload['First_Name'] = $lead->name;
		$payload['Last_Name'] = "";
		$payload['Mobile_Number'] = $lead->pickup_appointment_contact_c;
		$payload['EmailID'] = $list[0]->email1;
		$payload['Business_Trading_Name'] = $lead->merchant_name_c;
		$payload['Lead_ID'] = $lead->id;
		$payload['City'] = $lead->pickup_appointment_city_c;
		$payload['product'] = $lead->product_type;
		$payload['remarks'] = $lead->remarks;
		$payload['Loan_amount'] = $lead->loan_amount_c;
		$payload['stage_drop_off'] = $lead->stage_drop_off;
		$payload['Address_Street'] = $list[0]->primary_address_street;
		$payload['Address_pin'] = $list[0]->primary_address_postalcode;
		$payload['stage_drop_off'] = $lead->sales_stage == "Sanctioned" ? 'Customer Deal Generated' : '';
		$payload['app_form_link'] = $lead->app_form_link;
		$payload['product_type'] = $lead->product_type;
		$payload['loan_amount_c'] = $lead->loan_amount_c;

		$logger->log('debug', 'Payload: ' . print_r($payload, true));

		$json_body = json_encode($payload);

		// Payload Creation END;

		// Request to EOS API Call

		$curl = new CurlReq();
		$headers  = null;

		global $sugar_config;

		$url = $sugar_config['EOS_API_URL_PRIMARY'];

		$logger->log('debug', 'Before Api call');
		$response = $curl->curl_req($url , "post", $json_body, $headers);

		$jsonResponse = json_decode($response);


		$logger->log('debug', 'After Api call ' . $response);



		$lead->push_count_c += 1;
		$lead->save();

		//  If fails with primary url try with secondary url

		if (empty($jsonResponse) || $jsonResponse == "") {
			$url=$sugar_config['EOS_API_URL_SECONDARY'];
			$output = $curl->curl_req($url, "post", $json_body, $headers);
			$lead->push_count_c += 1;
			$lead->save();
			$jsonResponse = json_decode($output);
		}

		if (preg_match("/Success/", $jsonResponse->{'Message'})) {
			$lead->pushed_lead_c = explode("_", $jsonResponse->{'Message'})[1];
			$time = date("Y-m-d H:i:s");
			$time = strtotime($time) - (330 * 60);
			$lead->date_sent_to_EOS_c = date("Y-m-d H:i:s", $time);
			$lead->save();
		}
		if ($jsonResponse->{'Message'} == "Mobile Number already Exist.") {
			$lead->pushed_lead_c = -1;
			$lead->save();
		}
		if ($jsonResponse->{'Message'} == "The field Mobile_Number must be a string or array type with a minimum length of '10'.") {
			$lead->pushed_lead_c = -2;
			$lead->save();
		}


		$logger->log('debug', 'Final Json Response: ' . print_r($jsonResponse, true));
	}

	$logger->log('debug', 'PushLeadsToEOS END at ' . date('Y-M-d H:i:s'));

	//return true for completed
	return true;
}

