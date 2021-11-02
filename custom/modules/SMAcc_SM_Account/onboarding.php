<?php 
if (!defined('sugarEntry'))
define('sugarEntry', true);
require_once('include/entryPoint.php');
require_once('include/SugarPHPMailer.php');
require_once('custom/include/SendEmail.php');
?>
<style>
    .middle {
        background-color : #ffc300;
        text-align: center;
        font-weight: bold;
    }
    .bottom {
        background-color : #47d147;
        text-align: center;
        font-weight: bold;
    }
    .top {
        color: red;
        text-align: center;
        font-weight: bold;
    }
    .total {
        text-align: center;
        font-weight: bold;
        font-size: 12px;
    }
    .border {
        background-color : #DCDCDC;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
    }
</style>
<?php
$onBoardingMetrix = array();
$earlyMetrix = array();
global $db;
//$row_values = array();
$row_col_values = array( '0','1-4','5-6','7-10','11 and above','Grand Total');

$onboardingData = array('0' => array( '0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        '1-4' =>array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        '5-6' =>array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        '7-10'=>array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        '11 and above' =>array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        'Grand Total' =>array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                    );
$earlyBucketData = array('0' => array( '0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        '1-4' => array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        '5-6' =>array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        '7-10' =>array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        '11 and above' =>array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                        'Grand Total' => array('0' => 0,'1-4' => 0,'5-6' => 0,'7-10' => 0,'11 and above' => 0,'Grand Total' => 0),
                    );
$query = 'select COUNT(id) as count, opening_dpd_dash_group as opening_dpd_dash_group_new, current_dpd_dash_group as current_dpd_dash_group_new
from smacc_sm_account where deleted = 0 AND team = "onboarding_bucket"  GROUP BY opening_dpd_dash_group_new, current_dpd_dash_group';


$earlyBucketGroupQuery = 'select COUNT(id) as count, opening_dpd_dash_group as opening_dpd_dash_group_new, current_dpd_dash_group as current_dpd_dash_group_new
from smacc_sm_account where deleted = 0 AND team = "early_bucket" GROUP BY opening_dpd_dash_group_new, current_dpd_dash_group';
    
//Put where condition
$result = $db->query($query);
    
$firstRowSum = 0; $secondRowSum = 0; $thirdRowSum = 0; $fourthRowSum = 0; $fifthRowSum = 0;
while ($row = $db->fetchByAssoc($result)) {
    $onBoardingMetrix[$row['opening_dpd_dash_group_new']][$row['current_dpd_dash_group_new']]['count'] = $row['count'];
    if (!empty($row['count']) && $row['count'] != 0) {
        $onboardingData[$row['opening_dpd_dash_group_new']][$row['current_dpd_dash_group_new']] = $row['count'];
    }
    
    if ($row['opening_dpd_dash_group_new'] == 0) {
        $firstRowSum += $row['count'];
    } else if ($row['opening_dpd_dash_group_new'] == '1-4') {
        $secondRowSum += $row['count'];
    } else if ($row['opening_dpd_dash_group_new'] == '5-6') {
        $thirdRowSum += $row['count'];
    } else if ($row['opening_dpd_dash_group_new'] == '7-10') {
        $fourthRowSum += $row['count'];
    } else if ($row['opening_dpd_dash_group_new'] == '11 and above') {
        $fifthRowSum += $row['count'];
}
}

$onboardingData['0']['Grand Total'] = $firstRowSum;
$onboardingData['1-4']['Grand Total'] = $secondRowSum;
$onboardingData['5-6']['Grand Total'] = $thirdRowSum;
$onboardingData['7-10']['Grand Total'] = $fourthRowSum;
$onboardingData['11 and above']['Grand Total'] = $fifthRowSum;
$onboardingData['Grand Total']['Grand Total'] = $firstRowSum + $secondRowSum + $thirdRowSum + $fourthRowSum + $fifthRowSum;

$firstGrandTotal = array_sum(array_column($onboardingData, 0));
$onboardingData['Grand Total']['0'] = $firstGrandTotal;
$secondGrandTotal = array_sum(array_column($onboardingData, '1-4'));
$onboardingData['Grand Total']['1-4'] = $secondGrandTotal;
$thirdGrandTotal = array_sum(array_column($onboardingData, '5-6'));
$onboardingData['Grand Total']['5-6'] = $thirdGrandTotal;
$fourthGrandTotal = array_sum(array_column($onboardingData, '7-10'));
$onboardingData['Grand Total']['7-10'] = $fourthGrandTotal;
$fifthGrandTotal = array_sum(array_column($onboardingData, '11 and above'));
$onboardingData['Grand Total']['11 and above'] = $fifthGrandTotal;
?>

<html lang="en">
    <head>
        <title>Service Manager</title>
    </head>
<div class="container">
    <center><span style="font-size:25px; color: #3c8dbc"><b>Service Manager Dashboard</b></span></center>
    <h4 style="margin: 60px 0px 0px 0px; color: #3c8dbc">Onboarding Team</h4>
    <?php
        $data = constructTable($row_col_values, $onboardingData);
    ?>
    <h4 style="color: #3c8dbc">Early Bucket Team</h4>
    <?php
    
    $earluBuckeyGroupResult = $db->query($earlyBucketGroupQuery);

    $earlyBucketFirstRowSum = 0;
    $earlyBucketSecondRowSum = 0;
    $earlyBucketThirdRowSum = 0;
    $earlyBucketFourthRowSum = 0;
    $earlyBucketFifthRowSum = 0;
    while ($row = $db->fetchByAssoc($earluBuckeyGroupResult)) {
        $earlyMetrix[$row['opening_dpd_dash_group_new']][$row['current_dpd_dash_group_new']]['count'] = $row['count'];
        if (!empty($row['count']) && $row['count'] != 0) {
            $earlyBucketData[$row['opening_dpd_dash_group_new']][$row['current_dpd_dash_group_new']] = $row['count'];
        }
        if ($row['opening_dpd_dash_group_new'] == 0) {
            $earlyBucketFirstRowSum += $row['count'];
        } else if ($row['opening_dpd_dash_group_new'] == '1-4') {
            $earlyBucketSecondRowSum += $row['count'];
        } else if ($row['opening_dpd_dash_group_new'] == '5-6') {
            $earlyBucketThirdRowSum += $row['count'];
        } else if ($row['opening_dpd_dash_group_new'] == '7-10') {
            $earlyBucketFourthRowSum += $row['count'];
        } else if ($row['opening_dpd_dash_group_new'] == '11 and above') {
            $earlyBucketFifthRowSum += $row['count'];
        }
    }
    $earlyBucketData['0']['Grand Total'] = $earlyBucketFirstRowSum;
    $earlyBucketData['1-4']['Grand Total'] = $earlyBucketSecondRowSum;
    $earlyBucketData['5-6']['Grand Total'] = $earlyBucketThirdRowSum;
    $earlyBucketData['7-10']['Grand Total'] = $earlyBucketFourthRowSum;
    $earlyBucketData['11 and above']['Grand Total'] = $earlyBucketFifthRowSum;
    $earlyBucketData['Grand Total']['Grand Total'] = $earlyBucketFirstRowSum + $earlyBucketSecondRowSum + $earlyBucketThirdRowSum + $earlyBucketFourthRowSum + $earlyBucketFifthRowSum;

    $earlyBucketFirstGrandTotal = array_sum(array_column($earlyBucketData, 0));
    $earlyBucketData['Grand Total']['0'] = $earlyBucketFirstGrandTotal;
    $earlyBucketSecondGrandTotal = array_sum(array_column($earlyBucketData, '1-4'));
    $earlyBucketData['Grand Total']['1-4'] = $earlyBucketSecondGrandTotal;
    $earlyBucketThirdGrandTotal = array_sum(array_column($earlyBucketData, '5-6'));
    $earlyBucketData['Grand Total']['5-6'] = $earlyBucketThirdGrandTotal;
    $earlyBucketFourthGrandTotal = array_sum(array_column($earlyBucketData, '7-10'));
    $earlyBucketData['Grand Total']['7-10'] = $earlyBucketFourthGrandTotal;
    $earlyBucketFifthGrandTotal = array_sum(array_column($earlyBucketData, '11 and above'));
    $earlyBucketData['Grand Total']['11 and above'] = $earlyBucketFifthGrandTotal;
    
    $data = constructTable($row_col_values, $earlyBucketData);
    ?>
    <!--<h4 style="color:#3c8dbc">Individual Statistics- Productivity</h4>-->
<!--    <div style="padding:50px" class="container">
        <div class="panel panel-default">
            <div class="panel-body"><b> Overview statistics: </b> <br><br>
                <p><i>Statistics to be shown here ( Yet to get the requirement ) </i></p>
                <p><b>Sample data: </b> Blah blah blah</p> 
                <p><b>Current standing: </b>Blah blah blah </p> 
                <p><b>Strength: </b> Blah blah blah</p>
                <p><b>Opportunities: </b> Blah blah blah </p> 
            </div>
        </div>
    </div>-->
</div>
<?php


function constructTable($row_col_values, $onboardingData){
    echo '<div style="padding:50px" class="container">
        <table class="table table-bordered" id = "onboarding_team_table">
            <thead>
                <tr>
                    <th class = "border">Count of Current DPD Dash Group Row Labels</th>
                    <th class = "border">0 DPD Dash</th>
                    <th class = "border">1 to 4 DPD Dash</th>
                    <th class = "border">5 to 6 DPD Dash</th>
                    <th class = "border">7 to 10 DPD Dash</th>
                    <th class = "border">11 to 30 DPD Dash</th>
                    <th class = "border">Grand Total</th>
                </tr>
            </thead>
            <tbody>';
    foreach($row_col_values as $row_ind=>$row_val) {
        echo "<tr>";
        echo "<td class='total'>$row_val</td>";
        foreach($row_col_values as $col_ind=>$col_val) {

            if($row_val == "Grand Total"){
                echo "<td class='border'>".$onboardingData[$row_val][$col_val]."</td>";
            }
            else if($row_ind>$col_ind){
                echo "<td class='bottom'>".$onboardingData[$row_val][$col_val]."</td>";
            }else if($row_ind<$col_ind){
                echo "<td class='top'>".$onboardingData[$row_val][$col_val]."</td>";
            }else{
                echo "<td class='middle'>".$onboardingData[$row_val][$col_val]."</td>";
            }
        }
        echo "</tr>";
    }
    echo "</tbody>
            </table>
        </div>";
}
function sendCallReminderToServiceManager() {
    $userBean = new User();
    $emailObj = new Email();
    global $db, $sugar_config;

    $todaysData = date("Y-m-d");
    $todaysData = '2018-04-11';
    $queryToGetDistinct = "SELECT assigned_user_id from smacc_sm_account  INNER JOIN smacc_sm_account_cstm ON smacc_sm_account.id = smacc_sm_account_cstm.id_c where smacc_sm_account.status = 'Onboarding' AND smacc_sm_account_cstm.is_active_c = '1' GROUP BY smacc_sm_account.assigned_user_id";
    $distinctUserResult = $db->query($queryToGetDistinct);
    while ($row = $db->fetchByAssoc($distinctUserResult)) {
        if (!empty($row['assigned_user_id'])) {
            $userId = $row['assigned_user_id'];
            $userData = $userBean->retrieve($userId);
            if (!empty($userData)) {
                $queryToGetUserAccountData = "SELECT * from smacc_sm_account  INNER JOIN smacc_sm_account_cstm ON smacc_sm_account.id = smacc_sm_account_cstm.id_c where smacc_sm_account.assigned_user_id = '$userId' AND smacc_sm_account.status = 'Onboarding' AND smacc_sm_account_cstm.is_active_c = '1' AND callback_date BETWEEN '$todaysData 00:00:00' AND '$todaysData 23:59:59'";
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
                        $table .= "<tr><td>" . $row['id'] . "</td>"
                                . "<td>" . $row['merchant_name'] . "</td>"
                                . "<td>" . $row['contact'] . "</td>"
                                . "<td>" . $row['closing_dpd_group'] . "</td></tr>";
                    }
                }
                $table .= '</tbody></table>';
                $body = "Hi $userData->first_name, </br></br>"
                        . "The following is the list of merchant that need to be called back today as per reminders set by you in the CRM: </br></br>"
                        . "$table </br>Be sure to note your comments in the comments section in the CRM: </br> Click here for the link: linkformyservicemanagerprofile.com </br> </br>"
                        . " Thanks, </br>CRM Technology Team";
                echo "</br>";

                //Send email to the service manager.
                $email = $userData->email1;

                $mail = new SugarPHPMailer();
                $defaults = $emailObj->getSystemDefaultEmail();
                $subject = "Testing";
                $body = $body;
                $mail->setMailerForSystem();
                $mail->From = $defaults['email'];
                $mail->FromName = $defaults['name'];
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->prepForOutbound();
                $mail->AddAddress($sugar_config['ng_gowthami_gk']);
                @$mail->Send();
                die();
            }
        }
    }
    die();
}
?>
