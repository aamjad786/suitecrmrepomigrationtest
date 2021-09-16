<?php
// created: 2018-12-20 10:37:22
$dictionary["Call"]["fields"]["neo_paylater_open_calls_1"] = array (
  'name' => 'neo_paylater_open_calls_1',
  'type' => 'link',
  'relationship' => 'neo_paylater_open_calls_1',
  'source' => 'non-db',
  'module' => 'neo_Paylater_Open',
  'bean_name' => 'neo_Paylater_Open',
  'vname' => 'LBL_NEO_PAYLATER_OPEN_CALLS_1_FROM_NEO_PAYLATER_OPEN_TITLE',
  'id_name' => 'neo_paylater_open_calls_1neo_paylater_open_ida',
);
$dictionary["Call"]["fields"]["neo_paylater_open_calls_1_name"] = array (
  'name' => 'neo_paylater_open_calls_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_PAYLATER_OPEN_CALLS_1_FROM_NEO_PAYLATER_OPEN_TITLE',
  'save' => true,
  'id_name' => 'neo_paylater_open_calls_1neo_paylater_open_ida',
  'link' => 'neo_paylater_open_calls_1',
  'table' => 'neo_paylater_open',
  'module' => 'neo_Paylater_Open',
  'rname' => 'name',
);
$dictionary["Call"]["fields"]["neo_paylater_open_calls_1neo_paylater_open_ida"] = array (
  'name' => 'neo_paylater_open_calls_1neo_paylater_open_ida',
  'type' => 'link',
  'relationship' => 'neo_paylater_open_calls_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_PAYLATER_OPEN_CALLS_1_FROM_CALLS_TITLE',
);
