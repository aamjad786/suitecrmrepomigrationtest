<?php
// created: 2018-05-07 17:50:50
$dictionary["neo_renewal_cold_leads_activities_1_notes"] = array (
  'relationships' => 
  array (
    'neo_renewal_cold_leads_activities_1_notes' => 
    array (
      'lhs_module' => 'Neo_renewal_cold_leads',
      'lhs_table' => 'neo_renewal_cold_leads',
      'lhs_key' => 'id',
      'rhs_module' => 'Notes',
      'rhs_table' => 'notes',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
      'relationship_role_column' => 'parent_type',
      'relationship_role_column_value' => 'Neo_renewal_cold_leads',
    ),
  ),
  'fields' => '',
  'indices' => '',
  'table' => '',
);