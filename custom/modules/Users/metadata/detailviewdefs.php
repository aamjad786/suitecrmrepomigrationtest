<?php
$viewdefs ['Users'] = 
array (
  'DetailView' => 
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
      'form' => 
      array (
        'headerTpl' => 'modules/Users/tpls/DetailViewHeader.tpl',
        'footerTpl' => 'modules/Users/tpls/DetailViewFooter.tpl',
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_USER_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EMPLOYEE_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'LBL_USER_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 'full_name',
          1 => 'user_name',
        ),
        1 => 
        array (
          0 => 'status',
          1 => 
          array (
            'name' => 'UserType',
            'customCode' => '{$USER_TYPE_READONLY}',
          ),
        ),
      ),
      'LBL_EMPLOYEE_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 'employee_status',
          1 => 'show_on_employees',
        ),
        1 => 
        array (
          0 => 'title',
          1 => 'phone_work',
        ),
        2 => 
        array (
          0 => 'department',
          1 => 'phone_mobile',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'last_date_c',
            'label' => 'Last working day',
          ),
          1 => 
          array (
            'name' => 'sub_department_c',
            'label' => 'Sub Department',
          ),
        ),
        4 => 
        array (
          0 => 'reports_to_name',
          1 => 'phone_other',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'reportsto_email_c',
            'label' => 'Reporting Manager EmailID',
          ),
          1 => 
          array (
            'name' => 'reportsto_designation_c',
            'label' => 'Reporting manager designation',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'assign_web_to_leads_c',
            'studio' => 'visible',
            'label' => 'LBL_ASSIGN_WEB_TO_LEADS_C',
          ),
          1 => 'phone_fax',
        ),
        7 => 
        array (
          0 => 'phone_home',
        ),
        8 => 
        array (
          0 => 'messenger_type',
          1 => 'messenger_id',
        ),
        9 => 
        array (
          0 => 'address_street',
          1 => 'address_city',
        ),
        10 => 
        array (
          0 => 'address_state',
          1 => 'address_postalcode',
        ),
        11 => 
        array (
          0 => 'address_country',
        ),
        12 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'designation_c',
            'label' => 'LBL_DESIGNATION',
          ),
        ),
      ),
    ),
  ),
);
?>
