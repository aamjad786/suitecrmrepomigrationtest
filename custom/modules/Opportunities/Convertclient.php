<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class ConvertClient_class{

function convertclient_method($bean, $event, $arguments){

 global $db;
  $ID= $bean->id;
  $OppID= $bean->id;
  $sales_stage= $bean->sales_stage;
 $account_id = $bean->account_id;
if($sales_stage=="Loan_Disbursed"){
	
	
	echo  $query="SELECT `id` FROM `scrm_clients_opportunities_1_c` WHERE scrm_clients_opportunities_1opportunities_idb='$OppID' AND `deleted`='0'";	
  $result = $db->query($query);
  $result_row = $db->fetchByAssoc($result);
  $result = $db->query($query);
	  $id = $result_row['id'];
	  
if(empty($id)){
	
 $query="SELECT `id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `account_type`, `industry`, `annual_revenue`, `phone_fax`, `billing_address_street`, `billing_address_city`, `billing_address_state`, `billing_address_postalcode`, `billing_address_country`, `rating`, `phone_office`, `phone_alternate`, `website`, `ownership`, `employees`, `ticker_symbol`, `shipping_address_street`, `shipping_address_city`, `shipping_address_state`, `shipping_address_postalcode`, `shipping_address_country`, `parent_id`, `sic_code`, `campaign_id` FROM `accounts` WHERE id='$account_id' AND `deleted`='0'";	
  $result = $db->query($query);
  $result_row = $db->fetchByAssoc($result);
  $name = $result_row['name'];
  $description = $result_row['description'];
  $assigned_user_id = $result_row['assigned_user_id'];
  $account_type = $result_row['account_type'];
  $industry = $result_row['industry'];
  $annual_revenue = $result_row['annual_revenue'];
  $phone_fax = $result_row['phone_fax'];
  $billing_address_street = $result_row['billing_address_street'];
  $billing_address_city = $result_row['billing_address_city'];
  $billing_address_postalcode = $result_row['billing_address_postalcode'];
  $billing_address_country = $result_row['billing_address_country'];
  $rating = $result_row['rating'];
  $phone_office = $result_row['phone_office'];
  $phone_alternate = $result_row['phone_alternate'];
  $website = $result_row['website'];
  $ownership = $result_row['ownership'];
  $shipping_address_street = $result_row['shipping_address_street'];
  $shipping_address_city = $result_row['shipping_address_city'];
  $shipping_address_state = $result_row['shipping_address_state'];
  $shipping_address_postalcode = $result_row['shipping_address_postalcode'];
  $shipping_address_country = $result_row['shipping_address_country'];
  $ClientID=create_guid();
  $query="INSERT INTO `scrm_clients`(`id`, `name`, `date_entered`, `date_modified`, `description`, `deleted`, `assigned_user_id`, `scrm_clients_type`, `industry`, `annual_revenue`, `phone_fax`, `billing_address_street`, `billing_address_city`, `billing_address_state`, `billing_address_postalcode`, `billing_address_country`, `rating`, `phone_office`, `phone_alternate`, `website`, `ownership`,`shipping_address_street`, `shipping_address_city`, `shipping_address_state`, `shipping_address_postalcode`, `shipping_address_country`) VALUES ('$ClientID','$name',NOW(), NOW(),' $description','0','$assigned_user_id','$account_type','$industry','$annual_revenue','$phone_fax','$billing_address_street','$billing_address_city','$billing_address_state','$billing_address_postalcode','$billing_address_country','$rating','$phone_office','$phone_alternate','$website','$ownership','$shipping_address_street','$shipping_address_city','$shipping_address_state','$shipping_address_postalcode','$shipping_address_country')";
  $result = $db->query($query);
 
  $query="SELECT `id_c`, `mobile1_c`,`personal_email_c`,`official_email_c`, `mobile2_c`, `pan_no_c` FROM `accounts_cstm` WHERE id_c='$account_id'";	
  $result = $db->query($query);
  $result_row = $db->fetchByAssoc($result);
  $mobile1_c = $result_row['mobile1_c'];
  $personal_email_c = $result_row['personal_email_c'];
  $official_email_c = $result_row['official_email_c'];
  $mobile2_c = $result_row['mobile2_c'];
  $pan_no_c = $result_row['pan_no_c'];
  $query="INSERT INTO `scrm_clients_cstm`(`id_c`, `mobile1_c`, `mobile2_c`, `personal_email_c`,`organization_type_c`, `pan_no_c`) VALUES ('$ClientID','$mobile1_c','$personal_email_c','$official_email_c', '$mobile2_c','$account_type','$pan_no_c')";
  $result = $db->query($query);
  $RelateID=create_guid();
  $query="INSERT INTO `scrm_clients_opportunities_1_c`(`id`, `date_modified`, `deleted`, `scrm_clients_opportunities_1scrm_clients_ida`, `scrm_clients_opportunities_1opportunities_idb`) VALUES ('$RelateID', NOW(), '0','$ClientID','$OppID')";
  $result = $db->query($query);
}
}
}

}
