<?php
require_once('CurlReq.php');
require_once('custom/include/SendEmail.php');
require_once('custom/include/SendSMS.php');
global $current_user,$db;

class payLaterOpenAfterSaveEvents {

    function DeleteRecordOnStatusChangeToClosed($bean, $event, $arguments) {
        
        $oldData = $bean->fetched_row;
        
        if ($bean->status == "CLOSED") {
            $currentUserId = $current_user->id;
            $bean->closed_by = $currentUserId;
            $bean->date_closed = date('Y-m-d h:i:s');
            
            $bean->save();
            
       } else if (($oldData['status'] != "NOT_CONTACTABLE") && $bean->status == "NOT_CONTACTABLE") {
       //} else if ($bean->status == "NOT_CONTACTABLE") {
            
            if (!empty($bean->email_id)) {
                $emailContent = $this->getEmailContent($bean);
             
                if (!empty($emailContent)) {
                    $subject = 'APP ID : ' .$bean->application_id .' - ' .$bean->merchant_name .' - Welcome Call Failure Intimation';
                    //$subject = "Paylater Open - Not contactable";
                    $primaryEmail = $bean->email_id;

                    $email = new SendEmail();
                    $to = array($primaryEmail);
                    $cc = array('');
                    $email->send_email_to_user($subject, $emailContent, $to, $cc, $bean);
                }
            }
            if (!empty($bean->phone_number)) {
                $smsContent = $this->getSmsContent();
                $sms = new SendSMS();
                $sms->send_sms_to_user($tag_name="Cust_CRM_4",$bean->phone_number,$smsContent,$bean);
            }
        }
    }
    

    function getEmailContent($bean) {
       
        $url = getenv('SCRM_AS_API_BASE_URL')."/get_application_basic_details?ApplicationID=".$bean->application_id;
        require_once('CurlReq.php');
        $curl_req = new CurlReq();
        $response = $curl_req->curl_req($url);
        $address = '';
        if(!empty($response) && count($response)>=1){

            $json_response = json_decode($response, true);

            if(!empty($json_response)){
                
                $address = $json_response[0]['BusinessAddress'];
              
            }
        }

        $url = getenv('SCRM_AS_API_BASE_URL')."/get_application_deal_details?ApplicationID=".$bean->application_id;

        $response = $curl_req->curl_req($url);
        $term_days = 0;
        if(!empty($response) && count($response)>=1){

            $json_response = json_decode($response, true);

            if(!empty($json_response)){
               
                $term_days = $json_response[0]['Term (Days)'];

            }
        }

        $url = getenv('SCRM_AS_API_BASE_URL')."/get_application_repaymec_details?ApplicationID=".$bean->application_id;

            $response = $curl_req->curl_req($url);
            $repayment_frequency = '';
            if(!empty($response) && count($response)>=1){

                $json_response = json_decode($response, true);
                
				if(!empty($json_response)){

					$repayment_frequency = $json_response[0]['Repayment Frequency'];
				}
			}

        $url_emi_details= getenv('SCRM_AS_API_BASE_URL')."/get_emi_and_sanction_details?ApplicationID=".$bean->application_id;

        $response_emi_details = $curl_req->curl_req($url_emi_details);

        $EMIAmount = $ApplicationID = $PanNo = $ProductId = $paylaterOpenSancationLimit = $PayLaterPurchaseSanctionLimit = 0;

        if(!empty($response_emi_details) && count($response_emi_details)>=1){

            $json_response_for_emi = json_decode($response_emi_details, true);

            if(!empty($json_response_for_emi)){

                $ApplicationID = $json_response_for_emi[0]['ApplicationID'];

                $PanNo = $json_response_for_emi[0]['PanNo'];

                $ProductId = $json_response_for_emi[0]['ProductId'];

                $EMIAmount = $json_response_for_emi[0]['EMIAmount'];

                $paylaterOpenSancationLimit = $json_response_for_emi[0]['PayLaterOpenSanctionLimit'];

                $PayLaterPurchaseSanctionLimit = $json_response_for_emi[0]['PayLaterPurchaseSanctionLimit'];

            }
        }
        $phoneNumber = $bean->phone_number;

        $applicationNumber = $bean->application_id;
        

        $body = "Dear Sir/Madam,</br></br>
        Greetings from NeoGrowth! </br> </br>
        We have made several attempts to get in touch with you on your registered mobile number ($bean->phone_number) for welcoming you to the NeoGrowth Family and for reconfirming some important details regarding your loan account. Please let us know a convenient time and alternate contact number for reaching you.</br></br>
        Meanwhile, we urge you to refer to your loan related documents such as the Welcome Letter, Sanction Letter and Loan Repayment Schedule. If you have any queries, please write to us at helpdesk@neogrowth.in. You can also call our customer service numbers, 1800-419-5565 or 9820655655, between 10 A.M. – 6 P.M. from Monday to Saturday.</br></br>
        Thank you for choosing NeoGrowth Credit Private Limited!</br></br></br>
        Thanks & Regards </br>
        Customer Service Team</br>
        NeoGrowth Credit Pvt. Ltd</br>
        Customer Care Number|1800-4195565 & 9820655655</br> 
        Email: helpdesk@neogrowth.in</br>
        Business Timing: 10 A.M. – 6 P.M. from Monday to Saturday</br> </br>
            <table border='0'>
            <tr> 
                <th colspan='4'>Purchase Finance (TERM LOAN)</th>
            </tr>
            <tr>           
                <td>Application Id</td>
                <td>:</td>
                <td>$bean->application_id</td>
            </tr>
            <tr> 
                <td>Company Name </td>
                <td>:</td>
                <td>$bean->merchant_name</td>
            </tr>
            <tr>            
                
                <td>Business Address</td>
                <td>:</td>
                <td>$address</td>
            </tr>
            <tr>            
                <td>Email Address </td>
                <td>:</td>
                <td>$bean->email_id</td>
            </tr>
            <tr>            
                <td>Contact No(s)</td>
                <td>:</td>
                <td>$bean->phone_number</td>
            </tr>
            <tr>            
                <td>Advanced Amount</td>
                <td>:</td>
                <td>$bean->advance_amount</td>
            </tr>
            <tr>           
                <td>Repayment Amount</td>
                <td>:</td>
                <td>$bean->total_repayment_amount</td>
            </tr>
            <tr>            
                <td>Term (Days)</td>
                <td>:</td>
                <td>$term_days</td>
            </tr>
            <tr>            
                <td>Repayment Mode</td>
                <td>:</td>
                <td>$bean->repayment_mode</td>
            </tr>
            <tr>
                <td>Revised Repayment Frequency</td>
                <td>:</td>
                <td>$bean->repayment_frequency</td>
            </tr>
            <tr>
                <td>EMI Amount </td>
                <td>:</td>
                <td>$EMIAmount</td>
            </tr>
            <tr>
                <td>Processing Fees % </td>
                <td>:</td>
                <td>$bean->processing_fee</td>
            </tr>
            <tr>
                <td>EMI deduction date </td>
                <td>:</td>
                <td>'".(!empty($repayment_frequency) && ($repayment_frequency=='Daily' ||  $repayment_frequency=='Weekly')?'4th, 11th,18th and 25th of the month':($repayment_frequency =='Fortnightly'?'5th and 20 of the month':($repayment_frequency=='Monthly'?'5th of the month':'')))."'</td>
            </tr>
            <tr>
                <td>Pan Card no </td>
                <td>:</td>
                <td>$PanNo</td>
            </tr>
            <tr>
                <td>Source of Repayment</td>
                <td>:</td>
                <td>You can make the payment with NEFG/ RTGS or our Virtual account number NEOGRW FOLLOWED BY APP ID i.e. NEOGRW1057982</td>
            </tr>
            <tr>
                <td>Documents sent on your registered email id </td>
                <td>:</td>
                <td>Welcome letter, Repayment Schedule, Sanction letter</td>
            </tr>
            <tr>
                <td>TDS Refund</td>
                <td>:</td>
                <td>Request  you to please visit 'Merchant Portal' for TDS Refund</td>
            </tr>
            <tr>
                <td>Paylater Open Sanction limit </td>
                <td>:</td>
                <td>$paylaterOpenSancationLimit</td>
            <tr>
                <td>Tenure</td>
                <td>:</td>
                <td>22 Months</td>
            </tr>
            <tr>
                <td>Interest rate</td>
                <td>:</td>
                <td>2%</td>
            </tr>
            <tr>
                <td>Paylater Purchase Sanction Limit applicability</td>
                <td>:</td>
                <td>$PayLaterPurchaseSanctionLimit</td>
            </tr>
            <tr>
                <td>Request for fund</td>
                <td>:</td>
                <td>First 12 months</td>
            </tr>
            <tr>
                <td>Payment</td>
                <td>:</td>
                <td>Next 10 months</td>
            </tr>
            <tr>
                <td>Monthly statement generated</td>
                <td>:</td>
                <td>1st of every month (Statement will include all the total outstanding amount as on the last day of previous month including principal, interest charges.)</td>
            </tr>
            <tr>
                <td>Payment due on</td>
                <td>:</td>
                <td>10th of every month (Minimum amount due (10% of the bill amount) or Total bill amount)</td>
            </tr>
            <tr>
                <td>Delayed Interest</td>
                <td>:</td>
                <td>1%  (will be applicable beyond due date)</td>
            </tr>
            <tr>
                <td>Delayed Charges</td>
                <td>:</td>
                <td>delay charges can be from Rs. 500 to Rs.1500 depending upon the outstanding amount.</td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td>:</td>
                <td>NEFT/RTGS through neogrowth virtual account</td>
            </tr>
            <tr>
                <td>virtual Account number</td>
                <td>:</td>
                <td>virtual acc no. 868686 followed by APPID</td>
            </tr>
            <tr>
                <td>How to apply for the sanction limit</td>
                <td>:</td>
                <td>1. Merchant Portal - www.neogrowth.in<br>
                    2. Email - Withdraw@neogrowth.in</td>
            </tr>
            <tr>
                <td>Fund transfer</td>
                <td>:</td>
                <td>Within 24 working hours.</td>
            </tr>
        </table>";
        return $body;
    }

    function getSmsContent() {
        $content = "Dear Customer, we welcome you to the NeoGrowth family. We tried reaching you to reconfirm some important details regarding your loan account. We request you to refer to the email that has been sent with all your details. If you have any queries, please write to us at helpdesk@neogrowth.in. You can also call our customer service numbers, 1800-419-5565 or 9820655655, between 10 A.M. – 6 P.M. from Monday to Saturday. Regards, Customer Service Team";
        return $content;
    }

}
