<?php
// created: 2016-09-15 14:53:46
$dictionary["scrm_disposition_history_leads"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'scrm_disposition_history_leads' => 
    array (
      'lhs_module' => 'scrm_Disposition_History',
      'lhs_table' => 'scrm_disposition_history',
      'lhs_key' => 'id',
      'rhs_module' => 'Leads',
      'rhs_table' => 'leads',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'scrm_disposition_history_leads_c',
      'join_key_lhs' => 'scrm_disposition_history_leadsscrm_disposition_history_ida',
      'join_key_rhs' => 'scrm_disposition_history_leadsleads_idb',
    ),
  ),
  'table' => 'scrm_disposition_history_leads_c',
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
      'name' => 'scrm_disposition_history_leadsscrm_disposition_history_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'scrm_disposition_history_leadsleads_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'scrm_disposition_history_leadsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'scrm_disposition_history_leads_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'scrm_disposition_history_leadsscrm_disposition_history_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'scrm_disposition_history_leads_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'scrm_disposition_history_leadsleads_idb',
      ),
    ),
  ),
);