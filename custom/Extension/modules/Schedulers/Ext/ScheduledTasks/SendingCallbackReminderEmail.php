<?php

$job_strings[] = 'SendingCallbackReminderEmail';
date_default_timezone_set('Asia/Kolkata');

function SendingCallbackReminderEmail() {

    if (!defined('sugarEntry'))
        define('sugarEntry', true);
    require_once('include/entryPoint.php');
    require_once('SendEmail.php');

    $userBean = new User();
    $emailObj = new Email();
    global $db;

    $todaysData = date("Y-m-d");
    $queryToGetDistinct = "SELECT assigned_user_id FROM smacc_sm_account WHERE callback_date BETWEEN '$todaysData 00:00:00' AND '$todaysData 23:59:59' AND deleted NOT LIKE '1' GROUP BY assigned_user_id";
    $distinctUserResult = $db->query($queryToGetDistinct);
    while ($row = $db->fetchByAssoc($distinctUserResult)) {
        if (!empty($row['assigned_user_id'])) {
            $userId = $row['assigned_user_id'];
            $userData = $userBean->retrieve($userId);
            if (!empty($userData)) {
                $queryToGetUserAccountData = "SELECT * from smacc_sm_account where assigned_user_id = '$userId' AND deleted NOT LIKE '1' AND callback_date BETWEEN '$todaysData 00:00:00' AND '$todaysData 23:59:59'";
                $AccountDetailsResult = $db->query($queryToGetUserAccountData);
                $table = '<style>
                    table, th, td {
                        border: 1px solid black;
                        border-collapse: collapse;
                    }
                    th, td {
                        padding: 10px;
                    }
                    </style>
                <table style="width:100%; text-align:center">
                <tbody>
                <tr>
                  <th>App Id</th>
                  <th>Merchant Name</th> 
                  <th>Contact Number</th>
                  <th>Current DPD group</th>
                </tr>';
                if (!empty($AccountDetailsResult)) {
                    while ($row = $db->fetchByAssoc($AccountDetailsResult)) {
                        $table .= "<tr><td>" . $row['app_id'] . "</td>"
                                . "<td>" . $row['merchant_name'] . "</td>"
                                . "<td>" . $row['contact'] . "</td>"
                                . "<td>" . $row['current_dpd_dash_group'] . "</td></tr>";
                    }
                }
                $table .= '</tbody></table>';
                $body = "Hi $userData->first_name, </br></br>"
                        . "The following is the list of merchant that need to be called back today as per reminders set by you in the CRM: </br></br>"
                        . "$table </br>Be sure to note your comments in the comments section in the CRM. </br></br>"
                        . " Thanks, </br>CRM Technology Team";
                echo "</br>";

                //Send email to the service manager.
                $emailId = $userData->email1;
                $emailId = "gowthami.gk@neogrowth.in";
                $subject = "Call Back alert";
                $to = array($emailId);
                $email = new SendEmail();
                $email->send_email_to_user($subject, $body, $to);
            }
        }
    }
}

?>