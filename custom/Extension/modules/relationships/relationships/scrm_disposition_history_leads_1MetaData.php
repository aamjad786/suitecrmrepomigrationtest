<?php
// created: 2021-11-18 05:45:54
$dictionary["scrm_disposition_history_leads_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'scrm_disposition_history_leads_1' => 
    array (
      'lhs_module' => 'scrm_Disposition_History',
      'lhs_table' => 'scrm_disposition_history',
      'lhs_key' => 'id',
      'rhs_module' => 'Leads',
      'rhs_table' => 'leads',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'scrm_disposition_history_leads_1_c',
      'join_key_lhs' => 'scrm_disposition_history_leads_1scrm_disposition_history_ida',
      'join_key_rhs' => 'scrm_disposition_history_leads_1leads_idb',
    ),
  ),
  'table' => 'scrm_disposition_history_leads_1_c',
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
      'name' => 'scrm_disposition_history_leads_1scrm_disposition_history_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'scrm_disposition_history_leads_1leads_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'scrm_disposition_history_leads_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'scrm_disposition_history_leads_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'scrm_disposition_history_leads_1scrm_disposition_history_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'scrm_disposition_history_leads_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'scrm_disposition_history_leads_1leads_idb',
      ),
    ),
  ),
);