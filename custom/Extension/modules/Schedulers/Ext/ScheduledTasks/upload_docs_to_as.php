<?php

$job_strings[] = 'uploadDocsToAS';
date_default_timezone_set('Asia/Kolkata');

function uploadDocsToAS() {
    $myfile = fopen("Logs/paylaterDocUploadCron.log", "a");
    $bean = BeanFactory::getBean('Neo_Paylater_Leads');
    require_once 'CurlReq.php';
    fwrite($myfile, "\n\n----------------------Document Reupload Job ".date('Y-m-d H:i:s')."------------------");
    $cl = new CurlReq();
    $query = "neo_paylater_leads_cstm.document_reuploaded_c=1 and neo_paylater_leads.deleted=0 and neo_paylater_leads_cstm.as_application_id_c <> ''";
    $items = $bean->get_full_list('', $query);
    fwrite($myfile, "\n Count of Items :" . count($items));
    if ($items) {
        foreach ($items as $item) {
            $id = $item->id;
            $asAppId = $item->as_application_id_c;
            fwrite($myfile, "\n\n Item : $id");
            fwrite($myfile, "\n\n AS Application Id : $asAppId");

            $docuemts_arr = "";
            if ($item->documents_uploaded_c) {
                $string = $item->documents_json_c;
                fwrite($myfile, "\n\n documents_json_c : $string");
                $freshelyUploadedDoc = $item->freshly_uploaded_doc_c;
                $response = getOnlyNewDocs($string, $freshelyUploadedDoc);
                fwrite($myfile, "\n\n response from getOnlyNewDocs :");
                fwrite($myfile, json_encode($response));
                $docuemts_arr = $response['docToUpload'];
                fwrite($myfile, "\n\n document array to upload :");
                fwrite($myfile, json_encode($docuemts_arr));
                upload_docs($item, $docuemts_arr);
                $item->document_reuploaded_c = 0;
                $item->freshly_uploaded_doc_c = $response['jsonUpdate'];
                $item->save();
            }
        }
    }
    fwrite($myfile, "\n\n----------------------Document Reupload Job End ".date('Y-m-d H:i:s')."------------------");
    return true;
}

// uploadDocsToAS();


function upload_docs($item, $docuemts_arr) {
    $beanID = $item->id;
    $application_id = $item->as_application_id_c;
    $myfile = fopen("Logs/paylaterDocUploadCron.log", "a");
    fwrite($myfile, "\n\n In upload_docs and uploading files for $application_id and the bean ID is $beanID ");
    $cl = new CurlReq();
    if (!empty($application_id) && !empty($beanID)) {
        $entity_mapping = array('Proprietor' => 'PP',
            'Partnership' => 'PF',
            'Pvt_ltd' => 'CO',
            'HUF' => 'HU',
            'LLP' => 'PF',
            'Ltd_Co' => 'CO');

        $mapping = array('ap_form' => 'enquiry_form',
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

        $document_mapping = array('ap_form' => '00',
            'pan' => '03',
            'res_proof' => '02',
            'bus_add_proof' => '02',
            'bus_reg_proof' => '09',
            'agreement' => '00',
            'others' => '00',
            'approved_by_credit_agreement' => '00',
            'nach_form' => '00',
            'cancelled_cheque' => '00',
            'application_form' => '00',
            'aadhaar_application' => '02',
            'business_pan' => '03',
            'bank_statement' => '05',
            'business_constitution_proof' => '00',
            'gst_returns' => '10',
            'audited_financials' => '11'
        );
        $as_folder_mapping = array('ap_form' => 2,
            'pan' => 4129,
            'res_proof' => 41140,
            'bus_add_proof' => 41132,
            'bus_reg_proof' => 41131,
            'agreement' => 2,
            'others' => 2,
            'approved_by_credit_agreement' => 2,
            'nach_form' => 2,
            'cancelled_cheque' => 2,
            'application_form' => 2,
            'aadhaar_application' => 45745,
            'business_pan' => 45734,
            'bank_statement' => 45752,
            'business_constitution_proof' => 2,
            'gst_returns' => 45742,
            'audited_financials' => 45756
        );
        $bucketName = getenv('SCRM_PAYLATER_LEADS_BUCKET');
        if (!empty($docuemts_arr)) {
            foreach ($docuemts_arr as $k => $valueArray) {
                $keyvalue = $mapping[$k];
                $document_code = $document_mapping[$k];
                $as_folder_id = $as_folder_mapping[$k];
                if (empty($valueArray)) {
                    fwrite($myfile, "\nDocument type $k not found.\n");
                    continue;
                }
                foreach ($valueArray as $i => $value) {
                    if (empty($value)) {
                        fwrite($myfile, "\nDocument type $k empty URL found.\n");
                        continue;
                    }
                    $value = urldecode($value);
                    fwrite($myfile, "\nS3 uri $s3_uri .\n");
                    $urlArray = parse_url($value);
                    fwrite($myfile, "\n\n document URL");
                    fwrite($myfile, $urlArray);
                    $file_name = substr($urlArray['path'], 1);
                    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    $entity_code = $entity_mapping[$item->business_entity_c];
                    if (empty($entity_code))
                        $entity_code = 'PP';
                    $actual = $application_id . $entity_code . $document_code . ".$ext";
                    $host = $urlArray['host'];
                    $bucket = str_replace('.s3.amazonaws.com', '', $host);
                    fwrite($myfile, "\nBucket=$bucket\n");
                    fwrite($myfile, "\Entity Code=$entity_code\n");
                    fwrite($myfile, "\nDowloading from s3 file=$file_name\n");
                    fwrite($myfile, "\nFile name to be saved disc=$actual\n");

                    //Download file from s3 and save to $actual
                    require_once 'custom/modules/Neo_Paylater_Leads/controller.php';
                    $obj = new Neo_Paylater_LeadsController();
//                    $s3_uri = "http://$bucket.s3.amazonaws.com/$encoded_file_name";
                    $file_name = str_replace("\\", "", $file_name);
                    
                    $fileName = substr($file_name, strrpos($file_name, '/') + 1);
//                    $path =  substr($file_name, 0,strrpos($file_name, '/'));
                    if (strpos($file_name, "leads/") !== false) { // This needs to be changed
                        $path = "leads/$beanID";
                    } else if(strpos($file_name, "lead_form/") !== false){
                        $path = "lead_form";
                    }
                    $bucket = getenv('SCRM_PAYLATER_LEADS_BUCKET');
                    $s3_uri =  $obj->downloadDocFromS3Api($fileName, $path, 'ng_paylater', $bucket);
                    fwrite($myfile, "\nFile to be opened $s3_uri\n");
                    $data = file_put_contents($actual, fopen($s3_uri, 'r'));     
                    fwrite($myfile, "\n After new download data response is $data \n");
                    if ($data) {
                        $as_api_url = getenv('SCRM_AS_API_BASE_URL') . "/external_interfaces/upload_doc";

                        if (function_exists('curl_file_create')) { // php 5.5+
                            $cFile = curl_file_create($actual);
                        } else { // 
                            $cFile = '@' . realpath($actual);
                        }
                        $headers = [
                            'Content-Type: multipart/form-data'
                        ];
                        $params = [
                            "application_id" => $application_id,
                            "file_name" => $actual,
                            "folder_id" => $as_folder_id,
                            "doc_year" => 1,
                            "file" => $cFile
                        ];
                        fwrite($myfile, "\nUploading Document to AS  $keyvalue , Document code: $document_code, AS_folder_id=$as_folder_id, as_api_url: $as_api_url\n");
                        $response = $cl->curl_req($as_api_url, 'post', $params, $headers);
                        fwrite($myfile, "\nDocument Upload to AS Response: $response");
                        unlink($actual);
                    } else {
                        fwrite($myfile, "\nDocument download from s3 failed, so skipping upload to AS\n");
                    }
                }   //end foreach valueArr
            }   //end foreach document_arr
        } else {
            fwrite($myfile, "\nNo Documents to upload to AS for $beanID\n");
        }
    }
    fwrite($myfile, "\n\n In upload_docs End Application/bean is empty ");
    fclose($myfile);
}

function getOnlyNewDocs($documents, $freshDocumentMapping) {
    $returnArray = array();
    if (!empty($documents) && !empty($freshDocumentMapping)) {
        $string = htmlspecialchars_decode($freshDocumentMapping);
        $freshDocUploadArray = (array) json_decode($string);

        $string1 = htmlspecialchars_decode($documents);
        $docUploadArray = (array) json_decode($string1);
        $docArray = array();
        $updateOriginal = array();
        foreach ($freshDocUploadArray as $key => $value) {
            $docName = $key;
            $internalValues = $value;
            foreach ($internalValues as $key => $value) {
                $index = $key;
                if ($value == 0) {
                    $docArray[$docName][] = $docUploadArray[$docName][$index];
                }
                $updateOriginal[$docName][] = 1;
            }
        }
        $jsonFreshUploadedDocuments = json_encode($updateOriginal);

        $returnArray['docToUpload'] = $docArray;
        $returnArray ['jsonUpdate'] = $jsonFreshUploadedDocuments;

        return $returnArray;
    } else if (!empty($documents)) {
        $freshDocumentMapping = array();
        $string1 = htmlspecialchars_decode($documents);
        $docUploadArray = (array) json_decode($string1);
        $docArray = array();
        $updateOriginal = array();

        foreach ($docUploadArray as $key => $value) {
            $docName = $key;
            $internalValues = $value;
            foreach ($internalValues as $key => $value) {
                $index = $key;
                $docArray[$docName][] = $docUploadArray[$docName][$index];
                if ($value == 0) {
                    $freshDocumentMapping[$docName][] = 1;
                }
            }
        }
        $jsonFreshUploadedDocuments = json_encode($freshDocumentMapping);
        $returnArray['docToUpload'] = $docArray;
        $returnArray ['jsonUpdate'] = $jsonFreshUploadedDocuments;
        
        return $returnArray;
    }

    return false;
}
