<?php
// created: 2018-05-17 16:51:14
$dictionary["Note"]["fields"]["neo_renewal_cold_leads_notes_1"] = array (
  'name' => 'neo_renewal_cold_leads_notes_1',
  'type' => 'link',
  'relationship' => 'neo_renewal_cold_leads_notes_1',
  'source' => 'non-db',
  'module' => 'Neo_renewal_cold_leads',
  'bean_name' => 'Neo_renewal_cold_leads',
  'vname' => 'LBL_NEO_RENEWAL_COLD_LEADS_NOTES_1_FROM_NEO_RENEWAL_COLD_LEADS_TITLE',
  'id_name' => 'neo_renewal_cold_leads_notes_1neo_renewal_cold_leads_ida',
);
$dictionary["Note"]["fields"]["neo_renewal_cold_leads_notes_1_name"] = array (
  'name' => 'neo_renewal_cold_leads_notes_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_RENEWAL_COLD_LEADS_NOTES_1_FROM_NEO_RENEWAL_COLD_LEADS_TITLE',
  'save' => true,
  'id_name' => 'neo_renewal_cold_leads_notes_1neo_renewal_cold_leads_ida',
  'link' => 'neo_renewal_cold_leads_notes_1',
  'table' => 'neo_renewal_cold_leads',
  'module' => 'Neo_renewal_cold_leads',
  'rname' => 'name',
);
$dictionary["Note"]["fields"]["neo_renewal_cold_leads_notes_1neo_renewal_cold_leads_ida"] = array (
  'name' => 'neo_renewal_cold_leads_notes_1neo_renewal_cold_leads_ida',
  'type' => 'link',
  'relationship' => 'neo_renewal_cold_leads_notes_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_RENEWAL_COLD_LEADS_NOTES_1_FROM_NOTES_TITLE',
);
