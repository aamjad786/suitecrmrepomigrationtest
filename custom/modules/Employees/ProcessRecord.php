<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
//~ ini_set('display_errors', 'On');
class ProcessRecord {
	
	public function add_edit_button(&$bean, $event, $args) {
		global $current_user, $sugar_config, $db;
        
        $role = ACLRole::getUserRoleNames($current_user->id);
        
        if($role[0] == "Cluster Manager") {
			$current_user_id = $current_user->id;
			$query = "select id from users where reports_to_id = '$current_user_id' and deleted = 0";
			$result = $db->query($query);
			$user_ids = array();
			while($row = $db->fetchByAssoc($result)){
				$user_ids[] = $row['id'];
			}
			$id = $bean->id;
			if(in_array($id, $user_ids)) {
				$url = $sugar_config['site_url'];
				$url = $url . '/index.php?module=Employees&action=EditView&record='.$id;
				$bean->edit_field_c = '<a href = "'.$url.'" alt="url"><img border="0" align="absmiddle" alt="Alert" src="themes/SuiteR/images/edit_inline.gif" width="20px" height="20px"></a>';
			}
		} else if($role[0] == "Call Center Manager") {
			$rec_id = $bean->id;
			$query = "select ar.name from acl_roles ar join acl_roles_users aru on aru.role_id = ar.id and aru.deleted = 0 join users u on u.id = aru.user_id and u.deleted = 0  and u.id = '$rec_id' where ar.deleted = 0";
			$result = $db->query($query);
			$name = '';
			while($row = $db->fetchByAssoc($result)){
				$name = $row['name'];
			}
			if($name == "Call Center Agent"){
				$url = $sugar_config['site_url'];
				$url = $url . '/index.php?module=Employees&action=EditView&record='.$rec_id;
				$bean->edit_field_c = '<a href = "'.$url.'" alt="url"><img border="0" align="absmiddle" alt="Alert" src="themes/SuiteR/images/edit_inline.gif" width="20px" height="20px"></a>';
			}
			
		}
	}
	
}

		
		
