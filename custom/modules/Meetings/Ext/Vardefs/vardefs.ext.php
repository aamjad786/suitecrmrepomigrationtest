<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2018-05-17 16:45:13
$dictionary["Meeting"]["fields"]["neo_customers_meetings_1"] = array (
  'name' => 'neo_customers_meetings_1',
  'type' => 'link',
  'relationship' => 'neo_customers_meetings_1',
  'source' => 'non-db',
  'module' => 'Neo_Customers',
  'bean_name' => 'Neo_Customers',
  'vname' => 'LBL_NEO_CUSTOMERS_MEETINGS_1_FROM_NEO_CUSTOMERS_TITLE',
  'id_name' => 'neo_customers_meetings_1neo_customers_ida',
);
$dictionary["Meeting"]["fields"]["neo_customers_meetings_1_name"] = array (
  'name' => 'neo_customers_meetings_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_CUSTOMERS_MEETINGS_1_FROM_NEO_CUSTOMERS_TITLE',
  'save' => true,
  'id_name' => 'neo_customers_meetings_1neo_customers_ida',
  'link' => 'neo_customers_meetings_1',
  'table' => 'neo_customers',
  'module' => 'Neo_Customers',
  'rname' => 'name',
);
$dictionary["Meeting"]["fields"]["neo_customers_meetings_1neo_customers_ida"] = array (
  'name' => 'neo_customers_meetings_1neo_customers_ida',
  'type' => 'link',
  'relationship' => 'neo_customers_meetings_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_CUSTOMERS_MEETINGS_1_FROM_MEETINGS_TITLE',
);


// created: 2018-05-07 18:05:27
$dictionary["Meeting"]["fields"]["neo_paylater_leads_activities_1_meetings"] = array (
  'name' => 'neo_paylater_leads_activities_1_meetings',
  'type' => 'link',
  'relationship' => 'neo_paylater_leads_activities_1_meetings',
  'source' => 'non-db',
  'module' => 'Neo_Paylater_Leads',
  'bean_name' => 'Neo_Paylater_Leads',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_ACTIVITIES_1_MEETINGS_FROM_NEO_PAYLATER_LEADS_TITLE',
);


// created: 2018-02-15 14:35:25
$dictionary["Meeting"]["fields"]["neo_paylater_leads_meetings"] = array (
  'name' => 'neo_paylater_leads_meetings',
  'type' => 'link',
  'relationship' => 'neo_paylater_leads_meetings',
  'source' => 'non-db',
  'module' => 'Neo_Paylater_Leads',
  'bean_name' => 'Neo_Paylater_Leads',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_MEETINGS_FROM_NEO_PAYLATER_LEADS_TITLE',
  'id_name' => 'neo_paylater_leads_meetingsneo_paylater_leads_ida',
);
$dictionary["Meeting"]["fields"]["neo_paylater_leads_meetings_name"] = array (
  'name' => 'neo_paylater_leads_meetings_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_MEETINGS_FROM_NEO_PAYLATER_LEADS_TITLE',
  'save' => true,
  'id_name' => 'neo_paylater_leads_meetingsneo_paylater_leads_ida',
  'link' => 'neo_paylater_leads_meetings',
  'table' => 'neo_paylater_leads',
  'module' => 'Neo_Paylater_Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Meeting"]["fields"]["neo_paylater_leads_meetingsneo_paylater_leads_ida"] = array (
  'name' => 'neo_paylater_leads_meetingsneo_paylater_leads_ida',
  'type' => 'link',
  'relationship' => 'neo_paylater_leads_meetings',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_MEETINGS_FROM_MEETINGS_TITLE',
);


 // created: 2021-07-27 18:49:08
$dictionary['Meeting']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

 // created: 2021-07-27 18:49:08
$dictionary['Meeting']['fields']['jjwg_maps_geocode_status_c']['inline_edit']=1;

 

 // created: 2021-07-27 18:49:08
$dictionary['Meeting']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

 // created: 2021-07-27 18:49:08
$dictionary['Meeting']['fields']['jjwg_maps_lng_c']['inline_edit']=1;

 
?>