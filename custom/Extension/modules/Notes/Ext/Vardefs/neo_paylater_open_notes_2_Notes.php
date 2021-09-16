<?php
// created: 2018-12-20 10:37:45
$dictionary["Note"]["fields"]["neo_paylater_open_notes_2"] = array (
  'name' => 'neo_paylater_open_notes_2',
  'type' => 'link',
  'relationship' => 'neo_paylater_open_notes_2',
  'source' => 'non-db',
  'module' => 'neo_Paylater_Open',
  'bean_name' => 'neo_Paylater_Open',
  'vname' => 'LBL_NEO_PAYLATER_OPEN_NOTES_2_FROM_NEO_PAYLATER_OPEN_TITLE',
  'id_name' => 'neo_paylater_open_notes_2neo_paylater_open_ida',
);
$dictionary["Note"]["fields"]["neo_paylater_open_notes_2_name"] = array (
  'name' => 'neo_paylater_open_notes_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_PAYLATER_OPEN_NOTES_2_FROM_NEO_PAYLATER_OPEN_TITLE',
  'save' => true,
  'id_name' => 'neo_paylater_open_notes_2neo_paylater_open_ida',
  'link' => 'neo_paylater_open_notes_2',
  'table' => 'neo_paylater_open',
  'module' => 'neo_Paylater_Open',
  'rname' => 'name',
);
$dictionary["Note"]["fields"]["neo_paylater_open_notes_2neo_paylater_open_ida"] = array (
  'name' => 'neo_paylater_open_notes_2neo_paylater_open_ida',
  'type' => 'link',
  'relationship' => 'neo_paylater_open_notes_2',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_PAYLATER_OPEN_NOTES_2_FROM_NOTES_TITLE',
);
