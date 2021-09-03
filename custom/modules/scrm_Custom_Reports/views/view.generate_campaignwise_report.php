<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');

class Column_Order{

	private $column_order;

	public function Column_Order(){
	 $this->column_order = array('LeadSource',  'Unit_Name' ,'Fresh_Data_Given','Fresh_Data_Attempted','New','Contactable_Records','RPC','Interested','PickUp_Generated','Leads_Given_To_CAM','Cases_Pickedby_FOS','Cases_Pickedby_CAM','Cases_loggedin_FOS','Cases_loggedin_CAM','Cases_Sanctioned_FOS','Cases_Sanctioned_CAM','Cases_Disbursed_FOS','Cases_Disbursed_CAM','Total_Value_Disbursed_FOS','Total_Value_Disbursed_CAM','Total_Pickups','Total_Logins','Total_Sanctions','Total_Disbursals','Total_Value_Disbursed','Percent_Achievement','Contactability_On_FreshAttemptedData','RPC_Percentage','Interested_Percentage','Pickup_Percentage','Leads_Given_Perc_CAM','Pickupdone_To_Pickup_Generated_Perc_FOS','Pickupdone_To_Pickup_Generated_Perc_CAM','Login_Pickup_Perc_FOS','Login_Pickup_Perc_CAM','Sanction_Login_Perc_FOS','Sanction_Login_Perc_CAM','Disbursed_Sanction_Perc_FOS','Disbursed_Sanction_Perc_CAM','Average_Ticket_Size_FOS','Average_Ticket_Size_CAM','Overall_Login_Perc','Overall_Sanction_Perc','Overall_Disbursed_Perc','Overall_Ticket_Size');

	 	//echo sizeof($this->column_order)."<br>";
	}

	public function get_column_order(){
		return $this->column_order;
	}
}

class SecurityGroupMap{
	static $query = "";
	static $security_group_map = array();
	public static function init(){
		global $db;
		self::$query = "select group_concat(sg.name) as sg_name,u.user_name as user_name, u.id as user_id from 				users u
						LEFT JOIN securitygroups_users sgu on sgu.user_id = u.id
						LEFT JOIN securitygroups sg on sg.id=sgu.securitygroup_id
						group by user_name";

		$result = $db->query(self::$query);

		while($row = $db->fetchByAssoc($result)){
			self::$security_group_map[$row['user_id']] = $row['sg_name'];
		}
	}

	public static function getSecurityGroup($user_id){
		if (array_key_exists($user_id, self::$security_group_map)){
			return self::$security_group_map[$user_id];
		}
		else{
			return NULL;
		}
	}
}

SecurityGroupMap::init();

class OpportunityStatusMap{
	static $query = "";
	static $os_map = array();

	public static function init(){
		global $db;

		self::$query = "select o.id as o_id, o.name, os.status as opportunity_status, os.sub_status as 								opportunity_sub_status, os.date_modified from opportunities o
						LEFT JOIN opportunities_sales_opportunity_status_1_c os_rel on os_rel.opportunities_sales_opportunity_status_1opportunities_ida = o.id
						LEFT JOIN sales_opportunity_status os on os.id = os_rel.opportunit1c3c_status_idb
						ORDER BY os.date_modified DESC";

		$result = $db->query(self::$query);
		while($row = $db->fetchByAssoc($result)){
			$o_id = $row['o_id'];
			if (!array_key_exists($o_id, self::$os_map)){
				self::$os_map[$o_id]['status'] = $row['opportunity_status'];
				self::$os_map[$o_id]['sub_status'] = $row['opportunity_sub_status'];
			}
		}
	}

	public static function get_opportunity_status($o_id){
		if (array_key_exists($o_id, self::$os_map)){
			return self::$os_map[$o_id]['status'];
		}
		else{
			return null;
		}
	}

	public static function get_opportunity_sub_status($o_id){
		if (array_key_exists($o_id, self::$os_map)){
			return self::$os_map[$o_id]['sub_status'];
		}
		else{
			return null;
		}
	}
}

OpportunityStatusMap::init();

class Columns{
	protected $name;
	protected $value = 0;

	public function getName(){
		return $this->name;
	}

	public function getValue(){
		return $this->value;
	}

	public function setValue($value){
		$this->value = $value;
	}

	public function calculate($row, Row $class_row){
		echo "Base class calculate";
	}

	public function calculateSecurityGroup($cam_or_fos){
		$security_group_raw = SecurityGroupMap::getSecurityGroup($cam_or_fos);
		//cam_or_fos contains id of either FOS or CAM
		//FOS have security group TATA BSS (?)
		if($security_group_raw){
			if (strpos($security_group_raw, 'Tata BSS') !== false){
				return 'Tata BSS';
			}
			else if (strpos($security_group_raw, 'Kenkei') !== false){
				$this->value = 'Kenkei';
			}
			else if (strpos($security_group_raw, 'KServe') !== false){
				$this->value = 'KServe';
			}
			else{
				return 'Sales';
			}
		}
	}

}

class Columns_LeadSource extends Columns{
	public function Columns_TargetCount(){
		$this->name = 'lead_source';
	}

	public function calculate($row, Row $class_row){
		$this->value = $row['lead_source'];
	}
}

class Columns_TargetCount extends Columns{
	public function Columns_TargetCount(){
		$this->name = 'target_count';
	}

	public function calculate($row, Row $class_row){
		$this->value = 0;
	}
}

class Columns_Agent_Name extends Columns{
	public function Columns_Agent_Name(){
		$this->name = 'agent_name';
	}

	public function calculate($row, Row $class_row){
		if(array_key_exists('caller_name', $row))
			$this->value = $row['caller_name'];

		else
			echo $this->name." does not exist in row<br>";
	}

}

class Columns_Agent_User_Name extends Columns{
	public function Columns_Agent_User_Name(){
		$this->name = 'agent_user_name';
	}

	public function calculate($row, Row $class_row){
		if(array_key_exists('caller_user_name', $row))
			$this->value = $row['caller_user_name'];

		else
			echo $this->name." does not exist in row<br>";
	}
}

class Columns_Unit_Name extends Columns{
	public function Columns_Unit_Name(){
		$this->name = 'unit_name';
	}

	public function calculate($row, Row $class_row = NULL){
		$caller_id = $row['caller_id'];
		$security_group_raw = SecurityGroupMap::getSecurityGroup($caller_id);
		if($security_group_raw){
			if (strpos($security_group_raw, 'Tata BSS') !== false){
				$this->value = 'Tata BSS';
			}
			else if (strpos($security_group_raw, 'Kenkei') !== false){
				$this->value = 'Kenkei';
			}
			else if (strpos($security_group_raw, 'KServe') !== false){
				$this->value = 'KServe';
			}
			else{
				$this->value = NULL;
			}
		}
		else{
			$this->value = NULL;
		}
	}
}

class Columns_Fresh_Data_Given extends Columns{
	public function Columns_Fresh_Data_Given(){
		$this->name = 'fresh_data_given';
	}

	public function calculate($row, Row $class_row){
		if($row['lead_id'] and $class_row->get_column('Unit_Name')->getValue()){
			$this->value += 1;
		}
	}
}

class Columns_Fresh_Data_Attempted extends Columns{
	public function Columns_Fresh_Data_Attempted(){
		$this->name = 'fresh_data_attempted';
	}

	public function calculate($row, Row $class_row){
		if($row['status'] !== 'New' and $class_row->get_column('Unit_Name')->getValue()){
			$this->value += 1;
		}
	}
}

class Columns_New extends Columns{
	public function Columns_New(){
		$this->name = 'new';
	}

	public function calculate($row, Row $class_row){
		if($row['status'] === 'New' and $class_row->get_column('Unit_Name')->getValue())
			$this->value += 1;
	}
}

class Columns_Contactable_Records extends Columns{
	public function Columns_Contactable_Records(){
		$this->name = 'contactable_records';
	}

	public function calculate($row, Row $class_row){
		if((!empty($row['disposition_c']) and $row['disposition_c'] !== '') and $class_row->get_column('Unit_Name')->getValue()){
			if($row['disposition_c'] !== 'Not contactable' and $row['disposition_c'] !== 'Wrong number')
				$this->value += 1;
		}
	}
}

class Columns_RPC extends Columns{
	public function Columns_RPC(){
		$this->name = 'rpc';
	}

	public function calculate($row, Row $class_row){
		if($row['sub_disposition_c'] === 'Right party not contacted yet')
			$this->value += 1;
	}
}

class Columns_Interested extends Columns{
	public function Columns_Interested(){
		$this->name = 'interested';
	}

	public function calculate($row, Row $class_row){
		if($row['disposition_c'] === 'Interested')
			$this->value += 1;
	}
}

class Columns_PickUp_Generated extends Columns{
	public function Columns_PickUp_Generated(){
		$this->name = 'pickup_generated';
	}

	public function calculate($row, Row $class_row){
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos !== '') and $class_row->get_column('Unit_Name')->getValue()){
			$this->value += 1;
		}
	}
}

class Columns_Leads_Given_To_CAM extends Columns{

	public function Columns_Leads_Given_To_CAM(){
		$this->name = 'leads_given_to_cam';
	}

	public function calculate($row, Row $class_row){
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos !== '') and $class_row->get_column('Unit_Name')->getValue()){ 
			$security_group = $this->calculateSecurityGroup($cam_or_fos);
			if($security_group === 'Sales')
				$this->value += 1;
		}
	}
}

class Columns_Cases_Pickedby_FOS extends Columns{

	public function Columns_Cases_Pickedby_FOS(){
		$this->name = 'cases_pickedby_fos';
	}

	public function calculate($row, Row $class_row){
		$cam_or_fos = $row['cam_or_fos'];
		$security_group = $this->calculateSecurityGroup($cam_or_fos);
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if($security_group !== 'Sales'){
				$sales_stage = $row['sales_stage'];
				if ($sales_stage === 'Submitted' or $sales_stage === 'Sanctioned' or $sales_stage === 'Disbursed' or $sales_stage === 'Rejected' or $sales_stage === 'Credit_Rejected' or $sales_stage === 'Credit'){
					$this->value += 1;
				}
				else{
					$o_id = $row['opportunity_id'];
					$opportunity_status = OpportunityStatusMap::get_opportunity_status($o_id);
					$opportunity_sub_status = OpportunityStatusMap::get_opportunity_sub_status($o_id);

					/*
					Status list
					2 => 'Follow up',
				 	3 => 'Login',
				  	4 => 'Not Contactable',
				  	5 => 'Not Eligible',
				  	6 => 'Not Interested',
				  	7 => 'Pick up',

				  	Sub status list
				  	'6_1' => 'Rate of Interest',
					  '6_2' => 'No Requirement',
					  '6_3' => 'Loan Amount',
					  '4_1' => 'Wrong Number',
					  '4_2' => 'Not Picking',
					  '4_3' => 'Not Responding',
					  '5_1' => 'MCP Not Met',
					  '7_1' => 'Partial Pick Up',
					  '3_1' => 'Login',
					  '2_1' => 'Not Applicable',
					*/



					if ($opportunity_status === '7' and $opportunity_sub_status === '7_2'){
						$this->value += 1;
					}

				}
			}
		}
	}
}

class Columns_Cases_Pickedby_CAM extends Columns{

	public function Columns_Cases_Pickedby_CAM(){
		$this->name = 'cases_pickedby_cam';
	}

	public function calculate($row, Row $class_row){
		$cam_or_fos = $row['cam_or_fos'];
		$security_group = $this->calculateSecurityGroup($cam_or_fos);
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if($security_group === 'Sales'){
				$sales_stage = $row['sales_stage'];
				if ($sales_stage === 'Submitted' or $sales_stage === 'Sanctioned' or $sales_stage === 'Disbursed' or $sales_stage === 'Rejected' or $sales_stage === 'Credit_Rejected' or $sales_stage === 'Credit'){
					$this->value += 1;
				}
				else{
					$o_id = $row['opportunity_id'];
					$opportunity_status = OpportunityStatusMap::get_opportunity_status($o_id);
					$opportunity_sub_status = OpportunityStatusMap::get_opportunity_sub_status($o_id);

					/*
					Status list
					2 => 'Follow up',
				 	3 => 'Login',
				  	4 => 'Not Contactable',
				  	5 => 'Not Eligible',
				  	6 => 'Not Interested',
				  	7 => 'Pick up',

				  	Sub status list
				  	'6_1' => 'Rate of Interest',
					  '6_2' => 'No Requirement',
					  '6_3' => 'Loan Amount',
					  '4_1' => 'Wrong Number',
					  '4_2' => 'Not Picking',
					  '4_3' => 'Not Responding',
					  '5_1' => 'MCP Not Met',
					  '7_1' => 'Partial Pick Up',
					  '3_1' => 'Login',
					  '2_1' => 'Not Applicable',
					*/



					if ($opportunity_status === '7' and $opportunity_sub_status === '7_2'){
						$this->value += 1;
					}

				}
			}
		}
	}
}

class Columns_Cases_loggedin_FOS extends Columns{

	public function Columns_Cases_loggedin_FOS(){
		$this->name = 'cases_loggedin_fos';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage === 'Submitted' or $sales_stage === 'Sanctioned' or $sales_stage === 'Disbursed' or $sales_stage === 'Rejected' or $sales_stage === 'Credit_Rejected' or $sales_stage === 'Credit'){
				$security_group = $this->calculateSecurityGroup($cam_or_fos);
				if($security_group !== 'Sales'){
					$this->value += 1;
				}
			}
		}
	}
}

class Columns_Cases_loggedin_CAM extends Columns{

	public function Columns_Cases_loggedin_CAM(){
		$this->name = 'cases_loggedin_cam';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage === 'Submitted' or $sales_stage === 'Sanctioned' or $sales_stage === 'Disbursed' or $sales_stage === 'Rejected' or $sales_stage === 'Credit_Rejected' or $sales_stage === 'Credit'){
				$security_group = $this->calculateSecurityGroup($cam_or_fos);
				if($security_group == 'Sales')
					$this->value += 1;
			}
		}
	}
}

class Columns_Cases_Sanctioned_FOS extends Columns{

	public function Columns_Cases_Sanctioned_FOS(){
		$this->name = 'cases_sanctioned_fos';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Sanctioned' or $sales_stage == 'Disbursed'){ 
				$security_group = $this->calculateSecurityGroup($cam_or_fos);
				if($security_group != 'Sales')
					$this->value += 1;
			}
		}
	}
}

class Columns_Cases_Sanctioned_CAM extends Columns{

	public function Columns_Cases_Sanctioned_CAM(){
		$this->name = 'cases_sanctioned_cam';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Sanctioned' or $sales_stage == 'Disbursed'){ 
				$security_group = $this->calculateSecurityGroup($cam_or_fos);
				if($security_group == 'Sales')
					$this->value += 1;
			}
		}
	}
}

class Columns_Cases_Disbursed_FOS extends Columns{

	public function Columns_Cases_Disbursed_FOS(){
		$this->name = 'cases_disbursed_fos';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Disbursed'){ 
				$security_group = $this->calculateSecurityGroup($cam_or_fos);
				if($security_group != 'Sales')
					$this->value += 1;
			}
		}
	}
}

class Columns_Cases_Disbursed_CAM extends Columns{

	public function Columns_Cases_Disbursed_CAM(){
		$this->name = 'cases_disbursed_cam';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Disbursed'){ 
				$security_group = $this->calculateSecurityGroup($cam_or_fos);
				if($security_group == 'Sales')
					$this->value += 1;
			}
		}
	}
}

class Columns_Total_Value_Disbursed_FOS extends Columns{

	public function Columns_Total_Value_Disbursed_FOS(){
		$this->name = 'value_disbursed_fos';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Disbursed'){ 
				$security_group = $this->calculateSecurityGroup($cam_or_fos);

				if($security_group != 'Sales'){
					$loan_disbursed = ($row['loan_disbursed'] > 0)?$row['loan_disbursed']:$row['loan_requested'];
					$this->value += $loan_disbursed;
				}
			}
		}
	}
}

class Columns_Total_Value_Disbursed_CAM extends Columns{

	public function Columns_Total_Value_Disbursed_CAM(){
		$this->name = 'value_disbursed_cam';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Disbursed'){ 
				$security_group = $this->calculateSecurityGroup($cam_or_fos);

				if($security_group == 'Sales'){
					$loan_disbursed = ($row['loan_disbursed'] > 0)?$row['loan_disbursed']:$row['loan_requested'];
					$this->value += $loan_disbursed;
				}
			}
		}
	}
}

class Columns_Total_Pickups extends Columns{

	public function Columns_Total_Pickups(){
		$this->name = 'total_pickups';
	}

	public function calculate($row, Row $class_row){
		$this->value = $class_row->get_column('Cases_Pickedby_FOS')->getValue() + $class_row->get_column('Cases_Pickedby_CAM')->getValue();
		// $cam_or_fos = $row['cam_or_fos'];
		// if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
		// 		$this->value += 1;
		// }
	}
}

class Columns_Total_Logins extends Columns{

	public function Columns_Total_Logins(){
		$this->name = 'total_logins';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Submitted' or $sales_stage == 'Sanctioned' or $sales_stage == 'Disbursed'){ 
					$this->value += 1;
			}
		}
	}
}

class Columns_Total_Sanctions extends Columns{

	public function Columns_Total_Sanctions(){
		$this->name = 'total_sanctions';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Sanctioned' or $sales_stage == 'Disbursed'){ 
					$this->value += 1;
			}
		}
	}
}

class Columns_Total_Disbursals extends Columns{

	public function Columns_Total_Disbursals(){
		$this->name = 'total_disbursals';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Disbursed'){ 
					$this->value += 1;
			}
		}
	}
}

class Columns_Total_Value_Disbursed extends Columns{

	public function Columns_Total_Value_Disbursed(){
		$this->name = 'total_value_disbursed';
	}

	public function calculate($row, Row $class_row){
		$sales_stage = $row['sales_stage'];
		$cam_or_fos = $row['cam_or_fos'];
		if ((!empty($cam_or_fos) and $cam_or_fos != '') and $class_row->get_column('Unit_Name')->getValue()){
			if ($sales_stage == 'Disbursed'){ 
				$loan_disbursed = ($row['loan_disbursed'] > 0)?$row['loan_disbursed']:$row['loan_requested'];
				$this->value += $loan_disbursed;
			}
		}
	}
}

class Columns_Percent_Achievement extends Columns{

	public function Columns_Percent_Achievement(){
		$this->name = 'percent_achievement';
	}

	public function calculate($row, Row $class_row){
		// if ($class_row->get_column('TargetCount')->value != 0){
		// 	$this->value = round(100*($class_row['Cases_Disbursed_FOS']->value/$class_row['TargetCount']->value), 2);
		// }
		$this->value = 0;
	}
}

class Columns_Contactability_On_FreshAttemptedData extends Columns{

	public function Columns_Contactability_On_FreshAttemptedData(){
		$this->name = 'contactability_on_fresh_attempted_data';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Fresh_Data_Attempted')->value != 0){
			$this->value = round(100*($class_row->get_column('Contactable_Records')->value/$class_row->get_column('Fresh_Data_Attempted')->value), 2);
		}
	}
}

class Columns_RPC_Percentage extends Columns{

	public function Columns_RPC_Percentage(){
		$this->name = 'rpc_percentage';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Contactable_Records')->value != 0){
			$this->value = round(100*($class_row->get_column('RPC')->value/$class_row->get_column('Contactable_Records')->value), 2);
		}
	}
}

class Columns_Interested_Percentage extends Columns{

	public function Columns_Interested_Percentage(){
		$this->name = 'interested_percentage';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Contactable_Records')->value != 0){
			$this->value = round(100*($class_row->get_column('Interested')->value/$class_row->get_column('Contactable_Records')->value), 2);
		}
	}
}

class Columns_Pickup_Percentage extends Columns{

	public function Columns_Pickup_Percentage(){
		$this->name = 'pickup_percentage';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Interested')->value != 0){
			$this->value = round(100*($class_row->get_column('PickUp_Generated')->value/$class_row->get_column('Interested')->value), 2);
		}
	}
}

class Columns_Leads_Given_Perc_CAM extends Columns{

	public function Columns_Leads_Given_Perc_CAM(){
		$this->name = 'leads_given_cam_perc';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('PickUp_Generated')->value != 0){
			$this->value = round(100*($class_row->get_column('Leads_Given_To_CAM')->value/$class_row->get_column('PickUp_Generated')->value), 2);
		}
	}
}

class Columns_Pickupdone_To_Pickup_Generated_Perc_FOS extends Columns{

	public function Columns_Pickupdone_To_Pickup_Generated_Perc_FOS(){
		$this->name = 'pickupdone_to_pickup_generated_perc_fos';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('PickUp_Generated')->value != 0){
			$this->value = round(100*($class_row->get_column('Cases_Pickedby_FOS')->value/$class_row->get_column('PickUp_Generated')->value), 2);
		}
	}
}


class Columns_Pickupdone_To_Pickup_Generated_Perc_CAM extends Columns{

	public function Columns_Pickupdone_To_Pickup_Generated_Perc_CAM(){
		$this->name = 'pickupdone_to_pickup_generated_perc_cam';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('PickUp_Generated')->value != 0){
			$this->value = round(100*($class_row->get_column('Cases_Pickedby_CAM')->value/$class_row->get_column('PickUp_Generated')->value), 2);
		}
	}
}

class Columns_Login_Pickup_Perc_FOS extends Columns{

	public function Columns_Login_Pickup_Perc_FOS(){
		$this->name = 'login_pickup_perc_fos';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Cases_Pickedby_FOS')->value != 0){
			$this->value = round(100*($class_row->get_column('Cases_loggedin_FOS')->value/$class_row->get_column('Cases_Pickedby_FOS')->value), 2);
		}
	}
}

class Columns_Login_Pickup_Perc_CAM extends Columns{

	public function Columns_Login_Pickup_Perc_CAM(){
		$this->name = 'login_pickup_perc_cam';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Cases_Pickedby_CAM')->value != 0){
			$this->value = round(100*($class_row->get_column('Cases_loggedin_CAM')->value/$class_row->get_column('Cases_Pickedby_CAM')->value), 2);
		}
	}
}

class Columns_Sanction_Login_Perc_FOS extends Columns{

	public function Columns_Sanction_Login_Perc_FOS(){
		$this->name = 'sanction_login_perc_fos';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Cases_loggedin_FOS')->value != 0){
			$this->value = round(100*($class_row->get_column('Cases_Sanctioned_FOS')->value/$class_row->get_column('Cases_loggedin_FOS')->value), 2);
		}
	}
}

class Columns_Sanction_Login_Perc_CAM extends Columns{

	public function Columns_Sanction_Login_Perc_CAM(){
		$this->name = 'sanction_login_perc_cam';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Cases_loggedin_CAM')->value != 0){
			$this->value = round(100*($class_row->get_column('Cases_Sanctioned_CAM')->value/$class_row->get_column('Cases_loggedin_CAM')->value), 2);
		}
	}
}

class Columns_Disbursed_Sanction_Perc_FOS extends Columns{

	public function Columns_Disbursed_Sanction_Perc_FOS(){
		$this->name = 'disbursed_sanction_perc_fos';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Cases_Sanctioned_FOS')->value != 0){
			$this->value = round(100*($class_row->get_column('Cases_Disbursed_FOS')->value/$class_row->get_column('Cases_Sanctioned_FOS')->value), 2);
		}
	}
}

class Columns_Disbursed_Sanction_Perc_CAM extends Columns{

	public function Columns_Disbursed_Sanction_Perc_CAM(){
		$this->name = 'disbursed_sanction_perc_cam';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Cases_Sanctioned_CAM')->value != 0){
			$this->value = round(100*($class_row->get_column('Cases_Disbursed_CAM')->value/$class_row->get_column('Cases_Sanctioned_CAM')->value), 2);
		}
	}
}

class Columns_Average_Ticket_Size_FOS extends Columns{

	public function Columns_Average_Ticket_Size_FOS(){
		$this->name = 'avg_ticket_size_fos';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Cases_Disbursed_FOS')->value != 0){
			$this->value = round(($class_row->get_column('Total_Value_Disbursed_FOS')->value/$class_row->get_column('Cases_Disbursed_FOS')->value), 2);
		}
	}
}

class Columns_Average_Ticket_Size_CAM extends Columns{

	public function Columns_Average_Ticket_Size_CAM(){
		$this->name = 'avg_ticket_size_cam';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Cases_Disbursed_CAM')->value != 0){
			$this->value = round(($class_row->get_column('Total_Value_Disbursed_CAM')->value/$class_row->get_column('Cases_Disbursed_CAM')->value), 2);
		}
	}
}

class Columns_Overall_Login_Perc extends Columns{

	public function Columns_Overall_Login_Perc(){
		$this->name = 'overall_login_perc';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('PickUp_Generated')->value != 0){
			$this->value = round(100*($class_row->get_column('Total_Logins')->value/$class_row->get_column('PickUp_Generated')->value), 2);
		}
	}
}

class Columns_Overall_Sanction_Perc extends Columns{

	public function Columns_Overall_Sanction_Perc(){
		$this->name = 'overall_sanction_perc';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Total_Logins')->value != 0){
			$this->value = round(100*($class_row->get_column('Total_Sanctions')->value/$class_row->get_column('Total_Logins')->value), 2);
		}
	}
}

class Columns_Overall_Disbursed_Perc extends Columns{

	public function Columns_Overall_Disbursed_Perc(){
		$this->name = 'overall_disbursed_perc';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Total_Sanctions')->value != 0){
			$this->value = round(100*($class_row->get_column('Total_Disbursals')->value/$class_row->get_column('Total_Sanctions')->value), 2);
		}
	}
}

class Columns_Overall_Ticket_Size extends Columns{

	public function Columns_Overall_Ticket_Size(){
		$this->name = 'overall_ticket_size';
	}

	public function calculate($row, Row $class_row){
		if ($class_row->get_column('Total_Disbursals')->value != 0){
			$this->value = round(($class_row->get_column('Total_Value_Disbursed')->value/$class_row->get_column('Total_Disbursals')->value), 2);
		}
	}
}


class Row{
	private $row_array;
	private $column_order_obj;
	public function Row(){
		$this->column_order_obj = new Column_Order;
		$column_order = $this->column_order_obj->get_column_order();

		foreach ($column_order as $column) {
			try{
				$class_name = "Columns_".$column;
				$this->row_array[$column] = new $class_name();
			}
			catch(Exception $e){
				echo "exception caught";
			}
		}
	}

	public function get_column($name){
		return $this->row_array[$name];
	}

}

class Report{
	private $report;
	private $column_order_obj;
	private $ls_array;

	public function Report(){
		$this->report = array();
	}
	public function run($from_date, $to_date){
		global $db;
		$this->column_order_obj = new Column_Order;
		$column_order = $this->column_order_obj->get_column_order();
		$this->ls_array = $GLOBALS['app_list_strings']['lead_source_list'];

		foreach($this->ls_array as $key=>$val){
			if ($key != '' and !empty($key)){
				$this->report[$key] = new Row();
				$this->report[$key]->get_column('LeadSource')->setValue($key);
			}
		}

		$query = "select * from
						(select  l.id as lead_id, lc.sub_disposition_c, l.opportunity_id as opportunity_id, l.status, l.first_name, l.date_entered, l.date_modified, l.lead_source, lc.sub_source_c, lc.disposition_c, u.description,  o.assigned_user_id as cam_or_fos, o.sales_stage, lc.loan_amount_c as loan_requested, o.amount as loan_disbursed from leads l
							LEFT JOIN leads_cstm lc on l.id= lc.id_c
							LEFT JOIN opportunities o on o.id = l.opportunity_id
							LEFT JOIN users u on u.id = o.assigned_user_id 
							where l.deleted = 0
							AND l.date_entered BETWEEN '$from_date' AND '$to_date') t1
							LEFT JOIN
							(select l.id as lead_id, l.assigned_user_id as caller_id,u.user_name as caller_user_name, LTRIM( RTRIM( CONCAT( IFNULL( u.first_name, '' ) , ' ', IFNULL( u.last_name, '' ) ) ) ) AS caller_name
								from leads l
								LEFT JOIN users u on u.id = l.assigned_user_id) t2
								on t1.lead_id = t2.lead_id";
	        
	    $result = $db->query($query);
	    while($row = $db->fetchByAssoc($result)){
	    	$lead_source = $row['lead_source'];
	    	if (!array_key_exists($lead_source, $this->report) and ($lead_source != NULL)){
	    		$this->report[$lead_source] = new Row();
	    	}

	    	foreach ($column_order as $column) {
	    		if($lead_source)
	    			$this->report[$lead_source]->get_column($column)->calculate($row, $this->report[$lead_source]);
	    	}
	    }

	    ksort($this->report);

	}

	public function get_report(){
		return $this->report;
	}

}







class scrm_Custom_ReportsViewgenerate_campaignwise_report extends SugarView {
	
	private $chartV;
    function __construct(){    
        parent::SugarView();
    }
    
    private $TATA_caller_array = array();
	private $Kenkei_caller_array = array();
	function getMatrixData($post)
	{
		
		global $db;	
		
		global $sugar_config;
	
		
			//From Date & To Date filter Condition
		$from_date = $_REQUEST['from_date'];

		if(!empty($from_date))
		{
			$tmp = explode("/",$from_date);
			if( count($tmp) == 3)
			{
				$from_date = $tmp[2].'-'.$tmp[1].'-'.$tmp[0];
			} else 
			$from_date = '';
		}
		$to_date = $_REQUEST['to_date'];
		if(!empty($to_date))
		{
			$tmp = explode("/",$to_date);
			if( count($tmp) == 3)
			{
				$to_date = $tmp[2].'-'.$tmp[1].'-'.($tmp[0]);
				$to_date = date('Y-m-d', strtotime($to_date. ' + 1 days'));
			} else 
			$to_date = '';
		}
		/*
		Disposition Codes:
			1.	new_unattempted
			2.	Not contactable
			3.	Call_back
			4.	Follow_up
			5.	Dropped (Not eligible)
			6.	Interested
			7.	Wrong number
			8.	Pickup generation_Appointment


			Lead source
			'Cold Call' => 'Cold Call',
		  'Self Generated' => 'Self Generated',
		  'Email' => 'Email',
		  'OBD Campaign' => 'OBD Campaign',
		  'BTL' => 'BTL',
		  'SMS' => 'SMS',
		  'Digital' => 'Digital',
		  'Missed Calls' => 'Missed Calls Website',
		  'IMS' => 'IMS',
		  'Alliances' => 'Alliances',
		  'Tele_Calling' => 'Tele Calling',
		  'missed_calls_sms' => 'Missed Calls SMS',
		  'Facebook' => 'Facebook',
		  'Referral' => 'Referral',
		*/


		$report = new Report();
		$report->run($from_date, $to_date);

		return $report->get_report();
	}
	
	
	function array_sort_by_column(&$arr
	, $col, $dir = SORT_ASC) {
            $sort_col = array();
            foreach ($arr as $key=> $row) {
                $sort_col[$key] = $row[$col];
            }

            array_multisort($sort_col, $dir, $arr);
     }
		
    function display()
	{
		global $db;
		$report = $this->getMatrixData($_REQUEST);
		$export_data_final = array();

		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
		$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";

		$column_order_obj = new Column_Order;
		$column_order = $column_order_obj->get_column_order();

		$data = "";

		foreach($report as $row){
			$export_data = array();

			$data .= '<tr height="25">';
			foreach ($column_order as $column){
				if($column !== 'Unit_Name'){
					$data .= "<td $td_style>".$row->get_column($column)->getValue().'</label></td>';
					array_push($export_data, $row->get_column($column)->getValue());
				}
			}
			$data .= '</tr>';
			if (!empty($export_data)){
					array_push($export_data_final, $export_data);
			}
		}

		echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';		
		$report_name = "Campaign Wise Report";
		echo $html =<<<HTML_Search
		<div id="mainBody">
			<div  id="imageLoading"></div
			<div id="TitleReport"> <h2>$report_name</h2></div>						
			<div id="SearchPanel">
			<form id="ReportRequest" name="ReportRequest" method="POST">
			<table cellpadding="0" cellspacing="10">
			<tr>
				<td><label>From Date: </label></td>
				<td> <input type="text" id="from_date" name="from_date" size="10" value='$_REQUEST[from_date]' /> 
					<img border="0" src="themes/SuiteR/images/jscalendar.gif" id="fromb" align="absmiddle" />
					<script type="text/javascript">
						Calendar.setup({inputField   : "from_date",
						ifFormat      :    "%d/%m/%Y", 
						button       : "fromb",
						align        : "right"});
					</script>
				 </td>
				 <td></td>
			     <td><label>To Date: </label></td>
			     <td> <input type="text" id="to_date" name="to_date" size="10" value='$_REQUEST[to_date]' /> 
					<img border="0" src="themes/SuiteR/images/jscalendar.gif" id="tob" align="absmiddle" />
					<script type="text/javascript">
						Calendar.setup({inputField   : "to_date",
						ifFormat      :    "%d/%m/%Y", 
						button       : "tob",
						align        : "right"});
					</script>
				   </td>
			       <td></td>

			       <td>

			<td>
			
				 </td>
			 </tr>
			</table>
			</br>
			<table cellspacing="10">
			<tr>
			<td><input type="submit" id="Run_Report" name="Run_Report" value="Run Report" />&nbsp;&nbsp;&nbsp;<input type="submit" id="clear" name="Clear" value="Clear">&nbsp;&nbsp;<input type="submit" id="Export" name="Export" value="Export" /> <!-- &nbsp;&nbsp;<input type="submit" id="Export_Chart" name="Export_Chart" value="Export Chart" /> -->&nbsp;&nbsp;&nbsp;
			</tr>
			</table>
			</form>
			</div>
		</div>
		

HTML_Search;
		echo $HTML_Data_header = <<<HTML_Data_header
		<div id="mainData">
			<div id="DataHeader">
			<table cellpadding="0" cellspacing="0"  border="1">
			<tr  height="25">
			<th  $header_style><label>Lead Source</lable></th>
			<th  $header_style><label>Fresh Data Given</lable></th>
			<th  $header_style><label>Fresh Data Attempted</lable></th>
			<th  $header_style><label>New</lable></th>
			<th  $header_style><label>Contactable Records</lable></th>
			<th  $header_style><label>RPCs</lable></th>
			<th  $header_style><label>Interested</lable></th>
			<th  $header_style><label>Pick Up Generated</lable></th>
			<th  $header_style><label>Leads given to CAM</lable></th>
			<th  $header_style><label>Cases picked up by FOS</lable></th>
			<th  $header_style><label>Cases picked up by CAM</lable></th>
			<th  $header_style><label>Cases logged in through FOS pickup</lable></th>
			<th  $header_style><label>Cases logged in through CAM pickup</lable></th>
			<th  $header_style><label>Cases Sanctioned of FOS login</lable></th>
			<th  $header_style><label>Cases Sanctioned of CAM login</lable></th>
			<th  $header_style><label>Cases disbursed of FOS login</lable></th>
			<th  $header_style><label>Cases disbursed of CAM login</lable></th>
			<th  $header_style><label>value of FOS disbursals</lable></th>
			<th  $header_style><label>value of CAM disbursals</lable></th>
			<th  $header_style><label>Total Pickups</lable></th>
			<th  $header_style><label>Total Logins</lable></th>
			<th  $header_style><label>Total Sanctions</lable></th>
			<th  $header_style><label>Total Disbursals</lable></th>
			<th  $header_style><label>Total Value of Disbursals</lable></th>
			<th  $header_style><label>Percent Achievement (Count of Cases)</lable></th>
			<th  $header_style><label>Contactability On Fresh Attempted Data (contactable/Fresh attempted)</lable></th>
			<th  $header_style><label>RPC percentage (RPC/contactable records)</lable></th>
			<th  $header_style><label>Interested Cases Percentage (Interested/Contactable records)</lable></th>
			<th  $header_style><label>PickUp Generated Cases Percentage (PickUpGenerated/Interested records)</lable></th>
			<th  $header_style><label>Leads Given to CAM Percentage (Leads given to cam/Interested)</lable></th>
			<th  $header_style><label>FOS pickup done to pickUp generated(FOS pickup/total pickups)</lable></th>
			<th  $header_style><label>CAM pickup done to leads given(cam pickups/leads given)</lable></th>
			<th  $header_style><label>Login/PickUp FOS</lable></th>
			<th  $header_style><label>Login/Pickup CAM</lable></th>
			<th  $header_style><label>Sanction/Login FOS</lable></th>
			<th  $header_style><label>Sanction/Login CAM</lable></th>
			<th  $header_style><label>Disbursed/Sanction FOS</lable></th>
			<th  $header_style><label>Disbursed/Sanction CAM</lable></th>
			<th  $header_style><label>Average Ticket Size (FOS)</lable></th>
			<th  $header_style><label>Average Ticket Size (CAM)</lable></th>
			<th  $header_style><label>Overall Login Percentage (Total logins/Total pickups)</lable></th>
			<th  $header_style><label>Overall Sanction Percentage (Total Sanctions/Total logins)</lable></th>
			<th  $header_style><label>Overall Disbursal Percentage (Total disbursals/Total sanctions)</lable></th>
			<th  $header_style><label>Overall Ticket Size</lable></th>
			</tr>
			$data
			</table>
			</div>
		</div>
HTML_Data_header;

		echo $js=<<<JS
		<script>
		    
        $('#Export_Chart').click(function(){
              a = document.getElementById("group_by[]");
              if(a.options[a.selectedIndex].value!='')
                return true;
              else {
                alert("Please select Across option ");
                return false;
              }
        });
        
        
        
       function showLoadingMesg()
        {
          $('#imageLoading').css('display','block');
          return true;
        }
        $('#Run_Report').attr('onclick','showLoadingMesg()');
		$('#send_email').click(function(){
		 email = document.getElementById("email").value;
		 if(email =='')
		 {
			alert("Please provide Email Address");
			 return false;
		 }
		
		});
		
		$('#clear').click(function(){
			$('#from_date').val('');
			$('#to_date').val('');
			$('select option').removeAttr("selected");
			return false;
		});
</script>		
JS;

		if(!empty($_REQUEST['Export']))
		{
			$timestamp = date('Y_m_d_His'); 
			ob_end_clean();
			ob_start();	
			// output headers so that the file is downloaded rather than displayed
			header('Content-Type: text/csv; charset=utf-8');
			header("Content-Disposition: attachment; filename=CampaignWise_Report{$timestamp}.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			fputcsv($output, array('Lead Source', 'Fresh Data Given','Fresh Data Attempted','New','Contactable Records','RPCs','Interested','Pick Up Generated','Leads given to CAM','Cases picked up by FOS','Cases picked up by CAM','Cases logged in through FOS pickup','Cases logged in through CAM pickup','Cases Sanctioned of FOS login','Cases Sanctioned of CAM login','Cases disbursed of FOS login','Cases disbursed of CAM login','value of FOS disbursals','value of CAM disbursals','Total Pickups','Total Logins','Total Sanctions','Total Disbursals','Total Value of Disbursals','Percent Achievement (Count of Cases)','Contactability On Fresh Attempted Data (contactable/Fresh attempted)','RPC percentage (RPC/contactable records)','Interested Cases Percentage (Interested/Contactable records)','PickUp Generated Cases Percentage (PickUpGenerated/Interested records)','Leads Given to CAM Percentage (Leads given to cam/Interested)','FOS pickup done to pickUp generated(FOS pickup/total pickups)','CAM pickup done to leads given(cam pickups/leads given)','Login/PickUp FOS','Login/Pickup CAM','Sanction/Login FOS','Sanction/Login CAM','Disbursed/Sanction FOS','Disbursed/Sanction CAM','Average Ticket Size (FOS)','Average Ticket Size (CAM)','Overall Login Percentage (Total logins/Total pickups)','Overall Sanction Percentage (Total Sanctions/Total logins)','Overall Disbursal Percentage (Total disbursals/Total sanctions)','Overall Ticket Size'));

			foreach ($export_data_final as $row_data)
			{
				fputcsv($output,$row_data);
			}
			exit;
			
		}
	} //end of display
} //end of class
