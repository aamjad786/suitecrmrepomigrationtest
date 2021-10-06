<?php
// created: 2016-09-16 16:46:41
$dictionary["scrm_Disposition_History"]["fields"]["prospects_scrm_disposition_history_1"] = array (
  'name' => 'prospects_scrm_disposition_history_1',
  'type' => 'link',
  'relationship' => 'prospects_scrm_disposition_history_1',
  'source' => 'non-db',
  'module' => 'Prospects',
  'bean_name' => 'Prospect',
  'vname' => 'LBL_PROSPECTS_SCRM_DISPOSITION_HISTORY_1_FROM_PROSPECTS_TITLE',
  'id_name' => 'prospects_scrm_disposition_history_1prospects_ida',
);
$dictionary["scrm_Disposition_History"]["fields"]["prospects_scrm_disposition_history_1_name"] = array (
  'name' => 'prospects_scrm_disposition_history_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PROSPECTS_SCRM_DISPOSITION_HISTORY_1_FROM_PROSPECTS_TITLE',
  'save' => true,
  'id_name' => 'prospects_scrm_disposition_history_1prospects_ida',
  'link' => 'prospects_scrm_disposition_history_1',
  'table' => 'prospects',
  'module' => 'Prospects',
  'rname' => 'account_name',
);
$dictionary["scrm_Disposition_History"]["fields"]["prospects_scrm_disposition_history_1prospects_ida"] = array (
  'name' => 'prospects_scrm_disposition_history_1prospects_ida',
  'type' => 'link',
  'relationship' => 'prospects_scrm_disposition_history_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PROSPECTS_SCRM_DISPOSITION_HISTORY_1_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
);
