<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2016-07-20 13:39:21
$dictionary["Prospect"]["fields"]["net_missed_calls_prospects"] = array (
  'name' => 'net_missed_calls_prospects',
  'type' => 'link',
  'relationship' => 'net_missed_calls_prospects',
  'source' => 'non-db',
  'module' => 'net_missed_calls',
  'bean_name' => 'net_missed_calls',
  'vname' => 'LBL_NET_MISSED_CALLS_PROSPECTS_FROM_NET_MISSED_CALLS_TITLE',
  'id_name' => 'net_missed_calls_prospectsnet_missed_calls_ida',
);
$dictionary["Prospect"]["fields"]["net_missed_calls_prospects_name"] = array (
  'name' => 'net_missed_calls_prospects_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_NET_MISSED_CALLS_PROSPECTS_FROM_NET_MISSED_CALLS_TITLE',
  'save' => true,
  'id_name' => 'net_missed_calls_prospectsnet_missed_calls_ida',
  'link' => 'net_missed_calls_prospects',
  'table' => 'net_missed_calls',
  'module' => 'net_missed_calls',
  'rname' => 'name',
);
$dictionary["Prospect"]["fields"]["net_missed_calls_prospectsnet_missed_calls_ida"] = array (
  'name' => 'net_missed_calls_prospectsnet_missed_calls_ida',
  'type' => 'link',
  'relationship' => 'net_missed_calls_prospects',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_NET_MISSED_CALLS_PROSPECTS_FROM_NET_MISSED_CALLS_TITLE',
);


// created: 2016-07-21 15:13:59
$dictionary["Prospect"]["fields"]["nsms_sms_campaign_prospects"] = array (
  'name' => 'nsms_sms_campaign_prospects',
  'type' => 'link',
  'relationship' => 'nsms_sms_campaign_prospects',
  'source' => 'non-db',
  'module' => 'nsms_sms_campaign',
  'bean_name' => 'nsms_sms_campaign',
  'vname' => 'LBL_NSMS_SMS_CAMPAIGN_PROSPECTS_FROM_NSMS_SMS_CAMPAIGN_TITLE',
);


// created: 2018-04-24 12:59:31
$dictionary["Prospect"]["fields"]["prospects_leads_1"] = array (
  'name' => 'prospects_leads_1',
  'type' => 'link',
  'relationship' => 'prospects_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_PROSPECTS_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'prospects_leads_1leads_idb',
);
$dictionary["Prospect"]["fields"]["prospects_leads_1_name"] = array (
  'name' => 'prospects_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PROSPECTS_LEADS_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'prospects_leads_1leads_idb',
  'link' => 'prospects_leads_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Prospect"]["fields"]["prospects_leads_1leads_idb"] = array (
  'name' => 'prospects_leads_1leads_idb',
  'type' => 'link',
  'relationship' => 'prospects_leads_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_PROSPECTS_LEADS_1_FROM_LEADS_TITLE',
);


// created: 2016-09-16 16:46:41
$dictionary["Prospect"]["fields"]["prospects_scrm_disposition_history_1"] = array (
  'name' => 'prospects_scrm_disposition_history_1',
  'type' => 'link',
  'relationship' => 'prospects_scrm_disposition_history_1',
  'source' => 'non-db',
  'module' => 'scrm_Disposition_History',
  'bean_name' => 'scrm_Disposition_History',
  'side' => 'right',
  'vname' => 'LBL_PROSPECTS_SCRM_DISPOSITION_HISTORY_1_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
);


// created: 2016-08-28 13:25:41
$dictionary["Prospect"]["fields"]["sms_sms_prospects_1"] = array (
  'name' => 'sms_sms_prospects_1',
  'type' => 'link',
  'relationship' => 'sms_sms_prospects_1',
  'source' => 'non-db',
  'module' => 'SMS_SMS',
  'bean_name' => 'SMS_SMS',
  'vname' => 'LBL_SMS_SMS_PROSPECTS_1_FROM_SMS_SMS_TITLE',
);


 // created: 2016-09-30 16:18:34
$dictionary['Prospect']['fields']['acspm_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['acspm_c']['labelValue']='Average card sales per month:';

 

 // created: 2016-09-30 19:21:52
$dictionary['Prospect']['fields']['alt_address_street']['inline_edit']=true;
$dictionary['Prospect']['fields']['alt_address_street']['comments']='Street address for alternate address';
$dictionary['Prospect']['fields']['alt_address_street']['merge_filter']='disabled';

 

 // created: 2016-09-16 18:27:02
$dictionary['Prospect']['fields']['alt_landline_number_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['alt_landline_number_c']['labelValue']='Alternate Landline Number';

 

 // created: 2016-09-16 18:22:12
$dictionary['Prospect']['fields']['alt_mobile_number_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['alt_mobile_number_c']['labelValue']='Alternate Mobile Number';

 

 // created: 2016-12-14 21:38:20
$dictionary['Prospect']['fields']['appointment_time_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['appointment_time_c']['options']='date_range_search_dom';
$dictionary['Prospect']['fields']['appointment_time_c']['labelValue']='appointment time';
$dictionary['Prospect']['fields']['appointment_time_c']['enable_range_search']='1';

 

 // created: 2016-09-16 18:42:45
$dictionary['Prospect']['fields']['attempts_done_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['attempts_done_c']['labelValue']='Attempts done';

 

 // created: 2016-09-30 16:27:30
$dictionary['Prospect']['fields']['average_settlements_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['average_settlements_c']['labelValue']='Average Settlements Per Month:';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['avg_sales_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['avg_sales_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['avg_sales_c']['labelValue']='Avg Sales';
$dictionary['Prospect']['fields']['avg_sales_c']['enable_range_search']='1';

 

 // created: 2016-10-27 12:19:43
$dictionary['Prospect']['fields']['business_ownership_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['business_ownership_c']['labelValue']='Business Ownership';

 

 // created: 2016-09-16 17:55:35
$dictionary['Prospect']['fields']['business_premise_type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['business_premise_type_c']['labelValue']='Business premise type';

 

 // created: 2016-09-16 18:06:16
$dictionary['Prospect']['fields']['business_type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['business_type_c']['labelValue']='Business Type';

 

 // created: 2016-10-27 13:03:23
$dictionary['Prospect']['fields']['business_vintage_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['business_vintage_c']['labelValue']='Business Vintage';

 

 // created: 2016-10-28 15:55:28
$dictionary['Prospect']['fields']['business_vintage_years_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['business_vintage_years_c']['labelValue']='Business Year(Estabilished)';

 

 // created: 2016-09-16 17:42:58
$dictionary['Prospect']['fields']['called_date_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['called_date_c']['labelValue']='Called Date';

 

 // created: 2016-09-16 17:45:33
$dictionary['Prospect']['fields']['called_time_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['called_time_c']['labelValue']='Called Time';

 

 // created: 2016-09-16 18:38:04
$dictionary['Prospect']['fields']['caller_name_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['caller_name_c']['labelValue']='Caller Name';

 

 // created: 2016-09-16 18:43:23
$dictionary['Prospect']['fields']['caller_remark_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['caller_remark_c']['labelValue']='Caller Remark';

 

 // created: 2016-12-29 12:14:41
$dictionary['Prospect']['fields']['call_back_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['call_back_c']['options']='date_range_search_dom';
$dictionary['Prospect']['fields']['call_back_c']['labelValue']='Call-Back Date / Time';
$dictionary['Prospect']['fields']['call_back_c']['enable_range_search']='1';

 

 // created: 2016-09-16 18:30:27
$dictionary['Prospect']['fields']['campaign_code_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['campaign_code_c']['labelValue']='Campaign Code';

 

 // created: 2016-09-16 18:16:39
$dictionary['Prospect']['fields']['cbavy_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['cbavy_c']['labelValue']='Current Business Address Vintage In Years';

 

 // created: 2016-09-16 17:42:21
$dictionary['Prospect']['fields']['check_disposition_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['check_disposition_c']['labelValue']='Check Disposition';

 

 // created: 2016-09-16 18:28:45
$dictionary['Prospect']['fields']['city_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['city_c']['labelValue']='City';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['contact_person_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['contact_person_c']['labelValue']='Contact Person';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['cost_for_2_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['cost_for_2_c']['labelValue']='cost for 2';

 

 // created: 2016-12-04 13:16:54
$dictionary['Prospect']['fields']['country_code_c']['inline_edit']=1;

 

 // created: 2016-09-16 18:35:04
$dictionary['Prospect']['fields']['cravy_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['cravy_c']['labelValue']='Current Residence Address Vintage In Years';

 

 // created: 2016-09-16 18:41:18
$dictionary['Prospect']['fields']['credit_status_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['credit_status_c']['labelValue']='Credit status';

 

 // created: 2016-09-16 18:32:46
$dictionary['Prospect']['fields']['currency_id']['inline_edit']=1;

 

 // created: 2016-08-12 13:03:01
$dictionary['Prospect']['fields']['date_modified']['audited']=true;
$dictionary['Prospect']['fields']['date_modified']['comments']='Date record last modified';
$dictionary['Prospect']['fields']['date_modified']['merge_filter']='disabled';

 

 // created: 2016-09-16 17:43:41
$dictionary['Prospect']['fields']['date_record_uploaded_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['date_record_uploaded_c']['options']='date_range_search_dom';
$dictionary['Prospect']['fields']['date_record_uploaded_c']['labelValue']='Date Record Uploaded';
$dictionary['Prospect']['fields']['date_record_uploaded_c']['enable_range_search']='1';

 

 // created: 2016-09-16 18:00:21
$dictionary['Prospect']['fields']['disposition_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['disposition_c']['labelValue']='Disposition';

 

 // created: 2016-08-01 11:43:28
$dictionary['Prospect']['fields']['do_not_call']['audited']=true;
$dictionary['Prospect']['fields']['do_not_call']['inline_edit']=true;
$dictionary['Prospect']['fields']['do_not_call']['merge_filter']='disabled';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['dq_score_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['dq_score_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['dq_score_c']['labelValue']='DQ Score';
$dictionary['Prospect']['fields']['dq_score_c']['enable_range_search']='1';

 

 // created: 2016-10-27 13:07:44
$dictionary['Prospect']['fields']['edc_vintage_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['edc_vintage_c']['labelValue']='EDC Vintage';

 

 // created: 2018-03-09 02:09:39
$dictionary['Prospect']['fields']['eligible_instant_renewal_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['eligible_instant_renewal_c']['labelValue']='Eligible for Instant Renewal';

 

 // created: 2018-03-09 02:11:51
$dictionary['Prospect']['fields']['eligible_renewal_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['eligible_renewal_c']['labelValue']='Eligible for Renewal';

 

 // created: 2016-08-12 13:03:21
$dictionary['Prospect']['fields']['email1']['audited']=true;
$dictionary['Prospect']['fields']['email1']['inline_edit']=true;
$dictionary['Prospect']['fields']['email1']['merge_filter']='disabled';

 

 // created: 2016-09-16 18:41:56
$dictionary['Prospect']['fields']['finance_status_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['finance_status_c']['labelValue']='Finance status';

 

 // created: 2016-12-12 16:10:53
$dictionary['Prospect']['fields']['first_name']['audited']=true;
$dictionary['Prospect']['fields']['first_name']['inline_edit']=true;
$dictionary['Prospect']['fields']['first_name']['comments']='First name of the contact';
$dictionary['Prospect']['fields']['first_name']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['first_name']['unified_search']=false;
$dictionary['Prospect']['fields']['first_name']['required']=false;

 

 // created: 2016-12-13 15:11:49
$dictionary['Prospect']['fields']['first_name_l_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['first_name_l_c']['labelValue']='First Name_remove';

 

 // created: 2016-09-16 18:28:00
$dictionary['Prospect']['fields']['gender_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['gender_c']['labelValue']='Gender';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['hash_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['hash_c']['labelValue']='hash';

 

 // created: 2016-09-16 17:56:51
$dictionary['Prospect']['fields']['has_edc_machine_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['has_edc_machine_c']['labelValue']='Has EDC machine';

 

 // created: 2016-08-12 13:04:25
$dictionary['Prospect']['fields']['has_pos_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['has_pos_c']['labelValue']='has pos';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['industry_c'] = array(
    'name' => 'industry_c',
    'vname' => 'LBL_INDUSTRY',
    'type' => 'enum',
    'len' => '255',
    'audited'=>false,
    'required'=>false,
    'comment' => '',
    'ext1' => 'industry_list',
    'ext2' => '',
    'ext3' => '',
);  




 // created: 2016-09-16 18:09:45
$dictionary['Prospect']['fields']['industry_type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['industry_type_c']['labelValue']='Industry';

 

 // created: 2018-03-09 02:11:01
$dictionary['Prospect']['fields']['instant_renewal_amount_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['instant_renewal_amount_c']['labelValue']='Instant Renewal Amount';

 

 // created: 2016-12-12 16:44:20
$dictionary['Prospect']['fields']['interested_in_loan_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['interested_in_loan_c']['labelValue']='Interested in LOAN';

 

 // created: 2017-01-12 10:23:17
$dictionary['Prospect']['fields']['is_distributor_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['is_distributor_c']['labelValue']='is distributor';

 

 // created: 2017-01-12 10:21:41
$dictionary['Prospect']['fields']['is_online_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['is_online_c']['labelValue']='is online';

 

 // created: 2018-04-04 12:41:54
$dictionary['Prospect']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

 // created: 2018-04-04 12:41:54
$dictionary['Prospect']['fields']['jjwg_maps_geocode_status_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['jjwg_maps_geocode_status_c']['labelValue']='Geocode Status';

 

 // created: 2018-04-04 12:41:54
$dictionary['Prospect']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

 // created: 2018-04-04 12:41:54
$dictionary['Prospect']['fields']['jjwg_maps_lng_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['jjwg_maps_lng_c']['labelValue']='Longitude';

 

 // created: 2016-09-16 18:26:09
$dictionary['Prospect']['fields']['landline_number_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['landline_number_c']['labelValue']='Landline Number';

 

 // created: 2016-12-12 16:16:44
$dictionary['Prospect']['fields']['last_name']['audited']=false;
$dictionary['Prospect']['fields']['last_name']['inline_edit']=true;
$dictionary['Prospect']['fields']['last_name']['comments']='Last name of the contact';
$dictionary['Prospect']['fields']['last_name']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['last_name']['unified_search']=false;
$dictionary['Prospect']['fields']['last_name']['required']=false;
$dictionary['Prospect']['fields']['last_name']['importable']='true';

 

 // created: 2016-09-16 18:18:49
$dictionary['Prospect']['fields']['last_name_l_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['last_name_l_c']['labelValue']='Last Name l';

 

 // created: 2016-09-16 19:15:16
$dictionary['Prospect']['fields']['loan_amount_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['loan_amount_c']['labelValue']='loan amount';

 

 // created: 2016-09-16 18:32:28
$dictionary['Prospect']['fields']['loan_amount_required_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['loan_amount_required_c']['labelValue']='Loan Amount Required';

 

 // created: 2016-12-12 16:09:33
$dictionary['Prospect']['fields']['merchant_name_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['merchant_name_c']['labelValue']='Business /Trading Name';

 

 // created: 2016-10-28 19:29:00
$dictionary['Prospect']['fields']['merchant_type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['merchant_type_c']['labelValue']='Type of Business Entity:';

 

 // created: 2016-09-16 18:19:28
$dictionary['Prospect']['fields']['mobile_number_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['mobile_number_c']['labelValue']='Mobile Number';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['num_yr_in_business_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['num_yr_in_business_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['num_yr_in_business_c']['labelValue']='In Business For';
$dictionary['Prospect']['fields']['num_yr_in_business_c']['enable_range_search']='1';

 

 // created: 2016-09-16 18:40:39
$dictionary['Prospect']['fields']['operations_status_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['operations_status_c']['labelValue']='Operations status';

 

$dictionary['Prospect']['fields']['optout_primary']['mass_update'] = false;



 // created: 2016-10-21 13:10:12
$dictionary['Prospect']['fields']['phone_code_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['phone_code_c']['labelValue']='Phone Code';

 

 // created: 2016-07-27 12:16:29
$dictionary['Prospect']['fields']['phone_home']['audited']=true;
$dictionary['Prospect']['fields']['phone_home']['inline_edit']=true;
$dictionary['Prospect']['fields']['phone_home']['comments']='Home phone number of the contact';
$dictionary['Prospect']['fields']['phone_home']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['phone_home']['unified_search']=false;

 

 // created: 2016-10-17 18:40:53
$dictionary['Prospect']['fields']['phone_mobile']['audited']=true;
$dictionary['Prospect']['fields']['phone_mobile']['inline_edit']=true;
$dictionary['Prospect']['fields']['phone_mobile']['comments']='Mobile phone number of the contact';
$dictionary['Prospect']['fields']['phone_mobile']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['phone_mobile']['unified_search']=false;
$dictionary['Prospect']['fields']['phone_mobile']['required']=true;

 

 // created: 2016-10-29 10:12:06
$dictionary['Prospect']['fields']['phone_work']['audited']=false;
$dictionary['Prospect']['fields']['phone_work']['inline_edit']=true;
$dictionary['Prospect']['fields']['phone_work']['comments']='Work phone number of the contact';
$dictionary['Prospect']['fields']['phone_work']['duplicate_merge']='enabled';
$dictionary['Prospect']['fields']['phone_work']['duplicate_merge_dom_value']='1';
$dictionary['Prospect']['fields']['phone_work']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['phone_work']['unified_search']=false;

 

 // created: 2016-09-16 17:51:52
$dictionary['Prospect']['fields']['pickup_appointmnet_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['pickup_appointmnet_c']['labelValue']='Pickup / appointmnet';

 

 // created: 2016-09-16 18:46:28
$dictionary['Prospect']['fields']['pickup_executive_cam_rematks_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['pickup_executive_cam_rematks_c']['labelValue']='Pickup executive/CAM remarks';

 

 // created: 2016-09-16 18:40:03
$dictionary['Prospect']['fields']['pickup_lead_feedback_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['pickup_lead_feedback_c']['labelValue']='pickup / lead feedback';

 

 // created: 2016-09-16 18:29:49
$dictionary['Prospect']['fields']['pin_code_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['pin_code_c']['labelValue']='Pin Code';

 

 // created: 2016-09-16 18:38:59
$dictionary['Prospect']['fields']['pre_operations_status_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['pre_operations_status_c']['labelValue']='Pre-operations status (CIBIL)';

 

 // created: 2016-08-12 13:03:36
$dictionary['Prospect']['fields']['primary_address_city']['audited']=true;
$dictionary['Prospect']['fields']['primary_address_city']['inline_edit']=true;
$dictionary['Prospect']['fields']['primary_address_city']['comments']='City for primary address';
$dictionary['Prospect']['fields']['primary_address_city']['merge_filter']='disabled';

 

 // created: 2016-08-12 13:04:08
$dictionary['Prospect']['fields']['primary_address_postalcode']['audited']=true;
$dictionary['Prospect']['fields']['primary_address_postalcode']['inline_edit']=true;
$dictionary['Prospect']['fields']['primary_address_postalcode']['comments']='Postal code for primary address';
$dictionary['Prospect']['fields']['primary_address_postalcode']['merge_filter']='disabled';

 

 // created: 2016-08-12 13:03:47
$dictionary['Prospect']['fields']['primary_address_state']['audited']=true;
$dictionary['Prospect']['fields']['primary_address_state']['inline_edit']=true;
$dictionary['Prospect']['fields']['primary_address_state']['comments']='State for primary address';
$dictionary['Prospect']['fields']['primary_address_state']['merge_filter']='disabled';

 

 // created: 2016-09-30 19:21:03
$dictionary['Prospect']['fields']['primary_address_street']['audited']=true;
$dictionary['Prospect']['fields']['primary_address_street']['inline_edit']=true;
$dictionary['Prospect']['fields']['primary_address_street']['comments']='Street address for primary address';
$dictionary['Prospect']['fields']['primary_address_street']['merge_filter']='disabled';

 

 // created: 2016-09-16 18:02:45
$dictionary['Prospect']['fields']['product_pitched_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['product_pitched_c']['labelValue']='Product Pitched';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['ps_score_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['ps_score_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['ps_score_c']['labelValue']='PS Score';
$dictionary['Prospect']['fields']['ps_score_c']['enable_range_search']='1';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['ratings_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['ratings_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['ratings_c']['labelValue']='Ratings';
$dictionary['Prospect']['fields']['ratings_c']['enable_range_search']='1';

 

 // created: 2016-09-16 18:13:33
$dictionary['Prospect']['fields']['record_assigned_to_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['record_assigned_to_c']['labelValue']='Record assigned to(TATA /Kankel)';

 

 // created: 2017-01-24 13:20:57
$dictionary['Prospect']['fields']['remarks_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['remarks_c']['labelValue']='Remarks';

 

 // created: 2016-10-27 12:21:12
$dictionary['Prospect']['fields']['residence_ownership_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['residence_ownership_c']['labelValue']='Residence Ownership';

 

 // created: 2016-09-16 17:54:34
$dictionary['Prospect']['fields']['residence_premise_type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['residence_premise_type_c']['labelValue']='Residence premise type';

 

 // created: 2016-10-27 13:06:13
$dictionary['Prospect']['fields']['residence_vintage_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['residence_vintage_c']['labelValue']='Residence Vintage';

 

 // created: 2016-09-16 18:04:07
$dictionary['Prospect']['fields']['right_party_contact_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['right_party_contact_c']['labelValue']='Right Party Contact';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['shop_name_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['shop_name_c']['labelValue']='Shop Name';

 

 // created: 2016-12-14 18:45:09
$dictionary['Prospect']['fields']['source_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['source_c']['labelValue']='Target Source';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['source_url_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['source_url_c']['labelValue']='source url';
$dictionary['Prospect']['audited'] = true;
 


 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['star_rating_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['star_rating_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['star_rating_c']['labelValue']='Star Rating';
$dictionary['Prospect']['fields']['star_rating_c']['enable_range_search']='1';

 

 // created: 2016-09-30 16:20:15
$dictionary['Prospect']['fields']['status_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['status_c']['labelValue']='Status';

 

 // created: 2016-10-10 12:42:39
$dictionary['Prospect']['fields']['sub_disposition_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['sub_disposition_c']['labelValue']='Sub-disposition';

 

 // created: 2017-01-04 16:46:03
$dictionary['Prospect']['fields']['target_date_assigned_c']['inline_edit']='';
$dictionary['Prospect']['fields']['target_date_assigned_c']['options']='date_range_search_dom';
$dictionary['Prospect']['fields']['target_date_assigned_c']['labelValue']='Target Date Assigned';
$dictionary['Prospect']['fields']['target_date_assigned_c']['enable_range_search']='1';

 

 // created: 2016-10-28 18:07:50
$dictionary['Prospect']['fields']['total_sales_per_month_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['total_sales_per_month_c']['labelValue']='Total Sales Per Month';

 

 // created: 2016-10-27 11:40:37
$dictionary['Prospect']['fields']['type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['type_c']['labelValue']='Target Type';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['web_address_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['web_address_c']['labelValue']='web address';

 

 // created: 2016-06-30 14:44:47
$dictionary['Prospect']['fields']['year_established_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['year_established_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['year_established_c']['labelValue']='Established Year';
$dictionary['Prospect']['fields']['year_established_c']['enable_range_search']='1';

 

	$dictionary['Prospects']['fields']['billing_address_country']['type'] = 'enum';
	$dictionary['Prospects']['fields']['billing_address_country']['options'] = 'cstm_country_list';

	$dictionary['Prospects']['fields']['shipping_address_country']['type'] = 'enum';
	$dictionary['Prospects']['fields']['shipping_address_country']['options'] = 'cstm_country_list';
	
	$dictionary['Prospects']['fields']['billing_address_state']['type'] = 'enum';
	$dictionary['Prospects']['fields']['billing_address_state']['options'] = 'cstm_states_list';
	
	$dictionary['Prospects']['fields']['shipping_address_state']['type'] = 'enum';
	$dictionary['Prospects']['fields']['shipping_address_state']['options'] = 'cstm_states_list';
	
	$dictionary['Prospects']['fields']['billing_address_city']['type'] = 'enum';
	$dictionary['Prospects']['fields']['billing_address_city']['options'] = 'cstm_cities_list';
	
	$dictionary['Prospects']['fields']['shipping_address_city']['type'] = 'enum';
	$dictionary['Prospects']['fields']['shipping_address_city']['options'] = 'cstm_cities_list';
	



 // created: 2021-10-18 11:32:08
$dictionary['Prospect']['fields']['acspm_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['acspm_c']['labelValue']='Average card sales per month:';

 

 // created: 2021-10-18 11:32:08
$dictionary['Prospect']['fields']['alt_landline_number_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['alt_landline_number_c']['labelValue']='Alternate Landline Number';

 

 // created: 2021-10-18 11:32:08
$dictionary['Prospect']['fields']['alt_mobile_number_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['alt_mobile_number_c']['labelValue']='Alternate Mobile Number';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['appointment_time_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['appointment_time_c']['options']='date_range_search_dom';
$dictionary['Prospect']['fields']['appointment_time_c']['labelValue']='appointment time';
$dictionary['Prospect']['fields']['appointment_time_c']['enable_range_search']='1';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['attempts_done_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['attempts_done_c']['labelValue']='Attempts done';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['average_settlements_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['average_settlements_c']['labelValue']='Average Settlements Per Month:';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['avg_sales_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['avg_sales_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['avg_sales_c']['labelValue']='Avg Sales';
$dictionary['Prospect']['fields']['avg_sales_c']['enable_range_search']='1';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['business_ownership_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['business_ownership_c']['labelValue']='Business Ownership';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['business_type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['business_type_c']['labelValue']='Business Type';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['business_vintage_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['business_vintage_c']['labelValue']='Business Vintage';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['business_vintage_years_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['business_vintage_years_c']['labelValue']='Business Year(Estabilished)';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['called_date_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['called_date_c']['labelValue']='Called Date';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['check_disposition_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['check_disposition_c']['labelValue']='Check Disposition';

 

 // created: 2021-10-18 11:32:09
$dictionary['Prospect']['fields']['contact_person_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['contact_person_c']['labelValue']='Contact Person';

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['cost_for_2_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['cost_for_2_c']['labelValue']='cost for 2';

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['currency_id']['inline_edit']=1;

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['disposition_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['disposition_c']['labelValue']='Disposition';

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['dq_score_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['dq_score_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['dq_score_c']['labelValue']='DQ Score';
$dictionary['Prospect']['fields']['dq_score_c']['enable_range_search']='1';

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['edc_vintage_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['edc_vintage_c']['labelValue']='EDC Vintage';

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['first_name_l_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['first_name_l_c']['labelValue']='First Name_remove';

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['gender_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['gender_c']['labelValue']='Gender';

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['hash_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['hash_c']['labelValue']='hash';

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['has_edc_machine_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['has_edc_machine_c']['labelValue']='Has EDC machine';

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['industry_c']['inline_edit']=1;

 

 // created: 2021-10-18 11:32:10
$dictionary['Prospect']['fields']['industry_type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['industry_type_c']['labelValue']='Industry';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['is_distributor_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['is_distributor_c']['labelValue']='is distributor';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['is_online_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['is_online_c']['labelValue']='is online';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['jjwg_maps_geocode_status_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['jjwg_maps_geocode_status_c']['labelValue']='Geocode Status';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['jjwg_maps_lng_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['jjwg_maps_lng_c']['labelValue']='Longitude';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['last_name_l_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['last_name_l_c']['labelValue']='Last Name l';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['loan_amount_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['loan_amount_c']['labelValue']='loan amount';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['loan_amount_required_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['loan_amount_required_c']['labelValue']='Loan Amount Required';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['merchant_name_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['merchant_name_c']['labelValue']='Business /Trading Name';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['merchant_type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['merchant_type_c']['labelValue']='Type of Business Entity:';

 

 // created: 2021-10-18 11:32:11
$dictionary['Prospect']['fields']['mobile_number_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['mobile_number_c']['labelValue']='Mobile Number';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['num_yr_in_business_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['num_yr_in_business_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['num_yr_in_business_c']['labelValue']='In Business For';
$dictionary['Prospect']['fields']['num_yr_in_business_c']['enable_range_search']='1';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['pickup_appointmnet_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['pickup_appointmnet_c']['labelValue']='Pickup / appointmnet';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['pre_operations_status_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['pre_operations_status_c']['labelValue']='Pre-operations status (CIBIL)';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['product_pitched_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['product_pitched_c']['labelValue']='Product Pitched';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['ps_score_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['ps_score_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['ps_score_c']['labelValue']='PS Score';
$dictionary['Prospect']['fields']['ps_score_c']['enable_range_search']='1';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['ratings_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['ratings_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['ratings_c']['labelValue']='Ratings';
$dictionary['Prospect']['fields']['ratings_c']['enable_range_search']='1';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['residence_ownership_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['residence_ownership_c']['labelValue']='Residence Ownership';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['residence_vintage_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['residence_vintage_c']['labelValue']='Residence Vintage';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['right_party_contact_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['right_party_contact_c']['labelValue']='Right Party Contact';

 

 // created: 2021-10-18 11:32:12
$dictionary['Prospect']['fields']['shop_name_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['shop_name_c']['labelValue']='Shop Name';

 

 // created: 2021-10-18 11:32:13
$dictionary['Prospect']['fields']['source_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['source_c']['labelValue']='Target Source';

 

 // created: 2021-10-18 11:32:13
$dictionary['Prospect']['fields']['source_url_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['source_url_c']['labelValue']='source url';

 

 // created: 2021-10-18 11:32:13
$dictionary['Prospect']['fields']['star_rating_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['star_rating_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['star_rating_c']['labelValue']='Star Rating';
$dictionary['Prospect']['fields']['star_rating_c']['enable_range_search']='1';

 

 // created: 2021-10-18 11:32:13
$dictionary['Prospect']['fields']['status_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['status_c']['labelValue']='Status';

 

 // created: 2021-10-18 11:32:13
$dictionary['Prospect']['fields']['sub_disposition_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['sub_disposition_c']['labelValue']='Sub-disposition';

 

 // created: 2021-10-18 11:32:13
$dictionary['Prospect']['fields']['target_date_assigned_c']['inline_edit']='';
$dictionary['Prospect']['fields']['target_date_assigned_c']['options']='date_range_search_dom';
$dictionary['Prospect']['fields']['target_date_assigned_c']['labelValue']='Target Date Assigned';
$dictionary['Prospect']['fields']['target_date_assigned_c']['enable_range_search']='1';

 

 // created: 2021-10-18 11:32:13
$dictionary['Prospect']['fields']['telescript_c']['inline_edit']=1;

 

 // created: 2021-10-18 11:32:13
$dictionary['Prospect']['fields']['total_sales_per_month_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['total_sales_per_month_c']['labelValue']='Total Sales Per Month';

 

 // created: 2021-10-18 11:32:13
$dictionary['Prospect']['fields']['type_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['type_c']['labelValue']='Target Type';

 

 // created: 2021-10-18 11:32:14
$dictionary['Prospect']['fields']['web_address_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['web_address_c']['labelValue']='web address';

 

 // created: 2021-10-18 11:32:14
$dictionary['Prospect']['fields']['year_established_c']['inline_edit']='1';
$dictionary['Prospect']['fields']['year_established_c']['options']='numeric_range_search_dom';
$dictionary['Prospect']['fields']['year_established_c']['labelValue']='Established Year';
$dictionary['Prospect']['fields']['year_established_c']['enable_range_search']='1';

 
?>