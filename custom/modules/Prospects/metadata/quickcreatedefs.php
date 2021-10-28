<?php
$viewdefs ['Prospects'] = 
array (
  'QuickCreate' => 
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
        'LBL_PROSPECT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_MORE_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_prospect_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'last_name',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
          1 => 
          array (
            'name' => 'phone_work',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'title',
          ),
          1 => 
          array (
            'name' => 'phone_mobile',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'department',
          ),
          1 => 
          array (
            'name' => 'phone_fax',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'account_name',
          ),
          1 => 
          array (
            'name' => 'industry_type_c',
            'studio' => 'visible',
            'label' => 'LBL_INDUSTRY_TYPE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'email1',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'LBL_MORE_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'photo',
            'label' => 'LBL_PHOTO',
          ),
          1 => 
          array (
            'name' => 'do_not_call',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'shop_name_c',
            'label' => 'LBL_SHOP_NAME',
          ),
          1 => 
          array (
            'name' => 'web_address_c',
            'label' => 'LBL_WEB_ADDRESS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'star_rating_c',
            'label' => 'LBL_STAR_RATING',
          ),
          1 => 
          array (
            'name' => 'ratings_c',
            'label' => 'LBL_RATINGS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'year_established_c',
            'label' => 'LBL_YEAR_ESTABLISHED',
          ),
          1 => 
          array (
            'name' => 'avg_sales_c',
            'label' => 'LBL_AVG_SALES',
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
          ),
        ),
      ),
    ),
  ),
);
?>
