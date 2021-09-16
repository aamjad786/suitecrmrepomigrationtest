<?php
// created: 2018-05-07 18:03:24
$dictionary["neo_renewal_hot_leads_activities_1_notes"] = array (
  'relationships' => 
  array (
    'neo_renewal_hot_leads_activities_1_notes' => 
    array (
      'lhs_module' => 'Neo_renewal_hot_leads',
      'lhs_table' => 'neo_renewal_hot_leads',
      'lhs_key' => 'id',
      'rhs_module' => 'Notes',
      'rhs_table' => 'notes',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
      'relationship_role_column' => 'parent_type',
      'relationship_role_column_value' => 'Neo_renewal_hot_leads',
    ),
  ),
  'fields' => '',
  'indices' => '',
  'table' => '',
);