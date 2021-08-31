<?php
// created: 2016-12-04 22:37:14
$dictionary["scrm_targets_history_users"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'scrm_targets_history_users' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'scrm_Targets_History',
      'rhs_table' => 'scrm_targets_history',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'scrm_targets_history_users_c',
      'join_key_lhs' => 'scrm_targets_history_usersusers_ida',
      'join_key_rhs' => 'scrm_targets_history_usersscrm_targets_history_idb',
    ),
  ),
  'table' => 'scrm_targets_history_users_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'scrm_targets_history_usersusers_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'scrm_targets_history_usersscrm_targets_history_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'scrm_targets_history_usersspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'scrm_targets_history_users_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'scrm_targets_history_usersusers_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'scrm_targets_history_users_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'scrm_targets_history_usersscrm_targets_history_idb',
      ),
    ),
  ),
);