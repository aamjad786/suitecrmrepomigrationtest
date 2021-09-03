<?php
// created: 2019-04-29 16:10:47
$dictionary["Call"]["fields"]["cases_calls_1"] = array (
  'name' => 'cases_calls_1',
  'type' => 'link',
  'relationship' => 'cases_calls_1',
  'source' => 'non-db',
  'module' => 'Cases',
  'bean_name' => 'Case',
  'vname' => 'LBL_CASES_CALLS_1_FROM_CASES_TITLE',
  'id_name' => 'cases_calls_1cases_ida',
);
$dictionary["Call"]["fields"]["cases_calls_1_name"] = array (
  'name' => 'cases_calls_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CASES_CALLS_1_FROM_CASES_TITLE',
  'save' => true,
  'id_name' => 'cases_calls_1cases_ida',
  'link' => 'cases_calls_1',
  'table' => 'cases',
  'module' => 'Cases',
  'rname' => 'name',
);
$dictionary["Call"]["fields"]["cases_calls_1cases_ida"] = array (
  'name' => 'cases_calls_1cases_ida',
  'type' => 'link',
  'relationship' => 'cases_calls_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CASES_CALLS_1_FROM_CALLS_TITLE',
);
