<?php

/*********************************************************************************
* This code was developed by:
* Audox Ingenier�a Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

if(!defined('sugarEntry')) define('sugarEntry', true);

$customQueriesVersion = 1.6;

global $db;
global $sugar_config, $app_list_strings, $GLOBALS;
global $current_user;

// Validate $SugarQueriesApiKey to enable the use of this module
// Feel free to disable it or edit it and validate the use of this module against other criteria for your own purposes
function validate($url, $fields){
	/*$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, true); 
	curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = json_decode(curl_exec($curl));
	return $response->licence;*/
	return true;
}

// $SugarQueriesApiKey = $sugar_config['CustomQueries']['ApiKey'];
// $url="http://www.sugarqueries.com/validate.php";
// $fields = array(
// 	'Remote' => $_SERVER['REMOTE_ADDR'],
// 	'Url' => $sugar_config['site_url'],
// 	'ApiKey' => $SugarQueriesApiKey,
// );

// if(validate($url, $fields) == 0) die(json_encode(array('version' => $customQueriesVersion, 'error' => 1, 'msg' => 'No Valid License')));

if($_REQUEST['entryPoint']==='CustomQueriesRemote'){
	$user_hash="encrypt(lower(md5('".$_SERVER['PHP_AUTH_PW']."')),user_hash)";
	$query="SELECT * FROM users WHERE user_name='".$_SERVER['PHP_AUTH_USER']."' AND user_hash=".$user_hash." AND is_admin = 1 AND status = 'Active' AND !deleted";
	$res=$db->query($query, true, 'Error: '.mysql_error());
	if($res->num_rows==0) die(json_encode(array('version' => $customQueriesVersion, 'error' => 1, 'msg' => 'Unauthorized User')));
	if(!isset($_REQUEST['queries'])) die(json_encode(array('version' => $customQueriesVersion, 'error' => 1, 'msg' => 'The query is empty')));
	if(!isset($_REQUEST['format'])) $_REQUEST['format'] = 'json';
	$current_user->is_admin=1;
}

if(isset($_REQUEST['array'])){
	switch ($_REQUEST['array']) {
		case "sugar_config":
			echo json_encode($sugar_config);
			break;
		case "GLOBALS":
			echo json_encode($GLOBALS);
			break;
		case "app_list_strings":
			echo json_encode($app_list_strings);
			break;
	}
	return;
}

if(isset($_REQUEST['query'])) $_REQUEST['queries'] = $sugar_config['CustomQueries'][$_REQUEST['query']];

if($_REQUEST['entryPoint']==='CustomQueries'){
	echo '<script type="text/javascript" src="custom/include/js/jquery-1.3.2.js" ></script>';
	echo '<script type="text/javascript" src="custom/include/js/table2CSV.js" ></script>';
	echo "<script>
function getCSVData(table){
 var csv_value=$('#'+table).table2CSV({delivery:'value', separator : ','});
 $(\"#csv_text_\"+table).val(csv_value);	
}
</script>";
	echo '
	<form name="input" action="" method="post">
	<table>
	<tr><td>Queries:<br /><textarea rows="4" cols="50" name="queries">'.trim(htmlspecialchars_decode(isset($_REQUEST['queries'])?$_REQUEST['queries']:"", ENT_QUOTES)).'</textarea></td></tr>
	</table>
	<input type="submit" value="Submit">
	</form>
	';
}

if(isset($_REQUEST['queries']) && $current_user->is_admin){
	$html_result="";
	$array_result=array();
	$array_result['version'] = $customQueriesVersion; 
	$array_result['error'] = 0;
	$queries=trim(htmlspecialchars_decode($_REQUEST['queries'], ENT_QUOTES));
	$queries = rtrim($queries, ';');
	$queries = explode(";", $queries);
	$query_id=0;
	foreach ($queries as $query) {
		$query = trim($query);
		$html_result.="Query: ".$query."<br />";
		$array_result['results'][$query_id]['query']=$query;
		$res=$db->query($query, true, 'Error buscando Reservas: ');
		$html_result.="<table id=\"table_".$query_id."\" border=\"1\" cellspacing=0 cellpadding=0>";
		$header_style="style=\"background-color:black; color:white;\"";
		$i=0;
		while($row=$db->fetchByAssoc($res)){
			if($i==0){
				$html_result.="<tr><td ".$header_style.">#</td>";
				foreach ($row as $field => $value){
					$html_result.="<td ".$header_style.">".$field."</td>";
					$array_result['results'][$query_id]['header'][]=$field;
					}
				$html_result.="</tr>";
			}
			$html_result.="<tr><td>".$i."</td>";
			foreach ($row as $field => $value){
				$value = str_replace("\r\n", "", $value);
				$html_result.="<td>".$value."</td>";
				}
			$html_result.="</tr>";
			$array_result['results'][$query_id]['rows'][$i]=$row;
			$i++;
		}
		$html_result.="</table>";
		if($_REQUEST['entryPoint']==='CustomQueries'){
			$html_result.="<input value=\"View CSV\" type=\"button\" onclick=\"$('#table_".$query_id."').table2CSV()\">";
			$html_result.='<form id ="get_csv_form_table_'.$query_id.'" action="index.php?entryPoint=getCSV" method ="post" > 
<input type="hidden" id="csv_text_table_'.$query_id.'" name="csv_text">
<input type="submit" id="submit_'.$query_id.'" value="Download CSV File" onclick="getCSVData(\'table_'.$query_id.'\')">
</form>';
		

		}
		$html_result.="<br />";
		$query_id++;
	}
	if($_REQUEST['format']==='array') echo print_r($array_result);
	elseif($_REQUEST['format']==='json') echo json_encode($array_result);
	else echo $html_result;

	//////////////////////////////////////////////////
	
	$fields = array (
        //Text
        array (
               'required' => false,
               'name' => 'date_closed',
               'vname' => 'Date closed',
               'type' => 'datetimecombo',
               'massupdate' => 0,
               'no_default' => false,
               'comments' => '',
               'help' => '',
               'importable' => 'true',
               'duplicate_merge' => 'disabled',
               'duplicate_merge_dom_value' => '0',
               'audited' => false,
               'inline_edit' => true,
               'reportable' => true,
               'unified_search' => false,
               'merge_filter' => 'disabled',
               'size' => '20',
               'enable_range_search' => true,
               'dbType' => 'datetime',
             ),
        array (
               'required' => false,
               'name' => 'test_current_date',
               'vname' => 'Current Date',
               'type' => 'datetimecombo',
               'massupdate' => 0,
               'no_default' => false,
               'comments' => '',
               'help' => '',
               'importable' => 'true',
               'audited' => false,
               'inline_edit' => true,
               'reportable' => true,
               'unified_search' => false,
               'dbType' => 'datetime',
             ),
             array (
               'required' => false,
               'name' => 'date_assigned_to_dept',
               'vname' => 'Date date_assigned_to_dept',
               'type' => 'datetimecombo',
               'massupdate' => 0,
               'no_default' => false,
               'comments' => '',
               'help' => '',
               'importable' => 'true',
               'duplicate_merge' => 'disabled',
               'duplicate_merge_dom_value' => '0',
               'audited' => false,
               'inline_edit' => true,
               'reportable' => true,
               'unified_search' => false,
               'merge_filter' => 'disabled',
               'size' => '20',
               'enable_range_search' => true,
               'dbType' => 'datetime',
             ),
       array(
               'name' => 'case_details',
               'vname' => 'LBL_CASE_DETAILS',
               'type' => 'dynamicenum',
               'dbType' => 'enum',
               'options' => 'case_details_list',
               'len' => 200,
               'audited' => true,
               'comment' => 'Third category of case categories',
               'parentenum' => 'case_subcategory_c'
           ),
       array(
               'name' => 'tid',
               'vname' => 'TID',
               'type' => 'varchar',
               'audited' => false,
               'inline_edit' => false,
               'comment' => 'TID'
           ),
           array(
               'name' => 'not_apply',
               'vname' => 'Case Details are not applicable',
               'type' => 'bool',
               'audited' => false,
               'comment' => 'case details check box'
           ),
           array(
               'name' => 'category_count',
               'vname' => 'Category Edit Count',
               'type' => 'int',
               'no_default' => false,
               'audited' => false,
               'inline_edit' => false,
               'comment' => 'No. of edits for category/subcategory'
           ),
           array(
               'name' => 'case_category_counts',
               'vname' => 'Category Edit Count',
               'type' => 'int',
               'default' => 0,
               'no_default' => false,
               'audited' => false,
               'inline_edit' => false,
               'comment' => 'No. of edits for category/subcategory'
           ),
       array(
               'name' => 'is_call_back_30_min',
               'vname' => 'LBL_IS_CALL_BACK_30_MIN',
               'type' => 'bool',
               'studio' => 'visible',
           ),
       array(
               'name' => 'is_call_back',
               'vname' => 'LBL_IS_CALL_BACK',
               'type' => 'bool',
               'studio' => 'visible',
           ),
           array (
     'required' => true,
     'name' => 'LBAL',
     'vname' => 'LBAL',
     'type' => 'int',
     'massupdate' => 0,
     'no_default' => false,
     'comments' => '',
     'help' => '',
     'importable' => 'true',
     'duplicate_merge' => 'disabled',
     'duplicate_merge_dom_value' => '0',
     'audited' => false,
     'reportable' => true,
     'unified_search' => false,
     'merge_filter' => 'disabled',
     'len' => '255',
     'size' => '20',
     'enable_range_search' => false,
     'min' => false,
     'max' => false,
   ),
   array (
     'required' => false,
     'name' => 'min_preclosure_amount',
     'vname' => 'Minimum pre-closure amount requested',
     'type' => 'int',
     'massupdate' => 0,
     'no_default' => false,
     'comments' => '',
     'help' => '',
     'importable' => 'true',
     'duplicate_merge' => 'disabled',
     'duplicate_merge_dom_value' => '0',
     'audited' => false,
     'reportable' => true,
     'unified_search' => false,
     'merge_filter' => 'disabled',
     'len' => '255',
     'size' => '20',
     'enable_range_search' => false,
     'min' => false,
     'max' => false,
   ),
   array (
     'required' => false,
     'name' => 'proposed_preclosure_amount',
     'vname' => 'Proposed pre-closure amount',
     'type' => 'int',
     'massupdate' => 0,
     'no_default' => false,
     'comments' => '',
     'help' => '',
     'importable' => 'true',
     'duplicate_merge' => 'disabled',
     'duplicate_merge_dom_value' => '0',
     'audited' => false,
     'reportable' => true,
     'unified_search' => false,
     'merge_filter' => 'disabled',
     'len' => '255',
     'size' => '20',
     'enable_range_search' => false,
     'min' => false,
     'max' => false,
   ),
            array (
                 'required' => false,
                 'name' => 'closed_by',
                 'vname' => 'Closed By',
                 'type' => 'varchar',
                 'massupdate' => 0,
                 'no_default' => false,
                 'comments' => '',
                 'help' => '',
                 'importable' => 'true',
                 'duplicate_merge' => 'disabled',
                 'duplicate_merge_dom_value' => '0',
                 'audited' => true,
                 'inline_edit' => true,
                 'reportable' => true,
                 'unified_search' => false,
                 'merge_filter' => 'disabled',
                 'len' => '255',
                 'size' => '20',
               ),
           array (
                 'required' => false,
                 'name' => 'tat_in_days',
                 'vname' => 'TAT in Days',
                 'type' => 'varchar',
                 'massupdate' => 0,
                 'no_default' => false,
                 'comments' => '',
                 'help' => '',
                 'importable' => 'true',
                 'duplicate_merge' => 'disabled',
                 'duplicate_merge_dom_value' => '0',
                 'audited' => true,
                 'inline_edit' => true,
                 'reportable' => true,
                 'unified_search' => false,
                 'merge_filter' => 'disabled',
                 'len' => '20',
                 'size' => '20',
               ),
           array (
                 'required' => false,
                 'name' => 'tat_status',
                 'vname' => 'TAT Status',
                 'type' => 'varchar',
                 'massupdate' => 0,
                 'no_default' => false,
                 'comments' => '',
                 'help' => '',
                 'importable' => 'true',
                 'duplicate_merge' => 'disabled',
                 'duplicate_merge_dom_value' => '0',
                 'audited' => true,
                 'inline_edit' => true,
                 'reportable' => true,
                 'unified_search' => false,
                 'merge_filter' => 'disabled',
                 'len' => '20',
                 'size' => '20',
               ),
	array(
               'name' => 'assigned_user_department',
               'vname' => 'LBL_ASSIGNED_USER_DEPARTMENT',
               'type' => 'enum',
               'options' => 'user_departments_list',
               'len' => 100,
               'audited' => true,
               'comment' => 'assigned user department',

           ),
         array(
               'name' => 'current_user_department',
               'vname' => 'Current User Department',
               'type' => 'enum',
               'options' => 'user_departments_list',
               'len' => 100,
               'audited' => true,
               'comment' => 'Current User Department',

           ),
       array (
             'required' => false,
             'name' => 'financial_year',
             'vname' => 'Financial Year',
             'type' => 'varchar',
             'massupdate' => 0,
             'no_default' => false,
             'comments' => '',
             'help' => '',
             'importable' => 'true',
             'duplicate_merge' => 'disabled',
             'duplicate_merge_dom_value' => '0',
             'audited' => false,
             'inline_edit' => true,
             'reportable' => true,
             'unified_search' => false,
             'merge_filter' => 'disabled',
             'len' => '20',
             'size' => '20',
             'enable_range_search' => false,
             'disable_num_format' => '',
             'min' => false,
             'max' => false,
           ),
       array (
             'required' => false,
             'name' => 'quarter',
             'vname' => 'Quarter',
             'type' => 'varchar',
             'massupdate' => 0,
             'no_default' => false,
             'comments' => '',
             'help' => '',
             'importable' => 'true',
             'duplicate_merge' => 'disabled',
             'duplicate_merge_dom_value' => '0',
             'audited' => false,
             'inline_edit' => true,
             'reportable' => true,
             'unified_search' => false,
             'merge_filter' => 'disabled',
             'len' => '20',
             'size' => '20',
             'enable_range_search' => false,
             'disable_num_format' => '',
             'min' => false,
             'max' => false,
           ),
           array (
             'required' => false,
             'name' => 'digitally_signed',
             'vname' => 'Digitally Year',
             'type' => 'varchar',
             'massupdate' => 0,
             'no_default' => false,
             'comments' => '',
             'help' => '',
             'importable' => 'true',
             'duplicate_merge' => 'disabled',
             'duplicate_merge_dom_value' => '0',
             'audited' => false,
             'inline_edit' => true,
             'reportable' => true,
             'unified_search' => false,
             'merge_filter' => 'disabled',
             'len' => '20',
             'size' => '20',
             'enable_range_search' => false,
             'disable_num_format' => '',
             'min' => false,
             'max' => false,
           ),
       array(  
               'name' => 'sub_priority',   
               'vname' => 'LBL_SUB_PRIORITY',  
               'type' => 'dynamicenum',    
               'dbType' => 'enum', 
               'options' => 'sub_priority',    
               'len' => 100,   
               'audited' => true,  
               'comment' => 'The sub priority of the case',    
               'parentenum' => 'priority', 
           ),
	array(
               'name' => 'bot_comment',
               'vname' => 'Bot comment',
               'type' => 'text',
               'comment' => 'Automation Bot comment',
               'rows' => 6,
               'cols' => 80,
               'audited' => true,
           ),
       array(
               'name' => 'summary',
               'vname' => 'LBL_SUMMARY',
               'type' => 'text',
               'comment' => 'The summary of the case',
               'rows' => 6,
               'cols' => 80,
               'audited' => true,
               'required' => true,
             ),
		array(
               'required' => false,
               'name' => 'case_subcategory_c_new',
               'vname' => 'LBL_CASE_SUBCATEGORY_NEW',
               'type' => 'varchar',
               'importable' => 'false',
               'duplicate_merge' => 'disabled',
               'audited' => true,
               'reportable' => false
           ),
           array(
               'required' => false,
               'name' => 'case_category_c_new',
               'vname' => 'LBL_CASE_CATEGORY_NEW',
               'type' => 'varchar',
               'importable' => 'false',
               'duplicate_merge' => 'disabled',
               'audited' => true,
               'reportable' => false
           ),
           array(
               'required' => false,
               'name' => 'case_category_old',
               'vname' => 'Old category',
               'type' => 'varchar',
               'importable' => 'false',
               'duplicate_merge' => 'disabled',
               'audited' => true,
               'reportable' => false
           ),
           array(
               'required' => false,
               'name' => 'case_subcategory_old',
               'vname' => 'Old Subcategory',
               'type' => 'varchar',
               'importable' => 'false',
               'duplicate_merge' => 'disabled',
               'audited' => true,
               'reportable' => false
           ),
           array(
               'required' => false,
               'name' => 'case_category_approval',
               'vname' => 'Case Category Approved',
               'type' => 'int',
               'importable' => 'false',
               'duplicate_merge' => 'disabled',
               'audited' => true,
               'reportable' => false
           ),
           array(
               'required' => false,
               'name' => 'maker_comment',
               'vname' => 'LBL_MAKERCOMMENT',
               'type' => 'text',
               'importable' => 'false',
               'duplicate_merge' => 'disabled',
               'audited' => true,
               'reportable' => false
           ),
           array(
               'required' => false,
               'name' => 'checker_comment',
               'vname' => 'Checker comment',
               'type' => 'text',
               'importable' => 'false',
               'duplicate_merge' => 'disabled',
               'audited' => true,
               'reportable' => false
           ),
           array(
               'required' => false,
               'name' => 'maker_id',
               'vname' => 'LBL_MAKER_ID',
               'type' => 'varchar',
               'importable' => 'false',
               'duplicate_merge' => 'disabled',
               'audited' => true,
               'reportable' => true
           ),
           array(
               'required' => false,
               'name' => 'checker',
               'vname' => 'Checker',
               'type' => 'varchar',
               'importable' => 'false',
               'duplicate_merge' => 'disabled',
               'audited' => true,
               'reportable' => true
           ),
			array(
               'name' => 'reassigned_user_id',
               'link' => 'assigned_user_link',
               'vname' => 'LBL_RE_ASSIGNED_TO_NAME',
               'rname' => 'name',
               'type' => 'relate',
               'reportable' => true,
               'source' => 'non-db',
               'table' => 'users',
               'id_name' => 'reassigned_user_id',
               'module' => 'Users',
               'duplicate_merge' => 'disabled',
               'required' => false,
               'massupdate' => 1,
               'no_default' => false,
               'comments' => '',
               'help' => '',
               'importable' => 'true',
               'duplicate_merge_dom_value' => '0',
               'audited' => false,
               'unified_search' => false,
               'merge_filter' => 'disabled',
               'len' => '255',
               'size' => '20',
               'ext2' => '',
               'quicksearch' => 'enabled',
               'studio' => 'visible',
           ),
			array(
               'name' => 'classify',
               'vname' => 'Classify',
               'type' => 'bool',
               'studio' => 'visible',
           ),
               array (
               'required' => false,
               'name' => 'date_of_changes',
               'vname' => 'Date of approval',
               'type' => 'datetimecombo',
               'massupdate' => 0,
               'no_default' => false,
               'comments' => '',
               'help' => '',
               'importable' => 'true',
               'duplicate_merge' => 'disabled',
               'duplicate_merge_dom_value' => '0',
               'audited' => false,
               'inline_edit' => true,
               'reportable' => true,
               'unified_search' => false,
               'merge_filter' => 'disabled',
               'size' => '20',
               'enable_range_search' => false,
               'dbType' => 'datetime',
           ),
               array (
               'required' => false,
               'name' => 'date_of_request',
               'vname' => 'Date of request',
               'type' => 'datetimecombo',
               'massupdate' => 0,
               'no_default' => false,
               'comments' => '',
               'help' => '',
               'importable' => 'true',
               'duplicate_merge' => 'disabled',
               'duplicate_merge_dom_value' => '0',
               'audited' => false,
               'inline_edit' => true,
               'reportable' => true,
               'unified_search' => false,
               'merge_filter' => 'disabled',
               'size' => '20',
               'enable_range_search' => false,
               'dbType' => 'datetime',
           ),

	);
	require_once('ModuleInstall/ModuleInstaller.php');
   	$moduleInstaller = new ModuleInstaller();
    $moduleInstaller->install_custom_fields($fields);
}



?>