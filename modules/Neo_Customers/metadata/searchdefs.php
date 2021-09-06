<?php
$module_name = 'Neo_Customers';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 'name',
      1 => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'loan_status' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_LOAN_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'loan_status',
      ),
      'is_performing' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_IS_PERFORMING',
        'width' => '10%',
        'name' => 'is_performing',
      ),
      'half_paid_up' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_HALF_PAID_UP',
        'width' => '10%',
        'name' => 'half_paid_up',
      ),
      'customer_id' => 
      array (
        'type' => 'int',
        'label' => 'LBL_CUSTOMER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'customer_id',
      ),
      'mobile' => 
      array (
        'type' => 'phone',
        'label' => 'LBL_MOBILE',
        'width' => '10%',
        'default' => true,
        'name' => 'mobile',
      ),
      'blacklisted' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_BLACKLISTED',
        'width' => '10%',
        'name' => 'blacklisted',
      ),
      'location' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_LOCATION',
        'width' => '10%',
        'default' => true,
        'name' => 'location',
      ),
      'app_id' => 
      array (
        'type' => 'int',
        'label' => 'LBL_APP_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'app_id',
      ),
      'instant_renewal_eligibility' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_INSTANT_RENEWAL_ELIGIBILITY',
        'width' => '10%',
        'name' => 'instant_renewal_eligibility',
      ),
      'risk_grade' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_RISK_GRADE',
        'width' => '10%',
        'default' => true,
        'name' => 'risk_grade',
      ),
      'renewal_eligible' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_RENEWAL_ELIGIBLE',
        'width' => '10%',
        'name' => 'renewal_eligible',
      ),
      'renewal_eligiblity_amount' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_RENEWAL_ELIGIBLITY_AMOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'renewal_eligiblity_amount',
      ),
      'renewal_resource' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_RENEWAL_RESOURCE',
        'width' => '10%',
        'default' => true,
        'name' => 'renewal_resource',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
?>
