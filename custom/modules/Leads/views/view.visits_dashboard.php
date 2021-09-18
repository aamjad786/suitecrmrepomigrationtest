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
class LeadsViewvisits_dashboard extends SugarView {

	var $month;
	var $requested_month;
    function __construct(){    
        parent::SugarView();
        $this->month = date("F-Y");
        $this->requested_month = date("F");

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
		$input_month = ($_REQUEST['month']);
		if(!empty($_REQUEST['month'])){
			$this->requested_month = (date("F", strtotime($_REQUEST['month'])));
			$this->month = date("F-Y", strtotime($this->requested_month));
    	}
    	// var_dump($this->requested_month);
    	// var_dump($this->month);
    	// $this->requested_month = $requested_month;
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
					      { className: "dt-right", "targets": [3, 4, 5,6,7] },
					      { className: "dt-nowrap", "targets": [1, 2] },
					      { className: "dt-center", "targets": [0] }
					    ],
			            footerCallback: function () {
			                var api = this.api(),
			                    columns = [3, 4, 5,6,7]; // Add columns here

			                for (var i = 0; i < columns.length; i++) {
			                    $('#monthly_performance_table tfoot th').eq(columns[i]).html((api.column(columns[i], {filter: 'applied'}).data().sum()).toLocaleString('en-IN') + '<br>');
			                    // $('tfoot th').eq(columns[i]).append('Page: ' + api.column(columns[i], { filter: 'applied', page: 'current' }).data().sum());
			                }
			            }

                    });
                    
	         	
			    
            });
        </script>
	    <style>
		    table{
		        border: 1px solid black;
		    }
		
	    </style>
    <body>
    	<form action="" id="uploadForm1" method="post" enctype="multipart/form-data">
    		<label for="startDate">Date :</label>
    		<input type='month' name="month" id="month" min="2018-11" value="$input_month"> <br>
    		
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
    	echo "<p>$user->user_name - $user->full_name <br> $user->designation_c</p>";
    	global $db;
    	$query = "select users.id,users.user_name,users.designation_c as designation,value_disbursed channel_visit,users.first_name,users.last_name,target_amount_pending as customer_visit,cases_logged_in as login_count,achieved as disbursed_count,(value_disbursed+target_amount_pending) as total_visits from scrm_targets_history join users on users.id=user_profile_id where  users.reports_to_id='$user_id' and month='$this->requested_month' and (value_disbursed>0 or target_amount_pending>0)
    		";

    	// print_r($query); echo "<br>";
    	$results = $db->query($query);
    	$results_array = array();
    	while($row = $db->fetchByAssoc($results)){
    		array_push($results_array, $row);
    	}
    	
    	// print_r($results); echo "<br>";
    	$this->display_monthly_performance_table($results_array);
    	

    }

    

    function display_monthly_performance_table($results){
    	global $db;
        echo $HTML = <<<MONTHLY_PERFORMANCE_TABLE
            <hr>
            <h4>
                Visits Dashboard - For the month of $this->month
            </h4>
            <table id = "monthly_performance_table" class='stripe row-border order-column' style='width:100%'>
	        <thead>
	            <tr>
	            	<th>Sno</th>
	                <th>User Name</th>
	                <th>NG ID</th>
	                <th>Customer Visit</th>
	                <th>Channel Visit</th>
	                <th>Total Visit</th>
	                <th>Total Logins</th>
	                <th>Total Disbursed Cases</th>
	            </tr>
	        </thead>
MONTHLY_PERFORMANCE_TABLE;

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
	    	$customer_visit=0;
	    	$channel_visit=0;
	    	$total_visits=0;
	    	$disbursed_count=0;
	    	$login_count=0;

	    	if(!empty($row['customer_visit'])){
	        	$customer_visit = (int)$row['customer_visit'];
	        }
	        if(!empty($row['total_visits'])){
	        	$total_visits = (int)$row['total_visits'];
	        }
	        if(!empty($row['channel_visit'])){
	        	$channel_visit = (int)$row['channel_visit'];
	        }
	        if(!empty($row['id'])){
	        	$this_user_id = $row['id'];
	        }

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
	        if(!empty($row['disbursed_count'])){
	        	$disbursed_count = $row['disbursed_count'];
	        }
	        if(!empty($row['login_count'])){
	        	$login_count = $row['login_count'];
	        }
	       
	        echo "<td>" . $i++ . "</td>";
	        echo "<td><a href='index.php?module=Leads&action=visits_dashboard&user_id=$this_user_id&alt_startDate=$this->requested_month' target='_blank'>$display_user_name</a></td>";
	        echo "<td><a href='index.php?module=Users&action=DetailView&record=$this_user_id' target='_blank'>$user_name
	        	</a></td>";
        	echo "<td>$customer_visit</td>";
        	echo "<td>$channel_visit</td>";
        	echo "<td>$total_visits</td>";
        	echo "<td>$login_count</td>";
        	echo "<td>$disbursed_count</td>";
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
		    </tr>
  		</tfoot>
  		";
    	echo "</table>";
    }
}
?>