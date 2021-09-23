<?php
if(!defined('sugarEntry'))define('sugarEntry', true);

class AfterSaveLogicHook {
	
	public function UpdateMerchantName(&$bean, $event, $args) {
		
		$name_old = $bean->fetched_row['name'];
		$name_new = $bean->name;
		
		//~ if(!empty($name_old) && strcmp($name_new,$name_old) != 0){
			
			global $db;
			$account_id = $bean->id;
			
			$query1 = "UPDATE leads_cstm lc JOIN leads l ON l.id = lc.id_c SET merchant_name_c = '$name_new' WHERE l.account_id = '$account_id' AND l.deleted = 0";
			$db->query($query1);
			
			$query2 = "UPDATE opportunities_cstm oc JOIN accounts_opportunities ao ON ao.opportunity_id = oc.id_c SET oc.merchant_name_c = '$name_new' WHERE ao.account_id = '$account_id' AND ao.deleted = 0";
			$db->query($query2);
			
		//~ }
	}
}
