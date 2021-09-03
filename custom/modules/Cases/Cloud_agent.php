<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);

global $db;
global $current_user;

echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';
$header_style = "style=\"width:30%;background-color:black; padding: 10px;color:white;\"";
$td_style = "style=\"width:30%;border: 1px solid #000;padding: 10px !important;\"";

echo $html = <<<HTMLFORM1
		<h1><center><b>Customers under Agent - $current_user->first_name</b></center></h1>
		<br>
HTMLFORM1;

$query = "SELECT * FROM AgentPRIMapping where agent_id = '$current_user->user_name' and deleted=0";
$result = $db->query($query);

echo $HTML_Data_header = <<<HTML_Data_header
		<div id="mainData">
			<div id="DataHeader">
			<table cellpadding="0" cellspacing="0"  border="1">
			<tr  height="25">
			<th  $header_style><label>Applicant Name</lable></th>
			<th  $header_style><label>Mobile</lable></th>
			<th  $header_style><label>Bucket</lable></th>
			</tr>
HTML_Data_header;
while ($row = $db->fetchByAssoc($result)) {
    echo "<tr>";
    echo "<td $td_style>" . $row['applicant_name'] . "</td>";
    echo "<td $td_style>" . $row['mobile'] . "</td>";
    echo "<td $td_style>" . $row['bucket'] . "</td>";
    echo "</tr>";
}
echo "
			</table>
			</div>
		</div>";
require_once('custom/modules/Cases/Cases_functions.php');
$casesCommonFunctions = new Cases_functions();
$authentication = $casesCommonFunctions->casesAuthentication();

if (!$authentication) {
    die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
} else {
        echo $html = <<<HTMLFORM2
			<h1><center><b>PRI Integration</b></center></h1>
			<br>
			<form action="#" method='post' enctype= 'multipart/form-data'>
				<p><b>Note:</b> Only spreadsheets are accepted. Please ensure that the sheet in xls format</p>
				<table>
				<br>
				<tr>
					<td>File: </td>
					<td><input type="file" name="sheet" id="sheet" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"></td>
				</tr>
				<tr>
					<td></td><td colspan="1">
					<input type='submit' value='Upload' id='file_upload' name='file_upload'/></td>
					<td></td><td colspan="1">
					<input type='submit' value='Download (Last Uploaded File)' id='file_export' name='file_export'/></td>
				</tr>
			</table>
	    	</form>
			<br>
			<h5>Status</h5>
			<br>
HTMLFORM2;

        $export_data = array();
        $query = "SELECT * FROM AgentPRIMapping where deleted=0 order by date_entered";
        $result = $db->query($query);
        while ($row = $db->fetchByAssoc($result)) {
            $data = array();

            if (!empty($row['mobile'])) {
                array_push($data, $row['applicant_name']);
                array_push($data, $row['mobile']);
                array_push($data, $row['agent_name']);
                array_push($data, $row['agent_id']);
                array_push($data, $row['bucket']);
                array_push($data, $row['date_entered']);
                array_push($export_data, $data);
            }
        }

        if (!empty($_POST['file_upload'])) {
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
                $applicant_name_row = array_search('Applicant Name', $column_list);
                $mobile_row = array_search('mobile', $column_list);
                $agent_name_row = array_search('Service Manager', $column_list);
                $agent_id_row = array_search('Login ID', $column_list);
                $bucket_row = array_search('bucket', $column_list);
                $date_entered_row = array_search('date entered', $column_list);
                $total_count = 0;
                $q = "UPDATE AgentPRIMapping set deleted = 1 where 1";
                $r = $db->query($q);
                for ($i = 2; $i < $length; $i++) {
                    $total_count++;
                    $applicant_name = $data->sheets[0]['cells'][$i][$applicant_name_row];
                    $mobile = $data->sheets[0]['cells'][$i][$mobile_row];
                    $agent_id = $data->sheets[0]['cells'][$i][$agent_id_row];
                    $agent_name = $data->sheets[0]['cells'][$i][$agent_name_row];
                    $bucket = $data->sheets[0]['cells'][$i][$bucket_row];
                    $date_entered = $data->sheets[0]['cells'][$i][$date_entered_row];
                    if (!empty($mobile)) {
                        $query = "INSERT into AgentPRIMapping 
								(mobile,applicant_name,agent_id,agent_name,bucket,created_by,modified_user_id,deleted)
								values 
								('$mobile','$applicant_name','$agent_id','$agent_name','$bucket','$current_user->id','$current_user->id',0)
								ON DUPLICATE KEY UPDATE
	  							applicant_name = VALUES(applicant_name),
	  							agent_id = VALUES(agent_id),
	  							agent_name = VALUES(agent_name),
							  	bucket = VALUES(bucket),
							  	modified_user_id = VALUES(modified_user_id),
							  	deleted = VALUES(deleted)";
                        $result = $db->query($query);
                        if ($result) {
                            echo "<p>Mapped $applicant_name($mobile) to $agent_name($agent_id) successfully.</p>";
                        } else {
                            echo "<p style='color:red'>Mapping failed for $applicant_name($mobile) and $agent_name($agent_id). Contact IT team</p>";
                        }
                    }
                }
            } else {
                echo "<p style='color:red'>Please choose a file</p>";
            }
        } // file upload
        if (!empty($_POST['file_export'])) {
            $timestamp = date('Y_m_d_His');
            ob_end_clean();
            ob_start();
            // output headers so that the file is downloaded rather than displayed
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=MappingPhoneToAgent$timestamp.csv");

            // create a file pointer connected to the output stream
            $output = fopen('php://output', 'w');
            // output the column headings
            fputcsv($output, array('Applicant Name', 'mobile', 'Service Manager', 'Login ID', 'bucket', 'date entered'));

            foreach ($export_data as $row_data) {
                fputcsv($output, $row_data);
            }
            exit;
        }
    } // PRI
//}
