<?php

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
require_once('SendSMS.php');
class BeforeSaveLead
{

	public function change_lead_status(&$bean, $event, $args) {

		$bean->stored_fetched_row_c = $bean->fetched_row;
		$bean->primary_address_city = strtoupper($bean->primary_address_city);
	}

	public function check_duplicate_lead(&$bean, $event, $args) {
		
		global $db, $sugar_config;

		if (!empty($bean->dwh_sync_c)) {
			return;
		}
		if ($bean->is_renewal_c == 1) {
			return;
		}


		$mobile = $bean->phone_mobile;
		$lead_source = $bean->lead_source;
		// $query  = "select id from leads where deleted = 0 and phone_mobile = '$mobile' and lead_source = '$lead_source' and date_entered between '$from_date' and '$to_date'";
		$query  = "select id from leads where deleted = 0 and phone_mobile = '$mobile' and date_entered> CURDATE() - INTERVAL 30 DAY order by date_entered desc limit 1";
		$result = $db->query($query);

		if (empty($bean->fetched_row) && $result->num_rows > 0) {

			if ($_REQUEST['module'] == 'Import') {
				$bean->deleted = 1;
			} else {
				$bean->deleted = 1;
				echo json_encode('Mobile number already exists.');
				sugar_die();
			}
		}
	}
	
	public function check_digits(&$bean, $event, $args)
	{
		global $db, $sugar_config;

		if (!empty($bean->dwh_sync_c)) {
			return;
		}

		$mobile = $this->validate_mobile(trim($bean->phone_mobile));

		if ($mobile != 1) {

			if ($_REQUEST['module'] == 'Import') {
				$bean->deleted = 1;
			} else {

				echo json_encode('Please enter a valid 10 digit Mobile to continue.');
				sugar_die();
			}
		}
	}

	public function validate_mobile($mobile) {
		return preg_match('/^[0-9]{10}+$/', $mobile);
	}

	public function assignedUser($city)	{
		global $timedate;

		global $app_list_strings, $db;

		$list = $GLOBALS['app_list_strings']['cluster_city_mapping'];

		if (!empty($city) && array_key_exists($city, $list)) {

			$ngid = $list[$city];

			$user_bean = BeanFactory::getBean('Users');

			$query = 'users.deleted=0 and users.user_name = "' . $ngid . '"';

			$users = $user_bean->get_full_list('', $query);

			if (!empty($users)) {

				$user = $users[0];
				$userId = $user->id;
			} else {

				$userId = "";
			}
		}

		return $userId;
	}

	public function assignedUserId(&$bean, $event, $args)	{

		if (empty($bean->assigned_user_id)) {

			$city = strtoupper($bean->primary_address_city);

			$bean->assigned_user_id = $this->assignedUser($city);
		}
	}
}
