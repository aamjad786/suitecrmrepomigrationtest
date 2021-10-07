<?php
// created: 2018-05-07 18:05:26
$dictionary["neo_paylater_leads_activities_1_calls"] = array (
  'relationships' => 
  array (
    'neo_paylater_leads_activities_1_calls' => 
    array (
      'lhs_module' => 'Neo_Paylater_Leads',
      'lhs_table' => 'neo_paylater_leads',
      'lhs_key' => 'id',
      'rhs_module' => 'Calls',
      'rhs_table' => 'calls',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
      'relationship_role_column' => 'parent_type',
      'relationship_role_column_value' => 'Neo_Paylater_Leads',
    ),
  ),
  'fields' => '',
  'indices' => '',
  'table' => '',
);