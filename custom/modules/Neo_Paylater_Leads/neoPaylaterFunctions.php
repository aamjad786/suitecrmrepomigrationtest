<?php

if (!defined('sugarEntry'))
    die('Not a Valid Entry Point');
require_once('SendEmail.php');

class neoPaylaterFunctions {

    //function related to leads
   /*public function sendEmailToOps($appId, $businessName) {
        if (!empty($appId)) {
            $myfile = fopen("Logs/PayLaterDocUpload.log", "a");
            fwrite($myfile, "\n\n Sending email");

            $env = getenv('SCRM_ENVIRONMENT');
            if (in_array($env, array('dev', 'local'))) {
                $to = array('gowthami.gk@neogrowth.in'); //This should be moved to config file and use the variable everywhere
            } else if ($env == 'prod') {
                $to = array('Shahalam.ansari@neogrowth.in', 'neopaylater@neogrowth.in');
            }
            $subject = "$appId-$businessName-Documents Added";
            $body = "Hi, </br></br>"
                    . "Additional documents have been added for App ID - $appId for Business $businessName.</br></br>"
                    . "Thanks </br> NeoGrowth </br>";
            fwrite($myfile, "\n\n environment- $env \n\n APP Id- $appId \n\n Business name- $businessName \n\n To -  " . print_r($to));

            $email = new SendEmail();
            $email->send_email_to_user($subject, $body, $to);
            return true;
        } else {
            $myfile = fopen("Logs/PayLaterDocUpload.log", "a");
            fwrite($myfile, "\n\n App Id is empty");
            return true;
        }
    }*/

}
