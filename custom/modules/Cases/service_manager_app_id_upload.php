<?php

$environment = getenv('SCRM_SITE_URL');
require_once('custom/modules/Cases/Cases_functions.php');
$casesCommonFunctions = new Cases_functions();
$authentication = $casesCommonFunctions->casesAuthentication();
if (!$authentication) {
    die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
}
?>
<center><h1 style="color:#3C8DBC; margin-bottom: 40px; margin-top:20px"><b>Service Manager - App mapping upload</b></h1></center>

<form action="" id="uploadForm" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="sheet">Select file to upload:</label>
        <p><b>Note:</b> Only spreadsheets(.xls) are accepted. Use the sample file to upload data</p><br>
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

        require_once 'excel_reader2.php';
        $target_dir = "upload/";
        $errors = array();
        $file_name = $_FILES['sheet']['name'];
        $file_size = $_FILES['sheet']['size'];
        $file_tmp = $_FILES['sheet']['tmp_name'];
        $file_type = $_FILES['sheet']['type'];
        $file_ext = strtolower(end(explode('.', $_FILES['sheet']['name'])));

        if (!empty($file_name)) {
            $extensions = array("csv", "xls");
            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "Extension not allowed, please choose an xls file. ($file_ext is not supported)";
            }

            if ($file_size > 2097152) {
                $errors[] = 'File size must be less 2 MB';
            }

            if (empty($errors)) {
                move_uploaded_file($file_tmp, "upload/" . $file_name);
            } else {
                for ($i = 0; $i < sizeof($errors); $i++) {
                    echo "<p>$errors[0]</p>";
                }
            }
            $target_file = $target_dir . basename($_FILES["sheet"]["name"]);
            $data = new Spreadsheet_Excel_Reader($target_file);
            $column_list = $data->sheets[0]['cells'][1];
            $length = sizeof($data->sheets[0]['cells']) + 1;
            $applicantIdRow = array_search('App ID', $column_list);
            $serviceManagerRow = array_search('Service Manager', $column_list);
            for ($i = 2; $i < $length; $i++) {
                $total_count++;
                $applicantId = $data->sheets[0]['cells'][$i][$applicantIdRow];
                $serviceManager = $data->sheets[0]['cells'][$i][$serviceManagerRow];
                $queryToGetUserId = "SELECT id from users where user_name = '$serviceManager'";
                $userResult = $db->query($queryToGetUserId);
                while ($row = $db->fetchByAssoc($userResult)) {
                    $userId = $row['id'];
                    if (!empty($userId)) {
                        $serviceManagerAccount = BeanFactory::getBean('SMAcc_SM_Account');
                        $serviceManagerAccount->assigned_user_id = $userId;
                        $query = "smacc_sm_account.app_id = '$applicantId'";
                        $items = $serviceManagerAccount->get_full_list('',$query);
                        if(!empty($items)){
                            $beanId = $items[0]->id;
                            $serviceManagerAccount->id = $beanId; 
                        } else {
                            $serviceManagerAccount->app_id = $applicantId; //Creating new application
                        }
                        $newBean = $serviceManagerAccount->save();
                        global $current_user;
                        $uploadedUser =  $current_user->id; 
                        date_default_timezone_set("IST");
                        $currentTimeStamp =  date("Y-m-d H:i:s", time());
                        $mappingQuery = "INSERT INTO application_service_manager_mapping (application_id, service_manager_id, created_by, date_entered) VALUES ('$applicantId', '$newBean', '$uploadedUser', '$currentTimeStamp')";
                        $queryResponse = $db->query($mappingQuery);
                        
                    }
                }
            }
        }
        echo "Service managers have been upladed successfully";
    }//if(!empty($_FILES["sheet"]['name']))
}//end if(isset($_POST["submit"]))
?>


