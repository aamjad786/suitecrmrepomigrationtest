<?php
// created: 2016-07-20 13:39:21
$dictionary["Prospect"]["fields"]["net_missed_calls_prospects"] = array (
  'name' => 'net_missed_calls_prospects',
  'type' => 'link',
  'relationship' => 'net_missed_calls_prospects',
  'source' => 'non-db',
  'module' => 'net_missed_calls',
  'bean_name' => 'net_missed_calls',
  'vname' => 'LBL_NET_MISSED_CALLS_PROSPECTS_FROM_NET_MISSED_CALLS_TITLE',
  'id_name' => 'net_missed_calls_prospectsnet_missed_calls_ida',
);
$dictionary["Prospect"]["fields"]["net_missed_calls_prospects_name"] = array (
  'name' => 'net_missed_calls_prospects_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NET_MISSED_CALLS_PROSPECTS_FROM_NET_MISSED_CALLS_TITLE',
  'save' => true,
  'id_name' => 'net_missed_calls_prospectsnet_missed_calls_ida',
  'link' => 'net_missed_calls_prospects',
  'table' => 'net_missed_calls',
  'module' => 'net_missed_calls',
  'rname' => 'name',
);
$dictionary["Prospect"]["fields"]["net_missed_calls_prospectsnet_missed_calls_ida"] = array (
  'name' => 'net_missed_calls_prospectsnet_missed_calls_ida',
  'type' => 'link',
  'relationship' => 'net_missed_calls_prospects',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_NET_MISSED_CALLS_PROSPECTS_FROM_NET_MISSED_CALLS_TITLE',
);
