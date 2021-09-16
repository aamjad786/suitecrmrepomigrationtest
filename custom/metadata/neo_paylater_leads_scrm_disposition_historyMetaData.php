<?php
// created: 2018-02-15 14:35:25
$dictionary["neo_paylater_leads_scrm_disposition_history"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'neo_paylater_leads_scrm_disposition_history' => 
    array (
      'lhs_module' => 'Neo_Paylater_Leads',
      'lhs_table' => 'neo_paylater_leads',
      'lhs_key' => 'id',
      'rhs_module' => 'scrm_Disposition_History',
      'rhs_table' => 'scrm_disposition_history',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'neo_paylater_leads_scrm_disposition_history_c',
      'join_key_lhs' => 'neo_paylatece6r_leads_ida',
      'join_key_rhs' => 'neo_paylat54d7history_idb',
    ),
  ),
  'table' => 'neo_paylater_leads_scrm_disposition_history_c',
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
      'name' => 'neo_paylatece6r_leads_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'neo_paylat54d7history_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'neo_paylater_leads_scrm_disposition_historyspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'neo_paylater_leads_scrm_disposition_history_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'neo_paylatece6r_leads_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'neo_paylater_leads_scrm_disposition_history_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'neo_paylat54d7history_idb',
      ),
    ),
  ),
);