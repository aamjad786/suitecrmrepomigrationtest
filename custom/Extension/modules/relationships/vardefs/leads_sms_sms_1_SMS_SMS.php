<?php
// created: 2021-09-01 17:21:53
$dictionary["SMS_SMS"]["fields"]["leads_sms_sms_1"] = array (
  'name' => 'leads_sms_sms_1',
  'type' => 'link',
  'relationship' => 'leads_sms_sms_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_SMS_SMS_1_FROM_LEADS_TITLE',
  'id_name' => 'leads_sms_sms_1leads_ida',
);
$dictionary["SMS_SMS"]["fields"]["leads_sms_sms_1_name"] = array (
  'name' => 'leads_sms_sms_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_SMS_SMS_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_sms_sms_1leads_ida',
  'link' => 'leads_sms_sms_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["SMS_SMS"]["fields"]["leads_sms_sms_1leads_ida"] = array (
  'name' => 'leads_sms_sms_1leads_ida',
  'type' => 'link',
  'relationship' => 'leads_sms_sms_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_LEADS_SMS_SMS_1_FROM_SMS_SMS_TITLE',
);
