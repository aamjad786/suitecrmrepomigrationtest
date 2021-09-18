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


class CasesViewneo_users extends ViewList{

    var $from_date;

    var $to_date;

    var $ng_utils;

    public function __construct(){
        parent::__construct();
        $ng_utils = new Ng_utils();
        $this->from_date = $ng_utils->prepareFromDate($_REQUEST['from_date']);
        $this->to_date = $ng_utils->prepareToDate($_REQUEST['to_date']);
    }

	function display(){
		//print_r($this->dept_db_map);
		$department = "Engineering";
		if(isset($_REQUEST['department']) && !empty($_REQUEST['department'])){
			$department = $_REQUEST['department'];
            $department = trim($department);
            if(strcasecmp($department,"FINANCE") == 0){
                $department = "FINANCE & ACCOUNTS";
            }
		}
        echo("<p><b>Department : </b>" . $department . "</p>");
        echo("<p><b>From : </b>" . date_format(date_create($this->from_date),"d-m-Y") . "</p>");
        echo("<p><b>To : </b>" . date_format(date_create($this->to_date),"d-m-Y") . "</p>");
		$this->displayHeaders();
		$this->displayNeoUserRows($department);
		echo "</table></div><hr>";
	}

	function displayNeoUserRows($department){
		//$users = $this->fetchUserBean($department);
        $users = $this->fetchUserDetailsByQuery($department);
		foreach ($users as $user) {
		echo "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
        
        // echo "<td style='background-color:#f6f6f6;' valign='top' type='int' field='app_id' style='white-space: normal;'><a href='index.php?module=scrm_Custom_Reports&action=neo_user_detail'>$user->user_name</a></td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'>$user->user_name</td>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'>$user->full_name</td>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->email1 </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->reports_to_name </td>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->case_closed </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $user->within_tat_1 </td>";

        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $user->per_within_tat_1 </td>";
        
        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->tat_points </td>";

		}
	} 

	function fetchUserBean($department){
		$bean = BeanFactory::getBean('Users');
		$users = $bean->get_list(
					$order_by = "user_name",
					$where = "users.department='" . $department . "'",
					$row_offset = 0,
					$limit=-1,
					$max=-1,
					$show_deleted = 0);
		return $users['list'];
	}

	function fetchUserDetailsByQuery($department){
		global $db;
		$users = array();
		$query = "
            SELECT 
            u.user_name, 
            CONCAT(u.first_name, ' ', u.last_name) AS full_name , 
            CONCAT(r.first_name, ' ', r.last_name) AS  'reports_to_name' ,
            em.email_address AS email1 , 
            COUNT(c.id) AS 'case_closed' ,
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
            AND c.date_created >= '$this->from_date' AND c.date_created <= '$this->to_date'
            WHERE u.department = '$department'
            GROUP BY u.id";
            // print_r($query);
		$results = $db->query($query);
		while($row = $db->fetchByAssoc($results)){
            $user = "";
            ($user->user_name           = is_null($row['user_name'])?'N/A':$row['user_name']);
            ($user->full_name           = is_null($row['full_name'])?'N/A':$row['full_name']);
            ($user->email1              = is_null($row['email1'])?'N/A':$row['email1']);
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

	function displayHeaders(){
        echo $HEADERS1 = <<<HEADERS1
                <link rel="stylesheet" type="text/css" href="custom/include/css/dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="custom/include/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="custom/include/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">


        <script type="text/javascript" charset="utf8" src="custom/include/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="custom/include/js/dataTables.fixedColumns.min.js"></script>
        <script type="text/javascript" charset="utf8" src="custom/include/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" charset="utf8" src="custom/include/js/buttons.colVis.min.js"></script>
        
        <link rel="stylesheet" type="text/css" href="custom/include/css/daterangepicker.css" />


        <script>
            $(document).ready( function () {
                $('#user_score').DataTable({
                    "order": [[ 7, "desc" ]],
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
HEADERS1;
        echo "
                <hr>
                <h4>Neo Users</h4>
                <table id = 'user_score' border='1' class='cell-border compact stripe'>
            ";
        echo $HTML = <<<DISP1
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Reports To</th>
                <th>Department</th>
                <th># of cases closed</th>
                <th>Tickets within TAT</th>
                <th>Reward Points</th>
            </tr>
        </thead>
DISP1;
	}

}

?>
