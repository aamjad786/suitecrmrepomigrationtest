<?php
require_once('data/BeanFactory.php');
require_once 'custom/CustomLogger/CustomLogger.php';
class DataSync{
    /*
     * The method that does the checking for the specific fields. This is
     * the method that we'll want to call in the logic_hooks.php file.
     */
    public function __construct() {
		$this->logger = new CustomLogger('casesLogicHooks-'.date('Y-M-d'));
	}
    function CheckUpdatedFields($bean, $event, $arguments){
        $this->logger->log('debug', "---Inside data sync CheckUpdatedFields for case $bean->id---");
        if($_REQUEST['module'] != 'Import') {
            
            if(empty($bean->name)){
                if(!empty($bean->description)){
                    $bean->name = substr($bean->description,0, 50);
                } else {
                    $bean->name = "Blank Subject";
                }
            }
            
            $userDepartment = $this->getUserDepartment($bean->assigned_user_id);
            if($bean->current_user_department_c != $userDepartment){
                $bean->current_user_department_c = $userDepartment;
            }

            // $attended_by_c = $bean->attended_by_c;
            // $this->logger->log('debug', " $bean->id start attended_by  $attended_by_c");

            if($bean->case_subcategory_c == "financial_live_restructure"){
                $bean->type = "request";
                $bean->case_action_code_c = "non_ftr";
            }

            if($bean->case_subcategory_c == "information_ex_gratia"){
                $bean->type = "query";
                $bean->case_action_code_c = "ftr";
            }
            
            if(empty($bean->assigned_user_department_c) && (strpos($userDepartment, 'Customer') === false) ){
                $bean->assigned_user_department_c = $userDepartment;
                $bean->date_assigned_to_dept_c = $bean->date_modified;
            }

            if(!empty($bean->merchant_contact_number_c)){
                $bean->merchant_contact_number_c = htmlentities(htmlspecialchars($bean->merchant_contact_number_c));
            }
    
            if(!empty($bean->update_text)){
                $bean->update_text = htmlentities(htmlspecialchars($bean->update_text));
            }

            if(!(empty($bean->fetched_row) && $bean->in_save)){
                $this->edit_case($bean);
            }
            else{
                $this->create_case($bean);
            }

            // $attended_by_c = $bean->attended_by_c;

            if($bean->state == 'Closed'){
                $bean->date_attended_c = $bean->date_entered;
            } 
            else if($bean->state == 'Resolved'){
                $bean->date_attended_c =  $bean->date_modified;
            }
            // $this->logger->log('debug', "end attended_by_c  $attended_by_c");
        }
        //$bean->call_sns();
        $this->logger->log('debug', "---END data sync CheckUpdatedFields for case $bean->id---");
    }

    function create_case($bean) {
        $this->logger->log('debug', "New case $bean->id");
        global $db, $current_user, $sugar_config; 
        require_once 'modules/ACLRoles/ACLRole.php';
        $objACLRole = new ACLRole();

        $bean->age_c = 0;

        //Get the current user's role
        $roles = $objACLRole->getUserRoles($GLOBALS['current_user']->id);
        //check if they are in the Admin or Admin Manager's role
        if(!in_array($sugar_config['Customer_support_executive_Assignment_Dynamic'], $roles)) {
            //Set to null if its not a call back request from website, coz calling from api with admin credentials
            $bean->assigned_user_id = NULL;
        }
        // else{
        //     $bean->attended_by_c = $this->getUserName($bean->assigned_user_id);
        // }

        $this->logger->log('debug', "Assigned user id before : $bean->assigned_user_id");

        if(empty($bean->assigned_user_id)){
            if($current_user->user_name == $sugar_config['user_ng171']){
                $bean->assigned_user_id = $current_user->id;
            }
            else{
                $bean->assigned_user_id = $bean->getUserToAssign();
            }
            // $bean->attended_by_c = $this->getUserName($bean->assigned_user_id);

            $this->logger->log('debug', "Assigned user id after : $bean->assigned_user_id");

            $auditid=create_guid();
            $created_by=$current_user->id;

            date_default_timezone_set('Asia/Kolkata');
            $timestamp = date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s')) - (5*60*60+30*60));//subtract 5h 30min from current time;
            
            $this->logger->log('debug', "Auditid: $auditid  created_by: $created_by Date: " . date('Y-m-d H:i:s'));
            
            $query="insert into cases_audit values ('$auditid','$bean->id','$timestamp','$created_by','assigned_user_id','relate',null,'$bean->assigned_user_id',null,null)";
            $result=$db->query($query);

            $this->logger->log('debug', "Cases Audit Query : $query");
        }
        if($bean->state == 'Closed'){
            $bean->date_closed_c = $bean->date_entered;
            $bean->date_resolved_c = $bean->date_entered;
            // $bean->attended_by_c = $this->getUserName($bean->assigned_user_id);
            $bean->closed_by_c = $this->getUserName($GLOBALS['current_user']->id);
        }
        $this->logger->log('debug', "create_case end");
    }

    function edit_case($bean) {
        $this->logger->log('debug', "Editing case $bean->id");

        global $current_user, $sugar_config; 

        if (empty($bean->date_attended_c) && strtotime($bean->date_entered)!=strtotime($bean->date_modified)) {
            if($bean->state != "Closed"){
                if(!empty($bean->case_subcategory_c)) {
                    $bean->state = 'In_progress';
                    $current_date = TimeDate::getInstance()->nowDb();
                    $bean->date_attended_c = empty($bean->date_attended_c) ? $current_date : date('Y-m-d H:i:s',strtotime($bean->date_attended_c));
                }
            }
        }
        
        if($bean->state == 'Open' ){
            if(!empty($bean->case_subcategory_c)){
                $current_date = TimeDate::getInstance()->nowDb();
                $bean->date_attended_c = empty($bean->date_attended_c) ? $current_date : date('Y-m-d H:i:s',strtotime($bean->date_attended_c));
                $bean->state = 'In_progress';

            }
        }

        require_once('custom/include/SendEmail.php');
        require_once("custom/modules/Cases/CallBackFlow.php");
        
        $send = new SendEmail();
        $callBackFlow = new CallBackFlow();
        $to_mail_webcallback = array();
        $displayCategoryValue = ($GLOBALS['app_list_strings']['case_category_c_list'][$bean->case_category_c]);
        $sub = "Case [SR-#$bean->case_number] Updated for App ID: $bean->merchant_app_id_c ($bean->merchant_name_c) - $displayCategoryValue";
        $desc = '';
        $app_host = getenv('SCRM_ENVIRONMENT');
        $key_fields = array('assigned_user_id',
                            'state',
                            'update_text',
                            'resolution',
                            'merchant_contact_number_c');

        foreach($key_fields as $key_field){
            $p1 = $bean->fetched_row[$key_field];
            $p2 = $bean->$key_field;
            
            // do a change detection leveraging the fetched_row value
            if($bean->$key_field != $bean->fetched_row[$key_field]){
                if($key_field == 'assigned_user_id'){
                    $old_name = $this->getUserName($p1);
                    $new_name = $this->getUserName($p2);
                    
                    if($p1!=$p2 && !empty($old_name) && !empty($new_name))
                        $desc .= "Case was assigned from ".$old_name." to ".$new_name.".<br/>";
                    //for website call back cases we have seperate mail format
                    if($bean->is_call_back_c == 1){
                        $call_remainder_body = $callBackFlow->getEmailBodyForCallBackRemainder($bean);
                        $call_remainder_subject = $callBackFlow->getEmailSubForCallBackRemainderFromCall($bean);
                        $to_mail_webcallback = $callBackFlow->getToMailForAcase($bean);
                        if(!empty($call_remainder_body)){
                            $desc = $call_remainder_body;
                        }
                        if(!empty($call_remainder_subject)){
                            $sub = $call_remainder_subject;
                        }
                    } 
                    $new_assigned_user = $this->getUser($p2);
                    if(isset($new_assigned_user->phone_mobile)) {
                        $sms = "Dear $new_name,
                        Case [SR-#$bean->case_number] for App ID: $bean->merchant_app_id_c ($bean->merchant_name_c) - $displayCategoryValue is assigned to you.";
                        $this->sendnetcore($tag_name="Cust_CRM_Case_Assigned", $new_assigned_user->phone_mobile, $sms, $bean);
                    }
                }
                else if($key_field == 'update_text'){
                    $desc .= "Case had a comment from ".$this->getUserName($current_user->id)." on ".date('Y/m/d').":<br><i>".$bean->update_text.".</i><br/>";
                }
                else if($key_field == 'state'){
                    if ($p2 == 'Closed'){
                        // if (empty($bean->attended_by_c))
                        //    $bean->attended_by_c = $this->getUserName($bean->assigned_user_id);
                        if (empty($bean->date_resolved_c))
                            $bean->date_resolved_c = $bean->date_modified;
                        $bean->date_closed_c = $bean->date_modified;
                        $bean->closed_by_c = $this->getUserName($GLOBALS['current_user']->id);
                        if ($bean->case_action_code_c!='followup') {
                            $templ_array = $this->getEmailTemplate($bean,'Case Closure Template Duplicate');
                            $sub1 = $templ_array['subject'];
                            $desc1 = $templ_array['body'];
                            $sms = $this->getSMS($bean->case_number,'Case Closure SMS');
                            if ($app_host != 'prod') {
                                // $this->sendEmail($sugar_config['not_prod_user_email'], $sub1, $desc1, null, array('technology@neogrowth.in'));
                                $send->send_email_to_user($sub1,$desc1, $sugar_config['not_prod_user_email'], null, $bean);
                                //$this->sendnetcore($sugar_config['not_prod_netcore_number'], $sms, $bean);
                            }
                            else{
                                $send->send_email_to_user($sub1,$desc1,array($bean->merchant_email_id_c), null, $bean, $sugar_config['helpdesk_email_arr']);
                                $this->sendnetcore($tag_name="Cust_CRM_41", $bean->merchant_contact_number_c, $sms, $bean);
                            }
                        }
                    }
                    else if($p2=='Resolved'){
                        $bean->date_resolved_c = $bean->date_modified;
                    }
                }
            }
        }
        $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$bean->id);
        $this->format_and_send_email_to_user($bean, $sub, $desc, $url, $to_mail_webcallback);
        $this->logger->log('debug', "edit_case end");
    }

    function format_and_send_email_to_user($bean, $sub, $desc, $url, $to_mail_webcallback) {
        $this->logger->log('debug', "in format_and_send_email_to_user for $bean->id");

        if ($desc != ""){
            require_once('custom/include/SendEmail.php');

            global $sugar_config; 
            $send = new SendEmail();
            $primary_email = $this->getEmailForUser($bean->assigned_user_id);

            $desc = "Hello ".$primary_email.",<br><br>Following Case changes have happened for Case #[".$bean->case_number."] ".$bean->name.":<br>".$desc;
            $desc .= "<br/>You may review this Case at:<br/><a href='".$url."'>".$url."</a>";
            $desc .= "<br/><br/><b>Disclaimer:</b><i>This is an auto generated email, please do not reply.
            All replies will automatically bounce. Kindly review the case and update your remarks in CRM update text box and assign it back to the user.</i><br/><br/>";
            $to = explode(";",$bean->email_to_c);
            foreach($to as $key=>$each_email){
                if (strpos($each_email, $sugar_config['neogrowth_in_domain']) === false) {
                    unset($to[$key]);
                }
                if (strcasecmp($each_email, $sugar_config['helpdesk_email'])== 0 ) {
                    unset($to[$key]);
                }
            }
            array_push($to, $primary_email);
            //for web call back immediate supervisor needs to be notified
            if(!empty($to_mail_webcallback)){
                array_push($to, $to_mail_webcallback);
            }
            $cc = explode(";",$bean->email_cc_c);
            foreach($cc as $key=>$each_email){
                if (strpos($each_email, $sugar_config['neogrowth_in_domain']) === false) {
                    unset($cc[$key]);
                }
                if (strcasecmp($each_email, $sugar_config['helpdesk_email']) == 0 ) {
                    unset($cc[$key]);
                }
            }
            $send->send_email_to_user($sub, $desc, $to, $cc);
            $this->logger->log('debug', "Description emailed is : $desc");
        }
    }
    
    function assignDateAction($bean, $event, $arguments){
        $this->logger->log('debug', "---In assignDateAction for $bean->id ");

        $current_date = TimeDate::getInstance()->nowDb();

        if(empty( $bean->date_attended_c) && $bean->state=='Open'){
            $bean->date_attended_c =$current_date;
        } 
        else if($bean->state == 'Closed'){
            $bean->date_attended_c = $bean->date_entered;
        } 
        else if($bean->state=='Resolved'){
            $bean->date_attended_c =  $bean->date_modified;
        }
        $this->logger->log('debug', "---End assignDateAction for $bean->id with state is '$bean->state' and date_attended_c is '$bean->date_attended_c'");
    }
    
   
    function getUserDepartment($userId){
        $department = "";

        global $db;
        if(!empty($userId)){
            $getDepartmentQuery = $db->query("SELECT department from users where id = '$userId'");
            while($data = $db->fetchByAssoc($getDepartmentQuery)){
                $department = $data['department'];
            }
        }
        return $department;
    }



    function checkInsertedFields($bean, $event, $arguments){
        $this->logger->log('debug', "---In checkInsertedFields for $bean->id ");
        if($_REQUEST['module'] != 'Import'){
            if($bean->is_call_back_c == 1){   
                // CallBackFlow.php. Merchant email is sent from there. 
                $this->logger->log('debug', "CallBackFlow");
                return; 
            }
            global $sugar_config;
            $c = new aCase;
            $c->retrieve($bean->id);

            if(empty($bean->fetched_row) && $bean->in_save){
                // $sub = "Support Case [SR-#$c->case_number] Created for App ID: $bean->merchant_app_id_c ($bean->merchant_name_c) - $bean->case_category_c";
                require_once('custom/include/SendEmail.php');
                $send = new SendEmail();
                $templ_array = $this->getEmailTemplate($c, 'Case Creation Template Duplicate');
                $desc = $templ_array['body'];
                $sub = $templ_array['subject'];
                if(empty($bean->merchant_app_id_c) || $bean->merchant_app_id_c=="N/A")
                {
                    $sub=$sub.": SR #$bean->case_number";
                }
                $sms = $this->getSMS($c->case_number,'Case Creation SMS');
                $app_host = getenv('SCRM_ENVIRONMENT');
                if(($bean->case_category_c == "financial_live") && ($bean->case_subcategory_c == "financial_live_tds_refund")){
                    $to = $sugar_config['tdsrefund_email']; 
                    $body = $this->getTDSRefundEmailContent($c);
                    $sub = "$bean->merchant_app_id_c - establishment name TDS refund request - [SR-#$c->case_number]";
                    $send->send_email_to_user(  $sub,
                                                $body,
                                                $to,
                                                null,
                                                $bean,
                                                $sugar_config['helpdesk_email_arr']
                                            );
                                            $this->logger->log('debug', "Sent mail with sub $sub to $to for $bean->id ");
                }

                if(!($c->case_source_c=="merchant" && $c->case_sub_source_c=='email') && empty($c->status)){
                    $send->send_email_to_user(  $sub,
                                                $desc,
                                                array($bean->merchant_email_id_c),
                                                null,
                                                $bean,
                                                $sugar_config['helpdesk_email_arr']
                                            );
                    $this->logger->log('debug', "Sent mail with sub $sub to $bean->merchant_email_id_c for $bean->id ");
                }
                
                if ($app_host == 'prod') {
                    if($bean->is_call_back_c != 1){
                        $this->logger->log('debug', "sent netcore sms in prod for $bean->id ");
                        $this->sendnetcore($tag_name="sendnetcore", $bean->merchant_contact_number_c, $sms, $bean);
                    }
                }
            }
        }
        $this->logger->log('debug', "---End checkInsertedFields for $bean->id ");
    }

    public function getTDSRefundEmailContent($bean){
        $caseNumber = $bean->case_number;
        $caseCreated = $bean->date_entered;
        $applicationId = $bean->merchant_app_id_c;
        $establishmentName =  $bean->merchant_establisment_c; 
        $financialYear = $bean->financial_year_c;
        $quarter_c = $bean->quarter_c;
        $digitallySigned = $bean->digitally_signed_c;
        $content = "Dear Team,</br></br>
        Below mentioned case has been assigned to you which is raised by customer via merchant portal.</br></br>
        Kindly review and post resolution assign case to customer service. </br></br>";
        $table = '<table style="width:80%;" border="1">
                    <tr>
                        <th>Case Number</th>
                        <th>Case Created</th> 
                        <th>App Id</th>
                        <th>Establishment Name</th>
                        <th>Financial Year</th> 
                        <th>Quarter_c</th>
                        <th>Digitally Signed</th>
                    </tr>
                    <tr>
                        <td>'.$caseNumber.'</td>
                        <td>'.$caseCreated.'</td>
                        <td>'.$applicationId.'</td>
                        <td>'.$establishmentName.'</td>
                        <td>'.$financialYear.'</td> 
                        <td>'.$quarter_c.'</td>
                        <td>'.$digitallySigned.'</td>
                    </tr>

                  </table></br>';
        $content = $content.$table;
        return $content;
        
    }

    private function getUserName($user_id){
        $myfile = fopen("Logs/data_sync.log","a");
        // fwrite($myfile,$user_id);
        $user = BeanFactory::getBean('Users',$user_id);
        // fwrite($myfile,"\n".$user->first_name." ".$user->last_name."\n");
        if($user)
            return $user->first_name." ".$user->last_name;
        return "";
    }
    private function getUser($user_id){
        $user = BeanFactory::getBean('Users',$user_id);
        return $user;
    }

    private function getEmailForUser($user_id){
        $user = $this->getUser($user_id);
        if($user){
            return $user->emailAddress->getPrimaryAddress($user);
        }
        return "";
    }



    //Return 1 on success, 0 on failure
    private function sendnetcore($tag_name,$to,$message,$bean){
        require_once('custom/include/SendSMS.php');    
        $sms = new SendSMS();
        $sms->send_sms_to_user($tag_name,$to, $message, $bean);

    }

    
    function getEmailTemplate($bean,$str){       
        global $current_user;
        $case_number = $bean->case_number;
        $merchant_name = $bean->merchant_name_c;
        $merchant_app_id = $bean->merchant_app_id_c;
        $case_category = $bean->case_category_c;
        $description = $bean->description;
        $update = $bean->update_text;
        $userName = $current_user->full_name;
        $displayCategoryValue = ($GLOBALS['app_list_strings']['case_category_c_list'][$bean->case_category_c]);
        $date = date("j-F-Y");
        require_once('modules/EmailTemplates/EmailTemplate.php');
        $template = new EmailTemplate();
        // $template->retrieve('1936dbcb-debc-795b-b876-5886e8915ebe');
        $template->retrieve_by_string_fields(array('name' => $str ));

        $body = $template->body_html;
        $email_subject = $template->subject;
        
        
        $body = str_replace('$merchant_name', $merchant_name, $body);
        $body = str_replace('$case_number', $case_number, $body);
        $body = str_replace('$date', $date, $body);
        $body = str_replace('$description', $description, $body);
        $body = str_replace('$update', $update, $body);
        if(!empty($userName)){
            $body = str_replace('$signatureName', $userName, $body);
        } else {
            $body = str_replace('$signatureName |', "", $body);
        }

        $email_subject = str_replace('$case_number', $case_number, $email_subject);
        if($merchant_app_id!='Unverified'){
            $email_subject = str_replace('$merchant_app_id', $merchant_app_id, $email_subject);
        }else{
            $email_subject = str_replace('$merchant_app_id', 'N/A', $email_subject);
        }
        
        $email_subject = str_replace('$case_category', $displayCategoryValue, $email_subject);
        $email_subject = str_replace('$merchant_name', $merchant_name, $email_subject);
        $arr = array('body'=>$body, 'subject' =>$email_subject);
        

        return $arr;


    }

    function getSMS($case_number, $str){
        $date = date("j-F-Y");
        // echo $str;
        require_once('modules/EmailTemplates/EmailTemplate.php');
        $template = new EmailTemplate();
        // $template->retrieve('1936dbcb-debc-795b-b876-5886e8915ebe');
        // $str='Case Creation SMS';
        $template->retrieve_by_string_fields(array('name' => $str ));
        $body = ($template->body);
        $body = str_replace('$case_number', $case_number, $body);
        $date = date("j-F-Y");
        $body = str_replace('$date', $date, $body);
        // echo $body;
        // $text = "Dear Customer, your query has been registered with NeoGrowth via Ref. No. SR-$case_number, we will get back to you shortly with resolution.";
        return $body;
    }

    function markAlertsAsRead($bean, $event, $arguments){
        $this->logger->log('debug', "---Start markAlertsAsRead for $bean->id ---");
        // for website call back cases, close the alert when the case is closed
        if(!empty($bean->id) && $bean->is_call_back_c == 1 && $bean->fetched_row['state'] != 'Closed' && $bean->state == 'Closed'){
            $alert_bean = BeanFactory::getBean('Alerts');
            $alert_bean_list = $alert_bean->get_full_list("", "alerts.url_redirect like '%$bean->id%'");
            foreach ($alert_bean_list as $instanceBean){
                $instanceBean->is_read = 1;
                $instanceBean->save();
            }
            $this->logger->log('debug', " closed alerts when the case is closed");
        }
        $this->logger->log('debug', "---End markAlertsAsRead---");
    }

    function getPreviousUser($bean, $event, $arguments){
        $this->logger->log('debug', "--- In getPreviousUser for $bean->id ---");
        $bean->stored_fetched_row_c = $bean->fetched_row;
    }

    function resolutioncheck($bean, $event, $arguments){
        $this->logger->log('debug', "--- In resolutioncheck for $bean->id ---");
        if($bean->state=="Closed"){
            if(empty(trim($bean->resolution))){
                $this->logger->log('debug', "Resolution comment not present while closing case. Hence, cannot be submitted.");
                echo json_encode("Resolution comment not present while closing case. Hence, cannot be submitted.");
				sugar_die("Resolution comment not present while closing case. Hence, cannot be submitted.");
            }
        }
        $this->logger->log('debug', "--- ENd resolutioncheck for $bean->id ---");
    }

    function classify($bean, $event, $arguments){
        $this->logger->log('debug', "--- In classify for $bean->id ---");
        global $db;
        if(empty($bean->id)){
            $mailid=$bean->merchant_email_id_c;
            $date = date('Y-m-d', strtotime('-3 day'));
            $q="select count(*) as count from emails e join emails_text et on e.id=et.email_id where from_addr like '%$mailid%' and date_entered>='$date'";
            $this->logger->log('debug', "Classify check Query: $q ");
            $result=$db->query($q);
            while (($row = $db->fetchByAssoc($result)) != null) {
                $count = $row['count'][0];
            }
            if ($count>0){
                $bean->classify_c=1;
                $bean->bot_comment_c="Skipped classification because customer already mail in last 3 days.";
                $this->logger->log('debug', "Skipped classification because customer already mail in last 3 days.");
            }
        }
        $this->logger->log('debug', "--- END classify for $bean->id with classify_c is '$bean->classify_c' ---");
    }

    function checkUnfundedTDScase($bean, $event, $arguments){
        $this->logger->log('debug', "--- In checkUnfundedTDScase for $bean->id ---");
        require_once('custom/include/CurlReq.php');
        $curl_req = new CurlReq();
        $appid=$bean->merchant_app_id_c;
        $url=getenv('SCRM_AS_API_BASE_URL')."/get_application_basic_details?ApplicationID=$appid";
        $response = $curl_req->curl_req($url);
		$json_response = json_decode($response, true);
        if(!empty($json_response) && count($json_response)>0){
            $status=$json_response[0]["Status"];
        }
        if ($bean->case_subcategory_c=="financial_live_tds_refund"){
            if($status=="active")
            {
                $this->logger->log('debug', "--- END 1 checkUnfundedTDScase $status for $bean->id ---");
                return;
            }
            else{
                if($_REQUEST['module'] == 'Import'){
                    $bean->deleted=1;
                    $this->logger->log('debug', "--- END 2 checkUnfundedTDScase ". $_REQUEST['module']." for $bean->id ---");
                    return;
                }
                //echo $url." --  ".$json_response."  --";
                echo json_encode($appid.' is unfunded app id- TDS refund cannot be raised for this App id');
				sugar_die($appid.' is unfunded app id- TDS refund cannot be raised for this App id');
            }
        }
        else{
            $this->logger->log('debug', "--- END 3 checkUnfundedTDScase  for $bean->id ---");
            return;
        }
    }

    /**
     * Ambit checker
     * From AS API is_bc_app_id == true is a AMBIT
     * 
     */
    function checkAmbit($bean, $event, $arguments){
        $this->logger->log('debug', "--- In checkAmbit for $bean->id ---");

        require_once('custom/include/CurlReq.php');

        $curl_req = new CurlReq();

        $appid=$bean->merchant_app_id_c;

        $url=getenv('SCRM_AS_API_BASE_URL')."/applications/is_bc_app_id?application_id=$appid";

        $response = $curl_req->curl_req($url);

		$json_response = json_decode($response, true);

        if(!empty($json_response) && count($json_response)>0){

            if($json_response["is_bc_app_id"] == 1){

                $bean->fi_business_c = 'yes';

                $bean->partner_name_c = $json_response["partner_name_c"];
            }
        } 
        else{
            $bean->fi_business_c = 'no';
        }

        $this->logger->log('debug', "--- END checkAmbit for $bean->id and fi_business_c [ $bean->fi_business_c ] ---");

        return;
    }

    function processorName($bean,$event, $arguments){

        $this->logger->log('debug', "--- In processorName for $bean->id ---");

        require_once('custom/include/CurlReq.php');

        $curl_req = new CurlReq();

        $appid=$bean->merchant_app_id_c;

        $url=getenv('SCRM_AS_API_BASE_URL')."/master_data/processor_name_c?application_id=$appid";
        
        $response = $curl_req->curl_req($url);

		$json_response = json_decode($response, true);

        $bean->processor_name_c = '';

        if(!empty($json_response) && count($json_response)>0){

            foreach($json_response as $value){
                $bean->processor_name_c .= $value['ProcessorName'].',';
            }

            if(!empty($bean->processor_name_c)){
                $bean->processor_name_c = rtrim($bean->processor_name_c, ", ");
            }
        } 
        $this->logger->log('debug', "--- End processorName for $bean->id and processor_name_c is '$bean->processor_name_c' ---");
        return;
    }

    function suspicioustrans($bean, $event, $arguments){
        global $timedate, $current_user;

        $this->logger->log('debug', "--- In suspicioustrans $bean->id ---");
        
        $this->logger_susp_mail = new CustomLogger('SuspiciousMail');
        $this->logger_susp_mail->log('info', "---In suspicioustrans $bean->id ---time - ".$timedate->now());

        if($bean->case_subcategory_c=="information_suspicious_transaction" and $bean->state!="Closed"){
            $user = BeanFactory::getBean('Users');
            $query = "users.deleted=0 and users.designation='Chief Financial Officer'";
            $old_user = $bean->stored_fetched_row_c['assigned_user_id'];
            $this->logger_susp_mail->log('info', "Query :: " . $query);
            $items = $user->get_full_list('',$query);
            $id=$items[0]->id;
            if(($bean->assigned_user_id == $id) && strcmp($old_user, $bean->assigned_user_id)!=0) {
                $to=$items[0]->email1;
                $sub= "Consent on Suspicious Transaction Case [SR-#$bean->case_number] App ID: $bean->merchant_app_id_c, $bean->merchant_establisment_c";
                $desc="<pre>
                    Hello,
                    $current_user->full_name has assigned case to you for Suspicious transaction consent.
                    Dear Sir,
                    This case is created under suspicious transaction category.
                    The customer has shared declaration form. Kindly refer attached declaration and confirm if this transaction to be tagged as Normal or Suspicious.
                    Just add your comment stating Suspicious or Normal Transaction in the comment box.</pre>";
                $receipt_date = date_format(date_create($bean->date_entered), 'd/m/Y h:i:s a');
                $desc .= "<pre><b>Case History:</b>
                    <table border='1' style='border-collapse: collapse;'>
                        <tr>
                            <td><b>Case Number</b></td>
                            <td colspan=2>$bean->case_number</td>
                            <td><b>Case Login Date</b></td>
                            <td colspan=2>$bean->date_entered</td>
                        </tr>
                        <tr>
                            <td><b>Issue Category (SubCategory)</b></td>
                            <td colspan=2>$bean->case_category_c- $bean->case_subcategory_c</td>
                            <td><b>Case Status</b></td>
                            <td colspan=2>$bean->state</td>
                        </tr></table>";
                $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$bean->id);
                $desc.= "<pre>You may review this Case at:
                    <a href='$url'>$url</a></pre>";
                $email = new SendEmail();
                $user = BeanFactory::getBean($bean->assigned_user_id);
                $email->send_email_to_user($sub,$desc,$to,array(),$bean);
            }
        }
        $this->logger->log('debug', "---End suspicioustrans $bean->id ---");
    }

    public function edit_count($bean,$event, $arguments){
        $this->logger->log('debug', "--- In edit_count $bean->id ---");
        if($_REQUEST['module'] != 'Import'){
            global $db,$current_user;
            $old_subcategory=$bean->stored_fetched_row_c['case_subcategory_c'];
            $old_category=$bean->stored_fetched_row_c['case_category_c'];

            $groupFocus = new ACLRole();
            $roles = $groupFocus->getUserRoles($current_user->id);

            if(($old_subcategory!=$bean->case_subcategory_c || $old_category!=$bean->case_category_c) && in_array('Customer support executive',$roles)){
                $count=$bean->category_count_c+1;
                $this->logger_cat->log('debug', "[ $bean->id ]Old subcat: $old_subcategory  current subcat:$bean->case_subcategory_c \n Old cat: $old_category  current cat:$bean->case_category_c");
                $query="update cases s join cases_cstm c on s.id=c.id_c set c.category_count_c=$count where id='$bean->id'";
                $db->query($query);
                $this->logger_cat = new CustomLogger('cases_category_update');
                $this->logger->log('debug', "Category count update query : $query");
            }
        }
        $this->logger->log('debug', "---End edit_count $bean->id ---");
    }


    public function tempCategoryStore($bean){
        $this->logger->log('debug', "--- In tempCategoryStore $bean->id ---");
        global $current_user, $db, $sugar_config;
      
        if($bean->case_category_c != $_REQUEST['case_category_c_new_c'] || $bean->case_subcategory_c != $_REQUEST['case_subcategory_c_new_c']){
            if(!empty($_REQUEST['case_category_c_new_c']) && !empty($_REQUEST['case_subcategory_c_new_c'])){
                #print_r($_REQUEST['case_category_c_new_c']);exit;
                $case_id = $_REQUEST['record'];
                $new_case_category = $_REQUEST['case_category_c_new_c'];
                $new_case_sub_category = $_REQUEST['case_subcategory_c_new_c'];
                $maker_remark = $_REQUEST['maker_comment_c'];
                $date = date('Y-m-d H:i:s');
                $query ="UPDATE cases s join cases_cstm c on s.id=c.id_c SET c.case_category_old_c='$bean->case_category_c',c.case_subcategory_old_c='$bean->case_subcategory_c',c.case_category_c_new_c= '$new_case_category', c.case_subcategory_c_new_c='$new_case_sub_category',c.case_category_approval_c = 0 ,c.date_of_request_c='$date',c.maker_id_c = '$current_user->id' where id = '$case_id'";
              
                $results = $db->query($query);
                $this->logger_cat = new CustomLogger('cases_category_update');
                $this->logger_cat->log('debug', "Category temp store query : $query");

                $user_name = $this->getUserName($current_user->id);

                $sub = 'Case category and subcategory updated';
            
                $to = $sugar_config['category_change_notification_emails'];

                $desc = "<pre>Dear Manisha/Yogesh,
                    $user_name has asked for your approval for the change in category/subcategory of the following case.
                    proposed new category=$new_case_category
                    proposed new subcategory=$new_case_sub_category
                    Maker Remark=$maker_remark
                    </pre>";
                
                $desc .= "<pre><b>Case History:</b>
                    <table border='1' style='border-collapse: collapse;'>
                        <tr>
                            <td><b>Case Number</b></td>
                            <td colspan=2>$bean->case_number</td>
                            <td><b>Case Login Date</b></td>
                            <td colspan=2>$bean->date_entered</td>
                        </tr>
                        <tr>
                            <td><b>Issue Category (SubCategory)</b></td>
                            <td colspan=2>$bean->case_category_c- $bean->case_subcategory_c</td>
                            <td><b>Case Status</b></td>
                            <td colspan=2>$bean->state</td>
                        </tr>
                    </table>";
                    $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$bean->id);
                
                    $desc.= "<pre>You may review this Case at: <a href='$url'>$url</a></pre>";

                #print_r($desc);exit; 
                $email = new SendEmail();
                $email->send_email_to_user( $sub,
                                            $desc,
                                            $to,
                                            array(),
                                            $bean);
                $this->logger->log('debug', "Category changed email sent for case_number '$bean->case_number'");
            }

        }
        $this->logger->log('debug', "--- End tempCategoryStore $bean->id ---");
    }
    public function caseTypeSave($bean,$event, $arguments){
        global $sugar_config;
        $details = $sugar_config['case_types'];
        $subcat = $bean->case_subcategory_c;
        
        $index=$this->getdetail($details,$subcat);
        $detail=$details[$index];
        $type=$detail['qrc'];
  
        if(!empty($type)){
            global $db;
            $query = "update cases set type='".$type."' where id='".$bean->id."'";
           
            $db->query($query);
        }
      
   }

    public function getdetail($a,$subcat) {
        foreach($a as $key => $i)
        {
            if(array_search($subcat,$i))
            {
                return $key;
            }
        }
    }


    public function caseOwnerSave($bean,$event, $arguments){

        global $db,$current_user;

	    $file=fopen('Logs/caseOwnerLog.log','a');

        $created_by_user = $bean->created_by;
        
        if(empty($this->checkCaseOwnerIsExist($bean,$event, $arguments)) &&
          !empty($case_owner = $this->CheckIsCsExecutive($bean,$event, $arguments))){

            $query = "update cases_cstm set attended_by_c='".$case_owner."' where id_c='".$bean->id."'";
            $db->query($query);

        } 
        
    }

    //Check if its executive
    public function CheckIsCsExecutive($bean,$event, $arguments){

        global $db;
        
        $this->loggerCaseOwner = new CustomLogger('caseOwnerLog');

        $created_by_user = $bean->created_by;
        
        $query='SELECT au.user_id as user_id FROM acl_roles ar LEFT JOIN acl_roles_users au ON ar.id=au.role_id WHERE ar.name = "Customer support executive Assignment Dynamic" and user_id= "'.$created_by_user.'" and au.deleted=0';

        $row = $db->fetchOne($query);
        
        if(!empty($row['user_id'])){

            return $this->getUserName($row['user_id']);

        } 
        else {
	
            if(!empty($bean->assigned_user_id)){

                return $this->getUserName($bean->assigned_user_id);

            }

        }
    }

    public function checkCaseOwnerIsExist($bean,$event, $arguments){
        
        global $db;

	    $this->loggerCaseOwner = new CustomLogger('caseOwnerLog');

        $query='SELECT attended_by_c from cases_cstm where id_c="'.$bean->id.'"';

	    $this->loggerCaseOwner->log('debug', "Inside check Case Owner Is Exist -  $query");

        $row = $db->fetchOne($query);

        if(empty($row['attended_by_c'])|| $row['attended_by_c']==1){
            return '';
        } else {
            return $row['attended_by_c'];
        }
    }
}
