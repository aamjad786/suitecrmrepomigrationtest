<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');

require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once('custom/include/SendEmail.php');;
require_once('modules/EmailTemplates/EmailTemplate.php');
require_once('include/MVC/View/views/view.list.php');
require_once('custom/include/ng_utils.php');
require_once('modules/Neo_Customers/Renewals_functions.php');

class Neo_CustomersViewrenewals_user_analytics extends ViewList{

	private $chartV;
	private $total_count;
	private $header_list;
	private $csv_rows;
	var $ng_utils;
	var $renewals;

	function __construct(){
    	$this->total_count = 0;
    	$this->csv_rows = array();
    	$this->header_list = array();
    	$this->ng_utils = new Ng_utils();
    	$this->ng_utils->test();
    	$this->renewals = new Renewals_functions();
    }

    function exportCsv(){
		if(!empty($_REQUEST['Export']))
			{
				$timestamp = date('Y_m_d_His'); 
				ob_end_clean();
				ob_start();	
				// output headers so that the file is downloaded rather than displayed
				header('Content-Type: text/csv; charset=utf-8');
				header("Content-Disposition: attachment; filename=renewals_user_analytics_{$timestamp}.csv");

				// create a file pointer connected to the output stream
				$output = fopen('php://output', 'w');
				// output the column headings
				// foreach($this->header_list as $value){
				// 	fputcsv($output, $value);
				// }
				fputcsv($output,$this->header_list);
				foreach ($this->csv_rows as $row_data)
				{
					fputcsv($output,$row_data);
				}
				exit;
				
			}
    }

    function displayCalendarForm(){
		$from_date = $this->ng_utils->prepareFromDate($_REQUEST['from_date']);
		$to_date = $this->ng_utils->prepareToDate($_REQUEST['to_date']);
		
		$from_date_view = date_format(date_create($from_date),"Y-m-d");
		$to_date_view = date_format(date_create($to_date),"Y-m-d");

    	echo $html = <<<HTMLFORM
		<h1><center><b>Renewal Customer Reports</b></center></h1>
		<form action="index.php?module=Neo_Customers&action=RenewalsUserAnalytics" method='post'>
		<table>
		<b>Created Date</b> 
		<br><br> 
		
			<label for="From">From:</label>
			<input type="date" id="From" name="from_date">
			<br><br>
			<label for="To">To:  </label>
			<input type="date" id="To" name="to_date">
			<br><br>
			<input type='submit' value='Get Details' id='details' name='details'/>
			<br><br>
		
		
		<input type="submit" id="Export" name="Export" value="Export"/>
		
		<br>
		</table>
		</form>
		<hr>
HTMLFORM;
    }

    function displayRow($row){
        echo "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' style='white-space: normal;'>$row[0]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' style='white-space: normal;'>$row[1]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' style='white-space: normal;'>$row[2]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' style='white-space: normal;'>$row[3]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' style='white-space: normal;'>$row[4]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' style='white-space: normal;'>$row[5]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='int' style='white-space: normal;'>$row[6]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='int' style='white-space: normal;'>$row[7]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='int' style='white-space: normal;'>$row[8]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='int' style='white-space: normal;'>$row[9]</td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='int' style='white-space: normal;'>$row[10]</td>";

        echo "</tr>";
    }

    function displayHeaderRow(){
        echo $HTML = <<<DISP1
            <div id='detailpanel_2' class='list view  list508 expanded' style='overflow-x:auto;'>
            <h4>
                <a href='javascript:void(0)' class='collapseLink' onclick='collapsePanel(2);'>
                <img border='0' id='detailpanel_2_img_hide' src='themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
                <a href='javascript:void(0)' class='expandLink' onclick='expandPanel(2);'>
                <img border='0' id='detailpanel_2_img_show' src='themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
                User Analytics
            </h4>
            <table border='0' cellpadding='0' cellspacing='0' width='100%'>

	        <div style='border-bottom:1px solid #dddddd; align:left;'>
	            <th style="color:black">
	               
	                        User ID
	                        
	               
	            </th>
	            <th style="color:black">
	                <div style='white-space: normal;' align='left'>
	                        Name
	                        &nbsp;&nbsp;
	                </div>
	            </th>
	            <th style="color:black">
	                <div style='white-space: normal;' align='left'>
	                        City List
	                        &nbsp;&nbsp;
	                </div>
	            </th>
	            <th style="color:black">
	                <div style='white-space: normal;' align='left'>
	                        Location Assigned
	                        &nbsp;&nbsp;
	                </div>
	            </th>
	            <th style="color:black">
	                <div style='white-space: normal;'width='100%' align='left'>
	                        Role
	                        &nbsp;&nbsp;
	                </div>
	            </th>	            
	            <th style="color:black">                   
	                <div style='white-space: normal;'width='100%' align='left'>
	                    Count Of Disposition Changed
	                    &nbsp;&nbsp;
	                </div>
	            </th>

	            <th style="color:black">                    
	                <div style='white-space: normal;'width='100%' align='left'>
	                        Count Of Disposition Changed - Customer
	                        &nbsp;&nbsp;
	                </div>
	            </th>

	            <th style="color:black">                    
	                <div style='white-space: normal;'width='100%' align='left'>
	                        Count Of Disbursals
	                        &nbsp;&nbsp;
	                </div>
	            </th>
	            <th style="color:black">                   
	                <div style='white-space: normal;'width='100%' align='left'>
	                    Max Count of Customers
	                    &nbsp;&nbsp;
	                </div>
	            </th>
	            <th style="color:black">                   
	                <div style='white-space: normal;'width='100%' align='left'>
	                    Lead Assigned
	                    &nbsp;&nbsp;
	                </div>
	            </th>
	            <th style="color:black">                   
	                <div style='white-space: normal;'width='100%' align='left'>
	                    Leads Assigned by User
	                    &nbsp;&nbsp;
	                </div>
	            </th>
	        </div>
DISP1;
    }

    function parseAndDisplayResults($results){
    	global $db;
		while($row = $db->fetchByAssoc($results)){ 
			// print_r($row);echo "<br>";			
			$csv_row = array();
			$user_id = "-";
			if(isset($row['user_id']) && !empty($row['user_id'])){
				$user_id = $row['user_id'];
			}
			$ngid = "-";
			if(isset($row['ngid']) && !empty($row['ngid'])){
				$ngid = $row['ngid'];
			}
			array_push($csv_row, $ngid);
			$user_name = "-";
			if(isset($row['user_name']) && !empty($row['user_name'])){
				$user_name = $row['user_name'];
			}
			array_push($csv_row, $user_name);
			$city_list = "-";
			if(isset($row['city_list']) && !empty($row['city_list'])){
				$city_list = $row['city_list'];
			}
			array_push($csv_row, $city_list);
			$city = "-";
			if(isset($row['city']) && !empty($row['city'])){
				$city = $row['city'];
			}
			array_push($csv_row, $city);
			$role = "-";
			if(isset($row['role']) && !empty($row['role'])){
				$role = $row['role'];
			}
			array_push($csv_row, $role);
			//disp = dispositon
			$disp_count_list = array();
			if(isset($row['disp_count_list']) && !empty($row['disp_count_list'])){
				$disp_count_list = explode("|", $row['disp_count_list']);	
			}
			else{
				$disp_count_list[0] = "-"; //customer_level_count
				$disp_count_list[1] = "-"; //change count
			}
			$disp_count = "-";
			if(isset($disp_count_list[1]) && !empty($disp_count_list[1])){
				$disp_count = $disp_count_list[1];
			}
			array_push($csv_row, $disp_count);
			$disp_count_user = "-";
			if(isset($disp_count_list[0]) && !empty($disp_count_list[0])){
				$disp_count_user = $disp_count_list[0];
			}
			array_push($csv_row, $disp_count_user);
			$disbursal_count = "-";
			if(isset($row['disbursal_count']) && !empty($row['disbursal_count'])){
				$disbursal_count = $row['disbursal_count'];
			}
			array_push($csv_row, $disbursal_count);
			$max_customer_count = '-'; 
			if(isset($row['max_customer_count']) && !empty($row['max_customer_count'])){
				$max_customer_count = $row['max_customer_count'];
			}
			array_push($csv_row, $max_customer_count);
			$lead_assigned_count = '-'; 
			if(isset($row['lead_assigned_count']) && !empty($row['lead_assigned_count'])){
				$lead_assigned_count = $row['lead_assigned_count'];
			}
			array_push($csv_row, $lead_assigned_count);
			$user_lead_assigned_count = '-'; 
			if(!empty($row['user_lead_assigned_count'])){
				$user_lead_assigned_count = $row['user_lead_assigned_count'];
			}
			array_push($csv_row, $user_lead_assigned_count);
			// print_r($csv_row);echo "<br>";
			$this->displayRow($csv_row);
			array_push($this->csv_rows, $csv_row);
		}
    }
    function fetchAdminQuery($from_date,$to_date){
    	$query = "
			SELECT 
			ru.user_id as 'user_id',
			ru.user_name as 'ngid', 
			CONCAT(u.first_name,' ',u.last_name) as 'user_name', 
			ru.ticket_size, 
			ru.role as 'role', 
			ru.city as 'city_list',
			rua.activity_key as 'city', 
			MAX(rua.activity_value) as 'max_customer_count',
			(SELECT 
				CONCAT(COALESCE(COUNT(DISTINCT parent_id),0),'|',COALESCE(COUNT(*),0)) 
				FROM neo_customers_audit nca 
				LEFT JOIN neo_customers nc ON nc.id = nca.parent_id
				WHERE nca.field_name = 'disposition'
					AND nc.location = rua.activity_key
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'disp_count_list',
			(SELECT 
				SUM(floor((length(nca.after_value_string)-length(replace(nca.after_value_string ,'Disbursement','')))/LENGTH('Disbursement'))) 
				FROM neo_customers_audit nca
				LEFT JOIN neo_customers neo_customers ON neo_customers.id = nca.parent_id
				WHERE field_name = 'as_stage'
					AND after_value_string LIKE '%Disbursement%'
					AND neo_customers.location = rua.activity_key 
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'disbursal_count',
			(SELECT 
				COUNT(DISTINCT parent_id)
				FROM neo_customers_audit nca 
				LEFT JOIN neo_customers nc ON nc.id = nca.parent_id
				WHERE nca.field_name = 'disposition'
					AND nc.location = rua.activity_key
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'lead_assigned_count',
			(SELECT 
				COUNT(DISTINCT parent_id) FROM neo_customers_audit nca
				JOIN neo_customers nc ON nc.id = nca.parent_id
				WHERE nca.field_name = 'assigned_user_id' 
					AND nc.location = rua.activity_key
					AND nca.created_by = ru.user_id 
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'user_lead_assigned_count'
			FROM renewal_users ru
			LEFT JOIN users u ON ru.user_id = u.id
			LEFT JOIN  renewals_user_activity rua ON ru.user_id = rua.user_id
			AND rua.date_created >= '$from_date' AND rua.date_created <= '$to_date'
			WHERE ru.role = 'Renewal admin'
			GROUP BY ru.user_id, rua.activity_key
    	";
    	return $query;
    }
    function fetchManagerQuery($from_date,$to_date,$where_query,$renewal_manager_id){
    	$query = "
			SELECT 
			ru.user_id as 'user_id',
			ru.user_name as 'ngid', 
			CONCAT(u.first_name,' ',u.last_name) as 'user_name', 
			ru.ticket_size, 
			ru.role as 'role', 
			ru.city as 'city_list',
			rua.activity_key as 'city', 
			MAX(rua.activity_value) as 'max_customer_count',
			(SELECT 
				CONCAT(COALESCE(COUNT(DISTINCT parent_id),0),'|',COALESCE(COUNT(*),0)) FROM neo_customers_audit nca
				JOIN neo_customers neo_customers ON neo_customers.id = nca.parent_id
				WHERE nca.field_name = 'disposition' 
					AND neo_customers.location = rua.activity_key
					$where_query
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'disp_count_list',
			(SELECT 
				SUM(floor((length(nca.after_value_string)-length(replace(nca.after_value_string ,'Disbursement','')))/LENGTH('Disbursement')))
				FROM neo_customers_audit nca
				LEFT JOIN neo_customers neo_customers ON neo_customers.id = nca.parent_id
				WHERE field_name = 'as_stage'
					AND after_value_string LIKE '%Disbursement%'
					$where_query
					AND neo_customers.location = rua.activity_key   
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'disbursal_count',
			(SELECT 
				COUNT(DISTINCT parent_id) FROM neo_customers_audit nca
				JOIN neo_customers neo_customers ON neo_customers.id = nca.parent_id
				WHERE nca.field_name = 'assigned_user_id' 
					AND neo_customers.location = rua.activity_key
					$where_query
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'lead_assigned_count',
			(SELECT 
				COUNT(DISTINCT parent_id) FROM neo_customers_audit nca
				JOIN neo_customers nc ON nc.id = nca.parent_id
				WHERE nca.field_name = 'assigned_user_id' 
					AND nc.location = rua.activity_key
					AND nca.created_by = ru.user_id 
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'user_lead_assigned_count'
			FROM renewal_users ru
			LEFT JOIN users u ON ru.user_id = u.id
			LEFT JOIN  renewals_user_activity rua ON ru.user_id = rua.user_id
			AND rua.date_created >= '$from_date' AND rua.date_created <= '$to_date'
			WHERE ru.user_id = '$renewal_manager_id'
			AND ru.role = 'Renewal manager'
			GROUP BY ru.user_id, rua.activity_key
    	";
    	//echo "<br>";
    	//print_r($query);
    	return $query;
    }

    function displayRows(){
		global $db;
		require_once("include/TimeDate.php");
		$where_role = "
			AND ru.role = 'Renewal Location caller'
			AND ru.role = 'Renewal TAT caller'
		";
		$from_date = $this->ng_utils->prepareFromDate($_REQUEST['from_date']);
		$to_date = $this->ng_utils->prepareToDate($_REQUEST['to_date']);
		// print_r($to_date);
		$query = "
			SELECT 
			ru.user_id as 'user_id',
			ru.user_name as 'ngid', 
			CONCAT(u.first_name,' ',u.last_name) as 'user_name', 
			ru.ticket_size, 
			ru.role as 'role', 
			ru.city as 'city_list',
			rua.activity_key as 'city', 
			MAX(rua.activity_value) as 'max_customer_count',
			(SELECT 
				CONCAT(COALESCE(COUNT(DISTINCT parent_id),0),'|',COALESCE(COUNT(*),0)) FROM neo_customers_audit nca
				JOIN neo_customers nc ON nc.id = nca.parent_id
				WHERE nca.field_name = 'disposition' 
					AND nc.location = rua.activity_key
					AND nca.created_by = ru.user_id 
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'disp_count_list',
			(SELECT 
				SUM(floor((length(nca.after_value_string)-length(replace(nca.after_value_string ,'Disbursement','')))/LENGTH('Disbursement')))
				FROM neo_customers_audit nca
				LEFT JOIN neo_customers neo_customers ON neo_customers.id = nca.parent_id
				WHERE field_name = 'as_stage'
					AND after_value_string LIKE '%Disbursement%'
					AND neo_customers.location = rua.activity_key 
					AND neo_customers.assigned_user_id = ru.user_id  
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'disbursal_count',
			(SELECT 
				COUNT(DISTINCT parent_id) FROM neo_customers_audit nca
				JOIN neo_customers nc ON nc.id = nca.parent_id
				WHERE nca.field_name = 'assigned_user_id' 
					AND nc.location = rua.activity_key
					AND nca.created_by = ru.user_id 
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'lead_assigned_count',
			(SELECT 
				COUNT(DISTINCT parent_id) FROM neo_customers_audit nca
				JOIN neo_customers nc ON nc.id = nca.parent_id
				WHERE nca.field_name = 'assigned_user_id' 
					AND nc.location = rua.activity_key
					AND nca.created_by = ru.user_id 
					AND nca.date_created >= '$from_date' 
					AND nca.date_created <= '$to_date'
				) AS 'user_lead_assigned_count'
			FROM renewal_users ru
			LEFT JOIN users u ON ru.user_id = u.id
			LEFT JOIN  renewals_user_activity rua ON ru.user_id = rua.user_id
			AND rua.date_created >= '$from_date' AND rua.date_created <= '$to_date'
			WHERE ru.role = 'Renewal Location caller'
			OR ru.role = 'Renewal TAT caller'
			GROUP BY ru.user_id, rua.activity_key
			";

		// print_r($query); 
		// $max_count = $this->getMaxCustomerCount($from_date, $to_date);
		$results = $db->query($query);
		$this->parseAndDisplayResults($results);
		$manager_user_results = $this->renewals->getRenewalUsersByRole("Renewal manager");
        	// print_r($manager_user_results);
        	// echo "<br>";
        while($row=$db->fetchByAssoc($manager_user_results)){
        	// print_r($row);
        	// echo "<br>";
            $renewal_manager_id = "";
            $ticket_size = "";
            $city = "";
            $role = ""; 
            $renewal_manager_id = $row['user_id'];
            $ticket_size = $row['ticket_size'];
            $city = $row['city'];
            $role = $row['role'];      
            $where_query = "";
            $where_query = 'AND ' . $this->renewals->getQueryManager($city,$ticket_size,1);
            $manager_query = "";
            $manager_results = "";
            $manager_query = $this->fetchManagerQuery($from_date,$to_date,$where_query,$renewal_manager_id);
            $manager_results = $db->query($manager_query);
            $this->parseAndDisplayResults($manager_results);
        }
        $admin_query = "";
        $admin_results = "";
        $admin_query = $this->fetchAdminQuery($from_date,$to_date);
        $admin_results = $db->query($admin_query);
        $this->parseAndDisplayResults($admin_results);
    }

    function endTable(){
        echo $HTML = <<<ENDTABLE
        </table>
        </div>
ENDTABLE;
    }

	function isRenewalAdmin($roles){
	    $results = false;
	    if(empty($roles)) {
	        return $results;
	    }
	    foreach ($roles as $role) {
	        if(stripos($role,"Renewal admin") !== false){
	            $results = true;
	            break;
	        }
	    }   
	    return $results;
	}

    function checkAccess(){
		global $current_user, $sugar_config;
		$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$roles = ACLRole::getUserRoleNames($current_user->id);
		if (strpos($url, $sugar_config['AS_CRM_Domain']) !== false) {
		    $permitted_users = $sugar_config['renewals_user_analytics_permitted_user'];
		    if (!$current_user->is_admin && !in_array($current_user->user_name, $permitted_users)  && !$this->isRenewalAdmin($roles)) {
		        // print_r("here too:P");
		        die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
		    }
		}
    }

    function dateValueCheck(){
	
    	$from_date = $_REQUEST['from_date'];
    	$to_date = $_REQUEST['to_date'];
    	if($from_date>$to_date){
    		$this->displayCalendarForm();
    		die('From Date cant be greated than to date.');
    	}
    }

	function display(){
		$this->checkAccess();
		$this->dateValueCheck();
		$this->header_list = array("User ID", "Name", "Location Assigned", "City", "Role", "Count Of Disposition Changed", "Count Of Disposition Changed - Customer", "Count Of Disbursals", "Max Count Of Customers", "Leads Assigned", "Leads Assigned by User");
		$this->displayCalendarForm();
		$this->displayHeaderRow();		
		$this->displayRows();
		$this->endTable();
		$this->exportCsv();
	}
	

}//end class
?>