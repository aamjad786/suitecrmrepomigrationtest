<?php
if(!defined('sugarEntry')) define('sugarEntry', true);

ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once('custom/include/SendEmail.php');
require_once('modules/EmailTemplates/EmailTemplate.php');

class CasesViewSocial_impact_score_upload extends SugarView {
    
    private $log;
    function __construct() {
        parent::SugarView();
        $this->log = fopen("Logs/SocialImpactScore.log", "a");
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
            $permitted_users = array("NG377", "NG855", "NG950", "NG1007", "NG660", "NG894","NG478","NG866","NG1647","NG2029");
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
        <h1>Social Impact Score Upload</h1><br/>
        <form action="" id="uploadForm1" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="sheet">Select file name to upload :</label>
                <p><b>Note:</b> Only spreadsheets(.xlsx) are accepted. Use the sample file to upload data</p><br>
                <input type="file" id="sheet" name="sheet" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/> 
            </div>
            <input type="submit" value="Upload" name="upload"><br/><br/>
        </form>
        <a href="Social_Impact_Closed_deals.xlsx">Download Sample file</a><br/>
        <br/>
        <h1>Status</h1>
HTMLFORM;
    }
    function displayForm2(){
        echo $html = <<<HTMLFORM_2
       
        <br>
        
        <div id ='form_message'></div>
        <br>
        </span>
        <script type="text/javascript" src="custom/include/js/jquery.min.js"></script>
        <script type="text/javascript" src="custom/include/js/moment.min.js"></script>
        <script type="text/javascript" src="custom/include/js/daterangepicker.min.js"></script>
        <script type="text/javascript" src="custom/include/js/jquery-3.3.1.js"></script>
        <script type="text/javascript" src="custom/include/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="custom/include/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="custom/include/js/buttons.flash.min.js"></script>
        <script type="text/javascript" src="custom/include/js/jszip.min.js"></script>
        <script type="text/javascript" src="custom/include/js/pdfmake.min.js"></script>
        <script type="text/javascript" src="custom/include/js/vfs_fonts.js"></script>
        <script type="text/javascript" src="custom/include/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="custom/include/js/buttons.print.min.js"></script>
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
                       // echo "<pre>";print_r($sheet);exit;
                        $this->array2Table($sheet->getData());
                        
                    } catch (Exception $e) {
                        print_r($e);exit;
                        fwrite($this->log, "\n" . "Attendance file upload exception :: " . print_r($e->getMessage(),true));
                        $this->file_message .= "<p style='color:red'>Error Occured. Please contact Tech Support</p>";
                    }
                }
            } 
        }
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

 
    function array2Table($data) {

        if (!empty($data)) {
            $header = array();
            $appId = array();
            $social_impact_score = array();
            $return = "";
            foreach ($data as $i => $row) {
                
                if($i > 0 ){    

                    $agent = "";
                    $agent = "(" 
                        . "'" . $row[0] . "',"
                        . "'" . $row[1] . "',"
                        . "'" . $row[2] . "',"
                        . "'" . (!empty($row[4])?$row[4]:0). "',"
                        . "'" . (!empty($row[5])?$row[5]:0) . "',"
                        . "'" . $this->getDate($row[3]) . "',"
                        . "'" . (!empty($row[4])?1:0) . "',"
                        . " NOW(), NOW()" 
                        .")";                    
                    array_push($appId,$row[0]);
                    array_push($social_impact_score, $agent);
               }
            }
          
    //    echo "<pre>";print_r($social_impact_score);exit;
            if(!empty($social_impact_score)){
              
                $this->file_message .= $this->insertIntoDb($social_impact_score,$appId);
            }
            else{
                
                fwrite($myfile, "\n"."Failed to parse agent attendance records");
                $this->file_message .= "<p style='color:red'>Failed to insert agent attendance records</p>";    
            }
        }
    }

    function insertIntoDb($social_impact_score,$appId){
        global $db;
        $app_id = implode (", ", $appId);

        $update_query = "
        UPDATE social_impact_score SET deleted = 1 WHERE as_app_id IN ($app_id)";
       
        $result = $db->query($update_query);
       
        $insert_data_list = "";
        $insert_data_list   = implode(",", $social_impact_score);
        $return_message = "";
          
        $query  = "
            INSERT INTO social_impact_score (as_app_id, merchant_name, merchant_contact_number, total_number_of_staff, total_number_of_female_staff,case_closed_date,called,creation_date,update_date) 
            VALUES $insert_data_list
            ";

        fwrite($this->log, "\n" . "File Upload insert query : " . $query);
        $results = $db->query($query);
        if($results){
            fwrite($myfile, "\n"."Successfully inserted Social impact score records");
            $return_message .= "<p style='color:green'>Successfully inserted Social impact score records</p>";
        } 
        else{
            fwrite($myfile, "\n"."Failed to insert Social impact score records in db.");
            $return_message .= "<p style='color:red'>Failed to insert Social impact score records</p>";    
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

