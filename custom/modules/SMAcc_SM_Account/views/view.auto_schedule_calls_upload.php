<?php
//type = msg
//ND = days
//Name = app id
/**
 *  for 'report_to_apimap' check ozone tell campaign page reports, headers in that table are 'map' to be passed
 */
class SMAcc_SM_AccountViewAuto_schedule_calls_upload extends SugarView {

    private $log;
    private $report_to_apimap;
    private $report_type;
    private $report_to_ivr_type;

    function __construct() {
        parent::SugarView();
        $this->log = fopen("Logs/AutoScheduleCallsUpload.log", "a");
        $this->report_type = "";
        $this->report_to_apimap = array();
        $this->report_to_apimap['ach_new'] = array('PhoneNumber', 'type', 'Priority', 'ExpiryDate');
        $this->report_to_apimap['chq_nach_bounce'] = array('PhoneNumber','type', 'Priority','ExpiryDate', 'LoanNumber', 'LoanRupees');
        $this->report_to_apimap['delay_days'] = array('PhoneNumber', 'type', 'Priority', 'ExpiryDate', 'NoDays');
        $this->report_to_apimap['neo_pos_ims_ach'] = array('PhoneNumber', 'type', 'Priority', 'ExpiryDate');

        // This mapping is important, 
        // if changed in ozonetel needs to get updated here. This decides which ivr call customer gets
        $this->report_to_ivr_type = array();
        $this->report_to_ivr_type[] = 
        $this->report_to_ivr_type['ach_new'] = "ACH_Cases_New";
        $this->report_to_ivr_type['chq_nach_bounce'] = "Cheque_NACH_Bounce_MSG";
        $this->report_to_ivr_type['delay_days'] = "DELAY_OF_DAYS_MSG";
        $this->report_to_ivr_type['neo_pos_ims_ach'] = "Neopos_IMS_cases_New";

    }
    function __destruct() {
        fclose($this->log);
    }

    function displayForm(){
        echo $html = <<<HTMLFORM
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
                <script>
                $(function() {
                    var start = moment(new Date()).add(1,'days');
                    start.set({hour:0,minute:0,second:0,millisecond:0})
                    start.toISOString()
                    start.format()
                    var end = moment(new Date()).add(1,'days');
                    end.set({hour:23,minute:59,second:59,millisecond:0})
                    end.toISOString()
                    end.format()
                  $('input[name="time_to_send"]').daterangepicker({
                    "singleDatePicker": true,
                    "timePicker": true,
                    "startDate": moment(new Date()).add(1,'days'),
                    "endDate": end,
                    "minDate": start,
                    "maxDate": end,
                    locale: {
                      format: 'YYYY-MM-DD   hh:mm A'
                    }
                  });
                });
                </script>
        <h1>File Upload - Auto Schedule Calls - Ozonetel</h1><br/>
        <form action="" id="uploadForm1" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="sheet">Select file name to upload :</label>
                <p><b>Note:</b> Only spreadsheets(.xlsx) are accepted. Use the sample file to upload data</p><br>
                <input type="file" id="sheet" name="sheet" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/><p style='color:blue'>**Split the report if more than 200 phone numbers present**</p> 
                <br>
                <label for="report">Report Name:</label>
                <select id = 'report' name = 'report' required>
                	<option selected disabled>--Select Campaign--</option>
                	<option value="ach_new">ACH NEW</option>
                	<option value="chq_nach_bounce">CHEQUE & NACH BOUNCE</option>
                	<option value="delay_days">DELAY OF DAYS</option>
                	<option value="neo_pos_ims_ach">NEO POS AND IMS ACH</option>
                <select> <br><br>
                <label for="time_to_send">Select file name to upload :</label>
                <input type="text" id="time_to_send" name="time_to_send" required/>
            </div>
            <input type="submit" value="Upload" name="upload"><br/><br/>
        </form>
        <a href="ozontel_ach_new.xlsx">Download Sample file - ACH NEW</a><br/>
        <a href="ozontel_chq_nach_bounce.xlsx">Download Sample file - CHEQUE & NACH BOUNCE</a><br/>
        <a href="ozontel_delay_days.xlsx">Download Sample file - DELAY OF DAYS</a><br/>
        <a href="ozontel_neo_pos_ims_ach.xlsx">Download Sample file - NEO POS AND IMS ACH</a><br/>
        <br/>
        <h1>Status</h1>
HTMLFORM;
    }

    function printError($errors){
        echo "<p style='color:red'>File upload failed please try again. If problem persists contact IT team.</p>";
        for ($i = 0; $i < sizeof($errors); $i++) {
            echo "<p style='color:red'>$errors[0]</p>";
        }
        fwrite($this->log, "\n" . "File upload errors :: " . print_r($errors,true));
    }

    function handleFileUpload(){
        global $current_user;
        global $db;    
        fwrite($this->log, "\n-------------Auto_schedule_calls_upload::handleUploadedFile start ".date('Y-m-d H:i:s')."---------------\n");
        if (isset($_POST["upload"])) {
            require_once 'XLSXReader.php';
            $target_dir = "upload/";
            $errors = array();
            if (!empty($_FILES["sheet"])) {
                fwrite($this->log, "\n" . "Uploaded File Details : " . print_r($_FILES,true));
                $file = $_FILES["sheet"]["tmp_name"];
                $this->report_type = $_REQUEST['report'];
                $time_to_send = $_REQUEST['time_to_send'];
                $file_name = $current_user->user_name . "_" . time() . "_" . $_FILES['sheet']['name'];
                $file_size = $_FILES['sheet']['size'];
                $file_tmp = $_FILES['sheet']['tmp_name'];
                $file_type = $_FILES['sheet']['type'];
                $file_ext = strtolower(end(explode('.', $_FILES['sheet']['name'])));

                if (!empty($file_name)) {
                    if(empty($this->report_type)){
                        $errors[] = "Report doesnt exist. Kindly select the Report type.";
                    }
                    if(empty($time_to_send)){
                        $errors[] = "Time to send it empty. Kindly select the time period to send.";
                    }
                    $extensions = array("xlsx");
                    if (in_array($file_ext, $extensions) === false) {
                        $errors[] = "Extension not allowed, please choose an xlsx file. ($file_ext is not supported)";
                    }

                    if ($file_size > 2097152) {
                        $errors[] = 'File size must be less 2 MB';
                    }

                    if (empty($errors)) {
                        move_uploaded_file($file_tmp, $target_dir . $file_name);
                    } 
                    else {
                        $this->printError($errors);
                    }
                    $target_file = $target_dir . basename($file_name);
                    fwrite($this->log, "\n" . "Target File : " . $target_file);
                    try {
                        $xlsx = new XLSXReader($target_file);
                        $sheet = $xlsx->getSheet('Sheet1');
                        $json_body = "";
                        $json_body = $this->array2Table($sheet->getData(), $time_to_send);
                        if(!empty($json_body)){
                            $file_save_status = $this->save($file_name, $json_body, $time_to_send);
                        }
                        if($file_save_status){
                            echo "<p style='color:green'>File Uploaded Successfully</p>";
                        }
                        // header("Refresh:0");
                    } catch (Exception $e) {
                        fwrite($this->log, "\n" . "File upload exception :: " . print_r($e->getMessage(),true));
                        $this->file_message .= "<p style='color:red'>Error Occured. Please contact Tech Support</p>";
                    }
                }
            }//if(!empty($_FILES["sheet"]['name']))
        }//end if(isset($_POST["upload"]))
    }
    function save($file_name, $json_body, $time_to_send){
        $errors = [];
        global $db;
        $save_response = true;
        $newDate = date("Y-m-d H:i:s", strtotime($time_to_send));
        fwrite($this->log, "\n" . "time_to_send : " . $time_to_send);
        fwrite($this->log, "\n" . "newDate : " . $newDate);
        if(!empty($newDate)){
            $query = "
            INSERT INTO auto_schedule_calls
            (id, file_name, type, request_body, time_to_send, is_sent, date_entered ,date_modified)
            VALUES (UUID(), '$file_name', '$this->report_type', '$json_body', '$newDate', 0, NOW(), NOW());
            ";
            $result = $db->query($query);
            if($result){
                fwrite($this->log,"\n" .  "Insert to db: Success");
            }
            else{
                $errors[] = "File Upload Failed.";
                fwrite($this->log,"\n" .  "Insert to db: Failed");
                fwrite($this->log, "\n" . print_r($query,true));
            }
        }
        else{
            fwrite($this->log,"\n" .  "Time to sent - date format failed.");
            $errors[] = "File Upload Failed. Time Convertion Failed";
        }
        if(!empty($errors)){
            $this->printError($errors);
            $save_response = false;
        }
        return $save_response;
    }
    function array2Table($excel_data, $time_to_send) {
        $errors = [];
    	// print_r($excel_data);
        // print_r($this->report_type);
    	// die();
        $newDate = date("Y-m-d H:i:s", strtotime($time_to_send . ' +1 day'));
        fwrite($this->log, "\n" . "time_to_send : " . $time_to_send);
        fwrite($this->log, "\n" . "Expire date (1 day plus time to send)  : " . $newDate);
    	$params = array();
    	$params['map'] = $this->report_to_apimap[$this->report_type];
    	$params['data'] = array();
        $ivr_type = $this->report_to_ivr_type[$this->report_type];
        $priority = 1;
        if (!empty($excel_data)) {
            foreach ($excel_data as $index => $row) {
                if($index == 0){
                    continue;  
                } 
                $data = array();
                // echo "$index =>"; print_r($row); echo "<br>";
                if(!preg_match('/^[0-9]{10,}/', $row[0])) {
                    $errors[] = "Row ". ($index+1) .": Invalid Phone Number";
                }
                else{
                    array_push($data, $row[0]);
                }
                if(empty($row[1]) || $this->report_type != $row[1]){
                    $errors[] = "Wrong report uploaded. Kindly check report name inside the sheet";
                    break;
                }
                array_push($data, $ivr_type);
                array_push($data, $priority++);
                array_push($data, $newDate);
                
                if($this->report_type == 'delay_days'){
                    if(!preg_match('/^[0-9]/', $row[2])){
                        $errors[] = "Row ". ($index+1) .": Invalid Days";
                    }
                    else{
                        array_push($data, $row[2]);
                    }
                }
                elseif ($this->report_type == 'chq_nach_bounce') {
                    if(!preg_match('/^[0-9]{7}/', $row[2])){
                        $errors[] = "Row ". ($index+1) .": Invalid Application ID";
                    }
                    else{
                        array_push($data, $row[2]);
                    }
                    if(empty($row[3])){
                        $errors[] = "Row ". ($index+1) .": Empty Amount";
                    }
                    else{
                        array_push($data, $row[3]);
                    }                  
                }
                elseif ($this->report_type == 'ach_new' || $this->report_type == 'neo_pos_ims_ach') {
                    // pass
                }
                else{
                    //should not come here. Safe side
                    $errors[] = "Invalid report selected.";
                    break;
                }
                array_push($params['data'], $data);
            }
        }
        $encoded_params = "";
        $encoded_params = base64_encode(serialize($params));
        if($priority>=201){
            $errors[] = "# $priority of rows are uploaded. Max allowed is 200. Kindly split and upload";
        }
        if(!empty($errors) || empty($encoded_params)){
            //if no error message, the encoded params is empty
            $this->printError($errors);
            $encoded_params = "";
        }
        // print_r($encoded_params);
        // echo "<br>";
        // print_r(base64_decode($encoded_params));echo"<br>";
        // print_r(unserialize(base64_decode($encoded_params)));echo"<br>";
        // die('<br>here done');
        return $encoded_params;
    }

	function display(){
        global $current_user;
		$this->displayForm();
		$this->handleFileUpload();
	}
}

?>