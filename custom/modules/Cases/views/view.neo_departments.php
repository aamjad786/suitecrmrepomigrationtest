<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');

require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once('custom/include/SendEmail.php');
require_once('modules/EmailTemplates/EmailTemplate.php');
require_once('include/MVC/View/views/view.list.php');
require_once('custom/include/ng_utils.php');


class CasesViewneo_departments extends ViewList{

 	var $department_names;

    var $from_date;

    var $to_date;

    var $ng_utils;

    public function __construct(){
        parent::__construct();
        $ng_utils = new Ng_utils();
        $this->from_date = $ng_utils->prepareFromDate($_REQUEST['from_date']);
        $this->to_date = $ng_utils->prepareToDate($_REQUEST['to_date']);
    }

    function setDepartment_names($department_names){
    	$this->department_names = $department_names;
    }

    function getDepartment_names(){
    	return $this->department_names;
    }

	function getUserBean($user_name){
        $bean = BeanFactory::getBean('Users');
	    $query = 'users.deleted=0 and users.user_name = "'.$user_name.'"';
        // die($query);
	    $items = $bean->get_full_list('',$query);
        //var_dump($items[0]->id);
	    if(!empty($items)){
            return $items[0];
        }
        return null;
    }

    function getDetails($ngid) {
        $user = $this->getUserBean($ngid);
        if ($user) {
            $userBean = BeanFactory::getBean('Users', $user->id);
            echo "<p><b>$ngid</b></p>";
            echo "<p><b>Name: </b>$userBean->name</p>";
            echo "<p><b>Reports to: </b>$userBean->reports_to_name</p>";
            echo "<p><b>Department: </b>$userBean->department</p>";
            if (!empty($userBean->email1)) {
                echo "<p><b>E-mail: </b>$userBean->email1</p>";
            }
            $objACLRole = new ACLRole();
            $roles = $objACLRole->getUserRoles($userBean->id);
            echo "<p><b>Roles: </b>" . implode(', ', $roles) . "</p>";
            $objSecurityGroup = new SecurityGroup();
            $securityGroups = $objSecurityGroup->getUserSecurityGroups($userBean->id);
            // $sg_list = array();
            foreach (array_values($securityGroups) as $key => $value) {
                $sg_list[] = $value['name'];
            }
            if (!empty($sg_list)) {
                echo "<p><b>SecurityGroups: </b>" . implode(', ', $sg_list) . "</p>";
            } else {
                echo "<p><b>SecurityGroups: </b>None</p>";
            }
        } else {
            echo "<p style='color:red'>User '$ngid' not found in CRM. Click Submit to pull.</p>";
        }
    }

function getAllDepartment_names(){
    global $db;
    $query = "SELECT DISTINCT department from users WHERE department IS NOT NULL and department != '' ";
    $results = $db->query($query);
    $department_names = array();
    while($row = $db->fetchByAssoc($results)){
        array_push($department_names, $row['department']);
    }
    return $department_names;
}

    function displayDepartmentUpdate(){
    	$department_names = $this->getAllDepartment_names();
        echo $html = <<<DEPARTMENTFORM
		<h1><center><b>User Department Update</b></center></h1>
		<form action="#" method='post'>
		<table style="border-collapse: separate; border-spacing: 15px;">
		<tr>
			<td>Employee ID:</td>
			<td><input type='text' name='ngid' id='ngid' value='$_REQUEST[ngid]'/></td>
			<td>(Eg: ng618 or ng618,ng377)</td>
			<td colspan="1"><input type='submit' value='Get Details' id='details' name='details'/></td>
            <td colspan="1"><input type='submit' value='Pull User(s)' id='pull_user' name='pull_user'/></td>
		</tr>
		<tr>
			<td>Department:</td>
			<td>
				<select name='department' id='department' value='$_REQUEST[department]'> 
					<option value="" selected disabled>Select a Department</option>
DEPARTMENTFORM;
		foreach ($department_names as $department) {
			echo "<option value=$department>$department</option>";	
		}
        echo $html = <<<DEPARTMENTFORM1
				</select>	
			</td>
		</tr>
		<tr>
			<td colspan="1"><input type='submit' value='Update' id='update_department' name='update_department'/></td>
		</tr>
		</table>
		</form>
		<br>
		<h5>Status</h5>
		<br>
DEPARTMENTFORM1;

		$details = $_REQUEST['details'];
        $isPull = $_REQUEST['pull_user'];
		$ngid = $_REQUEST['ngid'];
		$department = $_REQUEST['department'];
		$isUpdate = $_REQUEST['update_department'];
        global $timedate;
        global $current_user;
        if (!empty($_POST['details'])) {
            if (!empty($ngid)) {
                $ngids = explode(",", $ngid);
                for ($i = 0; $i < sizeof($ngids); $i++) {
                    $eid = strtoupper(trim($ngids[$i]," "));
                    if (!empty($eid)) {
                        $this->getDetails($eid);
                        echo "<br>";
                    }
                }
            } else {
                echo "<p style='color:red'>Employee ID cannot be empty</p>";
            }
        }
		if($isUpdate){
	        if (!empty($ngid) && !empty($department)) {
	        	echo "Details Updated : ";
	            $ngids = explode(",", $ngid);
	            for ($i = 0; $i < sizeof($ngids); $i++) {
	            	$user = "";
	                $eid = strtoupper(trim($ngids[$i]," "));
	                if (!empty($eid)) {
	                    $user = $this->getUserBean($eid);
	                    $user->department = $department;
	                    echo "<p><b>$eid</b></p>";
	        			echo "<p><b>Name: </b>$user->name</p>";
	                    echo "<p><b>Department: </b>$user->department</p>";
	                    echo "<br>";
                        $myfile = fopen("updateAdrenalinUserInfo.txt", "a");
                        fwrite($myfile, "\n"."----------------displayDepartmentUpdate::Mannual department update-----------");
                        fwrite($myfile, "\n"."Old department value : $user->department, New Department value : $department");
                        fwrite($myfile, "\n"."Time : " . $timedate->now() . " modified by : " .$current_user->user_name);
                        fwrite($myfile, "\n"."----------------displayDepartmentUpdate::ends-----------\n");
	                    $user->save();
	                }
	            }
	        } else {
	            echo "<p style='color:red'>Blank Fields not accepted</p>";
	        }
		}
        if($isPull){
            require_once('PullUser.php');
            $pullUser = new PullUser();
            if(!empty($ngid)){
                $pullUser->insertUser($ngid);
            }
            else{
                echo "<p style='color:red'>Blank User ID Fields not accepted</p>";
            }
        }
    }

    function displayCalendarForm(){
        $from_date_view = date_format(date_create($this->from_date),"Y-m-d");
        $to_date_view = date_format(date_create($this->to_date),"Y-m-d");
        $max_date_view = date("Y-m-d");

        echo $html = <<<HTMLFORM
        <h1><center><b>Rewards & Recognition</b></center>
            <center><span id="date_error" style="color:red;"></span></center>         
        </h1>

        <form action="index.php?module=Cases&action=NeoDepartments" method='post'>
            <table>
                <tr>
                    <td>From</td>
                    <td><input type='date' name='from_date' id='from_date' value="$from_date_view" max="$max_date_view"/></td>
                    <td><span style="color:red; padding-left:10px" class = "date_error" id="from_date_error"></span></td>
                </tr>
                </br></br>
                <tr>
                    <td>To</td>
                    <td><input type='date' name='to_date' id='to_date' value="$to_date_view" max="$max_date_view"/></td>
                    <td><span style="color:red; padding-left:10px" class ="date_error" id="to_date_error"></span></td>
                </tr>
                <tr>
                    <td colspan="1" style="padding-top:30px;"><input type='submit' value='Get Details' id='submit' name='submit'/></td>
                </tr>
            </table>
        </form>
        <hr>
HTMLFORM;
        ?>
        <script>
            $("#from_date").change(function() {
                var input_type = "from";
                checkDate(input_type);
            });
            $("#to_date").change(function() {
                var input_type = "to";
                checkDate(input_type);
            });
            function checkDate(input_type){
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                from = new Date(from_date);
                to = new Date(to_date);
                if(to < from){
                    $('#submit').prop('disabled', true).css('opacity',0.5);
                    if(input_type === "from") {
                        $("#from_date").focus();
                        $("#from_date_error").html("From date cannot be more than To date");
                        $("#to_date_error").html("");
                    } else if(input_type === "to") {
                        $("#to_date").focus();
                        $("#to_date_error").html("To date cannot be less than From date");
                        $("#from_date_error").html("");

                    }
                    return false;
                } else {
                    $(".date_error").html("");
                    $('#submit').prop('disabled', false).css('opacity',1);
                    $("#submit").show();
                 }
            }
        </script>
        <?php
    }
    function endTable(){
        echo $HTML = <<<ENDTABLE
        </table>
        </div>
ENDTABLE;
    }

	function displayNeoDepartmentRows(){
		$departments = $this->fetchDepartmentDetails();
		$department_names = array();
		foreach ($departments as $row) {
			$this->displayRow($row);
			array_push($department_names, $row['department']);
		}
		$this->setDepartment_names($department_names);
	} 

	function displayRow($row){

		$department = $row['department'];
        $department = ucwords(strtolower($department));
		$count = $row['count'];
		$reward_point = $row['tat_points'];
		$case_count = $row['case_count'];
		$within_tat_1 = $row['within_tat_1'];
		$avg_case_time = $row['avg_case_time'];
		$per_within_tat_1 = (($within_tat_1/$case_count)*100);
        if(!empty($per_within_tat_1)){
            $per_within_tat_1 = round($per_within_tat_1,2);
        }
		if(empty($reward_point)){
			$reward_point = 0;
		}
		if(empty($case_count)){
			$case_count = 0;
		}
        if(empty($avg_case_time)){
            $avg_case_time= 0 ;
        }

        echo "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='int' field='app_id' style='white-space: normal;'><a href='index.php?module=Cases&action=NeoUsers&department=$department&from_date=$this->from_date&to_date=$this->to_date' target='_blank'>$department</a></td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='count' style='white-space: normal;'>$count</td>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $case_count </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $avg_case_time </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $within_tat_1 </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $per_within_tat_1 </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $reward_point </td>";
	}

	function fetchDepartmentDetails(){
		global $db;
		$departments = array();
		//SUM(IF(c.tat_points = 0, -20, c.tat_points)) as tat_points,
		$query = "
			SELECT 
			u.department, 
			COUNT(u.id) AS count, 
			COUNT(c.id) AS case_count,
			ROUND(AVG(c.hours_taken), 2) AS avg_case_time,
			SUM(
				CASE 
					WHEN c.within_tat_1 = 'y' THEN 1
					ELSE 0
				END
				) AS within_tat_1,
			SUM(
				CASE 
					WHEN c.tat_points = 0 THEN -20
					ELSE c.tat_points
				END
				) AS tat_points
			FROM users u 
            LEFT JOIN cases_rewards c ON u.id = c.user_id  
            AND c.date_created >= '$this->from_date' AND c.date_created <= '$this->to_date'
            WHERE u.department != 'Customer Support' AND u.department != 'Management'
            AND u.department != 'Customer Service' AND u.department != 'Customer Experience'
            GROUP BY u.department
		";
        // print_r($query); die();
		$results = $db->query($query);
		while($row = $db->fetchByAssoc($results)){
			array_push($departments,$row);
			//print_r($row);
			//echo "<br>";
		}
		return $departments;
	}

	function displayHeaders(){
        echo $HEADERS1 = <<<HEADERS1
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">


        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
        
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


        <script>
            $(document).ready( function () {
                $('#department_score').DataTable({
                        "order": [[ 6, "desc" ]],
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        "columnDefs": [
                            {
                                "targets": "_all",
                                "className": "dt-body-center"
                            }
                        ]
                    });
                $('#user_score').DataTable({
                        "order": [[ 8, "desc" ]],
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        "columnDefs": [
                            {
                                "targets": "_all",
                                "className": "dt-body-center"
                            }
                        ]
                    });
            });
        </script>
        <style>
            th.dt-center, td.dt-center { text-align: center; }
        </style>
HEADERS1;
        echo "
                <h4>Neo Departments</h4>
                <table id = 'department_score' border='1' class='cell-border compact stripe'>
            ";
        echo $HTML = <<<DISP1
            <thead>
                <tr>
                    <th>Department</th>
                    <th># Of Neons</th>
                    <th># of Cases Closed</th>
                    <th>Avg Ticket Time(Hrs)</th>
                    <th>Tickets within TAT</th>
                    <th>%Tickets within TAT</th>
                    <th>Reward Points</th>
                </tr>
            </thead>
DISP1;
	}

	function displayHeadersForUsers(){
        echo "
                <hr>
                <h4>Top Performers</h4>
                <table border='1' id = 'user_score' class='cell-border compact stripe'>
            ";
        echo $HTML = <<<USERDISP1
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Reports To</th>
                <th>Department</th>
                <th># of cases closed</th>
                <th>Tickets within TAT</th>
                <th>%Tickets within TAT</th>
                <th>Reward Points</th>
            </tr>
        </thead>
USERDISP1;
	}

	function displayNeoUserRows(){
		//$users = $this->fetchUserBean($department);
        $users = $this->fetchUserDetailsByQuery();
		foreach ($users as $user) {
		echo "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
        
        // echo "<td style='background-color:#f6f6f6;' valign='top' type='int' field='app_id' style='white-space: normal;'><a href='index.php?module=scrm_Custom_Reports&action=neo_user_detail'>$user->user_name</a></td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'>$user->user_name</td>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'>$user->full_name</td>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->email1 </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->reports_to_name </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->department </td>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->case_closed </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $user->within_tat_1 </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $user->per_within_tat_1 </td>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->tat_points </td>";

		}
	}

	function fetchUserDetailsByQuery(){
		global $db;
		$users = array();
		$query = "
            SELECT 
            u.id,
            c.id as 'case_id',
            u.user_name, 
            u.department AS department,
            CONCAT(u.first_name, ' ', u.last_name) AS full_name , 
            CONCAT(r.first_name, ' ', r.last_name) AS  'reports_to_name' ,
            em.email_address AS email1 , 
            COUNT(c.case_id) AS 'case_closed' ,
            SUM(
                CASE 
                    WHEN c.within_tat_1 = 'y' THEN 1
                    ELSE 0
                END
                ) AS within_tat_1,
            SUM(c.tat_points) AS tat_points
            FROM users u 
            LEFT JOIN users r ON u.reports_to_id = r.id 
            LEFT JOIN email_addr_bean_rel ear ON ear.bean_id = u.id AND ear.deleted = 0
            LEFT JOIN email_addresses em ON em.id = ear.email_address_id
            LEFT JOIN cases_rewards c ON u.id=c.user_id
            WHERE u.department != 'Customer Support' AND u.department != 'Management'
            AND u.department != 'Customer Service' AND u.department != 'Customer Experience'
            AND c.date_created >= '$this->from_date' AND c.date_created <= '$this->to_date'
            GROUP BY u.id
            ORDER BY tat_points desc
            LIMIT 25
            ";
		$results = $db->query($query);
		while($row = $db->fetchByAssoc($results)){
            $user = "";
            ($user->user_name           = is_null($row['user_name'])?'N/A':strtoupper($row['user_name']));
            ($user->full_name           = is_null($row['full_name'])?'N/A':$row['full_name']);
            ($user->email1              = is_null($row['email1'])?'N/A':$row['email1']);
            ($user->department          = is_null($row['department'])?'N/A':$row['department']);
            ($user->reports_to_name     = is_null($row['reports_to_name'])?'N/A':$row['reports_to_name']);
            ($user->tat_points          = is_null($row['tat_points'])?'0':$row['tat_points']);
            ($user->case_closed         = is_null($row['case_closed'])?'0':$row['case_closed']);
            ($user->within_tat_1        = is_null($row['within_tat_1'])?'0':$row['within_tat_1']);
            $user->per_within_tat_1     = ($user->within_tat_1/$user->case_closed)*100;

            if(!empty($user->per_within_tat_1)){
                $user->per_within_tat_1 = round($user->per_within_tat_1,2);
            }

			array_push($users,$user);
			//print_r($row);
			//echo "<br>";
		}
		return $users;
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
        //print_r($this->dept_db_map);
        $this->dateValueCheck();
        $this->displayCalendarForm();
        $this->displayHeaders();
        $this->displayNeoDepartmentRows();
        $this->endTable();
        $this->displayHeadersForUsers();
        $this->displayNeoUserRows();
        $this->endTable();
        echo "<hr>";
        // $this->displayDepartmentUpdate();
    }

}

?>
