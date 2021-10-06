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
          0 => 
          array (
            'name' => 'primary_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'alt',
              'copy' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
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
    ),
  ),
);
?>
