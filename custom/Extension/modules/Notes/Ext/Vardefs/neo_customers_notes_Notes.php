<?php
// created: 2018-05-05 16:50:11
$dictionary["Note"]["fields"]["neo_customers_notes"] = array (
  'name' => 'neo_customers_notes',
  'type' => 'link',
  'relationship' => 'neo_customers_notes',
  'source' => 'non-db',
  'module' => 'Neo_Customers',
  'bean_name' => 'Neo_Customers',
  'vname' => 'LBL_NEO_CUSTOMERS_NOTES_FROM_NEO_CUSTOMERS_TITLE',
  'id_name' => 'neo_customers_notesneo_customers_ida',
);
$dictionary["Note"]["fields"]["neo_customers_notes_name"] = array (
  'name' => 'neo_customers_notes_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_CUSTOMERS_NOTES_FROM_NEO_CUSTOMERS_TITLE',
  'save' => true,
  'id_name' => 'neo_customers_notesneo_customers_ida',
  'link' => 'neo_customers_notes',
  'table' => 'neo_customers',
  'module' => 'Neo_Customers',
  'rname' => 'name',
);
$dictionary["Note"]["fields"]["neo_customers_notesneo_customers_ida"] = array (
  'name' => 'neo_customers_notesneo_customers_ida',
  'type' => 'link',
  'relationship' => 'neo_customers_notes',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_CUSTOMERS_NOTES_FROM_NOTES_TITLE',
);
