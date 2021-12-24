<?php
if (!defined('sugarEntry'))
define('sugarEntry', true);

if (!defined('sugarEntry') || !sugarEntry)
die('Not A Valid Entry Point');

require_once('include/entryPoint.php');
require_once 'vendor/autoload.php';
require_once 'custom/CustomLogger/CustomLogger.php';

global $db, $sugar_config;

$logger =new CustomLogger('crmapi-2.0');

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
    'Dsa_leads',
    'Check_Duplicate_Lead'

);
$apiAction = array(
    'Create',
    'Update',
    'Fetch',
    'Audit',
    'Create_ETL',
    'Transacting',
    'EosUpdate'
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

                    // Converting pickup_appointment_city_c to primary_address_city because leads dosen't contain pickup_appointment_city_c field
                    ($k == 'pickup_appointment_city_c') ? $k='primary_address_city': "";

                    // Field Mapping with new schema

                    ($k == 'source_type') ? $k='source_type_c': "";
                    ($k == 'scheme') ? $k='scheme_c': "";
                    ($k == 'original_app_id') ? $k='original_app_id_c': "";
                    ($k == 'is_renewal') ? $k='is_renewal_c': "";
                    ($k == 'stage_drop_off') ? $k='stage_drop_off_c': "";
                    ($k == 'control_program') ? $k='control_program_c': "";
                    ($k == 'app_form_link') ? $k='app_form_link_c': "";
                    ($k == 'gst_registration') ? $k='gst_registration_c': "";
                    ($k == 'sales_3_month') ? $k='sales_3_month_c': "";
                    ($k == 'indicative_deal_amount') ? $k='indicative_deal_amount_c': "";
                    ($k == 'Alliance_Lead_Docs_shared') ? $k='Alliance_Lead_Docs_shared_c': "";
                    ($k == 'turnover') ? $k='turnover_c': "";


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
                    
                    global $db;
                    $query  = "select opportunity_id as opp_id from leads where deleted = 0 and id = '$id'";
                    $result = $db->query($query);
                    $row    = $db->fetchByAssoc($result);
                    $opp_id = $row['opp_id'];

                    if(!empty($opp_id)){

                        $opp_bean = new Opportunity();
                        $opp_bean->retrieve($opp_id);

                        $msg['Opportunity id']=$opp_bean->id;
                                               
                        $logger->log('debug', 'Lead Is Also Converted to Opportunity With Id: '.$opp_id);
                    }

                }
            }

        }
        

    }
    else if ($module == "Lead" && $action == 'EosUpdate') {
        
            $logger->log('debug', 'EosUpdate Lead API Request =====>'.var_export($rawData, true));
            
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
                            'Message' => 'Unable To Find Lead With Given ID'
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
    else if ($module == "Lead" && $action == 'Update') {
        
        $logger->log('debug', 'Update Lead API Request =====>'.var_export($rawData, true));
        
        $lead_id = $rawData->lead_id;
        
        if (!isset($lead_id) or empty($lead_id)) {
            $msg = array(
                'Success' => false,
                'Message' => 'Mandatory field(s) are missing'
            );
        }
        else if(!empty($rawData->disposition_c) &&  ($rawData->disposition_c =='interested' || $rawData->disposition_c=='pick_up') && empty($rawData->pickup_appointment_city_c))
        {  
            # This has execute only for the disposition is interested or pick and pick up is empty.
            $msg = array(
                'Success' => false,
                'Message' => 'Please select pickup city to continue.'
            );
            
        } else {
            
            $lead = new Lead();
            $retrieved_data  = $lead->retrieve($lead_id);
            
            if(!empty($retrieved_data)){

                foreach($rawData as $k=>$v){

                    if ($k == 'Description') $v = htmlentities($v);

                    if ($k == 'pickup_appointment_city_c' or $k == 'phone_mobile') {
                        continue;
                    }
                    
                    ($k == 'Alliance_Lead_Docs_shared') ? $k='Alliance_Lead_Docs_shared_c': "";
                    
                    $lead->{$k} = $v;
                }

                $id = $lead->save();

                if (!$id) {

                    $msg = array(
                        'Success' => false,
                        'Message' => 'Lead was not updated'
                    );

                }else{
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Lead Updated Successfully.'
                    );
                }
                
            }else{
                $msg = array(
                    'Success' => false,
                    'Message' => "Lead $lead_id not found in DB"
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

                function change_array_key($array,$newkey,$oldkey){
                    $array[0][$newkey] = $array[0][$oldkey];
                    unset($array[0][$oldkey]);
                    return $array;
                }

                foreach ($output[0] as $k=>$v){

                    if ($k=='disposition_c'){
                        $output[0][$k]=$app_list_strings['cstm_disposition_list'][$v];
                    }

                    else if($k=='sub_disposition_c'){
                        $output[0][$k]=$GLOBALS['app_list_strings']['cstm_subdisposition_list'][$v];
                    }

                    // Mapping new to old parameter
                    ($k == 'utm_adgroup_c') ? $output= change_array_key($output,'utm_adgroup','utm_adgroup_c'):"";
                    ($k == 'utm_term_c') ? $output= change_array_key($output,'utm_term','utm_term_c'):"";
                    ($k == 'utm_content_c') ? $output= change_array_key($output,'utm_content','utm_content_c'):"";
                    ($k == 'original_app_id_c') ? $output= change_array_key($output,'original_app_id','original_app_id_c'):"";
                    ($k == 'is_renewal_c') ? $output= change_array_key($output,'is_renewal','is_renewal_c'):"";
                    ($k == 'gst_registration_c') ? $output= change_array_key($output,'gst_registration','gst_registration_c'):"";
                    ($k == 'product_type_c') ? $output= change_array_key($output,'product_type','product_type_c'):"";
                    ($k == 'pushed_lead_c') ? $output= change_array_key($output,'pushed_lead','pushed_lead_c'):"";
                    ($k == 'push_count_c') ? $output= change_array_key($output,'push_count','push_count_c'):"";
                    ($k == 'bank_account_name_c') ? $output= change_array_key($output,'bank_account_name','bank_account_name_c'):"";
                    ($k == 'bank_account_type_c') ? $output= change_array_key($output,'bank_account_type','bank_account_type_c'):"";
                    ($k == 'bank_account_count_c') ? $output= change_array_key($output,'bank_account_count','bank_account_count_c'):"";
                    ($k == 'accept_online_c') ? $output= change_array_key($output,'accept_online','accept_online_c'):"";
                    ($k == 'indicative_deal_amount_c') ? $output= change_array_key($output,'indicative_deal_amount','indicative_deal_amount_c'):"";
                    ($k == 'source_type_c') ? $output= change_array_key($output,'source_type','source_type_c'):"";
                    ($k == 'turnover_c') ? $output= change_array_key($output,'turnover','turnover_c'):"";
                    ($k == 'hear_about_us_c') ? $output= change_array_key($output,'hear_about_us','hear_about_us_c'):"";
                    ($k == 'mention_the_detail_c') ? $output= change_array_key($output,'mention_the_detail','mention_the_detail_c'):"";
                    ($k == 'scheme_c') ? $output= change_array_key($output,'scheme','scheme_c'):"";
                    ($k == 'loan_moratorium_c') ? $output= change_array_key($output,'loan_moratorium','loan_moratorium_c'):"";
                    ($k == 'sales_3_month_c') ? $output= change_array_key($output,'sales_3_month','sales_3_month_c'):"";
                    ($k == 'has_shop_c') ? $output= change_array_key($output,'has_shop','has_shop_c'):"";
                    ($k == 'Alliance_Lead_Docs_shared_c') ? $output= change_array_key($output,'Alliance_Lead_Docs_shared','Alliance_Lead_Docs_shared_c'):"";
                    ($k == 'average_monthly_sales_c') ? $output= change_array_key($output,'average_monthly_sales','average_monthly_sales_c'):"";
                    ($k == 'control_program_c') ? $output= change_array_key($output,'control_program','control_program_c'):"";
                    ($k == 'stage_drop_off_c') ? $output= change_array_key($output,'stage_drop_off','stage_drop_off_c'):"";
                    ($k == 'app_form_link_c') ? $output= change_array_key($output,'app_form_link','app_form_link_c'):"";              
                }
                
                $logger->log('debug', 'Lead Fetch Response =====>'.var_export($output, true));

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
    else if($module == "Dsa_leads" && $action == 'Fetch'){
        
        $logger->log('debug', 'Dsa_leads Lead Fetch API Request =====>'.var_export($rawData, true));
        
        if(empty($rawData->dsa_code)){
            $msg = array(
                'Success' => false,
                'Message' => 'dsa_code field is missing'
            );
        } else {
            
            $dsa_code=$rawData->dsa_code;
    
            if(!empty($dsa_code)){
                
                global $db;

                $query ="SELECT 
                    l.id AS lead_id,
                    o.id AS opportunity_id,
                    l.date_entered,
                    l.assigned_user_id AS lead_assigned_user,
                    l.description AS lead_description,
                    l.first_name AS lead_first_name,
                    l.last_name AS lead_last_name,
                    l.title AS lead_title,
                    l.phone_mobile AS lead_phone_number,
                    l.primary_address_street AS lead_primary_address_street,
                    l.primary_address_city AS lead_primary_address_city,
                    l.lead_source AS lead_lead_source,
                    lc.source_type_c AS lead_source_type,
                    lc.dsa_id_c AS lead_dsa_id,
                    lc.business_type_c AS lead_business_type_c,
                    lc.disposition_c AS lead_disposition_c,
                    lc.industry_type_c AS lead_industry_type_c,
                    lc.loan_amount_c AS lead_loan_amount_c,
                    lc.merchant_name_c AS lead_merchant_name_c,
                    lc.merchant_type_c AS lead_merchant_type_c,
                    lc.sub_source_c AS lead_sub_source_c,
                    lc.dsa_code_c AS lead_dsa_code_c,
                    lc.sub_disposition_c AS sub_disposition_c,
                    lc.customer_id_c AS lead_customer_id_c,
                    lc.nature_of_business_c AS lead_nature_of_business_c,
                    o.name AS opportunity_name,
                    o.description AS opportunity_description,
                    o.assigned_user_id AS opportunity_assigned_user_id,
                    o.opportunity_type AS opportunity_type,
                    o.campaign_id AS opportunity_campaign_id,
                    o.lead_source AS opportunity_lead_source,
                    o.amount AS opportunity_amount,
                    oc.original_app_id_c AS opportunity_original_app_id,
                    oc.is_renewal_c AS is_renewal,
                    oc.date_funded_c AS opportunity_id,
                    insurance_c,
                    oc.product_type_c AS opportunity_product_type,
                    oc.source_type_c AS opportunity_source_type,
                    industry_c,
                    oc.control_program_c AS opportunity_control_program,
                    oc.stage_drop_off_c AS stage_drop_off,
                    oc.app_form_link_c AS app_form_link,
                    oc.eos_disposition_c,
                    oc.eos_sub_disposition_c,
                    reject_reason_c,
                    is_eligible_c,
                    oc.loan_amount_c AS loan_amount_c,
                    pickup_appointment_date_c,
                    pickup_appointment_address_c,
                    pickup_appointment_pincode_c,
                    pickup_appointment_contact_c,
                    application_id_c,
                    oc.merchant_name_c AS opportunity_merchant_name_c,
                    pickup_appointment_city_c,
                    oc.sub_source_c AS opportunity_sub_source_c,
                    oc.dsa_code_c AS dsa_code_c,
                    oc.loan_amount_sanctioned_c AS loan_amount_sanctioned_c,
                    opportunity_status_c
                FROM
                    leads l
                        JOIN
                    leads_cstm lc ON l.id = lc.id_c
                        JOIN
                    opportunities o ON l.opportunity_id = o.id
                        JOIN
                    opportunities_cstm oc ON l.opportunity_id = oc.id_c
                    
                    WHERE
                        lc.dsa_code_c LIKE '%".$dsa_code."%'";
            
            
            $res = $db->query($query);
            $output=array();
            
            $logger->log('debug', 'Dsa_leads Lead Fetch Query: '.$query);
            $logger->log('debug', 'Dsa_leads Lead Fetch Query: '.var_export($res,true));
                 while($row = $db->fetchByAssoc($res)){                
                     $output = $row;
                     $logger->log('debug', 'Dsa_leads Output inside while: '.$row);
                }

                $logger->log('debug', 'Dsa_leads Output: '.var_export($output,true));

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
    }
    else if($module == "Check_Duplicate_Lead" && $action == "Fetch"){
        
        $logger->log('debug', 'Check_Duplicate_Lead Fetch API Request =====>'.var_export($rawData, true));

        if (validate_mobile(trim($rawData->phone_mobile)) != 1) {
            $logger->log('error', 'Invalid Mobile Number Present In The Request....!');

            $msg = array(
                'Success' => false,
                'Message' => 'Please enter a valid 10 digit Mobile Number.'
            );
        }else{

            $lead_id = checkDuplicateLead($rawData->phone_mobile,'');

            if(!empty($lead_id)){
                $msg = array(
                      'Success' => false,
                      'Message'=>"Sorry, we have a record in our database that matches your lead details. Please re-check and create again.",
                      'Info' => "Lead already exist with similar details id = '$lead_id'",
                      'lead_id' =>$lead_id
                  );
              } else {
                $msg = array(
                    'Success' => true,
                    'Message'=>"we don't have a record in our database.",
                    'Info' => "Lead not exist with the mobile number",
                    'mobile_number' =>$rawData->phone_mobile
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

                // Data Validation
                $isDataValid=true;

                $amount_regEx="/^[0-9]*$/";
                $source_type_c_regEX="/^[a-zA-Z ]*$/";
                $loan_amount_sanctioned_c_regEx="/^[0-9]*$/";
                $application_id_c_regEx="/^[0-9]*$/";
                $control_program_c_regEx ="/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/";
                $stage_drop_off_c_regEx ="/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/";
                $pickup_appointment_pincode_c_regEx="/^[1-9][0-9]{5}$/";
                $pickup_appointment_address_c_regEX="/^[#.0-9a-zA-Z\s,-]+$/";

                if(!empty($rawData->amount) && !preg_match($amount_regEx,$rawData->amount)){
            
                    $isDataValid=false;
        
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Invalid Amount Should Be Digit Only !'
                    );
                }

                if(!empty($rawData->source_type_c) && !preg_match($source_type_c_regEX,$rawData->source_type_c)){
            
                    $isDataValid=false;
        
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Invalid Source Type Should Not Contain Any Special Character !'
                    );
                }

                if(!empty($rawData->loan_amount_sanctioned_c) && !preg_match($loan_amount_sanctioned_c_regEx,$rawData->loan_amount_sanctioned_c)){
            
                    $isDataValid=false;
        
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Invalid Loan Amount Sanctioned Should Be Digit Only !'
                    );
                }

                if(!empty($rawData->control_program_c) && !preg_match($control_program_c_regEx,$rawData->control_program_c)){
            
                    $isDataValid=false;
        
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Invalid Control Program Should Not Contain Any Special Character !'
                    );
                }

                if(!empty($rawData->stage_drop_off_c) && !preg_match($stage_drop_off_c_regEx,$rawData->stage_drop_off_c)){
            
                    $isDataValid=false;
        
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Invalid Stage Drop Off Should Not Contain Any Special Character !'
                    );
                }
                
                if(!empty($rawData->Address_pin) && !preg_match($pickup_appointment_pincode_c_regEx,$rawData->Address_pin)){
            
                    $isDataValid=false;
        
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Invalid Address PIN !'
                    );
                }

                if(!empty($rawData->Address_Street) && !preg_match($pickup_appointment_address_c_regEX,$rawData->Address_Street)){
            
                    $isDataValid=false;
        
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Invalid Street Address Shoud contain alphabets , numbers and these special character(#,-.) !'
                    );
                }

                if(filter_var($rawData->app_form_link_c, FILTER_VALIDATE_URL) === FALSE){
                    
                    $isDataValid=false;
        
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Invalid App Form Link!'
                    );
                }
                
                if($isDataValid){

                    if (!empty($rawData->opportunity_stage)) $oppBean->sales_stage = $rawData->opportunity_stage;
                    if (!empty($rawData->amount)) $oppBean->amount = $rawData->amount;
                    if (!empty($rawData->feedback)) $oppBean->pickup_appointment_feedback_c = $rawData->feedback;
                    if (!empty($rawData->remarks)) $oppBean->remarks_c = $rawData->remarks;
                    if (!empty($rawData->source_type)) $oppBean->source_type_c = $rawData->source_type;
                    if (!empty($rawData->loan_amount_sanctioned_c)) $oppBean->loan_amount_sanctioned_c = $rawData->loan_amount_sanctioned_c;
                    if (!empty($rawData->user_id)) $oppBean->assigned_user_id = $rawData->user_id;
                    if (!empty($rawData->pickup_appointment_city_c)) $oppBean->pickup_appointment_city_c = $rawData->pickup_appointment_city_c;
                    if (!empty($rawData->application_id)) $oppBean->application_id_c = $rawData->application_id;
                    if (!empty($rawData->opportunity_status)) $oppBean->opportunity_status_c = $rawData->opportunity_status;
                    if (!empty($rawData->sub_status)) $oppBean->sub_status_c = $rawData->sub_status;
                    if (!empty($rawData->pickup_appointment_date_c)) $oppBean->pickup_appointment_date_c = $rawData->pickup_appointment_date_c;
                    if (!empty($rawData->control_program)) $oppBean->control_program_c = $rawData->control_program;
                    if (!empty($rawData->stage_drop_off)) $oppBean->stage_drop_off_c = $rawData->stage_drop_off;
                    if (!empty($rawData->app_form_link)) $oppBean->app_form_link_c = $rawData->app_form_link;
                    if (!empty($rawData->Address_pin)) $oppBean->pickup_appointment_pincode_c = $rawData->Address_pin;
                    if (!empty($rawData->Address_Street)) $oppBean->pickup_appointment_address_c = $rawData->Address_Street;
                    if (!empty($rawData->reject_reason)) $oppBean->reject_reason_c = $rawData->reject_reason;
                    if (!empty($rawData->is_eligible)) $oppBean->is_eligible_c = $rawData->is_eligible;
                    if (!empty($rawData->alliance_opportunities_status)) $oppBean->alliance_opp_status_c = $rawData->alliance_opportunities_status;
                   
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

                function change_array_key($array,$newkey,$oldkey){
                    $array[0][$newkey] = $array[0][$oldkey];
                    unset($array[0][$oldkey]);
                    return $array;
                }

                foreach ($output[0] as $k=>$v){
                    
                    // Mapping new to old parameter
                    ($k == 'original_app_id_c') ? $output= change_array_key($output,'original_app_id','original_app_id_c'):"";
                    ($k == 'is_renewal_c') ? $output= change_array_key($output,'is_renewal','is_renewal_c'):"";
                    ($k == 'date_funded_c') ? $output= change_array_key($output,'date_funded','date_funded_c'):"";
                    ($k == 'insurance_c') ? $output= change_array_key($output,'insurance','insurance_c'):"";
                    ($k == 'APR_c') ? $output= change_array_key($output,'APR','APR_c'):"";
                    ($k == 'processing_fees_c') ? $output= change_array_key($output,'processing_fees','processing_fees_c'):"";
                    ($k == 'product_type_c') ? $output= change_array_key($output,'product_type','product_type_c'):"";
                    ($k == 'sub_status_c') ? $output= change_array_key($output,'sub_status','sub_status_c'):"";
                    ($k == 'scheme_c') ? $output= change_array_key($output,'scheme','scheme_c'):"";
                    ($k == 'Alliance_Lead_Docs_shared_c') ? $output= change_array_key($output,'Alliance_Lead_Docs_shared','Alliance_Lead_Docs_shared_c'):"";
                    ($k == 'sms_count_c') ? $output= change_array_key($output,'sms_count','sms_count_c'):"";
                    ($k == 'source_type_c') ? $output= change_array_key($output,'source_type','source_type_c'):"";
                    ($k == 'seller_id_online_platform_c') ? $output= change_array_key($output,'seller_id_online_platform','seller_id_online_platform_c'):"";
                    ($k == 'seller_partner_rating_online_platform_c') ? $output= change_array_key($output,'seller_partner_rating_online_platform','seller_partner_rating_online_platform_c'):"";
                    ($k == 'seller_customer_rating_online_platform_c') ? $output= change_array_key($output,'seller_customer_rating_online_platform','seller_customer_rating_online_platform_c'):"";
                    ($k == 'business_age_in_months_c') ? $output= change_array_key($output,'business_age_in_months','business_age_in_months_c'):"";
                    ($k == 'settlement_cycle_in_days_c') ? $output= change_array_key($output,'settlement_cycle_in_days','settlement_cycle_in_days_c'):"";
                    ($k == 'partner_id_c') ? $output= change_array_key($output,'partner_id','partner_id_c'):"";
                    ($k == 'industry_c') ? $output= change_array_key($output,'industry','industry_c'):"";
                    ($k == 'sales_3_month_c') ? $output= change_array_key($output,'sales_3_month','sales_3_month_c'):"";
                    ($k == 'push_count_c') ? $output= change_array_key($output,'push_count','push_count_c'):"";
                    ($k == 'date_sent_to_EOS_c') ? $output= change_array_key($output,'date_sent_to_EOS','date_sent_to_EOS_c'):"";
                    ($k == 'date_updated_by_EOS_c') ? $output= change_array_key($output,'date_updated_by_EOS','date_updated_by_EOS_c'):"";
                    ($k == 'eos_disposition_c') ? $output= change_array_key($output,'eos_disposition','eos_disposition_c'):"";
                    ($k == 'eos_sub_disposition_c') ? $output= change_array_key($output,'eos_sub_disposition','eos_sub_disposition_c'):"";
                    ($k == 'control_program_c') ? $output= change_array_key($output,'control_program','control_program_c'):"";
                    ($k == 'stage_drop_off_c') ? $output= change_array_key($output,'stage_drop_off','stage_drop_off_c'):"";
                    ($k == 'app_form_link_c') ? $output= change_array_key($output,'app_form_link','app_form_link_c'):"";
                    ($k == 'reject_reason_c') ? $output= change_array_key($output,'reject_reason','reject_reason_c'):"";
                    ($k == 'eos_sub_status_c') ? $output= change_array_key($output,'eos_sub_status','eos_sub_status_c'):"";
                    ($k == 'eos_opportunity_status_c') ? $output= change_array_key($output,'eos_opportunity_status','eos_opportunity_status_c'):"";
                    ($k == 'sent_count_c') ? $output= change_array_key($output,'sent_count','sent_count_c'):"";
                    
                }

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
    /*else if($module == "Cases" && $action=='Update') {
        $logger->log('debug', 'Update Case API Request =====>'.var_export($rawData, true));
        global $db;
        if(empty($rawData->case_id)) {
            $msg = array(
                'Success' => false,
                'Message' => 'Mandatory field(s) are missing'
            );
        }
        else{
            $case_id = $rawData->case_id;
            $subcategory=$rawData->case_subcategory;
            $category=$rawData->case_category;
            $status=$rawData->status;
            $q="select * from cases where case_number=$case_id";
            $result = $db->query($q);
            if($result->num_rows== 0) {
                $msg = array(
                    'Success' => false,
                    'Message' => "Case no. $case_id not found in DB"
                );
            }
            else{
                $q="update cases s join cases_cstm c on s.id=c.id_c set c.case_subcategory_c='$subcategory',c.case_category_c='$category' where case_number='$case_id'";
                $result = $db->query($q);
            
                $msg = array(
                    'Success' => true,
                    'Message' => "case $case_id updated successfully"
                );
            }
        
        }
    }*/
    else if ($module == "Cases" && $action == 'Create') { 
        //financial_year,quarter,digitally_signed,is_call_back,call_back_30_min,
        $logger->log('debug', 'Create Case API Request =====>'.var_export($rawData, true));
        
        $merchant_name_c            = get_var_value($rawData->merchant_name_c);
        $merchant_app_id_c          = get_var_value($rawData->merchant_app_id_c);
        $merchant_contact_number_c  = get_var_value($rawData->merchant_contact_number_c);
        $merchant_email_id_c        = get_var_value($rawData->merchant_email_id_c);
        $description                = get_var_value($rawData->description);
        $case_location_c            = get_var_value($rawData->case_location_c);
        $subject                    = get_var_value($rawData->subject);
        $case_source_c              = get_var_value($rawData->case_source_c);
        $case_sub_source_c          = get_var_value($rawData->case_sub_source_c);
        $complaintaint_c            = get_var_value($rawData->complaintaint_c);
        $merchant_establisment_c    = get_var_value($rawData->merchant_establisment_c);
        $case_subcategory_c         = get_var_value($rawData->case_subcategory_c);
        $case_category_c            = get_var_value($rawData->case_category_c);
        $call_back_start_time_c     = get_var_value($rawData->call_back_start_time_c);
        $call_back_duration_c       = get_var_value($rawData->call_back_duration_c);
        //dont delete or modify this field '$is_call_back_c', based on this in data_sync we are skipping assigned_user_id = null
        $is_call_back_c             = get_var_value($rawData->is_call_back);
        $call_back_30_min_c         = get_var_value($rawData->call_back_30_min);
        $custom_case_type           = get_var_value($rawData->custom_case_type);
        $custom_s3_url              = get_var_value($rawData->custom_s3_url);
        $type                       = get_var_value($rawData->type);
        $financial_year_c           = get_var_value($rawData->financial_year);
        $digitally_signed_c         = get_var_value($rawData->digitally_signed);
        $quarter_c                  = get_var_value($rawData->quarter);
        $agent_user_id              = "";
        $recordExits                = FALSE;

        global $db;
            
        if (!empty($custom_case_type) && $custom_case_type=='merchant_app_tds_refund') {
            // $case_category_c = "financial_live";
            // $case_subcategory_c="financial_live_tds_refund";
            // $case_source_c = "merchant";

            $queryToGetCases = "select id from cases JOIN cases_cstm on cases.id = cases_cstm.id_c where cases_cstm.merchant_app_id_c = '$merchant_app_id_c' AND cases.deleted=0 AND financial_year_c = '$financial_year_c' AND quarter_c = '$quarter_c' ";
            $casesResults = $db->query($queryToGetCases);
            $casesResultNumberOfRows = $casesResults->num_rows;
            
            if ($casesResultNumberOfRows > 0) {
                $msg = array(
                    'Success' => false,
                    'Message' => 'You have already raised a TDS refund request for Application ID '.$merchant_app_id_c .' for '.$quarter_c.', '.$financial_year_c.'.'
                );
                echo json_encode($msg);
                return;     
            }
            
            if(empty($financial_year_c) || empty($quarter_c)){
                $msg = array(
                'Success' => false,
                'Message' => 'Mandatory field(s) Financial year / Quarter are missing'
                );
                echo json_encode($msg);
                return;
            } 
            $type="Request";
            $description .= "<br/> <b>Attachment by merchant</b>: $custom_s3_url<br/>";
        }
        if (!isset($merchant_name_c) or empty($merchant_name_c)) {
            $msg = array(
                'Success' => false,
                'Message' => 'Mandatory field(s) merchant name are missing'
            );
        }
        else if ($is_call_back_c == 1 and
                    (!isset($case_source_c) or empty($case_source_c)
                    or !isset($merchant_contact_number_c) or empty($merchant_contact_number_c)
                    or !isset($case_sub_source_c) or empty($case_sub_source_c)
                    or !isset($call_back_start_time_c) or empty($call_back_start_time_c)
                    or !isset($call_back_duration_c) or empty($call_back_duration_c)
                    )
                ) {
                $msg = array(
                    'Success' => false,
                    'Message' => 'Mandatory field(s) for is_call_back are missing [case source, merchant contact no, case sub source, call back start time, call back duration]'
                );
        }
        else {
            if (strtolower($case_sub_source_c) === "merchant_app") {
                global $app_list_strings;
                if (isset($case_category_c) and !empty($case_category_c)) {
                    if (!empty($cc = array_search($case_category_c,$app_list_strings['case_category_c_list']))) {
                        $case_category_c = $cc;
                    }
                }
                if (isset($case_subcategory_c) and !empty($case_subcategory_c)) {
                    if (!empty($csc = array_search($case_subcategory_c,$app_list_strings['case_subcategory_c_list']))) {
                        $case_subcategory_c = $csc;
                    }
                }
            }
            if($is_call_back_c){
                if(empty($subject)){
                    $subject = "Website Call Back";
                }
                $description = "Call back Time " . date("d/m/Y H:i", strtotime($call_back_start_time_c));
            }
            $name_value_list = array(
                array(
                    "name" => "merchant_name_c",
                    "value" => $merchant_name_c
                ) ,
                array(
                    "name" => 'merchant_app_id_c',
                    "value" => $merchant_app_id_c
                ) ,
                array(
                    "name" => "merchant_establisment_c",
                    "value" => $merchant_establisment_c
                ) ,
                array(
                    "name" => "merchant_contact_number_c",
                    "value" => $merchant_contact_number_c
                ) ,
                array(
                    "name" => "merchant_email_id_c",
                    "value" => $merchant_email_id_c
                ) ,
                array(
                    "name" => "description",
                    "value" => $description
                ) ,
                array(
                    "name" => "name",
                    "value" => $subject
                ) ,
                array(
                    "name" => "case_location_c",
                    "value" => strtolower($case_location_c)
                ) ,
                array(
                    "name" => "case_source_c",
                    "value" => strtolower($case_source_c)
                ) ,
                array(
                    "name" => "case_sub_source_c",
                    "value" => strtolower($case_sub_source_c)
                ) ,
                array(
                    "name" => "case_subcategory_c",
                    "value" => $case_subcategory_c
                ) ,
                array(
                    "name" => "case_category_c",
                    "value" => $case_category_c
                ) ,
                array(
                    "name" => "complaintaint_c",
                    "value" => $complaintaint_c
                ) ,
                array(
                    "name" => "call_back_30_min_c",
                    "value" => $call_back_30_min_c
                ) ,
                array(
                    "name" => "is_call_back_c",
                    "value" => $is_call_back_c
                ),
                array(
                    "name" => "type",
                    "value" => $type
                ),
                array(
                    "name" => "financial_year_c",
                    "value" => $financial_year_c
                ) ,
                array(
                    "name" => "quarter_c",
                    "value" => $quarter_c
                ),
                array(
                    "name" => "digitally_signed_c",
                    "value" => $digitally_signed_c
                ),
            );
            $case = BeanFactory::newBean('Cases');
            foreach($name_value_list as $array){
                $case->{$array['name']} = $array['value'];
            }
            
            $case->save();
            $id = $case->id;//createrecord($session_id, 'Cases', $name_value_list, $url);
            if ($id) {
                global $db;
                $subsource=array("merchant_app","website");
                if (in_array($case->case_sub_source_c, $subsource))
                {
                    $query="update cases set created_by='1' where id='".$id."'";
                    $db->query($query);
                }

                if ($is_call_back_c == 1){
                    require_once('custom/modules/Cases/CallBackFlow.php');
                    $call_back_flow = new CallBackFlow();
                    $call_back_flow->createCallBackFlow($id, $call_back_start_time_c, $call_back_duration_c);
                }            
                $msg = array(
                    'Success' => true,
                    'Message' => 'Case Created Successfully',
                    'Case ID' => $id
                );
            }
            else {
                $msg = array(
                    'Success' => false,
                    'Message' => 'Case Not Created ',
                    'Case ID' => $id
                );
            }
        }
    }
    else if($module == "Neo_Customers" && $action == "Fetch"){
        $logger->log('debug', 'Fetch Neo_Customers API Request =====>'.var_export($rawData, true));
        (isset($rawData->application_id) ? $application_id = $rawData->application_id : '');
        (isset($rawData->customer_id) ? $customer_id = $rawData->customer_id : '');
        //print_r($application_id);print_r($customer_id);
        if(empty($application_id) && empty($customer_id)){
            $msg = array(
                'Success' => false,
                'Message' => 'Mandatory field(s) are missing'
            );
        }
        else if(!empty($customer_id))
        {
            $bean = BeanFactory::getBean('Neo_Customers');
            $query = "neo_customers.deleted=0 and neo_customers.customer_id = $customer_id";
            $items = $bean->get_full_list('',$query);
            $results = array();
            if(!empty($items)){
                $result = array();
                foreach ($items as $item) {
                    $result['id'] = $item->id;
                    // $result['fresh_renewal'] = true;//$item->fresh_renewal;
                    $result['fresh_renewal'] = $item->fresh_renewal;
                    $result['renewal_eligible'] = $item->renewal_eligible;
                    $result['name'] = $item->name;
                    $result['mobile'] = $item->mobile;
                    $result['location'] = $item->location;
                    $result['scheme'] = $item->scheme;
                }
                $results[]=$result;
            }
            $msg = array(
                'Success' => true,
                'Message' => 'Customers',
                'Customers' => $results
            );
        }
        else if(!empty($application_id))
        {
            $bean = BeanFactory::getBean('Neo_Customers');
            $query = "neo_customers.deleted=0 and neo_customers.app_id = $application_id";
            
            $items = $bean->get_full_list('',$query);
            $results = array();
            if(!empty($items)){
                $result = array();
                foreach ($items as $item) {
                    $result['id'] = $item->id;
                    // $result['fresh_renewal'] = true;//$item->fresh_renewal;
                    $result['fresh_renewal'] = $item->fresh_renewal;
                    $result['renewal_eligible'] = $item->renewal_eligible;
                    $result['name'] = $item->name;
                    $result['mobile'] = $item->mobile;
                    $result['location'] = $item->location;
                    $result['scheme'] = $item->scheme;
                }
                $results[]=$result;
            }
           // $results = getNeoCustomer($session_id, 'Neo_Customers', $application_id, $url);
            $msg = array(
                'Success' => true,
                'Message' => 'Customers',
                'Customers' => $results
            );
        }
    }
    /*else if($module == "Users" && $action == "Create"){
        $logger->log('debug', 'Create Users API Request =====>'.var_export($rawData, true));
        try {
            // print_r($rawData->nguid);
            $result = json_decode(json_encode($rawData), TRUE);
            $message = "";
            $loginUserId = $result['nguid'];
            $loginUserId = array_keys($loginUserId);
            $user = new User;
            if(!empty($loginUserId)){
                $user->retrieve($loginUserId[0]);
            }
        
            if(empty($user->date_entered)) {
            //save for first time
                if(!empty($loginUserId) && !empty($result['cn'])&& !empty($result['username']) 
                && !empty($result['firstname'])) {
                    $user->new_schema = true;
                    $user->new_with_id = true;
                    $user->id = $loginUserId[0];
                    $user->name = $result['cn'];
                    $user->user_name = $result['username'];
        
                    $user->first_name = $result['firstname'];
                    $user->last_name = $result['lastname'];
                    $user->email1 = $result['email'];
                    // $user->department = $result['department'];
                    //creating users by sales app request - department will be sales
                    $user->department = 'SALES';
                    $user->is_admin = stripos($result['description'], 'crm') > -1;
                    $user->authenticated = true;
                    $user->description = $result['memberOf'];
                    $user->team_exists = false;
                    $user->table_name = "users";
                    $user->module_dir = 'Users';
                    $user->object_name = "User";
                    $user->status = "Active";
        
                    $user->importable = true;
                    $user->encodeFields = Array ("first_name", "last_name", "description");
                    $user->save();
                    $message .=  $result['sAMAccountName'][0]." user saved\n";
                    $success = true;
                    require_once('custom/include/SendEmail.php');
                    $email = new SendEmail();
                    $desc = json_encode($rawData);
                    require_once('custom/include/SendEmail.php');
                    // Updating CAM Role
                    require_once('custom/include/ng_utils.php');
                    $ng_utils = new Ng_utils();
                    $roleID = $ng_utils->fetchRoleIdFromName('Customer Acquisition Manager');
                    if (empty($roleID)) {
                    $message .= "Roles not found, Updation failed. Please contact admin or your supervisor";
                    $success = false;
                    }
                    $user->load_relationship('aclroles');
                    $status = $user->aclroles->add($roleID);
                    if($status){
                        $logger->log('debug', 'Added '.$user->id.' to '.$roleID);
                    }
                    else{
                        $message .= "Unable add user to the given role. Some error. $user->id";
                        $success = false;
                    }
                    //Assigning to sales security group
                    global $db;
                    $query = "select id from securitygroups where name = 'Sales Team'";
                    $results = $db->query($query);
                    $add_sg_id = '';
                    while($row = $db->fetchByAssoc($results)){
                        $add_sg_id = $row['id'];
                    }
                    $user->load_relationship('SecurityGroups');
                    if(!empty($add_sg_id)){
                        $status = $user->SecurityGroups->add($add_sg_id);
                        if($status){
                            $logger->log('debug','Added '.$user->id.' to '.$add_sg_id);
                        }
                        else{
                            $message .= "Unable add User to the given Security Group. Some error.";
                            $success = false;
                        }
                    }
                    else{
                        $message .= "Security group not found, Updation failed. Please contact admin or your supervisor";
                        $success = false;
                    }
                    $email->send_email_to_user("User Created in CRM Using Sales App",$desc,[$sugar_config['non_prod_merchant_email']], [$sugar_config['non_prod_merchant_CC_email']],null,array(),1);
                }
                else{
                    $message .=  "Missing Details in AD. Contact Admin";
                    $success = false;
                }
            }
            else{
                $message .=  "User already exit in CRM";
                $success = true;
            }
            
        } catch(Exception $e){
            $message .= "Exception in saving $nguid".$e->getMessage();
            $success = false;
        }
        $msg = array(
            'Success' => $success,
            'Message' => $message,
        );
    }
    else if($module == "Users" && $action == "Create_ETL"){
        $logger->log('debug', 'Create Users through ETL API Request =====>'.var_export($rawData, true));
        global $db;
            try{
            // print_r($rawData);
            $user = new User;
            if(!empty($rawData->nguid)){
                $user->retrieve($rawData->nguid);
            }
            if(empty($user->date_entered)) {
            //save for first time
                if(!empty($rawData->nguid) && !empty($rawData->cn)&& !empty($rawData->username) 
                && !empty($rawData->firstname) && !empty($rawData->status)){
                    $user->new_schema = true;
                    $user->new_with_id = true;
                    $user->id = $rawData->nguid;
                    $user->name = $rawData->cn;
                    $user->user_name = $rawData->username;
                    $user->phone_mobile=$rawData->mobile;
                    $user->first_name = $rawData->firstname;
                    $user->last_name = $rawData->lastname;
                    $user->email1 = $rawData->email;
                    $user->department = $rawData->department;
                    $user->is_admin = stripos($rawData->description, 'crm') > -1;
                    $user->authenticated = true;
                    $user->description = $rawData->memberOf;
                    $user->team_exists = false;
                    $user->table_name = "users";
                    $user->module_dir = 'Users';
                    $user->object_name = "User";
                    $user->status = $rawData->status;
                    $user->importable = true;
                    $user->encodeFields = Array ("first_name", "last_name", "description");
                    $user->save();
                    $message .=  $rawData->username . " user saved\n";
                    $success = true;
                }
                else{
                    $message .=  "Missing Details in AD. Contact Admin";
                    $success = false;
                }
            }
            else{
                $message .=  " User already exist in CRM. ";
                if(!empty($rawData->status) && $user->status != $rawData->status){
                    $user->status = $rawData->status;
                    $message .=  " Status is updated to $rawData->status ";
                }
                if(!empty($rawData->mobile) && $user->phone_mobile != $rawData->mobile){
                    $user->phone_mobile = $rawData->mobile;
                    $message .=  " Mobile is updated to $rawData->mobile ";
                }
                if(!empty($rawData->joining_date) && $user->joining_date_c != $rawData->joining_date){
                    $user->joining_date_c = $rawData->joining_date;
                    $message .=  " joining_date is updated to $rawData->joining_date ";
                }
                if(!empty($rawData->designation) && $user->designation_c != $rawData->designation){
                    $user->designation_c = $rawData->designation;
                    $message .=  " designation is updated to $rawData->designation ";
                }
                if(!empty($rawData->email) && $user->email1 != $rawData->email){
                    $user->email1 = $rawData->email;
                    $message .=  " email is updated to $rawData->email ";
                }
                $user->save();
        
                $query="update  user_preferences SET contents = 'YTo0OntzOjEwOiJ1c2VyX3RoZW1lIjtzOjY6IlN1aXRlUiI7czo4OiJ0aW1lem9uZSI7czoxMjoiQXNpYS9Lb2xrYXRhIjtzOjI6InV0IjtpOjE7czo2OiJDYWxsc1EiO2E6MTE6e3M6NjoibW9kdWxlIjtzOjU6IkNhbGxzIjtzOjY6ImFjdGlvbiI7czo1OiJpbmRleCI7czoxMzoic2VhcmNoRm9ybVRhYiI7czoxMjoiYmFzaWNfc2VhcmNoIjtzOjU6InF1ZXJ5IjtzOjQ6InRydWUiO3M6Nzoib3JkZXJCeSI7czowOiIiO3M6OToic29ydE9yZGVyIjtzOjA6IiI7czoxMDoibmFtZV9iYXNpYyI7czoxMToiOTEyMjYyNTg3NDAiO3M6MjM6ImN1cnJlbnRfdXNlcl9vbmx5X2Jhc2ljIjtzOjE6IjAiO3M6MjA6ImZhdm9yaXRlc19vbmx5X2Jhc2ljIjtzOjE6IjAiO3M6MTU6Im9wZW5fb25seV9iYXNpYyI7czoxOiIwIjtzOjY6ImJ1dHRvbiI7czo2OiJTZWFyY2giO319' WHERE assigned_user_id='".$user->id."' and category='global'";
                
                $db->query($query);        
                
                $designations=array(strtolower("Associate Manager - Customer Acquisition"),strtolower("Senior Associate Manager - Customer Acquisition"),strtolower("Area Sales Manager"),strtolower("Senior Area Sales Manager"));
                    if (in_array(strtolower($user->designation),$designations)){
                        $q="select count(*) as count from  acl_roles_users where role_id='978da784-78e3-5c78-ff7a-57e10a137412' and user_id='$user->id' and deleted=0";
                        $result=$db->query($q);
                        $count=0;
                        while (($row = $db->fetchByAssoc($result)) != null) {
                            $count=$row['count'];
                        }
                        if(empty($count) || $count==0 || !isset($count))
                        {
                            $roleid=create_guid();
                            $query="insert into acl_roles_users values('$roleid','978da784-78e3-5c78-ff7a-57e10a137412','$user->id','','0')";
                            $db->query($query);
                        }
                    }
                $success = true;
            }
            
        } catch(Exception $e){
            $message .= "Exception in saving $nguid".$e->getMessage();
            $success = false;
        }
        $msg = array(
            'Success' => $success,
            'Message' => $message,
        );
    }*/ 
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

function validate_mobile($mobile){
    return preg_match('/^[0-9]{10}+$/', $mobile);
}

function checkDuplicateLead( $mobile,$scheme_c) {
    global $db;
    if(empty($date_entered))
        $date_entered = date("Y-m-d");

    $query  = "select id,scheme_c from leads l join leads_cstm lcstm where deleted = 0 and phone_mobile = '$mobile' and date_entered> CURDATE() - INTERVAL 30 DAY order by date_entered desc limit 1";
    $result = $db->query($query);
    while (($row = $db->fetchByAssoc($result)) != null) {
        $logger =new CustomLogger('crmapi-2.0');
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

