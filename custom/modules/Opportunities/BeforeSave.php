<?php
if (!defined('sugarEntry'))
    define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
require_once('custom/include/SendSMS.php');
require_once('custom/include/SendEmail.php');
//~ ini_set('display_errors','On');
class BeforeSaveOpportunity {

    public function store_assigned(&$bean, $event, $args)
    {
        $bean->stored_fetched_row_c = $bean->fetched_row;
        $cities=array(
            'BENGALURU'=>'BANGALORE',
            'BHUBANESWAR'=>'BHUBANESHWAR',
            'VADODARA'=>'BARODA',
            'VIJAYAWADA'=>'VIJAYWADA',
            'VISAKHAPATNAM'=>'VIZAG'
        );
        if(array_key_exists($bean->pickup_appointment_city_c,$cities))
        {
            $bean->pickup_appointment_city_c=$cities[$bean->pickup_appointment_city_c];
        }
        $bean->pickup_appointment_city_c=strtoupper($bean->pickup_appointment_city_c);

    }
    public function validateMobileNumber(&$bean, $event, $args){

        $contact_number = $this->validate_mobile(trim($bean->pickup_appointment_contact_c));
       
        if($mobile !=1){

            $msg = array(
                'Success' => false,
                'Message'=>"Please enter a valid 10 digit Pickup Appoinment contact to continue!."
                );
            echo $msg;
            
           sugar_die();
           
        }
    } 

    public function validate_mobile($mobile)
    {
        return preg_match('/^[0-9]{10}+$/', $mobile);
    }


        
    public function send_email_sms_alert(&$bean, $event, $args) {
        
        global $db;
        
        $sales_stage_old = $bean->fetched_row['sales_stage'];
        $sales_stage_new = $bean->sales_stage;
        
        if (!empty($sales_stage_old) && strcmp($sales_stage_new, $sales_stage_old) != 0 && empty($bean->dwh_sync_c)) {
            
            $user_id   = $bean->assigned_user_id;
            $user_bean = new User();
            $user_bean->retrieve($user_id);
            
            $cam_name     = $user_bean->full_name;
            $trading_name = $bean->merchant_name_c;
            $current_date = date("d/m/Y H:i:s");
            //
            $message = "Hi $cam_name, status of $trading_name is moved from $sales_stage_old to $sales_stage_new on $current_date";
            
            $mobile_no = $user_bean->phone_mobile;
            $mobile_no = "91" . substr($mobile_no, -10);
            
            $env = getenv('SCRM_ENVIRONMENT');
            if($env=='prod'){
                $sms = new SendSMS();
                $sms->send_sms_to_user($tag_name="Cust_CRM_5", $mobile_no, $message, $bean);
            }
        }
    } 
}
