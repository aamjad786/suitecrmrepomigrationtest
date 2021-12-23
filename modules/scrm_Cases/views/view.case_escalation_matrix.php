<?php

require_once('include/MVC/View/views/view.list.php');
require_once('modules/Cases/CasesListViewSmarty.php');

class scrm_CasesViewCase_escalation_matrix extends SugarView {

    public function __construct(){
    	parent::__construct();
    }

	function displayHeaders(){
			echo "
				<div id='detailpanel_1' class='list view  list508 expanded' style='overflow-x:auto;'>
                <h4>
                    <a href='javascript:void(0)' class='collapseLink' onclick='collapsePanel(1);'>
                    <img border='0' id='detailpanel_1_img_hide' src='themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
                    <a href='javascript:void(0)' class='expandLink' onclick='expandPanel(1);'>
                    <img border='0' id='detailpanel_1_img_show' src='themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
                    Escalation List
                    <script>
                        document.getElementById('detailpanel_1').className += ' expanded';
                    </script>
                </h4>
                <table border='0' cellpadding='0' cellspacing='0' width='100%' class='panelContainer list view table default'>
            ";
        echo $HTML = <<<DISP1
        <div style='border-bottom:1px solid #dddddd; align:left;'>
			<th scope='col'>
				<div style='white-space: normal;color:black;width:100%;'>
						Assigned User
						&nbsp;&nbsp;
				</div>
			</th>
            <th scope='col'>
				<div style='white-space: normal;color:black;'>
                        Department
                        &nbsp;&nbsp;
                </div>
            </th>
            <th scope='col'>
				<div style='white-space: normal;color:black;'>
                        Escalation Level 1
                        &nbsp;&nbsp;
                </div>
            </th>
            <th scope='col'>
				<div style='white-space: normal;color:black;'>
                        Escalation Level 2
                        &nbsp;&nbsp;
                </div>
            </th>
            <th scope='col'>
				<div style='white-space: normal;color:black;'>
                        Escalation Level 3
                        &nbsp;&nbsp;
                </div>
            </th>
            <th scope='col'>
				<div style='white-space: normal;color:black;'>
                        # of current cases assigned
                        &nbsp;&nbsp;
                </div>
            </th>
        </div>
DISP1;
	}

	function getCaseCount($id){
		$count = "N/A";
		global $db;
	    $query = "
	    	SELECT COUNT(*) AS 'count' 
	    	FROM cases c
	    	LEFT JOIN users u ON u.id = c.assigned_user_id
	    	WHERE u.id = '$id' and c.state != 'Closed'
	    ";
	    $results = $db->query($query);
		while ($row = $db->fetchByAssoc($results)) {
			$count = $row['count'];
		}
	    return $count;
	}

	function fetchEscalationUserMatrix(){
		$users = array();
		global $db;
	    //EXPECTED FORMAT :: NG894|balayeswanth.b@neogrowth.in
	    $query = "
			SELECT uce.id, uce.assigned_user, 
			u.department, 
			CONCAT(u.first_name, ' ', u.last_name) AS 'user_name',
			CONCAT(m1.first_name, ' ', m1.last_name) AS 'm1_user_name',
			CONCAT(m2.first_name, ' ', m2.last_name) AS 'm2_user_name',
			CONCAT(m3.first_name, ' ', m3.last_name) AS 'm3_user_name',
			count(c.assigned_user_id) as 'count'
			FROM user_case_escalation uce
			LEFT JOIN cases c ON uce.id = c.assigned_user_id
			LEFT JOIN users u ON uce.id = u.id
			LEFT JOIN users m1 ON uce.esc_1_user = m1.user_name
			LEFT JOIN users m2 ON uce.esc_2_user = m2.user_name
			LEFT JOIN users m3 ON uce.esc_3_user = m3.user_name
			WHERE c.state != 'Closed' 
			GROUP BY c.assigned_user_id
			ORDER BY count(c.assigned_user_id) desc;
	    	";
	    $results = $db->query($query);
	   	//print_r($results->num_rows);
    	//echo "<br>";
	    while ($row = $db->fetchByAssoc($results)) {
	    	$user = "";
	    	$user->id = "";
	    	$user->department = "";
	    	$user->count = "";
	    	$user->user_name = "";
	    	$user->m1_user_name = "";
	    	$user->m2_user_name = "";
	    	$user->m3_user_name = "";

	    	$user->id = $row['id'];
	    	$user->ngid = $row['assigned_user'];
	    	$user->department = $row['department'];
	    	$user->count = $row['count'];
	    	$user->user_name = $row['user_name'];
	    	$user->m1_user_name = $row['m1_user_name'];
	    	$user->m2_user_name = $row['m2_user_name'];
	    	$user->m3_user_name = $row['m3_user_name'];

	    	array_push($users, $user);
	    }
	    return $users;
	}

	function displayRows(){
		$users = $this->fetchEscalationUserMatrix();
		foreach ($users as $user) {
			echo "<tr style='border-bottom:1px solid #dddddd; align:left;'>";

	        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'><a href = 'index.php?module=scrm_Cases&action=CaseEscalationMatrixUpdate&ngid=$user->ngid&details=1&return_module=scrm_Cases&return_action=DetailView'>$user->user_name</a></td>";
	        
	        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'>$user->department</td>";
	        
	        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->m1_user_name</td>";

	        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->m2_user_name</td>";
	        
	        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='funded_date' style='white-space: normal;'> $user->m3_user_name</td>";

	        echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='reward_point' style='white-space: normal;'> $user->count </td>";
		}
	}

    function display(){
    	$this->displayHeaders();
    	$this->displayRows();   
    	echo "</table></div><hr>";
    }
}

?>