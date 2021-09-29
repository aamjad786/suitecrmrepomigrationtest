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
else if ($module == "Lead" && $action == 'Create') {
   
    $isDataValid=true;

    $logger->log('debug', 'Create Lead API Request =====>'.print_r($rawData, true));

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

    if($isDataValid){

        // Dedup Check

        $isDuplicate=false;

        if (!$rawData->is_renewal_c) {
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
    
        $logger->log('debug', 'Update Lead API Request =====>'.print_r($rawData, true));
        
        $leadId = $rawData->lead_id;

        if (isset($leadId) and !empty($leadId)) {

            $leadBean = new Lead();
            $leadBeanData=$leadBean->retrieve($leadId);

            $leadBeanData->eos_disposition_c = $rawData->eos_disposition_c;
            $leadBeanData->eos_sub_disposition_c = $rawData->eos_sub_disposition_c;
            $leadBeanData->eos_opportunity_status_c = $rawData->eos_opportunity_status_c;
            $leadBeanData->eos_sub_status_c = $rawData->eos_sub_status_c;
            $leadBeanData->eos_remark_c = $rawData->eos_remark_c;
            $leadBeanData->date_updated_by_eos_c = date("Y-m-d H:i:s", strtotime($rawData->date_updated_by_eos_c)-(330*60));

            $leadBeanData->save();

            $msg = array(
                'Success' => true,
                'Message' => 'Lead Updated Successfully'
            );
        }
        else {

            $msg = array(
                'Success' => false,
                'Message' => 'Mandatory field(s) are missing. Lead ID Is Empty'
            );
            
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
            $op->remarks_c = $rawData->remarks_c;
            $op->source_type_c=$rawData->source_type_c;
            $op->loan_amount_sanctioned_c = $rawData->loan_amount_sanctioned_c;
            $op->assigned_user_id = $rawData->user_id;

            if(!empty($rawData->pickup_appointment_city_c)){
                $op->pickup_appointment_city_c = $rawData->pickup_appointment_city_c;
            }
            $op->application_id_c = $rawData->application_id;
            if(!empty($rawData->date_updated_EOS))
            {
                $op->eos_opportunity_status_c=$rawData->eos_opportunity_status_c;
                $op->eos_sub_status_c=$rawData->eos_sub_status_c;

                $time=strtotime($rawData->date_updated_EOS);
                $time=$time-(330*60);
                $op->date_updated_by_EOS=date("Y-m-d H:i:s", $time);
            }
                $op->opportunity_status_c = $rawData->opportunity_status;
                $op->sub_status_c=$rawData->sub_status_c;
            $op->pickup_appointment_date_c=$rawData->pickup_appointment_date_c;
            $op->control_program_c=$rawData->control_program_c;
            $op->stage_drop_off_c=$rawData->stage_drop_off_c;
            $op->app_form_link_c=$rawData->app_form_link_c;
            // $time=strtotime($rawData->date_updated_EOS);
            // $time=$time-(330*60);
            // $op->date_updated_by_EOS=date("Y-m-d H:i:s", $time);
            $op->eos_disposition_c=$rawData->eos_disposition_c;
            $op->eos_sub_disposition_c=$rawData->eos_sub_disposition_c;
            $op->pickup_appointment_pincode_c=$rawData->Address_pin;
            $op->pickup_appointment_address_c=$rawData->Address_Street;
            $op->reject_reason_c=$rawData->reject_reason_c;
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
else if ($module == "Opportunities" && $action == 'Fetch') {
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
        $logger=new CustomLogger('crmapi-2.0.log');
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