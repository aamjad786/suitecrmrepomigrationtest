<?php
// created: 2018-02-15 14:35:25
$dictionary["neo_paylater_leads_meetings"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'neo_paylater_leads_meetings' => 
    array (
      'lhs_module' => 'Neo_Paylater_Leads',
      'lhs_table' => 'neo_paylater_leads',
      'lhs_key' => 'id',
      'rhs_module' => 'Meetings',
      'rhs_table' => 'meetings',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'neo_paylater_leads_meetings_c',
      'join_key_lhs' => 'neo_paylater_leads_meetingsneo_paylater_leads_ida',
      'join_key_rhs' => 'neo_paylater_leads_meetingsmeetings_idb',
    ),
  ),
  'table' => 'neo_paylater_leads_meetings_c',
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
      'name' => 'neo_paylater_leads_meetingsneo_paylater_leads_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'neo_paylater_leads_meetingsmeetings_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'neo_paylater_leads_meetingsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'neo_paylater_leads_meetings_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'neo_paylater_leads_meetingsneo_paylater_leads_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'neo_paylater_leads_meetings_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'neo_paylater_leads_meetingsmeetings_idb',
      ),
    ),
  ),
);