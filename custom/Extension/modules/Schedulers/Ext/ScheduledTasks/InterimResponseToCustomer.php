<?php

$job_strings[] = 'InterimResponseToCustomer';
date_default_timezone_set('Asia/Kolkata');

function InterimResponseToCustomer() {
    if (!defined('sugarEntry'))
        define('sugarEntry', true);
    require_once('include/entryPoint.php');
    require_once('custom/include/SendEmail.php');
    // $currentDate = date("Y-m-d");
    $env = getenv('SCRM_ENVIRONMENT');

    global $db, $sugar_config;
    $getCasesQuery = "SELECT cases.id, cases.date_entered, cases.case_number, cases.state, cases_cstm.merchant_email_id_c, cases_cstm.escalation_level_c from cases JOIN cases_cstm ON cases.id = cases_cstm.id_c WHERE state !='Closed'";
    $cases = $db->query($getCasesQuery);

    while ($row = $db->fetchByAssoc($cases)) {
        $customerEmail = $row['merchant_email_id_c'];
        $caseCreationDate = $row['date_entered'];
        $escalationLevel = $row['escalation_level_c'];

        print_r($customerEmail);
        echo "</br>";
        if (!empty($escalationLevel) && ($escalationLevel == 2)) {
            $ticket = $row['case_number'];
            $body = getEmailContent($ticket);
            if(in_array($env,array('dev','local')))
                $emailId = $sugar_config['ng_gowthami_gk'];
            else if($env == 'prod')
                $emailId = $customerEmail;
            $subject = "Update on your query SR#$ticket";
            $to = array($emailId);
            $email = new SendEmail();
            $email->send_email_to_user($subject, $body, $to);
        }
    }

    return true;
}

function getEmailContent($ticket) {

    $body = "<pre>
    Dear NeoGrowth Customer, </br></br>"
    ."Greetings from NeoGrowth! Our records indicate that a decision on your query [SR-#$ticket] is still pending."
    ."Please be rest assured that we are following-up on this with the respective department and will certainly contact you once a resolution is received. </br></br>"
    ."We appreciate your patience and apologise for any inconvenience.</br></br>"
    ."For any further assistance, please do not hesitate to email us on helpdesk@neogrowth.in or call us on 1800-419-5565 between 10 A.M - 6 P.M from Monday to Saturday.</br></br>"
    ."We are always here to assist you.</br></br>"
    ."Thank you for choosing NeoGrowth.</br></br>
    </pre>";

    return $body;
}

?>