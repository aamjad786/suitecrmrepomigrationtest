<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once 'SendEmail.php';
require_once('modules/EmailTemplates/EmailTemplate.php');
// global db;
// echo "hi";die();

class Neo_CustomersViewassign extends SugarView {
    
    private $chartV;

    function __construct(){    
        parent::SugarView();
    }
    public $ticket_size_arr = array('1'=>'< 10.4 Lakhs',
                '2'=>'10.4-25 Lakhs',
                '3'=>'> 25 Lakhs');

    public function getRenewalRoles(){
        global $db;
        $q1 = "select * from acl_roles where name like '%renewal%' and deleted=0";
        $result = $db->query($q1);
        $arr = [];
        while($row = $db->fetchByAssoc($result)){
            $role = $row['name'];
            $arr[] = $role;
            // echo $role;
        }
        // var_dump($arr);
        return $arr;
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
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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

    
    function assign($ngid, $role, $sg, $removeRole, $removeSG){
        echo $role;
        if (!empty($role)) {
            $roleAssignment = $this->assignRole($ngid, $role, $removeRole);
        }
        if (!empty($sg)) {
            $sgAssignment = $this->assignSG($ngid, $sg, $removeSG);
        }
//        if (!$roleAssignment == 0 || !$sgAssignment == 0) {
//            $this->SendSuccessEmail($ngid, $role, $sg, $roleAssignment, $sgAssignment);
//        }
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
        } else {
            echo "<p style='color:red'>User '$ngid' not found in CRM. Click Submit to pull.</p>";
        }
        $renewal_user = $this->getRenewalUser($user->id);
        if (!empty($renewal_user)) {
            echo "<p><b>City: </b>".$renewal_user['city']."</p>";
            echo "<p><b>Role: </b>".$renewal_user['role']."</p>";
            $ticket_size_values = explode(",",$renewal_user['ticket_size']);
            $ticket_size_arr = $this->ticket_size_arr;
            echo "<p><b>Ticket Size: </b>";
            foreach ($ticket_size_values as $k=>$v){
                $v = (int)$v;
                echo $ticket_size_arr[$v].",";
            }
            echo "</p>";
        }
    }

    function getRenewalUser($user_id){
        global $db;
        $query = "select * from renewal_users where user_id='$user_id'";
        $res = $db->query($query);
        // echo $query;
        $row = $db->fetchByAssoc($res);
        // var_dump($row);
        return $row;
    }

    function processRenewalUsers($ngid,$ticket_size,$city,$role){
        global $db;
        $user = $this->getUserBean($ngid);
        if ($user) {
            $id = $user->id;
            $user_name = $user->user_name;
            $ticket_size = implode (",", $ticket_size);
            $city = implode (", ", $city);

            // echo $ngid;
            $row = $this->getRenewalUser($id);
            // var_dump($row);
            if(empty($row)){
                $query = "insert into renewal_users (user_id,user_name,ticket_size,city,role) 
                    values ('$id','$user_name','$ticket_size','$city','$role')";
                // print_r($query);
                $db->query($query);
                echo "Record Inserted<br/>";
            }else{
                if(!empty($ticket_size) && !empty($city) && !empty($role)){
                    $query = "update renewal_users set ticket_size='$ticket_size', city='$city', role='$role' where user_id='$id'";
                    // echo $query;
                    $db->query($query);
                    echo "Record updated<br/>";
                }else{
                    echo "<p style='color:red'>All fields are compulsory.</p><br/>";
                }
            }
        }

    }

    function display() {
        global $current_user;
        global $db;
        $ticket_size_arr = $this->ticket_size_arr;
        

        $header_style="style=\"background-color:black; padding: 10px;color:white;\"";
        $td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";

        $ngid = $_REQUEST['ngid'];
        // $mgrid = $_REQUEST['mgrid'];
        // $role = $_REQUEST['role'];
        // $sg = $_REQUEST['sg'];
        // $removeRole = $_REQUEST['removeRole'];
        // $removeSG = $_REQUEST['removeSG'];
        // $fieldsToBeUpdated = $_REQUEST['fields_to_be_updated'];


        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';
        echo $html = <<<HTMLFORM
        <h1><center><b>User Management</b></center></h1>
        <form action="#" method='post'>
        <h2><b>Manual</b></h2>
        <table>
        <tr>
            <td>Employee ID:</td>
            <td><input type='text' name='ngid' id='ngid' value='$_REQUEST[ngid]' /></td>
            <td>(Eg: ng618 or ng618,ng377)</td>
            <td colspan="1"><input type='submit' value='Get Details' id='details' name='details'/></td>
        </tr>
                
        <tr>
            <td>Ticket Size:</td>
            <td>
                <select name='ticket[]' id='ticket'  multiple>
                    <option value="0">Select ticket size</option>
                    <option value="1"> $ticket_size_arr[1]</option>
                    <option value="2"> $ticket_size_arr[2]</option>
                    <option value="3"> $ticket_size_arr[3]</option>
                </select>
            </td>
            
        </tr>
        <tr>
            <td>City:</td>
            <td>
                <select name='city[]' id='city' value='$_REQUEST[city]' multiple>
                    <option value="">Select city</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Pune">Pune</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Chandigarh">Chandigarh</option>
                    <option value="Jaipur">Jaipur</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Chennai">Chennai</option>
                    <option value="Hyderabad">Hyderabad</option>
                    <option value="Kolkata">Kolkata</option>
                </select>
            </td>
            
        </tr>

HTMLFORM;
        echo "
        <tr>
            <td>Role:</td>
            <td><select name='role' id='role' value='$_REQUEST[role]' > 
                <option value=''>Select a Role</option>";
        $roles = $this->getRenewalRoles();
        foreach($roles as $role){
            echo "<option value='$role'>$role</option>";
        }
        echo "
                </select>
            </td>
            <td>
                <input type='radio' name='removeRole' value='0' checked/> &nbspAdd &nbsp&nbsp
                <input type='radio' name='removeRole' value='1' /> &nbspRemove
            </td>
            
        </tr>";
        echo $html = <<<HTMLFORM1
        <tr>
            <td></td><td colspan="1"><input type='submit' value='Submit' id='single' name='single'/></td>
        </tr>
        </table>
        </form>
        <br>
        <h5>Status</h5>
        <br>
HTMLFORM1;

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

        
        if (!empty($_POST['single'])) {
            if (!empty($ngid)) {
                $ngids = explode(",",$ngid);
                for ($i=0; $i < sizeof($ngids); $i++) {
                    $eid = strtoupper(trim($ngids[$i], " "));
                    if (!empty($eid)) {
                        $ticket_size = $_REQUEST['ticket'];
                        $city = $_REQUEST['city'];
                        $role = $_REQUEST['role'];
                        $removeRole = $_REQUEST['removeRole'];
                        if($this->searchUser($eid)){
                            // $this->assign($eid, $role, $sg, $removeRole, $removeSG);
                            $this->processRenewalUsers($eid,$ticket_size,$city,$role);
                            $this->assign($eid, $role, $sg, $removeRole, $removeSG);
                        }
                        else{
                            echo "<p>User ".$eid." is not in CRM database.</p>";
                            if($this->pullUser($eid)){
                                $this->assign($eid, $role, $sg, $removeRole, $removeSG);
                            }
                        }
                        // if (!empty($mgrid)) {
                        //     $this->updateManager($eid, $mgrid);
                        // }
                        // $this->getDetails($eid);
                        echo "<br>";
                    }
                }
            }
            else{
                echo "<p style='color:red'>Employee ID cannot be empty</p>";
            }
        }

        
        
    }
}


