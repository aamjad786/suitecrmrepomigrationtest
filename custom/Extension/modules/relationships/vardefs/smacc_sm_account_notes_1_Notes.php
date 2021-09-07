<?php
// created: 2018-05-02 18:21:04
$dictionary["Note"]["fields"]["smacc_sm_account_notes_1"] = array (
  'name' => 'smacc_sm_account_notes_1',
  'type' => 'link',
  'relationship' => 'smacc_sm_account_notes_1',
  'source' => 'non-db',
  'module' => 'SMAcc_SM_Account',
  'bean_name' => 'SMAcc_SM_Account',
  'vname' => 'LBL_SMACC_SM_ACCOUNT_NOTES_1_FROM_SMACC_SM_ACCOUNT_TITLE',
  'id_name' => 'smacc_sm_account_notes_1smacc_sm_account_ida',
);
$dictionary["Note"]["fields"]["smacc_sm_account_notes_1_name"] = array (
  'name' => 'smacc_sm_account_notes_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SMACC_SM_ACCOUNT_NOTES_1_FROM_SMACC_SM_ACCOUNT_TITLE',
  'save' => true,
  'id_name' => 'smacc_sm_account_notes_1smacc_sm_account_ida',
  'link' => 'smacc_sm_account_notes_1',
  'table' => 'smacc_sm_account',
  'module' => 'SMAcc_SM_Account',
  'rname' => 'name',
);
$dictionary["Note"]["fields"]["smacc_sm_account_notes_1smacc_sm_account_ida"] = array (
  'name' => 'smacc_sm_account_notes_1smacc_sm_account_ida',
  'type' => 'link',
  'relationship' => 'smacc_sm_account_notes_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SMACC_SM_ACCOUNT_NOTES_1_FROM_NOTES_TITLE',
);
