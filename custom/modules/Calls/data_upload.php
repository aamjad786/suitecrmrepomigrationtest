
<center><h1 style="color:#3C8DBC; margin-bottom: 40px; margin-top:20px"><b>Call data upload</b></h1></center>

<form action="" id="uploadForm" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="sheet">Select file to upload:</label>
        <p><b>Note:</b> Only spreadsheets(.xls) are accepted. Use the sample file to upload data</p><br>
        <input type="file" id="sheet" name="sheet" required accept=".csv,.xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/> 
    </div>
    <input type="submit" value="Submit" name="submit"><br/><br/>
</form>

<?php
$myfile = fopen("Logs/call_data_upload.log",'a');
global $db;
if (!defined('sugarEntry'))
    define('sugarEntry', true);

require_once('include/entryPoint.php');

ini_set('max_execution_time', 300000);

$count = 0;

if (isset($_POST["submit"])) {

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
        $total_count=0;
        fwrite($myfile, "file uploaded $file_name\n");
        if (!empty($file_name)) {
            $extensions = array("csv", "xls", "xlsx");
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
            $assignedToRow = array_search('Assigned to', $column_list);
            $requestedDateRow= array_search('requested date', $column_list);
            $contactNumberRow = array_search('Contact Number', $column_list);
            $callDateRow = array_search('Call date', $column_list);
            $appIdRow = array_search('app id', $column_list);
            $refundAmountRow = array_search('Refund Amount', $column_list);
            $ifscCodeRow = array_search('IFSC Code', $column_list);
            $bankAccountNumberRow = array_search('Bank account number', $column_list);
            $typeOfCallRow = array_search('Type of Call', $column_list);
            $callType = "";
            
            
            for ($i = 2; $i < $length; $i++) {
                $total_count++;
             
                $assignedTo = $data->sheets[0]['cells'][$i][$assignedToRow];
                $contactNumber = $data->sheets[0]['cells'][$i][$contactNumberRow];
                $callDate =  Date('Y-m-d H:i:s', strtotime($data->sheets[0]['cells'][$i][$callDateRow]));
                $app_id = $data->sheets[0]['cells'][$i][$appIdRow];
                $refundAmount = $data->sheets[0]['cells'][$i][$refundAmountRow];
                $ifscCode = $data->sheets[0]['cells'][$i][$ifscCodeRow];
                $bankAccountNumber = $data->sheets[0]['cells'][$i][$bankAccountNumberRow];
                $typeOfCall = $data->sheets[0]['cells'][$i][$typeOfCallRow];
                $requestedDate=$data->sheets[0]['cells'][$i][$requestedDateRow];

                $callType = $typeOfCall;
                $typeOfCall = $GLOBALS['app_list_strings']['calls_type_list'][$callType];
                /*$arr = array_flip($GLOBALS['app_list_strings']['calls_type_list']);
                $callType = '';
                if(array_key_exists($typeOfCall, $arr)){
                    $callType = $arr[$typeOfCall];
                }*/
                fwrite($myfile,"callType=$callType, typeOfCall = $typeOfCall\n");

                $userId = "";
                if(!empty($assignedTo)){

                    $queryToGetUserId = "SELECT id from users where user_name = '$assignedTo'";
                    $userResult = $db->query($queryToGetUserId);
                    while ($row = $db->fetchByAssoc($userResult)) {
                        $userId = $row['id'];
                    }
                }
               
               

                if(!empty($contactNumber) || !empty($app_id)){

                    $queryCalls = 'SELECT id from calls 
                    left join calls_cstm on calls.parent_id = calls.id
                
                    where calls_cstm.contact_number_c ="'.$contactNumber.'" and date(date_entered) ="'.date('Y-m-d',strtotime($callDate)).'" and deleted =0';
                   
                    $callResult = $db->query($queryCalls);

                    $rows = $db->fetchByAssoc($callResult);
                    
                    if(!empty($rows) && count($rows)>=1){
                        $total_count--;

                    }else{
                        
                        $call = BeanFactory::getBean('Calls');

                        fwrite($myfile, "ContactNumber = $contactNumber, app_id=$app_id\n");

                        $call->name = "$app_id_c $contactNumber - $typeOfCall";
                        $call->contact_number_c = $contactNumber;
                        $call->assigned_user_id = "$userId";
                        $call->calls_type_c = "$callType";
                        $call->date_entered = $callDate;
                        $call->date_start = date('Y-m-d H:i:s');
                        $call->app_id_c = "$app_id";
                        $call->refund_amount_c = "$refundAmount";
                        $call->ifsc_code_c = "$ifscCode";
                        $call->repeat_type = "unprocessed";
                        $call->account_number_c = "$bankAccountNumber";
                        $call->date_requested_c =$requestedDate;
                        $call->status = "Planned";
                        $call->save();
                    }
                }
            }
        }
        echo "$total_count records added successfully";
    }
}

?>


