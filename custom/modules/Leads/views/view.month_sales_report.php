<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/entryPoint.php');
require_once('include/MVC/View/SugarView.php');

// if($is_admin || ($current_user->department == 'SALES')) {
// 	echo $html = <<<SCRIPT_ACCESS
// 		<a target="_blank" style="text-decoration: none" href="?module=Leads&action=Month_sales_report"><span style ="font-family: Arial;font-size:14px;" ><b>1. Performance Report - Monthly</b></span></a>
// SCRIPT_ACCESS;
// 	}
class LeadsViewmonth_sales_report extends SugarView {

	var $month;
	var $requested_month;
    function __construct(){    
        parent::SugarView();
        $this->month = date("F-Y");

    }

	function getUserBean($user_id){
        $bean = BeanFactory::getBean('Users');
	    $query = 'users.deleted=0 and users.id = "'.$user_id.'"';
        // die($query);
	    $items = $bean->get_full_list('',$query);
        //var_dump($items[0]->id);
	    if(!empty($items)){
            return $items[0];
        }
        return null;
    }


    function display(){
    	// var_dump($_REQUEST['alt_startDate']);//die();
		$from_date  = date("Y-m-01 00:00:00");
		$requested_month = "";
    	if(!empty($_REQUEST['alt_startDate'])){
    		$requested_month = date('F',strtotime($_REQUEST['alt_startDate']));
    		$from_date 		= date("Y-m-d H:i:s", strtotime($_REQUEST['alt_startDate']));
    		$this->month 	= date("F-Y", strtotime($_REQUEST['alt_startDate']));
    	}else{
    		$requested_month =  date("F");
    	}
    	// var_dump($requested_month);
    	$this->requested_month = $requested_month;
		// print_r($from_date);echo "<br>";
		$to_date    = date("Y-m-d H:i:s", strtotime("+1 month", strtotime($from_date)));
		$from_date  = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($from_date)));
		$to_date    = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($to_date)));
		
		// echo $this->month;
        echo $html = <<<SCRIPT
        <link rel="stylesheet" type="text/css" href="custom/include/css/dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="custom/include/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="custom/include/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">


        <script type="text/javascript" charset="utf8" src="custom/include/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="custom/include/js/dataTables.fixedColumns.min.js"></script>
        <script type="text/javascript" charset="utf8" src="custom/include/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" charset="utf8" src="custom/include/js/buttons.colVis.min.js"></script>
        
        <script type="text/javascript" src="custom/include/js/moment.min.js"></script>
        <script type="text/javascript" src="custom/include/js/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="custom/include/css/daterangepicker.css" />


        <script>
            $(document).ready( function () {
		        // SUM PLUGIN
		        jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
		            return this.flatten().reduce( function ( a, b ) {
		                if ( typeof a === 'string' ) {
		                    a = a.replace(/[^\d.-]/g, '') * 1;
		                }
		                if ( typeof b === 'string' ) {
		                    b = b.replace(/[^\d.-]/g, '') * 1;
		                }

		                return a + b;
		            }, 0 );
		        } );
                $('#monthly_performance_table').DataTable({
					    "columnDefs": [
					      { className: "dt-right", "targets": [3, 4, 5, 6, 7, 8, 9, 10] },
					      { className: "dt-nowrap", "targets": [1, 2] },
					      { className: "dt-center", "targets": [0] }
					    ],
			            footerCallback: function () {
			                var api = this.api(),
			                    columns = [3, 4, 5, 6, 7, 8, 9, 10]; // Add columns here

			                for (var i = 0; i < columns.length; i++) {
			                    $('#monthly_performance_table tfoot th').eq(columns[i]).html((api.column(columns[i], {filter: 'applied'}).data().sum()).toLocaleString('en-IN') + '<br>');
			                    // $('tfoot th').eq(columns[i]).append('Page: ' + api.column(columns[i], { filter: 'applied', page: 'current' }).data().sum());
			                }
			            }

                    });
                    $('#monthly_performance_table1').DataTable({
					    "columnDefs": [
					      { className: "dt-right", "targets": [3, 4, 5, 6, 7, 8, 9, 10] },
					      { className: "dt-nowrap", "targets": [1, 2] },
					      { className: "dt-center", "targets": [0] }
					    ],
			            footerCallback: function () {
			                var api = this.api(),
			                    columns = [3, 4, 5, 6, 7, 8, 9, 10]; // Add columns here

			                for (var i = 0; i < columns.length; i++) {
			                    $('#monthly_performance_table1 tfoot th').eq(columns[i]).html((api.column(columns[i], {filter: 'applied'}).data().sum()).toLocaleString('en-IN') + '<br>');
			                    // $('tfoot th').eq(columns[i]).append('Page: ' + api.column(columns[i], { filter: 'applied', page: 'current' }).data().sum());
			                }
			            }
                    });
                $('#lead_status_report_table').DataTable({
    					"columnDefs": [
					      { className: "dt-right", "targets": [3, 4, 5, 6, 7, 8] },

					      { className: "dt-nowrap", "targets": [1,2] },
					      { className: "dt-center", "targets": [0] }
					    ],
			            footerCallback: function () {
			                var api = this.api(),

			                    columns = [3, 4, 5, 6, 7, 8]; // Add columns here


			                for (var i = 0; i < columns.length; i++) {
			                    $('#lead_status_report_table tfoot th').eq(columns[i]).html((api.column(columns[i], {filter: 'applied'}).data().sum()).toLocaleString('en-IN') + '<br>');
			                }
			            }
                	});
	         	// $('#lead_source_report_table').DataTable({});
	            $('#dsa_performance_report_table').DataTable({
    					"columnDefs": [

					      { className: "dt-right", "targets": [3, 4, 5, 6, 7, 8, 9, 10, 11] },

					      { className: "dt-nowrap", "targets": [1,2] },
					      { className: "dt-center", "targets": [0] }
					    ],
			            footerCallback: function () {
			                var api = this.api(),

			                    columns = [3, 4, 5, 6, 7, 8, 9, 10, 11]; // Add columns here


			                for (var i = 0; i < columns.length; i++) {
			                    $('#dsa_performance_report_table tfoot th').eq(columns[i]).html((api.column(columns[i], {filter: 'applied'}).data().sum()).toLocaleString('en-IN') + '<br>');
			                }
			            }
	            	});

			    $('.date-picker').datepicker( {
			        changeMonth: true,
			        changeYear: true,
			        showButtonPanel: true,
			        dateFormat: 'MM yy',
			        onClose: function(dateText, inst) { 
			            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
			        },
			        altFormat: "yy-mm-dd",
			        altField: "#alt_startDate"
			    });
            });
        </script>
	    <style>
		    table{
		        border: 1px solid black;
		    }
		.ui-datepicker-calendar {
		    display: none;
		    }
	    </style>
    <body>
    	<form action="" id="uploadForm1" method="post" enctype="multipart/form-data">
    		<label for="startDate">Date :</label>
    		<input type='text' name="startDate" id="startDate" class="date-picker" readonly='true'> <br>
    		<input type="hidden" id="alt_startDate" name="alt_startDate" />
    		<input type="submit" value="Generate Reports" name="Submit"><br/><br/>
    	</form>
SCRIPT;
    	global $current_user;
    	$user_id = $current_user->id;
    	if(!empty($_REQUEST['user_id'])){
    		$user_id = $_REQUEST['user_id'];
    		// echo $_REQUEST['user_id'];
    	}
    	$user = $this->getUserBean($user_id);
    	echo "<p>$user->user_name - $user->full_name <br> $user->designation</p>";
    	global $db;
    	$query = "
    		SELECT 
    			u.id AS 'id',
    			u.user_name AS 'user_name',
    			u.first_name AS 'first_name',
    			u.last_name AS 'last_name',
    			u.designation AS 'designation',
    			concat(mu.first_name, ' ', mu.last_name) AS 'reports_to_name',

    			FORMAT(sth.target,0,'en_IN') AS 'target',
    			FORMAT(sth.sales_target,0,'en_IN') AS 'sales_targert',
    			FORMAT(sth.achieved,0,'en_IN') AS 'achieved',
    			FORMAT(sth.target_amount_achieved,0,'en_IN') AS 'target_amount_achieved',
    			FORMAT(sth.cases_sanctioned,0,'en_IN') as 'cases_sanctioned',
    			FORMAT(sth.cases_sanctioned_amount,0,'en_IN') AS 'cases_sanctioned_amount',
    			FORMAT(sth.cases_wip,0,'en_IN') AS 'cases_wip',
    			FORMAT(sth.cases_wip_amount,0,'en_IN') AS 'cases_wip_amount',

    			FORMAT(sth.cases_picked_up,0,'en_IN') AS 'cases_picked_up',
    			FORMAT(sth.cases_attended,0,'en_IN') AS 'cases_attended',
    			FORMAT(sth.cases_logged_in,0,'en_IN') AS 'cases_logged_in',
    			FORMAT(sth.cases_dropped,0,'en_IN') AS 'cases_dropped',

    			sth.lead_source AS 'lead_source',
    			FORMAT(sth.cases_picked_up,0,'en_IN') AS 'cases_picked_up',
    			FORMAT(sth.dsa_cases_logged_in,0,'en_IN') AS 'dsa_cases_logged_in',
    			FORMAT(sth.dsa_cases_sanctioned,0,'en_IN') AS 'dsa_cases_sanctioned',
    			FORMAT(sth.dsa_cases_disbursed,0,'en_IN') AS 'dsa_cases_disbursed',

    			FORMAT(sth.no_of_dsa_assigned,0,'en_IN') AS 'no_of_dsa_assigned',
    			FORMAT(sth.active_dsa,0,'en_IN') AS 'active_dsa',
    			FORMAT(sth.dsa_cases_login_amount,0,'en_IN') AS 'dsa_cases_login_amount',
    			FORMAT(sth.dsa_cases_sanctioned,0,'en_IN') AS 'dsa_cases_sanctioned',
    			FORMAT(sth.dsa_cases_sanctioned_amount,0,'en_IN') AS 'dsa_cases_sanctioned_amount',
    			FORMAT(sth.dsa_cases_disbursed_amount,0,'en_IN') AS 'dsa_cases_disbursed_amount'

    		FROM users u
    		LEFT JOIN users mu ON mu.id = u.reports_to_id
    		LEFT JOIN scrm_targets_history sth ON sth.user_profile_id = u.id
    		WHERE u.reports_to_id = '$user_id'
    		AND sth.month = '$requested_month'
    		AND sth.target_amount_achieved > 0
    		AND u.deleted = 0
    	";

    	// print_r($query); echo "<br>";
    	$results = $db->query($query);
    	$results_array = array();
    	while($row = $db->fetchByAssoc($results)){
    		array_push($results_array, $row);
    	}
    	$results_array1 = array();
    	$query="SELECT 
    			u.id AS 'id',
    			u.user_name AS 'user_name',
    			u.first_name AS 'first_name',
    			u.last_name AS 'last_name',
    			u.designation AS 'designation',
    			concat(u.first_name, ' ', u.last_name) AS 'reports_to_name',

    			FORMAT(sth.target,0,'en_IN') AS 'target',
    			FORMAT(sth.sales_target,0,'en_IN') AS 'sales_targert',
    			FORMAT(sth.achieved,0,'en_IN') AS 'achieved',
    			FORMAT(sth.target_amount_achieved,0,'en_IN') AS 'target_amount_achieved',
    			FORMAT(sth.cases_sanctioned,0,'en_IN') as 'cases_sanctioned',
    			FORMAT(sth.cases_sanctioned_amount,0,'en_IN') AS 'cases_sanctioned_amount',
    			FORMAT(sth.cases_wip,0,'en_IN') AS 'cases_wip',
    			FORMAT(sth.cases_wip_amount,0,'en_IN') AS 'cases_wip_amount',

    			FORMAT(sth.cases_picked_up,0,'en_IN') AS 'cases_picked_up',
    			FORMAT(sth.cases_attended,0,'en_IN') AS 'cases_attended',
    			FORMAT(sth.cases_logged_in,0,'en_IN') AS 'cases_logged_in',
    			FORMAT(sth.cases_dropped,0,'en_IN') AS 'cases_dropped',

    			sth.lead_source AS 'lead_source',
    			FORMAT(sth.cases_picked_up,0,'en_IN') AS 'cases_picked_up',
    			FORMAT(sth.dsa_cases_logged_in,0,'en_IN') AS 'dsa_cases_logged_in',
    			FORMAT(sth.dsa_cases_sanctioned,0,'en_IN') AS 'dsa_cases_sanctioned',
    			FORMAT(sth.dsa_cases_disbursed,0,'en_IN') AS 'dsa_cases_disbursed',

    			FORMAT(sth.no_of_dsa_assigned,0,'en_IN') AS 'no_of_dsa_assigned',
    			FORMAT(sth.active_dsa,0,'en_IN') AS 'active_dsa',
    			FORMAT(sth.dsa_cases_login_amount,0,'en_IN') AS 'dsa_cases_login_amount',
    			FORMAT(sth.dsa_cases_sanctioned,0,'en_IN') AS 'dsa_cases_sanctioned',
    			FORMAT(sth.dsa_cases_sanctioned_amount,0,'en_IN') AS 'dsa_cases_sanctioned_amount',
    			FORMAT(sth.dsa_cases_disbursed_amount,0,'en_IN') AS 'dsa_cases_disbursed_amount'

    		FROM users u
    		
    		LEFT JOIN scrm_targets_history sth on u.id=sth.user_profile_id where (sth.user_profile_id='1' or sth.description is null)
    		AND sth.user_type='CAM'
    		AND sth.target_amount_achieved > 0
    		AND sth.month = '$requested_month'";
    		// print_r($query); echo "<br>";
    	$results = $db->query($query);
    	// $results_array = array();
    	while($row = $db->fetchByAssoc($results)){
    		array_push($results_array1, $row);
    	}
    	// print_r($results); echo "<br>";
    	$this->display_monthly_performance_table($results_array);
    	$this->display_monthly_performance_table($results_array1,"monthly_performance_table1", 'unaccounted');
    	// $this->display_lead_status_report_table($results_array); 
    	// $this->display_lead_source_report_table($from_date, $to_date, $user_id); //display all types of lead source except empty
    	// $this->display_dsa_performance_report_table($results_array);

    }

    function display_dsa_performance_report_table($results){
        echo $HTML = <<<DSA_PERFORMANCE_REPORT_TABLE
            <hr>
            <h4>
                DSA Performance Report - For the month of $this->month
            </h4>
            <table id = 'dsa_performance_report_table' class='stripe row-border order-column' style='width:100%'>
	        <thead>
	            <tr>
	            	<th>Sno</th>
	                <th>User Name</th>
	                <th>NG ID</th>
	                
	                <th>Active DSA</th>
	                <th>Disbursed No.</th>
	                <th>Disbursed Amount</th>
	                <th>Sanctioned No.</th>
	                <th>Sanctioned Amount</th>
	                <th>Disbursed No.</th>
	                <th>Disbursed Amount</th>
	                <th>WIP No.</th>
	                <th>WIP Amount</th>

	            </tr>
	        </thead>
DSA_PERFORMANCE_REPORT_TABLE;
		$i=1;
	    foreach ($results as $row) {
	    	if(!empty($row['lead_source'])){
	    		continue;
	    	}
	    	echo "<tr>";
	    	//To avoid failure page
	    	$this_user_id = $user_id;
	    	$display_user_name = "-";
	    	$user_name = "-";

	    	$no_of_dsa_assigned = "0";
	    	$active_dsa = "0";
	    	$dsa_cases_logged_in = "0";
	    	$dsa_cases_login_amount = "0";
	    	$dsa_cases_sanctioned = "0";
	    	$dsa_cases_sanctioned_amount = "0";
	    	$dsa_cases_disbursed = "0";
	    	$dsa_cases_disbursed_amount = "0";
	    	$cases_wip = "0";
	    	$cases_wip_amount = "0";

	        if(!empty($row['id'])){
	        	$this_user_id = $row['id'];
	        }
	    	if(!empty($row['first_name']) && !empty($row['last_name'])){
	    		$display_user_name = $row['first_name'] . ' ' . $row['last_name'];
	    	}
	        if(!empty($row['user_name'])){
	        	$user_name = $row['user_name'];
	        }

	        if(!empty($row['no_of_dsa_assigned'])){
	        	$no_of_dsa_assigned = $row['no_of_dsa_assigned'];
	        }
	        if(!empty($row['active_dsa'])){
	        	$active_dsa = $row['active_dsa'];
	        }
	        if(!empty($row['dsa_cases_logged_in'])){
	        	$dsa_cases_logged_in = $row['dsa_cases_logged_in'];
	        }
	        if(!empty($row['dsa_cases_login_amount'])){
	        	$dsa_cases_login_amount = $row['dsa_cases_login_amount'];
	        }
	        if(!empty($row['dsa_cases_sanctioned'])){
	        	$dsa_cases_sanctioned = $row['dsa_cases_sanctioned'];
	        }
	        if(!empty($row['dsa_cases_sanctioned_amount'])){
	        	$dsa_cases_sanctioned_amount = $row['dsa_cases_sanctioned_amount'];
	        }
	        if(!empty($row['dsa_cases_disbursed'])){
	        	$dsa_cases_disbursed = $row['dsa_cases_disbursed'];
	        }
	        if(!empty($row['dsa_cases_disbursed_amount'])){
	        	$dsa_cases_disbursed_amount = $row['dsa_cases_disbursed_amount'];
	        }
	        if(!empty($row['cases_wip'])){
	        	$cases_wip = $row['cases_wip'];
	        }
	        if(!empty($row['cases_wip_amount'])){
	        	$cases_wip_amount = $row['cases_wip_amount'];
	        }


	        echo "<td>" . $i++ . "</td>";
	        echo "<td><a href='index.php?module=Leads&action=Month_sales_report&user_id=$this_user_id&alt_startDate=$this->requested_month' target='_blank'>$display_user_name</a></td>";
	        echo "<td><a href='index.php?module=Users&action=DetailView&record=$this_user_id' target='_blank'>$user_name
	        	</a></td>";
        	
        	echo "<td>$active_dsa</td>";
        	echo "<td>$dsa_cases_logged_in</td>";
        	echo "<td>$dsa_cases_login_amount</td>";
        	echo "<td>$dsa_cases_sanctioned</td>";
        	echo "<td>$dsa_cases_sanctioned_amount</td>";
        	echo "<td>$dsa_cases_disbursed</td>";
        	echo "<td>$dsa_cases_disbursed_amount</td>";
        	echo "<td>$cases_wip</td>";
        	echo "<td>$cases_wip_amount</td>";
	        echo "</tr>";

    	}
    	echo "  
    	<tfoot>
		    <tr>
		      <th>Total</th>
		      <th></th>
		      <th></th>
		      <th>x</th>
		      <th>x</th>

		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		    </tr>
  		</tfoot>
  		";
    	echo "</table>";
    }

    function display_lead_source_report_table($from_date, $to_date, $user_id){
    	global $db;
    	$query = "
    		SELECT 
    			u.id AS 'id',
    			u.user_name AS 'user_name',
    			u.first_name AS 'first_name',
    			u.last_name AS 'last_name',
    			u.designation AS 'designation',

    			sth.lead_source AS 'lead_source',
    			sth.cases_picked_up AS 'cases_picked_up',
    			sth.dsa_cases_logged_in AS 'dsa_cases_logged_in',
    			sth.dsa_cases_sanctioned AS 'dsa_cases_sanctioned',
    			sth.dsa_cases_disbursed AS 'dsa_cases_disbursed'

    		FROM users u
    		LEFT JOIN scrm_targets_history sth ON sth.user_profile_id = u.id
    		AND sth.date_entered >= '$from_date'
    		AND sth.date_entered <= '$to_date'

    		WHERE u.id = '$user_id'
    		AND u.deleted = 0
    		AND u.status = 'Active'
    	";

    	// print_r($query); echo "<br>";
    	$results = $db->query($query);
        echo $HTML = <<<LEAD_SOURCE_REPORT_TABLE
            <hr>
            <h4>
                Lead Source Report - For the month of $this->month
            </h4>
            <table id = 'lead_source_report_table' class='stripe row-border order-column' style='width:100%'>
	        <thead>
	            <tr>
	            	<th>Sno</th>
	                <th>Lead Source</th>
	                <th>Open</th>
	                <th>Logged In</th>
	                <th>Sanctioned</th>
	                <th>Disbursed</th>
	            </tr>
	        </thead>
LEAD_SOURCE_REPORT_TABLE;
		$i=1;
	    while($row = $db->fetchByAssoc($results)){
	    	if(empty($row['lead_source'])){
	    		continue;
	    	}
	    	echo "<tr>";

	    	$lead_source = "-";
	    	$cases_picked_up = "0";
	    	$dsa_cases_logged_in = "0";
	    	$dsa_cases_sanctioned = "0";
	    	$dsa_cases_disbursed = "0";

	        if(!empty($row['lead_source'])){
	        	$lead_source = $row['lead_source'];
	        }
	        if(!empty($row['cases_picked_up'])){
	        	$cases_picked_up = $row['cases_picked_up'];
	        }
	        if(!empty($row['dsa_cases_logged_in'])){
	        	$dsa_cases_logged_in = $row['dsa_cases_logged_in'];
	        }
	        if(!empty($row['dsa_cases_sanctioned'])){
	        	$dsa_cases_sanctioned = $row['dsa_cases_sanctioned'];
	        }
	        if(!empty($row['dsa_cases_disbursed'])){
	        	$dsa_cases_disbursed = $row['dsa_cases_disbursed'];
	        }


	        echo "<td>" . $i++ . "</td>";
        	echo "<td>$lead_source</td>";
        	echo "<td>$cases_picked_up</td>";
        	echo "<td>$dsa_cases_logged_in</td>";
        	echo "<td>$dsa_cases_sanctioned</td>";
        	echo "<td>$dsa_cases_disbursed</td>";
	        echo "</tr>";

    	}
    	echo "</table>";
    }

    function display_lead_status_report_table($results){
    	// echo "display_lead_status_report_table";
    	// print_r($results);
        echo $HTML = <<<LEAD_STATUS_REPORT_TABLE
            <hr>
            <h4>
                Lead Status Report - For the month of $this->month
            </h4>
            <table id = 'lead_status_report_table' class='stripe row-border order-column' style='width:100%'>
	        <thead>
	            <tr>
	            	<th>Sno</th>
	                <th>User Name</th>
	                <th>NG ID</th>
	                <th>Leads Assigned</th>
	                
	                <th>Logged in</th>
	                <th>Sanctioned</th>
	                <th>Disbursed</th>
	                <th>WIP</th>
	                <th>Dropped</th>
	            </tr>
	        </thead>
LEAD_STATUS_REPORT_TABLE;
		$i=1;
	    foreach ($results as $row) {
	    	if(!empty($row['lead_source'])){
	    		continue;
	    	}
	    		    	// echo "<br>".$i;
	    	echo "<tr>";
	    	//To avoid failure page
	    	$this_user_id = $user_id;
	    	$display_user_name = "-";
	    	$user_name = "-";

	    	$cases_picked_up = "0";
	    	$cases_attended = "0";
	    	$cases_logged_in = "0";
	    	$cases_sanctioned = "0";
	    	$achieved = "0";
	    	$cases_wip = "0";
	    	$cases_dropped = "0";

	        if(!empty($row['id'])){
	        	$this_user_id = $row['id'];
	        }
	    	if(!empty($row['first_name']) && !empty($row['last_name'])){
	    		$display_user_name = $row['first_name'] . ' ' . $row['last_name'];
	    	}
	        if(!empty($row['user_name'])){
	        	$user_name = $row['user_name'];
	        }

	        if(!empty($row['cases_picked_up'])){
	        	$cases_picked_up = $row['cases_picked_up'];
	        }
	        // if(!empty($row['cases_attended'])){
	        // 	$cases_attended = $row['cases_attended'];
	        // }
	        if(!empty($row['cases_logged_in'])){
	        	$cases_logged_in = $row['cases_logged_in'];
	        }
	        if(!empty($row['cases_sanctioned'])){
	        	$cases_sanctioned = $row['cases_sanctioned'];
	        }
	        if(!empty($row['achieved'])){
	        	$achieved = $row['achieved'];
	        }
	        if(!empty($row['cases_wip'])){
	        	$cases_wip = $row['cases_wip'];
	        }
	        if(!empty($row['cases_dropped'])){
	        	$cases_dropped = $row['cases_dropped'];
	        }
	        echo "<td>" . $i++ . "</td>";
	        echo "<td><a href='index.php?module=Leads&action=Month_sales_report&user_id=$this_user_id&alt_startDate=$this->requested_month' target='_blank'>$display_user_name</a></td>";
	        echo "<td><a href='index.php?module=Users&action=DetailView&record=$this_user_id' target='_blank'>$user_name
	        	</a></td>";
        	echo "<td>$cases_picked_up</td>";
        	// echo "<td>$cases_attended</td>";
        	echo "<td>$cases_logged_in</td>";
        	echo "<td>$cases_sanctioned</td>";
        	echo "<td>$achieved</td>";
        	echo "<td>$cases_wip</td>";
        	echo "<td>$cases_dropped</td>";
	        echo "</tr>";

    	}
    	echo "  
    	<tfoot>
		    <tr>
		      <th>Total</th>
		      <th></th>
		      <th></th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>

		    </tr>
  		</tfoot>
  		";
    	echo "</table>";
    }


    function display_monthly_performance_table($results,$id='monthly_performance_table',$unaccounted=""){
    	global $db;
    	if(empty($unaccounted)){
    		$unaccounted="";
    	}else{
    		$unaccounted = "(unaccounted)";
    	}
        echo $HTML = "
            <hr>
            <h4>
                Performance Report $unaccounted - For the month of $this->month
            </h4>
            <table id = '$id' class='stripe row-border order-column' style='width:100%'>
	        <thead>
	            <tr>
	            	<th>Sno</th>
	                <th>User Name</th>
	                <th>Designation</th>
	                <th>Target count</th>
	                <th>Actual Disbursed count</th>
	                <th>Target Value</th>
	                <th>Actual Disbursed Value</th>
	                <th>Sanctioned No</th>
	                <th>Sanctioned Value</th>
	                <th>WIP No.</th>
	                <th>WIP Value</th>
	            </tr>
	        </thead>";

		$i=1;
	    foreach ($results as $row) {
	    	if(!empty($row['lead_source'])){
	    		continue;
	    	}
	    	echo "<tr>";
	    	//To avoid failure page
	    	$this_user_id = $user_id;
	    	$display_user_name = "-";
	    	$user_name = "-";
	    	$designation = "-";
	    	$reports_to_name = "-";

	    	$target = "0";
	    	$sales_targert = "0";
	    	$achieved = "0";
	    	$target_amount_achieved = "0";
	    	$cases_sanctioned = "0";
	    	$cases_sanctioned_amount = "0";
	    	$cases_wip = "0";
	    	$cases_wip_amount = "0";

	        if(!empty($row['id'])){
	        	$this_user_id = $row['id'];
	        }
	    	if(!empty($row['first_name']) && !empty($row['last_name'])){
	    		$display_user_name = $row['first_name'] . ' ' . $row['last_name'];
	    	}
	        if(!empty($row['user_name'])){
	        	$user_name = $row['user_name'];
	        }
	        if(!empty($row['designation'])){
	        	$designation = $row['designation'];
	        }
	        if(!empty($row['reports_to_name'])){
	        	$reports_to_name = $row['reports_to_name'];
	        }
	         // target, sales_targert, achieved, target_amount_achieved, cases_sanctioned, cases_sanctioned_amount, cases_wip,cases_wip_amount
	        if(!empty($row['target'])){
	        	$target = $row['target'];
	        }
	        if(!empty($row['sales_targert'])){
	        	$sales_targert = $row['sales_targert'];
	        }
	        if(!empty($row['achieved'])){
	        	$achieved = $row['achieved'];
	        }
	        if(!empty($row['target_amount_achieved'])){
	        	$target_amount_achieved = $row['target_amount_achieved'];
	        }
	        if(!empty($row['cases_sanctioned'])){
	        	$cases_sanctioned = $row['cases_sanctioned'];
	        }
	        if(!empty($row['cases_sanctioned_amount'])){
	        	$cases_sanctioned_amount = $row['cases_sanctioned_amount'];
	        }
	        if(!empty($row['cases_wip'])){
	        	$cases_wip = $row['cases_wip'];
	        }
	        if(!empty($row['cases_wip_amount'])){
	        	$cases_wip_amount = $row['cases_wip_amount'];
	        }
	        echo "<td>" . $i++ . "</td>";
	        echo "<td><a href='index.php?module=Leads&action=Month_sales_report&user_id=$this_user_id&alt_startDate=$this->requested_month' target='_blank'>$display_user_name</a></td>";
	        echo "<td><a href='index.php?module=Users&action=DetailView&record=$this_user_id' target='_blank'>$designation
	        	</a></td>";
        	echo "<td>$target</td>";
        	echo "<td>$achieved</td>";
        	echo "<td>$sales_targert</td>";
        	
        	echo "<td>$target_amount_achieved</td>";
        	echo "<td>$cases_sanctioned</td>";
        	echo "<td>$cases_sanctioned_amount</td>";
        	echo "<td>$cases_wip</td>";
        	echo "<td>$cases_wip_amount</td>";
	        echo "</tr>";

    	}
    	echo "  
    	<tfoot>
		    <tr>
		      <th>Total</th>
		      <th></th>
		      <th></th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		    </tr>
  		</tfoot>
  		";
    	echo "</table>";
    }
}
?>