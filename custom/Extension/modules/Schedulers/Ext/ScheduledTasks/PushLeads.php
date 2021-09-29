<?php
require_once 'custom/CustomLogger/CustomLogger.php';
require_once('custom/include/CurlReq.php');

$job_strings[] = 'PushLeads';


function PushLeads()
{

	$logger = new CustomLogger('PushLeadsToEOS-'.date('Y-M-d'));

	$logger->log('debug', 'PushLeadsToEOS Started at ' . date('Y-M-d H:i:s'));

	$bean = BeanFactory::getBean('Leads');

	// $lead_list = $bean->get_full_list("", "leads.deleted=0 and 
	// 									   leads.opportunity_id is null and
	// 									   (leads_cstm.control_program_c!='NeoCash Insta' or leads_cstm.control_program_c is null) and 
	// 									   (leads_cstm.pushed_lead_c=0 or leads_cstm.pushed_lead_c is null) and 
	// 									   (leads_cstm.push_count_c<=5 or leads_cstm.push_count_c is null)");
	
	$lead_list = $bean->get_full_list("", "leads.date_entered>'2021-02-15'");

	// $logger->log('debug', '');
	$logger->log('debug', 'Total Leads Fetched To Process: ' . count($lead_list));

	foreach ($lead_list as $lead) {

		//  Skipping Lead if 

		if(trim($lead->dsa_code_c)=='Nine Group' || trim($lead->lead_source)=='Self Generated'){
			$logger->log('debug', 'Skipping as lead source is ' . $lead->lead_source . ' or DSA Code is '.$lead->dsa_code_c);
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
		

		//  Skipping END;


		// Payload Creation

		$payload = array();
		$payload['Lead_Source'] = $lead->lead_source;
		$payload['Sub_Source'] = $lead->sub_source_c;
		$payload['DSA_code'] = $lead->dsa_code_c;
		$payload['First_Name'] = $lead->name;
		$payload['Last_Name'] = "";
		$payload['Mobile_Number'] = $lead->pickup_appointment_contact_c;
		$payload['EmailID'] = $lead->email1;
		$payload['Business_Trading_Name'] = $lead->merchant_name_c;
		$payload['Lead_ID'] = $lead->id;
		$payload['City'] = $lead->pickup_appointment_city_c;
		$payload['product'] = $lead->product_type;
		$payload['remarks'] = $lead->remarks;
		$payload['Loan_amount'] = $lead->loan_amount_c;
		$payload['Address_Street'] = $lead->primary_address_street;
		$payload['Address_pin'] = $lead->primary_address_postalcode;
		$payload['stage_drop_off'] = $lead->sales_stage == "Sanctioned" ? 'Customer Deal Generated' : $lead->stage_drop_off;
		$payload['app_form_link'] = $lead->app_form_link;
		$payload['product_type'] = $lead->product_type;
		$payload['loan_amount_c'] = $lead->loan_amount_c;

		$json_body = json_encode($payload);
		
		$logger->log('debug', 'Payload: ' . print_r($json_body, true));

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
			$logger->log('debug', 'Lead Is Sent To EOS: ' . $response);
			$lead->pushed_lead_c = explode("_", $jsonResponse->{'Message'})[1];
			$time = date("Y-m-d H:i:s");
			$time = strtotime($time) - (330 * 60);
			$lead->date_sent_to_eos_c = date("Y-m-d H:i:s", $time);
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

PushLeads();