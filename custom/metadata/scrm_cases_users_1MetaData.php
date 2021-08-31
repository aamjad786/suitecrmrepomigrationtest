<?php
// created: 2018-06-26 17:19:07
$dictionary["scrm_cases_users_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'scrm_cases_users_1' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'scrm_Cases',
      'rhs_table' => 'scrm_cases',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'scrm_cases_users_1_c',
      'join_key_lhs' => 'scrm_cases_users_1users_ida',
      'join_key_rhs' => 'scrm_cases_users_1scrm_cases_idb',
    ),
  ),
  'table' => 'scrm_cases_users_1_c',
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
      'name' => 'scrm_cases_users_1users_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'scrm_cases_users_1scrm_cases_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'scrm_cases_users_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'scrm_cases_users_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'scrm_cases_users_1users_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'scrm_cases_users_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'scrm_cases_users_1scrm_cases_idb',
      ),
    ),
  ),
);