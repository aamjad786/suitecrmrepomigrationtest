<?php
// created: 2017-08-23 11:29:58
$dictionary["SMS_SMS"]["fields"]["cases_sms_sms_1"] = array (
  'name' => 'cases_sms_sms_1',
  'type' => 'link',
  'relationship' => 'cases_sms_sms_1',
  'source' => 'non-db',
  'module' => 'Cases',
  'bean_name' => 'Case',
  'vname' => 'LBL_CASES_SMS_SMS_1_FROM_CASES_TITLE',
  'id_name' => 'cases_sms_sms_1cases_ida',
);
$dictionary["SMS_SMS"]["fields"]["cases_sms_sms_1_name"] = array (
  'name' => 'cases_sms_sms_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CASES_SMS_SMS_1_FROM_CASES_TITLE',
  'save' => true,
  'id_name' => 'cases_sms_sms_1cases_ida',
  'link' => 'cases_sms_sms_1',
  'table' => 'cases',
  'module' => 'Cases',
  'rname' => 'name',
);
$dictionary["SMS_SMS"]["fields"]["cases_sms_sms_1cases_ida"] = array (
  'name' => 'cases_sms_sms_1cases_ida',
  'type' => 'link',
  'relationship' => 'cases_sms_sms_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CASES_SMS_SMS_1_FROM_SMS_SMS_TITLE',
);
