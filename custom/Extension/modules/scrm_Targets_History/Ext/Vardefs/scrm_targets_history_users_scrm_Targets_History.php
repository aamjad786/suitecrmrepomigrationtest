<?php
// created: 2016-12-04 22:37:14
$dictionary["scrm_Targets_History"]["fields"]["scrm_targets_history_users"] = array (
  'name' => 'scrm_targets_history_users',
  'type' => 'link',
  'relationship' => 'scrm_targets_history_users',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'vname' => 'LBL_SCRM_TARGETS_HISTORY_USERS_FROM_USERS_TITLE',
  'id_name' => 'scrm_targets_history_usersusers_ida',
);
$dictionary["scrm_Targets_History"]["fields"]["scrm_targets_history_users_name"] = array (
  'name' => 'scrm_targets_history_users_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SCRM_TARGETS_HISTORY_USERS_FROM_USERS_TITLE',
  'save' => true,
  'id_name' => 'scrm_targets_history_usersusers_ida',
  'link' => 'scrm_targets_history_users',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'name',
);
$dictionary["scrm_Targets_History"]["fields"]["scrm_targets_history_usersusers_ida"] = array (
  'name' => 'scrm_targets_history_usersusers_ida',
  'type' => 'link',
  'relationship' => 'scrm_targets_history_users',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SCRM_TARGETS_HISTORY_USERS_FROM_SCRM_TARGETS_HISTORY_TITLE',
);
