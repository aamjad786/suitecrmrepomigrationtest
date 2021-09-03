<?php
require_once('include/entryPoint.php');
class Ng_utils {

    public $messages = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',

        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',  // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',

        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    
    function test(){
        echo "working";
    }

    function prepareToDate($request_date){
        if(empty($request_date)){
            $time_date = new TimeDate();
            $to_date = $time_date->nowDb();
        }
        else{
            $to_date = date_create($request_date);
            $to_date = date_format($to_date,"Y-m-d 23:59:59");
        }
        $GLOBALS['log']->debug("Custom Report Current DB time is (to date for query)" . $to_date);
        return $to_date;
    }

    function fetchRoleIdFromName($role_name){
        global $db;
        $query = "select id from acl_roles where name = '$role_name'";
        $results = $db->query($query);
        $role_id = "";
        while($row = $db->fetchByAssoc($results)){
            $role_id = $row['id'];
        }
        return $role_id;
    }

    function getUserNameForRole($roleId=null, $roleName=null){
        if(empty($roleId)&&empty($roleName)){
            return null;
        }
        if(empty($roleId) && !empty($roleName)){
            $roleId = $this->fetchRoleIdFromName($roleName);
        }
        $query = "SELECT users.user_name, users.id, concat(users.first_name,' ',users.last_name) as name ".
            "FROM users ".
            "INNER JOIN acl_roles_users ON acl_roles_users.role_id = '$roleId' ".
                "AND acl_roles_users.user_id = users.id AND acl_roles_users.deleted = 0 ".
            "WHERE users.deleted=0 ";

        $result = $GLOBALS['db']->query($query);
        $user_names = array();

        while($row = $GLOBALS['db']->fetchByAssoc($result) ){
                $user = array();
                $user['user_name'] = $row['user_name'];
                $user['id'] = $row['id'];
                $user['name'] = $row['name'];
                $user_names[] = $user;
        }

        return $user_names;
    }

    function prepareMonthFirstDate(){
        // print_r(date('Y-m-01 00:00:00'));
        return date('Y-m-01 00:00:00');
    }

    /*This can be used to send http code where ever we are returning api response
    to other applications*/
    function sendHttpStatusCode($httpStatusCode, $httpStatusMsg) {
        $phpSapiName    = substr(php_sapi_name(), 0, 3);
        if ($phpSapiName == 'cgi' || $phpSapiName == 'fpm') {
            header('Status: '.$httpStatusCode.' '.$httpStatusMsg);
        } else {
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
            header($protocol.' '.$httpStatusCode.' '.$httpStatusMsg);
        }
    }

    function prepareFromDate($request_from_date){
        if(empty($request_from_date)){
            $from_date = $this->prepareMonthFirstDate();
        }
        else{
            try{
                $from_date = date_create($request_from_date);
                $from_date = date_format($from_date,"Y-m-d H:i:s");
            }
            catch(Exception $e) {
              $from_date = $this->prepareMonthFirstDate();
            }
        }
        if(empty($from_date)){
            //if error in creating from_date, sent by default first date of the current month
            $from_date = $this->prepareMonthFirstDate();
        }
        $GLOBALS['log']->debug("Custom Report Current DB time is (from date for query)" . $from_date);
        return $from_date;
    }

    function prepareFromDateUsingTo($request_from_date,$request_to_date){
    	if(empty($request_from_date)){
    		$from_date = date_create($request_to_date);
			date_modify($from_date,"-30 days");
			$from_date = date_format($from_date,"Y-m-d H:i:s");
    	}
    	else{
    		$from_date = date_create($request_from_date);
    		$from_date = date_format($from_date,"Y-m-d H:i:s");
    	}
    	$GLOBALS['log']->debug("Custom Report Current DB time is (from date for query)" . $from_date);
    	return $from_date;
    }

    function exportCsv($header_list, $csv_rows){
		$timestamp = date('Y_m_d_His'); 
		ob_end_clean();
		ob_start();	
		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header("Content-Disposition: attachment; filename=renewals_user_analytics_{$timestamp}.csv");

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');
		// output the column headings
		// foreach($this->header_list as $value){
		// 	fputcsv($output, $value);
		// }
		fputcsv($output,$header_list);
		foreach ($csv_rows as $row_data)
		{
			fputcsv($output,$row_data);
		}
		exit;
    }
}


?>