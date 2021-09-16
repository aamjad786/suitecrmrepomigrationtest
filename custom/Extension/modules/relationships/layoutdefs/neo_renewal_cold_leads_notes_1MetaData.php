<?php
// created: 2018-05-17 16:51:14
$dictionary["neo_renewal_cold_leads_notes_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'neo_renewal_cold_leads_notes_1' => 
    array (
      'lhs_module' => 'Neo_renewal_cold_leads',
      'lhs_table' => 'neo_renewal_cold_leads',
      'lhs_key' => 'id',
      'rhs_module' => 'Notes',
      'rhs_table' => 'notes',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'neo_renewal_cold_leads_notes_1_c',
      'join_key_lhs' => 'neo_renewal_cold_leads_notes_1neo_renewal_cold_leads_ida',
      'join_key_rhs' => 'neo_renewal_cold_leads_notes_1notes_idb',
    ),
  ),
  'table' => 'neo_renewal_cold_leads_notes_1_c',
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
      'name' => 'neo_renewal_cold_leads_notes_1neo_renewal_cold_leads_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'neo_renewal_cold_leads_notes_1notes_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'neo_renewal_cold_leads_notes_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'neo_renewal_cold_leads_notes_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'neo_renewal_cold_leads_notes_1neo_renewal_cold_leads_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'neo_renewal_cold_leads_notes_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'neo_renewal_cold_leads_notes_1notes_idb',
      ),
    ),
  ),
);