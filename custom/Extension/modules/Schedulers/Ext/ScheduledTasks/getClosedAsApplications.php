<?php

$job_strings[] = 'getClosedAsApplications';
date_default_timezone_set('Asia/Kolkata');


function getClosedAsApplications() {

    $previousDayDate = date('Y-m-d', strtotime('-1 day'));
    $url = getenv('SCRM_AS_API_BASE_URL') . "/crm/closed_applications?product=neocash&date=$previousDayDate";
   // $url = "https://dev.advancesuite.in:3003/crm/closed_applications?product=neocash&date=2013-12-02";    
    $curl = new CurlReq();
    $header = array(
        "authorization: Basic bmVvZ3Jvd3RoOmNSbUBuZTBnUjB3dGg=",
      );
    $response = $curl->curl_req($url,'get',null,$header);
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