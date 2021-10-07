<?php
// created: 2016-09-15 14:53:46
$dictionary["Lead"]["fields"]["scrm_disposition_history_leads"] = array (
  'name' => 'scrm_disposition_history_leads',
  'type' => 'link',
  'relationship' => 'scrm_disposition_history_leads',
  'source' => 'non-db',
  'module' => 'scrm_Disposition_History',
  'bean_name' => 'scrm_Disposition_History',
  'vname' => 'LBL_SCRM_DISPOSITION_HISTORY_LEADS_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
  'id_name' => 'scrm_disposition_history_leadsscrm_disposition_history_ida',
);
$dictionary["Lead"]["fields"]["scrm_disposition_history_leads_name"] = array (
  'name' => 'scrm_disposition_history_leads_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SCRM_DISPOSITION_HISTORY_LEADS_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
  'save' => true,
  'id_name' => 'scrm_disposition_history_leadsscrm_disposition_history_ida',
  'link' => 'scrm_disposition_history_leads',
  'table' => 'scrm_disposition_history',
  'module' => 'scrm_Disposition_History',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["scrm_disposition_history_leadsscrm_disposition_history_ida"] = array (
  'name' => 'scrm_disposition_history_leadsscrm_disposition_history_ida',
  'type' => 'link',
  'relationship' => 'scrm_disposition_history_leads',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SCRM_DISPOSITION_HISTORY_LEADS_FROM_LEADS_TITLE',
);
