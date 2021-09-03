<h1>Document Requests</h1>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
 <script type="text/javascript">
	
    $(document).ready(function() {
	    $('#output').DataTable( {
	        dom: 'Bfrtip',
	        buttons: [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ]
	    } );
	} );

 </script>	

<?php
$document_master = array(1=>"Loan statement",2=>'Interest Certificate',3=>'Repayment Schedule', 4=>'Sanction Letter',6=>'Welcome Letter',7=>'Loan Agreement');
if($full_view){
?>


<form id='dateform' name='dateform' action="<?= $_SERVER[REQUEST_URI]; ?>" method="post">
<b>From Date:</b><input type='date' id='fromdate' name='fromdate'/>
<br/><br/>
<b>To Date:</b><input type='date' id='todate' name='todate' />
<input type="submit" value="submit" id='submit' name='submit'/>
</form>
<br/><br/><br/><br/>

<?php
}

function getStatus($refer){
	$url = getenv('AWS_API_UTILITY_URL')."/documents/$refer";
	require_once('CurlReq.php');
	$curl = new CurlReq();
	$res = $curl->curl_req($url);
	$decoded_res = json_decode($res);
	$status = $decoded_res->status;
	$ans = "unknown";
	if(in_array($status, array('initiated','requested','uploaded'))){
		$ans = 'Under process';
	}else if($status=='rejected'){
		$ans = 'Request rejected by Reportal';
	}else if($status=='sent'){
		$ans = "Sent and Delivered";
	}
	return $ans;
}

if(!empty($_REQUEST['submit'])){
	// var_dump($_REQUEST);
	$fromdate = $_REQUEST['fromdate'];
	$todate = $_REQUEST['todate'];
	
	$query = "select * from statement_tracker where date_entered>='$fromdate' and date_entered<'$todate' order by date_entered desc";
}else{
	$query = "select * from statement_tracker where app_id='$app_id' order by date_entered desc";
}
	global $db;
	$results = $db->query($query);

	if($results){
		?>
		
		 <table id = 'output'  style='width:100%'>
	    <thead>
	        <tr>
	        	<th>Sent on</th>
	        	<th>Sent By</th>
				<th>Document Type</th>
				<th>Department</th>
				<th>Status</th>
				<th>App Id</th>
				<th>Establishment</th>
	        </tr>
	    </thead>
	    <tbody>
	    	<?php
		 while (($row = $db->fetchByAssoc($results)) != null) {
		 	$doc_type = $row['doc_type'];
		 	$user_name = $row['user_name'];
		 	$query2 = "select first_name,last_name,department,user_name from users where user_name='$user_name'";
		 	$user_res = $db->query($query2);
		 	while (($user_row = $db->fetchByAssoc($user_res)) != null) {
		 		// var_dump($user_row);
		 		$user_full_name = $user_row['first_name']." ".$user_row['last_name'];
		 		$user_department = $user_row['department'];
		 	}
		    echo "
		    <tr>
		    <td>".date('Y-m-d H:i:s', strtotime('+5 hours 30 minutes', strtotime($row['date_entered'])))."</td>
		    	<td>$user_full_name</td>
		    	
		    	<td>".$document_master[$doc_type]."</td>
		    	<td>$user_department</td>";
		    // if(!empty($row['status'])){
		    	echo "<td>".($row['status'])."</td>";
		    // }
		    // else if(!empty($row['status'])){
		    // 	echo "<td>".($row['status'])."</td>";
		    // }else{
		    // 	echo "<td>Unknown</td>";
		    // }
		    echo 
		    	"<td>".$row['app_id']."</td>
		    	<td>".$row['establishment']."</td>
		    </tr>";

		}
		echo "</table><br/>";
	}
