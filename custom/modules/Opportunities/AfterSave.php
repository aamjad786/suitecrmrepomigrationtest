<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
require_once('custom/include/SendSMS.php');
require_once('custom/include/SendEmail.php');
require_once('modules/ACLRoles/ACLRole.php');
class AfterSaveOpportunity
{

    public function send_appointment_smstocam(&$bean, $event, $args)
    {

        $myfile = fopen("Logs/appointment_sms.log", 'a');
        fwrite($myfile, date("d/m/Y H:i:s"));
        global $db;
        $query = "select lead_source from leads where opportunity_id='$bean->id'";
        $result = $db->query($query);
        $source = "";
        while (($row = $db->fetchByAssoc($result)) != null) {
            $source = $row['lead_source'];
        }
        fwrite($myfile, "\n" . $source);
        $sources = array("Alliances", "missed_calls_sms", "Web Site", "Marketing", "Digital Journey", "BTL");
        $user_id   = $bean->assigned_user_id;
        $user_bean = new User();
        $user_bean->retrieve($user_id);
        $assigned_user_old = $bean->fetched_row['assigned_user_id'];
        $assigned_user_new = $bean->assigned_user_id;

        if (preg_match("/Customer Acquisition/i", $user_bean->designation) && in_array($source, $sources)) {

            if (($bean->deleted == 0) && (!empty($assigned_user_new) || strcmp($assigned_user_new, $assigned_user_old) != 0) && empty($bean->dwh_sync_c)) {
                $customer = $bean->name;
                $cust_mobile = $bean->pickup_appointment_contact_c;
                $pickup_date = $bean->pickup_appointment_date_c;
                $pickup_add = $bean->pickup_appointment_address_c;


                $cam_name     = $user_bean->full_name;
                $trading_name = $bean->merchant_name_c;
                $current_date = date("d/m/Y H:i:s");
                $mobile_no = $user_bean->phone_mobile;
                $date = substr($pickup_date, 0, 10);
                $message = "You have to visit $customer for document pick up on $date. You may call the customer at $cust_mobile Address: $pickup_add.";
                $mobile_no = "91" . substr($mobile_no, -10);
                $cust_mobile = "91" . substr($cust_mobile, -10);
                fwrite($myfile, "\n\n" . $message . "\n\n");
                $env = getenv('SCRM_ENVIRONMENT');

                if ($env == 'prod') {
                    $sms = new SendSMS();
                    $sms->send_sms_to_user($tag_name = "Cust_CRM_7", $mobile_no, $message, $bean);
                } else {
                    $sms = new SendSMS();
                    $sms->send_sms_to_user($tag_name = "Cust_CRM_7", "919131952467", $message, $bean);
                }
            }
        }
    }

    public function audit_first_assigned(&$bean, $event, $args)
    {
        global $db, $current_user;
        $myfile = fopen("Logs/convertlead.log", 'a');
        fwrite($myfile, "\n" . "audit" . "\n");
        $date_audit = date("Y-m-d h:i:s");
        $timestamp = strtotime($date_audit);
        $time = $timestamp - (5 * 60 * 60 + 30 * 60); //subtract 5h 30min from current time;
        $datetime = date("Y-m-d H:i:s", $time);
        fwrite($myfile, "\n" . "yes" . "\n");
        $auditid = create_guid();
        fwrite($myfile, "\n" . $auditid . "\n");
        $opp_id = $bean->id;
        $old_assigned_user = $bean->fetched_row['assigned_user_id'];
        $created_by = $current_user->id;
        if (empty($created_by) || !isset($created_by)) {
            $created_by = "1";
        }
        if ((empty($old_assigned_user) || !isset($old_assigned_user)) && (!empty($bean->assigned_user_id) && isset($bean->assigned_user_id))) {
            $query = "insert into opportunities_audit values ('$auditid','$opp_id','$datetime','$created_by','assigned_user_id','relate','$old_assigned_user','$bean->assigned_user_id',null,null)";
            $result = $db->query($query);
            fwrite($myfile, "\n" . $query . "\n");
        }
    }

    public function send_appointment_smstocustomer(&$bean, $event, $args)
    {
        $myfile = fopen("Logs/appointment_sms.log", 'a');
        fwrite($myfile, date("d/m/Y H:i:s"));
        global $db;
        $opp_status = $bean->opportunity_status_c;
        $user_id   = $bean->assigned_user_id;
        $user_bean = new User();
        $user_bean->retrieve($user_id);
        $old_opp_status = $bean->fetched_row['opportunity_status_c'];
        $assigned_user_new = $bean->assigned_user_id;
        if (preg_match("/Customer Acquisition/i", $user_bean->designation)) {

            if (($bean->deleted == 0) && !empty($opp_status) && strcmp($old_opp_status, $opp_status) != 0) {
                $customer = $bean->name;
                $cust_mobile = $bean->pickup_appointment_contact_c;
                $pickup_date = $bean->pickup_appointment_date_c;
                $pickup_add = $bean->pickup_appointment_address_c;


                $cam_name     = $user_bean->full_name;
                $trading_name = $bean->merchant_name_c;
                $current_date = date("d/m/Y H:i:s");
                $mobile_no = $user_bean->phone_mobile;
                $date = substr($pickup_date, 0, 10);
                if ($opp_status == "Follow up") {
                    $message1 = "Dear Customer, We urge you to complete the application process at the earliest to serve you better. You can reach your RM, $cam_name at $mobile_no. Regards, Team NeoGrowth";
                } else if ($opp_status == "Not Contactable") {
                    $message1 = "Dear Customer, Thank you for applying at NeoGrowth. Your RM, $cam_name has been trying to reach you. You can call him at $mobile_no. Regards, Team NeoGrowth";
                }
                $mobile_no = "91" . substr($mobile_no, -10);
                $cust_mobile = "91" . substr($cust_mobile, -10);
                fwrite($myfile, "\n\n" . $message1 . "\n\n");
                $env = getenv('SCRM_ENVIRONMENT');
                fwrite($myfile, $message1 . " " . $cust_mobile . "\n");
                if ($env == 'prod') {
                    $sms = new SendSMS();
                    $sms->send_sms_to_user($tag_name = "Cust_CRM_6", $cust_mobile, $message1, $bean);
                } else {
                    $sms = new SendSMS();
                    $sms->send_sms_to_user($tag_name = "Cust_CRM_6", "919131952467", $message1, $bean);
                }
            }
        }
    }

    public function city_cluster_mapping(&$bean, $event, $args)
    {
        if ($bean->stored_fetched_row_c["control_program_c"] != "NeoCash Insta") {
            global $db;
            $q = "select source_type_c from leads_cstm where opportunity_id='$bean->id'";
            $result = $db->query($q);
            $source = "";
            $myfile = fopen("Logs/clustermap.log", 'a');
            fwrite($myfile, date("d/m/Y H:i:s"));
            while (($row = $db->fetchByAssoc($result)) != null) {
                $source = $row['source_type_c'];
            }

            $positive = array('appointment_done_followup', 'appointment_done_will_get_documents_later', 'appointment_done_picked_up_documents', 'appointment_done_cam_visit_customer', 'Appointment fixed', 'appointment_done_cam_to_visit_customer');

            $positive_status = in_array($bean->opportunity_status_c, $positive);
            $leadsources = array("Marketing", "Alliances");
            fwrite($myfile, "\n" . $bean->assigned_user_id . "\n");
            if ($source != "SalesApp" && $bean->source_type_c != "SalesApp") {
                if (!in_array($bean->lead_source, $leadsources) || ((in_array($bean->lead_source, $leadsources) && $positive_status))) {
                    $city = $bean->pickup_appointment_city_c;
                    $old_city = $bean->stored_fetched_row_c['pickup_appointment_city_c'];
                    $old_status = $bean->stored_fetched_row_c['opportunity_status_c'];
                    fwrite($myfile, "\n" . $old_city . "\n");
                    if (strcmp($city, $old_city) == 0 && strcmp($old_status, $bean->opportunity_status_c) == 0) {
                        $old_assigned = $bean->stored_fetched_row_c['assigned_user_id'];
                        if (empty($bean->assigned_user_id) && !empty($old_assigned)) {
                            $query = "update opportunities set assigned_user_id='$old_assigned' where id='$bean->id'";
                            $db->query($query);
                        } else {
                            $query = "update opportunities set assigned_user_id='$bean->assigned_user_id' where id='$bean->id'";
                            $db->query($query);
                        }
                    } else {
                        $table = "cluster_city_mapping";
                        if ($bean->lead_source == "Alliances") {
                            $table = "cluster_city_mapping_alliance";
                        }
                        $q = "select * from $table where city='$city'";
                        $result = $db->query($q);
                        while (($row = $db->fetchByAssoc($result)) != null) {
                            $spoc = $row['spoc_id'];
                        }
                        if (!empty($spoc)) {
                            $ngid = $spoc;
                            $user_bean = BeanFactory::getBean('Users');
                            $query = 'users.deleted=0 and users.user_name = "' . $ngid . '"';
                            $users = $user_bean->get_full_list('', $query);
                            $userId = "";
                            if (!empty($users)) {
                                $user = $users[0];
                                $userId = $user->id;
                            } else {
                                $userId = "1";
                            }
                        }
                        $bean->assigned_user_id = $userId;
                        fwrite($myfile, "\n" . $bean->assigned_user_id . "\n");
                        fwrite($myfile, "\n" . $source . "\n");
                        $query = "update opportunities set assigned_user_id='$bean->assigned_user_id' where id='$bean->id'";
                        $db->query($query);
                    }
                } else if (((in_array($bean->lead_source, $leadsources) && !$positive_status && !empty($bean->opportunity_status_c)))) {
                    // $userId="DC8F5E5E-3F7C-4028-85C5-41785D11E6EF";
                    $userId = $bean->assigned_user_id;

                    $query = "update opportunities set assigned_user_id='$userId' where id='$bean->id'";
                    $db->query($query);
                }
            } else {
                if (!empty($bean->assigned_user_id) && isset($bean->assigned_user_id)) {
                    //if opportunity is coming from sales app
                    $query = "update opportunities set assigned_user_id='$bean->assigned_user_id' where id='$bean->id'";
                    $db->query($query);
                } else {
                    $old_assigned = $bean->stored_fetched_row_c['assigned_user_id'];
                    fwrite($myfile, "\n" . $old_assigned . "\n");
                    if (!empty($old_assigned)) {
                        //if opportunity updated from sales app but assigned user is not updated
                        $query = "update opportunities set assigned_user_id='$old_assigned' where id='$bean->id'";
                        $db->query($query);
                        return;
                    }
                    //if lead is coming from sales app
                    $query = "update opportunities set assigned_user_id=(select assigned_user_id from leads where opportunity_id='$bean->id') where id='$bean->id'";
                    $db->query($query);
                }
            }
        }
    }

    public function city_cluster_mapping_insta(&$bean, $event, $args)
    {
        if ($bean->stored_fetched_row_c["control_program_c"] == "NeoCash Insta" || $bean->control_program_c == "NeoCash Insta") {

            $file1 = fopen("Logs/unassignuser.log", "a");
            fwrite($file1, "unassigned-city_cluster_insta");
            global $db, $current_user;
            $q = "select source_type_c from leads where opportunity_id='$bean->id'";
            $result = $db->query($q);
            $source = "";
            if (!empty($current_user->id) && $current_user->id != 1) {
                $bean->source_type_c = "SalesApp";
                $q1 = "update opportunities set source_type_c='SalesApp' where id='$bean->id'";
                $db->query($q1);
            }
            $myfile = fopen("Logs/clustermap.log", 'a');
            fwrite($myfile, date("d/m/Y H:i:s"));
            while (($row = $db->fetchByAssoc($result)) != null) {
                $source = $row['source_type_c'];
            }

            //$stages=array("Sanctioned","Disbursed","Credit");
            fwrite($myfile, "\n" . $bean->assigned_user_id . "\n");
            if ($source != "SalesApp" && $bean->source_type_c != "SalesApp" && $bean->stored_fetched_row_c['source_type_c'] != "SalesApp") {
                if (!empty($bean->application_id_c) || !empty($bean->stored_fetched_row_c['application_id_c'])) {
                    $city = $bean->pickup_appointment_city_c;
                    $old_city = $bean->stored_fetched_row_c['pickup_appointment_city_c'];
                    $old_appid = $bean->stored_fetched_row_c['application_id_c'];
                    fwrite($myfile, "\n" . $old_city . "\n");
                    if (strcmp($city, $old_city) == 0 && strcmp($old_appid, $bean->application_id_c) == 0) {
                        $old_assigned = $bean->stored_fetched_row_c['assigned_user_id'];
                        if (empty($bean->assigned_user_id) && !empty($old_assigned)) {
                            $query = "update opportunities set assigned_user_id='$old_assigned' where id='$bean->id'";
                            $db->query($query);
                        } else {
                            $query = "update opportunities set assigned_user_id='$bean->assigned_user_id' where id='$bean->id'";
                            $db->query($query);
                        }
                    } else {
                        $table = "cluster_city_mapping_insta";
                        $q = "select * from $table where city='$city'";
                        $result = $db->query($q);
                        while (($row = $db->fetchByAssoc($result)) != null) {
                            $spoc = $row['spoc_id'];
                        }
                        if (!empty($spoc)) {
                            $ngid = $spoc;
                            $user_bean = BeanFactory::getBean('Users');
                            $query = 'users.deleted=0 and users.user_name = "' . $ngid . '"';
                            $users = $user_bean->get_full_list('', $query);
                            $userId = "";
                            if (!empty($users)) {
                                $user = $users[0];
                                $userId = $user->id;
                            } else {
                                $userId = "1";
                            }
                            fwrite($myfile, "\nspoc- " . $userId . "\n");
                        } else {
                            $userId = "1";
                            fwrite($myfile, "\nspoc- blank spoc- $city" . "\n");
                        }
                        $bean->assigned_user_id = $userId;
                        fwrite($myfile, "\n" . $bean->assigned_user_id . "\n");
                        fwrite($myfile, "\n" . $source . "\n");
                        $query = "update opportunities set assigned_user_id='$bean->assigned_user_id' where id='$bean->id'";
                        $db->query($query);
                    }
                } else if (empty($bean->application_id_c)) {
                    $userId = "1";
                    $query = "update opportunities set assigned_user_id='$userId' where id='$bean->id'";
                    $db->query($query);
                }
            } else {
                if (!empty($bean->assigned_user_id) && isset($bean->assigned_user_id)) {
                    //if opportunity is coming from sales app
                    $query = "update opportunities set assigned_user_id='$bean->assigned_user_id' where id='$bean->id'";
                    $db->query($query);
                } else {
                    $old_assigned = $bean->stored_fetched_row_c['assigned_user_id'];
                    fwrite($myfile, "\n" . $old_assigned . "\n");
                    if (!empty($old_assigned)) {
                        //if opportunity updated from sales app but assigned user is not updated
                        $query = "update opportunities set assigned_user_id='$old_assigned' where id='$bean->id'";
                        $db->query($query);
                        return;
                    }
                    //if lead is coming from sales app
                    //$query = "update opportunities set assigned_user_id=(select assigned_user_id from leads where opportunity_id='$bean->id') where id='$bean->id'";
                    //$db->query($query);
                }
            }
        }
    }

    public function dsa_spoc_assign(&$bean, $event, $args)
    {
        if ($bean->lead_source == "dsa online" && $bean->stored_fetched_row_c["lead_source"] == "dsa online") {

            global $db, $current_user;
            $q = "select source_type_c from leads_cstm where opportunity_id='$bean->id'";
            $result = $db->query($q);
            $source = "";
            if (!empty($current_user->id) && $current_user->id != 1) {
                //allow manual intervention in assigned to
                $bean->source_type_c = "SalesApp";
                $q1 = "update opportunities_cstm set source_type_c='SalesApp' where id='$bean->id'";
                $db->query($q1);
            }
            $myfile = fopen("Logs/clustermap.log", 'a');
            fwrite($myfile, date("d/m/Y H:i:s"));
            while (($row = $db->fetchByAssoc($result)) != null) {
                $source = $row['source_type_c'];
            }

            fwrite($myfile, "\n" . $bean->assigned_user_id . "\n");
            if ($source != "SalesApp" && $bean->source_type_c != "SalesApp" && $bean->stored_fetched_row_c['source_type_c'] != "SalesApp") {

                $eligible = $bean->stored_fetched_row_c['is_eligible'];

                //echo "$eligible";exit;

                $sales_stages = array('Credit', 'Sanctioned', 'Rejected', 'awaiting_resolution_confirmation');


                if ((in_array($bean->is_eligible, array("Yes", "No")) && strcmp($eligible, $bean->is_eligible) != 0)) {

                    if (in_array($bean->sales_stage, $sales_stages)) {

                        $dsa = $bean->dsa_id;
                        $table = "cluster_city_mapping_dsa";
                        $q = "select * from $table where dsa='$dsa'";
                        $result = $db->query($q);
                        while (($row = $db->fetchByAssoc($result)) != null) {
                            $spoc = $row['spoc_id'];
                        }
                        if (!empty($spoc)) {
                            $ngid = $spoc;
                            $user_bean = BeanFactory::getBean('Users');
                            $query = 'users.deleted=0 and users.user_name = "' . $ngid . '"';
                            $users = $user_bean->get_full_list('', $query);
                            $userId = "";
                            if (!empty($users)) {
                                $user = $users[0];
                                $userId = $user->id;
                            } else {
                                $userId = "1";
                            }
                        }
                        $bean->assigned_user_id = $userId;
                        fwrite($myfile, "\n" . $bean->assigned_user_id . "\n");
                        fwrite($myfile, "\n" . $source . "\n");
                        $query = "update opportunities set assigned_user_id='$bean->assigned_user_id' where id='$bean->id'";
                        $db->query($query);
                    }
                }
            }
        }
    }

    public function cam_mapping_insta(&$bean, $event, $args) 
    {
        if (($bean->stored_fetched_row_c["_c"] == "NeoCash Insta" || $bean->control_program_c == "NeoCash Insta") && !empty($bean->application_id_c)) {
            // Only Sacntioned and Reffered by CAM is Not empty only it will work. CSI -1121
            if ((!empty($bean->sales_stage) && $bean->sales_stage == 'Sanctioned') && !empty($bean->refferd_by_cam) && $bean->digital == 'no') {
    
                $user_bean = BeanFactory::getBean('Users');
    
                $query = 'users.deleted=0 and users.user_name = "' . $bean->refferd_by_cam . '"';
    
                $users = $user_bean->get_full_list('', $query);
    
                $userId = "";
    
                if (!empty($users)) {
                    global $db;
    
                    $user = $users[0];
    
                    $userId = $user->id;
    
                    $query = "update opportunities set assigned_user_id='$userId' where id='$bean->id'";
    
                    $db->query($query);
                }
                return;
            }
        }
    }
    public function assignmentMail(&$bean, $event, $args)
    {

        //echo "<pre>";print_r($bean->assigned_user_id);exit;
        $user_bean = BeanFactory::getBean('Users', $bean->assigned_user_id);

        //BeanFactory::getBean('Users',$escalate_to);


        require_once('custom/include/SendEmail.php');

        $email = new SendEmail();

        $bean->assigned_user_id;
        $type = "";
        if ($bean->control_program_c == "NeoCash Insta") {
            $type = " NeoCash Insta";
        }

        $user_id   = $bean->assigned_user_id;
        $cust_mobile = $bean->pickup_appointment_contact_c;
        $customer = $bean->name;

        $to = array($user_bean->email1);
        $name = $user_bean->name;
        //echo "<pre>";print_r($to);exit;
        $cc = array();

        $subject = "New$type Opportunity Assigned - $customer (Do not reply)";

        $body = "
		<pre>Hi,</br>
        You have been assigned a new<b>$type</b> Opportunity - $customer/$cust_mobile. Please check your Sales App to start working on this assignment.</br>
		
			Thanks,</br>
			Team NeoGrowth";

        if (!empty($to)) {
            //echo $to;exit;
            $email->send_email_to_user($subject, $body, $to, $cc);
        }
    }

    public function assignmentCustomerSMS(&$bean, $event, $args)
    {

        $myfile = fopen("Logs/oppCustomerSMS.log", "a");
        date_default_timezone_set("Asia/Calcutta");

        $user_id   = $bean->assigned_user_id;

        $user_bean = new User();

        $user_bean->retrieve($user_id);

        $assigned_user_old = $bean->stored_fetched_row_c['assigned_user_id'];

        $assigned_user_new = $bean->assigned_user_id;

        fwrite($myfile, $assigned_user_new . " " . $assigned_user_old . "\n");

        if (preg_match("/Customer Acquisition/i", $user_bean->designation) && !empty($bean->assigned_user_id) && strcmp($assigned_user_new, $assigned_user_old) != 0) {

            $user_bean = BeanFactory::getBean('Users', $bean->assigned_user_id);

            $cam_user_name = $user_bean->first_name . ' ' . $user_bean->last_name;

            $cam_user_mobile_no = !empty($user_bean->phone_mobile) ? $user_bean->phone_mobile : '';

            $bean->assigned_user_id;

            $user_id   = $bean->assigned_user_id;

            $cust_mobile = $bean->pickup_appointment_contact_c;

            $cust_mobile = "91" . substr($cust_mobile, -10);

            $message = 'Dear Customer,
            Thank you for applying at NeoGrowth. Your RM, "' . $cam_user_name . '" will call you shortly. You can reach him at "' . $cam_user_mobile_no . '" Regards, Team NeoGrowth’';

            $sms = new SendSMS();
            $a =  $sms->send_sms_to_user($tag_name = "Cust_CRM_41 ", $cust_mobile, $message, $bean);

            return true;
        } else {
            return true;
        }
    }

    public function assign_from_logs(&$bean, $event, $args)
    {

        if ($bean->control_program_c == "NeoCash Insta" && !empty($bean->application_id_c) && empty($bean->assigned_user_id)) {
            $file1 = fopen("Logs/unassignuser.log", "a");
            fwrite($file1, "unassigned- assign from logs");
            global $db;
            $q = "select after_value_string from opportunities_audit where parent_id='" . "$bean->id" . "' and field_name='assigned_user_id' order by date_created desc limit 1";
            $result = $db->query($q);
            $assigned = "";
            while (($row = $db->fetchByAssoc($result)) != null) {
                $assigned = $row['after_value_string'];
            }
            if (!empty($assigned)) {
                $q = "update opportunities set assigned_user_id='$assigned' where id='" . "$bean->id" . "'";
                $db->query($q);
            }
        }
    }
}
