<?php
if(!defined('sugarEntry')) define('sugarEntry', true);

ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once('custom/include/SendEmail.php');;
require_once('modules/EmailTemplates/EmailTemplate.php');

class Neo_CustomersVieweligibility_upload extends SugarView {
    
    private $chartV;

    function __construct(){    
        parent::SugarView();
    }

    function display() {
        global $current_user;
        global $db;
    
        echo $html = <<<HTMLFORM
        <h1>Eligibility Upload</h1><br/>
        <form action="" id="uploadForm3" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload3">Select file name to upload :</label>
                <input type="file" id="fileToUpload3" name="fileToUpload3"/>
            </div>
            <input type="submit" value="Upload" name="monthlyFileUpload"><br/><br/>
        </form>
        <a href="?module=Neo_Customers&action=downloadSample">Download Sample file</a><br/>
        <p>Note: After changing sample file save it in xls format before uploading.</p>
        <br/>
        <hr/>
        <h1>Response</h1>
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
                // if(in_array($file_ext,$extensions) === false){
                //     $errors[]="Extension not allowed, please choose an xls file. ($file_ext is not supported)";
                // }

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
                // var_dump($data->sheets[0]);
                $column_list = $data->sheets[0]['cells'][2];
                $length = sizeof($data->sheets[0]['cells']) + 1;
                $customer_id_row = array_search('Customer_ID', $column_list);
                $half_paid_up_row = array_search("50% paid-up", $column_list);
                $blacklisted_row = array_search('Blacklist', $column_list);
                $ever_30_dpd_row = array_search("30 DPD'", $column_list);
                $scheme_row = array_search("Scheme", $column_list);
                // $blacklist_row = array_search('Credit Reject / Collections blacklist', $column_list);
                $total_count=0;
                $failed_ids = array();
                $yes_no = array("Y","N");
                $scheme_array=array("Renewal - Auto Selection","");
                $r = $db->query($q);
                for ($i = 3; $i < $length+1 ; $i++) {
                    $total_count++;
                    $customer_id = $data->sheets[0]['cells'][$i][$customer_id_row];
                    $half_paid_up = $data->sheets[0]['cells'][$i][$half_paid_up_row];
                    $blacklist = $data->sheets[0]['cells'][$i][$blacklisted_row];
                    $ever_30_dpd = $data->sheets[0]['cells'][$i][$ever_30_dpd_row];
                    $scheme = $data->sheets[0]['cells'][$i][$scheme_row];
                    $scheme=trim($scheme);
                    if (!empty($customer_id)) {
                        $pass = 1;
                        // $q = "SELECT count(*) count from neo_customers where customer_id = '$customer_id'";
                        $bean = BeanFactory::getBean('Neo_Customers')->get_full_list('',"customer_id=$customer_id");
                        $bean = $bean[0];
                        // var_dump($bean[0]->date_entered);
                        // $r = $db->query($q);
                        // $c = 0;
                        // while ($e_row = $db->fetchByAssoc($r)) {
                        //     $c = $e_row['count'];
                        // }
                        if (empty($bean->customer_id)){
                            echo "<p style='color:red'>Customer ID $customer_id does not exist.</p>";
                            $pass = 0;
                        }else{
                            if (!in_array($blacklist,$yes_no)) {                    
                                echo "<p style='color:red'>Customer ID $customer_id has invalid blacklist value.</p>";
                                $pass = 0;
                            }
                            if (!in_array($half_paid_up, $yes_no)) {
                                echo "<p style='color:red'>Customer ID $customer_id has invalid 50% paid-up value.</p>";
                                $pass = 0;
                             } 
                             if (!in_array($ever_30_dpd, $yes_no)) {
                                echo "<p style='color:red'>Customer ID $customer_id has invalid 30 DPD' value.</p>";
                                $pass = 0;
                             }
                             if(!in_array($scheme, $scheme_array))
                             {
                                echo "<p style='color:red'>Customer ID $customer_id has invalid scheme name. Only 'Renewal - Auto Selection' or (blank) value acceptable. </p>";
                                $pass = 0;
                             }
                            
                            if ($pass == 1) {
                                $is_eligible = 0;
                                // var_dump($blacklist);
                                // var_dump($ever_30_dpd);
                                // var_dump($half_paid_up);
                                if($blacklist=='N' && $ever_30_dpd=='N' && $half_paid_up=='Y'){
                                    $is_eligible = 1;

                                }
                                if($is_eligible){
                                    $bean->renewal_eligible = 1;
                                    // $query = "update neo_customers set renewal_eligible=1 where customer_id='$customer_id'";
                                    echo "<p style='color:green'>Customer ID $customer_id is eligible</p>";
                                    
                                }else{
                                    $bean->renewal_eligible = 0;
                                    // $query = "update neo_customers set renewal_eligible=0 where customer_id='$customer_id'";
                                    echo "<p style='color:red'>Customer ID $customer_id not eligible</p>";
                                }
                                $bean->scheme=$scheme;
                                // $result = $db->query($query);
                                $bean->save();
                                // if($result){
                                //     echo "<p>Updated successfully.</p>";
                                // }else{
                                //     $failed_ids[] = $customer_id;
                                //     echo "<p style='color:red'>Failed for Customer ID $customer_id. Contact IT team</p>";
                                //     // var_dump($result);
                                // }
                                
                            }
                        }
                       
                    }
                }
                 echo "<br/>Updated successfully<br>";
            }else{
                echo "<p style='color:red'>Please choose a file</p>";
            }
        } // End of upload form submit     
    }
}


