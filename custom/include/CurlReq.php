<?php
if(!defined('sugarEntry') || !sugarEntry) {
	define('sugarEntry', true);
}
// require_once('include/entryPoint.php');

class CurlReq {

	public function curl_req(	$url,
								$type='get',
								$params='',
								$headers='',
								$useragent='',
								$strCookie='',
								$max_redirects='',
								$timeout='',
								$return_error=false,
								$port='',
								$return_header=false
								){
		if(empty($url))
			return;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		if(empty($headers)){
			$headers  = [
                'Content-Type: application/json'
            ];
		}
		
		if($type == 'get'){
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
		}
		else {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		if(!empty($headers)){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		if(!empty($useragent)){
			curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		}
		if(!empty($strCookie)){
			curl_setopt($ch, CURLOPT_COOKIE, $strCookie);
		}
		if(!empty($max_redirects)){
			curl_setopt($ch, CURLOPT_MAXREDIRS, $max_redirects);
		}
		if(!empty($timeout)){
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		}
		if(!empty($port)){
			curl_setopt($ch, CURLOPT_PORT, $port);
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$output = curl_exec($ch);

		curl_close($ch);

		$logger = new CustomLogger('ALL_APIs');
        $logger->log('debug', "curl URL : $url");

		$logger->log('debug', "request type : $type");
		$logger->log('debug', "Headers : " . var_export($headers, true));
		if(!empty($params))
			$logger->log('debug', "Params : " . var_export($params, true));
		if(!empty($useragent))
			$logger->log('debug', "useragent : " . var_export($useragent, true));
		if(!empty($strCookie))
			$logger->log('debug', "Cookie : " . var_export($strCookie, true));
		if(!empty($max_redirects))
			$logger->log('debug', "max_redirects : " . var_export($max_redirects, true));
		if(!empty($timeout))
			$logger->log('debug', "timeout : " . var_export($timeout, true));
		if(!empty($port))
			$logger->log('debug', "port : " . var_export($port, true));
        $logger->log('debug', "Response : " . var_export($output, true));

		if ($return_error) {
			$error = curl_error($ch);
			return array(	'response'=>$output,
							'error'=>$error
						);
		}
		if ($return_header) {
			$error = curl_getinfo($ch);
			return array(	'response'=>$output,
							'header'=>$error
						);
		}
		return $output;
	}

}
