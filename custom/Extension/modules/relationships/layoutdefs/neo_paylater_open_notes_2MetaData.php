<?php
// created: 2018-12-20 10:37:45
$dictionary["neo_paylater_open_notes_2"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'neo_paylater_open_notes_2' => 
    array (
      'lhs_module' => 'neo_Paylater_Open',
      'lhs_table' => 'neo_paylater_open',
      'lhs_key' => 'id',
      'rhs_module' => 'Notes',
      'rhs_table' => 'notes',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'neo_paylater_open_notes_2_c',
      'join_key_lhs' => 'neo_paylater_open_notes_2neo_paylater_open_ida',
      'join_key_rhs' => 'neo_paylater_open_notes_2notes_idb',
    ),
  ),
  'table' => 'neo_paylater_open_notes_2_c',
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
      'name' => 'neo_paylater_open_notes_2neo_paylater_open_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'neo_paylater_open_notes_2notes_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'neo_paylater_open_notes_2spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'neo_paylater_open_notes_2_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'neo_paylater_open_notes_2neo_paylater_open_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'neo_paylater_open_notes_2_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'neo_paylater_open_notes_2notes_idb',
      ),
    ),
  ),
);