<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');
global $sugar_config;
set_time_limit(0);

$application_id = $_REQUEST['application_id'];
$email_id = $_REQUEST['email_id'];

if(!empty($_REQUEST['user_email'])){
	if(!empty($email_id)){
		$email_id.=','.$_REQUEST['user_email'];
	} else {
		$email_id.=$_REQUEST['user_email'];
	}
}

$document_type = intval($_REQUEST['document_type']);
$from_month = null;
$to_month = null;
$establishment = $_REQUEST['establishment'];
if(array_key_exists('from_month', $_REQUEST)){
	$from_month = $_REQUEST['from_month'];	
}
if(array_key_exists('to_month', $_REQUEST)){
	$to_month = $_REQUEST['to_month'];	
}
$submit = $_REQUEST['submit'];
$response['message'] = '';
$response['error'] = '';

$document_master = array(1=>"Loan statement",2=>'Interest Certificate',3=>'Repayment Schedule', 4=>'Sanction Letter',6=>'Welcome Letter',7=>'Loan Agreement');
$document_name = $document_master[$document_type];

$myfile = fopen("Logs/send_merchant_email.log", "a");
fwrite($myfile,"\n***********starting here******************");
require_once('CurlReq.php');
$curl_req = new CurlReq();
fwrite($myfile, "\nRequest params = ".print_r($_REQUEST,true));
if(!empty($application_id)){
	if(!empty($document_type) && !empty($submit) &&!empty($email_id)) {
		global $db;
		global $current_user;
		$status = 'Not sent';
		$user_name = $current_user->user_name;
		
		if(in_array($document_type, array(1,2,3,4))){
			
			$env = getenv('SCRM_ENVIRONMENT');
			if($env != 'prod'){
				$email_id = 'nikhil.kumar@neogrowth.in';
			}
			
			$data = ['application_id'=>$application_id,
					'document_type'=>$document_type,
					'from_date'=>$from_month,
					'to_date'=>$to_month,
					'email'=>$email_id,
					'application'=>'CRM'
					];
			$headers=array("Content-Type :multipart/form-data");
			$url = getenv('AWS_API_UTILITY_URL')."/documents";
			$res = $curl_req->curl_req($url,'post',$data,$headers);
			fwrite($myfile,"Response = ".print_r($res,true));
			$reference = null;
			if($res){
				$json_response = json_decode($res);
				// var_dump($json_response);
				if(!empty($json_response) && $json_response->status=="initiated"){
					$reference = $json_response->reference;

					$response['message'] = "<span style='color:green'><b>$document_name sent successfully to customer.</b></span>";

					$status = 'Sent';
						
				}else{
						$response['error'] = "Some error occured";
						$status = 'Not sent';
				}
			}else{
				$response['error'] = "Unable to send mail to $application_id. Please contact IT team";
				$status = 'Not sent';
			}
			

			
		}else if (in_array($document_type, array(6,7))){
			$secret_key = getenv('SCRM_AS_SECRET_KEY');
                        if($document_type == 6){
                            $type = "WelcomeMail";
                        } else if($document_type == 7){
                            $type = "loanagreementemail";
                        }
                        $str = "$type~$application_id~$secret_key";
			$secret = sha1($str);

			$url =  getenv('SCRM_AS_URL')."/api/CustomAPI/CallCustomAPI?Type=$type&VerificationKey=$secret&ApplicationId=$application_id";
			fwrite($myfile,"\nURL=$url");
			$res = $curl_req->curl_req($url);
			$obj = json_decode($res);
			fwrite($myfile,"\nResponse = $res");
			if($obj->IsSuccess==1){
				$response['message'] = "<span style='color:green'><b>$document_name sent successfully to customer.</b></span>";
				$status = 'Sent';
		 	}else{
		 		$response['error'] = "<span style='color:red'><b>$Some error occured while sending $document_name.</b></span>";
		 		$status = 'Not sent';
		 	}

		}
		$query = "insert into statement_tracker (doc_type,user_name,app_id,start_date,end_date,refer,establishment,status) values (";
			$query .= "$document_type,'$user_name','$application_id','$from_month','$to_month','$reference','$establishment','$status')";
			fwrite($myfile,"\nQuery = $query");
			$db->query($query);
	}else if(!empty($submit) && empty($document_type)) {
		$response['error'] =  "Please select a valid Document type. ";
	}else if (!empty($submit) && empty($email_id)) {
		$response['error'] =  "Email id of the merchant cannot be blank. ";
	}
}else{
	$response['error'] = "Empty Application ID. ";
}
echo json_encode($response);

