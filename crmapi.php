<?php
if (!defined('sugarEntry'))
    define('sugarEntry', true);
require_once('include/entryPoint.php');
require_once 'vendor/autoload.php';

$log_file = fopen("crmapi.log","a");
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

global $db, $sugar_config;

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
    'Neo_Paylater_Leads',
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

//Test

if ($_SERVER['HTTP_AUTHORIZEDAPPLICATION'] == $scrm_key && in_array($_SERVER['HTTP_REQUESTEDMODULE'], $apiModule) && in_array($_SERVER['HTTP_REQUESTEDMETHOD'], $apiAction)) {

    $module = $_SERVER['HTTP_REQUESTEDMODULE'];
    $action = $_SERVER['HTTP_REQUESTEDMETHOD'];
    $fp      = fopen('php://input', 'r');
    $rawData = json_decode(stream_get_contents($fp));


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
else
if ($module == "Lead" && $action == 'Create') {
   
    $GLOBALS['log']->debug('================>'.var_export($rawData, true));
    
    $can_create_lead = false;

    if(validate_mobile(trim($rawData->phone_mobile)) !=1)
    {
        $msg = array(
            'Success' => false,
            'Message' => 'Please enter a valid 10 digit Mobile to continue.'
        );
    }


    if($rawData->dwh_sync_c == 1){
        $can_create_lead = true;
    }
    else {
        if(empty($rawData->lead_source)){
                $msg = array(
                    'Success' => false,
                    'Message' => 'Mandatory field(s) are missing. Empty Lead Source'
                );
        }
        else if ($rawData->lead_source == 'dsa online' || $rawData->lead_source == 'Web Site' || ($rawData->sub_source_c=='Website' && $rawData->lead_source=='Marketing') || ($rawData->sub_source_c=='Customer App' && $rawData->lead_source=='Marketing') || ($rawData->sub_source_c=='NeoCash Insta' && $rawData->lead_source=='Marketing')){
            $can_create_lead = true;
            $rawData->disposition_c="interested";
        }
        else{
            if(!empty($rawData->last_name) && !empty($rawData->merchant_name_c) && !empty($rawData->phone_mobile) && !empty($rawData->lead_source)) {
                $can_create_lead = true;
            }
            else{
                $msg = array(
                    'Success' => false,
                    'Message' => 'Mandatory field(s) are missing'
                );
            }
        }
    }
    if($can_create_lead) {
        $count = 0;

        if(empty($rawData->dwh_sync_c) && !($rawData->is_renewal_c)){
                $lead_id = checkDuplicateLead($rawData->phone_mobile,$rawData->scheme_c);
        }
        if(!empty($lead_id)){
          $msg = array(
                'Success' => false,
                'Message'=>"Sorry, we have a record in our database that matches your lead details. Please re-check and create again.",
                'Info' => "Lead already exist with similar details id = '$lead_id'",
                'lead_id' =>$lead_id
            );
        }else{

            
            $user_role = getUserRole($rawData->assigned_user_id);
           
            $status="";
            if ($user_role == true || $rawData->dwh_sync_c ==1 || $rawData->sub_source_c=='RA Portal') {
                $status = "Verified";
                $lead_type = "Hot";
            }
            $lead = new Lead();
            foreach($rawData as $k=>$v){
                if($k=='Description')$v=htmlentities($v);
                if($k=='assigned_user_id')
                {
                    $q="select id from users where id='$v'";
                    $result = $db->query($q);
                    while ($row = $db->fetchByAssoc($result)) {
                        $v = $row['id'];
                    }
                }
                $lead->{$k} = $v;
            }
            $lead->status = $status;
            $lead->save();
            $id = $lead->id;//createrecord($session_id, 'Leads', $name_value_list, $url);
            if (empty($id) || $id == 'null') {
                
                $msg = array(
                    'Success' => false,
                    'Message' => 'Error occured creating lead.',
                );
            } else if ($status == 'Verified') {
                $opp_id = getOppID($id);
                $opp_bean = new Opportunity();
                $opp_bean->retrieve($opp_id);
                $opp_bean->remarks = get_var_value($rawData->remarks);
                $opp_bean->amount = get_var_value($rawData->amount);
                $opp_bean->loan_amount_sanctioned_c = get_var_value($rawData->loan_amount_sanctioned_c);
                $opp_bean->dwh_sync_c = get_var_value($rawData->dwh_sync_c);
                $opp_bean->source_type_c=get_var_value($rawData->source_type_c);
                $opp_bean->application_id_c = get_var_value($rawData->application_id_c);
                $opp_bean->processing_fees = get_var_value($rawData->processing_fees);
                $opp_bean->APR = get_var_value($rawData->APR);
                if(!empty($rawData->pickup_appointment_city_c))
                {
                    $opp_bean->pickup_appointment_city_c=get_var_value(strtoupper($rawData->pickup_appointment_city_c));
                }else{
                    $cities=array(
                        'Bengaluru'=>'BANGALORE',
                        'Bhubaneswar'=>'BHUBANESHWAR',
                        'Vadodara'=>'BARODA',
                        'Vijayawada'=>'VIJAYWADA'
                    );
                    if(array_key_exists($rawData->primary_address_city,$cities))
                    {
                        $opp_bean->pickup_appointment_city_c=get_var_value(strtoupper($cities[$rawData->primary_address_city]));
                    }
                    else{
                        $opp_bean->pickup_appointment_city_c=get_var_value(strtoupper($rawData->primary_address_city));
                    }
                }
                $opp_bean->insurance = get_var_value($rawData->insurance);
                $opp_bean->sales_stage = !empty($rawData->sales_stage)?($rawData->sales_stage):'Open';
                $opp_bean->date_funded = get_var_value($rawData->date_funded);
                $opp_bean->lead_source=get_var_value($rawData->lead_source);
                if(!empty($date_entered)){
                    $opp_bean->date_entered = $date_entered;
                }
                $opp_bean->save();
                $msg = array(
                    'Success' => true,
                    'Message' => 'Lead Created Successfully',
                    'Lead id' => $id,
                    'Opportunity id' => $opp_id
                );
            }
            else {
                $msg = array(
                    'Success' => true,
                    'Message' => 'Lead Created Successfully',
                    'Lead id' => $id
                );
            }
        }//end of check duplicate lead
    }
}
else
if ($module == "Lead" && $action == 'Update') {
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
        // $lead->assigned_user_id = NULL;
        if(!empty($retrieved_data)){
            foreach($rawData as $k=>$v){
                if($k=='Description')$v=htmlentities($v);
                if($k=='pickup_appointment_city_c' or $k=='phone_mobile')
                {
                    continue;
                }
                /*else if($k=='disposition_c'){
                    if($v=='interested'||$v=='pick_up'){
                        $city = $lead->primary_address_city;
                        $list = $GLOBALS['app_list_strings']['cluster_city_mapping'];
                        if(!empty($city) && array_key_exists($city, $list)){
                            $ngid = $list[$city];
                            $user_bean = getUserBean($ngid);
                            $lead->assigned_user_id=$user_bean->id;
                        }
                        
                    }
                }*/

                $lead->{$k} = $v;
            }
            $id = $lead->save();
            $opp_id = getOppID($id);
            if($id){
                $opp_id = getOppID($id);
                    $opp_bean = new Opportunity();
                    $opp_bean->retrieve($opp_id);
                    if (!empty($rawData->pickup_appointment_city_c)){
                        $opp_bean->pickup_appointment_city_c=get_var_value(strtoupper($rawData->pickup_appointment_city_c));
                    }
                    $opp_bean->save();
                $msg = array(
                    'Success' => true,
                    'Message' => 'Lead Updated Successfully',
                    'Opportunity_id' => $opp_id
                );
            }else{
                $msg = array(
                    'Success' => false,
                    'Message' => 'Lead was not updated'
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
else
if ($module == "Lead" && $action == 'Fetch') {
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
else
if ($module == "Opportunities" && $action == 'Update') {
    $myfile = fopen("Logs/OpportunityUpdateCrmApi.log", "a");
    fwrite($myfile, "\n".date('Y-m-d h:i:s'));
    fwrite($myfile, var_export($rawData, true));
    $opp_id = $rawData->opportunity_id;
    (isset($rawData->update_date) ? $update_date = $rawData->update_date : '');

    if (!isset($opp_id) or empty($opp_id)) {
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing'
        );
    }
    else {
        
        $op = new Opportunity();
        $retrieved_data = $op->retrieve($opp_id);
        if(!empty($retrieved_data)){
            $op->sales_stage = $rawData->opportunity_stage;
            $op->amount = $rawData->amount;
            $op->pickup_appointment_feedback_c = $rawData->feedback;
            $op->remarks = $rawData->remarks;
            $op->source_type=$rawData->source_type;
            $op->loan_amount_sanctioned_c = $rawData->loan_amount_sanctioned_c;
            $op->assigned_user_id = $rawData->user_id;

            if(!empty($rawData->pickup_appointment_city_c)){
                $op->pickup_appointment_city_c = $rawData->pickup_appointment_city_c;
            }
            $op->application_id_c = $rawData->application_id;
            if(!empty($rawData->date_updated_EOS))
            {
                $op->eos_opportunity_status=$rawData->opportunity_status;
                $op->eos_sub_status=$rawData->sub_status;

                $time=strtotime($rawData->date_updated_EOS);
                $time=$time-(330*60);
                $op->date_updated_by_EOS=date("Y-m-d H:i:s", $time);
            }
                $op->opportunity_status_c = $rawData->opportunity_status;
                $op->sub_status=$rawData->sub_status;
            $op->pickup_appointment_date_c=$rawData->pickup_appointment_date_c;
            $op->control_program=$rawData->control_program;
            $op->stage_drop_off=$rawData->stage_drop_off;
            $op->app_form_link=$rawData->app_form_link;
            // $time=strtotime($rawData->date_updated_EOS);
            // $time=$time-(330*60);
            // $op->date_updated_by_EOS=date("Y-m-d H:i:s", $time);
            $op->eos_disposition=$rawData->eos_disposition;
            $op->eos_sub_disposition=$rawData->eos_sub_disposition;
            $op->pickup_appointment_pincode_c=$rawData->Address_pin;
            $op->pickup_appointment_address_c=$rawData->Address_Street;
            $op->reject_reason=$rawData->reject_reason;
            $op->is_eligible=$rawData->is_eligible;


            $id = $op->save();
            if(!empty($update_date) && !empty($opp_stage)){
                $query = "update Opportunities_audit set date_created='$update_date' where parent_id='$id' and after_value_string='$opp_stage' order by date_created desc limit 1";

                global $db;

                $db->query($query);

            }
            if($id){
                $msg = array(
                    'Success' => true,
                    'Message' => 'Opportunity Updated Successfully'
                );
            }else{
                $msg = array(
                    'Success' => false,
                    'Message' => 'Opportunity was not updated'
                );
            }
        }else{
            $msg = array(
                'Success' => false,
                'Message' => "Opportunity $opp_id not found in DB"
            );
        }
    }
}
else
if ($module == "Opportunities" && $action == 'Fetch') {
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

else
if ($module == "Meeting" && ($action == 'Create' ||$action == 'Update')) {

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
else if($module == "Cases" && $action=='Update')
{
    global $db;
    if(empty($rawData->case_id))
    {
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
        if($result->num_rows== 0)
        {
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
}
else
if ($module == "Meeting" && $action == 'Fetch') {
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
else
if ($module == "Cases" && $action == 'Create') {

    // print_r($rawData);

    $merchant_name_c = get_var_value($rawData->merchant_name_c);
    $merchant_app_id_c = get_var_value($rawData->merchant_app_id_c);
    $merchant_contact_number_c = get_var_value($rawData->merchant_contact_number_c);
    $merchant_email_id_c = get_var_value($rawData->merchant_email_id_c);
    $description = get_var_value($rawData->description);
    $case_location_c = get_var_value($rawData->case_location_c);
    $subject = get_var_value($rawData->subject);
    $case_source_c = get_var_value($rawData->case_source_c);
    $case_sub_source_c = get_var_value($rawData->case_sub_source_c);

    $complaintaint_c = get_var_value($rawData->complaintaint_c);
    $merchant_establisment_c = get_var_value($rawData->merchant_establisment_c);
    $case_subcategory_c = get_var_value($rawData->case_subcategory_c);
    $case_category_c = get_var_value($rawData->case_category_c);

    $call_back_start_time_c = get_var_value($rawData->call_back_start_time_c);
    $call_back_duration_c = get_var_value($rawData->call_back_duration_c);
    //dont delete or modify this field '$is_call_back_c', based on this in data_sync we are skipping assigned_user_id = null
    $is_call_back_c = get_var_value($rawData->is_call_back_c);
    $call_back_30_min = get_var_value($rawData->call_back_30_min);

    $custom_case_type = get_var_value($rawData->custom_case_type);
    $custom_s3_url = get_var_value($rawData->custom_s3_url);
    $type = get_var_value($rawData->type);
    $financial_year_c = get_var_value($rawData->financial_year_c);
    $digitally_signed_c = get_var_value($rawData->digitally_signed_c);
    $quarter_c = get_var_value($rawData->quarter_c);
    $agent_user_id = "";
    $recordExits = FALSE;

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
            'Message' => 'Mandatory field(s) are missing'
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
                'Message' => 'Mandatory field(s) are missing'
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
        $case = new aCase();
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
                $query="update cases set created_by='1' where id=$id";
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
else
if($module == "Neo_Customers" && $action == "Fetch"){
    (isset($rawData->application_id) ? $application_id = $rawData->application_id : '');
    (isset($rawData->customer_id) ? $customer_id = $rawData->customer_id : '');
    //print_r($application_id);print_r($customer_id);
    if(empty($application_id) && empty($customer_id)){
        $msg = array(
            'Success' => false,
            'Message' => 'Mandatory field(s) are missing'
        );
    }else if(!empty($customer_id)){
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
                $result['scheme_c'] = $item->scheme_c;
            }
            $results[]=$result;
        }
        $msg = array(
            'Success' => true,
            'Message' => 'Customers',
            'Customers' => $results
        );
    }
    else if(!empty($application_id)){
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
                $result['scheme_c'] = $item->scheme_c;
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

else
if($module == "Users" && $action == "Create"){
    try{
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
        && !empty($result['firstname'])){
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
            $GLOBALS['log']->debug('Added '.$user->id.' to '.$roleID);
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
                    $GLOBALS['log']->debug('Added '.$user->id.' to '.$add_sg_id);
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
            $email->send_email_to_user("User Created in CRM Using Sales App",$desc,["balayeswanth.b@neogrowth.in"], ["v.gopi@neogrowth.in"],null,array(),1);
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
    
}catch(Exception $e){
    $message .= "Exception in saving $nguid".$e->getMessage();
    $success = false;
}
$msg = array(
    'Success' => $success,
    'Message' => $message,
);
}
else
if($module == "Users" && $action == "Create_ETL"){
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
        if(!empty($rawData->joining_date) && $user->joining_date != $rawData->joining_date){
            $user->joining_date = $rawData->joining_date;
            $message .=  " joining_date is updated to $rawData->joining_date ";
        }
        if(!empty($rawData->designation) && $user->designation != $rawData->designation){
            $user->designation = $rawData->designation;
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
    
}catch(Exception $e){
    $message .= "Exception in saving $nguid".$e->getMessage();
    $success = false;
}
$msg = array(
    'Success' => $success,
    'Message' => $message,
);
} else if($module == "Paylater_Open" && $action == 'Create') {
    $paylaterOpen = new neo_Paylater_Open();
    $name_value_list = array();
    $myfile = fopen("Logs/paylaterOpenCrmapi.log", "a");
    fwrite($myfile, "\n".date('Y-m-d h:i:s'));
    fwrite($myfile, var_export($rawData, true));
    $productType = "";
    if(isset($rawData) && !empty($rawData)){
        foreach ($rawData as $key => $value) {
            if ($key == "application_id") {
                $applicationId = $value;
            }
            if ($key == "product") {
                $env = getenv('SCRM_ENVIRONMENT');
                if (in_array($env, array('prod'))) {
                    if ($value == 3) {
                        $productType = "paylater_open";
                    } else if ($value == 4) {
                        $productType = "purchase_finance";
                    }
                } else {
                    if ($value == 2) {
                        $productType = "paylater_open";
                    } else if ($value == 4) {
                        $productType = "purchase_finance";
                    }
                }
            } else {
                $paylaterOpen->{$key} = $value;
            }
        }
        $paylaterOpen->status ='IN_PROGRESS';
        $paylaterOpen->product = $productType;

//        require_once 'modules/Cases/Case.php';
//        $objCase = new aCase();
//        $userToAssign = $objCase->getUserToAssign();
//        $name_value_list[] = array('name'=>'assigned_user_id','value'=>$userToAssign);

        fwrite($myfile, "Data going to be saved in Neo Paylater Open");
        fwrite($myfile, var_export($name_value_list, true));  
                
        if(!empty($paylaterOpen)) {
            $queryToGetPaylaterOpen = "select id from neo_paylater_open where application_id = '$applicationId'";
            $results = $db->query($queryToGetPaylaterOpen);
            $numberOfRows = $results->num_rows;

            if($numberOfRows <= 0){
                $id =  $paylaterOpen->save();
                if($id){
                    $msg = array(
                        'Success' => true,
                        'Message' => 'Paylater Open application Created Successfully',
                        'Paylater Open ID' => $id
                    );
                }else{
                    $msg = array(
                        'Success' => false,
                        'Message' => 'Paylater Open application is Not Created',
                        'Paylater Open ID' => $id
                    );
                } 
            } else {
                $msg = array(
                    'Success' => false,
                    'Message' => 'Application ID '.$applicationId.' already exists in CRM',
                    'Paylater Open ID' => $id
                );
            }
        } 
    }
} else if($module == "Paylater_Open" && $action == 'Update'){
    print_r("INSIDE PAYLATER OPEN");
    $myfile = fopen("Logs/paylaterOpenUpdateEmailValidation.log", "a");
    fwrite($myfile, "\n".date('Y-m-d h:i:s'));
    fwrite($myfile, var_export($rawData, true));
    foreach($rawData as $key=>$value){
        $name_value_list[] = array('name'=>$key,'value'=>$value);
        if($key=='application_number'){
            $applicationNumber = $value;
        }
        if($key=='email'){
           $email = $value;
        }
    }
    
    fwrite($myfile, "\n Application $applicationNumber");
    fwrite($myfile, "\n EMail  $email");

    if(!empty($applicationNumber) && !empty($email)){
        $queryToGetId = "select id from neo_paylater_open where application_id = '$applicationNumber' AND ((email_id = '$email') OR (alternate_email_id = '$email'))";
        $results = $db->query($queryToGetId);
        while($row = $db->fetchByAssoc($results)){
            if(!empty($row['id'])){
                $paylaterOpenId = $row['id'];
            }
        }
        if(!empty($paylaterOpenId)){
            $queryToUpdateEmailVerification = "UPDATE neo_paylater_open 
            SET is_primary_email_verified = CASE  
                WHEN email_id = '$email' THEN 1
                else
                 is_primary_email_verified
              END,
            is_secondary_email_verified = CASE  
                WHEN alternate_email_id = '$email' THEN 1
                else
                 is_secondary_email_verified
              END,
            email_verification_status = '1'
            where id = '$paylaterOpenId'";
            $response = $db->query($queryToUpdateEmailVerification);
        } else {
            $message = "Record not found";
        }        
        print_r($response);
    }    
}else if($module == "Paylater_Open" && $action == 'Transacting'){
        global $db;
        $query = "update neo_paylater_open set transaction_status='' where transaction_status='not_transacting'";
        $db->query($query);
        $applications = $rawData->paylater_accounts;
        $applicationIds = '';
        foreach ($applications as $key => $value) {
            if($key != 0){
                $applicationIds .= ",$value";
            } else {
                $applicationIds .= "$value";
            }
        }
        $queryToUpdate = "update neo_paylater_open set transaction_status='not_transacting' where application_id IN ($applicationIds)";
        $response = $db->query($queryToUpdate);
        if($response){
            $msg = array(
                'Success' => true,
                'Message' => 'Application updated successfully',
            );
        } else {
             $msg = array(
                'Success' => false,
                'Message' => 'Application update failed',
            );
        }
        echo json_encode($msg);
        exit;
    }
}
else {
    $msg = array(
        'Success' => false,
        'Message' => 'Oops! Something went wrong'
    );
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

// function createrelationship($session_id, $module, $module_id, $link_field_name, $related_ids, $name_value_list, $delete, $url) {
//     $set_relationship_parameters = array(
//         "session" => $session_id,
//         "module_name" => $module,
//         "module_id" => $module_id,
//         "link_field_name" => $link_field_name,
//         "related_ids" => $related_ids,
//         "name_value_list" => $name_value_list,
//         "delete" => $delete
//     );
//     $set_relationship_result = call("set_relationship", $set_relationship_parameters, $url);
//     return $set_relationship_result;
// }


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

function validate_mobile($mobile)
{
    return preg_match('/^[0-9]{10}+$/', $mobile);
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

function checkDuplicateLead( $mobile,$scheme_c) {
    global $db;
    if(empty($date_entered))
        $date_entered = date("Y-m-d");

    $query  = "select id,scheme_c from leads where deleted = 0 and phone_mobile = '$mobile' and date_entered> CURDATE() - INTERVAL 30 DAY order by date_entered desc limit 1";
    $result = $db->query($query);
    while (($row = $db->fetchByAssoc($result)) != null) {
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

function getLeadID($opp_id) {
    global $db;
    $query  = "select id as lead_id from leads where deleted = 0 and opportunity_id = '$opp_id'";
    $result = $db->query($query);
    $row    = $db->fetchByAssoc($result);
    $lead_id = $row['lead_id'];

    return $lead_id;
}






function sendHttpStatusCode($httpStatusCode, $httpStatusMsg) {
    $phpSapiName    = substr(php_sapi_name(), 0, 3);
    if ($phpSapiName == 'cgi' || $phpSapiName == 'fpm') {
        header('Status: '.$httpStatusCode.' '.$httpStatusMsg);
    } else {
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
        header($protocol.' '.$httpStatusCode.' '.$httpStatusMsg);
    }
}

function get_var_value($var){
    if(!empty($var))
        return $var;
    return "";
}

$messages = array(
    // Informational 1xx
    100 => 'Continue',
    101 => 'Switching Protocols',

    // Success 2xx
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',

    // Redirection 3xx
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',  // 1.1
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    // 306 is deprecated but reserved
    307 => 'Temporary Redirect',

    // Client Error 4xx
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Requested Range Not Satisfiable',
    417 => 'Expectation Failed',

    // Server Error 5xx
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    509 => 'Bandwidth Limit Exceeded'
);


?>