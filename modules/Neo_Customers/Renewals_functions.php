<?php
require_once 'custom/CustomLogger/CustomLogger.php';
$logger = new CustomLogger('updaterenewedAppid');
require_once('include/entryPoint.php');

class Renewals_functions{

    public $ticket_size_arr = array('1'=>'10000000',
            '2'=>'25000000',
            '3'=>'20000000000000');

    private $log;
    function __construct() {
       // $this->log = fopen("Logs/RenewalsJob.log", "a");
    }
    function __destruct() {
      //  fclose($this->log);
    }

    function createHotLeadPhonebased($phone){
        $bean = BeanFactory::getBean('Neo_Customers');
        $query = "neo_customers.deleted=0 and neo_customers.mobile = $phone";
        $items = $bean->get_full_list('',$query);
        $msgg = array(
            'Success' => false,
            'Message' => 'No matching record found',
            'ID' => null
        );
        if(!empty($items)){
            $item = $items[0];
            if($item && $item->renewal_eligible) {
                if($item->queue_type=='cold_lead'){
                    $item->queue_type='hot_lead';
                    $item->source='sms_campaign';
                    $item->initiated_by = "sms_campaign";
                    $item->priority = 'P0';
                    $msgg = array(
                        'Success' => true,
                        'Message' => 'Cold lead converted to hot lead',
                        'ID' => $item->id
                    );

                }else if($item->queue_type=='hot_lead'){
                    $item->queue_type='hot_lead';
                    $item->source='sms_campaign';
                    // $hot_lead->initiated_by = "sms_campaign";
                    $item->priority = 'P0';
                    $msgg = array(
                        'Success' => true,
                        'Message' => 'Hot lead source changed',
                        'ID' => $item->id
                    );
                    sendHttpStatusCode(200,'OK');

                }
                $item->hot_lead_trigger_time = TimeDate::getInstance()->getNow()->asDb();
                $user_id = $renewals->getTATUser($item);
                if(!empty($user_id))
                    $item->assigned_user_id = $user_id;
                $item->save();

            }else{
                
                sendHttpStatusCode(404,'Not found');
            }

        }else{
            sendHttpStatusCode(404,'Not found');
        }
        
        echo json_encode($msgg);
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

    function isEligibleForTentativeOffer($bean){
        $result = false;
        // if(empty($bean->upfront_deduction_app_list)){
        //     echo "<p>Kindly Update App ID for Upfront Deduction<p>";
        // }
        //(empty($bean->instant_renewal_eligibility) && $bean->instant_renewal_eligibility == '0') &&
        if(!empty($bean->queue_type) && $bean->queue_type != 'not_eligible' 
            // && !empty($bean->upfront_deduction_app_list)
        ) {
            $result = true;
        }
        // fwrite($this->log, $bean->customer_id." tentative offer eligibility:$result\n");
        return $result;
    }

    function printJson($json){
        // var_dump($json);
        $arr = json_decode($json);
        // var_dump($arr);
        foreach($arr as $k=>$v){
            echo "<b>$k:</b> <i>$v</i><br/>";
        }
    }

    function printJson1($json){
        // var_dump($json);
        $arr = json_decode($json);
        // var_dump($arr);
        foreach($arr as $arr1){
            echo "<br/>";
            $arr = array($arr1);
            foreach($arr1 as $k=>$v){
                echo "<b>$k:</b> <i>$v</i><br/>";
            }
        }
    }


    function Adddisposition($bean, $event, $arguments){
         if(!(empty($bean->fetched_row) && $bean->in_save)){
            $old_disposition = $bean->fetched_row['disposition'];
            $new_disposition = $bean->disposition;
            if($old_disposition != $new_disposition){
                $sdh = new scrm_Disposition_History();
                $sdh->disposition_c     = $GLOBALS['app_list_strings']['renewals_disposition_list'][$new_disposition];
                $sdh->sub_disposition_c = $GLOBALS['app_list_strings']['renewals_subdisposition_list'][$bean->subdisposition];
                $sdh->assigned_user_id  = $GLOBALS['current_user']->name;
                $sdh->description  = $bean->description;
                $sdh->save();
            }
            // $bean->description="helo";
         }
    }


    function assignLeads(){
        fwrite($this->log, "\n--------------ConvertToHotLead::assignLeads start ".date('Y-m-d H:i:s')."---------------------\n");
        // $this->assign('Renewal TAT caller');
        $this->assign('Renewal manager');
        
       
        fwrite($this->log, "\n-------------ConvertToHotLead::assignLeads end------------\n");
    }

    function getTATUser($bean,$source = ""){
        fwrite($this->log, "\n-------------getTATUser start ".date('Y-m-d H:i:s')."---------------------\n");
        $time = $bean->hot_lead_trigger_time;
        $already_in_process = false;
        if(!empty($time)){
            $time = strtotime( "+1 month", strtotime( $time ) );
            if(strtotime('now')<$time){
                $already_in_process = true;
            }
        }
        // var_dump($bean->id);
        if(!$already_in_process){
            global $db;
            $query = "select count(*) as count from neo_customers_audit where parent_id='$bean->id' and date_created<= (NOW() - INTERVAL 1 MONTH) and field_name in ('description','renewed_app_id','disposition');";
            fwrite($this->log, "\nquery =$query\n");
            // var_dump($query);
            $result = $db->query($query);
            $row    = $db->fetchByAssoc($result);
            fwrite($this->log, "\nresult =".print_r($row,true));
            if(!empty($row)){
                $count = $row['count'];
                if((int)$count>0){
                    $already_in_process=true;
                }
            }
        }
        //TODO:Check for changes in description/renewed_app_id/disposition in audit log
        if(!$already_in_process){
            global $db, $sugar_config;
            $query = "";
            if($source == "cibil_trigger"){
                $query = "
                    SELECT ru.user_id, ru.user_name , (SELECT count(*) from neo_customers where assigned_user_id = ru.user_id) FROM renewal_users ru
                    LEFT JOIN neo_customers nc ON nc.assigned_user_id = ru.user_id AND nc.deleted = 0
                    WHERE ru.role = 'Renewal TAT caller' 
                    GROUP BY ru.user_id
                    ORDER BY (SELECT count(*) from neo_customers where assigned_user_id = ru.user_id)
                    LIMIT 1
                ";
            }
            else{
                $query  = "select * from renewal_users where role='Renewal TAT caller' limit 1";
            }
            $result = $db->query($query);
            $row    = $db->fetchByAssoc($result);
            fwrite($this->log, "\nresult =".print_r($row,true));
            return $row['user_id'];
        }else{
            $user =  BeanFactory::getBean('Users',$bean->assigned_user_id);
            if($user){
                $email =  $user->emailAddress->getPrimaryAddress($user);
            }
            $env = getenv('SCRM_ENVIRONMENT');
                $url = getenv('SCRM_SITE_URL');
                $sub = 'Customer Enquiry';
                $url .= "/index.php?module=Neo_Customers&action=DetailView&record=".$bean->id;
                $desc = "Hello,<br/>Customer has enquired on a lead you are already working on. Please go to $url.<br/><br/><br/>";
                fwrite($this->log,"\nURL=$url\n");

            require_once('custom/include/SendEmail.php');;
            $send = new SendEmail();
            if($env=='prod'){
                $send->send_email_to_user($sub,$desc,array($email),null,$bean);
            }else{
                $send->send_email_to_user($sub,$desc,$sugar_config['RF_non_prod_TATUsers_emails'],null,$bean,null,1);
            }
            return null;
        }
        fwrite($this->log, "\n-------------getTATUser end ".date('Y-m-d H:i:s')." end ---------------------\n");
    }

    function assign($role){
        global $db;
        $query  = "select * from renewal_users where role='$role'";
        $result = $db->query($query);
        fwrite($this->log, "\n-------------ConvertToHotLead::assignLeads Renewal manager------------\n");
        while ($row = $db->fetchByAssoc($result)) {
            $ticket_size = $row['ticket_size'];
            $city = $row['city'];
            $user_name = $row['user_name'];
            $user_id = $row['user_id'];//getUserID($user_name);
            fwrite($this->log, "\nAssigned User data data: ".print_r($row,true));
            if(!empty($user_id)){
                if($role == 'Renewal manager')
                    $query = $this->getQueryManager($city,$ticket_size);
                else
                    $query = $this->getQueryTAT();
                $items = $this->getCustomers($query);
                fwrite($this->log, "\nTotal Customers are : ".count($items));
                // die();
                if($items){
                    foreach($items as $item) {
                        fwrite($this->log, "\nCustomers data: ".print_r($item->name,true));
                        $item->assigned_user_id = $user_id;
                        $item->save();
                    }
                }
                
            }//if(!empty($user_id)){
        }
    }

    function getCustomers( $query){
        $bean = BeanFactory::getBean('Neo_Customers');
        fwrite($this->log, "\nQuery: ".print_r($query,true));
        // die();
        $items = $bean->get_full_list('',$query);
        return $items;
        fwrite($this->log, "\n-------------ConvertToHotLead::getItems end------------\n");
    }


    function getQueryManager($city, $ticket_size,$check_assigned_user=null){
        
        $city_arr = explode(",",$city);
        $city_str = "";
        foreach($city_arr as $k=>$city){
            if($k>0)$city_str .= ",";
            $city_str  .= "'".trim($city)."'";
        }
        if(empty($check_assigned_user))
        $query = "neo_customers.deleted=0 and neo_customers.location in ($city_str) and neo_customers.assigned_user_id in (null,'') ";
        else
            $query = "neo_customers.deleted=0 and neo_customers.location in ($city_str) ";

        if($ticket_size=="1")
            $query .= "and ( loan_amount<1040000 OR loan_amount = 0 )";
        else if($ticket_size=="2")
            $query .= "and ( loan_amount BETWEEN 1040000 AND 2500000 OR loan_amount = 0 )";
        else if($ticket_size=="3")
            $query .= "and ( loan_amount > 2500000 OR loan_amount = 0 )";
        else if($ticket_size=="1,2"){
             $query .= "and ( loan_amount < 2500000 OR loan_amount = 0 )";
        }else if($ticket_size=="1,3"){
             $query .= "and ( loan_amount <1040000 OR loan_amount > 2500000 OR loan_amount = 0 )";
        }else if($ticket_size=="2,3"){
             $query .= "and (loan_amount >= 1040000 OR loan_amount = 0 )";
        }else {
            $query .= "and loan_amount >= 0";
        }
        
        
        return $query;
    }

    function getQueryTAT(){

        $query = "neo_customers.deleted=0 and neo_customers.queue_type='hot_lead' and neo_customers.source in ('cibil_trigger','sms_campaign','merchant_app', 'email_campaign')";
        return $query;
    }


    function assignUserId($bean){
        $amount = (int)$bean->loan_amount;
        $ticket_size = 0;
        $location = $bean->location;
        $ticket_size = $this->getTicketSizeAmount($bean->amount);
        $user_data = $this->getRenewalUser($ticket_size,$location);
        if(!empty($user_data))
        $bean->assigned_user_id = $user_data['user_id'];
    }

    function getTicketSizeAmount($amount){

        if($amount<1000000){
            $ticket_size=1;
        }else if($amount>2500000){
            $ticket_size=3;
        }else{
            $ticket_size=2;
        }
        return $ticket_size;
    }

    // function getAmountForTicketSize($ticket_size_key){
    //     $ticket_size_arr = $this->ticket_size_arr;


    // }

    function getAllRenewalUsers(){
        global $db;
        $query = "select * from renewal_users";
        // echo $query; echo "<br>";
        
        return $this->dbQueryAll($query);
    }

    function getRenewalUsersByRole($role){
        global $db;
        $query = "select * from renewal_users where role = '$role'";
        // echo $query; echo "<br>";
        
        return $this->dbQueryAll($query);
    }

    function dbQueryAll($query){
         global $db;
         $res = $db->query($query);
        return $res;
    }

    function getRenewalBean($customer_id){
        $neo_customer = new Neo_Customers();
        $bean  = BeanFactory::getBean("Neo_Customers");
        $query = "deleted=0 and customer_id=$customer_id";
        $items = $bean->get_full_list('',$query);
        if(!empty($items)){
            $item = $items[0];
            return $item;
        }
        return null;

    }

    function getRenewalUser($ticket_size,$location){
        global $db;
        $query = "select * from renewal_users where ticket_size='$ticket_size' and city='$location'";
        // echo $query;
        
        return $this->dbQuery($query);
    }

    function getRenewalUserById($user_id){
        global $db;
        $query = "select * from renewal_users where user_id='$user_id'";
        // echo $query;
        
        return $this->dbQuery($query);
    }

    function dbQuery($query){
         global $db;
         $res = $db->query($query);
         $row = $db->fetchByAssoc($res);
        return $row;
    }

    static function isRenewalsUser($roles){
        $results = false;
        if(empty($roles)) {
            return $results;
        }
        foreach ($roles as $role) {
            if(stripos($role,"Renewal") !== false){
                $results = true;
                break;
            }
        }   
        return $results;
    }

    function updateRenewedAppIds($ids, $changed_values){
        try{
            $myfile = fopen("Logs/RenewalsJob.txt", "a");
            fwrite($this->log, "\n-----------------function :: updateRenewedAppIds------------------\n" . $ids);
            $response = true;
            $bean = BeanFactory::getBean('Neo_Customers');
            $query = "neo_customers.id in $ids and neo_customers.deleted=0";
            $items = $bean->get_full_list('',$query);
            fwrite($this->log, "\nNo of Neo_customers to be updated :: " . sizeof($items));
            //print_r($changed_values);
            // var_dump($items);
            if ($items){
                foreach($items as $item){
                    //echo "<br>" . $item->name;
                    fwrite($this->log, "\nname :: " . $item->name);
                    $app_list_old   = $changed_values[$item->id]['old_value'];
                    $old_app_count  = count(explode(',',$app_list_old));
                    $app_list_new   = $changed_values[$item->id]['new_value'];
                    $app_list_new   = explode(",", $app_list_new);
                    $new_app_count  = count($app_list_new);
                    //echo "<br>" . $new_app_count." ".$old_app_count;
                    fwrite($this->log, "\nNew count: " . $new_app_count." old count : ".$old_app_count);
                    if($new_app_count>$old_app_count){
                        $new_app    = array();
                        $diff_count = $new_app_count - $old_app_count;
                        //echo "<br>$diff_count";
                        $new_app    = array_slice($app_list_new, -1*$diff_count, $diff_count);
                        $new_app    = implode(",", $new_app);
                        if(!empty($new_app)){
                            //echo "new app is ".$new_app;
                            fwrite($this->log, "\nnew_app :: " . $new_app);
                            $item->renewed_app_id = $new_app;
                            $item->save();
                        }
                    }
                }
            }
        }
        catch(Exception $e){
            fwrite($this->log, "\nException in updateRenewedAppIds :: " . $e->getMessage());
            return false;
        }

        return $response;
    }

    function checkRenewedAppIdsFromAudit($last_run_date){
        $logger = new CustomLogger('updaterenewedAppid');
        $logger->log('debug', "<--------------called function checkRenewedAppIdsFromAudit---------------->");
        try{
            $response = true;
            global $db;
            $query          = "select id, parent_id, field_name, before_value_string, after_value_string from neo_customers_audit where date_created > '$last_run_date' and field_name = 'app_id_list' ";
            $results        = $db->query($query);
            
            $ids            = array();
            $changed_values = array();
           // print_r($results);
            $logger->log('debug', "\nrows fetched from db fetch (audit log entries) :: " . $results->num_rows);
            while($row = $db->fetchByAssoc($results)){
                if(!isset($row['parent_id']) && empty($row['parent_id'])
                    && !isset($row['field_name']) && empty($row['field_name'])
                    && !isset($row['before_value_string']) && empty($row['before_value_string'])
                    && !isset($row['after_value_string']) && empty($row['after_value_string'])){
                        $logger->log('debug',  "\nfatal :: checkRenewedAppIdsFromAudit :: Details not available for neo_customers_audit id = " . $row['id']);
                    $response = false;
                    continue;
                }
                $values = array();
                array_push($ids, $row['parent_id']);
                $values['old_value'] = $row['before_value_string'];
                $values['new_value'] = $row['after_value_string'];
                $changed_values[$row['parent_id']] = $values;
                //print_r($changed_values);
            }
            print_r($ids);
            $ids_str = "('" . implode("','", $ids) . "')";
            $logger->log('debug', "\nids to fetch :: " . $ids_str);
            $logger->log('debug', "\nChanged Values :: " . print_r($changed_values));
            if(!empty($ids_str) && !empty($changed_values)){
                $response = $response && $this->updateRenewedAppIds($ids_str, $changed_values);
            }
            return $response;
        }
        catch(Exception $e){
            $logger->log('debug',  "\nException in checkRenewedAppIdsFromAudit :: " . $e->getMessage());
            return false;
        }
    }

    function sendSMSToEligibleCustomerFromAudit($last_run_date){
        try{
            if(empty($last_run_date)){
                $last_run_date =  date("Y-m-d H:i:s",strtotime("-1 days"));
            }
            global $db;
            $query          = "select id, parent_id, field_name, before_value_string, after_value_string from neo_customers_audit where date_created > '$last_run_date' and field_name = 'renewal_eligible' and after_value_string=true ";
            $results        = $db->query($query);
            $ids            = array();
            $changed_values = array();
            $message="Dear Customer, You are now eligible for top-up loan. Give miss call on 9152007511 or write to us at renewal@neogrowth.in (T&C)";
            require_once 'custom/include/SendSMS.php';
            //print_r($results);
            fwrite($this->log, "\nrows fetched from db fetch (audit log entries) :: " . $results->num_rows);
            while($row = $db->fetchByAssoc($results)){
                $id = $row['parent_id'];
                $bean = BeanFactory::getBean('Neo_Customers',$id);
                $mobile = $bean->mobile;
                if(!empty($mobile)){
                    $sms = new SendSMS();
                    $env = getenv('SCRM_ENVIRONMENT');
                    if($env == 'prod'){
                        $sms->send_sms_to_user($tag_name="Cust_CRM_2", $mobile, $message, $bean);
                    }
                }
            }
            return true;
        }
        catch(Exception $e){
            fwrite($this->log, "\nException in checkEligibleCustomerFromAudit :: " . $e->getMessage());
            return false;
        }
    }

    function updateMaxCustomerCount(){
        global $db;
        $results = $this->getAllRenewalUsers();
        while ($row = $db->fetchByAssoc($results)) {
            $current_max_count = 0;
            $max_neo_customers = 0;
            $user_id            = $row['user_id'];
            $user_name          = $row['user_name'];
            $ticket_size        = $row['ticket_size'];
            $ticket_size_arr    = explode(",", $row['ticket_size']);
            $city               = $row['city'];
            $city_arr           = explode(",", $row['city']);
            $role               = $row['role'];
            if(!empty($row['max_neo_customers'])){
                $max_neo_customers  = $row['max_neo_customers'];
            }

            $query = $this->getNeoCustomerListQuery($user_id,$ticket_size, $city, $role);
            echo "query :: ";print_r($query);echo "<br>";   die(); 
            $current_max_count[$user_id] = $this->dbQuery($query);
            // echo "current_max_count :: ";print_r($current_max_count);echo "<br>";
        }
        $query = "
            UPDATE renewal_users
            SET max_neo_customers = 
                (
                    CASE 
                        WHEN id in [$user_id] THEN $current_max_count[$user_id]
                        ELSE max_neo_customers 
                    END
                ),
            last_entered = NOW()
            ";
            //WHEN max_neo_customers < $current_max_count THEN $current_max_count 
        $update_results = $this->dbQuery($query);

        print_r($results);
    }

    function getNeoCustomerListQuery($user_id,$ticket_size, $city, $role){

            $query = "
                SELECT COUNT(*) FROM neo_customers 
                ";
            $query .= " 
                LEFT JOIN users
                ON neo_customers.assigned_user_id=users.id
                ";
            $where_auto = "  neo_customers.deleted=0";

            if($role == 'Renewal manager')
                $where .= $this->getQueryManager($city,$ticket_size,1);
            else
                 $where .= "AND neo_customers.assigned_user_id='$user_id' ";

            if($where != "")
                    $query .= " where $where AND ".$where_auto;
            else
                    $query .= " where ".$where_auto;
            
            $query .= " GROUP BY customer_id,location";

            if($order_by != "")
                    $query .= " ORDER BY $order_by";
            else
                    $query .= " ORDER BY neo_customers.customer_id";
                print_r($query); echo "<br>";
            return $query;
    }

    function updateRenewalUserActivity($query){
        $myfile = fopen("Logs/renewalUserAnalytics.log", "a");
        $response = true;
        global $db;
        fwrite($this->log, "\n"."count_query - ".$query); 
        $results = $this->dbQueryAll($query);
        print_r($results);
        if($results){
            fwrite($this->log, "\n"."Max user count updated"); 
            $response = true;
        }
        else{
            fwrite($this->log, "\n"."Max user count updation Failed"); 
            $response = false;
        }
        return $response;

    }

    function maxCustomerCount(){
        global $db;
        $logger = new CustomLogger('renewalUserAnalytic');
        $logger->log('debug', "-------------maxCustomerCount::Starts------------");
        global $timedate;
        $logger->log('debug', "time - ".$timedate->now());
        $max_count = array();
        $loc_caller_query = " 
            INSERT INTO renewals_user_activity 
            (id,user_id,date_created, activity_key, activity_value)
            SELECT 
            UUID(),assigned_user_id, NOW(), location, count(*) as 'current_count'
            FROM neo_customers neo_customers
            LEFT JOIN renewal_users ru ON ru.user_id = neo_customers.assigned_user_id
            WHERE neo_customers.deleted = 0
            AND neo_customers.renewal_eligible = 1
            AND ru.role = 'Renewal Location caller'
            GROUP BY neo_customers.assigned_user_id, neo_customers.location
            ";
        $tat_caller_query = " 
            INSERT INTO renewals_user_activity 
            (id,user_id,date_created, activity_key, activity_value)
            SELECT 
            UUID(),assigned_user_id, NOW(), location, count(*) as 'current_count'
            FROM neo_customers neo_customers
            LEFT JOIN renewal_users ru ON ru.user_id = neo_customers.assigned_user_id
            WHERE neo_customers.deleted = 0
            AND neo_customers.renewal_eligible = 1
            AND ru.role = 'Renewal TAT caller'
            GROUP BY neo_customers.assigned_user_id, neo_customers.location
            ";
        $admin_query = " 
            INSERT INTO renewals_user_activity 
            (id,user_id,date_created, activity_key, activity_value)
            SELECT 
            UUID(), ru.user_id, NOW(),location, count(*) as 'current_count'
            FROM neo_customers neo_customers
            LEFT JOIN renewal_users ru on neo_customers.id
            WHERE ru.role = 'Renewal admin'
            AND neo_customers.renewal_eligible = 1
            GROUP BY ru.user_id, location;
            ";
        $update_success = true;
        $update_success = $update_success && $this->updateRenewalUserActivity($loc_caller_query);
        $update_success = $update_success && $this->updateRenewalUserActivity($tat_caller_query);
        $update_success = $update_success && $this->updateRenewalUserActivity($admin_query);
        $results = $this->getRenewalUsersByRole("Renewal manager");
        while($row=$db->fetchByAssoc($results)){
            $renewal_manager_id = $row['user_id'];
            $ticket_size = $row['ticket_size'];
            $city = $row['city'];
            $role = $row['role'];      
            if(empty($renewal_manager_id) || empty($ticket_size) || empty($role) || empty($city)){
                $logger->log('debug', "Missing important details. Skipping activity update for manager $renewal_manager_id");
                $logger->log('debug', "Manager ticket_size :: $ticket_size, city :: $city, role :: $role");
            }     
            $logger->log('debug', "Manager ticket_size :: $ticket_size, city :: $city, role :: $role");
            // echo("Manager ticket_size :: $ticket_size, city :: $city, role :: $role<br>");
            $where_query = "";
            $manager_query = "";
            $where_query = 'AND ' . $this->getQueryManager($city,$ticket_size,1);
            $logger->log('debug', "Manager where query :: $where_query"); 
            $manager_query = "
                INSERT INTO renewals_user_activity 
                (id,user_id,date_created, activity_key, activity_value)
                SELECT 
                UUID(),'$renewal_manager_id', NOW(), location, count(*) as 'current_count'
                FROM neo_customers
                WHERE neo_customers.renewal_eligible = 1 
                $where_query
                GROUP BY neo_customers.location;
            "; 
            // print_r($manager_query); echo "<br>";
            $update_success = $update_success && $this->updateRenewalUserActivity($manager_query);
        }
        return $update_success;
    }

    function updateDispositionBasedOnQueueType($bean, $event, $arguments){
        $new_queue_type = "";
        $old_queue_type = "";
        $new_queue_type = $bean->queue_type;
        //workflow start: Create not eligible queue
        if(empty($bean->queue_type)){
            $bean->queue_type = 'not_eligible';
        }//workflow end: Create not eligible queue
        if(empty($bean->fetched_row)){
            // echo "enter empty<br>";
            if($new_queue_type == 'cold_lead' || $new_queue_type == 'hot_lead'){
                // echo "EMPTY IF<br>";
                $bean->disposition = "not_yet_contacted";
            }
            else{
                // echo "EMPTY ELSE<br>";
                $bean->disposition = "";
            }
        }
        else{
            $old_queue_type = $bean->fetched_row['queue_type'];
            if($old_queue_type == '' && $new_queue_type == 'not_eligible'){
                // echo "NULL to NE<br>";
                $bean->disposition = "";
            }
            elseif (($old_queue_type == '' || $old_queue_type == 'not_eligible') 
                && ($new_queue_type == 'cold_lead' || $new_queue_type == 'hot_lead')){
                //NE to CL, NE to HL
                // echo "NE to CL, NE to HL<br>";
                $bean->disposition = "not_yet_contacted";
            }
            elseif ($old_queue_type == 'cold_lead' && $new_queue_type == 'hot_lead' && empty($bean->disposition)) {
                //CL to HL
                // echo "CL to HL<br>";
                $bean->disposition = "not_yet_contacted";
            }
            elseif (($old_queue_type == 'cold_lead' || $old_queue_type == 'hot_lead')
                && $new_queue_type == "not_eligible") {
                //HL to NE, CL to NE
                // echo "HL to NE, CL to NE<br>";
                $bean->disposition = "";
            }
            //workflow start: Create hot lead 
            elseif($old_queue_type == 'cold_lead' && $bean->disposition=='interested'){
                $bean->queue_type='hot_lead';
                $bean->source='manual';
                $bean->hot_lead_trigger_time=TimeDate::getInstance()->getNow()->asDb();
            }
            //workflow end: Create hot lead 
            else{
                // echo "HOLY ELSE<br>";
            }
            //HL to CL - Retain disposition
        }
    }
    function updateDispositionForOldRecords(){
        //update dispositions for renewal customers who stays in intrested for 2 months as 'Not Contacted Yet'
        global $timedate;
        $response = true;
        fwrite($this->log, "\n-------------updateDisposition() starts------------\n");
        fwrite($this->log, "\n--------------" . $timedate->now() . "-----------\n");
        //incase to optimisize or reduce query output, filter with last_successfull run
        global $db;
        $query = "
            SELECT DISTINCT parent_id 
            FROM neo_customers_audit 
            WHERE field_name = 'disposition' 
            AND after_value_string = 'interested'
            AND date_created <= DATE_SUB(SYSDATE(), INTERVAL 60 DAY)
            "
            ;
        fwrite($this->log, "\nFetch id for customer : $query \n");
        $results = $db->query($query);
        fwrite($this->log, "\nTotal rows fetched : $results->num_rows \n");
        $parent_array = array();
        while($row = $db->fetchByAssoc($results)){
            if(!empty(trim($row['parent_id']))){
                array_push($parent_array, "'" . $row['parent_id'] . "'");
            }
        }
        if(!empty($parent_array)){
            $parent_string = implode(",", $parent_array);
            fwrite($this->log, "\nCustomer IDs 'changed_before_60_days' : $parent_string \n");
            //fetch parent id for dispositon changed in last 60 days
            $parent_disp_change_array = $this->fetchDispostionChangedParentID($parent_string);
            $parent_disp_change_string = implode(",", $parent_disp_change_array);
            fwrite($this->log, "\nCustomer IDs 'changed_in_60_days' : $parent_disp_change_string \n");
            //if dispostion is changed, dont update those dispositions
            $parent_array = array_diff($parent_array, $parent_disp_change_array);
            $parent_string = implode(",", $parent_array);
            fwrite($this->log, "\nCustomer IDs 'filtered ones' : $parent_string \n");
            $bean = BeanFactory::getBean('Neo_Customers');
            $query_1 = "neo_customers.deleted=0 and neo_customers.id in ($parent_string)";  
            $items = $bean->get_full_list('',$query_1);
            if ($items){
                foreach($items as $item){
                    // var_dump($item);
                    if(!empty($item->id) && $item->disposition == 'interested'){
                        fwrite($this->log, "\n dispositions are updated for $item->name\n");
                        $item->disposition = 'not_yet_contacted';
                        $item->save();
                    }
                }
                fwrite($this->log, "\n dispositions are updated to 'not_yet_contacted'\n");
            }
            else{
                fwrite($this->log, "\n dispositions update failed. No Beans Found \n");
                $response = false;
            }
        }
        else{
            fwrite($this->log, "\n No ID found. \n");
        }
        fwrite($this->log, "\n-------------Response :: $response------------\n");
        fwrite($this->log, "\n-------------updateDisposition() ends------------\n");
        return $response;
    }

    function updateQueueForOldRecords(){
        //update queue for renewal customers who stays in intrested for 2 months as 'Not Interested'
        //From hot lead to cold lead
        global $timedate;
        $response = true;
        fwrite($this->log, "\n-------------updateQueueForOldRecords() starts------------\n");
        fwrite($this->log, "\n--------------" . $timedate->now() . "-----------\n");
        //incase to optimisize or reduce query output, filter with last_successfull run
        global $db;
        $query = "
            SELECT id
            FROM neo_customers
            WHERE queue_type = 'hot_lead' 
            AND disposition = 'not_interested'
            "
            ;
        fwrite($this->log, "\nFetch id for customer - hot_lead, not_interested : $query \n");
        $results = $db->query($query);
        fwrite($this->log, "\nTotal rows fetched : $results->num_rows \n");
        $customer_list_1 = array();
        $customer_list_2 = array();
        while($row = $db->fetchByAssoc($results)){
            if(!empty(trim($row['id']))){
                array_push($customer_list_1, $row['id']);
            }
        }
        //fetch parent id for disposition changed in last 60 days
        //if disposition is changed, dont update those queue
        $customer_list_2 = $this->getChangedFieldCustomerId('disposition', 60, 'not_interested');
        $result = array_diff($customer_list_1,$customer_list_2);

        //fetch parent id for source changed in last 60 days - hot lead trigger
        //if source is changed, dont update those queue
        $customer_list_2 = $this->getChangedFieldCustomerId('source', 60, null, 1);
        $result = array_diff($customer_list_1,$customer_list_2);

        if(!empty($result)){
            $parent_array = array();
            foreach ($result as $row) {
                array_push($parent_array, "'" . $row . "'");
            }
            $parent_string = implode(",", $parent_array);
            fwrite($this->log, "\nCustomer IDs 'filtered ones' : $parent_string \n");
            $bean = BeanFactory::getBean('Neo_Customers');
            $query_1 = "neo_customers.deleted=0 and neo_customers.id in ($parent_string)";  
            $items = $bean->get_full_list('',$query_1);
            if ($items){
                foreach($items as $item){
                    // var_dump($item);
                    if(!empty($item->id) && $item->disposition == 'not_interested'){
                        fwrite($this->log, "\n queue_type is updated for $item->name\n");
                        $item->queue_type ='cold_lead';
                        $item->hot_lead_trigger_time = '';
                        $item->save();
                    }
                }
                fwrite($this->log, "\n queue_type is updated to 'cold_lead'\n");
            }
            else{
                fwrite($this->log, "\n queue_type update failed. No Beans Found \n");
                $response = false;
            }
        }
        else{
            fwrite($this->log, "\n No ID found. \n");
        }
        fwrite($this->log, "\n-------------Response :: $response------------\n");
        fwrite($this->log, "\n-------------updateQueueForOldRecords() ends------------\n");
        return $response;

    }

    function getChangedFieldCustomerId($field_name = null, $interval_days = 60, $new_value = null, $new_value_not_null = 0){
        $customer_list = array();
        fwrite($this->log, "\nField Name is $field_name \n");
        fwrite($this->log, "\nInterval days is $interval_days \n");
        global $db;
        $query = "
            SELECT DISTINCT parent_id 
            FROM neo_customers_audit 
            "
            ;
        $where = "";
        if(!empty($field_name)){
            if(!empty($where)){
                $where .= " AND field_name = '$field_name' ";
            }
            else{
                $where .= " field_name = '$field_name' ";   
            }
        }
        if(!empty($new_value)){
            if(!empty($where)){
                $where .= " AND after_value_string = '$new_value' ";
            }
            else{
                $where .= " after_value_string = '$new_value' ";   
            }
        }
        else{
            if(!empty($new_value_not_null)){
                $where .= " AND after_value_string IS NOT NULL";
            }
        }

        if(!empty($interval_days)){
            if(!empty($where)){
                $where .= " AND date_created < DATE_SUB(SYSDATE(), INTERVAL $interval_days DAY) ";
            }
            else{
                $where .= " date_created < DATE_SUB(SYSDATE(), INTERVAL $interval_days DAY) ";   
            }
        }
        if(!empty($where)){
            $query = $query . " WHERE " . $where;
        }
        fwrite($this->log, "\nFetch parent_id for customer - $query \n");
        $results = $db->query($query);
        fwrite($this->log, "\nTotal rows fetched : $results->num_rows \n");
        while($row = $db->fetchByAssoc($results)){
            if(!empty(trim($row['parent_id']))){
                array_push($customer_list, $row['parent_id']);
            }
        }
        // print_r($query);
        // echo "<br>";
        return $customer_list;
    }

    function fetchDispostionChangedParentID($parent_string){
        global $db;
         $query = "
            SELECT DISTINCT parent_id 
            FROM neo_customers_audit 
            WHERE field_name = 'disposition' 
            AND date_created >= DATE_SUB(SYSDATE(), INTERVAL 60 DAY)
            AND parent_id IN ($parent_string)
            "
            ;      
        $results = $db->query($query); 
        $parent_disp_change_array = array();
        while($row = $db->fetchByAssoc($results)){
            if(!empty(trim($row['parent_id']))){
                array_push($parent_disp_change_array, "'" . $row['parent_id'] . "'");
            }
        }
        return $parent_disp_change_array;
    }

    //this function will split the renewed app id and save it in seperate table with is_crm_renewal flag set to 1
    function insertCrmRenewedAppInfo($customer,$app_id_str){
        global $db;
        $app_id_arr = explode(",", $app_id_str);
        $insert_data_list = array();
        foreach ($app_id_arr as $app_id) {
            $insert_data = "";
            $insert_data = "(" 
                . "'" . $app_id . "',"
                . "'" . $customer->id . "',"
                . 1 . ","
                . " NOW(), NOW()" 
                .")";  
            array_push($insert_data_list, $insert_data);
        }
        print_r($insert_data_list);echo "<br>";
        $insert_data_list = implode(',', $insert_data_list);
        if(empty($insert_data_list)){
            return;
        }
        $query = "
            INSERT INTO neo_customers_app
            (app_id, customer_bean_id, is_crm_renewal, date_created, date_modified)
            VALUES $insert_data_list
        ";
        echo "query :: " . $query . "<br>";
        $results = $db->query($query);
        if($results){
            echo "<p>Renewed app id updated successfully in CRM.</p>";
        }
        else{
            echo "<p>Renewed app id updated in CRM Failed.</p>";
        }
        return;
    }

    //Fetch crm renewed apps details. (Renewed via CRM)
    function fetchNeoAppsForCustomer($customer_id, $where = ''){
        global $db;
        $query = "
            SELECT 
            app_id, customer_bean_id, is_crm_renewal, date_created, date_modified
            FROM neo_customers_app where
            $where
            ";
        $neo_customer_apps = array();
        $results = $db->query($query);
        while ($row = $db->fetchByAssoc($results)) {
            array_push($neo_customer_apps, $row);
        }
        return $neo_customer_apps;
    }

    //Fetch crm renewed apps list. (Renewed via CRM)
    function fetchCrmRenewedNeoAppIds($customer_id){
        $neo_customer_apps = $this->fetchNeoAppsForCustomer($customer_id, 'is_crm_renewal = 1');
        $app_list = array();
        foreach ($neo_customer_apps as $app) {
            array_push($app_list, $app['app_id']);
        }
        return $app_list;
    }






}