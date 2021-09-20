<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/entryPoint.php');
require_once('include/MVC/View/SugarView.php');


class LeadsViewsales_dashboard extends SugarView {

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
		$from_date  = date("Y-m-01 00:00:00");
		$requested_month = "";
		$input_month = ($_REQUEST['month']);
		if(!empty($_REQUEST['month'])){
			$this->requested_month = (date("F", strtotime($_REQUEST['month'])));
			$this->month = date("F-Y", strtotime($this->requested_month));
    	}
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
					      { className: "dt-right", "targets": [ 4, 5, 6, 7, 8, 9, 10,11,12,13,14,15] },
					      { className: "dt-nowrap", "targets": [1, 2,3] },
					      { className: "dt-center", "targets": [0] }
					    ],
			            footerCallback: function () {
			                var api = this.api(),
			                    columns = [4, 5, 6, 7, 8, 9, 10,11,12,13,14,15]; // Add columns here

			                for (var i = 0; i < columns.length; i++) {
			                	console.log((api.column(columns[i], {filter: 'applied'}).data().sum()).toLocaleString('en-IN'));
			                    $('#monthly_performance_table tfoot th').eq(columns[i]).html((api.column(columns[i], {filter: 'applied'}).data().sum()).toLocaleString('en-IN') + '<br>');
			                    
			                }
			            }
                    });
                   
               $('#monthly_performance_table_count').DataTable({
					    "columnDefs": [
					      { className: "dt-right", "targets": [3, 4, 5, 6, 7, 8, 9, 10,11,12] },
					      { className: "dt-nowrap", "targets": [1, 2] },
					      { className: "dt-center", "targets": [0] }
					    ],
			            footerCallback: function () {
			                var api = this.api(),
			                    columns = [3, 4, 5, 6, 7, 8, 9, 10,11,12]; // Add columns here

			                for (var i = 0; i < columns.length; i++) {
			                    $('#monthly_performance_table_count tfoot th').eq(columns[i]).html((api.column(columns[i], {filter: 'applied'}).data().sum()).toLocaleString('en-IN') + '<br>');
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
    	$query = "
    		SELECT 
    			u.id AS 'id',
    			u.user_name AS 'user_name',
    			u.first_name AS 'first_name',
    			u.last_name AS 'last_name',
    			ucstm.designation_c AS 'designation',
    			concat(mu.first_name, ' ', mu.last_name) AS 'reports_to_name',

    			FORMAT(sth.target,0,'en_IN') AS 'target',
    			FORMAT(sth.sales_target,0,'en_IN') AS 'sales_target',
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
    			FORMAT(sth.cases_login_amount,0,'en_IN') AS 'cases_login_amount',
    			FORMAT(sth.dsa_cases_sanctioned,0,'en_IN') AS 'dsa_cases_sanctioned',
    			FORMAT(sth.dsa_cases_sanctioned_amount,0,'en_IN') AS 'dsa_cases_sanctioned_amount',
    			FORMAT(sth.dsa_cases_disbursed_amount,0,'en_IN') AS 'dsa_cases_disbursed_amount',

    			FORMAT(sth.credit_amount,0,'en_IN') AS 'credit_amount',
    			FORMAT(sth.credit_count,0,'en_IN') AS 'credit_count',
    			FORMAT(sth.ops_rejected_count,0,'en_IN') AS 'ops_rejected_count',
    			FORMAT(sth.ops_rejected_amount,0,'en_IN') AS 'ops_rejected_amount',
    			FORMAT(sth.sent_to_finance_count,0,'en_IN') AS 'sent_to_finance_count',
    			FORMAT(sth.sent_to_finance_amount,0,'en_IN') AS 'sent_to_finance_amount',
    			FORMAT(sth.insurance,0,'en_IN') AS 'insurance',
    			FORMAT(sth.processing_fees,0,'en_IN') AS 'processing_fees',
    			FORMAT(sth.apr,0,'en_IN') AS 'apr',
    			FORMAT(sth.disbursal_target,0,'en_IN') AS 'disbursal_target',
    			FORMAT(sth.login_target,0,'en_IN') AS 'login_target'
    		
    		FROM users u
			JOIN users_cstm ucstm ON u.id=ucstm.id_c
    		LEFT JOIN users mu ON mu.id = u.reports_to_id
    		LEFT JOIN scrm_targets_history sth ON sth.user_profile_id = u.id
    		WHERE u.reports_to_id = '$user_id'
    		AND sth.month = '$this->requested_month'
    		AND (sth.target_amount_achieved > 0 || sth.disbursal_target>0)
    		AND u.deleted = 0
    	";

    	 // print_r($query); echo "<br>";
    	$results = $db->query($query);
    	$results_array = array();
    	$insurance_count = 0;
    	while($row = $db->fetchByAssoc($results)){
    		// var_dump($row);
    		if($results_array['insurance']>0){
    			$insurance_count+=1;
    		}
    		array_push($results_array, $row);
    	}

    	// print_r($results); echo "<br>";
    	$this->display_monthly_performance_table($results_array);
    	$this->display_monthly_performance_table_count($results_array,'monthly_performance_table_count');
    	
    }

    function get_int_val($str){
    	return intval(preg_replace('/[^\d.]/', '', $str));
    }

    function display_monthly_performance_table($results,$id='monthly_performance_table',$unaccounted=""){
    	// var_dump($results);
    	global $db;
    	if(empty($unaccounted)){
    		$unaccounted="";
    	}else{
    		$unaccounted = "(unaccounted)";
    	}
        echo $HTML = "
            <hr>
            <h4>
                Performance Report $unaccounted by value - For the month of $this->month
            </h4>
            <table id = '$id' class='stripe row-border order-column' style='width:100%'>
	        <thead>
	            <tr>
	            	<th>Sno</th>
	                <th>User Name</th>
	                <th>Designation</th>
	                <th>Zone/Branch</th>
	                <th>Login</th>
	                <th>Ops Rejected</th>
	                <th>Credit</th>
	                <th>Sanctioned</th>
	                <th>STF</th>
	                <th>Disbursed</th>
	                <th>Target</th>
	                <th>Ahead/Behind (%)</th>
	                <th>Pipeline</th>
	                <th>Insurance</th>
	                <th>APR(%)</th>
	                <th>Processing Fee(%)</th>
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
	    	$target_amount_achieved = empty($row['target_amount_achieved'])?"0":$row['target_amount_achieved'];
	    	$cases_sanctioned_amount =empty($row['cases_sanctioned_amount'])?"0":$row['cases_sanctioned_amount'];
	    	$cases_login_amount=empty($row['cases_login_amount'])?"0":$row['cases_login_amount'];
	    	$sent_to_finance_amount = empty($row['sent_to_finance_amount'])?"0":$row['sent_to_finance_amount'];
	    	$credit_amount=empty($row['credit_amount'])?"0":$row['credit_amount'];
	    	$ops_rejected_amount=empty($row['ops_rejected_amount'])?"0":$row['ops_rejected_amount'];
	    	$insurance = empty($row['insurance'])?"0":$row['insurance'];
	    	$processing_fees = empty($row['processing_fees'])?"0":$row['processing_fees'];
	    	$apr = empty($row['apr'])?"0":$row['apr'];


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
	 
	       
	       
	        if(!empty($row['cases_sanctioned_amount'])){
	        	$cases_sanctioned_amount = $row['cases_sanctioned_amount'];
	        }
	        $disbursal_target = $this->get_value($row,'disbursal_target');
	        $ahead_behind_percent = $this->get_ahead_behind_percent($target_amount_achieved, $disbursal_target);
	        
	        $pipeline = $cases_login_amount-$crdit_rejected_amount;
	        $processing_fees_percent = number_format($this->get_int_val($processing_fees)/$this->get_int_val($target_amount_achieved)*100*1.0,2,'.','');
	        $zone = $this->getzoneinfo($user_name);
	        echo "<td>" . $i++ . "</td>";
	        echo "<td><a href='index.php?module=Leads&action=sales_dashboard&user_id=$this_user_id&month=$this->requested_month' target='_blank'>$display_user_name</a></td>";
	        echo "<td><a href='index.php?module=Users&action=DetailView&record=$this_user_id' target='_blank'>$designation
	        	</a></td>";
	        echo "<td>$zone</td>";
	        echo "<td>$cases_login_amount</td>";
	        echo "<td>$ops_rejected_amount</td>";
	        echo "<td>$credit_amount</td>";
	        echo "<td>$cases_sanctioned_amount</td>";
	        echo "<td>$sent_to_finance_amount</td>";
	        echo "<td>$target_amount_achieved</td>";
        	echo "<td>$disbursal_target</td>";
        	echo "<td>".$ahead_behind_percent."%</td>";
        	echo "<td>".$this->get_value($row,'cases_wip_amount')."</td>";
        	echo "<td>$insurance</td>";
        	echo "<td>$apr</td>";
        	echo "<td>".$processing_fees_percent ."%</td>";
        	
	        echo "</tr>";

    	}
    	echo "  
    	<tfoot>
		    <tr>
		      <th>Total</th>
		      <th></th>
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
		      <th>x</th>
		      <th>x</th>
		      <th>x</th>
		    </tr>
  		</tfoot>
  		";
    	echo "</table>";
    }
    function getzoneinfo($ng_id){
    	if(strcasecmp($ng_id, 'NG502')==0)return 'South';
    	else if(strcasecmp($ng_id, 'NG830')==0)return 'West';
    	else if(strcasecmp($ng_id, 'NG650')==0)return 'North';
    	else if(strcasecmp($ng_id, 'NG477')==0)return 'India';
    	else{
    		global $db;
    		$query = "select * from sales_mapping where RSM_code='$ng_id'";
			$res = $db->query($query);
			$row = $db->fetchByAssoc($res);
			if(!empty($row)){
				return $row['branch'];
			}
			$query = "select * from sales_mapping where LH_code='$ng_id'";
			$res = $db->query($query);
			$row = $db->fetchByAssoc($res);
			if(!empty($row)){
				return $row['branch'];
			}
			$query = "select * from sales_mapping where CM_code='$ng_id'";
			$res = $db->query($query);
			$row = $db->fetchByAssoc($res);
			if(!empty($row)){
				return $row['branch'];
			}
			$query = "select * from sales_mapping where CAM_code='$ng_id'";
			$res = $db->query($query);
			$row = $db->fetchByAssoc($res);
			if(!empty($row)){
				return $row['branch'];
			}
    	}
    	return "-";
    	
    }
    function get_ahead_behind_percent($val1, $val2){
    	if(empty($val1)||empty($val2))return 0;
    	return number_format((float)($val1-$val2)/$val2*100*1.0,2,'.','');
    }

    function get_value($row,$value){
    	return empty($row[$value])?"0":$row[$value];
    }

    function display_monthly_performance_table_count($results,$id='monthly_performance_table',$unaccounted=""){
    	global $db;
    	if(empty($unaccounted)){
    		$unaccounted="";
    	}else{
    		$unaccounted = "(unaccounted)";
    	}
        echo $HTML = "
            <hr>
            <h4>
                Performance Report $unaccounted by count - For the month of $this->month
            </h4>
            <table id = '$id' class='stripe row-border order-column' style='width:100%'>
	        <thead>
	            <tr>
	            	<th>Sno</th>
	                <th>User Name</th>
	                <th>NG ID</th>
	                <th>Login</th>
	                <th>Login Target</th>
	                <th>Ahead/Behind (%)</th>
	                <th>Ops Rejected</th>
	                <th>Sanctioned</th>
	                <th>STF</th>
	                <th>Disbursed</th>
	                <th>Insurance Target</th>
	                <th>Insurance</th>
	                <th>Ahead/Behind (%)</th>
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
	        $cases_logged_in =$this->get_value($row,'cases_logged_in');
	        $login_target = $this->get_value($row,'login_target');
	        $ahead_behind_percent = $this->get_ahead_behind_percent($target_amount_achieved, $login_target);
	         // target, sales_targert, achieved, target_amount_achieved, cases_sanctioned, cases_sanctioned_amount, cases_wip,cases_wip_amount
	       
	        echo "<td>" . $i++ . "</td>";
	        echo "<td><a href='index.php?module=Leads&action=sales_dashboard&user_id=$this_user_id&month=$this->requested_month' target='_blank'>$display_user_name</a></td>";
	        echo "<td><a href='index.php?module=Users&action=DetailView&record=$this_user_id' target='_blank'>$user_name
	        	</a></td>";
	        echo "<td>$cases_logged_in</td>";
	        echo "<td>$login_target</td>";
        	echo "<td>$ahead_behind_percent</td>";

	        echo "<td>".$this->get_value($row,'ops_rejected_count')."</td>";
	        echo "<td>".$this->get_value($row,'cases_sanctioned')."</td>";
	        echo "<td>".$this->get_value($row,'sent_to_finance_count')."</td>";
	        echo "<td>".$this->get_value($row,'achieved')."</td>";
	                	echo "<td>-</td>";
        	echo "<td>".$this->get_value($row,'no_of_dsa_assigned')."</td>";
        	echo "<td>-</td>";
        	
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
		      <th>x</th>
		    </tr>
  		</tfoot>
  		";
    	echo "</table>";
    }
}
?>