<?php 
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class Assign_target
{
     function assign_target($bean, $event, $arguments)
    {
        global $db;
        $ID = $bean->id;
        $assigned_user_id = $bean->assigned_user_id;
        $list_type = $bean->list_type;
		if($list_type=='Cloud_Agent'){
			$Pro_list_record = $db->query("Select related_id from prospect_lists_prospects where prospect_lists_prospects.prospect_list_id ='$ID' AND related_type='Prospects'");
			$row = $Pro_list_record->fetch_assoc();
				while($row = $Pro_list_record->fetch_assoc()){
					$prospects_ID=$row["related_id"];
					$db->query("UPDATE `prospects` SET `assigned_user_id`='$assigned_user_id' WHERE `id`='$prospects_ID' AND deleted=0");
				}
		}
	}
    function addRel_assign_target($bean, $event, $arguments)
    {
        global $db;
        $ID = $bean->id;
        $assigned_user_id = $bean->assigned_user_id;
        $list_type = $bean->list_type;
        $related_module=$arguments['related_module'];
		$related_id=$arguments['related_id'];
		
		$target_date_assigned_c = date("Y-m-d H:i:s");
		//$target_date_assigned_c = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($target_date_assigned_c)));
		
		
		if($list_type=='Cloud_Agent' && $related_module=='Prospects' ){
				$campaign_id = '';
				$result = $db->query("select campaign_id from prospect_list_campaigns where prospect_list_id = '$ID' and deleted = 0");
				if($row = $db->fetchByAssoc($result)){
					$campaign_id = $row['campaign_id'];
				}
				//$db->query("UPDATE prospects p JOIN prospects_cstm pc ON pc.id_c = p.id SET p.assigned_user_id = '$assigned_user_id', p.campaign_id = '$campaign_id', pc.target_date_assigned_c = '$target_date_assigned_c' WHERE p.id = '$related_id' AND p.deleted = 0");
				
				//update using bean
				$prospects_bean = BeanFactory::getBean('Prospects', $related_id);
				$prospects_bean->assigned_user_id = $assigned_user_id;
				$prospects_bean->campaign_id = $campaign_id;
				$prospects_bean->target_date_assigned_c = $target_date_assigned_c;
				$prospects_bean->save();
				
			} else if($related_module == 'Campaigns'){
				$db->query("update prospects p join prospects_cstm pc on pc.id_c = p.id join prospect_lists_prospects pp on p.id = pp.related_id and pp.deleted = 0 and pp.prospect_list_id = '$ID' SET p.assigned_user_id = '$assigned_user_id', p.campaign_id = '$related_id', pc.target_date_assigned_c = '$target_date_assigned_c' WHERE p.deleted = 0");	
			}
        	
	}
        
     
}
    
?>
