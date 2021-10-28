<?php
// created: 2018-04-24 12:59:31
$dictionary["Prospect"]["fields"]["prospects_leads_1"] = array (
  'name' => 'prospects_leads_1',
  'type' => 'link',
  'relationship' => 'prospects_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_PROSPECTS_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'prospects_leads_1leads_idb',
);
$dictionary["Prospect"]["fields"]["prospects_leads_1_name"] = array (
  'name' => 'prospects_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PROSPECTS_LEADS_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'prospects_leads_1leads_idb',
  'link' => 'prospects_leads_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Prospect"]["fields"]["prospects_leads_1leads_idb"] = array (
  'name' => 'prospects_leads_1leads_idb',
  'type' => 'link',
  'relationship' => 'prospects_leads_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_PROSPECTS_LEADS_1_FROM_LEADS_TITLE',
);
