<?php
require_once 'custom/CustomLogger/CustomLogger.php';
$job_strings[] = 'ImportingRegularizationCall';
global $logger;
$logger= new CustomLogger('Regularization');
date_default_timezone_set('Asia/Kolkata');

function ImportingRegularizationCall() {
global $db,$logger;
$logger= new CustomLogger('Regularization');
$previousDayDate = date('Y-m-d', strtotime('-1 day'));

  $url = getenv('SCRM_AS_API_BASE_URL')."/crm/get_regularised_details";

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
    $logger->log('debug', "\n".date('Y-m-d h:i:s'));
    $logger->log('debug', $url);
    $logger->log('debug', var_export($result, true));
    
    $responseArray = json_decode($sBody, true);
    if (!empty($responseArray) && $responseArray['status'] != "failed") {
        foreach ($responseArray['regularised_details'] as $key => $value) {
            $data = $value;
            if (!empty($data)) {
                $applicationId = $data['application_id'];
                global $db;
                require_once('ApplicationApi.php');
                $applicationApis = new ApplicationApi();
               
                //https://uat.advancesuite.in:3003/crm/get_regularised_details?app_id=1000025

                $regularizationBean  = BeanFactory::getBean('reg_regularization')->get_full_list('',"app_id=$applicationId");
                if (empty($regularizationBean))
                {
                    $regularizationBean = BeanFactory::newBean('reg_regularization');
                    $regularizationBean->app_id=$applicationId;
                }
                else{
                    $regularizationBean=$regularizationBean[0];
                }

                    $url = getenv('SCRM_AS_API_BASE_URL')."/crm/get_disbursed_loans?application_id=$applicationId";

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


                    
                    //$getApplicationDetailsApi = $applicationApis->getAppData($applicationId, "/crm/get_regularised_details?app_id=");
                    $json_response = json_decode($sBody, true);

                    $logger->log('debug', print_r($json_response,true));
                    //$json_response = json_decode($url2, true);

                    if(!empty($json_response) && count($json_response)>0){

                        $json_response = $json_response[0];

                        $regularizationBean->merchant_name=$json_response['MerchantName'];
                        $regularizationBean->phone = $json_response['Mobile'];
                        $regularizationBean->branch = $json_response['Branch'];
                        $regularizationBean->email = $json_response['EmailID'];

                        
                    }

                    $is_regularised      = $data['regularisation_status'];

                    $regularizationBean->regularization_date = $data['regularisation_date'];

                    $regularizationBean->regularized = $is_regularised;
                        
                    $regularizationBean->cam_name = $data['cam_name'];
                    $regularizationBean->emi = $data['emi'];

                    $mid_detail = $data['mid_details'];
                        
                    $mid =  implode(",", array_column($mid_detail, "MID"));
                        
                    $regularizationBean->mid = $mid;

                    $processor_name =  implode(",", array_column($mid_detail, "processor_name"));
                        
                    $regularizationBean->processor_name = $processor_name;

                    $tid =  implode(",", array_column($mid_detail, "TID"));

                    $regularizationBean->tid = $tid;

                    $terminal_maker =  implode(",", array_column($mid_detail, "TerminalMakerName"));

                    $regularizationBean->terminal_maker = $terminal_maker;

                    $logger->log('debug', print_r($regularizationBean->app_id,true));
                        

                

                    $url2=getenv('SCRM_AS_API_BASE_URL')."/get_control_program?ApplicationID=$applicationId";
                    $json_response = json_decode($url2, true);
                        if(!empty($json_response) && count($json_response)>0){
                        $control_program = $json_response[0]['controlProgram'];
                    }
                    $control_program=strtolower($control_program);

                    if(!empty($is_regularised) && $control_program!="restructure")
                    {
                        $regularizationBean->save();
                    }
                   
                
            }
        }
    }
}


?>