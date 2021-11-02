<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/

/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once('modules/Neo_Customers/Neo_Customers_sugar.php');
class Neo_Customers extends Neo_Customers_sugar {
	
	function __construct(){
		parent::__construct();
	}
//neo_customers.renewed_app_id as 'Renewed App ID',
        function create_export_query($order_by, $where, $relate_link_join='')
        {
            global $current_user;
            $user_id = $current_user->id;
            // convert_tz(sq_nca.date_created ,'+0:00','+5:30') : Converting db time to ist for export reports
            $query = "SELECT
                            neo_customers.customer_id as 'id',
                            neo_customers.name as 'Name',
                            neo_customers.mobile as 'Mobile',
                            neo_customers.loan_amount as 'Loan Amount Of Live Loans',
                            if(neo_customers.renewal_eligible = 1, 'Yes','No') as 'Eligible For Renewal',
                            neo_customers.renewal_eligiblity_amount as 'Instant Renewal Eligiblity Amount',
                            if(neo_customers.instant_renewal_eligibility = 1, 'Yes','No') as 'Eligible For Instant Renewal',
                            if(neo_customers.blacklisted = 1, 'Yes','No') as 'Collection Reject',
                            if(neo_customers.credit_reject = 1, 'Yes','No') as 'Credit Reject',
                            if(neo_customers.half_paid_up = 1, 'Yes','No') as 'Half paid up',
                            if(neo_customers.ever_30_dpd = 1, 'Yes','No') as 'Ever 30plus dpd' ,
                            CASE  
                                WHEN neo_customers.queue_type ='cold_lead' THEN 'CL'
                                WHEN neo_customers.queue_type ='hot_lead' THEN 'HL'
                                WHEN neo_customers.queue_type ='not_eligible' THEN 'NE'
                                ELSE 'N/A'
                            END as 'Lead Type',
                            neo_customers.disposition as 'Disposition',
                            neo_customers.subdisposition as 'Sub Disposition',
                            REPLACE(neo_customers.renewed_app_id, ',', '|') as 'Renewed App ID',
                            neo_customers.as_stage as 'AS Stage',
                            neo_customers.as_remarks as 'AS Remarks',
                            (
                                select sq_nca.date_created
                                from neo_customers_audit sq_nca 
                                where sq_nca.parent_id = neo_customers.id 
                                and sq_nca.field_name='renewal_eligible' 
                                and sq_nca.after_value_string = 'true' 
                                order by date_created desc limit 1
                            ) as 'latest renewal eligible date',
                            concat(users.first_name,' ',users.last_name) as 'Assigned User Name',
                            convert_tz(neo_customers.hot_lead_trigger_time ,'+0:00','+5:30') as 'Hot Lead Trigger Time'
                            ";
        	$query .=  $custom_join['select'];
                            $query .= " FROM neo_customers ";
        	$query .=  $custom_join['join'];
                            $query .= "";
            $query .= "		LEFT JOIN users
                            ON neo_customers.assigned_user_id=users.id";
            $where_auto = "  neo_customers.deleted=0";

            require_once('modules/Neo_Customers/Renewals_functions.php');
            $renewals = new Renewals_functions();
            global $current_user;
            $details = $renewals->getRenewalUserById($current_user->id);
            $ticket_size = $details['ticket_size'];
            $city = $details['city'];
            $role = $details['role'];
            
            if($role == 'Renewal manager'){
                $where_auto .= " AND (".$renewals->getQueryManager($city,$ticket_size,1).")";
            }
            if(!in_array($role, array('Renewal manager','Renewal admin'))){
                $where_auto .= " AND neo_customers.assigned_user_id='$user_id'";
            }



            if($where != "")
                    $query .= " where $where AND ".$where_auto;
            else
                    $query .= " where ".$where_auto;



            if($order_by != "")
                    $query .= " ORDER BY $order_by";
            else
                    $query .= " ORDER BY neo_customers.customer_id";
            $GLOBALS['log']->debug("Create export query neo customers -> " . $query);
            // print_r($query);
            // die();
            return $query;
        }


        function getTicketSizeAmount($amount){

            if($amount<1040000){
                $ticket_size=1;
            }else if($amount>2500000){
                $ticket_size=3;
            }else{
                $ticket_size=2;
            }
            return $ticket_size;
        }

        function get_concerned_sales_resources($source){
            if($source!='merchant_app')return null;
            $loan_amount = $this->loan_amount;
            $city = $this->location;
            $ticket_size = $this->getTicketSizeAmount($amount);
            global $db;
            $query  = "select * from renewal_users where role='Renewal Manager' and city like '%$city%' and ticket_size='%ticket_size%'";
            $result = $db->query($query);
            $emails = [];
            while ($row = $db->fetchByAssoc($result)) {
                $ticket_size = $row['user_id'];
                $user=BeanFactory::getBean('Users',$user_id); renewals.php 
                $primary_email=$user->emailAddress->getPrimaryAddress($user);
                $emails[] = $primary_email;
            }
            return $emails;
        }

        function send_sms_to_customer(){
            $item = $this;
            require_once('custom/include/SendSMS.php');
            $env = getenv('SCRM_ENVIRONMENT');
            $user_mobile_number = $item->mobile;
            
            $message = null;

            if($item->instant_renewal_eligibility){
                $amount = $item->renewal_eligiblity_amount;
                if($amount>0){
                    $name = $item->name;
                    $message = "Hi $name, as a privilege customer, We bring you an Exclusive Pre-approved business loan of Rs. $amount. No income documents required. Give miss call on 9152007511 (T&C)";
                }
            }
            if(empty($message)){
                $arr = array("With NeoGrowth you can now meet your last minute fund requirements. Give missed call 9152007511 (T&C)","NEVER BEFORE Offer - Renew your LOAN with NeoGrowth. Give missed call 9152007511 (T&C)","Get Business Loan up to 1.5 Cr in 72 hours! Give missed call on 9152007511 (T&C)");
                $index = 0;
                if($item->loan_amount > 7500000){
                    $index = mt_rand(0, 2);
                }else{
                    $index = mt_rand(0, 1);
                }
                $message = $arr[$index]; 
            }
            $sms = new SendSMS();
            fwrite($this->log, "\nSending Message: $message to ".$user_mobile_number);
            $sms->send_sms_to_user($tag_name="Cust_CRM_3",$user_mobile_number, $message, $item, 'Cibil Trigger Scheduler');
            fwrite($this->log, "\nSms sent\n");
        }
        

        function send_email_to_customer(){
            $item = $this;
            $application_id = $item->app_id;
            fwrite($this->log,"application_id=$application_id");
            require_once('custom/include/CurlReq.php');
            $curl_req = new CurlReq();

            $as_api_url = getenv('SCRM_AS_API_BASE_URL'); 
            echo $as_api_url; 
            $email = ""; 
            // $test_email = getenv('SCRM_TEST_EMAIL');
            // // $test_email='25saurabh06@gmail.com';
            // $env = getenv('SCRM_ENVIRONMENT');
            // if(in_array($env,array('dev','local')))
            //         $email = $test_email;
            if(empty($email)){
                if (!empty($application_id) && !empty($as_api_url)) {
                    $res = $curl_req->curl_req($as_api_url."/get_merchant_details?ApplicationID=".$application_id);
                    var_dump($res);
                    if($res){
                        $res = json_decode($res);
                        $email = ($res[0]->{'Applicant Email Id'});
                    }
                }
            }
            $body=null;
            $subject = null;
            $instant_renewal_eligibility = $item->instant_renewal_eligibility;
            $renewal_eligiblity_amount = $item->renewal_eligiblity_amount;
            fwrite($this->log, "Eligiblity = $instant_renewal_eligibility");
            fwrite($this->log,"Amount=$renewal_eligiblity_amount");
            if($item->instant_renewal_eligibility){
                if($item->renewal_eligiblity_amount>0){
                    $name = $item->name;
                    $amount = $item->renewal_eligiblity_amount;
                    $subject = "Exclusive Pre-approved Business Loan. No Documents required!";
                    $body = "Hi $name,<br/> As a privilege customer, We bring you an Exclusive Pre-approved business loan of Rs. $amount. No income documents required. Give miss call on 9152007511 (T&C).<br/><br/><br/>";
                }
            }
            fwrite($this->log,"body=$body");
            fwrite($this->log,"Subject=$subject");
            // if(!empty($email)){
                require_once('custom/include/SendEmail.php');
                $send_email = new SendEmail();

                if(empty($body)){
                    $body = file_get_contents('renewals.html');
                    $subject = 'NEVER BEFORE Offer - Renew your LOAN with NeoGrowth';
                }
                fwrite($this->log,"Subject=$subject");
                $send_email->send_email_to_user($subject,$body, array($email),null,$item);
                fwrite($this->log, "\nEmail sent\n");
            // }

        }
    	

}
?>