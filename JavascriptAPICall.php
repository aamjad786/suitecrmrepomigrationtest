<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);

require_once('include/entryPoint.php');
require_once('custom/include/SendEmail.php');
require_once ('data/BeanFactory.php');
require_once ('data/SugarBean.php');
require_once('custom/modules/Cases/views/view.detail.php');
global $sugar_config,$db,$current_user,$app_list_strings;

$apiName = $_REQUEST['api'];

if ($apiName == 'getApplicationDataFromLMM') {

    $application_id = $_REQUEST['application_id'];

    $url = getenv('SCRM_LMM_URI') ."/api/v2/paylater_accounts/".$application_id;
    
    $response = curl_req($url);

    print_r($response);

} else if($apiName == 'getRegisteredEmail') {

    $case_id = $_REQUEST['case_id'];
    
    $bean = BeanFactory::newBean('Cases');

    $case=$bean->retrieve($case_id);

    $as_api_base_url = getenv('SCRM_AS_API_BASE_URL');

    $url = $as_api_base_url."/get_merchant_details?ApplicationID=".$case->merchant_app_id_c;
    
    $response = curl_req($url);

    if($response) {

        $json_response = json_decode($response, true);
      
        if(!empty($json_response) && count($json_response)>0) {

            $applicant_email_id = $json_response[0]['Applicant Email Id'];

            print_r($applicant_email_id);
        }

    } else {
        print_r("false");
    }

} else if ($apiName == 'SendVerificationEmail') {

    $emailId = $_REQUEST['email'];
    
    $application_id = $_REQUEST['application_id'];

    $url = getenv('SCRM_LMM_URI') .'/api/v2/paylater_open/paylater_accounts/'.$application_id.'/tokens/email_verification_link?email='.$emailId;

    $response = curl_req($url);

    $responseArray = json_decode($response);

    if ($responseArray->code == 'success') {

        $to = array($emailId);

        $body = getEmailValidationContent($application_id, $responseArray->reset_url);

        $email = new SendEmail();

        $cc = array();

        $subject = "Email Verification";

        $email->send_email_to_user($subject, $body, $to, $cc);

        print_r($response);
    }
} else if($apiName == 'deleteAttandance'){

    $q = 'DELETE FROM cases_agents_attendance WHERE id = "'.$_REQUEST['id'].'"';
   
    $r = $db->query($q);
    
    print_r($r);
} else if($apiName == 'approveCategory'){
    $bean = BeanFactory::newBean('Cases');
    $case=$bean->retrieve($_REQUEST['id']);
    $assigned_user = $case->assigned_user_id;
    $datetime=date("Y-m-d H:i:s");
    if(empty($_REQUEST['reject'])){
        $details = $sugar_config['case_types'];

        $oldCategory=$case->case_category_c;
        $oldSubCategory=$case->case_subcategory_c;
        $case->case_category_c=$case->case_category_c_new_c;
        $case->case_subcategory_c=$case->case_subcategory_c_new_c;

        $subcat = $case->case_subcategory_c;
        $index=getdetail($details,$subcat);
        $detail=$details[$index];
        $type=$detail['qrc'];
        $action_code=$detail['ftr'];

        $case->type=$app_list_strings['case_type_mapping'][$case->case_subcategory_c_new_c]['qrc'];
        $r =  $case->save();
        $timestamp=strtotime($datetime);
        $timestamp = $timestamp - (5*60*60+30*60);//subtract 5h 30min from current time;
        $timestamp = date("Y-m-d H:i:s", $timestamp);
        $auditid=create_guid();
        // Approved
        $update_case = 'UPDATE cases s join cases_cstm c on s.id=c.id_c SET c.case_category_approval_c =1, c.case_category_counts_c = case_category_counts_c + 1, s.assigned_user_id ="'.$assigned_user.'",deleted=0,c.checker_comment_c="'.$_REQUEST['checker_comments'].'" ,c.checker_c="'.$_REQUEST['user_id'].'", c.date_of_changes_c = "'.$datetime.'",s.type = "'.$type.'" WHERE id = "'.$_REQUEST['id'].'"';

        $audit_query1 ="insert into cases_audit values ('$auditid','$case->id','$timestamp','1','$case_subcategory_c','relate','$oldSubCategory','$case->case_subcategory_c',null,null)";

        $auditid=create_guid();

        $audit_query2 ="insert into cases_audit values ('$auditid','$case->id','$timestamp','1','$case_category_c','relate','$oldCategory','$case->case_category_c',null,null)";

        $s = $db->query($update_case);

        $res1=$db->query($audit_query1);

        $res2=$db->query($audit_query2);

        if(!empty($action_code)){
            $update_cases_cstm = "update cases_cstm set case_action_code_c = '".$action_code."' where id_c='$item->id'";

            $results = $db->query($update_cases_cstm);
        }

    } 
    
    if(!empty($_REQUEST['reject'])){
        // Rejected
        $update_case = 'UPDATE cases s join cases_cstm c on s.id=c.id_c SET c.case_category_approval_c =2,assigned_user_id ="'.$assigned_user.'",deleted=0,c.checker_comment_c="'.$_REQUEST['checker_comments'].'", c.date_of_changes_c = "'.$datetime.'",c.checker_c="'.$_REQUEST['user_id'].'" WHERE id = "'.$_REQUEST['id'].'"';

        $s = $db->query($update_case);
    }
    
    $to = getUserEmail($case->maker_id_c);
    
    $email = new SendEmail();

    $subject = "Approved your updated Category / Sub Category for case $case->case_number";
    
    $user_maker = getUserName($case->maker_id_c);

    $status= empty($_REQUEST['reject'])?'approved':'rejected';
    if ($status=='rejected')
    {
        $subject = "Rejected your Category / Sub Category change request for case $case->case_number";
    } 
   
    $desc = "<pre>Dear $user_maker,
            Your requested change in category/subcategory of the following case has been $status,
           
            </pre>";
                
                $desc .= "<pre><b>Case History:</b>
                            <table border='1' style='border-collapse: collapse;'>
                                <tr>
                                    <td><b>Case Number</b></td>
                                    <td colspan=2>$case->case_number</td>
                                    <td><b>Case Login Date</b></td>
                                    <td colspan=2>$case->date_entered</td>
                                </tr>
                                <tr>
                                    <td><b>Issue Category (SubCategory)</b></td>
                                    <td colspan=2>$case->case_category_c- $case->case_subcategory_c</td>
                                    <td><b>Case Status</b></td>
                                    <td colspan=2>$case->state</td>
                                </tr></table>";
                                $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$case->id);
                            
                                $desc.= "<pre>You may review this Case at:
        <a href='$url'>$url</a></pre>";

    $cc = array();

    $email->send_email_to_user($subject, $desc, $to, $cc);
    
    print_r($r=1);


} 
else if( $apiName == 'bulkapproveCategory'){
    //var_dump($_REQUEST['user_id']);exit;
    $details = $sugar_config['case_types'];
    $bean = BeanFactory::newBean('Cases');

    foreach($_REQUEST['category'] as $case_id){
        
        $case=$bean->retrieve($case_id);

        $assigned_user = $case->assigned_user_id;

        $datetime=date("Y-m-d H:i:s");

        $oldCategory=$case->case_category_c;

        $oldSubCategory=$case->case_subcategory_c;

        $case->case_category_c=$case->case_category_c_new_c;

        $case->case_subcategory_c=$case->case_subcategory_c_new_c;

        $case->type=$app_list_strings['case_type_mapping'][$case->case_subcategory_c_new_c]['qrc'];
        
        $r =  $case->save();

        $subcat         = $case->case_subcategory_c;
        $index          = getdetail($details,$subcat);
        $detail         = $details[$index];
        $type           = $detail['qrc'];
        $action_code    = $detail['ftr'];

        $timestamp=strtotime($datetime);

        $timestamp = $timestamp - (5*60*60+30*60);//subtract 5h 30min from current time;

        $timestamp = date("Y-m-d H:i:s", $timestamp);
        
        $auditid=create_guid();

        // Approved
        $update_case = 'UPDATE cases s join cases_cstm c on s.id=c.id_c SET c.case_category_approval_c =1, c.case_category_counts_c = c.case_category_counts_c + 1, assigned_user_id ="'.$assigned_user.'",deleted=0,c.checker_comment_c="'.$_REQUEST['checker_comments'].'" ,c.checker_c="'.$_REQUEST['user_id'].'", c.date_of_changes_c = "'.$datetime.'",type = "'.$type.'" WHERE id = "'.$case_id.'"';

        $s = $db->query($update_case);

        $audit_query1 ="insert into cases_audit values ('$auditid','$case->id','$timestamp','1','$case_subcategory_c','relate','$oldSubCategory','$case->case_subcategory_c',null,null)";
        
        $res1=$db->query($audit_query1);

        $auditid=create_guid();

        $audit_query2 ="insert into cases_audit values ('$auditid','$case->id','$timestamp','1','$case_category_c','relate','$oldCategory','$case->case_category_c',null,null)";

        $res2=$db->query($audit_query2);

        $to = getUserEmail($case->maker_id_c);
        
        $email = new SendEmail();

        $subject = "Approved your updated Category / Sub Category for case $case->case_number";
        
        $user_maker = getUserName($case->maker_id_c);

        if(!empty($action_code)){
            $update_cases_cstm = "update cases_cstm set case_action_code_c = '".$action_code."' where id_c='$item->id'";
    
            $results = $db->query($update_cases_cstm);
        }

        $status= empty($_REQUEST['reject'])?'approved':'rejected';

        if ($status=='rejected') {
            $subject = "Rejected your Category / Sub Category change request for case $case->case_number";
        } 
    
        $desc = "<pre>Dear $user_maker,
            Your requested change in category/subcategory of the following case has been $status,
           
            </pre>";
                
        $desc .= "<pre><b>Case History:</b>
            <table border='1' style='border-collapse: collapse;'>
                <tr>
                    <td><b>Case Number</b></td>
                    <td colspan=2>$case->case_number</td>
                    <td><b>Case Login Date</b></td>
                    <td colspan=2>$case->date_entered</td>
                </tr>
                <tr>
                    <td><b>Issue Category (SubCategory)</b></td>
                    <td colspan=2>$case->case_category_c- $case->case_subcategory_c</td>
                    <td><b>Case Status</b></td>
                    <td colspan=2>$case->state</td>
                </tr></table>";
        $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$case->id);
    
        $desc.= "<pre>You may review this Case at:
        <a href='$url'>$url</a></pre>";

        $cc = array();

        $email->send_email_to_user($subject, $desc, $to, $cc);
        

    }

    print_r($r=1);
  
} else if($apiName == 'bulkRejectCategory') {
    // var_dump($_REQUEST['category']);exit;
    foreach($_REQUEST['category'] as $case_id){
        
        $bean = BeanFactory::newBean('Cases');
    
        $case=$bean->retrieve($case_id);
    
        $assigned_user = $case->assigned_user_id;
    
        $datetime=date("Y-m-d H:i:s");
    
        $update_case = 'UPDATE cases s join cases_cstm c on s.id=c.id_c SET c.case_category_approval_c =2,assigned_user_id ="'.$assigned_user.'",deleted=0,c.checker_comment_c="'.$_REQUEST['checker_comments'].'", c.checker_c="'.$_REQUEST['user_id'].'",c.date_of_changes_c = "'.$datetime.'"  WHERE id = "'.$case_id.'"';

        $s = $db->query($update_case);
    
        $to = getUserEmail($case->maker_id_c);
        
        $email = new SendEmail();
    
        $subject = "Approved your updated Category / Sub Category for case $case->case_number";
        
        $user_maker = getUserName($case->maker_id_c);
    
        $status= 'rejected';
    
        if ($status=='rejected')
        {
            $subject = "Rejected your Category / Sub Category change request for case $case->case_number";
        } 
    
        $desc = "<pre>Dear $user_maker,
                Your requested change in category/subcategory of the following case has been $status,
            
                </pre>";
                    
                    $desc .= "<pre><b>Case History:</b>
                                <table border='1' style='border-collapse: collapse;'>
                                    <tr>
                                        <td><b>Case Number</b></td>
                                        <td colspan=2>$case->case_number</td>
                                        <td><b>Case Login Date</b></td>
                                        <td colspan=2>$case->date_entered</td>
                                    </tr>
                                    <tr>
                                        <td><b>Issue Category (SubCategory)</b></td>
                                        <td colspan=2>$case->case_category_c- $case->case_subcategory_c</td>
                                        <td><b>Case Status</b></td>
                                        <td colspan=2>$case->state</td>
                                    </tr></table>";
                                    $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=DetailView&record=".$case->id);
                                
                                    $desc.= "<pre>You may review this Case at:
            <a href='$url'>$url</a></pre>";
    
        $cc = array();
    
        $email->send_email_to_user($subject, $desc, $to, $cc);
    
    }
    
    print_r($r=1);
}
function getEmailValidationContent($applicationId, $link) {

    $emailContent = "Dear Customer,</br>
    Welcome to NeoGrowth! Your account number is $applicationId with the validity of 22 months.</br>
    Please verify your email address for further communication.</br>
    <a href = '$link'> $link </a></br></br>
    Thanks,</br>
    NeoGrowth Team </br>";

    return $emailContent;
}

function getUserEmail($user_id){
  
    $bean=new User();

    $user=$bean->retrieve($user_id);
   
    $email = $user->email1;
    return $email;
}

function getdetail($a,$subcat) {
    foreach($a as $key => $i) {
        if(array_search($subcat,$i)) {
            return $key;
        }
    }
}

function getUserName($user_id){
    $bean=new User();
    
    $user= $bean->retrieve($user_id);
  
    $name = $user->name;
    
    return $name;
}

function curl_req($url) {

    $bearerPassword = getenv('LMS_BEARER_PASSWORD');

    $header = array(
        "authorization: Bearer $bearerPassword",
        'content-type' => 'application/json'
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

?>