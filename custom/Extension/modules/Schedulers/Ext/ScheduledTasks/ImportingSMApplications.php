<?php

require_once('custom/include/CurlReq.php');
require_once 'custom/CustomLogger/CustomLogger.php';
require_once('custom/include/CurlReq.php');

$job_strings[] = 'ImportingSMApplications';

date_default_timezone_set('Asia/Kolkata');

function ImportingSMApplications() {
    global $sugar_config;
    $previousDayDate = date('Y-m-d', strtotime('-1 day'));
    $url = getenv('SCRM_AS_API_BASE_URL')."/crm/get_disbursed_loans?date=$previousDayDate";

    $headers = array();
    $headers[] = 'Authorization: Basic bmVvZ3Jvd3RoOmNSbUBuZTBnUjB3dGg=';
 

    $curl_req       = new CurlReq();

    $result         = $curl_req->curl_req($url, 'get', '', $headers, '', '', '', '', false, '', true);
    $result   	    = $result['response'];
    $aHeaderInfo    = $result['header'];

    $curlHeaderSize = $aHeaderInfo['header_size'];
    //$sBody = trim(substr($result, $curlHeaderSize));
    $ResponseHeader = explode("\n", trim(mb_substr($result, 0, $curlHeaderSize)));
    unset($ResponseHeader[0]);

    
    $logger = new CustomLogger('importingSmApplicationsError');
    $logger->log('debug', "--- START In importingSmApplication in ScheduledTasks at ".date('Y-m-d h:i:s')."---");

    $logger->log('debug', "curl URL : $url");
    $logger->log('debug', "Result : " . var_export($result, true));
    
    //Parsing response
    $responseArray = json_decode($result, true);
    $logger->log('debug', "response array------------ =:". json_encode($responseArray));
    
    if (!empty($responseArray) && $responseArray['status'] != "failed") {
        
        $logger->log('debug', "responseArray1 :". $responseArray['Application Id']);
        
        foreach ($responseArray as $key => $value) {
        $logger->log('debug', "responseArray :". $responseArray['Application Id']);
            $data = $value;
            $logger->log('debug', "data :".  var_export($data,true));
           
            

            if (!empty($data)) {
                $applicationId = $data['Application Id'];
                $logger->log('debug', "-------application id1-----changed :". $applicationId);
                global $db;
                require_once('ApplicationApi.php');
                $applicationApis = new ApplicationApi();
                $advance_amount = "";
                $processingFees = 0;
                $gstOnProcessingFee = 0;
                $getApplicationDetailsApi = $applicationApis->getAppData($applicationId, "/get_application_deal_details?ApplicationID=");
                $json_response = json_decode($getApplicationDetailsApi, true);
                $logger->log('debug', "-------json response------------ =:". json_encode($json_response ));
                if(!empty($json_response) && count($json_response)>0){
                    $advance_amount = $json_response[0]['Advance Amount'];
                }
                
                $getCasesQuery = "SELECT * FROM smacc_sm_account where app_id = $applicationId ";
                $logger->log('debug', "-------getCasesQuery------------ =:". $getCasesQuery);
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
                    $isBalanceTransfer = $data['IsBalanceTransfer'];
                    $scheme = $data['Scheme'];
                    $subScheme = $data['SubScheme'];

                    $explodeFundingDate = explode("T", $fundingDateWithTime, 2);
                    $fundingDate = $explodeFundingDate[0];
                    $explodedisbursalDate = explode("T", $disbursalDateWithTime, 2);
                    
                    $disbursalDate = $explodedisbursalDate[0];
                    $logger->log('debug', "-------applicationId------------ =:". $applicationId);
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
                   
                    
                    $logger->log('debug', "Funded date ---> " . $fundingDateWithTimeFormatted ."\n");
                    $logger->log('debug', "Processing Fee --> ". $processingFees . "\n");
                    $logger->log('debug', "GST on Processing Fee --> ". $gstOnProcessingFee . "\n");

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
                    $smAccountBean->isbalancetransfer = $isBalanceTransfer;
                    $smAccountBean->scheme=$scheme;
                    $smAccountBean->sub_scheme=$subScheme;
                        
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

                    if($control_program!="restructure" && $applicant_scheme!="COVID 19" && $is_ambit != 1){
                        $smAccountBean->save();
                    }
                }
            }
        }
    }
}


?>