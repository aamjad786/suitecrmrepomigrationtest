<link rel="stylesheet" type="text/css" href="custom/include/css/dataTables.min.css">
<link rel="stylesheet" type="text/css" href="custom/include/css/fixedColumns.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="custom/include/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="custom/include/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="custom/include/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" charset="utf8" src="custom/include/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="custom/include/js/buttons.colVis.min.js"></script>
<style type="text/css">
	#formTable th,td{
		    padding: .5em;
	}
	#output{
		border:1px solid;
	}
	#output tr,td{

	}
</style>
<?php
global $app_list_strings;
?>
<form action="" id="search" method="post" enctype="multipart/form-data">
	<table id="formTable">
		<tr>
			<td><b> Case Number: </b></td>
			<td>
				<input type="text" id="case_number" name="case_number"/>
			</td>
			<td><b> App id: </b></td>
	    	<td>	
	    		<input type="text" id="merchant_app_id_c" name="merchant_app_id_c"/>
	    	</td>
	    	<td><b> UserName: </b></td>
	    	<td>
	    		<!-- <input list="esc_user" name="ngid" id="ngid" value='' autocomplete="off"/>
                <datalist id="esc_user">
                </datalist> -->
                <select id="user_name" name="user_name" multiple="true">
                	<?= getCrmUserDetails(); ?>
                </select>
                
            </td>
            <td><b> Department: </b></td>
	    	<td>
	    		<input list="esc_department" name="department" id="department" value='' />
                <datalist id="esc_department">
                	<?= createList(getDepartments()); ?>
                </datalist>
            </td>
	    </tr>
	    <tr>
			<td><b> Subject: </b></td>
			<td>
				<input type="text" id="name" name="name"/>
			</td>
			<td><b> Case owner: </b></td>
	    	<td>	
	    		<input type="text" id="attended_by_c" name="attended_by_c"/>
	    	</td>
	    	<td><b> Status: </b></td>
	    	<td>
	    		<select id="state" name="state[]" multiple="true">	
	    		<!-- <input list="esc_status" id="status" name="status"/>
	    		<datalist id="esc_status">
	    			
                </datalist> -->
                <?= createList($app_list_strings['case_state_dom']); ?>
            </select>
	    	</td>
	    	
	    </tr>
	     <tr>
			<td><b> Email: </b></td>
			<td>
				<input type="text" id="email" name="email"/>
			</td>
			<td><b> Priority: </b></td>
	    	<td>	
	    		<input list="esc_priority" id="priority" name="priority"/>
	    		<datalist id="esc_priority">
	    			<?= createList($app_list_strings['case_priority_dom']); ?>
                </datalist>
	    	</td>
	    </tr>
	</table>
	 <input type="submit" value="Submit" name="submit"><br/><br/>
</form>
<?php

if (isset($_POST["submit"])) {
	
	global $db;
	$query  = "select cases.*,cases_cstm.*,CONCAT(first_name, ' ', last_name) AS user_name,users.department from cases join cases_cstm on id=id_c join users on assigned_user_id=users.id";
	$i=0;
	foreach($_POST as $k=>$v){
		if($k=="submit")continue;
		$where = "";
		$prefix="";
		if(in_array($k, array("department","user_name")))
			$prefix="users.";
		else if ($k=='name')
			$prefix="cases.";

		if(!empty($v)){
			if($k=='state'){
				$where = " $prefix$k in (";
				foreach($v as $c=>$value){
					if($c==0)
						$where .= "'$value'";
					else
						$where .= ",'$value'";
				}
				$where .= ')';
			}else{
				$where  = " $prefix$k='$v'";
			}
		}
		
		if(!empty($where)){
			if($i==0){
				$query .= " where ".$where;
			}else{
				$query .= " and ".$where;
			}
			$i++;
		}
	}
	$query .= " order by cases.date_entered desc ";
	$res = $db->query($query);
?>
	
<hr>
	<h4>
		Cases
	</h4>
    <table id = 'output'  style='width:100%'>
    <thead>
        <tr>
			<th>Case Number</th>
			<th>Subject</th>
			<th>APP ID</th>
			<th>Merchant Name</th>
			<th>Status</th>
			<th>Date created</th>
			<th>Assigned user name</th>
			<th>Department</th>
			<th>View</th>
			<th>Edit</th>
        </tr>
    </thead>
    <tbody>
    	<?php
	while($row = $db->fetchByAssoc($res)){
		$view_link="https://crm.advancesuite.in/SuiteCRM/index.php?module=Cases&action=DetailView&record=".$row['id'];
		$edit_link="https://crm.advancesuite.in/SuiteCRM/index.php?module=Cases&action=EditView&record=".$row['id'];
		?>
		
    	<tr>
    		<td><?=$row['case_number'];?></td>
    		<td><?=$row['name'];?></td>
    		<td><?=$row['merchant_app_id_c'];?></td>
    		<td><?=$row['merchant_name_c'];?></td>
    		<td><?=$row['state'];?></td>
    		<td><?=$row['date_entered'];?></td>
    		<td><?=$row['user_name'];?></td>
    		<td><?=$row['department'];?></td>
    		<td><a href="<?=$view_link?>" target="_blank">view</a></td>
    		<td><a href="<?=$edit_link?>" target="_blank">edit</a></td>
    	</tr>
    
    <?php
	}

	echo "</tbody>
</table>";
}



function getCrmUserDetails(){
    global $db;
    $users = array();
    $query = "SELECT user_name,CONCAT(first_name, ' ', last_name) AS 'name' FROM users WHERE deleted = 0 and status = 'Active'";
    $results = $db->query($query);
    $i=0;
    $user_options_str = "<option value=''></option>";
    while($row = $db->fetchByAssoc($results)){
        if(!empty($row['user_name']) && !empty($row['name'])){
            $user_options_str .="<option value='" . $row['user_name'] . "'>".$row['name']."</option>";
        }
    }   
    return $user_options_str;
}
function getDepartments(){
	global $db;
	// var_dump($db);
    $users = array();
    $query = "SELECT distinct department from users where department is not null";
    $results = $db->query($query);
    $arr = array();
    while($row = $db->fetchByAssoc($results)){

        $arr[$row['department']] = $row['department'];
    }
    var_dump($arr);
    return $arr;   
}
function createList($arr){
	$str = "";
	foreach($arr as $k=>$v){
		$str .= "<option value='$k'>$v</option>";
	}
	return $str;
}
?>
 <script type="text/javascript">
	$(document).ready( function () {
    	
    	$('#output').DataTable({});
    	
    });

 </script>