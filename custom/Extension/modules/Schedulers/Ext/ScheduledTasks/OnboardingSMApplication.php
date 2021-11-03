<?php

require_once('custom/include/CurlReq.php');
require_once 'custom/CustomLogger/CustomLogger.php';

$curl_req = new CurlReq();

$job_strings[] = 'OnboardingSMApplication';
date_default_timezone_set('Asia/Kolkata');

function OnboardingSMApplication() {
    
    $previousDayDate = date('Y-m-d', strtotime('-1 day'));
    
    $url = getenv('SCRM_AS_API_BASE_URL')."/crm/get_disbursed_loans?date=$previousDayDate";

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
    
    $logger = new CustomLogger('importingSmApplicationsError');
    $logger->log('debug', date('Y-m-d h:i:s'));
    $logger->log('debug', $url);
    $logger->log('debug', var_export($result, true));
    
    $responseArray = json_decode($sBody, true);
    if (!empty($responseArray) && $responseArray['status'] != "failed") {
        foreach ($responseArray as $key => $value) {
            $data = $value;
            if (!empty($data)) {
                $applicationId = $data['ApplicationID'];
                global $db;
                require_once('ApplicationApi.php');
                $applicationApis = new ApplicationApi();
                $advance_amount = "";
                $processingFees = 0;
                $gstOnProcessingFee = 0;
                $getApplicationDetailsApi = $applicationApis->getAppData($applicationId, "/get_application_deal_details?ApplicationID=");
                $json_response = json_decode($getApplicationDetailsApi, true);
                if(!empty($json_response) && count($json_response)>0){
                    $advance_amount = $json_response[0]['Advance Amount'];
                }
                
                $getCasesQuery = "SELECT * FROM smacc_sm_account where app_id = $applicationId";
                $response = $db->query($getCasesQuery);
                if ($response->num_rows <= 0) {
                    $merchantname = $data['MerchantName'];
                    $contactNumber = $data['Mobile'];
                    $openingDpdDashGroup = $data['OpeningDPDDashGroup'];
                    $dpdDash = $data['DPDDash'];
                    $lbal = $data['LBAL'];
                    $dVariance = $data['DVariance'];
                    $fundingDateWithTime = $data['FundedDate'];
                    $disbursalDateWithTime = $data['PaidOutDate'];
                    $branch = $data['Branch'];
                    $region = $data['Region'];
                    $customerId = $data['CustomerID'];
                    $repaymentMode = $data['Repaymentmode'];
                    $regularised = $data['Regularised'];
                    $repaymentFrequency = $data['RepaymentFrequency'];
                    $processingFees = $data['ProcessingFee'];
                    $gstOnProcessingFee = $data['GSTOnProcessingFee'];

                    $explodeFundingDate = explode("T", $fundingDateWithTime, 2);
                    $fundingDate = $explodeFundingDate[0];

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
                    
                    $dateFormat = new DateTime($fundingDateWithTime);
                    $fundingDateWithTimeFormatted = $dateFormat->format('Y-m-d H:i:s'); 
                   
                    
                    $logger->log('debug', "Funded date ---> " . $fundingDateWithTimeFormatted);
                    $logger->log('debug', "Processing Fee --> ". $processingFees);
                    $logger->log('debug', "GST on Processing Fee --> ". $gstOnProcessingFee);

                    $smAccountBean = BeanFactory::newBean('SMAcc_SM_Account');
                    $smAccountBean->app_id = $applicationId;
                    $smAccountBean->merchant_name = $merchantname;
                    $smAccountBean->contact = $contactNumber;
                    $smAccountBean->lbal = $lbal;
                    $smAccountBean->d_varinace = $dVariance;
                    $smAccountBean->opening_dpd_dash_group = $openingDPDDashGroup;
                    $smAccountBean->current_dpd_dash_group = $currentDPDDashGroup;
                    $smAccountBean->d_dpd_dash = $derivedDPDDash;
                    $smAccountBean->is_active_c = "1";
                    $smAccountBean->branch = $branch;
                    $smAccountBean->region = $region;
                    $smAccountBean->customer_id = $customerId;
                    $smAccountBean->repayment_mode = $repaymentMode;
                    $smAccountBean->regularised_c = $regularised;
                    $smAccountBean->repayment_frequency = $repaymentFrequency;
                    $smAccountBean->email_id = $data['EmailID'];
                    $smAccountBean->constitution = $data['Constitution'];
                    $smAccountBean->welcome_call_status = 'IN_PROGRESS';
                    $smAccountBean->advance_amount = $advance_amount;//$data[''];
                    $smAccountBean->total_repayment_amount = $data['Repay'];
                    $smAccountBean->loan_tenure = $data['Tenure'];
                    $smAccountBean->rate_of_interest = $data['Flat'];
                    $smAccountBean->funded_date = $fundingDateWithTimeFormatted;
                    $smAccountBean->processing_fee = $processingFees+$gstOnProcessingFee;
                    $processing_fees = $processingFees+$gstOnProcessingFee;

    
                    $url4 = getenv('SCRM_AS_API_BASE_URL')."/get_merchant_details?ApplicationID=".$applicationId;
                    $json_response = json_decode($url4, true);
				    if(!empty($json_response) && count($json_response)>0){
                        $applicant_scheme = $json_response[0]['Scheme'];
                        $sub_scheme = $json_response[0]['Sub Scheme'];
                        $first_app_id = $json_response[0]['First Application Id'];
                        $isbalancetranfer = $json_response[0]['Is Balance Transfer'];
                        }
                        $smAccountBean->scheme=$applicant_scheme;
                        $smAccountBean->sub_scheme=$sub_scheme;
                        $smAccountBean->isbalancetransfer=$isbalancetranfer;
                        

                    # ONBOARDING RESTRUCTURE CONDITION - CSI - 648 
                    $url2=getenv('SCRM_AS_API_BASE_URL')."/get_control_program?ApplicationID=$applicationId";
                    $json_response = json_decode($url2, true);
                    if(!empty($json_response) && count($json_response)>0){
                        $control_program = $json_response[0]['controlProgram'];
                    }
                    $control_program=strtolower($control_program);

                    # Ambit 
                    $url_ambit=getenv('SCRM_AS_API_BASE_URL')."/applications/is_bc_app_id?application_id=$appid";
                    $json_response = json_decode($url_ambit, true);
                  
                    $is_ambit = 0;

                    if(!empty($json_response) && count($json_response)>0){

                        if($json_response['is_bc_app_id'] == 1){

                            $is_ambit=1;
                            
                        } 
                    }

                    if($control_program!="restructure" && $applicant_scheme!="COVID 19" && $is_ambit != 1)
                    {
                        $smAccountBean->save();
                    }
                   
                }
            }
        }
    }
}


?>