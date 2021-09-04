<?php
// created: 2021-09-04 10:05:32
$dictionary["scrm_Disposition_History"]["fields"]["leads_scrm_disposition_history_1"] = array (
  'name' => 'leads_scrm_disposition_history_1',
  'type' => 'link',
  'relationship' => 'leads_scrm_disposition_history_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_SCRM_DISPOSITION_HISTORY_1_FROM_LEADS_TITLE',
  'id_name' => 'leads_scrm_disposition_history_1leads_ida',
);
$dictionary["scrm_Disposition_History"]["fields"]["leads_scrm_disposition_history_1_name"] = array (
  'name' => 'leads_scrm_disposition_history_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_SCRM_DISPOSITION_HISTORY_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_scrm_disposition_history_1leads_ida',
  'link' => 'leads_scrm_disposition_history_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["scrm_Disposition_History"]["fields"]["leads_scrm_disposition_history_1leads_ida"] = array (
  'name' => 'leads_scrm_disposition_history_1leads_ida',
  'type' => 'link',
  'relationship' => 'leads_scrm_disposition_history_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_LEADS_SCRM_DISPOSITION_HISTORY_1_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
);
