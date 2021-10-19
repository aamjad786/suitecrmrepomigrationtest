<?php
$job_strings[] = 'CasesEscalationMail';
date_default_timezone_set('Asia/Kolkata');
require_once('include/entryPoint.php');

function CasesEscalationMail(){
    global $sugar_config;
    $logger = new CustomLogger('CasesEscalationMail');
    $logger->log('debug', "---Start function::CasesEscalationMail() at - ".date('Y-m-d H:i:s')."---");
    
    $bean = BeanFactory::getBean('Cases');
    $query = "cases.deleted=0 and cases.state in ('Open','In_progress')";
    $logger->log('debug', "Query :: " . $query);
    $items = $bean->get_full_list('',$query);
    
    if ($items){
        foreach($items as $item){
            if ($item->escalation_level_c>=1){
                $level = $item->escalation_level_c;
                $user = getUser($item->assigned_user_id);
                $cc=array();
                //print_r($item);
                $cc = getCCListFromTable($item,$user);
                if ($item->case_subcategory_c=="information_suspicious_transaction") {
                    $cc[0][3] = $sugar_config['ng_sachin_bawari'];
                    $cc[0][4] = $sugar_config['ng_ravi_sarpangala'];
                    $cc[0][5] = $sugar_config['ng_ravi_kumar'];
                    $cc[0][6] = $sugar_config['ng_yogesh_nakhwa'];
                    
                    $cc[1][3] = $sugar_config['ng_sachin_bawari_name'];
                    $cc[1][4] = $sugar_config['ng_ravi_sarpangala_name'];
                    $cc[1][5] = $sugar_config['ng_ravi_kumar_name'];
                    $cc[1][6] = $sugar_config['ng_yogesh_nakhwa_name'];

                    $department=strtolower($item->assigned_user_department_c);
                    
                    if(preg_match('/collection/i', $department)){
                        $cc[0][2] = $sugar_config['ng_sorabh_malhotra'];
                        $cc[1][2] = $sugar_config['ng_sorabh_malhotra_name'];
                    }
                }

                $logger->log('debug', print_r($cc,true));

                // old logic ends
                if($item->age_c>30){
                    $cc[0][7]=$cc[0][4];
                    $cc[1][7]=$cc[1][4];
                    $cc[0][4] = $sugar_config['ng_arun_nayyar'];
                    $cc[1][4] = $sugar_config['ng_arun_nayyar_name'];
                }else{
                    if(($key = array_search($sugar_config['ng_arun_nayyar'], $cc[0])) !== false) {
                        unset($cc[0][$key]);

                        if($key==$level){
                            $level--;
                        }
                    }
                    if(($key = array_search($sugar_config['ng_arun_nayyar_name'], $cc[1])) !== false) {
                        unset($cc[1][$key]);
                    }
                }
                //Escalation mails are not supposed to sent to PK - start
                if(($key = array_search($sugar_config['ng_piyush_khaitan_khaitan'], $cc[0])) !== false) {
                    unset($cc[0][$key]);

                    if($key==$level){
                        $level--;
                    }
                }
                if(($key = array_search($sugar_config['ng_piyush_khaitan_neogrowth'], $cc[0])) !== false) {
                    unset($cc[0][$key]);

                    if($key==$level){
                        $level--;
                    }
                }                    
                if(($key = array_search($sugar_config['ng_piyush_khaitan_name'], $cc[1])) !== false) {
                    unset($cc[1][$key]);
                }
                if(($key = array_search($sugar_config['ng_piyush_khaitan_name'], $cc[1])) !== false) {
                    unset($cc[1][$key]);
                }
                //Escalation mails are not supposed to sent to PK - end
                // echo "cc got is:<br>";
                // var_dump($cc);
                $ccemails = $cc[0];
                $ccnames = $cc[1];
                $email_count = count($ccemails);
                // echo "count is ".$email_count;
                // echo "old level is ".(int)$level;
                if($email_count>0 && (int)$level>=$email_count){
                    $level = $email_count-1;
                }
                // echo "new level is ".$level;
                $to = array($ccemails[$level]);
                $to_name = $ccnames[$level];
                unset($ccemails[$level]);
                unset($ccnames[$level]);
                $cc = $ccemails;

                require_once('custom/include/SendEmail.php');
                $sub = "STR Alert: Case [SR-#$item->case_number] Aged $item->age_c days Escalated for App ID:  $item->merchant_app_id_c ($item->merchant_establisment_c) - $item->case_category_c";
                $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$item->id);

                if ($item->is_AuditEnabled()) {
                    $order = ' order by ' . $item->get_audit_table_name() . '.date_created';
                    $query = "SELECT " . $item->get_audit_table_name() . ".* FROM " . $item->get_audit_table_name() . " WHERE " . $item->get_audit_table_name() . ".parent_id = '$item->id'" . $order;
                    global $db;
                    $result = $item->db->query($query);
                    $results = array();
                    while (($row = $item->db->fetchByAssoc($result)) != null) {
                        $results[] = $row;
                    }
                    $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$item->id);
                    $user = getUser($item->assigned_user_id);
                    $case_category = $GLOBALS['app_list_strings']['case_category_c_list'][$item->case_category_c];
                    $case_subcategory = $GLOBALS['app_list_strings']['case_subcategory_c_list'][$item->case_subcategory_c];
                    $case_status = $GLOBALS['app_list_strings']['case_state_dom'][$item->state];
                    $query_comments = "
                        select (select count(*) from aop_case_updates 
                        where case_id ='$item->id' and deleted = 0 and is_user_comment_c = 1) as 'total_comments',
                        name, description 
                        from aop_case_updates 
                        where case_id ='$item->id' 
                        and deleted = 0 
                        and is_user_comment_c = 1
                        group by id
                        order by date_modified desc 
                        limit 1";
                    $logger->log('debug', "Query :: ". $query_comments);
                    $results_comments = $db->query($query_comments);
                    $total_comments = 0;
                    $latest_comment = "N/A";
                    while ($row_comment = $db->fetchByAssoc($results_comments)) {
                        if(isset($row_comment['total_comments']) && !empty($row_comment['total_comments'])){
                            $total_comments = $row_comment['total_comments'];
                        }
                        if(isset($row_comment['description']) && !empty($row_comment['description'])){
                            $latest_comment = $row_comment['description'];
                        }
                    }
                    $logger->log('debug', "Latest Comment Length:: ". strlen($latest_comment));
                    $read_more = "";
                    if(strlen($latest_comment)>200){
                        $latest_comment = substr($latest_comment, 0, 200);
                        $read_more = "<a href='$url'>...ReadMore</a>"; 
                    }
                    $logger->log('debug', "Total Comment made on this case :: ". $total_comments);
                    $logger->log('debug', "Latest Comment :: ". $latest_comment);
                    
                    $desc ="<pre>
                        Dear $to_name,

                        This is a system generated auto escalation email. This is to bring to your notice 
                        that Case No: SR-#$item->case_number is pending for resolution with $user->department since $item->age_c days.
                        Request your attention to help resolve this escalation as soon as possible.";

                    $msg = $results;
                    $receipt_date = date_format(date_create($item->date_entered), 'd/m/Y h:i:s a');
                    $desc .= "<pre><b>Case History:</b>
                    <table border='1' style='border-collapse: collapse;'>
                        <tr>
                            <td><b>Case Number</b></td>
                            <td colspan=2>$item->case_number</td>
                            <td><b>Case Login Date</b></td>
                            <td colspan=2>$receipt_date</td>
                        </tr>
                        <tr>
                            <td><b>Issue Category (SubCategory)</b></td>
                            <td colspan=2>$case_category - $case_subcategory</td>
                            <td><b>Case Status</b></td>
                            <td colspan=2>$case_status</td>
                            <td><b>Total Comments</b></td>
                            <td colspan=1>$total_comments</td>
                        </tr>
                        <tr>
                            <td><b>Latest Comment</b></td>
                            <td colspan=7>$latest_comment</pre>$read_more<pre></td>
                        </tr>
                        <tr>
                            <td><b>Functional SPOC</b></td>
                            <td><b>Function</b></td>
                            <td><b>Case Receipt Date and Time</b></td>
                            <td><b>Case Action Date and Time</b></td>
                            <td><b>Time Spent</b></td>
                            <td><b>Action Status</b></td>
                            <td><b>Reassigned to</b></td>
                        </tr>";
                    foreach ($results as $r) {
                        if ($r['field_name'] == 'assigned_user_id') {
                            $before_user = getUserDetails($r['before_value_string']);
                            $after_user = getUserDetails($r['after_value_string']);
                            $changed_by = getUserDetails($r['created_by']);
                            $action_status = '';
                            if ($receipt_date == date_format(date_create($item->date_entered), 'd/m/Y h:i:s a')) {
                                $action_status = 'Allocated';
                            }else{
                                $action_status = 'Reassigned';
                            }
                            $action_date = date_format(date_create($r['date_created']), 'd/m/Y h:i:s a');
                            if(!empty($receipt_date) && !empty($action_date) && $action_status=="Allocated"){
                                $days_spent = getDaysSpent($item->date_entered,$r['date_created']);
                            }else if(!empty($receipt_date) && !empty($action_date) && $action_status=="Reassigned"){
                                $days_spent = getDaysSpent($rd,$r['date_created']);
                            }else{
                                $days_spent = "0";
                            }
                            $desc .= "<tr>";
                            $desc .= "<td>$before_user[0]</td>";
                            $desc .= "<td>$before_user[1]</td>";
                            $desc .= "<td>$receipt_date</td>";
                            $desc .= "<td>$action_date</td>";
                            $desc .= "<td>$days_spent</td>";
                            $desc .= "<td>$action_status</td>";
                            $desc .= "<td>$after_user[0]</td>";
                            $desc .= "</tr>";
                            $receipt_date = date_format(date_create($r['date_created']), 'd/m/Y h:i:s a');
                            $rd = $r['date_created'];
                        }
                    }
                    $desc.= "</table></pre>";
                    $desc.= "<pre>You may review this Case at:
                        <a href='$url'>$url</a></pre>";
                }

                $email = new SendEmail();

                $app_host = getenv('SCRM_ENVIRONMENT');
                $caseLevel = $item->escalation_level_c;
                $logger->log('debug', print_r($to,true));
                $logger->log('debug', print_r($cc,true));
                echo "Case id: ".$item->id."<br>";
                echo "Case Subcategory: ".$item->case_subcategory_c."<br>";
                echo "Assigned to: ".$item->assigned_user_name."<br>";
                echo "Escalation Level: ".$item->escalation_level_c."<br>";
                echo "Email will be sent to: " ; print_r($to);echo "<br>";
                print_r($cc);
                echo "<br><br>";
                $email->send_email_to_user($sub,$desc,$to, $cc,$item);
                $logger->log('debug', "------------------function::CasesEscalationMail() Ends--------------");
            }

        }
    }
    return true;
}


function getCCListFromTable($case,$user){
    $logger = new CustomLogger('CasesEscalationMail');
    $logger->log('debug', "------------------function::getCCListFromTable() Starts--------------");
    $emails = array();
    $names  = array();
    $level  = $case->escalation_level_c;
    // print_r($level);echo "<br>";
    global $db;
    $query = "
        SELECT id, assigned_user, esc_1_user, esc_2_user, esc_3_user 
        FROM user_case_escalation
        WHERE id = '$user->id'
        ";
    $logger->log('debug', "query :: $query");
    $results = $db->query($query);
    $esc_details = array();
    while ($row = $db->fetchByAssoc($results)) {
        array_push($esc_details, $row['assigned_user']);
        array_push($esc_details, $row['esc_1_user']);
        array_push($esc_details, $row['esc_2_user']);
        array_push($esc_details, $row['esc_3_user']);
        break;
    }

    if($level == 4){
        //PK'S DETAILS WILL BE ADDED IN THE PARENT FUNCTION
        $level = $level-1;
    }
    $logger->log('debug', "Escalation Level :: " . $level);
    $logger->log('debug', "Escalation Matrix :: " . serialize($esc_details) . " For $user->user_name");
    for($i=0;$i<=$level;$i++){
        $user_bean = "";
        $name = "";
        if(isset($esc_details[$i]) && !empty($esc_details[$i])){
            $user_bean  = getUserByNgid($esc_details[$i]);
            $name       = $user_bean->first_name . ' ' . $user_bean->last_name;
            if(!empty($user_bean->email1) && !empty($name)){
                $emails[]   = $user_bean->email1;   
                $names[]    = $name;
            }
        }        
    }
    $logger->log('debug', "names: " .serialize($names) . ", emails: ". serialize($emails));
    $logger->log('debug', "------------------function::getCCListFromTable() Ends--------------");
    return array($emails, $names);
}


function getUserID($ngid){
    global $db;
    $query  = "SELECT id FROM users WHERE user_name = '$ngid' ";
    $results = $db->query($query);
    $id = "";
    // print_r($results);
    while($row = $db->fetchByAssoc($results)){
        $id = $row['id'];
    }
    return $id;
}

function getUserByNgid($ngid){
    $user_id = getUserID($ngid);
    $user = getUser($user_id);
    if($user)
        return $user;
    else
        return null;
}

function getUser($user_id){
    $user = BeanFactory::getBean('Users',$user_id);
    return $user;
}

function getUserDetails($user_id){
    $user = BeanFactory::getBean('Users',$user_id);
    if(!empty($user->id)){
        $user_details[] = $user->first_name." ".$user->last_name;
        $user_details[] = $user->department;
        return $user_details;
    }
    return "";
}
function getDaysSpent($dt1, $dt2){
    $start = new Datetime($dt1);
    $end = new Datetime($dt2);
    if ($start > $end) {
        return "0";
    }
    $interval = $end->diff($start);
    $days = $interval->format("%d");
    $holidays = array();
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);
    foreach($period as $dt) {
        $curr = $dt->format('D');
        if ($curr == 'Sat' || $curr == 'Sun') {
            $days--;
        }
        elseif (in_array($dt->format('Y-m-d'), $holidays)) {
            $days--;
        }
    }
    return $days." days ".$interval->format("%h hours %i minutes");
}
?>