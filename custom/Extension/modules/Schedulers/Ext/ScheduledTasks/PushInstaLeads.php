<?php
require_once 'custom/CustomLogger/CustomLogger.php';
require_once('custom/include/CurlReq.php');

$job_strings[] = 'PushInstaLeads';

function PushInstaLeads()
{

	$logger = new CustomLogger('PushInstaLeadsToEOS');
	$logger->log('debug', 'PushInstaLeadsToEOS Started at ' . date('Y-M-d H:i:s'));


	$bean = BeanFactory::getBean('Leads');

	$DayDate = date('Y-m-d H:i:s', strtotime('-360 minutes'));
	$lead_list = $bean->get_full_list("", "leads.deleted=0 and 
										   leads.opportunity_id is null and
										   leads.date_entered>'2021-04-15' and
										   leads.date_entered<'$DayDate' and 
										   leads_cstm.control_program_c='NeoCash Insta' and 
										   (leads_cstm.push_count_c<=5 or leads_cstm.push_count_c is null) and
										   (leads_cstm.sent_count_c<=2 or leads_cstm.sent_count_c is null)");

	$logger->log('debug', 'Total Insta leads fetched to process ' . count($lead_list));
	
	foreach ($lead_list as $lead) {

		
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


		$arr = array();
		$arr['Lead_Source'] = $lead->lead_source;
		$arr['Sub_Source'] = $lead->sub_source_c;
		$arr['DSA_code'] = $lead->dsa_code_c;
		$arr['First_Name'] = $lead->first_name;
		$arr['Last_Name'] = $lead->last_name;
		$arr['Mobile_Number'] = $lead->phone_mobile;
		$arr['EmailID'] = $lead->email1;
		$arr['Business_Trading_Name'] = $lead->merchant_name_c;
		$arr['Lead_ID'] = $lead->id;
		$arr['City'] = $lead->primary_address_city;
		$arr['product'] = "NeoCash Insta";
		$arr['remarks'] = $lead->remarks;
		$arr['Loan_amount'] = $lead->loan_amount_c;
		$arr['stage_drop_off'] = $lead->stage_drop_off;
		$arr['Address_Street'] = $lead->primary_address_street;
		$arr['Address_pin'] = $lead->primary_address_postalcode;

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
			$logger->log('debug', 'Lead Is Sent To EOS: '.$lead->id);
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

// PushInstaLeads();