<?php
if (!defined('sugarEntry'))
    define('sugarEntry', true);

require_once('include/entryPoint.php');
ini_set('max_execution_time', 300000);

global $db;
$environment = getenv('SCRM_SITE_URL');

require_once('custom/modules/Cases/Cases_functions.php');
$casesCommonFunctions = new Cases_functions();
$authentication = $casesCommonFunctions->casesAuthentication();

if (!$authentication) {
    die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
}
?>

<?php
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
$startDate = $fromDate . ' 00:00:00';
$endDate = $toDate . ' 23:59:59';
$queryToGetSurveyReports = "SELECT * from survey_report where submit_date BETWEEN '$startDate' AND ' $endDate'";
//$queryToGetSurveyReports= "SELECT * from survey_report where submit_date BETWEEN '2018-06-01 00:00:00' AND ' 2018-08-08 23:59:59'";

$reports = $db->query($queryToGetSurveyReports);
?>
<style>
    /*        table {
                max-width:980px;
                table-layout:fixed;
                margin:auto;
            }
            th, td {
                padding:5px 10px;
                border:1px solid #000;
            }
            thead, tfoot {
                background:#f9f9f9;
                display:table;
                width:100%;
            }
            tbody {
                height:400px;
                overflow:auto;
                overflow-x:hidden;
                display:block;
                width:100%;
            }
            tbody tr {
                display:table;
                width:100%;
                table-layout:fixed;
            }*/
    .column {
        float: left;
        width: 50%;
        padding: 10px;
        height: 30px; /* Should be removed. Only for demonstration */
    }
</style>
<div class="container">
    <table class="table table-bordered table-striped scroll" >
        <!--<tfoot>-->
        <tr>
            <th style="width:180px;">Area</th>
            <th colspan="4">Finance</th>
            <th colspan="4">Operations</th>
            <th colspan="3">Sales</th>
            <th colspan="3">Customer Service</th>
            <th colspan="2">Collections</th>
        </tr>

        <tr>
            <th style="width:180px;">Question Number</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
            <th>13</th>
            <th>14</th>
            <th>15</th>
            <th>16</th>
        </tr>
        <!--</tfoot>-->

        <tbody>

            <?php
            $financeForEachQuestion[0] = $financeForEachQuestion[1] = $financeForEachQuestion[2] = $financeForEachQuestion[3] = $financeForEachQuestion['total'][0] = $financeForEachQuestion['total'][1] = $financeForEachQuestion['total'][2] = $financeForEachQuestion['total'][3] = 0;

            $operationsForEachQuestion[0] = $operationsForEachQuestion[1] = $operationsForEachQuestion[2] = $operationsForEachQuestion[3] = $operationsForEachQuestion['total'][0] = $operationsForEachQuestion['total'][1] = $operationsForEachQuestion['total'][2] = $operationsForEachQuestion['total'][3] = 0;

            $salesForEachQuestion[0] = $salesForEachQuestion[1] = $salesForEachQuestion[2] = $salesForEachQuestion['total'][0] = $salesForEachQuestion['total'][1] = $salesForEachQuestion['total'][2] = 0;

            $customerServiceForEachQuestion[0] = $customerServiceForEachQuestion[1] = $customerServiceForEachQuestion[2] = $customerServiceForEachQuestion['total'][0] = $customerServiceForEachQuestion['total'][1] = $customerServiceForEachQuestion['total'][2] = 0;

            $collectionForEachQuestion[0] = $collectionForEachQuestion[1] = $collectionForEachQuestion['total'][0] = $collectionForEachQuestion['total'][1] = 0;

            while ($row = $db->fetchByAssoc($reports)) {
                $emailId = $row['email_id'];
                $financeData = explode(',', $row['finance_data']);
                $operationsData = explode(',', $row['operations_data']);
                $salesData = explode(',', $row['sales_data']);
                $customerServiceData = explode(',', $row['customer_service_data']);
                $collectionData = explode(',', $row['collections_data']);
                //Finance questions
                for ($i = 0; $i < 4; $i++) {
                    if ($financeData[$i] == "4" || $financeData[$i] == "5") {
                        $financeForEachQuestion[$i] += 1;
                    } else if ($financeData[$i] == "1" || $financeData[$i] == "2" || $financeData[$i] == "3") {
                        $financeForEachQuestion['total'][$i] += 1;
                    }
                }
                //Operations questions
                for ($i = 0; $i < 4; $i++) {
                    if ($operationsData[$i] == "4" || $operationsData[$i] == "5") {
                        $operationsForEachQuestion[$i] += 1;
                    } else if ($operationsData[$i] == "1" || $operationsData[$i] == "2" || $operationsData[$i] == "3") {
                        $operationsForEachQuestion['total'][$i] += 1;
                    }
                }
                //Sales questions
                for ($i = 0; $i < 3; $i++) {
                    if ($salesData[$i] == "4" || $salesData[$i] == "5") {
                        $salesForEachQuestion[$i] += 1;
                    } else if ($salesData[$i] == "1" || $salesData[$i] == "2" || $salesData[$i] == "3") {
                        $salesForEachQuestion['total'][$i] += 1;
                    }
                }

                //Customer service questions
                for ($i = 0; $i < 3; $i++) {
                    if ($customerServiceData[$i] == "4" || $customerServiceData[$i] == "5") {
                        $customerServiceForEachQuestion[$i] += 1;
                    } else if ($customerServiceData[$i] == "1" || $customerServiceData[$i] == "2" || $customerServiceData[$i] == "3") {
                        $customerServiceForEachQuestion['total'][$i] += 1;
                    }
                }

                //collection questions
                for ($i = 0; $i < 2; $i++) {
                    if ($collectionData[$i] == "4" || $collectionData[$i] == "5") {
                        $collectionForEachQuestion[$i] += 1;
                    } else if ($collectionData[$i] == "1" || $collectionData[$i] == "2" || $collectionData[$i] == "3") {
                        $collectionForEachQuestion['total'][$i] += 1;
                    }
                }
                ?>
    <!--                        <tr>            
                                <td style="width:180px;"><?php echo $emailId; ?></td>
                                <td><?php
                if ($financeData[0] != "-1") {
                    echo $financeData[0];
                }
                ?></td>
                                <td><?php
                if ($financeData[1] != "-1") {
                    echo $financeData[1];
                }
                ?></td>
                                <td><?php
                if ($financeData[2] != "-1") {
                    echo $financeData[2];
                }
                ?></td>
                                <td><?php
                if ($financeData[3] != "-1") {
                    echo $financeData[3];
                }
                ?></td>
                                <td><?php
                if ($operationsData[0] != "-1") {
                    echo $operationsData[0];
                }
                ?></td>
                                <td><?php
                if ($operationsData[1] != "-1") {
                    echo $operationsData[1];
                }
                ?></td>
                                <td><?php
                if ($operationsData[2] != "-1") {
                    echo $operationsData[2];
                }
                ?></td>
                                <td><?php
                if ($operationsData[3] != "-1") {
                    echo $operationsData[3];
                }
                ?></td>
                                <td><?php
                if ($salesData[0] != "-1") {
                    echo $salesData[0];
                }
                ?></td>
                                <td><?php
                if ($salesData[1] != "-1") {
                    echo $salesData[1];
                }
                ?></td>
                                <td><?php
                if ($salesData[2] != "-1") {
                    echo $salesData[2];
                }
                ?></td>
                                <td><?php
                if ($customerServiceData[0] != "-1") {
                    echo $customerServiceData[0];
                }
                ?></td>
                                <td><?php
                if ($customerServiceData[1] != "-1") {
                    echo $customerServiceData[1];
                }
                ?></td>
                                <td><?php
                if ($customerServiceData[2] != "-1") {
                    echo $customerServiceData[2];
                }
                ?></td>
                                <td><?php
                if ($collectionData[0] != "-1") {
                    echo $collectionData[0];
                }
                ?></td>
                                <td><?php
                if ($collectionData[1] != "-1") {
                    echo $collectionData[1];
                }
                ?></td>
                            </tr>-->
                <?php
            }
            $finace0Total = $financeForEachQuestion[0] + $financeForEachQuestion['total'][0];
            $finace1Total = $financeForEachQuestion[1] + $financeForEachQuestion['total'][1];
            $finace2Total = $financeForEachQuestion[2] + $financeForEachQuestion['total'][2];
            $finace3Total = $financeForEachQuestion[3] + $financeForEachQuestion['total'][3];

            $operations0Total = $operationsForEachQuestion[0] + $operationsForEachQuestion['total'][0];
            $operations1Total = $operationsForEachQuestion[1] + $operationsForEachQuestion['total'][1];
            $operations2Total = $operationsForEachQuestion[2] + $operationsForEachQuestion['total'][2];
            $operations3Total = $operationsForEachQuestion[3] + $operationsForEachQuestion['total'][3];

            $sales1Total = $salesForEachQuestion[0] + $salesForEachQuestion['total'][0];
            $sales2Total = $salesForEachQuestion[1] + $salesForEachQuestion['total'][1];
            $sales3Total = $salesForEachQuestion[2] + $salesForEachQuestion['total'][2];

            $customerService0Total = $customerServiceForEachQuestion[0] + $customerServiceForEachQuestion['total'][0];
            $customerService1Total = $customerServiceForEachQuestion[1] + $customerServiceForEachQuestion['total'][1];
            $customerService2Total = $customerServiceForEachQuestion[2] + $customerServiceForEachQuestion['total'][2];

            $collection0Total = $collectionForEachQuestion[0] + $collectionForEachQuestion['total'][0];
            $collection1Total = $collectionForEachQuestion[1] + $collectionForEachQuestion['total'][1];

            $totalFinaceCsat = $totalFinace = $totalOperationsCsat = $totalOperations = $totalSalesCst = $totalSales = 0;
            for ($i = 0; $i < 4; $i++) {
                $totalFinaceCsat += $financeForEachQuestion[$i];
                $totalOperationsCsat += $operationsForEachQuestion[$i];
                $totalSalesCst += $salesForEachQuestion[$i];
                $totalCustomerServiceCsat += $customerServiceForEachQuestion[$i];
                $totalCollectionCsat += $collectionForEachQuestion[$i];
            }
            $totalFinace = $finace0Total + $finace1Total + $finace2Total + $finace3Total;
            $totalOperations = $operations0Total + $operations1Total + $operations2Total + $operations3Total;
            $totalSales = $sales1Total + $sales2Total + $sales3Total;
            $totalCustomerService = $customerService0Total + $customerService1Total + $customerService2Total;
            $totalCollection = $collection0Total + $collection1Total;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th  style="width:180px;">CSAT for each question</th>
                <th><?php echo (int) ($financeForEachQuestion[0] * 100 / $finace0Total) . "%($finace0Total)"; ?></th>
                <th><?php echo (int) ($financeForEachQuestion[1] * 100 / $finace1Total) . "%($finace1Total)"; ?></th>
                <th><?php echo (int) ($financeForEachQuestion[2] * 100 / $finace2Total) . "%($finace2Total)"; ?></th>
                <th><?php echo (int) ($financeForEachQuestion[3] * 100 / $finace3Total) . "%($finace3Total)"; ?></th>
                <th><?php echo (int) ($operationsForEachQuestion[0] * 100 / $operations0Total) . "%($operations0Total)"; ?></th>
                <th><?php echo (int) ($operationsForEachQuestion[1] * 100 / $operations1Total) . "%($operations1Total)"; ?></th>
                <th><?php echo (int) ($operationsForEachQuestion[2] * 100 / $operations2Total) . "%($operations2Total)"; ?></th>
                <th><?php echo (int) ($operationsForEachQuestion[3] * 100 / $operations3Total) . "%($operations3Total)"; ?></th>
                <th><?php echo (int) ($salesForEachQuestion[0] * 100 / $sales1Total) . "%($sales1Total)"; ?></th>
                <th><?php echo (int) ($salesForEachQuestion[1] * 100 / $sales2Total) . "%($sales2Total)"; ?></th>
                <th><?php echo (int) ($salesForEachQuestion[2] * 100 / $sales3Total) . "%($sales3Total)"; ?></th>
                <th><?php echo (int) ($customerServiceForEachQuestion[0] * 100 / $customerService0Total) . "%($customerService0Total)"; ?></th>
                <th><?php echo (int) ($customerServiceForEachQuestion[1] * 100 / $customerService1Total) . "%($customerService1Total)"; ?></th>
                <th><?php echo (int) ($customerServiceForEachQuestion[2] * 100 / $customerService2Total) . "%($customerService2Total)"; ?></th>
                <th><?php echo (int) ($collectionForEachQuestion[0] * 100 / $collection0Total) . "%($collection0Total)"; ?></th>
                <th><?php echo (int) ($collectionForEachQuestion[1] * 100 / $collection1Total) . "%($collection1Total)"; ?></th>
            </tr>

            <tr>
                <th  style="width:180px;">CSAT for each section</th>
                <th colspan="4"> <?php echo (int) ($totalFinaceCsat * 100 / $totalFinace) . "% " . ("($totalFinaceCsat" . "/$totalFinace)")?></th>
                <th colspan="4"><?php echo (int) ($totalOperationsCsat * 100 / $totalOperations) . "% " . ("($totalOperationsCsat" . "/$totalOperations)") ?></th>
                <th colspan="3"><?php echo (int) ($totalSalesCst * 100 / $totalSales) . "% " .("($totalSalesCst" . "/$totalSales)  ") ?></th>
                <th colspan="3"><?php echo (int) ($totalCustomerServiceCsat * 100 / $totalCustomerService) . "% " .("($totalCustomerServiceCsat" . "/$totalCustomerService)") ?></th>
                <th colspan="2"><?php echo (int) ($totalCollectionCsat * 100 / $totalCollection) . "% " . ("($totalCollectionCsat" . "/$totalCollection)  ")  ?></th>
            </tr>
    <!--            <tr>
                    <th  style="width:180px;">CSAT across all sections</th>
                    <th colspan="6"><?php $csatAcrossAllSections = $totalFinaceCsat + $totalOperationsCsat + $totalSalesCst + $totalCustomerServiceCsat + $totalCollectionCsat; echo $csatAcrossAllSections; ?> </th>
                    <th colspan="6"><?php $csatAcrossAllSections1 = ($totalFinace + $totalOperations + $totalSales + $totalCustomerService + $totalCollection); echo $csatAcrossAllSections1; ?></th>           
                    <th colspan="6"><?php echo (int) ((($totalFinaceCsat * 100 / $totalFinace) + ($totalOperationsCsat * 100 / $totalOperations) + ($totalSalesCst * 100 / $totalSales) + ($totalCustomerServiceCsat * 100 / $totalCustomerService) + ($totalCollectionCsat * 100 / $totalCollection)) / 5) . "% "  .("($csatAcrossAllSections" . "/$csatAcrossAllSections1)") ?></th>
                </tr>-->
        </tfoot>
    </table>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
            </div>
            <div class="col-lg-3 border border-dark" style="background-color:#ddd; height:20px;">
                <h1>CSAT across all sections :</h2>
            </div>
            <div class="col-lg-2 border border-dark" style="background-color:#ddd; height:20px;">
                <h2><?php echo (int) ((($totalFinaceCsat * 100 / $totalFinace) + ($totalOperationsCsat * 100 / $totalOperations) + ($totalSalesCst * 100 / $totalSales) + ($totalCustomerServiceCsat * 100 / $totalCustomerService) + ($totalCollectionCsat * 100 / $totalCollection)) / 5) . "% " . ("($csatAcrossAllSections" . "/$csatAcrossAllSections1)") ?></h2>
            </div>
            <div class="col-lg-4">
            </div>
        </div>
    </div>
</div>


