<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
require_once('include/entryPoint.php');
require_once('custom/include/SendEmail.php');

$userBean = new User();
$emailObj = new Email();
$smAccount = new SMAcc_SM_Account();
global $db;
if ($_REQUEST) {
    $userId = $_REQUEST['user_id'];
    $applicationIds = $_REQUEST['id'];
    $appIdArray = explode(",", $applicationIds);
    if (!empty($userId)) {
        $userData = $userBean->retrieve($userId);

        if (!empty($userData->email1)) {
            $emailId = $userData->email1;
            if (!empty($appIdArray)) {
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
                  <th>Team</th>
                  <th>Branch</th> 
                  <th>Applicant Name</th> 
                  <th>Mobile</th> 
                  <th>Repayment Mode</th> 
                  <th>Repayment Frequency</th> 
                  <th>D-Variance</th> 
                  <th>Regularized</th> 
                  <th>D-DPD Dash - AS ON DATE</th>
                </tr>';
                foreach ($appIdArray as $instanceAppId) {
                    $smAccountData = $smAccount->retrieve($instanceAppId);
                    if (!empty($_REQUEST['team'])) {
                        $team = $_REQUEST['team'];
                    } else {
                        $team = $smAccountData->team;
                    }
                    $branch = strtoupper($smAccountData->branch);
                    $region = strtoupper($smAccountData->region);
                    $table .= "<tr>"
                            . "<td>" . $smAccountData->app_id . "</td>"
                            . "<td>" . $team . "</td>"
                            . "<td>" . $branch . "</td>"
                            . "<td>" . $smAccountData->merchant_name . "</td>"
                            . "<td>" . $smAccountData->contact . "</td>"
                            . "<td>" . $smAccountData->repayment_mode_c . "</td>"
                            . "<td>" . $smAccountData->repayment_frequency_c . "</td>"
                            . "<td>" . $smAccountData->d_varinace . "</td>"
                            . "<td>" . $smAccountData->regularised_c . "</td>"
                            . "<td>" . $smAccountData->current_dpd_dash_group . "</td></tr>";
                }
            }
            $table .= '</tbody></table>';

            $body = "Hi $userData->first_name, </br></br>"
                    . "You have been assigned new accounts. Please find the details below: </br></br>"
                    . "$table </br>"
                    . " Thanks, </br>CRM Technology Team";
            echo "</br>";

            //Send email to the service manager.
            $emailId = $userData->email1;
            $subject = "New accounts Assigned";
            $to = array($emailId);
            $email = new SendEmail();
            $email->send_email_to_user($subject, $body, $to);
        }
    }
}
?>
