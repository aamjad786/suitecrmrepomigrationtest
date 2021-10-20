<?php

    function sendSmsJobQueue($job) {

        require_once('custom/CustomLogger/CustomLogger.php');
        require_once ('custom/include/SendSMS.php');
        
        $logger =new CustomLogger('SendSmsJobQueue');
    
        $cleanedData=htmlspecialchars_decode($job->data);
        $arrayData=json_decode($cleanedData, true);

        $logger->log('debug', 'Job ID: '.$job->id);
        $logger->log('debug', 'Job Data: '.print_r($arrayData,true));
        
        if (!empty($job->data)) {
            
            $sms=new SendSMS();
            
            $isSend=$sms->send_sms_to_user($arrayData["tag_name"],$arrayData["custMobileNo"],$arrayData["messageToSend"]);
            
            if($isSend){
                $logger->log('debug', 'SMS Send Successfully!');
            }else{
                $logger->log('fatal', 'Error Occured While Sending SMS Please Check Send SMS LOG!');
            }
            
        }else{
            
            $logger->log('fatal', 'Job Is Failed Because Of Data is Empty!');
            $job->$message='Job Is Failed Because Of Data is Empty!';

            return false;
        }
    
        return true;
    }
