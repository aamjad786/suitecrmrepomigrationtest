<?php
// created: 2018-02-15 14:35:25
$dictionary["Call"]["fields"]["neo_paylater_leads_calls"] = array (
  'name' => 'neo_paylater_leads_calls',
  'type' => 'link',
  'relationship' => 'neo_paylater_leads_calls',
  'source' => 'non-db',
  'module' => 'Neo_Paylater_Leads',
  'bean_name' => 'Neo_Paylater_Leads',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_CALLS_FROM_NEO_PAYLATER_LEADS_TITLE',
  'id_name' => 'neo_paylater_leads_callsneo_paylater_leads_ida',
);
$dictionary["Call"]["fields"]["neo_paylater_leads_calls_name"] = array (
  'name' => 'neo_paylater_leads_calls_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_CALLS_FROM_NEO_PAYLATER_LEADS_TITLE',
  'save' => true,
  'id_name' => 'neo_paylater_leads_callsneo_paylater_leads_ida',
  'link' => 'neo_paylater_leads_calls',
  'table' => 'neo_paylater_leads',
  'module' => 'Neo_Paylater_Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Call"]["fields"]["neo_paylater_leads_callsneo_paylater_leads_ida"] = array (
  'name' => 'neo_paylater_leads_callsneo_paylater_leads_ida',
  'type' => 'link',
  'relationship' => 'neo_paylater_leads_calls',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_PAYLATER_LEADS_CALLS_FROM_CALLS_TITLE',
);
