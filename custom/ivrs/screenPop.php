<?php
if($_REQUEST['type']=='INBOUND'){
	$phoneNumber = $_REQUEST['phoneNumber'];
	$app_host = getenv('SCRM_APP_HOST');
 	$as_api_base_url = getenv('SCRM_AS_API_BASE_URL');
    if (strpos($app_host, 'crm') === false) 
        $app_host = 'dev.advancesuite.in';
	$url = $as_api_base_url."/get_applications_by_phone?mobile=$phoneNumber";
	$response = curl_req($url);
	$app_id ="";
	if($response){
		$json_response = json_decode($response);
		$app_id = $json_response[0];
		// var_dump($json_response);
		// die();
	}
	if(!empty($app_id)){
		$url = $as_api_base_url."/get_application_basic_details?ApplicationID=$app_id";
		$response = curl_req($url);
		if(!empty($response)){
			$json_response = json_decode($response);
			$json_response = $json_response[0];
			// var_dump($json_response[0]);
			if(!empty($json_response)){
				$phone_no = $json_response->{'Contact Number'};
				$email_id = $json_response->{'Contact Email ID'};
				$name = $json_response->{'Contact Person Name'};
				$company = $json_response->{'Company Name'};
				$industry = $json_response->{'Industry'};
				$address = $json_response->{'BusinessAddress'};
				echo "<h3>Name:  <i>$name</i></h3>";
				echo "<h3>Email:  <i>$email_id</i></h3>";
				echo "<h3>Phone:  <i>$phone_no</i></h3>";
				echo "<h3>Company:  <i>$company</i></h3>";
				echo "<h3>Industry:  <i>$industry</i></h3>";
				echo "<h3>Address:  <i>$address</i></h3>";

	 		}else{
	 			echo "<h3> No information found in CRM.</h3>";
	 		}
	 	}
	 }

}
 function curl_req($url){
 	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
 }