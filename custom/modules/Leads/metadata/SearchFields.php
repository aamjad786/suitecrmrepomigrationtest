<?php
// created: 2021-09-14 17:59:08
$searchFields['Leads'] = array (
  'first_name' => 
  array (
    'query_type' => 'default',
  ),
  'last_name' => 
  array (
    'query_type' => 'default',
  ),
  'search_name' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
    ),
    'force_unifiedsearch' => true,
  ),
  'account_name' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'leads.account_name',
    ),
  ),
  'lead_source' => 
  array (
    'query_type' => 'default',
    'operator' => '=',
    'options' => 'lead_source_dom',
    'template_var' => 'LEAD_SOURCE_OPTIONS',
  ),
  'do_not_call' => 
  array (
    'query_type' => 'default',
    'operator' => '=',
    'input_type' => 'checkbox',
  ),
  'phone' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'phone_mobile',
      1 => 'phone_work',
      2 => 'phone_other',
      3 => 'phone_fax',
      4 => 'phone_home',
    ),
  ),
  'email' => 
  array (
    'query_type' => 'default',
    'operator' => 'subquery',
    'subquery' => 'SELECT eabr.bean_id FROM email_addr_bean_rel eabr JOIN email_addresses ea ON (ea.id = eabr.email_address_id) WHERE eabr.deleted=0 AND ea.email_address LIKE',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
  'favorites_only' => 
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'subquery' => 'SELECT favorites.parent_id FROM favorites
			                    WHERE favorites.deleted = 0
			                        and favorites.parent_type = \'Leads\'
			                        and favorites.assigned_user_id = \'1\') OR NOT ({0}',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
  'assistant' => 
  array (
    'query_type' => 'default',
  ),
  'website' => 
  array (
    'query_type' => 'default',
  ),
  'address_street' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'primary_address_street',
      1 => 'alt_address_street',
    ),
  ),
  'address_city' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'primary_address_city',
      1 => 'alt_address_city',
    ),
  ),
  'address_state' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'primary_address_state',
      1 => 'alt_address_state',
    ),
  ),
  'address_postalcode' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'primary_address_postalcode',
      1 => 'alt_address_postalcode',
    ),
  ),
  'address_country' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'primary_address_country',
      1 => 'alt_address_country',
    ),
  ),
  'current_user_only' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'assigned_user_id',
    ),
    'my_items' => true,
    'vname' => 'LBL_CURRENT_USER_FILTER',
    'type' => 'bool',
  ),
  'assigned_user_id' => 
  array (
    'query_type' => 'default',
  ),
  'status' => 
  array (
    'query_type' => 'default',
    'options' => 'lead_status_dom',
    'template_var' => 'STATUS_OPTIONS',
  ),
  'open_only' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'status',
    ),
    'operator' => 'not in',
    'closed_values' => 
    array (
      0 => 'Dead',
      1 => 'Recycled',
      2 => 'Converted',
    ),
    'type' => 'bool',
  ),
  'range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_call_date' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_call_date' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_call_date' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_record_uploaded' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_record_uploaded' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_record_uploaded' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_call_back' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_call_back' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_call_back' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_loan_amount' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_loan_amount' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_loan_amount' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_num' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_num' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_num' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_call_date_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_call_date_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_call_date_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_record_uploaded_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_record_uploaded_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_record_uploaded_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_call_back_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_call_back_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_call_back_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_num_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_num_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_num_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_loan_amount_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_loan_amount_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_loan_amount_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'opportunity_name' => 
  array (
    'query_type' => 'default',
  ),
  'phone_mobile' => 
  array (
    'query_type' => 'default',
  ),
  'opportunity_amount' => 
  array (
    'query_type' => 'default',
  ),
  'range_loan_amount_required_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_loan_amount_required_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_loan_amount_required_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'date_modified' => 
  array (
    'query_type' => 'default',
  ),
  'range_call_back_date_time_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_call_back_date_time_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_call_back_date_time_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'dwh_sync_c' => 
  array (
    'query_type' => 'default',
  ),
  'original_app_id' => 
  array (
    'query_type' => 'default',
  ),
  'description' => 
  array (
    'query_type' => 'default',
  ),
  'product_type' => 
  array (
    'query_type' => 'default',
  ),
  'bank_account_count' => 
  array (
    'query_type' => 'default',
  ),
  'range_pushed_lead_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_pushed_lead_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_pushed_lead_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  
);