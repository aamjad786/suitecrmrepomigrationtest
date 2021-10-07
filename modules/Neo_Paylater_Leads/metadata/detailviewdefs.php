<?php
$module_name = 'Neo_Paylater_Leads';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
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
            'comment' => 'First name of the contact',
            'label' => 'LBL_FIRST_NAME',
          ),
          1 => 
          array (
            'name' => 'last_name',
            'comment' => 'Last name of the contact',
            'label' => 'LBL_LAST_NAME',
          ),
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
            'name' => 'lead_type',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_TYPE',
          ),
        ),
        5 => 
        array (
          0 => '',
          1 => '',
        ),
        6 => 
        array (
          0 => '',
          1 => '',
        ),
        7 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
      'lbl_address_information' => 
      array (
        0 => 
        array (
          0 => 'primary_address_street',
          1 => 'alt_address_street',
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
    ),
  ),
);
?>
