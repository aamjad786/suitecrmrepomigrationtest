<?php

class payLaterOpenBeforeSaveEvents {

    function sendEmailToCustomer($bean, $event, $arguments) {
        require_once('SendEmail.php');
        $updatedUser = $bean->assigned_user_id;
        $beforeSaveData = $bean->fetched_row;
        $productType = $bean->product;
        $earlierUser = $beforeSaveData['assigned_user_id'];
        if (!empty($beforeSaveData) && ($updatedUser != $earlierUser) && ($productType == "paylater_open")){
            $subject = "Welcome Call for Open PayLater";
            $primaryEmail = $bean->email_id;
            $secondaryEmail = $bean->alternate_email_id;
            $to = array($primaryEmail, $secondaryEmail);
            $body = $this->getEmailContent($bean->application_id);
            $email = new SendEmail();
            $cc = array();
            $email->send_email_to_user($subject, $body, $to, $cc, $bean);
        } else {
            //Do nothing
        }
    }

    function getEmailContent($applicationId) {
        require_once 'CurlReq.php';
        $curl = new CurlReq();
        $header = array(
            "authorization: Bearer NeoPaylater@321",
            'content-type' => 'application/json'
        );
        $url = getenv('SCRM_LMM_URI') ."/api/v2/paylater_accounts/".$application_id;
//        $url = "https://uat.advancesuite.in:3039/api/v2/paylater_accounts/".$applicationId;
        $response = $curl->curl_req($url, 'get', null, $header);
        $responseArray = json_decode($response);
        $sanctionedLimit = $responseArray->credit_limit;
        $accountActivationDate = $responseArray->activation_date;
        $accountExpiryDate = $responseArray->account_valid_till;
        $delayCharges = "";
        $body = "Dear Customer,</br></br>
                    Welcome Aboard!</br>
                    Hi, we tried reaching you. Please find below your account details along with requisite product information linked to your Open PayLater account <i>$applicationId</i>:</br>
                    <b>Tenure: </b><i>22 Months </i></br>
                    <b>Sanctioned Limit: </b><i>INR $sanctionedLimit </i></br>
                    <b>Account Activation Date: </b><i>$accountActivationDate </i></br>
                    <b>Account Expiry Date: </b><i>$accountExpiryDate </i></br>
                    <b>Interest Charges: </b><i>2% PM </i> </br>
                    <b>Mode of Repayments: </b><i>NEFT/ IMPS/ RTGS</i></br>
                    <b>Delay Charges: </b><i>$delayCharges</i></br>
                    <b>Delay Rate of Interest:</b> <i>1% PM </i> </br></br>
                    Kindly reach out to us at Toll-free numbers 1800 419 5565 / 9820 655 655 for any query/ concerns. </br></br>
                    Regards, </br>
                    NEOGrowth Credit Pvt. Ltd.</br></br>";
        return $body;
    }

}
