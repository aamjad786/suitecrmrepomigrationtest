<?php
// created: 2018-06-26 17:19:07
$dictionary["scrm_Cases"]["fields"]["scrm_cases_users_2"] = array (
  'name' => 'scrm_cases_users_2',
  'type' => 'link',
  'relationship' => 'scrm_cases_users_2',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'vname' => 'LBL_SCRM_CASES_USERS_2_FROM_USERS_TITLE',
  'id_name' => 'scrm_cases_users_2users_ida',
);
$dictionary["scrm_Cases"]["fields"]["scrm_cases_users_2_name"] = array (
  'name' => 'scrm_cases_users_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SCRM_CASES_USERS_2_FROM_USERS_TITLE',
  'save' => true,
  'id_name' => 'scrm_cases_users_2users_ida',
  'link' => 'scrm_cases_users_2',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'name',
);
$dictionary["scrm_Cases"]["fields"]["scrm_cases_users_2users_ida"] = array (
  'name' => 'scrm_cases_users_2users_ida',
  'type' => 'link',
  'relationship' => 'scrm_cases_users_2',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SCRM_CASES_USERS_2_FROM_SCRM_CASES_TITLE',
);
