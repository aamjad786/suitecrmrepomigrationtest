<?php
// created: 2018-03-09 02:11:01
$searchFields['Prospects'] = array (
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
  'do_not_call' => 
  array (
    'query_type' => 'default',
    'operator' => '=',
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
  'sms_campaign_run' => 
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'subquery' => 'select id from prospects where (NOT {0} AND id not in (select sms_sms_prospects_1prospects_idb from sms_sms_prospects_1_c)) or 
    ({0} and id  in (select sms_sms_prospects_1prospects_idb from sms_sms_prospects_1_c))',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
  'email' => 
  array (
    'query_type' => 'default',
    'operator' => 'subquery',
    'subquery' => 'SELECT eabr.bean_id FROM email_addr_bean_rel eabr JOIN email_addresses ea ON (ea.id = eabr.email_address_id) WHERE eabr.deleted=0 AND ea.invalid_email=0 AND ea.email_address LIKE',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
  'assistant' => 
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
  'range_year_established' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_year_established' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_year_established' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_ratings' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_ratings' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_ratings' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_appointment_time' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_appointment_time' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_appointment_time' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_avg_sales' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_avg_sales' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_avg_sales' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_num_yr_in_business' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_num_yr_in_business' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_num_yr_in_business' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_star_rating' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_star_rating' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_star_rating' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_appointment_time_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_appointment_time_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_appointment_time_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_avg_sales_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_avg_sales_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_avg_sales_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_num_yr_in_business_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_num_yr_in_business_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_num_yr_in_business_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_ratings_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_ratings_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_ratings_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_star_rating_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_star_rating_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_star_rating_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_year_established_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_year_established_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_year_established_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_dq_score' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_dq_score' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_dq_score' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_ps_score' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_ps_score' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_ps_score' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_ps_score_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_ps_score_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_ps_score_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
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
  'range_target_date_assigned_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_target_date_assigned_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_target_date_assigned_c' => 
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
  'range_dq_score_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_dq_score_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_dq_score_c' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
);