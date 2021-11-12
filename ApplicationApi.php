<?php

if (!defined('sugarEntry') || !sugarEntry) {
    define('sugarEntry', true);
}
// require_once('include/entryPoint.php');
require_once('custom/include/CurlReq.php');

class ApplicationApi {

    public function getAppData($app_id, $apiUrl) {

        $curl_req = new CurlReq();
        $as_api_base_url = getenv('SCRM_AS_API_BASE_URL');

        $url = $as_api_base_url . $apiUrl . $app_id;
        $response = $curl_req->curl_req($url);

        $logger = new CustomLogger('AS_APIs');
        $logger->log('debug', " Application API Url --> $url");
        $logger->log('debug', var_export($response, true));

        return $response;
    }

    public function getApplicationByPhoneNumber($phoneNumber) {
        $curl_req = new CurlReq();
        $as_api_base_url = getenv('SCRM_AS_API_BASE_URL');
        $url = $as_api_base_url . "/get_applications_by_phone?mobile=$phoneNumber";
        $response = $curl_req->curl_req($url);

        $logger = new CustomLogger('AS_APIs');
        $logger->log('debug', " Application API Url --> $url");
        $logger->log('debug', var_export($response, true));

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
