<?php
// created: 2018-02-15 14:35:25
$dictionary["SMS_SMS"]["fields"]["neo_paylater_leads_sms_sms"] = array (
  'name' => 'neo_paylater_leads_sms_sms',
  'type' => 'link',
  'relationship' => 'neo_paylater_leads_sms_sms',
  'source' => 'non-db',
  'module' => 'Neo_Paylater_Leads',
  'bean_name' => 'Neo_Paylater_Leads',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_SMS_SMS_FROM_NEO_PAYLATER_LEADS_TITLE',
  'id_name' => 'neo_paylater_leads_sms_smsneo_paylater_leads_ida',
);
$dictionary["SMS_SMS"]["fields"]["neo_paylater_leads_sms_sms_name"] = array (
  'name' => 'neo_paylater_leads_sms_sms_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_SMS_SMS_FROM_NEO_PAYLATER_LEADS_TITLE',
  'save' => true,
  'id_name' => 'neo_paylater_leads_sms_smsneo_paylater_leads_ida',
  'link' => 'neo_paylater_leads_sms_sms',
  'table' => 'neo_paylater_leads',
  'module' => 'Neo_Paylater_Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["SMS_SMS"]["fields"]["neo_paylater_leads_sms_smsneo_paylater_leads_ida"] = array (
  'name' => 'neo_paylater_leads_sms_smsneo_paylater_leads_ida',
  'type' => 'link',
  'relationship' => 'neo_paylater_leads_sms_sms',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_SMS_SMS_FROM_SMS_SMS_TITLE',
);
