<?php if(!defined('sugarEntry')) define('sugarEntry', true);

require_once 'modules/ACLRoles/ACLRole.php';
require_once 'data/BeanFactory.php';
require_once 'data/SugarBean.php';
require_once 'data/Relationships/SugarRelationship.php';

global $db;
global $sugar_config, $app_list_strings, $GLOBALS;
global $current_user;

if (true) {
	if(isset($_REQUEST['userID']) && isset($_REQUEST['roleID'])){
		$user_id = trim(htmlspecialchars_decode($_REQUEST['userID'], ENT_QUOTES));
		$role_id = trim(htmlspecialchars_decode($_REQUEST['roleID'], ENT_QUOTES));

		$bean = BeanFactory::getBean('Users',$user_id);
		$bean->load_relationship('aclroles');
		if (isset($_REQUEST['remove']) && $_REQUEST['remove'] == 0) {
			$status = $bean->aclroles->add($role_id);
			if($status)
				echo "Added ".$user_id." to ".$role_id;
			else
				echo "Unable add user to the given role. Some error.";
		}
		else if(isset($_REQUEST['remove']) && $_REQUEST['remove'] == 1){
			$status = $bean->aclroles->delete($bean, $role_id);
			if($status)
				echo "Removed ".$user_id." from ".$role_id;
			else
				echo "Unable remove user from the given role. Some error.";
		}else{
			echo "Remove option not set";
		}
	}
	else if(isset($_REQUEST['userID']) && isset($_REQUEST['sgID'])) {
		$user_id = trim(htmlspecialchars_decode($_REQUEST['userID'], ENT_QUOTES));
		$sg_id = trim(htmlspecialchars_decode($_REQUEST['sgID'], ENT_QUOTES));

		$bean = BeanFactory::getBean('Users',$user_id);
		$bean->load_relationship('SecurityGroups');
		if (isset($_REQUEST['remove']) && $_REQUEST['remove'] == 0) {
			$status = $bean->SecurityGroups->add($sg_id);
			if($status)
				echo "Added ".$user_id." to ".$sg_id;
			else
				echo "Unable add User to the given Security Group. Some error.";
		}
		else if(isset($_REQUEST['remove']) && $_REQUEST['remove'] == 1){
			$status = $bean->SecurityGroups->delete($bean, $sg_id);
			if($status)
				echo "Removed ".$user_id." from ".$sg_id;
			else
				echo "Unable remove User from the given SecurityGroups. Some error.";
		}else{
			echo "Remove option not set";
		}

	}
	else if(isset($_REQUEST['roleID']) && isset($_REQUEST['sgID'])) {
		$role_id = trim(htmlspecialchars_decode($_REQUEST['roleID'], ENT_QUOTES));
		$sg_id = trim(htmlspecialchars_decode($_REQUEST['sgID'], ENT_QUOTES));

		$bean = BeanFactory::getBean('SecurityGroups',$sg_id);
		$bean->load_relationship('aclroles');
		if (isset($_REQUEST['remove']) && $_REQUEST['remove'] == 0) {
			$status = $bean->aclroles->add($role_id);
			if($status)
				echo "Added ".$role_id." to ".$sg_id;
			else
				echo "Unable add Role to the given SecurityGroups. Some error.";
		}
		else if(isset($_REQUEST['remove']) && $_REQUEST['remove'] == 1){
			$status = $bean->aclroles->delete($bean, $role_id);
			if($status)
				echo "Removed ".$role_id." from ".$sg_id;
			else
				echo "Unable remove Role from the given SecurityGroups. Some error.";
		}else{
			echo "Remove option not set";
		}
	}
	else{
		if(!isset($_REQUEST['roleID'])) {
			echo "No Role ID.";
		}
		if(!isset($_REQUEST['sgID'])) {
			echo "No Security Group ID.";
		}
		if(!isset($_REQUEST['userID'])) {
			echo "No User ID.";
		}
	}

}else{
	echo "No access.";
}