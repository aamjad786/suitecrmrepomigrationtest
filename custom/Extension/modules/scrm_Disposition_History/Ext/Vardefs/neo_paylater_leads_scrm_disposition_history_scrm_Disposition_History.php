<?php
// created: 2018-02-15 14:35:25
$dictionary["scrm_Disposition_History"]["fields"]["neo_paylater_leads_scrm_disposition_history"] = array (
  'name' => 'neo_paylater_leads_scrm_disposition_history',
  'type' => 'link',
  'relationship' => 'neo_paylater_leads_scrm_disposition_history',
  'source' => 'non-db',
  'module' => 'Neo_Paylater_Leads',
  'bean_name' => 'Neo_Paylater_Leads',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_SCRM_DISPOSITION_HISTORY_FROM_NEO_PAYLATER_LEADS_TITLE',
  'id_name' => 'neo_paylatece6r_leads_ida',
);
$dictionary["scrm_Disposition_History"]["fields"]["neo_paylater_leads_scrm_disposition_history_name"] = array (
  'name' => 'neo_paylater_leads_scrm_disposition_history_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_SCRM_DISPOSITION_HISTORY_FROM_NEO_PAYLATER_LEADS_TITLE',
  'save' => true,
  'id_name' => 'neo_paylatece6r_leads_ida',
  'link' => 'neo_paylater_leads_scrm_disposition_history',
  'table' => 'neo_paylater_leads',
  'module' => 'Neo_Paylater_Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["scrm_Disposition_History"]["fields"]["neo_paylatece6r_leads_ida"] = array (
  'name' => 'neo_paylatece6r_leads_ida',
  'type' => 'link',
  'relationship' => 'neo_paylater_leads_scrm_disposition_history',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_SCRM_DISPOSITION_HISTORY_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
);
