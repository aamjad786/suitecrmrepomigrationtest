<?php
require_once('CurlReq.php');

//API to fetch the dat from AS
$asApplicationId = $this->bean->as_application_id_c;
//$asApplicationId = 1009692;
//paylater leads
/*if (!empty($asApplicationId)) {
    $asMapping = array('Personal PAN of Applicant' => 'pan',
        'Aadhaar of Applicant' => 'aadhaar_application',
        'Business Address Proof' => 'bus_add_proof',
        'Business Registration Proof' => 'bus_reg_proof',
        'Business PAN' => 'business_pan',
        'Bank Statement' => 'bank_statement',
        'Business Constitution Proof' => 'business_constitution_proof',
        'GST Returns' => 'gst_returns',
        'Audited Financials' => 'audited_financials',
        'Any Other Document' => 'others',
        'Remark' => 'remark'
    );
    $as_api_url = getenv('SCRM_AS_API_BASE_URL');
//$as_api_url = "https://dev.advancesuite.in:3003";
    $curl_req = new CurlReq();
    $url = $as_api_url . "/getSanctionTermsAndDocument?ApplicationID=" . $asApplicationId;
    $header = array(
        "authorization: Basic bmVvZ3Jvd3RoOmNSbUBuZTBnUjB3dGg=",
    );
    $respose = $curl_req->curl_req($url, 'get', null, $header);

    $responseArray = json_decode($respose, true);
}*/
?>