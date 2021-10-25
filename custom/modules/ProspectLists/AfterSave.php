<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class AfterSave{
	public function assign_targets_to_group(&$bean, $event, $args){
		global $db;
        $ID = $bean->id;
        $list_type = $bean->list_type;
		$assigned_user_id_fetched = $bean->fetched_row['assigned_user_id'];
        $assigned_user_id = $bean->assigned_user_id;
        
        $target_date_assigned_c = date("Y-m-d H:i:s");
		$target_date_assigned_c = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($target_date_assigned_c)));
        
		if($list_type=='Cloud_Agent' && strcmp($assigned_user_id_fetched , $assigned_user_id) != 0){
			
				$query = "UPDATE prospects p JOIN prospects_cstm pc ON pc.id_c = p.id JOIN prospect_lists_prospects plp ON plp.related_id = p.id AND plp.prospect_list_id = '$ID' AND plp.related_type = 'Prospects' AND plp.deleted =0 SET p.assigned_user_id = '$assigned_user_id' , pc.target_date_assigned_c = '$target_date_assigned_c' WHERE p.deleted = 0";
				
				$db->query($query);
				
				$db->query("update prospect_lists set date_assigned = '$target_date_assigned_c' where id = '$ID' and deleted = 0");
				
				$name = $bean->name;
				$description = $bean->description;
				$parent_id = $bean->id;
				$parent_type = "ProspectLists";
				
				$insert_popup = "INSERT INTO alerts  (id,name,date_entered,date_modified,modified_user_id,created_by,description,deleted,assigned_user_id,is_read,target_module,type,url_redirect) VALUES (UUID(),'New Target List - $name - ',NOW(),NOW(),'1','1','$description','0','$assigned_user_id','0','$parent_type','info','index.php?action=DetailView&module=$parent_type&record=$parent_id')";
			$insert_popup_result = $db->query($insert_popup);
		}
	}
}

?>
