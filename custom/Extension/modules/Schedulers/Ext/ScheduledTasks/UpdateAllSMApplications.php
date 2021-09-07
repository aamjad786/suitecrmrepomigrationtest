<?php

$job_strings[] = 'UpdateAllSMApplications';
date_default_timezone_set('Asia/Kolkata');
    

function UpdateAllSMApplications() {

    if (!defined('sugarEntry'))
        define('sugarEntry', true);
    require_once('include/entryPoint.php');
    require_once('custom/include/SendEmail.php');
    global $db;
    $getAppIdsQuery = "SELECT id, app_id, rate_of_interest, funded_date, deleted  from smacc_sm_account where (rate_of_interest IS NULL OR funded_date IS NULL) AND deleted = '0'";
    $appIds = $db->query($getAppIdsQuery);
    $appIDArray = "";
    $arrayOfAppIds = [];

    while ($row = $db->fetchByAssoc($appIds)) {
        array_push($arrayOfAppIds, $row['app_id']);
    }
    $size = sizeof($arrayOfAppIds);
    $maxCount = $size / 300;

    $min = 0;
    $max = 300;
    $count = 0;

    while ($count <= $maxCount) {
        for ($i = $min; $i < $max; $i++) {
            if(!empty($arrayOfAppIds[$i])){
                $appIDArray .= "'" . $arrayOfAppIds[$i] . "',";
            }
        }
        $min = $min + 300;
        $max = $min + 300;
        if ($max > $size) {
            $max = $size;
        }
        $update = updateFromAS($appIDArray);
        $appIDArray = "";
        $count++;
    }
    
    return true;
}

function updateFromAS($appIDArray) {
    global $db;
    if (!empty($appIDArray)) {

        $url = getenv('SCRM_AS_API_BASE_URL') . "/crm/get_disbursed_loans?application_id=[$appIDArray]";

        $cSession = curl_init();
        $requestHeaders = array();
        $requestHeaders[] = 'Authorization: Basic bmVvZ3Jvd3RoOmNSbUBuZTBnUjB3dGg=';
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_VERBOSE, 0);
        curl_setopt($cSession, CURLOPT_HEADER, true);
        curl_setopt($cSession, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($cSession, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($cSession);

        $httpCode = curl_getinfo($cSession, CURLINFO_HTTP_CODE);
        $aHeaderInfo = curl_getinfo($cSession);
        $curlHeaderSize = $aHeaderInfo['header_size'];
        $sBody = trim(mb_substr($result, $curlHeaderSize));
        $ResponseHeader = explode("\n", trim(mb_substr($result, 0, $curlHeaderSize)));
        unset($ResponseHeader[0]);
        //Parsing response
        $responseArray = json_decode($sBody, true);
        if (!empty($responseArray) && $responseArray['status'] != "failed") {
            foreach ($responseArray as $key => $value) {
                $data = $value;
                $processingFees = 0;
                $gstOnProcessingFee = 0;
                if (!empty($data)) {
                    $applicationId = $data['ApplicationID'];
                    $openingDpdDashGroup = $data['OpeningDPDDashGroup'];
                    $dpdDash = $data['DPDDash'];
                    $lbal = $data['LBAL'];
                    $dVariance = $data['DVariance'];
                    $repaymentMode = $data['Repaymentmode'];
                    $regularised = $data['Regularised'];
                    $repaymentFrequency = $data['RepaymentFrequency'];
                    $fundingDateWithTime = $data['FundedDate'];
                    $processingFees = $data['ProcessingFee'];
                    $gstOnProcessingFee = $data['GSTOnProcessingFee'];
                    
                    $explodeFundingDate = explode("T", $fundingDateWithTime, 2);
                    $fundingDate = $explodeFundingDate[0];
                    
                    $dateFormat = new DateTime($fundingDateWithTime);
                    $fundingDateWithTimeFormatted = $dateFormat->format('Y-m-d H:i:s'); 

                    $explodedisbursalDate = explode("T", $disbursalDateWithTime, 2);
                    $disbursalDate = $explodedisbursalDate[0];
                    $diff = date_diff(date_create($disbursalDate), date_create($fundingDate));
                    $gracePeriod = $diff->format("%R%d");

                    $derivedDPDDash = $dpdDash - $gracePeriod;

                    if ($derivedDPDDash <= 0) {
                        $currentDPDDashGroup = "0";
                    } else if ($derivedDPDDash > 0 && $derivedDPDDash <= 4) {
                        $currentDPDDashGroup = "1-4";
                    } else if ($derivedDPDDash >= 5 && $derivedDPDDash <= 6) {
                        $currentDPDDashGroup = "5-6";
                    } else if ($derivedDPDDash >= 7 && $derivedDPDDash <= 10) {
                        $currentDPDDashGroup = "7-10";
                    } else {
                        $currentDPDDashGroup = "11 and above";
                    }

                    $openingDPDDash = $data['OpeningDPDDash'];
                    if ($openingDPDDash < 0 || $openingDPDDash == "0.0") {
                        $openingDPDDashGroup = "0";
                    } else if ($openingDPDDash >= 1 && $openingDPDDash <= 4) {
                        $openingDPDDashGroup = "1-4";
                    } else if ($openingDPDDash >= 5 && $openingDPDDash <= 6) {
                        $openingDPDDashGroup = "5-6";
                    } else if ($openingDPDDash >= 7 && $openingDPDDash <= 10) {
                        $openingDPDDashGroup = "7-10";
                    } else {
                        $openingDPDDashGroup = "11 and above";
                    }

                    $getAppDataQuery = "SELECT id from smacc_sm_account where app_id = $applicationId";
                    $applicationData = $db->query($getAppDataQuery);
                    while ($row = $db->fetchByAssoc($applicationData)) {
                        $id = $row['id'];
                    }
                    $smAccountBean = new SMAcc_SM_Account;
                    $smAccountBean->retrieve($id);

                    $smAccountBean->date_modified = false;
//                    $smAccountBean->opening_dpd_dash_group = $openingDPDDashGroup;
//                    $smAccountBean->current_dpd_dash_group = $currentDPDDashGroup;
//                    $smAccountBean->d_dpd_dash = $derivedDPDDash;
//                    $smAccountBean->lbal = $lbal;
//                    $smAccountBean->repayment_mode_c = $repaymentMode;
//                    $smAccountBean->regularised_c = $regularised;
//                    $smAccountBean->repayment_frequency_c = $repaymentFrequency;
//                    $smAccountBean->d_varinace = $dVariance;
                    $smAccountBean->funded_date = $fundingDateWithTimeFormatted;
                    $smAccountBean->rate_of_interest = $data['Flat'];
                    $smAccountBean->processing_fee = $processingFees+$gstOnProcessingFee;

                    $smAccountBean->save();
                }
            }
        }
    }
}
?>