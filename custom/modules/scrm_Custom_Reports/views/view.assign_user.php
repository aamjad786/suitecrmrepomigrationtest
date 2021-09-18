<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once 'custom/include/SendEmail.php';
require_once('modules/EmailTemplates/EmailTemplate.php');

class scrm_Custom_ReportsViewassign_user extends SugarView {
	
	private $chartV;
    private $email_body = "";

    function __construct(){    
        parent::SugarView();
    }

    function createEmailBody($ngid, $role, $sg, $roleAssignment, $sgAssignment){
        global $current_user;
        $body = '$auser_name $role_body $sg_body in CRM by $assignee_full_name ($assignee_user_name).<br>';

        $role_body = "";
        $sg_body = "";

        if ($roleAssignment == 1) {
            $role_body.="is assigned to $role role";
        }else if($roleAssignment == 2){
            $role_body.="is removed from $role role";
        }
        if ($roleAssignment!=0 && $sgAssignment!=0) {
                $sg_body.=" and ";
        }
        if ($sgAssignment == 1) {
            $sg_body.="is added to $sg securitygroup";
        }else if($sgAssignment == 2){
            $sg_body.="is removed from $sg securitygroup";
        }

        $body = str_replace('$auser_name', $ngid, $body);
        $body = str_replace('$arole_name', $role, $body);
        $body = str_replace('$role_body', $role_body, $body);
        $body = str_replace('$sg_body', $sg_body, $body);
        $body = str_replace('$assignee_full_name', $current_user->name, $body);
        $body = str_replace('$assignee_user_name', $current_user->user_name, $body);
        return $body;
    }

    function SendSuccessEmail(){
    	global $current_user;
        $send = new SendEmail();
        $template = new EmailTemplate();
        $template->retrieve_by_string_fields(array('name' => 'Role Assignment' ));

        $body = $template->body_html;
        $email_subject = $template->subject;

        $body = str_replace('$body', $this->email_body, $body);

        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (strpos($url, 'dev') !== false) {
            $send->send_email_to_user($email_subject,$body,array('ramesh.a@neogrowth.in'),array('nikhil.kumar@neogrowth.in','hemanth.vaddi@neogrowth.in'));
        }else if (strpos($url, 'crm.advancesuite.in') !== false) {
            $send->send_email_to_user($email_subject,$body,array('ramesh.a@neogrowth.in'),array('nikhil.kumar@neogrowth.in','hemanth.vaddi@neogrowth.in'));
        }else{
            $send->send_email_to_user($email_subject,$body,array('hemanth.vaddi@neogrowth.in'),array('nikhil.kumar@neogrowth.in'));
        }
    }

	function assignRole($ngid, $role, $remove=0){
        global $db;
        $q1 = "SELECT id FROM users WHERE user_name='$ngid' and deleted=0";
        $result = $db->query($q1);
		while($row = $db->fetchByAssoc($result)){
            $userID = $row['id'];
        }

        if (empty($userID)) {
            echo "<p style='color:red'>Unable to assign user $ngid. Please contact Tech Support</p>";
            return false;
        }

        $q2 = "SELECT id FROM acl_roles where name='$role'";
        $result = $db->query($q2);
		while($row = $db->fetchByAssoc($result)){
            $roleID = $row['id'];
        }

        if (empty($roleID)) {
            echo "<p style='color:red'>No such role called $role. Please contact Tech Support</p>";
            return false;
        }

	    if ($remove==0) {
            echo "<p>Assigning $ngid to $role role.....</p>";
	    }else{
            echo "<p>Removing $ngid from $role role.....</p>";
        }
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        
        global $sugar_config;
        $httpServer = $sugar_config['host_name'];

        $url = $protocol . $httpServer . $_SERVER['REQUEST_URI'];
    	$url = substr($url,0,strpos($url,"?"))."?entryPoint=UserRoleAssignment";
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $strCookie = 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';
        $headers = array(
            "cache-control: no-cache",
            "Content-type: application/x-www-form-urlencoded"
        );
        session_write_close();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_COOKIE, $strCookie );
        curl_setopt($ch, CURLOPT_POSTFIELDS, "userID=$userID&roleID=$roleID&remove=$remove");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
		if(strpos(html_entity_decode($response), 'Added') !== false) {
            echo "<p style='color:green'>Successfully Assigned.</p>";
            return 1;
		}else if(strpos(html_entity_decode($response), 'Removed') !== false){
            echo "<p style='color:green'>Successfully Removed.</p>";
            return 2;
		}else{
            echo "<p style='color:red'>Unable to assign/remove user $ngid to $role. Please contact Tech Support</p>";
            if ($err) {
                echo "Error #:" . $err;
            }
            return 0;
        }
    }

	function assignSG($ngid, $sg, $remove=0){
        global $db;
        $q1 = "SELECT id FROM users WHERE user_name='$ngid' and deleted=0";
        $result = $db->query($q1);
		while($row = $db->fetchByAssoc($result)){
            $userID = $row['id'];
        }

        if (empty($userID)) {
            echo "<p style='color:red'>Unable to assign user $ngid. Please contact Tech Support</p>";
            return 0;
        }

        $q2 = "SELECT id FROM securitygroups where name='$sg'";
        $result = $db->query($q2);
		while($row = $db->fetchByAssoc($result)){
            $sgID = $row['id'];
        }

        if (empty($sgID)) {
            echo "<p style='color:red'>No such Security Group called $sg. Please contact Tech Support</p>";
            return 0;
        }

	    if ($remove==0) {
            echo "<p>Assigning $ngid to $sg Security Group.....</p>";
	    }else{
            echo "<p>Removing $ngid from $sg Security Group.....</p>";
        }

        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        global $sugar_config;
        $httpHost = $sugar_config['host_name'];

        $url = $protocol . $httpHost . $_SERVER['REQUEST_URI'];
    	$url = substr($url,0,strpos($url,"?"))."?entryPoint=UserRoleAssignment";

        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $strCookie = 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';
        $headers = array(
            "cache-control: no-cache",
            "Content-type: application/x-www-form-urlencoded"
        );
        session_write_close();
        $ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch,CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_COOKIE, $strCookie );
        curl_setopt($ch, CURLOPT_POSTFIELDS, "userID=$userID&sgID=$sgID&remove=$remove");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
		if(strpos(html_entity_decode($response), 'Added') !== false) {
            echo "<p style='color:green'>Successfully Assigned.</p>";
            return 1;
		}else if(strpos(html_entity_decode($response), 'Removed') !== false){
            echo "<p style='color:green'>Successfully Removed.</p>";
            return 2;
		}else{
            echo "<p style='color:red'>Unable to add/remove user $ngid to $sg. Please contact Tech Support</p>";
            if ($err) {
                echo "Error #:" . $err;
            }
            return 0;
        }
    }

	function assign($ngid, $role, $sg, $removeRole, $removeSG){
        if (!empty($role)) {
            $roleAssignment = $this->assignRole($ngid, $role, $removeRole);
        }
        if (!empty($sg)) {
            $sgAssignment = $this->assignSG($ngid, $sg, $removeSG);
        }
        if (!$roleAssignment == 0 || !$sgAssignment == 0) {
            $this->email_body .= $this->createEmailBody($ngid, $role, $sg, $roleAssignment, $sgAssignment);
        }
    }

    function pullUser($ngid){
        require_once('PullUser.php');
        $pullUser = new PullUser();
        if(!empty($pullUser->insertUser($ngid))){
            echo "<p>Successfully pulled User $ngid</p>";
            return true;           
        }
        else{
            echo "<p style='color:red'>Unable to pull user $ngid to CRM. Try again after some time. If its still the same contact IT admin / IT support for further clarifications.</p>";
            return false;
        }
    }

    function searchUser($ngid){
        global $db;
		echo "<p><b>Searching User ".$ngid.".....</b></p>";
        $query = "SELECT user_name,id FROM users WHERE user_name='$ngid' and deleted=0";
        $result = $db->query($query);
		while($row = $db->fetchByAssoc($result)){
			if (strtoupper($row['user_name'])==strtoupper($ngid)) {
				echo "<p style='color:green'>User ".$ngid." exists in CRM.</p>";
                return true;
            }
        }
        return false;
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

	function updateManager($usr_ng_id, $mgr_ng_id){
        $usr = $this->getUserBean($usr_ng_id);
        $mgr = $this->getUserBean($mgr_ng_id);

		if(!$usr){
			if($this->pullUser($usr_ng_id))
                $usr = $this->getUserBean($usr_ng_id);
        }
		if(!$mgr){
			if($this->pullUser($mgr_ng_id))
                $mgr = $this->getUserBean($mgr_ng_id);
        }
		if($usr && $mgr){
            $updated_count++;
			$usr->reports_to_id=$mgr->id;
            $usr->save();
            echo "<p style='color:green'>Updated $mgr_ng_id as manager of $usr_ng_id</p>";
		}else{
            echo "<p style='color:red'>Failed to create relationship for $usr_ng_id</p>";
        }
    }

    function getDetails($ngid) {
        $user = $this->getUserBean($ngid);
        if ($user) {
            $userBean = BeanFactory::getBean('Users', $user->id);
            echo "<p><b>GUID:</b> $userBean->id</p>";
            echo "<p><b>NGID:</b> $userBean->user_name</p>";
            echo "<p><b>Name: </b>$userBean->name</p>";
            echo "<p><b>Reports to: </b>$userBean->reports_to_name</p>";
            echo "<p><b>Designation: </b>$userBean->designation_c</p>";
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

    function updateFields($ngid, $fieldsToBeUpdated, $updateFieldsFrom) {
        if (!empty($ngid) && !empty($fieldsToBeUpdated) &&!(empty($updateFieldsFrom))) {
            if($updateFieldsFrom == "ad"){
                $resultData = getDataFromAS($ngid);    
            }
            else if($updateFieldsFrom == "adrenalin"){
                $resultData = getDataFromAdrenalin($ngid);
            }
            else{
                echo "<p style='color:red'>Invalid kindly select Adrenalin or AD</p>";
            }
            
            if (!empty($resultData)) {
                $department = $resultData['department'][0];
                $manager_dn = $resultData['manager'][0];
                $email = $resultData['mail'][0];
                $userId = $resultData['objectGUID'][0];
                $employee_code = $resultData['employee_code'][0];
                if($userId == "-" & !empty($employee_code)){
                    $bean = $this->getUserBean($employee_code);
                }
                else{
                    $bean = BeanFactory::getBean('Users', $userId);
                }
                $fieldsUpdated = '';
                
                if (in_array("Email", $fieldsToBeUpdated)) {
                	if (!empty($email)) {
	                    $bean->email1 = $email;
	                    $fieldsUpdated .= "Email, ";
                	}else{
                		echo "<p style='color:red'>Email ID for the user '$ngid' does not exist in ".$updateFieldsFrom.".</p>";
                	}
                }
                if (in_array("Department", $fieldsToBeUpdated)) {
                	if (!empty($department)) {
                    	$bean->department = $department;
	                    $fieldsUpdated .= "Department, ";
                	}else{
                		echo "<p style='color:red'>Department for the user '$ngid' does not exist in ".$updateFieldsFrom."</p>";
                	}
                }
                if (in_array("Manager", $fieldsToBeUpdated)) {
                    if (!empty($manager_dn)) {
                        if($updateFieldsFrom == "ad"){
                            $query = "users.description = '$manager_dn'";
                        }
                        elseif ($updateFieldsFrom == "adrenalin") {
                            $query = "users.user_name = '$manager_dn'";
                        }
                        $usersBean = BeanFactory::getBean('Users');
                        $managerData = $usersBean->get_full_list('', $query);
                        if (!empty($managerData)) {
                            $managerId = $managerData[0]->id;
                            $bean->reports_to_id = $managerId;
                            $fieldsUpdated .= "Manager";
                        } else {
                            echo "<p style='color:red'>Manager information for the user '$ngid' does not exist in ".$updateFieldsFrom.".</p>";
                        }
                    } else {
                        echo "<p style='color:red'>Mananger for the '$ngid' does not exist in CRM. Please pull the manager first.</p>";
                    }
                }
                echo "<p style='color:green'> $fieldsUpdated is updated successfully for the user '$ngid'.</p>";
               $bean->save();
            }
        } else {
            echo "<p style='color:red'>User '$ngid' not found in CRM. Click Submit to pull.</p>";
        }
    }

    //admin role, not is_admin/system administrator
    function isAdminRoleUser($roles){
        $results = false;
        if(empty($roles)) {
            return $results;
        }
        foreach ($roles as $role) {
            if(stripos($role,"admin") !== false){
                $results = true;
                break;
            }
        }   
        return $results;
    }

function getDepartment_names(){
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
        $department_names = $this->getDepartment_names();
        echo $html = <<<DEPARTMENTFORM
        <h1><center><b>User Department Update</b></center></h1>
        <form action="#" method='post'>
        <table style="border-collapse: separate; border-spacing: 15px;">
        <tr>
            <td>Employee ID:</td>
            <td>
                <input list="esc_user" name="ngid" id="ngid" value='$_REQUEST[ngid]' required/>
                <datalist id="esc_user">
                </datalist>
            </td>
        </tr>
        <tr>
            <td>Department:</td>
            <td>
                <select name='department' id='department' value='$_REQUEST[department]' required> 
                    <option value="" selected disabled>Select a Department</option>
DEPARTMENTFORM;
        foreach ($department_names as $department) {
            echo "<option value='$department'>$department</option>";  
        }
        echo $html = <<<DEPARTMENTFORM1
                </select>   
            </td>
        </tr>
        <tr>
            <td colspan="1"><input type='submit' value='Update Department' id='update_department' name='update_department'/></td>
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
        // print_r($department);
        $isUpdate = $_REQUEST['update_department'];
        global $timedate;
        global $current_user;
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
    function display() {
        global $current_user;
        global $db;
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $roles = ACLRole::getUserRoleNames($current_user->id);
        if (strpos($url, 'crm.advancesuite.in') !== false) {
            $permitted_users = array("NG377","NG894","NG619","NG618","NG538","NG1039","NG171");
            if (!$current_user->is_admin && !in_array(strtoupper($current_user->user_name), ($permitted_users))  && !$this->isAdminRoleUser($roles)) {
                // print_r("here too:P");
                die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
            }
        }
        // print_r(!$current_user->is_admin);echo "<br>";
        // print_r(!$this->isAdminRoleUser($roles));echo "<br>";
        // print_r(!in_array($current_user->user_name, $permitted_users));echo "<br>";
		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
		$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";

        $ngid = $_REQUEST['ngid'];
        $mgrid = $_REQUEST['mgrid'];
        $role = $_REQUEST['role'];
        $sg = $_REQUEST['sg'];
        $removeRole = $_REQUEST['removeRole'];
        $removeSG = $_REQUEST['removeSG'];
        $fieldsToBeUpdated = $_REQUEST['fields_to_be_updated'];
        
        global $db;
        $queryToGetRoles = "SELECT acl_roles.name from acl_roles where deleted = 0";
        $dynamicRoles = "";
        $results = $db->query($queryToGetRoles);
        $response = array();
        while($row = $db->fetchByAssoc($results)){
            $role = $row['name'];
            $dynamicRoles .= "<option value='".$role."'>$role</option>";
        }
        $users = $this->getCrmUserDetails();
    ?>
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
		<h1><center><b>User Management</b></center></h1>
		<form action="#" method='post'>
		<h2><b>Manual</b></h2>
		<table>
		<tr>
			<td>Employee ID:</td>
			<td>
                <input list="esc_user" name="ngid" id="ngid" value='$_REQUEST[ngid]' required/>
                <datalist id="esc_user">
                </datalist>
			<td>(Eg: ng618 or ng618,ng377)</td>
			<td colspan="1"><input type='submit' value='Get Details' id='details' name='details'/></td>
		</tr>
                <tr>
                    <td>Update fields</td>
                    <td>                      
                    <input type="checkbox" name="fields_to_be_updated[]" value="Manager"> Manager<br>
                    <input type="checkbox" name="fields_to_be_updated[]" value="Department"> Department<br>
                    <input type="checkbox" name="fields_to_be_updated[]" value="Email"> Email<br>
                    </td>
                    <td>
                        <input type='radio' name='updateFieldsFrom' value='adrenalin' checked/> &nbspAdrenalin &nbsp&nbsp
                        <input type='radio' name='updateFieldsFrom' value='ad' /> &nbspAD
                    </td>
                    <td colspan="1"><input type='submit' value='Update Fields' id='update_from' name='update_from'/></td>
		</tr>
		<tr>
			<td>Manager ID:</td>
			<td><input type='text' name='mgrid' id='mgrid' value='$_REQUEST[mgrid]'/></td>
		</tr>
		<tr>
			<td>Role:</td>
			<td>
				<select name='role' id='role' value='$_REQUEST[role]'> 
					<option value="">Select a Role</option>

                                         $dynamicRoles

				</select>
			</td>
			<td>
				<input type='radio' name='removeRole' value='0' checked/> &nbspAdd &nbsp&nbsp
				<input type='radio' name='removeRole' value='1' /> &nbspRemove
			</td>
		</tr>
		<tr>
			<td>Security Group:</td>
			<td>
				<select name='sg' id='sg' value='$_REQUEST[sg]'>
					<option value="">Select a Security Group</option>
                    <option value="Sales Team">Sales Team</option>
					<option value="case agent">Case Agent</option>
					<option value="case manager">Case Manager</option>
					<option value="Call Center-Tata BSS">Call Center-Tata BSS</option>
					<option value="Call Center-Kenkei2">Call Center-Kenkei</option>
					<option value="Call Center-KServe">Call Center-KServe</option>
					<option value="Marketing">Marketing</option>
					<option value="Marketing Team">Marketing Team</option>
					<option value="No Access Group">No Access Group</option>
				</select>
			</td>
			<td>
				<input type='radio' name='removeSG' value='0' checked/> &nbspAdd &nbsp&nbsp
				<input type='radio' name='removeSG' value='1' /> &nbspRemove
			</td>
		</tr>
		<tr>
			<td></td><td colspan="1"><input type='submit' value='Submit' id='single' name='single'/></td>
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

        if (!empty($_POST['update_from'])) {
            if (!empty($ngid)) {
                if (!empty($fieldsToBeUpdated)) {
                    $ngids = explode(",", $ngid);
                    for ($i = 0; $i < sizeof($ngids); $i++) {
                        $eid = strtoupper(trim($ngids[$i], " "));
                        if (!empty($eid)) {
                            if($this->searchUser($eid)){
                                $this->updateFields($eid, $fieldsToBeUpdated,$_POST['updateFieldsFrom']);
                                echo "<br>";
                            }
                            else{
                                echo "<p>User ".$eid." is not in CRM database.</p>";
                                if($this->pullUser($eid)){
                                    $this->updateFields($eid, $fieldsToBeUpdated,$_POST['updateFieldsFrom']);
                                    echo "<br>";
                                }
                            }
                        }
                    }
                } else {
                    echo "<p style='color:red'>Please choose atleast one field to be updated</p>";
                }
            } else {
                echo "<p style='color:red'>Employee ID cannot be empty</p>";
            }
        }
        if (!empty($_POST['single'])) {
            if (!empty($ngid)) {
				$ngids = explode(",",$ngid);
				for ($i=0; $i < sizeof($ngids); $i++) {
                    $eid = strtoupper(trim($ngids[$i], " "));
                    if (!empty($eid)) {
                    $role = $_REQUEST['role'];
						if($this->searchUser($eid)){
                            $this->assign($eid, $role, $sg, $removeRole, $removeSG);
						}
						else{
							echo "<p>User ".$eid." is not in CRM database.</p>";
							if($this->pullUser($eid)){
                                $this->assign($eid, $role, $sg, $removeRole, $removeSG);
                            }
                        }
                        if (!empty($mgrid)) {
                            $this->updateManager($eid, $mgrid);
                        }
                        // $this->getDetails($eid);
                        echo "<br>";
                    }
                }
			}
			else{
                echo "<p style='color:red'>Employee ID cannot be empty</p>";
            }
        }

        echo "<hr>";
        $this->displayDepartmentUpdate();


        echo $html = <<<HTMLFORM2
		<br><hr><br>
		<h2><b>File based </b></h2>
		<form action="#" method='post' enctype= 'multipart/form-data'>
		<p><b>Note:</b> Only spreadsheets are accepted. Please ensure that the sheet has headings in first row as follows (without any line breaks)</p>
		<table>
			<tr>
				<td></td>
				<td>Employee ID as <b>Emp Code</b></td>
			</tr>
			<tr>
				<td></td>
				<td>Manager ID as <b>Reporting Supervisor EMP CODE</b></td>
			</tr>
			<tr>
				<td></td>
				<td>Role as <b>Role</b></td>
			</tr>
			<tr>
				<td></td>
				<td>Security Group as <b>Security Group</b></td>
			</tr>
			<br>
			<tr>
				<td>File: </td>
				<td><input type="file" name="sheet" id="sheet" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"></td>
			</tr>
			<tr>
				<td></td><td colspan="1">
				<input type='submit' value='Submit' id='multiple' name='multiple'/></td>
			</tr>
		</table>
    	</form>
		<br>
		<h5>Status</h5>
		<br>
HTMLFORM2;

        if (!empty($_POST['multiple'])) {
            require_once 'excel_reader2.php';
            $target_dir = "upload/";
            $errors = array();
            $file_name = $_FILES['sheet']['name'];
            $file_size = $_FILES['sheet']['size'];
            $file_tmp = $_FILES['sheet']['tmp_name'];
            $file_type = $_FILES['sheet']['type'];
			$file_ext = strtolower(end(explode('.',$_FILES['sheet']['name'])));

            if (!empty($file_name)) {
				$extensions= array("csv","xls","xlsx");
				if(in_array($file_ext,$extensions) === false){
					$errors[]="Extension not allowed, please choose an xls or csv file.";
                }

				if($file_size > 2097152){
					$errors[]='File size must be less 2 MB';
                }

				if(empty($errors)==true){
			        move_uploaded_file($file_tmp,"upload/".$file_name);
			    }else{
                    print_r($errors);
                }

                $target_file = $target_dir . basename($_FILES["sheet"]["name"]);
                $data = new Spreadsheet_Excel_Reader($target_file);
                echo "<br>";
                $column_list = $data->sheets[0]['cells'][1];
                $length = sizeof($data->sheets[0]['cells']);
                $eid_row = array_search('Emp Code', $column_list);
                $mid_row = array_search('Reporting Supervisor EMP CODE', $column_list);
                $role_row = array_search('Role', $column_list);
                $sg_row = array_search('Security Group', $column_list);

				$total_count=0;
				$updated_count=0;
				for ($i = 2; $i <= $length ; $i++) {
                    $total_count++;
                    $eid = $data->sheets[0]['cells'][$i][$eid_row];
                    $mid = $data->sheets[0]['cells'][$i][$mid_row];
                    $role = $data->sheets[0]['cells'][$i][$role_row];
                    $sg = $data->sheets[0]['cells'][$i][$sg_row];
                    if (!empty($eid)) {
                        $eid = strtoupper(trim($eid, " "));
						if($this->searchUser($eid)){
                            $this->assign($eid, $role, $sg, 0, 0);
						}else{
							echo "<p>User ".$eid." is not in CRM database.</p>";
							if($this->pullUser($eid)){
                                $this->assign($eid, $role, $sg, 0, 0);
                            }
                        }
                        $mid = strtoupper($mid);
						if(!empty($mid)){
							if($this->updateManager($eid, $mid)){
                                $updated_count++;
                            }
                        }
                        echo "<br>";
                    }
                }
                $this->SendSuccessEmail();
			}else{
                echo "<p style='color:red'>Please choose a file</p>";
            }
        }
    }
}
function getDataFromAS($ngId) {
    try {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "3009",
            CURLOPT_URL => getenv("SCRM_AD_UTILITY_HOST")."/json2ldap",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"method\"  : \"ldap.search\",\n  \"params\"  : { \n  \"filter\": \"(sAMAccountName=$ngId)\",\n  \"attributes\": \"*\",\n    \"binaryAttributes\": [\"objectGUID\", \"objectSid\"]\n  },\n  \"decoded\" : true\n}",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
        $results = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            die();
        }
        $results = (json_decode($results, true));
        $results = $results['matches'];
        foreach ($results as $result) {
            try {
                return $result;
            } catch (Exception $e) {
                echo "Exception in saving $ngId" . $e->getMessage();
            }
        }
    } catch (Exception $e) {
        echo "Exception occured in script execution" . $e->getMessage();
    }
}

function getDataFromAdrenalin($ngid){
    global $db;
    $query = "select employee_code, company_name, mail_id, reporting_manager, designation_name, department_name, modified_on from adrenalin_user_info where employee_code ='".$ngid."'";
    $results = $db->query($query);
    $response = array();
    while($row = $db->fetchByAssoc($results)){
        $response['employee_code'][0] = $row['employee_code'];
        $response['mail'][0] = $row['mail_id'];
        $response['manager'][0] = $row['reporting_manager'];
        $response['department'][0] = $row['department_name'];
        $response['objectGUID'][0] = "-";
    }
    return $response;
}

?>
