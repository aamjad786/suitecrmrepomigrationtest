<?php
if(!defined('sugarEntry') || !sugarEntry) {
	define('sugarEntry', true);
}
// require_once('include/entryPoint.php');

class CurlReq {

	public function curl_req($url,$type='get',$params="",$headers=""){
		if(empty($url))return;
		$ch = curl_init();
		if(empty($headers)){
			$headers  = [
                'Content-Type: application/json'
            ];
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		if($type=='get'){
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
		}
		else {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		if(!empty($headers)){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output = curl_exec($ch);
		// print_r($output);
		curl_close($ch);
		return $output;
	}

}
