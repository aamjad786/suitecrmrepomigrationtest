<?php

class OpportunitiesViewSpoc_mapping_marketing extends SugarView {
	
    
    public function __construct() {
		parent::__construct();
	}

	
    function printUserDetails($userBean){
		global $db;
		$query = "SELECT * FROM cluster_city_mapping where city='$userBean'";
		
    	$results = $db->query($query);
		$row = $db->fetchByAssoc($results);
		$bean=BeanFactory::getbean('Users');
		$ngid=$row['spoc_id'];
		$query="users.user_name='$ngid'";
		$bean=$bean->get_full_list('',$query);
	
        echo "<p><b>City : </b>".$row['city']."</p>";
		echo "<p><b>SPOC ID : </b>".$row['spoc_id']."</p>";
		echo "<p><b>SPOC Name : </b>".$bean[0]->name."</p>";
		echo "<p><b>Last Update By: </b>".strtoupper($row['last_updated_by'])."</p>";
		echo "<p><b>Last Update Date: </b>".(!empty($row['last_updated_date'])?date('d-m-Y',strtotime($row['last_updated_date'])):'')."</p>";
      
	}
	function getSpocDetails($city){
    	
        if ($city) {
        	$this->printUserDetails($city);
        	echo "<hr>";
        } else {
            echo "<p style='color:red'>City '$city' not found in CRM.</p>";
            return;
		}
	}

	function getCrmSpocDetails(){
    	global $db;
    	$users = array();
    	$query = "SELECT * FROM cluster_city_mapping";
    	$results = $db->query($query);
		$i=0;
		
    	while($row = $db->fetchByAssoc($results)){
		
    		if(!empty($row['spoc_id']) && !empty($row['city'])){
    			
    			$users[$i++] = $row['spoc_id'].":".$row['city'];
    		}
		}	
		
		$users = implode(",", $users);
		
    	return $users;
	}
	
	function getCrmUserDetails(){
    	global $db;
    	$users = array();
    	$query = "SELECT user_name,CONCAT(first_name, ' ', last_name) AS 'name' FROM users WHERE deleted = 0 and status = 'Active'";
    	$results = $db->query($query);
    	$i=0;
    	while($row = $db->fetchByAssoc($results)){
    		if(!empty($row['user_name']) && !empty($row['name'])){
    			
				$users[$i++] = $row['user_name'].":".$row['name'];
				
    		}
    	}	
    	// print_r($users);
		$users = implode(",", $users);
		
		return $users;
    }

	function displayForm(){

		$users = $this->getCrmUserDetails();
		
		$spoc = $this->getCrmSpocDetails();
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
			$(document).ready(function(){
				var cam_users = "<?php echo $spoc?>";
				cam_users = cam_users.split(",");
				jQuery.each(cam_users, (index, item) => {
					console.log(index + "=>" + item);
					items = item.split(":");
					
					console.log("<option data-id='"+items[0]+"' value='"+items[1]+"'>"+items[1]+"</option>");
					$('#cities').append("<option data-id='"+items[0]+"' value='"+items[1]+"'>"+items[1]+"</option>");
				});
			});
		</script>
	<?php
        echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';
        echo $html = <<<HTMLFORM
		<h1><center><b>SPOC Mapping Management</b></center></h1>
		<form action="index.php?module=Opportunities&action=Spoc_mapping_marketing" method='post' autocomplete="off">
		<h2><b>Manual</b></h2>
		<table>
			<tr>
				<td>City:</td>
				<td>
					<input list="cities" name="city" id="city" value='$_REQUEST[city]'/>
					<datalist id="cities">
					</datalist>
				</td>
				<td>(Eg: Bangalore or Mumbai)</td>
				<td colspan="1"><input type='submit' value='Get SPOC Details' id='details' name='details'/></td>
			</tr>
			<tr>
				<td>SPOC NG ID:</td>
				<td>
					<input list="esc_user" name="ngid" id="ngid" value='$_REQUEST[ngid]'/>
					<datalist id="esc_user">
					</datalist>
				</td>
				
			</tr>
			
		<tr>
			<td></td><td colspan="1"><input type='submit' value='Spoc Update' id='single' name='single'/></td>
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
		$city = $_REQUEST['city'];
        if (!empty($_REQUEST['details'])) {
            if (!empty($city)) {
              	$this->getSpocDetails($city);
            } else if(empty($_REQUEST['city'])) {
                echo "<p style='color:red'>City cannot be empty</p>";
            }
        }
	}
	
	function updateIntoSpocTable($ngid,$city){

		global $current_user;
    	if(!empty($ngid) && !empty($city)){
    		
			global $db;
			$query = "
				UPDATE  
					cluster_city_mapping 
				SET 
					spoc_id = '$ngid',
					last_updated_by = '$current_user->user_name',
					last_updated_date = '".date('Y-m-d')."'
				WHERE 
					city='$city'
				";

			$results = $db->query($query);
		
			
		    if($results){
		    	echo "<p>SPOC ID updated for $city</p>";
		    }
		    else{
		    	echo "<p style='color:red'>Updation Failed. Please Contact Administrator</p>";
		    }
    	} else {

			echo "<p style='color:red'>User Not found in CRM.</p>";
			echo "<p>SPOC/Assigned User :: $ngid</p>";
			echo "<p> City :: $city</p>";
			echo "<p> Last Updated By :: $current_user->user_name</p>";
			echo "<p> Last Updated Date :: '".date('d-m-Y')."'</p>";
		
			echo "<hr>";
    	}
    	
    }



	function handleSubmittedForm(){
		$ngid 		= $_REQUEST['ngid'];
		
		$city = $_REQUEST['city'];

        if (!empty($_REQUEST['city']) && !empty($_REQUEST['ngid'])) {
            
            	$this->updateIntoSpocTable($ngid,$city);
		}
		else if(!empty($_REQUEST['single'])){ 
			echo "<p style='color:red'>City / NG ID are mandatory, cannot be empty</p>";
		}
     
	}
	
    function display(){
		global $current_user;
		$groupFocus = new ACLRole();
        $roles = $groupFocus->getUserRoles($current_user->id);
		//$permitted_users = array("NG377","NG690","NG478","NG894","NG660","NG637","NG1647","NG2029");
		if (!$current_user->is_admin  && !in_array('Sales Admin',$roles)){
		    die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
		}
    	$this->displayForm();
    	$this->handleSubmittedForm();
    	$this->displayUserDetails();
    }



}

?>