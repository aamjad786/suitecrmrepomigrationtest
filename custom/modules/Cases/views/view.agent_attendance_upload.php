<?php
if(!defined('sugarEntry')) define('sugarEntry', true);

ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once('custom/include/SendEmail.php');
require_once('modules/EmailTemplates/EmailTemplate.php');

class CasesViewAgent_attendance_upload extends SugarView {
    
    private $log;
    function __construct() {
        parent::SugarView();
        $this->log = fopen("Logs/CaseAgentAttendance.log", "a");
        $this->form_message = "";
        $this->file_message = "";
    }
    function __destruct() {
        fclose($this->log);
    }
    function checkAccess(){
        global $current_user;
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $roles = ACLRole::getUserRoleNames($current_user->id);
        if (strpos($url, 'crm.advancesuite.in') !== false) {
            $permitted_users = array("NG377", "NG855", "NG950", "NG1007", "NG660", "NG894","NG478","NG866","NG1647","NG2029","NG2054","NG2064");
            if (!$current_user->is_admin && !in_array($current_user->user_name, $permitted_users)) {
                die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
            }
        }
    }

    function getCustomerSupportAgents(){
        global $db;
        $query = "
            SELECT u.id as 'id' FROM users u
            LEFT JOIN acl_roles_users aru ON aru.user_id = u.id
            LEFT JOIN acl_roles au ON aru.role_id = au.id
            WHERE au.name = 'Customer support executive Assignment'
            ";
        $results = $db->query($query);
        $user_id_list = array();
        while($row = $db->fetchByAssoc($results)){
            array_push($user_id_list, "'" . $row['id'] . "'");
        }
        $user_id_list = implode(",", $user_id_list);
        // print_r($user_id_list);
        $users1 = BeanFactory::getBean('Users');
        $query1 = "users.deleted=0 and users.id in ($user_id_list)";
        $items = $users1->get_full_list('',$query1);
        // print_r($query1);
        $cs_users_options = "";
        if ($items){
            foreach ($items as $item) {
                $userBean = new User();
                $userBean->retrieve($item->id);
                $item = $userBean;
                $id = $item->id;
                $name = $item->first_name." ".$item->last_name;
                $department = $item->department;
                $email = $item->email1;
                $user_name = $item->user_name;
                // var_dump($row);
                if(true || $department=='Customer Support'){
                    $cs_users_options .= "<option value='$user_name'> $name, $user_name, $department</option>";
                }
            }   
        }
        return $cs_users_options;
    }

    function displayForm(){
        $cs_users_options = $this->getCustomerSupportAgents();
        $env = getenv('SCRM_SITE_URL');
        echo $html = <<<HTMLFORM
        <h1>Monthly File Upload - Agent Attendance</h1><br/>
        <form action="" id="uploadForm1" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="sheet">Select file name to upload :</label>
                <p><b>Note:</b> Only spreadsheets(.xlsx) are accepted. Use the sample file to upload data</p><br>
                <input type="file" id="sheet" name="sheet" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/> 
            </div>
            <input type="submit" value="Upload" name="upload"><br/><br/>
        </form>
        <a href="attendance_format_July_2018.xlsx">Download Sample file</a><br/>
        <br/>
        <h1>Status</h1>
HTMLFORM;
    }
    function displayForm2(){
        echo $html = <<<HTMLFORM_2
        <hr/>
        <h1>Agent Attendance Update Form</h1><br/>
        <span style="text-align: center; color:green" >
            <p id="csatdateRangeText"> </p>
        </span>
        <span style="float: left; margin-bottom: 40px;">
        <form action="" id="uploadForm2" method="post" enctype="multipart/form-data">
        <table style="border-collapse: separate; border-spacing: 15px;">
            <tr>
                <td>Customer Service Agent </td>
                <td><input list="cs_users" name="cs_users" placeholder='Select Agent' required/>
                <datalist id="cs_users">$cs_users_options</datalist></td>
            </tr>
            <tr>
                <td>Leave Period</td>
                <td><input type="text" name="datefilter" id="date_range_picker" value="" placeholder="Date Filter" required/></td>
            </tr>
            <tr>
                <td><input type="submit" value="submit" name="submit_form"></td>
            <tr>
        </table>
        <form>
        <br>
        <h1>Status</h1>
        <div id ='form_message'></div>
        <br>
        </span>
        <script type="text/javascript" src="custom/include/js/jquery.min.js"></script>
        <script type="text/javascript" src="custom/include/js/moment.min.js"></script>
      
        <script type="text/javascript" src="custom/include/js/jquery-3.3.1.js"></script>
        <script type="text/javascript" src=custom/include/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="custom/include/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="custom/include/js/buttons.flash.min.js"></script>
        <script type="text/javascript" src="custom/include/js/jszip.min.js"></script>
        <script type="text/javascript" src="custom/include/js/pdfmake.min.js"></script>
        <script type="text/javascript" src="custom/include/js/vfs_fonts.js"></script>
        <script type="text/javascript" src="custom/include/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="custom/include/js/buttons.print.min.js"></script>
        <script type="text/javascript" src="custom/include/js/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="custom/include/css/daterangepicker.css"/>
        <link rel="stylesheet" type="text/css" href="custom/include/css/dataTables.min.css"/>
        <link rel="stylesheet" type="text/css" href="custom/include/css/buttons.dataTables.min.css"/>
       
        <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel'
                ]
            } );
        } );
           
            $(function () {
                $('input[name="datefilter"]').daterangepicker({
                    autoUpdateInput: false,
                    minDate: new Date(),
                    changeMonth: true, 
                    changeYear: false, 
                    dateFormat: 'Y-m-d',
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });
                $('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {
                        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    });
                $('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {
                        $(this).val('');
                    });
                $('input[name="datefilter"]').on('change.daterangepicker', function (ev, picker) {
                    console.log($(this).val());
                });
            });
            $(document).on('click','.data',function(){
                var id = $(this).attr('data-id');
                var r = confirm('Are you sure delete this record');
                if(r== true){
                  $.ajax({
                    url:"JavascriptAPICall.php?api=deleteAttandance",
                    type: "post",
                    data:{id:id},
                    success:function(result){
                        console.log(result);
                        if(result == 1){
                            location.reload();
                        } else {
                            alert("Something went wrong");
                        }
                    }
                  });
                }
            });
        </script>
HTMLFORM_2;
    }
    function handleFileUpload(){
        global $current_user;
        global $db;    
        fwrite($this->log, "\n-------------agent_attendance_upload::handleUploadedFile start ".date('Y-m-d H:i:s')."---------------\n");
        if (isset($_POST["upload"])) {
            require_once 'XLSXReader.php';
            $target_dir = "upload/";
            $errors = array();

            if (!empty($_FILES["sheet"])) {
                fwrite($this->log, "\n" . "Uploaded File Details : " . print_r($_FILES,true));
                $file = $_FILES["sheet"]["tmp_name"];
                $file_name = $current_user->user_name . "_" . time() . "_" . $_FILES['sheet']['name'];
                $file_size = $_FILES['sheet']['size'];
                $file_tmp = $_FILES['sheet']['tmp_name'];
                $file_type = $_FILES['sheet']['type'];
                $file_ext = strtolower(end(explode('.', $_FILES['sheet']['name'])));

                if (!empty($file_name)) {
                    $extensions = array("xlsx");
                    if (in_array($file_ext, $extensions) === false) {
                        $errors[] = "Extension not allowed, please choose an xlsx file. ($file_ext is not supported)";
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
                    fwrite($this->log, "\n" . "Target File : " . $target_file);
                    try {
                        $xlsx = new XLSXReader($target_file);
                        $sheet = $xlsx->getSheet('Sheet1');
                        $this->array2Table($sheet->getData());
                        // header("Refresh:0");
                    } catch (Exception $e) {
                        fwrite($this->log, "\n" . "Attendance file upload exception :: " . print_r($e->getMessage(),true));
                        $this->file_message .= "<p style='color:red'>Error Occured. Please contact Tech Support</p>";
                    }
                }
            }//if(!empty($_FILES["sheet"]['name']))
        }//end if(isset($_POST["upload"]))
    }

    function getDate($excel_date){
        $unix_date = "";
        $unix_date = ($excel_date - 25569) * 86400;
        $php_date = gmdate("Y-m-d", $unix_date);
        if(empty($php_date)){
            echo "<p style='color:red'>Error Occured. Invalid Date Format. Date found in file :: $excel_date</p>";
        }
        return $php_date;
    }

    function fetchUserId($user_id){
        global $db;
        $query = "select id from users where user_name = '$user_id'";
        // echo $query . "<br>";
        $res = $db->query($query);
        $row = $db->fetchByAssoc($res);
        return $row['id'];
    }

    function convertHeaderDates($row){
        $header = array();
        foreach ($row as $i => $col) {
            if($i<5){
                $header[$i] = $col;
                continue;
            }
            $formatted_date = $this->getDate($col);
            // echo "formatted_date : $formatted_date<br>";
            if(!in_array($formatted_date, $duplicate_date_array)){
                $header[$i] = $formatted_date;
            }
            else{
                $this->file_message .= "<p style='color:red'>Failed to parse file. Duplicate Dates found. $date</p>";
                return array();
            }
        }
        //if no dates are found
        if(sizeof($header)<7){
            $this->file_message .= "<p style='color:red'>No date columns present</p>";
            return array();
        }

        return $header;
    }

    // row 0 is header, which contains following contents
    // [0] => Sr. No. [1] => Emp-ID [2] => Name [3] => Reporting To [4] => DOJ 
    // [5] => From this postion is date
    function array2Table($data) {
        if (!empty($data)) {
            $header = array();
            $agent_leave_list_total = array();
            $return = "";
            foreach ($data as $i => $row) {
                if($i == 0){
                    $header = $this->convertHeaderDates($row);
                    if(empty($header)){
                        //if no dates are found or error
                        return;
                    }
                }
                else{
                    $user_id = $this->fetchUserId($row[1]);
                    if(empty($user_id)){
                        $this->file_message .= "<p style='color:red'>Failed to insert agent attendance record for Emp-ID :: $row[1]. User not found</p>";
                        continue;
                    }
                    foreach ($row as $i => $col) {
                        // echo "buildAgent : $i<br>";
                        if($i<5 || ($col != 'L' && $col != 'P' && $col != 'l' && $col != 'p')){
                            // echo ", $col <br>";
                            continue;
                        }
                        $col = strtoupper($col);
                        $date = $header[$i];
                        // echo "date : $header[$i] = $date<br>";
                        $agent = "";
                        $agent = "(" 
                            . "UUID(),"
                            . "'" . $user_id . "',"
                            . "'" . $date . "',"
                            . "'" . $col . "',"
                            . " NOW(), NOW()" 
                            .")";                    
                        // print_r($agent);echo "<br>";     
                        array_push($agent_leave_list_total, $agent);
                    }
                }
                // print_r("$i =>");
                // print_r($row);
                // echo"<hr>";
            }
            if(!empty($agent_leave_list_total)){
                $this->file_message .= $this->insertIntoDb($agent_leave_list_total);
            }
            else{
                fwrite($myfile, "\n"."Failed to parse agent attendance records");
                $this->file_message .= "<p style='color:red'>Failed to insert agent attendance records</p>";    
            }
        }
    }

    function insertIntoDb($agent_leave_list_total){
        global $db;
        $insert_data_list = "";
        $insert_data_list   = implode(",", $agent_leave_list_total);
        $return_message = "";
        // print_r($insert_data_list);echo "<br>";     
        $query  = "
            INSERT INTO cases_agents_attendance (id, user_id, attendance_date, attendance_status, date_created, date_modified) 
            VALUES $insert_data_list
            ";
        // print_r($query);
        // die();
        fwrite($this->log, "\n" . "File Upload insert query : " . $query);
        $results = $db->query($query);
        if($results){
            fwrite($myfile, "\n"."Successfully inserted agent attendance records");
            $return_message .= "<p style='color:green'>Successfully inserted agent attendance records</p>";
        } 
        else{
            fwrite($myfile, "\n"."Failed to insert agent attendance records in db.");
            $return_message .= "<p style='color:red'>Failed to insert agent attendance records</p>";    
        }
        return $return_message;
    }

    function handleFormUpload(){
        if (isset($_POST["submit_form"])) {
            $this->form_message = "";
            fwrite($this->log, "\n-------------agent_attendance_upload::handleFormUpload start ".date('Y-m-d H:i:s')."---------------\n");
            fwrite($this->log, "\n" . "Uploaded Field Details : " . print_r($_REQUEST,true));
            if (!empty($_REQUEST['datefilter']) && !empty($_REQUEST['cs_users'])) {
                $dateRange = $_REQUEST['datefilter'];
                $extractedDate = explode('-', $dateRange);
                $fromDate = date("Y-m-d", strtotime($extractedDate[0]));
                $toDate = date("Y-m-d", strtotime($extractedDate[1]));
            } 
            else{
                $this->form_message .=  "<p style='color:red'>Agent and date selection is mandatory, kindly select both and submit.</p>";
                return;
            }
            $agent_leave_list_total = array();
            $user_id = $this->fetchUserId($_REQUEST['cs_users']);
            if(empty($user_id)){
                $this->form_message .=  "<p style='color:red'>Failed to insert agent attendance record for Emp-ID :: $row[1]. User not found. Please Contact Tech Support.</p>";
            }
            $startDateTime = new DateTime($fromDate);
            $endDateTime = new DateTime($toDate);
            //adding one more day to include end date
            $endDateTime->modify('+1 day');
            $period = new DatePeriod( $startDateTime, new DateInterval('P1D'), $endDateTime);
            foreach ($period as $date) {
                $date = $date->format("Y-m-d");
                $agent = "";
                $agent = "(" 
                    . "UUID(),"
                    . "'" . $user_id . "',"
                    . "'" . $date . "',"
                    . "'" . 'L' . "',"
                    . " NOW(), NOW()"
                    . ")"; 
                array_push($agent_leave_list_total, $agent);
            }
            if(!empty($agent_leave_list_total)){
                $this->form_message .= $this->insertIntoDb($agent_leave_list_total);
            }
            else{
                fwrite($myfile, "\n"."Failed to insert agent attendance records");
                $this->form_message .=  "<p style='color:red'>Failed to insert agent attendance records</p>";    
            }
        }
        global $db;
        $date = date('Y-m-d', strtotime('-7 days'));
        $query = "select ca.id, attendance_date , attendance_status, date_created 
        , CONCAT(first_name,' ',last_name) as name  from cases_agents_attendance ca left join users u on u. id = ca.user_id
       where attendance_date >= '".$date."'";
      
        $res = $db->query($query);
       
        echo '<table id="myTable" class="display" style="width:140%">
        <thead>
            <tr>
                <th style="text-align: left;">S.No</th>
                <th style="text-align: left;">Name</th>
                <th style="text-align: left;">Attendance Date</th>
                <th style="text-align: left;">Attendance Status</th>
                <th style="text-align: left;">Date Created</th>
                <th style="text-align: left;">Action</th>
            </tr>
        </thead>
        <tbody>';
      
        $i = 1;
        
        while($row = $db->fetchByAssoc($res)){
            //print_r($row);exit;
           echo "<tr>";
           echo   "<td>".$i++."</td>";
           echo   "<td>".ucwords($row['name'])."</td>";
           echo   "<td>".date('d-m-Y',strtotime($row['attendance_date']))."</td>";
           echo   "<td>".$row['attendance_status']."</td>";
           echo   "<td>".date('d-m-Y',strtotime($row['date_created']))."</td>";
        
           echo   "<td><input type='submit' class='data' data-id='".$row['id']."' value='Delete' name='delete'></td>";
           echo "</tr>";
        }
      echo  '</tbody>
    </table>';
    }

    function display() {
       
        $this->checkAccess();
        $this->displayForm();
        $this->handleFileUpload();
        echo $this->file_message;
        $this->displayForm2();
        $this->handleFormUpload();
        echo $this->form_message;
    }


}
?>

