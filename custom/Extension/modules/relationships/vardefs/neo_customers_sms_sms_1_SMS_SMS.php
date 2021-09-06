<?php
// created: 2018-08-17 13:21:15
$dictionary["SMS_SMS"]["fields"]["neo_customers_sms_sms_1"] = array (
  'name' => 'neo_customers_sms_sms_1',
  'type' => 'link',
  'relationship' => 'neo_customers_sms_sms_1',
  'source' => 'non-db',
  'module' => 'Neo_Customers',
  'bean_name' => 'Neo_Customers',
  'vname' => 'LBL_NEO_CUSTOMERS_SMS_SMS_1_FROM_NEO_CUSTOMERS_TITLE',
  'id_name' => 'neo_customers_sms_sms_1neo_customers_ida',
);
$dictionary["SMS_SMS"]["fields"]["neo_customers_sms_sms_1_name"] = array (
  'name' => 'neo_customers_sms_sms_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_CUSTOMERS_SMS_SMS_1_FROM_NEO_CUSTOMERS_TITLE',
  'save' => true,
  'id_name' => 'neo_customers_sms_sms_1neo_customers_ida',
  'link' => 'neo_customers_sms_sms_1',
  'table' => 'neo_customers',
  'module' => 'Neo_Customers',
  'rname' => 'name',
);
$dictionary["SMS_SMS"]["fields"]["neo_customers_sms_sms_1neo_customers_ida"] = array (
  'name' => 'neo_customers_sms_sms_1neo_customers_ida',
  'type' => 'link',
  'relationship' => 'neo_customers_sms_sms_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_CUSTOMERS_SMS_SMS_1_FROM_SMS_SMS_TITLE',
);
