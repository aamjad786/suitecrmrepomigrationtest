<?php
// created: 2021-09-04 15:09:03
$dictionary["Lead"]["fields"]["scrm_disposition_history_leads_1"] = array (
  'name' => 'scrm_disposition_history_leads_1',
  'type' => 'link',
  'relationship' => 'scrm_disposition_history_leads_1',
  'source' => 'non-db',
  'module' => 'scrm_Disposition_History',
  'bean_name' => 'scrm_Disposition_History',
  'vname' => 'LBL_SCRM_DISPOSITION_HISTORY_LEADS_1_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
  'id_name' => 'scrm_disposition_history_leads_1scrm_disposition_history_ida',
);
$dictionary["Lead"]["fields"]["scrm_disposition_history_leads_1_name"] = array (
  'name' => 'scrm_disposition_history_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SCRM_DISPOSITION_HISTORY_LEADS_1_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
  'save' => true,
  'id_name' => 'scrm_disposition_history_leads_1scrm_disposition_history_ida',
  'link' => 'scrm_disposition_history_leads_1',
  'table' => 'scrm_disposition_history',
  'module' => 'scrm_Disposition_History',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["scrm_disposition_history_leads_1scrm_disposition_history_ida"] = array (
  'name' => 'scrm_disposition_history_leads_1scrm_disposition_history_ida',
  'type' => 'link',
  'relationship' => 'scrm_disposition_history_leads_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SCRM_DISPOSITION_HISTORY_LEADS_1_FROM_LEADS_TITLE',
);
