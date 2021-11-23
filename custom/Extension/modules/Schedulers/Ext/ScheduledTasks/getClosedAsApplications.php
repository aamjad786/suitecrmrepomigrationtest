<?php

$job_strings[] = 'getClosedAsApplications';
date_default_timezone_set('Asia/Kolkata');


function getClosedAsApplications() {
    global $sugar_config;
    $previousDayDate = date('Y-m-d', strtotime('-1 day'));
    $url = $sugar_config['getClosedAsApplications'] . $previousDayDate;
   // $url = "https://dev.advancesuite.in:3003/crm/closed_applications?product=neocash&date=2013-12-02";    
    $curl = new CurlReq();
    $header = array(
        "authorization: Basic bmVvZ3Jvd3RoOmNSbUBuZTBnUjB3dGg=",
      );
    $response = $curl->curl_req($url,'get',null,$header);
    
    $logger = new CustomLogger('AS_APIs');
	$logger->log('debug', "curl URL : $url");
	$logger->log('debug', "Response : " . var_export($response, true));

    $responseArray = json_decode($response);
    if (!empty($responseArray)){
        foreach ($responseArray as $key => $value) {
            $data = $value;
            if (!empty($data)) {
                $applicationId = $data->applicationId;
                global $db;
                $getCasesQuery = "SELECT * FROM social_impact_score where as_app_id = $applicationId";
                $response = $db->query($getCasesQuery);
                if ($response->num_rows <= 0) {
                    $merchantname = $data->company_name;
                    $contactNumber = $data->contact_number;
                    $todaysDate = $date = date('Y-m-d H:i:s');
                    $caseClosedDateRaw = $data->closed_date;
                    $caseClosedDate = date("Y-m-d h:i:s", strtotime($caseClosedDateRaw));
                    $insertQuery = "INSERT INTO `social_impact_score` ( `as_app_id`, `merchant_name`, `merchant_contact_number`,`case_closed_date`, `creation_date`) VALUES ( '$applicationId', '$merchantname', '$contactNumber', '$caseClosedDate', '$todaysDate')";
                    $response = $db->query($insertQuery);
                }
            }
        }
    }
   return true;
}

?>