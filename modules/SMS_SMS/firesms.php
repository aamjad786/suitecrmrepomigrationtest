<?php
/*
* This class provides methods for interacting with the
* {@link http://www.firetext.co.uk/api/ FireText} API. *
* @copyright Copyright (c) 2010 {@link http://www.firetext.co.uk
* FireText Communications Ltd.}
*/
class FireText
{
	const API_CLIENT_VERSION = '2.1';
	const API_URL = 'http://www.firetext.co.uk/api';
	const PATH_CREDITS = '/credit';
	const PATH_SENDSMS = '/sendsms';
	const PATH_SUBSCRIBE = '/subscribe';
	const PATH_TRANSFER = '/transfercredit';
	const PATH_SUBACCOUNT = '/addsubaccount';
	const PATH_SENTMESSAGES = '/sentmessages';
	const PATH_RECEIVEDMESSAGES = '/receivedmessages';
		
	/*
	* FireText account username
	* @var string
	*/
	static $username = '';
	/*
	* FireText account password
	* @var string
	*/
	static $password = '';
	/*
	* Set FireText username and password
	*
	* @param string $username FireText username
	* @param string $password FireText password
	*/
	public static function SetAuth($username, $password)
	{
		self::$username = $username;
		self::$password = $password;
	}
	/*
	* Sends an HTTP request to the FireText API with Basic authentication.
	* * @param string $uri Target URI for this request (relative to the API root)
	* @param mixed $data x-www-form-urlencoded data (or array) to be sent in
	* a POST request body
	* @param string $method Specifies the HTTP method to be used for this request
	* @return FireTextResponse
	*/
	public static function sendRequest($uri, $data = '', $method = 'POST')
	{
		$ch = curl_init();
		if(!is_array($data))
		{
			parse_str($data, $data);
		}
		
		if( $uri == "/sendsms" ) { 
			$data[ "message" ] = htmlspecialchars_decode( $data[ "message" ], ENT_QUOTES ); 
		}
		$data['username'] = self::$username;
		$data['password'] = self::$password;
		if('POST' == strtoupper($method))
		{
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		else
		{
			$uri .= (strstr($uri,'?')?'&':'?') . http_build_query($data);
		}

		curl_setopt($ch, CURLOPT_URL, self::API_URL . $uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:', "User-Agent: FireText/".self::API_CLIENT_VERSION." PHP/:".phpversion()."Client/" . self::$username));
		// parse FireText response
		$result = new StdClass();
		$result->response = curl_exec($ch);
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
			$result->code = strstr($result->response, ':', true);
			$data = substr(strstr($result->response, ':'), 1);
			$result->data = $data[0] == ' ' ? false : strstr($data, ' ', true);
			$result->status = substr(strstr($data, ' '), 1);
		}else{
			$vcode = explode(':', $result->response);
			$result->code = $vcode[0];
			$data = substr(strstr(":".$vcode[1], ':'), 1);
			$data1 = $data[0] == ' ' ? false : explode(' ', $data);
			$result->data = $data1[0];
			$result->status = substr(strstr($data, ' '), 1);
		}		
		
		// parse sent messages
		if(!empty($result->items))
		{
			array_shift($result->items);
			//array_ walk($result->items, create _function('&$v,$k', 'parse_str($v,$v);'));
		}
		curl_close($ch);
		return $result;
	}
	/*
	* Sends a HTTP credit request.
	* @return FireTextResponse
	*/
	public static function credits()
	{
		return self::sendRequest(self::PATH_CREDITS);
	}

	/*
	* Sends a HTTP subscribe request.
	* @param mixed!$data!x-www-form-urlencoded data (or array)
	* @return FireTextResponse*/
	public static function subscribe($data)
	{
		return self::sendRequest(self::PATH_SUBSCRIBE, $data);
	}
	/*
	* Sends a HTTP send sms request.
	* @param mixed!$data!x-www-form-urlencoded data (or array)
	* @return FireTextResponse*/
	public static function sendSms($data)
	{
		return self::sendRequest(self::PATH_SENDSMS, $data);
	}
	/*
	* Retrieve sent messages.
	* @param mixed $data x-www-form-urlencoded data (or array)
	* @return FireTextResponse
	*/
	public static function sent($data)
	{
		return self::sendRequest(self::PATH_SENTMESSAGES, $data);
	}
	/*
	* Transfer credit to a subaccount.
	* @param mixed $data x-www-form-urlencoded data (or array)
	* @return FireTextResponse
	*/
	public static function transfer($data)
	{
		return self::sendRequest(self::PATH_TRANSFER, $data);
	}
	/*
	* Add a subaccount.
	* @param mixed $data x-www-form-urlencoded data (or array)
	* @return FireTextResponse
	*/
	public static function add($data)
	{
		return self::sendRequest(self::PATH_SUBACCOUNT, $data);
	}
	/*
	* Sends a HTTP received sms request.
	* @param mixed!$data!x-www-form-urlencoded data (or array)
	* @return FireTextResponse*/
	public static function receivedSms($data)
	{
		return self::sendRequest(self::PATH_RECEIVEDMESSAGES, $data);
	}
}
?>
