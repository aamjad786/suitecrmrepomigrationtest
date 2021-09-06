<?php
// created: 2018-05-05 16:50:11
$dictionary["neo_customers_notes"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'neo_customers_notes' => 
    array (
      'lhs_module' => 'Neo_Customers',
      'lhs_table' => 'neo_customers',
      'lhs_key' => 'id',
      'rhs_module' => 'Notes',
      'rhs_table' => 'notes',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'neo_customers_notes_c',
      'join_key_lhs' => 'neo_customers_notesneo_customers_ida',
      'join_key_rhs' => 'neo_customers_notesnotes_idb',
    ),
  ),
  'table' => 'neo_customers_notes_c',
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
      'name' => 'neo_customers_notesneo_customers_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'neo_customers_notesnotes_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'neo_customers_notesspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'neo_customers_notes_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'neo_customers_notesneo_customers_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'neo_customers_notes_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'neo_customers_notesnotes_idb',
      ),
    ),
  ),
);