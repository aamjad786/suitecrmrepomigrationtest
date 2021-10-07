<?php
if(!defined('sugarEntry')) die('Not a Valid Entry Point');
require_once('SendEmail.php');
//require_once('modules/bhea_Reports/report_utils.php');

class Neo_Paylater_LeadsController extends SugarController {

	public $bucket;//getenv('SCRM_PAYLATER_LEADS_BUCKET');

    function __construct() {
        $this->bucket = getenv('SCRM_PAYLATER_LEADS_BUCKET');
    }

    function action_DocUpload() {
        include_once 'file_upload.php';
        die();
    }

    //all functions are related to paylater leads
    /*function action_upload() {
        require_once('custom/modules/Neo_Paylater_Leads/neoPaylaterFunctions.php');
        $id = ($_REQUEST['beanID']);
        if (empty($id)) {
            echo "Application id is missing, Contact Admin.";
            die();
        }
        $message = "";
        $AS_DOC_upload = $_REQUEST['AS_DOC_upload'];
        $AS_RE_UPLOAD = $_REQUEST['AS_RE_UPLOAD'];
        $uploaded_urls = array();
        $mapping = array('ap_form' => 'application',
            'pan' => 'pan',
            'res_proof' => 'residential_address_proof',
            'bus_add_proof' => 'business_address_proof',
            'bus_reg_proof' => 'business_registration_proof',
            'agreement' => 'agreement',
            'others' => 'others',
            'approved_by_credit_agreement' => 'approved_by_credit_agreement',
            'nach_form' => 'nach_form',
            'cancelled_cheque' => 'cancelled_cheque',
            'application_form' => 'application_form',
            'aadhaar_application' => 'aadhaar_application',
            'business_pan' => 'business_pan',
            'bank_statement' => 'bank_statement',
            'business_constitution_proof' => 'business_constitution_proof',
            'gst_returns' => 'gst_returns',
            'audited_financials' => 'audited_financials'
        );
        $newDocsUploaded = false;
        $myfile = fopen("Logs/PayLaterDocUpload.log", "a");
        fwrite($myfile, "\n\n----------------------Paylater documenet upload------------------ $id");
        fwrite($myfile, "\n".date('Y-m-d h:i:s'));
        if (isset($_FILES)) {
            $payLaterLeadDocuments = $this->getPaylaterLeadDocuments($id);
            $arrayOfDocuments = (array) $payLaterLeadDocuments;
            $uploaded_urls = (array) $arrayOfDocuments['documents'];
            $freshDocuments = (array) $arrayOfDocuments['documentStatus'];
//            fwrite($myfile, var_export($_FILES, true));
            foreach ($_FILES as $k => $arr) {
                if (!empty($arr['name'][0])) {
                    $newDocsUploaded = true;
                    if (!empty($arrayOfDocuments)) {
                        $uploaded_urls[$k] = array();
                        $freshDocuments[$k] = array();
                    }
                    foreach ($arr as $fp => $fvs) {
                        if ($fp != 'tmp_name')
                            continue;
                        foreach ($fvs as $i => $fv) {
                            $length = $i;
                            $mapping_value = $mapping[$k];
                            $ext = pathinfo($arr['name'][$i], PATHINFO_EXTENSION);
                            $actual = "leads/$id/$mapping_value" . "_$length.$ext";
                            $tmpfile = $arr['tmp_name'][$i]; 
                            $newFileName = "$mapping_value" . "_$length.$ext";
                            $path = 'leads/'.$id;
                            $bucket = "neo-paylater-prod";//$this->bucket;
                            $url = $this->uploadDocToS3Api($tmpfile, $arr['type'][$i], $newFileName, $path, 'ng_paylater', $bucket);
//                          $url = $this->upload_to_S3($fv, $actual);
                            fwrite($myfile, "\n\n Actual URL $url \n\n");
                            $uploaded_urls[$k][] = $url;
                            $freshDocuments[$k][] = 0;
                            $length++;
                        }
                    }
                }
            }
            if (!empty($uploaded_urls)) {
                fwrite($myfile, "\n$uploaded_urls");
                $string = json_encode($uploaded_urls);    
                $bean = BeanFactory::getBean('Neo_Paylater_Leads', $id);
                $bean->documents_uploaded_c = 1;
                $bean->documents_json_c = $string;
                if($bean->as_documents_uploaded_c != 1 && $AS_DOC_upload == 1){
                    $bean->as_documents_uploaded_c = $AS_DOC_upload;
                }
                if($AS_RE_UPLOAD == 1){
                    $bean->document_reuploaded_c = 1;
                }
                if(!empty($freshDocuments)){
                    $jsonFreshUploadedDocuments = json_encode($freshDocuments);
                    $bean->freshly_uploaded_doc_c = $jsonFreshUploadedDocuments;
                }
                $bean->save();
                $message = "Upload success";
                if($newDocsUploaded){
                    $appId = $bean->as_application_id_c;
                    $businessName = $bean->business_name;
                    $emailToOps = new neoPaylaterFunctions();
                    $emailToOps->sendEmailToOps($appId, $businessName);  
                } 

            }
        } else {
            if(!empty($id) && !empty($AS_RE_UPLOAD) && $AS_RE_UPLOAD == 1){
                $bean = BeanFactory::getBean('Neo_Paylater_Leads', $id);
                $bean->document_reuploaded_c = 1;
                $bean->save();
            }
            $message =  "Empty files";
        }
        return $message;
    }


    function getPaylaterLeadDocuments($beanId){
        
        if(!empty($beanId)){
           $bean = BeanFactory::getBean('Neo_Paylater_Leads', $beanId);
            $allDocuments = array();
            if ($bean->documents_uploaded_c) {
                $string = $bean->documents_json_c;
                $string = htmlspecialchars_decode($string);
                $documents = json_decode($string);
                $allDocuments['documents'] = $documents;
                $documentStatus = $bean->freshly_uploaded_doc_c;
                $jsonDocumentStatus = htmlspecialchars_decode($documentStatus);
                $allDocuments['documentStatus'] = json_decode($jsonDocumentStatus);
                return $allDocuments;
            }
        } else {
            $message = "Bean Id is empty";
            $messageType = "FAILURE";
        }

    }


    function getS3(){
    	if (!class_exists('S3')) require_once 'custom/ivrs/S3.php';

        // AWS access info
		if (!defined('SCRM_AWS_ACCESS_KEY')) define('SCRM_AWS_ACCESS_KEY', getenv('SCRM_AWS_ACCESS_KEY'));
		if (!defined('SCRM_AWS_ACCESS_SECRET')) define('SCRM_AWS_ACCESS_SECRET', getenv('SCRM_AWS_ACCESS_SECRET'));


        //instantiate the class
        $s3 = new S3(SCRM_AWS_ACCESS_KEY, SCRM_AWS_ACCESS_SECRET);
        return $s3;
    }

    function upload_to_S3($tmp,$actual){
        
    	$s3 = $this->getS3();
		$bucket = $this->bucket;	
		
	    //Upload to S3
		try{
		    if($s3->putObjectFile($tmp, $bucket , $actual, S3::ACL_PUBLIC_READ) )
		    {
		        $image='http://'.$bucket.'.s3.amazonaws.com/'.$actual;
		        return $image;
		        // echo $image;
		        echo "image url: $image uploaded";
		    }else{
		        echo 'error uploading to S3 Amazon';
    }
		}catch(Exception $e){
			echo $e->getMessage();
		}
		return "";
    }

    function download_from_S3($file_name,$save_to="",$bucket=""){
        $s3 = $this->getS3();
    	if(empty($bucket))
            $bucket = $this->bucket;
        $res = null;
		if(empty($save_to)){
            $save_to = basename(($file_name));
		}else{
            $save_to = basename($save_to);
        }
        $GLOBALS['log']->debug('[S3]: Downloading file: $file_name from s3 bucket : $bucket, and saving 
			to location: $save_to');
		try{
			if(!empty($save_to)){
                $res = $s3->getObject($bucket, ($file_name), basename($save_to));
			}else{
                $res = $s3->getObject($bucket, ($file_name), basename($file_name));
            }
			
		}catch(Exception $e){
            echo $e->getMessage();
			$GLOBALS['log']->error('[S3]: Exception occured during download : '.$e->getMessage());
        }
		$GLOBALS['log']->debug('[S3]: Response of download : '.print_r($response,true));
        return $res;
    }

    function uploadDocToS3Api($tmpfile, $type, $filename, $path, $application, $bucket) {
        $myfile = fopen("Logs/PayLaterDocUpload.log", "a");
        fwrite($myfile, "\n\n----------------------uploadDocToS3Api------------------ $filename");
        $filePath = curl_file_create($tmpfile, $type, $filename);
        if (!empty($filePath)) {
            require_once('CurlReq.php');
            $curl = new CurlReq();
            $headers = [
                'Content-Type: multipart/form-data'
            ];
            $as_api_url = getenv('AWS_API_UTILITY_URL')."/aws_upload";

            $params = [
                "application" => $application,
                "bucket" => $bucket,
                "path" => $path,
                "files" => $filePath
            ];
            $response = $curl->curl_req($as_api_url, 'post', $params, $headers);
            $apiResponse = json_decode($response, true);
            fwrite($myfile, var_export($apiResponse, true));
            $fullUrl = $apiResponse["download_url"];
            $array = explode("?", $fullUrl, 2);
            $url = $array[0];
        }
        return $url;
    }
    
    function downloadDocFromS3Api($fileName, $path, $application, $bucket) {
        $myfile = fopen("Logs/PayLaterDocUpload.log", "a");
        fwrite($myfile, "\n\n----------------------downloadDocFromS3Api------------------ $fileName");
    
        if (!empty($fileName)) {
            require_once('CurlReq.php');
            $curl = new CurlReq();
            $headers = [
                'Content-Type: multipart/form-data'
            ];
            $as_api_url = getenv('AWS_API_UTILITY_URL')."/aws_download";
            $params = [
                "application" => $application,
                "bucket" => $bucket,
                "path" => $path,
                "files" => $fileName
            ];
            $response = $curl->curl_req($as_api_url, 'post', $params, $headers);
            $apiResponse = json_decode($response, true);
            $url = $apiResponse["download_url"];

            return $url;
        } else {
            $message = "The url to be downloaded is empty";
            echo $message;
        }
       
    }*/

}
