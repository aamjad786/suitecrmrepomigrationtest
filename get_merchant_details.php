<?php 
if(!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');
global $sugar_config;

$application_id = $_REQUEST['application_id'];
$as_api_url = getenv('SCRM_AS_API_BASE_URL');

if (!empty($application_id) && !empty($as_api_url)) {
	$res = curl_req($as_api_url."/get_merchant_details?ApplicationID=".$application_id);
	if($res){
		echo $res;
	}
}

function curl_req($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}