<?php

if (!defined('sugarEntry') || !sugarEntry) {
    define('sugarEntry', true);
}
// require_once('include/entryPoint.php');

class CreateCall {

    function customCreateCall($applicationId, $type, $call = null) {
        require_once('ApplicationApi.php');
        $myfile2 = fopen("Logs/call_data_upload.log",'a');
        fwrite($myfile2, "\nInside Create Call");
        $applicationApis = new ApplicationApi();
        if(empty($call))
            $call = BeanFactory::getBean('Calls');
        //Getting merchant details for the application 
        $getmerchantDetailsApiResponse = $applicationApis->getAppData($applicationId, "/get_merchant_details?ApplicationID=");
        if ($getmerchantDetailsApiResponse) {
            $json_response = json_decode($getmerchantDetailsApiResponse, true);

            if (!empty($json_response) && count($json_response) > 0) {
                $call->contact_number_c = $json_response[0]['Applicant Number'];
                $call->email_id_c= $json_response[0]['Applicant Email Id'];
                $call->branch_c = $json_response[0]['Branch Name'];
                $call->establishment_name_c = $json_response[0]['Company Name'];
            }
        }
        //Getting  Applocation Repayment Details
        $getApplicationRepaymentDetailsApiResponse = $applicationApis->getAppData($applicationId, "/get_application_repaymec_details?ApplicationID=");
        if ($getApplicationRepaymentDetailsApiResponse) {
            $json_response_repayment = json_decode($getApplicationRepaymentDetailsApiResponse, true);
            if (!empty($json_response_repayment) && count($json_response_repayment) > 0) {
                $call->repayment_mode_c = $json_response_repayment[0]['Repayment Mode'];
            }
        }

        //Getting  Application Funding Details
        $getApplicationFundingDetailsApiResponse = $applicationApis->getAppData($applicationId, "/get_application_funding_details?ApplicationID=");
        if ($getApplicationFundingDetailsApiResponse) {
            $json_response_funding = json_decode($getApplicationFundingDetailsApiResponse, true);
            if (!empty($json_response_funding) && count($json_response_funding) > 0) {
                $call->funded_date_c = $json_response_funding[0]['Funded Date'];
            }
        }

        //Getting Application Status
        $getApplicationStatusApiResponse = $applicationApis->getAppData($applicationId, "/get_app_status/?app_id_c=");
        if ($getApplicationStatusApiResponse) {
            $json_response_status = json_decode($getApplicationStatusApiResponse, true);
            if (!empty($json_response_status) && count($json_response_status) > 0) {
                $call->loan_status_c = ($json_response_status['app_status'] == 'Y' ? 'Closed' : 'Active');
            }
        }
        $callType = '';
        if(!empty($type)){
            $callType =  $GLOBALS['app_list_strings']['calls_type_list'][$type];
        }
        $call->name = "$applicationId - $call->contact_number_c - $callType";
        $call->calls_type_c = $type;
        $call->app_id_c = $applicationId;
        $call->direction = 'Outbound';
        $call->status = "Planned";

        $newCallBean = $call->save();
        
        return $newCallBean;
    }

}
