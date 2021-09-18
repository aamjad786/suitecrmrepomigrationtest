<?php
$environment = getenv('SCRM_SITE_URL');

require_once('custom/modules/Cases/Cases_functions.php');
$casesCommonFunctions = new Cases_functions();
$authentication = $casesCommonFunctions->casesAuthentication();
if (!$authentication) {
    die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
} 
?>
<center><h1 style="color:#3C8DBC; margin-bottom: 40px; margin-top:20px"><b> NPS - CSAT Report</b></h1></center>

    <form action="" id="uploadForm" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="sheet">Select file name to upload :</label>
            <p><b>Note:</b> Only spreadsheets(.xlsx) are accepted. Use the sample file to upload data</p><br>
            <input type="file" id="sheet" name="sheet" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/> 
        </div>
        <input type="submit" value="Submit" name="submit"><br/><br/>
    </form>

    <?php
    global $db;
    if (!defined('sugarEntry'))
        define('sugarEntry', true);

    require_once('include/entryPoint.php');
    ini_set('max_execution_time', 300000);

    $count = 0;

    if (isset($_POST["submit"])) {

        require_once 'XLSXReader.php';
        $target_dir = "upload/";
        $errors = array();

        if (!empty($_FILES["sheet"])) {
            $file = $_FILES["sheet"]["tmp_name"];
            $file_name = $current_user->user_name . "_" . time() . "_" . $_FILES['sheet']['name'];
            $file_size = $_FILES['sheet']['size'];
            $file_tmp = $_FILES['sheet']['tmp_name'];
            $file_type = $_FILES['sheet']['type'];
            $file_ext = strtolower(end(explode('.', $_FILES['sheet']['name'])));

            if (!empty($file_name)) {
                $extensions = array("xlsx");
                if (in_array($file_ext, $extensions) === false) {
                    $errors[] = "Extension not allowed, please choose an xls file. ($file_ext is not supported)";
                }

                if ($file_size > 2097152) {
                    $errors[] = 'File size must be less 2 MB';
                }

                if (empty($errors)) {
                    move_uploaded_file($file_tmp, $target_dir . $file_name);
                } else {
                    echo "<p style='color:red'>File upload failed please try again. If problem persists contact IT team.</p>";
                    for ($i = 0; $i < sizeof($errors); $i++) {
                        echo "<p>$errors[0]</p>";
                    }
                }
                $target_file = $target_dir . basename($file_name);
                try {
                    $xlsx = new XLSXReader($target_file);
                    $sheet = $xlsx->getSheet('Raw Data');
                    array2Table($sheet->getData());
                    //header("Refresh:0");
                    echo "Documnet uploaded successfully.";
                } catch (Exception $e) {
                    $GLOBALS['log']->debug("Renewals file upload exception :: " . $e->getMessage());
                }
            }
        }//if(!empty($_FILES["sheet"]['name']))
    }//end if(isset($_POST["submit"]))

    function array2Table($data) {
        if (!empty($data)) {
            foreach ($data as $i => $row) {
                $phoneNumber = "";
                $submitDate = "";
                $score = "";
                if ($i == 0) {
                    continue;
                }
                $financeData = "";
                $operationsData = "";
                $salesData = "";
                $customerData = "";
                $collectionsData = "";
                $remarks = "";
                foreach ($row as $k => $cell) {
                    $value = escape($cell);
                    if ($k == 2) {
                        $date = ($value - 25569) * 86400;
                        $submitDate = gmdate("Y-m-d H:i:s", $date);
                    }
                    if ($k == 0) {
                        $responseId = $value;
                    }
                    if ($k == 16) {
                        $score = $value;
                    }
                    if ($k == 7) {
                        if (!empty($value) && $value != "Custom Variable 1") {
                            $phoneNumber = $value;
                        }
                    }

                    if ($k == 17 && $cell == 'No')
                        break;
                    if ($k == 12) {
                        $emailId = $value;
                    }
                    if ($k == 18) {
                        $remarks = $value;
                    }
                    if ($k >= 19) {
                        $question_no = $k - 18;
                        if ($i == 1) {
//                        echo "<td>$question_no</td>";
                        } else {
                            $csat_data[$question_no][$value] ++;
                            if ($question_no < 5) {
                                if (!empty($financeData)) {
                                    $financeData .= ",";
                                }
                                if (empty($value)) {
                                    $financeData .= "-1";
                                }
                                $financeData .= $value;
                            } else if ($question_no < 9) {
                                if (!empty($operationsData)) {
                                    $operationsData .= ",";
                                }
                                if (empty($value)) {
                                    $operationsData .= "-1";
                                }
                                $operationsData .= $value;
                            } else if ($question_no < 12) {
                                if (!empty($salesData)) {
                                    $salesData .= ",";
                                }
                                if (empty($value)) {
                                    $salesData .= "-1";
                                }
                                $salesData .= $value;
                            } else if ($question_no < 15) {
                                if (!empty($customerData)) {
                                    $customerData .= ",";
                                }
                                if (empty($value)) {
                                    $customerData .= "-1";
                                }
                                $customerData .= $value;
                            } else {
                                if (!empty($collectionsData)) {
                                    $collectionsData .= ",";
                                }
                                if (empty($value)) {
                                    $collectionsData .= "-1";
                                }
                                $collectionsData .= $value;
                            }
                        }
                    }
                }
                if (!empty($responseId)) {
                    getAgentPriMappingDataAndInserIntoSurveyReport($phoneNumber, $submitDate, $score, $financeData, $operationsData, $salesData, $customerData, $collectionsData, $emailId, $responseId,$remarks);
                }
            }
        }
    }

    function getAgentPriMappingDataAndInserIntoSurveyReport($phoneNumber, $submitDate, $score, $financeData, $operationsData, $salesData, $customerData, $collectionsData, $emailId, $responseId,$remarks) {
        global $db;

        if (!empty($phoneNumber)) {
            $queryToGetCustoerAgent = "select * from AgentPRIMapping where mobile = $phoneNumber";
            $AgentPriMappingData = $db->query($queryToGetCustoerAgent);
            while ($row = $db->fetchByAssoc($AgentPriMappingData)) {
                if (!empty($row)) {
                    $agentId = $row['agent_id'];
                    $agentName = $row['agent_name'];
                    $phoneNumber = $row['mobile'];
                }
            }
        } else {
            $message = "Phone number does not exist to get";
        }
        $checkQuery = "SELECT * from survey_report where response_id = '$responseId' AND submit_date = '$submitDate'";
        $queryResponse = $db->query($checkQuery);
        if ($queryResponse->num_rows <= 0) {            //Checking if that number with exact time stamp already exists, only then insert the data to the table
            $inserQuesry = "Insert INTO survey_report (customer_phone_number, score, agent_name, agent_id,submit_date, finance_data, operations_data, sales_data, customer_service_data, collections_data,email_id,response_id,remarks) VALUES ('$phoneNumber', '$score', '$agentName', '$agentId', '$submitDate','$financeData','$operationsData','$salesData','$customerData','$collectionsData','$emailId','$responseId','$remarks')";
            $db->query($inserQuesry);
        }
    }

    function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    function dateRange($date) { //This is not yet being used. Should use this to optimize between csat and nps
        $response = array();
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
        $response['fromDate'] = $fromDate;
        $response['todate'] = $toDate;
        return $response;
    }
?>
<script type="text/javascript" src="custom/include/js/jquery.min.js"></script>
<script type="text/javascript" src="custom/include/js/moment.min.js"></script>
<script type="text/javascript" src="custom/include/js/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="custom/include/css/daterangepicker.css" />

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <div  style="margin: 30px 0px 30px 0px">
    <span>
        <input type="text" name="datefilter" id="date_range_picker" value="" placeholder="Date Filter"/>
    </span>
    </div>
    
    <span style="text-align: center; color:green;" >
        <p style="margin-bottom: 30px" id="csatdateRangeText"> </p>
    </span>
    <body>
        <div class="container">
            <ul class="nav nav-tabs nav-justified">
                <li id = "nps_row" class="active"><a href="">Agent NPS Tracker</a></li>
                <li id = "csat_row"><a href="">CSAT survey data</a></li>
            </ul>
            <br>
        </div>
        <div id = "csat" style="display:none">
            <div class="portlet-body">
                <?php
                require_once('custom/modules/Cases/csat.php');
                ?>
            </div>
        </div>
        <div id = "nps" style="display:none">
            <div class="portlet-body">
                <?php
                require_once('custom/modules/Cases/nps.php');
                ?>
            </div>
        </div>
    </body>
</html>


<script>

    $(document).ready(function() {
        $("#nps").show();
        $("#csat_row").click(function(){
            $("#nps").hide();
            $("#csat").show();
            $("#nps_row").removeClass("active");
            $("#csat_row").addClass("active");
            return false;
        });
        $("#nps_row").click(function(){
            $("#nps").show();
            $("#csat").hide();
            $("#nps_row").addClass("active");
            $("#csat_row").removeClass("active");

            return false;
        });
    });
    
        $(function () {
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            maxDate: new Date(),
            locale: {
                cancelLabel: 'Clear'
            }
        });
        $('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            var dateRange = $('#date_range_picker').val();
            var environment = '<?php echo $environment; ?>';
            window.location = environment + '/index.php?module=Cases&action=Upload_nps_csat&daterange=' + dateRange;
        });
        $('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
            
        });
        $('#csatdateRangeText').html("Showing the data from the date range <b><?php echo date("F jS, Y", strtotime($fromDate)); ?></b> to <b><?php echo date("F jS, Y", strtotime($toDate)); ?></b>");

</script>


