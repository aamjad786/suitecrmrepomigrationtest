<?php

require_once('include/MVC/View/views/view.list.php');
require_once('modules/Cases/CasesListViewSmarty.php');

class scrm_CasesViewUpdate_case_escalation_matrix extends SugarView {

    public function __construct(){
    	parent::__construct();
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

	function printUserDetails($userBean){
        echo "<p><b>$userBean->user_name</b></p>";
        echo "<p><b>Name: </b>$userBean->name</p>";
        echo "<p><b>Reports to: </b>$userBean->reports_to_name</p>";
        echo "<p><b>Department: </b>$userBean->department</p>";
        echo "<p><b>E-mail: </b>$userBean->email1</p>";
	}
	
    function getDetails($ngid) {
        $user = $this->getUserBean($ngid);
        if ($user) {
        	$this->printUserDetails($user);
        	echo "<hr>";
        } else {
            echo "<p style='color:red'>User '$ngid' not found in CRM.</p>";
            return;
        }
	    $level = 3;
	    $manager_id = $user->reports_to_id;
	    for($i=1;$i<=$level;$i++){
	        if(!empty($manager_id)){
	            $manager =$this->getUser($manager_id);
	            if(!empty($manager)){
	            	echo "<p><b>Escalation Level $i User</b></p>";
	            	$this->printUserDetails($manager);
	            	echo "<hr>";
	                $manager_id = $manager->reports_to_id;
	            }
	            else{
	                break;
	            }
	        }
	    }
    }

	function getUser($user_id){
	    $user = BeanFactory::getBean('Users',$user_id);
	    return $user;
	}

    function getUserID($ngid){
		global $db;
		$query 	= "SELECT id FROM users WHERE user_name = '$ngid' ";
		$results = $db->query($query);
		$id = "";
		// print_r($results);
		while($row = $db->fetchByAssoc($results)){
			$id = $row['id'];
		}
		return $id;
    }

    function getUserByNgid($ngid){
    	$user_id = $this->getUserID($ngid);
    	$user = $this->getUser($user_id);
    	if($user)
    		return $user;
    	else
    		return null;
    }

    function getEscalationDetails($ngid){
    	// $user = $this->getUserBean($ngid);
    	$user = $this->getUserByNgid($ngid);
        if ($user) {
        	$this->printUserDetails($user);
        	echo "<hr>";
        } else {
            echo "<p style='color:red'>User '$ngid' not found in CRM.</p>";
            return;
        }
		global $db;
		$query 	= "
			SELECT id, assigned_user, department,esc_1_user, esc_2_user, esc_3_user 
			FROM user_case_escalation
			WHERE id = '$user->id'
			";
		$results = $db->query($query);
		$i = 1;
		echo "<p><b>ESCALATION DETAILS</b></p>";
		while($row = $db->fetchByAssoc($results)){
			$user_bean   = $this->getUserByNgid($row['assigned_user']);
			$esc_1_user  = $this->getUserByNgid($row['esc_1_user']);
			$esc_2_user  = $this->getUserByNgid($row['esc_2_user']);
			$esc_3_user  = $this->getUserByNgid($row['esc_3_user']);
			echo "<p><b>SPOC :: </b> " 
				. $user_bean->user_name . " - " . $user_bean->first_name.' '.$user_bean->last_name
				. " - " .$user_bean->email1 ."</p>";
			echo "<p><b>Escalation Level 1 User :: </b> " 
				. $esc_1_user->user_name . " - " . $esc_1_user->first_name.' '.$esc_1_user->last_name
				. " - " .$esc_1_user->email1 ."</p>";
			echo "<p><b>Escalation Level 2 User :: </b> " 
				. $esc_2_user->user_name . " - " . $esc_2_user->first_name.' '.$esc_2_user->last_name
				. " - " .$esc_2_user->email1 ."</p>";
			echo "<p><b>Escalation Level 3 User :: </b> " 
				. $esc_3_user->user_name . " - " . $esc_3_user->first_name.' '.$esc_3_user->last_name
				. " - " .$esc_3_user->email1 ."</p>";
		}

    }

    function getCrmUserDetails(){
    	global $db;
    	$users = array();
    	$query = "SELECT user_name,CONCAT(first_name, ' ', last_name) AS 'name' FROM users WHERE deleted = 0 and status = 'Active'";
    	$results = $db->query($query);
    	$i=0;
    	while($row = $db->fetchByAssoc($results)){
    		if(!empty($row['user_name']) && !empty($row['name'])){
    			// $users[$row['user_name']] = $row['name'];
    			$users[$i++] = $row['user_name'].":".$row['name'];
    		}
    	}	
    	// print_r($users);
    	$users = implode(",", $users);
    	return $users;
    }


    function displayForm(){
    	$users = $this->getCrmUserDetails();
    ?>
		<style>
		table td{
			padding:5px;
		}
		</style>
		<script>
			$(document).ready(function(){
			var cam_users = "<?php echo $users?>";
			cam_users = cam_users.split(",");
			jQuery.each(cam_users, (index, item) => {
				console.log(index + "=>" + item);
			    item = item.split(":");
			    $('#esc_user').append("<option value='" + item[0] + "'>"+item[1]+"</option>");
			});
			});
		</script>
	<?php
        echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';
        echo $html = <<<HTMLFORM
		<h1><center><b>Escalation Matrix Management</b></center></h1>
		<form action="#" method='post'>
		<h2><b>Manual</b></h2>
		<table>
		<tr>
			<td>Employee ID:</td>
			<td>
				<input list="esc_user" name="ngid" id="ngid" value='$_REQUEST[ngid]'/>
				<datalist id="esc_user">
				</datalist>
			</td>
			<td>(Eg: ng618 or ng618,ng377)</td>
			<td colspan="1"><input type='submit' value='Get Escalation Matrix' id='details' name='details'/></td>
			<td colspan="1"><input type='text' id='details' name='email_id' value='$_REQUEST[email_id]'/></td>
			<td colspan="1"><input type='submit' value='Update Email' id='details' name='update_email'/></td>
		<tr>
			<td>Escalation User 1:</td>
			<td>
				<input list="esc_user" name="esc_user_1" id="esc_user_1" value='$_REQUEST[esc_user_1]'/>
				<datalist id="esc_user">
				</datalist>
			</td>
		</tr>
		<tr>
			<td>Escalation User 2:</td>
			<td>
				<input list="esc_user" name="esc_user_2" id="esc_user_2" value='$_REQUEST[esc_user_2]'/>
				<datalist id="esc_user">
				</datalist>
			</td>
		</tr>
		<tr>
			<td>Escalation User 3:</td>
			<td>
				<input list="esc_user" name="esc_user_3" id="esc_user_3" value='$_REQUEST[esc_user_3]'/>
				<datalist id="esc_user">
				</datalist>
			</td>
		</tr>
		<tr>
			<td></td><td colspan="1"><input type='submit' value='Update Escalation Matrix' id='single' name='single'/></td>
			<td><input tabindex="2" title="Clear" onclick="SUGAR.searchForm.clear_form(this.form); return false;" class="button" type="button" name="clear" id="search_form_clear" value="Clear"></td>
		</tr>
		</table>
		</form>
		<br>
		<h5>Status</h5>
		<br>
HTMLFORM;
        echo $script = <<<JS
		<style>
		table td{
			padding:5px;
		}
		</style>
JS;
    }

    function displayUserDetails(){
		$ngid = $_REQUEST['ngid'];
        if (!empty($_REQUEST['details'])) {
            if (!empty($ngid)) {
                $ngids = explode(",", $ngid);
                for ($i = 0; $i < sizeof($ngids); $i++) {
                    $eid = strtoupper(trim($ngids[$i]," "));
                    if (!empty($eid)) {
                        $this->getEscalationDetails($eid);
                        echo "<br>";
                    }
                }
            } else {
                echo "<p style='color:red'>Employee ID cannot be empty</p>";
            }
        }
    }

    function updateIntoEscalationTable($ngid,$esc_user_1,$esc_user_2,$esc_user_3){
    	$assigned_user_bean = $this->getUserByNgid($ngid);
    	$esc_user_1_bean 	= $this->getUserByNgid($esc_user_1);	
    	$esc_user_2_bean 	= $this->getUserByNgid($esc_user_2);
    	$esc_user_3_bean 	= $this->getUserByNgid($esc_user_3);
    	if(!empty($assigned_user_bean->id) && !empty($esc_user_1_bean->id)){
    		$assigned_user_detail	= $assigned_user_bean->user_name;
    		$esc_user_1_details 	= $esc_user_1_bean->user_name;
    		$esc_user_2_details 	= $esc_user_2_bean->user_name;
    		$esc_user_3_details 	= $esc_user_3_bean->user_name;
	    	global $db;
	    	$query = "
		    	INSERT INTO user_case_escalation 
		    	(id, assigned_user, department,esc_1_user, esc_2_user, esc_3_user, date_created, is_manual_update) 
		    	VALUES(
		    		'$assigned_user_bean->id', '$assigned_user_detail','$assigned_user_bean->department', 
		    		'$esc_user_1_details', '$esc_user_2_details', '$esc_user_3_details', NOW(), 1
		    		) 
		    	ON DUPLICATE KEY 
		    	UPDATE 
		    		esc_1_user = '$esc_user_1_details',
		    		esc_2_user = '$esc_user_2_details',
		    		esc_3_user = '$esc_user_3_details',
		    		date_modified = NOW(),
		    		is_manual_update = 1
		    	";
		    // print_r($query);
		    $results = $db->query($query);
		    if($results){
		    	echo "<p>Escalation matrix updated for $assigned_user_bean->user_name</p>";
		    }
		    else{
		    	echo "<p style='color:red'>Updation Failed. Please Contact Administrator</p>";
		    }
    	}
    	else{
			echo "<p style='color:red'>User Not found in CRM.</p>";
			echo "<p>SPOC/Assigned User :: $ngid</p>";
			echo "<p>Escalation Level 1 user : $esc_user_1</p>";
			echo "<p>Escalation Level 2 user : $esc_user_2</p>";
			echo "<p>Escalation Level 3 user : $esc_user_3</p>";
			echo "<hr>";
    	}
    	//id, assigned_user, department,esc_1_user, esc_2_user, esc_3_user
    	// INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19
    }

    function updateEmail($ngid,$email_id){
    	$user_bean = $this->getUserByNgid($ngid);
    	$user_bean->email1 = $email_id;
    	$user_bean->save();
    	echo "<p>Email updated for $user_bean->user_name - $email_id</p>";
    }

    function handleSubmittedForm(){
		$ngid 		= $_REQUEST['ngid'];
		$esc_user_1 = $_REQUEST['esc_user_1'];
		$esc_user_2 = $_REQUEST['esc_user_2'];
		$esc_user_3 = $_REQUEST['esc_user_3'];
		$email_id 	= $_REQUEST['email_id'];
		$id_list = array();
        if (!empty($_POST['single'])) {
            if (!empty($ngid) && !empty($esc_user_1)) {
            	array_push($id_list, $ngid);
            	array_push($id_list, $esc_user_1);
            	array_push($id_list, $esc_user_2);
            	array_push($id_list, $esc_user_3);
            	if(count(array_unique($id_list))!=4){
            		echo "<p style='color:red'>Duplication is not allowed. Please try again</p>";
            		return ;
            	}
            	$this->updateIntoEscalationTable($ngid,$esc_user_1,$esc_user_2,$esc_user_3);
			}
			else{
                echo "<p style='color:red'>Employee ID,Escalation Level 1 are mandatory, cannot be empty</p>";
            }
        }
        if(!empty($_POST['update_email'])){
        	if(!empty($ngid) && !empty($email_id)){
        		$this->updateEmail($ngid,$email_id);
        	}
			else{
                echo "<p style='color:red'>Employee ID,Email details are mandatory, cannot be empty</p>";
            }
        }
    }

    function display(){
    	global $current_user, $sugar_config;
		$permitted_users = $sugar_config['up_case_esc_matrix_permitted_user'];
		if (!$current_user->is_admin  && !(in_array(strtoupper($current_user->user_name), $permitted_users))){
		    die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
		}
    	$this->displayForm();
    	$this->handleSubmittedForm();
    	$this->displayUserDetails();
    }
}

?>