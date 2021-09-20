<?php
if(!defined('sugarEntry')) define('sugarEntry', true);

ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once('custom/include/SendEmail.php');;
require_once('modules/EmailTemplates/EmailTemplate.php');

class Neo_CustomersViewmonthly_file_upload extends SugarView {
    
    private $chartV;

    function __construct(){    
        parent::SugarView();
    }

    function display() {
        global $current_user;
        global $db;
    
        echo $html = <<<HTMLFORM
        <h1>Monthly File Upload</h1><br/>
        <form action="" id="uploadForm3" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload3">Select file name to upload :</label>
                <input type="file" id="fileToUpload3" name="fileToUpload3"/>
            </div>
            <input type="submit" value="Upload" name="monthlyFileUpload"><br/><br/>
        </form>
        <a href="RenewalMonthlyUpload.xls">Download Sample file</a><br/>
        <br/>
        <hr/>
        <h1>Repsonse</h1>
        <br/>
HTMLFORM;

        if(isset($_POST["monthlyFileUpload"])) {
            require_once 'excel_reader2.php';
            $target_dir = "upload/";
            $errors = array();
            $file_name = $_FILES['fileToUpload3']['name'];
            $file_size = $_FILES['fileToUpload3']['size'];
            $file_tmp = $_FILES['fileToUpload3']['tmp_name'];
            $file_type = $_FILES['fileToUpload3']['type'];
            $file_ext = strtolower(end(explode('.',$_FILES['fileToUpload3']['name'])));

            if (!empty($file_name)) {
                $extensions= array("csv","xls");
                if(in_array($file_ext,$extensions) === false){
                    $errors[]="Extension not allowed, please choose an xls file. ($file_ext is not supported)";
                }

                if($file_size > 2097152){
                    $errors[]='File size must be less 2 MB';
                }

                if(empty($errors)){
                    move_uploaded_file($file_tmp,"upload/".$file_name);
                }else{
                    for ($i=0; $i < sizeof($errors); $i++) { 
                        echo "<p>$errors[0]</p>";
                    }
                }
                $target_file = $target_dir . basename($_FILES["fileToUpload3"]["name"]);
                $data = new Spreadsheet_Excel_Reader($target_file);
                $column_list = $data->sheets[0]['cells'][1];
                $length = sizeof($data->sheets[0]['cells']) + 1;
                $customer_id_row = array_search('Customer ID', $column_list);
                $instant_renewal_row = array_search('Instant Renewal', $column_list);
                $eligible_amount_row = array_search('Instant Renewal Eligible Amount', $column_list);
                $risk_grade_row = array_search('Risk Grade', $column_list);
                $blacklist_row = array_search('Credit Reject / Collections blacklist', $column_list);
                $total_count=0;
                $failed_ids = array();
                $yes_no = array("Yes","No","");
                $q = "UPDATE RenewalMonthlyData set deleted = 1 where 1";
                $r = $db->query($q);
                for ($i = 2; $i < $length ; $i++) {
                    $total_count++;
                    $customer_id = $data->sheets[0]['cells'][$i][$customer_id_row];
                    $instant_renewal = $data->sheets[0]['cells'][$i][$instant_renewal_row];
                    $eligible_amount = $data->sheets[0]['cells'][$i][$eligible_amount_row];
                    $risk_grade = $data->sheets[0]['cells'][$i][$risk_grade_row];
                    $blacklist = $data->sheets[0]['cells'][$i][$blacklist_row];
                    if (!empty($customer_id)) {
                        $pass = 1;
                        $q = "SELECT count(*) count from neo_customers where customer_id = '$customer_id'";
                        $r = $db->query($q);
                        $c = 0;
                        while ($e_row = $db->fetchByAssoc($r)) {
                            $c = $e_row['count'];
                        }
                        if ($c != 1){
                            echo "<p style='color:red'>Customer ID $customer_id does not exist.</p>";
                            $pass = 0;
                        }else{
                            if (!in_array($instant_renewal, array("Yes","No",""))) {                    
                                echo "<p style='color:red'>Customer ID $customer_id has invalid instant_renewal value.</p>";
                                $pass = 0;
                            }
                            if (!in_array($blacklist, array("Yes","No",""))) {                    
                                echo "<p style='color:red'>Customer ID $customer_id has invalid blacklist value.</p>";
                                $pass = 0;
                            }
                            if (!in_array($risk_grade, array("R1","R2","R3",""))) {
                                echo "<p style='color:red'>Customer ID $customer_id has invalid risk_grade value.</p>";
                                $pass = 0;
                             } 
                            if (!preg_match("/\d+(\.\d+)*/", $eligible_amount)) {
                                echo "<p style='color:red'>Customer ID $customer_id has invalid eligible_amount value.</p>";
                                $pass = 0;
                            }
                            if ($pass == 1) {
                                $query = "INSERT into RenewalMonthlyData 
                                    (customer_id,instant_renewal,eligible_amount,risk_grade,blacklist,created_by,modified_user_id,deleted)
                                    values 
                                    ('$customer_id','$instant_renewal','$eligible_amount','$risk_grade','$blacklist','$current_user->id','$current_user->id',0)
                                    ON DUPLICATE KEY UPDATE
                                    customer_id = VALUES(customer_id),
                                    instant_renewal = VALUES(instant_renewal),
                                    eligible_amount = VALUES(eligible_amount),
                                    risk_grade = VALUES(risk_grade),
                                    blacklist = VALUES(blacklist),
                                    modified_user_id = VALUES(modified_user_id),
                                    deleted = VALUES(deleted)";
                                $result = $db->query($query);
                                if($result){
                                    echo "<p>Added/Updated Customer ID $customer_id successfully.</p>";
                                }else{
                                    $failed_ids[] = $customer_id;
                                    echo "<p style='color:red'>Failed for Customer ID $customer_id. Contact IT team</p>";
                                    var_dump($result);
                                }
                            }
                        }
                        echo "<br>";
                    }
                }
            }else{
                echo "<p style='color:red'>Please choose a file</p>";
            }
        } // End of upload form submit     
    }
}


