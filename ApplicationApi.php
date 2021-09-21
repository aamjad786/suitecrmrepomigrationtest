<?php

if (!defined('sugarEntry') || !sugarEntry) {
    define('sugarEntry', true);
}
// require_once('include/entryPoint.php');
require_once('CurlReq.php');

class ApplicationApi {

    public function getAppData($app_id, $apiUrl) {

        $curl_req = new CurlReq();
        $as_api_base_url = getenv('SCRM_AS_API_BASE_URL');

        $url = $as_api_base_url . $apiUrl . $app_id;
        $response = $curl_req->curl_req($url);
        $myfile = fopen("Logs/ApplicationApi.log", "a");
        fwrite($myfile, "\n \n Application API Url --> $url\n");
        fwrite($myfile, var_export($response, true));

        return $response;
    }

    public function getApplicationByPhoneNumber($phoneNumber) {
        $curl_req = new CurlReq();
        $as_api_base_url = getenv('SCRM_AS_API_BASE_URL');
        $url = $as_api_base_url . "/get_applications_by_phone?mobile=$phoneNumber";
        $response = $curl_req->curl_req($url);
        if ($response) {
            $json_response = json_decode($response, true);
            if (!empty($json_response)) {
                $app_id = $json_response[0];
                return $app_id;
            }
        }
        return;
    }

}
