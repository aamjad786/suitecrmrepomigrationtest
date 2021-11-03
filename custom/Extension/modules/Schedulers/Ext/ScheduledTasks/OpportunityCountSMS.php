<?php 
require_once('include/entryPoint.php');
require_once('data/BeanFactory.php');
require_once('custom/include/SendEmail.php');

date_default_timezone_set('Asia/Kolkata');

$job_strings[] = 'OpportunityCountSMS';

function OpportunityCountSMS(){

    $logger = new CustomLogger('OpportunityCountSMS');
    $logger->log('debug', '<=============== OpportunityCountSMS Started ====================>');
    
    global $db;
    $date=date('Y-m-d');
    $email = new SendEmail();
    
    $bean = BeanFactory::getBean('Users');
    $query = "users.deleted=0 and (users_cstm.designation_c like '%Customer Acquisition%' or users_cstm.designation_c like '%Cluster Manager%') and users.status='Active'";
   
    
    $items = $bean->get_full_list('',$query);
    if($items) {

        foreach($items as $item) {
            
            $q="select count(*) as count from (select distinct(pickup_appointment_contact_c) from opportunities o join opportunities_cstm c on o.id=c.id_c  where assigned_user_id='$item->id' and  o.deleted=0 and date_entered>'$date') a";
            $result=$db->query($q);
            $row = $db->fetchByAssoc($result);
            $count=$row['count'][0];
            $user = BeanFactory::getBean('Users',$item->id);
            $to=array($user->email1);
            $cc=array();
            $name=$item->name;
            $subject = "Opportunity assignment for $date (Do not reply)";

            if ($count > 0) {

                $body = "<pre>Hi $name,
    You have been assigned $count Opportunities today.
    Please log into the CRM/Sales App to view them.
    Regards,
    Team NeoGrowth";

            } else {

                $body = "<pre>Hi $name,
    No new Opportunities have been assigned to you today up till now.
    Thanks,
    Team NeoGrowth";

            }
            $logger->log('debug', 'Email Sending Details===> ');
            $logger->log('debug', '$to:  '.$to);
            $logger->log('debug', '$name:  '.$name);
            $logger->log('debug', '$subject:  '.$subject);
            $logger->log('debug', '$body:  '.$body);
            
            $email->send_email_to_user($subject, $body, $to, $cc);
        }
    }
    echo "at the end";
    return true;
}

// OpportunityCountSMS();