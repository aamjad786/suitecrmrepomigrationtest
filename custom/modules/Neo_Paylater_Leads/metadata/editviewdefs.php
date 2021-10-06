<?php
$module_name = 'Neo_Paylater_Leads';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CONTACT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_ADDRESS_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EMAIL_ADDRESSES' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'lbl_contact_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
            'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;<input name="first_name"  id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
          ),
          1 => 'last_name',
        ),
        1 => 
        array (
          0 => 'phone_mobile',
          1 => 
          array (
            'name' => 'business_name',
            'label' => 'LBL_BUSINESS_NAME',
          ),
        ),
        2 => 
        array (
          0 => 'email1',
          1 => 
          array (
            'name' => 'primary_address_city',
            'comment' => 'City for primary address',
            'label' => 'LBL_PRIMARY_ADDRESS_CITY',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'partner_name',
            'studio' => 'visible',
            'label' => 'LBL_PARTNER_NAME',
          ),
          1 => 
          array (
            'name' => 'lead_source',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_SOURCE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'campaign',
            'label' => 'LBL_CAMPAIGN',
          ),
          1 => 
          array (
            'name' => 'customer_query',
            'label' => 'LBL_CUSTOMER_QUERY',
          ),
        ),
      ),
      'lbl_address_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'address_c',
            'label' => 'LBL_ADDRESS',
          ),
          1 => 
          array (
            'name' => 'residential_address_c',
            'label' => 'LBL_RESIDENTIAL_ADDRESS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_state',
            'comment' => 'State for primary address',
            'label' => 'LBL_PRIMARY_ADDRESS_STATE',
          ),
          1 => 
          array (
            'name' => 'alt_address_city',
            'comment' => 'City for alternate address',
            'label' => 'LBL_ALT_ADDRESS_CITY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_postalcode',
            'comment' => 'Postal code for primary address',
            'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
          ),
          1 => 
          array (
            'name' => 'alt_address_state',
            'comment' => 'State for alternate address',
            'label' => 'LBL_ALT_ADDRESS_STATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_country',
            'comment' => 'Country for primary address',
            'label' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
          ),
          1 => 
          array (
            'name' => 'alt_address_postalcode',
            'comment' => 'Postal code for alternate address',
            'label' => 'LBL_ALT_ADDRESS_POSTALCODE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'business_entity_c',
            'studio' => 'visible',
            'label' => 'LBL_BUSINESS_ENTITY',
          ),
          1 => 
          array (
            'name' => 'alt_address_country',
            'comment' => 'Country for alternate address',
            'label' => 'LBL_ALT_ADDRESS_COUNTRY',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'partner_fse_name',
            'label' => 'LBL_PARTNER_FSE_NAME',
          ),
          1 => 
          array (
            'name' => 'partner_fse_number',
            'label' => 'LBL_PARTNER_FSE_NUMBER',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'pre_approved_limit',
            'label' => 'LBL_PRE_APPROVED_LIMIT',
          ),
          1 => 
          array (
            'name' => 'missing_documents',
            'label' => 'LBL_MISSING_DOCUMENTS',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'as_application_id_c',
            'label' => 'LBL_AS_APPLICATION_ID',
          ),
          1 => 
          array (
            'name' => 'as_lead_status_c',
            'label' => 'LBL_AS_LEAD_STATUS',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'as_remarks_c',
            'label' => 'LBL_AS_REMARKS',
          ),
          1 => 
          array (
            'name' => 'lead_type',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_TYPE',
          ),
        ),
      ),
      'lbl_email_addresses' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'check_disposition',
            'label' => 'LBL_CHECK_DISPOSITION',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'disposition',
            'studio' => 'visible',
            'label' => 'LBL_DISPOSITION',
          ),
          1 => 
          array (
            'name' => 'subdisposition',
            'studio' => 'visible',
            'label' => 'LBL_SUBDISPOSITION',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'callback',
            'label' => 'LBL_CALLBACK',
          ),
          1 => 
          array (
            'name' => 'meeting',
            'label' => 'LBL_MEETING',
          ),
        ),
        3 => 
        array (
          0 => 'assigned_user_name',
          1 => 'description',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'fe_name',
            'label' => 'LBL_FE_NAME',
          ),
          1 => 
          array (
            'name' => 'attempts_done',
            'label' => 'LBL_ATTEMPTS_DONE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'comment' => 'Date record last modified',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'paylater_user_role_c',
            'label' => 'LBL_PAYLATER_USER_ROLE',
          ),
          1 => 
          array (
            'name' => 'store_id_c',
            'label' => 'LBL_STORE_ID',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'fse_email_c',
            'label' => 'LBL_FSE_EMAIL',
          ),
          1 => 
          array (
            'name' => 'ng_portal_status',
            'comment' => '',
            'label' => 'LBL_NG_PORTAL_STATUS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'customer_id',
            'label' => 'LBL_CUSTOMER_ID',
          ),
          1 => 
          array (
            'name' => 'paylater_lead_id_c',
            'label' => 'LBL_PAYLATER_LEAD_ID',
          ),
        ),
      ),
    ),
  ),
);
?>
