<?php
// created: 2018-12-20 10:37:22
$dictionary["neo_paylater_open_calls_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'neo_paylater_open_calls_1' => 
    array (
      'lhs_module' => 'neo_Paylater_Open',
      'lhs_table' => 'neo_paylater_open',
      'lhs_key' => 'id',
      'rhs_module' => 'Calls',
      'rhs_table' => 'calls',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'neo_paylater_open_calls_1_c',
      'join_key_lhs' => 'neo_paylater_open_calls_1neo_paylater_open_ida',
      'join_key_rhs' => 'neo_paylater_open_calls_1calls_idb',
    ),
  ),
  'table' => 'neo_paylater_open_calls_1_c',
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
      'name' => 'neo_paylater_open_calls_1neo_paylater_open_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'neo_paylater_open_calls_1calls_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'neo_paylater_open_calls_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'neo_paylater_open_calls_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'neo_paylater_open_calls_1neo_paylater_open_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'neo_paylater_open_calls_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'neo_paylater_open_calls_1calls_idb',
      ),
    ),
  ),
);