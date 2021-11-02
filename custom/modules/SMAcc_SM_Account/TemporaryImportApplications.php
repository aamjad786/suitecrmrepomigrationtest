<html>
    <center><h1 style="color:#3C8DBC"><b> Temporary Importing Service Manager Applications</b></h1></center>
    <center><p style="color:red">Please be careful while uploading. Do not import the sheet twice, as there is no if exists check.</p></center></br></br>
    <body>
        <div id ="temporary_import">
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="sheet">Select file name to upload :</label>
                    <p><b>Note:</b> Only spreadsheets(.xls or .csv) are accepted. Use the sample file to upload data</p><br>
                    <input type="file" name="sheet" id="fileToUpload">
                    <br/><br/>
                    <input type='submit' value='Submit' id='excel_upload' name='excel_upload'/><br/><br/>
                </div>
            </form>
        </div>
    </body>
</html>
<?php
error_reporting(0);
if (!defined('sugarEntry'))
    define('sugarEntry', true);
global $db;

if (!is_admin($GLOBALS['current_user'])) {
    ?>
    <script>
        $('#temporary_import').hide();
    </script>
    <?php
    echo "You do not have access, please contact admin";
    die();
}

if (!empty($_POST['excel_upload'])) {
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
            $errors[] = "Extension not allowed, please choose an xls or csv file.";
        }
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less 2 MB';
        }

        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "upload/" . $file_name);
        } else {
            print_r($errors);
        }
        $target_file = $target_dir . basename($_FILES["sheet"]["name"]);
        $data = new Spreadsheet_Excel_Reader($target_file);
        echo "<br>";
        $column_list = $data->sheets[0]['cells'][1];
        $length = sizeof($data->sheets[0]['cells']);

        $app_id_row = array_search('App ID', $column_list);
        $merchent_name_row = array_search('Merchant Name', $column_list);
        $branch_row = array_search('Branch', $column_list);
        $region_row = array_search('Region', $column_list);
        $funded_date_row = array_search('Funded Date', $column_list);
        $customer_id_row = array_search('CM ID', $column_list);
        $team_row = array_search('Team', $column_list);
        $login_id_row = array_search('Login ID', $column_list);
        $status_row = array_search('Short Synopsis on latest actions taken', $column_list);

        $total_count = 0;
        $updated_count = 0;
        for ($i = 2; $i <= $length; $i++) {
            $total_count++;
            $app_id = $data->sheets[0]['cells'][$i][$app_id_row];
            $merchent_name = $data->sheets[0]['cells'][$i][$merchent_name_row];
            $branch = $data->sheets[0]['cells'][$i][$branch_row];
            $region = $data->sheets[0]['cells'][$i][$region_row];
            $funded_date = $data->sheets[0]['cells'][$i][$funded_date_row];
            $customer_id = $data->sheets[0]['cells'][$i][$customer_id_row];
            $team = $data->sheets[0]['cells'][$i][$team_row];
            $login_id = $data->sheets[0]['cells'][$i][$login_id_row];
            $status = $data->sheets[0]['cells'][$i][$status_row];
            $userId = '';

            $getUserQuery = "SELECT id from users where user_name = '$login_id'";
            $userData = $db->query($getUserQuery);
            while ($row = $db->fetchByAssoc($userData)) {
                if (!empty($row['id'])) {
                    $userId = $row['id'];
                }
            }
            $smAccountBean = BeanFactory::newBean('SMAcc_SM_Account');
            $smAccountBean->assigned_user_id = $userId;
            $smAccountBean->app_id = $app_id;
            $smAccountBean->merchant_name = $merchent_name;
            $smAccountBean->branch = $branch;
            $smAccountBean->region = $region;
            $smAccountBean->deleted = 0;
            $smAccountBean->customer_id = $customer_id;
            $smAccountBean->team = $team;
            $smAccountBean->status = $status;
            $smAccountBean->save();
        }
    } else {
        echo "<p style='color:red'>Please choose a file</p>";
    }
}
?>