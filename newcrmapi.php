<?php
if (!defined('sugarEntry'))
define('sugarEntry', true);

if (!defined('sugarEntry') || !sugarEntry)
die('Not A Valid Entry Point');

require_once('include/entryPoint.php');
require_once 'vendor/autoload.php';
require_once 'custom/CustomLogger/CustomLogger.php';

global $db, $sugar_config;

$logger=new CustomLogger('crmapi-2.0');

$scrm_key = $sugar_config['scrm_key'];
$url      = $sugar_config['scrm_api_url'];

$apiModule = array(
    'Target',
    'Lead',
    'Call',
    'Meeting',
    'Task',
    'Note',
    'User',
    'Opportunities',
    'Cases',
    'Opportunity_Status',
    'Neo_Customers',
    "Users",
    'Paylater_Open'
);
$apiAction = array(
    'Create',
    'Update',
    'Fetch',
    'Audit',
    'Create_ETL',
    'Transacting'
);

if ($_SERVER['HTTP_AUTHORIZEDAPPLICATION'] == $scrm_key && in_array($_SERVER['HTTP_REQUESTEDMODULE'], $apiModule) && in_array($_SERVER['HTTP_REQUESTEDMETHOD'], $apiAction)) {

    $module = $_SERVER['HTTP_REQUESTEDMODULE'];
    $action = $_SERVER['HTTP_REQUESTEDMETHOD'];
    $clientIP=$_SERVER['REMOTE_ADDR'];
    
    $fp      = fopen('php://input', 'r');
    $rawData = json_decode(stream_get_contents($fp));

    $logger->log('debug', 'Request Coming From IP: '.$clientIP);

    // Setting Admin As Global User For Audit Purpose
    global $current_user;
    $current_user = BeanFactory::newBean('Users');
    $current_user->getSystemUser();

if ($action == 'Audit') {
    require_once ('modules/Audit/Audit.php');

    global $beanFiles, $beanList, $focus;
    $bean_name = $beanList[$module];
    require_once ($beanFiles[$bean_name]);

    $focus = new $bean_name();
    $record = $rawData->id;
    $criteria = $rawData->criteria;
    if (!empty($record)) {
        $result = $focus->retrieve($rawData->id);
        if ($result == null || !$focus->ACLAccess('', $focus->isOwner($current_user->id))) {
            sugar_die($app_strings['ERROR_NO_RECORD']);
        }

        if ($focus->is_AuditEnabled()) {
            $order = ' order by ' . $focus->get_audit_table_name() . '.date_created desc';
            $query = "SELECT " . $focus->get_audit_table_name() . ".* FROM " . $focus->get_audit_table_name() . " WHERE " . $focus->get_audit_table_name() . ".parent_id = '$focus->id'" . $order;
            global $db;
            $result = $focus->db->query($query);
            $results = array();
            while (($row = $focus->db->fetchByAssoc($result)) != null) {
                $results[] = $row;
            }

            $msg = $results;
        }
    }
}
else if ($module == "Lead" && $action == 'Create') {
   
    $logger->log('debug', 'Create Lead API Request =====>'.var_export($rawData, true));
    
    $isDataValid=true;

    $first_name_regEx = "/^[A-Za-z]+$/";
    $last_name_regEx = "/^[A-Za-z]+$/";
    $business_vintage_years_c_regEx = "/^(18|19|20)\d{2}$/";
    $sub_source_c_regEx = "/^[a-zA-Z ]*$/";
    $loan_amount_c_regEx = "/^[0-9]*$/";
    $product_type_c_regEx = "/^[a-zA-Z ]*$/";
    $bank_account_name_c_regEx = "/^[a-zA-Z ]*$/";
    $bank_account_count_c_regEx = "/^[0-9]{1,2}$/";
    $bank_account_type_c_regEx = "/^[a-zA-Z ]*$/";
    $fse_name_c_regEx = "/^[a-zA-Z ]*$/"; //Reference Name
    $fse_number_c_regEx = "/^[0-9]*$/"; //Reference Number
    $primary_address_street_regEx = "/^[#.0-9a-zA-Z\s,-]+$/";
    $primary_address_postalcode_regEx = "/^[1-9][0-9]{5}$/";
    $alt_address_street_regEx = "/^[#.0-9a-zA-Z\s,-]+$/";
    $alt_address_postalcode_regEx = "/^[1-9][0-9]{5}$/";
    $alt_landline_number_c_regEx = "/^[0-9]{10}$/";
    $phone_work_regEx = "/^[0-9]{10}$/"; //Alternate Mobile Number
    $average_total_monthly_sales_c_regEx = "/^[0-9]*$/";
    $total_sales_per_month_c_regEx = "/(?=.*?\d)^\$?(([1-9]\d{0,2}(,\d{3})*)|\d+)?(\.\d{1,2})?$/";
    $dsa_code_c_regEx = "/^\d*[a-zA-Z][a-zA-Z \d]*$/";
    $scheme_c_regEx = "/^\d*[a-zA-Z][a-zA-Z \d]*$/";
    $turnover_c_regEx = "/^[0-9]*$/";
    $residence_vintage_c_regEx = "/^\d{1,2}$/";
    $business_vintage_c_regEx = "/^\d{1,2}$/";
    $gst_registration_c_regEx = "/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/";
    $average_settlements_c_regEx="/^[0-9]*$/";
    //  Mandatory Fields 

    if (validate_mobile(trim($rawData->phone_mobile)) != 1) {
        $isDataValid=false;
        $logger->log('error', 'Invalid Mobile Number Present In The Request....!');
        $msg = array(
            'Success' => false,
            'Message' => 'Please enter a valid 10 digit Mobile to continue.'
        );
    }

    if (empty($rawData->lead_source)) {
        $isDataValid=false;
        $logger->log('error', 'Mandatory field(s) are missing. Empty Lead Source....!');
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing. Empty Lead Source'
        );
    }

    if (empty($rawData->first_name)) {
        $isDataValid=false;
        $logger->log('error', 'Mandatory field(s) are missing.Empty First Name....!');
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing. Empty First Name'
        );
    }

    if (empty($rawData->last_name)) {
        $isDataValid=false;
        $logger->log('error', 'Mandatory field(s) are missing.Empty Last Name....!');
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing. Empty Last Name'
        );
    }

    if (empty($rawData->merchant_name_c)) {
        $isDataValid=false;
        $logger->log('error', 'Mandatory field(s) are missing.Empty Merchant Name ....!');
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing. Empty Merchant Name'
        );
    }

    //  Mandatory Fields END

    // ==== Data Validation =====
 
    // $logger->log('debug', 'firstcond: '.!empty($rawData->first_name) );
    // $logger->log('debug', 'secondcond: '.!preg_match($first_name_regEx,$rawData->$first_name) );

    if(!empty($rawData->first_name) && !preg_match($first_name_regEx,$rawData->first_name)){
        
        $isDataValid=false;
        
        $msg = array(
            'Success' => false,
            'Message' => 'First Name Should Contain Alphabets Only!'
        );
    }

    if(!empty($rawData->last_name) && !preg_match($last_name_regEx,$rawData->last_name)){
        
        $isDataValid=false;
        
        $msg = array(
            'Success' => false,
            'Message' => 'Last Name Should Contain Alphabets Only!'
        );
    }


    if(!empty($rawData->business_vintage_years_c) && !preg_match($business_vintage_years_c_regEx,$rawData->business_vintage_years_c)){
        
        $isDataValid=false;
        
        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Business Vintage Year!'
        );
    }


    if(!empty($rawData->sub_source_c) && !preg_match($sub_source_c_regEx,$rawData->sub_source_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Sub Source!'
        );
    }

    if(!empty($rawData->loan_amount_c) && !preg_match($loan_amount_c_regEx,$rawData->loan_amount_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Loan Amount!'
        );
    }

    if(!empty($rawData->product_type_c) && !preg_match($product_type_c_regEx,$rawData->product_type_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Product Type!'
        );
    }

    if(!empty($rawData->bank_account_name_c) && !preg_match($bank_account_name_c_regEx,$rawData->bank_account_name_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Bank Account Name!'
        );
    }

    if(!empty($rawData->bank_account_count_c) && !preg_match($bank_account_count_c_regEx,$rawData->bank_account_count_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Bank Account Count!'
        );
    }

    if(!empty($rawData->bank_account_type_c) && !preg_match($bank_account_type_c_regEx,$rawData->bank_account_type_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Bank Account Type!'
        );
    }

    if(!empty($rawData->fse_name_c) && !preg_match($fse_name_c_regEx,$rawData->fse_name_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Reference Name!'
        );
    }

    if(!empty($rawData->fse_number_c) && !preg_match($fse_number_c_regEx,$rawData->fse_number_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Reference Number!'
        );
    }

    if(!empty($rawData->primary_address_street) && !preg_match($primary_address_street_regEx,$rawData->primary_address_street)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Primary Address Street!'
        );
    }

    if(!empty($rawData->primary_address_postalcode) && !preg_match($primary_address_postalcode_regEx,$rawData->primary_address_postalcode)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Primary Address Postal Code!'
        );
    }
    
    if(!empty($rawData->alt_address_street) && !preg_match($alt_address_street_regEx,$rawData->alt_address_street)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Alternative Address Street!'
        );
    }

    if(!empty($rawData->alt_address_postalcode) && !preg_match($alt_address_postalcode_regEx,$rawData->alt_address_postalcode)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Alternative Address Postal Code!'
        );
    }

    if(!empty($rawData->alt_landline_number_c) && !preg_match($alt_landline_number_c_regEx,$rawData->alt_landline_number_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Alternative Landline Number!'
        );
    }


    if(!empty($rawData->phone_work) && !preg_match($phone_work_regEx,$rawData->phone_work)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Alternative Mobile Number!'
        );
    }

    if(!empty($rawData->average_total_monthly_sales_c) && !preg_match($average_total_monthly_sales_c_regEx,$rawData->average_total_monthly_sales_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Average Total Monthly Sales!'
        );
    }

    if(!empty($rawData->total_sales_per_month_c) && !preg_match($total_sales_per_month_c_regEx,$rawData->total_sales_per_month_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Average Total Sales Per Month!'
        );
    }

    if(!empty($rawData->dsa_code_c) && !preg_match($dsa_code_c_regEx,$rawData->dsa_code_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid DSA Code !'
        );
    }

    if(!empty($rawData->scheme_c) && !preg_match($scheme_c_regEx,$rawData->scheme_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Scheme Name !'
        );
    }

    if(!empty($rawData->turnover_c) && !preg_match($turnover_c_regEx,$rawData->turnover_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Turnover !'
        );
    }

    if(!empty($rawData->residence_vintage_c) && !preg_match($residence_vintage_c_regEx,$rawData->residence_vintage_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Residence Vintage !'
        );
    }

    if(!empty($rawData->business_vintage_c) && !preg_match($business_vintage_c_regEx,$rawData->business_vintage_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Business Vintage !'
        );
    }

    if(!empty($rawData->gst_registration_c) && !preg_match($gst_registration_c_regEx,$rawData->gst_registration_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid GST Registration Number !'
        );
    }

    if(!empty($rawData->average_settlements_c) && !preg_match($average_settlements_c_regEx,$rawData->average_settlements_c)){
        
        $isDataValid=false;

        $msg = array(
            'Success' => false,
            'Message' => 'Invalid Average Settlements!'
        );
    }

    if($isDataValid){

        // Dedup Check

        $isDuplicate=false;

        if (!$rawData->is_renewal_c || $rawData->dwh_sync_c) {
            $lead_id = checkDuplicateLead($rawData->phone_mobile, $rawData->scheme_c);
        }

        if (!empty($lead_id)) {
            $isDuplicate=true;
            $logger->log('error', 'Duplicate Lead Found....!');

            $msg = array(
                'Success' => false,
                'Message' => "Sorry, we have a record in our database that matches your lead details. Please re-check and create again.",
                'Info' => "Lead already exist with similar details id = '$lead_id'",
                'lead_id' => $lead_id
            );
        }

        // Dedup Check END


        // Actual Lead Creation

        if(!$isDuplicate) {

            $lead = new Lead();

            foreach ($rawData as $k => $v) {

                if ($k == 'Description') $v = htmlentities($v);

                if ($k == 'assigned_user_id') {

                    $q = "select id from users where id='$v'";
                    $result = $db->query($q);
                    while ($row = $db->fetchByAssoc($result)) {
                        $v = $row['id'];
                    }
                }

                $lead->{$k} = $v;
            }

            $lead->save();
            $id = $lead->id; 

            if (empty($id) || $id == 'null') {
                
                $msg = array(
                    'Success' => false,
                    'Message' => 'Error occured while creating lead.',
                );
            }else{
                $logger->log('debug', 'Lead Is Created With Id: '.$id);
                $msg = array(
                    'Success' => true,
                    'Message' => 'Lead Created Successfully',
                    'Lead id' => $id,
                );
            }
        }

    }
    

}
else if ($module == "Lead" && $action == 'Update') {
    
        $logger->log('debug', 'Update Lead API Request =====>'.var_export($rawData, true));
        
        // Data Validation
        global $app_list_strings;
        $isDataValid=true;

       if(!array_key_exists($rawData->eos_disposition_c,$app_list_strings['eos_disposition_list'])) {
            $isDataValid=false;
            $msg = array(
                'Success' => false,
                'Message' => 'Invalid EOS Disposition!'
            );
       }

       if(!array_key_exists($rawData->eos_sub_disposition_c,$app_list_strings['eos_sub_disposition_list'])) {
            
            $isDataValid=false;
            $msg = array(
                'Success' => false,
                'Message' => 'Invalid EOS Sub Disposition!'
            );
        }

        if(!array_key_exists($rawData->eos_opportunity_status_c,$app_list_strings['eos_opp_status_list'])) {
            
            $isDataValid=false;
            $msg = array(
                'Success' => false,
                'Message' => 'Invalid EOS Opportunity Status!'
            );
        }

        if(!array_key_exists($rawData->eos_opportunity_sub_status_c,$app_list_strings['eos_opp_substatus_list'])) {
            
            $isDataValid=false;
            $msg = array(
                'Success' => false,
                'Message' => 'Invalid EOS Opportunity Sub Status!'
            );
        }
       

       if($isDataValid){

           $leadId = $rawData->lead_id;
   
           if (isset($leadId) && !empty($leadId)) {
   
               $leadBean = new Lead();
               $leadBeanData=$leadBean->retrieve($leadId);
               
               if($leadBeanData){

                   $leadBeanData->eos_disposition_c = $rawData->eos_disposition_c;
                   $leadBeanData->eos_sub_disposition_c = $rawData->eos_sub_disposition_c;//$app_list_strings['eos_sub_disposition_list']
                   $leadBeanData->eos_opportunity_status_c = $rawData->eos_opportunity_status_c;//$app_list_strings['eos_opp_status_list']
                   $leadBeanData->eos_sub_status_c = $rawData->eos_opportunity_sub_status_c;//$app_list_strings['eos_opp_substatus_list']
                   $leadBeanData->eos_remark_c = $rawData->eos_remark_c;
                   $leadBeanData->date_updated_by_eos_c = date("Y-m-d H:i:s", strtotime($rawData->date_updated_by_eos_c)-(330*60));
       
                   $leadBeanData->save();
       
                   $msg = array(
                       'Success' => true,
                       'Message' => 'Lead Updated Successfully'
                   );

               }else{
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Unable To Find Lead With Given'
                    );
               }
           }
           else {
   
               $msg = array(
                   'Success' => false,
                   'Message' => 'Mandatory field(s) are missing. Lead ID Is Empty'
               );
               
           }
       }
}
else if ($module == "Lead" && $action == 'Fetch') {
    (isset($rawData->status) ? $status = $rawData->status : '');
    (isset($rawData->from_date) ? $from_date = $rawData->from_date : '');
    (isset($rawData->to_date) ? $to_date = $rawData->to_date : '');
    (isset($rawData->user) ? $user = $rawData->user : '');
    (isset($rawData->user_id) ? $user_id = $rawData->user_id : '');
    (isset($rawData->lead_id) ? $lead_id = $rawData->lead_id : '');
    if (!empty($from_date)) {
        $from_date = date('Y-m-d H:i:s', strtotime('-5 hours', strtotime($from_date)));
        $from_date = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($from_date)));
        $from_date = "AND leads.date_entered >= '$from_date'";
    }
    else $from_date = '';
    $to_date = get_var_value($rawData->to_date);
    if (!empty($to_date)) {
        $to_date = date('Y-m-d H:i:s', strtotime('-5 hours', strtotime($to_date)));
        $to_date = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($to_date)));
        $to_date = "AND leads.date_entered <= '$to_date'";
    }
    else {
        $to_date = '';
    }

    if (!isset($status) or empty($status)) {
        $status = '';
    }
    else {
        $status = "AND leads.status = '$status'";
    }

    if (empty($user_id) && empty($user) && empty($lead_id)) {
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing'
        );
    }
    else {
        if (empty($user_id) && !empty($user)) $user_id = getUserId($session_id, 'Users', $user, $url);
        if (!empty($lead_id)) {
            $query = "leads.id = '$lead_id'";
        } else {
            $query = " leads.assigned_user_id = '$user_id' $lead_status $from_date $to_date";
        }
        global $db;
        global $app_list_strings;
        $query = "select leads.*,leads_cstm.* from leads join leads_cstm on id=id_c where leads.deleted=0 and $query";
        $res = $db->query($query);
        $output=array();
        while($row = $db->fetchByAssoc($res)){
            $output[] = $row;
        }
        if (!empty($output)) {
            foreach ($output[0] as $k=>$v)
            {
                if ($k=='disposition_c'){
                    $output[0][$k]=$app_list_strings['cstm_disposition_list'][$v];
                }
                else if($k=='sub_disposition_c')
                {
                    $output[0][$k]=$GLOBALS['app_list_strings']['cstm_subdisposition_list'][$v];
                }
            }
            $msg = array(
                'Success' => true,
                'Message' => 'Leads records',
                'Leads' => $output
            );
        }else{
            $msg = array(
                'Success' => false,
                'Message' => 'No such lead found'
            );
        }

    }
}
else if ($module == "Opportunities" && $action == 'Update') {
    
    $logger->log('debug', 'Update Opportunities API Request =====>'.var_export($rawData, true));
    
    $oppId = $rawData->opportunity_id;
    
    if (isset($oppId) or !empty($oppId)) {

        $oppBean = new Opportunity();
        $oppBean=$oppBean->retrieve($oppId);
       
        if(!empty($oppBean)) {

            if (!empty($rawData->opportunity_stage)) $oppBean->sales_stage = $rawData->opportunity_stage;
            if (!empty($rawData->amount)) $oppBean->amount = $rawData->amount;
            if (!empty($rawData->feedback)) $oppBean->pickup_appointment_feedback_c = $rawData->feedback;
            if (!empty($rawData->remarks_c)) $oppBean->remarks_c = $rawData->remarks_c;
            if (!empty($rawData->source_type_c)) $oppBean->source_type_c = $rawData->source_type_c;
            if (!empty($rawData->loan_amount_sanctioned_c)) $oppBean->loan_amount_sanctioned_c = $rawData->loan_amount_sanctioned_c;
            if (!empty($rawData->user_id)) $oppBean->assigned_user_id = $rawData->user_id;
            if (!empty($rawData->pickup_appointment_city_c)) $oppBean->pickup_appointment_city_c = $rawData->pickup_appointment_city_c;
            if (!empty($rawData->application_id)) $oppBean->application_id_c = $rawData->application_id;
            if (!empty($rawData->opportunity_status)) $oppBean->opportunity_status_c = $rawData->opportunity_status;
            if (!empty($rawData->sub_status_c)) $oppBean->sub_status_c = $rawData->sub_status_c;
            if (!empty($rawData->pickup_appointment_date_c)) $oppBean->pickup_appointment_date_c = $rawData->pickup_appointment_date_c;
            if (!empty($rawData->control_program_c)) $oppBean->control_program_c = $rawData->control_program_c;
            if (!empty($rawData->stage_drop_off_c)) $oppBean->stage_drop_off_c = $rawData->stage_drop_off_c;
            if (!empty($rawData->app_form_link_c)) $oppBean->app_form_link_c = $rawData->app_form_link_c;
            if (!empty($rawData->Address_pin)) $oppBean->pickup_appointment_pincode_c = $rawData->Address_pin;
            if (!empty($rawData->Address_Street)) $oppBean->pickup_appointment_address_c = $rawData->Address_Street;
            if (!empty($rawData->reject_reason_c)) $oppBean->reject_reason_c = $rawData->reject_reason_c;
            if (!empty($rawData->is_eligible)) $oppBean->is_eligible = $rawData->is_eligible;
            
            $oppId=$oppBean->save();
            
            if(!empty($oppId)){
                $msg = array(
                    'Success' => true,
                    'Message' => 'Opportunity Updated Successfully'
                );   
            }
            else{
                $logger->log('error', 'Unable To Update Opportunity');
            }

        } else {
            $msg = array(
                'Success' => false,
                'Message' => 'Unable To Update Opportunity.'
            );
            $logger->log('error', 'Unable To Find Data With Request Opp ID: '.$oppId);
        }
        
    }
    else {
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing. Empty Opportunity ID.'
        );
        $logger->log('error', 'UMandatory field(s) are missing. Empty Opportunity ID.');
    }
    
    
}
else if ($module == "Opportunities" && $action == 'Fetch') {

    $logger->log('debug', 'Fetch Opportunities API Request =====>'.var_export($rawData, true));

    (isset($rawData->status) ? $status = $rawData->status : '');
    (isset($rawData->from_date) ? $from_date = $rawData->from_date : '');
    (isset($rawData->to_date) ? $to_date = $rawData->to_date : '');
    (isset($rawData->user) ? $user = $rawData->user : '');
    (isset($rawData->user_id) ? $user_id = $rawData->user_id : '');
    (isset($rawData->opp_id) ? $opp_id = $rawData->opp_id : '');
    (isset($rawData->offset) ? $offset = $rawData->offset : '');
    (isset($rawData->max_results) ? $max_results = $rawData->max_results : '');
    if (!empty($from_date)) {
        $from_date = date('Y-m-d H:i:s', strtotime('-5 hours', strtotime($from_date)));
        $from_date = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($from_date)));
        $from_date = "AND opportunities.date_entered >= '$from_date'";
    }
    else $from_date = '';
    $to_date = $rawData->to_date;
    if (!empty($to_date)) {
        $to_date = date('Y-m-d H:i:s', strtotime('-5 hours', strtotime($to_date)));
        $to_date = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($to_date)));
        $to_date = "AND opportunities.date_entered <= '$to_date'";
    }
    else {
        $to_date = '';
    }

    if (!isset($status) or empty($status)) {
        $status = '';
    }
    else {
        $status = "AND opportunities.status = '$status'";
    }
    if (empty($user_id) && empty($user) && empty($opp_id) && empty($opp_id_list)) {
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing'
        );
    }
    else {
        if (empty($user_id) && !empty($user)) $user_id = getUserId($session_id, 'Users', $user, $url);
        $where_query = "";
        if (empty($user_id)) {
            if(is_array($opp_id)){
                $where_query = "opportunities.id in ('".implode("','",$opp_id)."')";
            }else{
                $where_query = "opportunities.id in ('$opp_id')";
            }
        } else {
            $where_query = "opportunities.assigned_user_id = '$user_id' $from_date $to_date";
        }
        
        global $db;
        $user_query="";
        if(!empty($user_id))$user_query = "AND opportunities.assigned_user_id = '$user_id'";
        $query = "select opportunities.*,opportunities_cstm.*,leads.id as lead_id from opportunities join opportunities_cstm on id=id_c join leads on leads.opportunity_id = opportunities.id  where opportunities.deleted=0 and $where_query $status";
        $res = $db->query($query);
        $output=array();
        while($row = $db->fetchByAssoc($res)){
            $output[] = $row;
        }
        if (!empty($output)) {
            $msg = array(
                'Success' => true,
                'Message' => 'My Opportunites list',
                'Opportunities' => $output
            );
        }else{
            $msg = array(
                'Success' => false,
                'Message' => 'No such opportunity found'
            );
        }
        
    }
}
else if ($module == "Meeting" && ($action == 'Create' ||$action == 'Update')) {

    $logger->log('debug', $action .' Meeting API Request =====>'.var_export($rawData, true));    

    if (empty($rawData->name) or empty($rawData->status) or empty($rawData->date_start)) {
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing'
        );
    }
    else {
        
        $meeting = new Meeting();
        $record = NULL;
        if($action=='Update'){
            $record = $meeting->retrieve($rawData->id);
            if(empty($record)){
                $msg = array(
                    'Success' => false,
                    'Message' => "Meeting $rawData->id not found in DB"
                );
            }
        }
        if($action=='Create' || !empty($record)){
            foreach($rawData as $k=>$v){
                if($k=='parent_module')$k='parent_type';
                else if($k=='user_id')$k='assigned_user_id';
                else if($k=='date_start' ||$k=='date_end'){$v = date( 'Y-m-d H:i:s', strtotime($v) );}
                $meeting->{$k} = $v;
            }

            $meeting->popup = 0;
            $meeting->email_reminder_time = '300';
            $meeting->reminder_time = '300';
            $id = $meeting->save();
            if (empty($id) || $id == 'null') {
                
                $msg = array(
                    'Success' => false,
                    'Message' => 'Error occured creating Meeting.',
                );
            }else{
                $msg = array(
                    'Success' => true,
                    'Message' => "Meeting ".$action."d Successfully",
                    'Meeting ID' => $id
                );
            }
        }
        
    }
}
else if ($module == "Meeting" && $action == 'Fetch') {

    $logger->log('debug', 'Fetch Meeting API Request =====>'.var_export($rawData, true));  

    (isset($rawData->parent_id) ? $parent_id = $rawData->parent_id : '');
    (isset($rawData->user_id) ? $user_id = $rawData->user_id : '');
    (isset($rawData->from_date) ? $from_date = $rawData->from_date : '');
    if (!empty($from_date)) {
        $from_date = date('Y-m-d H:i:s', strtotime('-5 hours', strtotime($from_date)));
        $from_date = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($from_date)));
        $from_date = "AND meetings.date_entered >= '$from_date'";
    }
    else $from_date = '';
    $to_date = $rawData->to_date;
    if (!empty($to_date)) {
        $to_date = date('Y-m-d H:i:s', strtotime('-5 hours', strtotime($to_date)));
        $to_date = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($to_date)));
        $to_date = "AND meetings.date_entered <= '$to_date'";
    }
    else {
        $to_date = '';
    }

    if (!isset($user_id) or empty($user_id)) {
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing'
        );
    }
    else {
        if (!empty($user_id)) {
            $user_id = "meetings.assigned_user_id = '$user_id'";
        }
        else {
            $user_id = '';
        }
        if(empty($parent_id)){
            $query = "$user_id $from_date $to_date";
        } else {
            $query = "meetings.parent_id = '$parent_id' AND $user_id $from_date $to_date";
        }
        $select_query = "select meetings.*,meetings_cstm.* from meetings join meetings_cstm on id=id_c  where meetings.deleted=0 and $query";
    
        global $db;
        $res = $db->query($select_query);
        $output=array();
        while($row = $db->fetchByAssoc($res)){
            $output[] = $row;
        }

        $msg = array(
            'Success' => true,
            'Message' => 'meetings records',
            'Meetings' => $output
        );
    }
}
else {
    $msg = array(
        'Success' => false,
        'Message' => 'Oops! Something went wrong'
    );
}
}

echo json_encode($msg);
exit;




function getUserBean($user_name){
    $bean = BeanFactory::getBean('Users');
    $query = 'users.deleted=0 and users.user_name = "'.$user_name.'"';
    $items = $bean->get_full_list('',$query);
    if(!empty($items)){
        $items[0]->load_relationship('aclroles');
        return $items[0];
    }
    return null;
}

function call_login($method, $parameters, $url) {
    $_headers = apache_request_headers();

    $jsonEncodedData = json_encode($parameters);

    $post = array(
        "method" => $method,
        "input_type" => "JSON",
        "response_type" => "JSON",
        "rest_data" => $jsonEncodedData
    );

    $final_url = $url . "?" . http_build_query($post);

    $cookie_file = "/tmp/cookie_" . $_SERVER['PHP_AUTH_USER'] . ".txt";

    $cmd = 'curl "' . $final_url . '"';

    if (!empty($_headers['Authorization'])) {
        $cmd = $cmd . ' -H "Authorization: ' . $_headers['Authorization'] . '"';
    }

    $cmd = $cmd . ' -k -L --cookie ' . $cookie_file . ' --cookie-jar ' . $cookie_file . ' -0';

    exec($cmd, $result);

    $result = $result[0];

    return $result;
}

function getMeetings($sessionID, $module, $parent_id, $user_id, $from_date, $to_date, $url) {

    if(empty($parent_id)){
        $query = "$user_id $from_date $to_date";
    } else {
        $query = "meetings.parent_id = '$parent_id' AND $user_id $from_date $to_date";
    }
    $select_fields = array(
            'name',
            'status',
            'date_start',
            'date_end',
            'location',
            'duration_hours',
            'duration_minutes',
            'parent_id',
            'parent_type',
            'reminder_time',
            'email_reminder_time',
            'date_entered',
            'description',
            'nature_of_visit',
            'visit_purpose',
            'cust_name',
            'cust_mobile',
            'industry',
            'sub_supplier',
            'controlled_program'
        );
    $select_query = "select ";
    foreach($select_fields as $field){
        $select_query .= $field.", ";
    }
    $select_query = rtrim($select_query,", ");
    $select_query .= " from meetings where $query";
    
    global $db;
    $res = $db->query($select_query);
    $output=array();
    while($row = $db->fetchByAssoc($res)){
        $output[] = $row;
    }

    return $output;
}

function getUserId($sessionID, $module, $user_name, $url) {

    $get_entry_list_parameters = array(
        'session' => $sessionID,
        'module_name' => $module,
        'query' => "users.user_name = '$user_name'",
        'order_by' => "",
        'offset' => 0,
        'select_fields' => array(
            'id'
        ),
        'link_name_to_fields_array' => '',
        'max_results' => 2,
        'deleted' => 0
    );

    $result = call("get_entry_list", $get_entry_list_parameters, $url);

    $ID = $result->entry_list[0]->id;
    return $ID;
}

function getUserRole($user_id) {
    require_once 'modules/ACLRoles/ACLRole.php';
    $objACLRole = new ACLRole();
    $roles = $objACLRole->getUserRoles($user_id);
    if(in_array('Customer Acquisition Manager',$roles) 
        || in_array('Renewal manager',$roles) 
        || in_array('Renewal Location caller',$roles) ) {
        return true;
    }

    return false;
}

function validate_mobile($mobile){
    return preg_match('/^[0-9]{10}+$/', $mobile);
}

function checkDuplicateLead( $mobile,$scheme_c) {
    global $db;
    if(empty($date_entered))
        $date_entered = date("Y-m-d");

    $query  = "select id,scheme_c from leads l join leads_cstm lcstm where deleted = 0 and phone_mobile = '$mobile' and lcstm.scheme_c='$scheme_c'and date_entered> CURDATE() - INTERVAL 30 DAY order by date_entered desc limit 1";
    $result = $db->query($query);
    while (($row = $db->fetchByAssoc($result)) != null) {
        $logger=new CustomLogger('crmapi-2.0');
        $logger->log('debug', 'Dedup Query Result: '.print_r($row,true));
        $lead_id = $row['id'];
    }
    return $lead_id;
}

function getOppID($lead_id) {
    global $db;
    $query  = "select opportunity_id as opp_id from leads where deleted = 0 and id = '$lead_id'";
    $result = $db->query($query);
    $row    = $db->fetchByAssoc($result);
    $opp_id = $row['opp_id'];

    return $opp_id;
}

function get_var_value($var){
    if(!empty($var))
        return $var;
    return "";
}

?>