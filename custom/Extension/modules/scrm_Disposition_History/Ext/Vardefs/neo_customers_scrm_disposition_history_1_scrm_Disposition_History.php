<?php
// created: 2018-05-17 17:09:57
$dictionary["scrm_Disposition_History"]["fields"]["neo_customers_scrm_disposition_history_1"] = array (
  'name' => 'neo_customers_scrm_disposition_history_1',
  'type' => 'link',
  'relationship' => 'neo_customers_scrm_disposition_history_1',
  'source' => 'non-db',
  'module' => 'Neo_Customers',
  'bean_name' => 'Neo_Customers',
  'vname' => 'LBL_NEO_CUSTOMERS_SCRM_DISPOSITION_HISTORY_1_FROM_NEO_CUSTOMERS_TITLE',
  'id_name' => 'neo_customers_scrm_disposition_history_1neo_customers_ida',
);
$dictionary["scrm_Disposition_History"]["fields"]["neo_customers_scrm_disposition_history_1_name"] = array (
  'name' => 'neo_customers_scrm_disposition_history_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NEO_CUSTOMERS_SCRM_DISPOSITION_HISTORY_1_FROM_NEO_CUSTOMERS_TITLE',
  'save' => true,
  'id_name' => 'neo_customers_scrm_disposition_history_1neo_customers_ida',
  'link' => 'neo_customers_scrm_disposition_history_1',
  'table' => 'neo_customers',
  'module' => 'Neo_Customers',
  'rname' => 'name',
);
$dictionary["scrm_Disposition_History"]["fields"]["neo_customers_scrm_disposition_history_1neo_customers_ida"] = array (
  'name' => 'neo_customers_scrm_disposition_history_1neo_customers_ida',
  'type' => 'link',
  'relationship' => 'neo_customers_scrm_disposition_history_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_NEO_CUSTOMERS_SCRM_DISPOSITION_HISTORY_1_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
);
