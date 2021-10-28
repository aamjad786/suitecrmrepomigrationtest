<?php
// created: 2018-04-24 12:59:31
$dictionary["Lead"]["fields"]["prospects_leads_1"] = array (
  'name' => 'prospects_leads_1',
  'type' => 'link',
  'relationship' => 'prospects_leads_1',
  'source' => 'non-db',
  'module' => 'Prospects',
  'bean_name' => 'Prospect',
  'vname' => 'LBL_PROSPECTS_LEADS_1_FROM_PROSPECTS_TITLE',
  'id_name' => 'prospects_leads_1prospects_ida',
);
$dictionary["Lead"]["fields"]["prospects_leads_1_name"] = array (
  'name' => 'prospects_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PROSPECTS_LEADS_1_FROM_PROSPECTS_TITLE',
  'save' => true,
  'id_name' => 'prospects_leads_1prospects_ida',
  'link' => 'prospects_leads_1',
  'table' => 'prospects',
  'module' => 'Prospects',
  'rname' => 'account_name',
);
$dictionary["Lead"]["fields"]["prospects_leads_1prospects_ida"] = array (
  'name' => 'prospects_leads_1prospects_ida',
  'type' => 'link',
  'relationship' => 'prospects_leads_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_PROSPECTS_LEADS_1_FROM_PROSPECTS_TITLE',
);
