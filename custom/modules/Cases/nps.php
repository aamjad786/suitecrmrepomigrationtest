<?php
$environment = getenv('SCRM_SITE_URL');
require_once('custom/modules/Cases/Cases_functions.php');
$casesCommonFunctions = new Cases_functions();
$authentication = $casesCommonFunctions->casesAuthentication();
if(!$authentication){
    die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
}
?>
<span>
    <select id="bucket_list">
        <option value="all">All</option>
        <option value="early">Early</option>
        <option value="on_boarding">Onboarding</option>
        <option value="customer_service">Customer Service</option>        
    </select>
</span>

<?php
global $db;
global $current_user;
$role = ACLRole::getUserRoleNames($current_user->id);
$userName = $current_user->user_name;
if (!empty($_REQUEST['daterange'])) {
    $dateRange = $_REQUEST['daterange'];
    $extractedDate = explode('-', $dateRange);
    $fromDate = date("Y-m-d", strtotime($extractedDate[0]));
    $toDate = date("Y-m-d", strtotime($extractedDate[1]));
} else {
    $todaysDate = date("Y-m-d");
    $currentMonth = date('m');
    $currentYear = date('Y');
    $fromDate = $currentYear . '-' . $currentMonth . '-01';
    $toDate = $todaysDate;
}
$startDate =  $fromDate . ' 00:00:00';
$endDate = $toDate. ' 23:59:59';
if (true) {
    $queryToGetAllNPS = "Select survey_report.*, agent_bucket_mapping.bucket from survey_report INNER JOIN agent_bucket_mapping ON survey_report.agent_id = agent_bucket_mapping.agent_id where survey_report.customer_phone_number <> '' AND survey_report.agent_name <> '' AND survey_report.submit_date BETWEEN '$startDate' AND '$endDate'";
    $allnps = $db->query($queryToGetAllNPS);
} else if (in_array("Customer support executive uue", $role)) {
    $queryToGetAllNPS = "Select survey_report.*, agent_bucket_mapping.bucket from survey_report INNER JOIN agent_bucket_mapping ON survey_report.agent_id = agent_bucket_mapping.agent_id where survey_report.customer_phone_number <> '' AND survey_report.agent_name <> '' AND survey_report.submit_date BETWEEN '$startDate' AND ' $endDate' AND survey_report.agent_id = '$userName'";
    $allnps = $db->query($queryToGetAllNPS);
    ?>
    <script>
        $('#uploadForm').hide();
    </script>
    <?php
} else {
    ?>
    <script>
        $('#uploadForm').hide();
        $('#date_range_picker').hide();
    </script>
    <?php
    echo "You do not have access, please contact admin";
    die();
}
$arrayOfData = array();
while ($row = $db->fetchByAssoc($allnps)) {
    $agentId = $row['agent_id'];
    $agentName = $row['agent_name'];
    $score = $row['score'];
    $bucket = $row['bucket'];
    if (array_key_exists($agentId, $arrayOfData)) {
        $countOfCustomers = $arrayOfData[$agentId]['count_of_customers'];
        $arrayOfData[$agentId]['count_of_customers'] = $countOfCustomers + 1;
    } else {
        $arrayOfData[$agentId]['agentName'] = $agentName;
        $arrayOfData[$agentId]['detractors'] = 0;
        $arrayOfData[$agentId]['passives'] = 0;
        $arrayOfData[$agentId]['promoters'] = 0;
        $arrayOfData[$agentId]['count_of_customers'] = 1;
        $arrayOfData[$agentId]['bucket'] = $bucket;
    }
    $scorebracket = calculatingNPSBreakdown($score);
    if (!empty($scorebracket)) {
        $arrayOfData[$agentId][$scorebracket] = $arrayOfData[$agentId][$scorebracket] + 1;
    }
}
//print_r($arrayOfData);
function calculatingNPSBreakdown($score) {
    $scoreBracket = '';
    if (!empty($score)) {
        if ($score >= 1 AND $score <= 6) {
            $scoreBracket = 'detractors';
        } else if ($score >= 7 AND $score <= 8) {
            $scoreBracket = 'passives';
        } else if ($score >= 9 AND $score <= 10) {
            $scoreBracket = 'promoters';
        }
    }
    return $scoreBracket;
}
?>
<style>
    td {text-align:center}
</style>
<!-- Data display -->
<div>
<div style="width: 75%; float:left;">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Agent Name</th>
                <th>Agent NG Id</th>
                <th>NPS</th>
                <th>Number of Customers Serviced</th>
            </tr>
        </thead>
        <tbody>
            <?php
//            print_r($arrayOfData);
            $onBoardingBucketPromotorsTotal = $onBoardingBucketDetractorsTotal = $onBoardingBucketPassivesTotal = 0;
            $earlyBucketDetractorsTotal = $earlyBucketPromotorsTotal = $earlyBucketPassivesTotal = 0;
            $customerServicePromotorsTotal =  $customerServiceDetractorsTotal =  $customerServicePassivesTotal = 0;
            
            $overAllPromotorsTotal = $overAllDetractorsTotal = $overAllPassivesTotal = 0;
            $overAllBucketCount = 0;
            
            foreach ($arrayOfData as $key => $value) {
                $ngId = $key;
                $agentName = $value['agentName'];
                $numberofCustomers = $value['count_of_customers'];
                $totalPromotors = $value['promoters'];
                $totalDetractors = $value['detractors'];
                $totalpassives = $value['passives'];
                $bucket = $value['bucket'];
                $nps = round((($totalPromotors - $totalDetractors) / $numberofCustomers) * 100, 1);
                if($bucket == "on_boarding"){
                    $onBoardingBucketPromotorsTotal += $totalPromotors;
                    $onBoardingBucketDetractorsTotal += $totalDetractors;
                    $onBoardingBucketPassivesTotal  += $totalpassives;       
                } else if($bucket == "early"){
                    $earlyBucketPromotorsTotal += $totalPromotors;
                    $earlyBucketDetractorsTotal += $totalDetractors;
                    $earlyBucketPassivesTotal += $totalpassives;
                } else if($bucket == "customer_service"){
                    $customerServicePromotorsTotal += $totalPromotors;
                    $customerServiceDetractorsTotal += $totalDetractors;
                    $customerServicePassivesTotal += $totalpassives;
                }
                $overAllPromotorsTotal += $totalPromotors;
                $overAllDetractorsTotal += $totalDetractors;
                ?>
                <tr class="<?php echo $bucket?>">
                    <td><?php echo $agentName; ?></td>
                    <td><?php echo $ngId; ?></td>
                    <td><?php echo $nps; ?></td>
                    <td><?php echo $numberofCustomers; ?></td>
                </tr>
                <?php
            }            
            $overallOnBoardingBucketCount = $onBoardingBucketPromotorsTotal+$onBoardingBucketDetractorsTotal+$onBoardingBucketPassivesTotal;
            $overallEarlyBucketCount = $earlyBucketDetractorsTotal+$earlyBucketPromotorsTotal+$earlyBucketPassivesTotal;
            $overallCustomerServiceBucketCount = $customerServicePromotorsTotal+$customerServiceDetractorsTotal+$customerServicePassivesTotal;
            
            $onboardsPromotersPercentage = $onBoardingBucketPromotorsTotal/$overallOnBoardingBucketCount*100;
            $onboardsDetractorsPercentage = $onBoardingBucketDetractorsTotal/$overallOnBoardingBucketCount*100;
            $earlyPromotersPercentage = $earlyBucketPromotorsTotal/$overallEarlyBucketCount*100;
            $earlyDetractorsPercentage = $earlyBucketDetractorsTotal/$overallEarlyBucketCount*100;
            $customerServicePromotersPercentage = $customerServicePromotorsTotal/$overallCustomerServiceBucketCount*100;
            $customerServiceDetractorsPercentage = $customerServiceDetractorsTotal/$overallCustomerServiceBucketCount*100;
            
            $npsScoreForOnboardBucket = $onboardsPromotersPercentage - $onboardsDetractorsPercentage;
            $npsScoreForEarlyBucket = $earlyPromotersPercentage - $earlyDetractorsPercentage;
            $npsScoreForCustomerService = $customerServicePromotersPercentage - $customerServiceDetractorsPercentage;
            
            $allScore = $npsScoreForOnboardBucket+$npsScoreForEarlyBucket+$npsScoreForCustomerService;
            ?>
        </tbody>
    </table>
</div>

    <div style="width: 25%; float:right; margin-top:30px;">
        <span>
            <p id="nps_for_each_bucket_message"></p>
        </span>
    </div>
</div>

<script>
$('#nps_for_each_bucket_message').html(" NPS score for all buckets is <?php echo round($allScore, 1) ?>");
 $("#bucket_list").change(function () {
    var bucketValue = $('#bucket_list').val();
    if(bucketValue === "early"){
        $('.on_boarding').hide();
        $('.customer_service').hide();
        $('.early').show();
        $('#nps_for_each_bucket_message').html(" NPS score for early bucket is <?php echo round($npsScoreForEarlyBucket, 1) ?>");
    } else if(bucketValue === "on_boarding"){
        $('.early').hide();
        $('.customer_service').hide();
        $('.on_boarding').show();
        $('#nps_for_each_bucket_message').html(" NPS score for on boarding bucket is <?php echo round($npsScoreForOnboardBucket, 1) ?>");
    } else if(bucketValue === "customer_service"){ 
        $('.early').hide();
        $('.on_boarding').hide();
        $('.customer_service').show();
        $('#nps_for_each_bucket_message').html(" NPS score for customer service bucket is <?php echo round($npsScoreForCustomerService, 1) ?>");
    } else {
        $('.on_boarding').show();
        $('.early').show();
        $('.customer_service').show();
        $('#nps_for_each_bucket_message').html(" NPS score for all buckets is <?php echo round($allScore, 1) ?>");

    }
});
</script>
