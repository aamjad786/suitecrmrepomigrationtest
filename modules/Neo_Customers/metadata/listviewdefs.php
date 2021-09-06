<?php
$module_name = 'Neo_Customers';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'CUSTOMER_ID' => 
  array (
    'type' => 'int',
    'label' => 'LBL_CUSTOMER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'RENEWAL_ELIGIBLITY_AMOUNT' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_RENEWAL_ELIGIBLITY_AMOUNT',
    'width' => '10%',
    'default' => true,
  ),
  'RENEWAL_ELIGIBLE' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_RENEWAL_ELIGIBLE',
    'width' => '10%',
  ),
  'INSTANT_RENEWAL_ELIGIBILITY' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_INSTANT_RENEWAL_ELIGIBILITY',
    'width' => '10%',
  ),
  'BLACKLISTED' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_BLACKLISTED',
    'width' => '10%',
  ),
  'MOBILE' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE',
    'width' => '10%',
    'default' => true,
  ),
  'DISPOSITION' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DISPOSITION',
    'width' => '10%',
    'default' => true,
  ),
  'LOAN_AMOUNT' => 
  array (
    'type' => 'int',
    'label' => 'LBL_LOAN_AMOUNT',
    'width' => '10%',
    'default' => true,
  ),
  'QUEUE_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_QUEUE_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
  'PRIORITY' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_PRIORITY',
    'width' => '10%',
    'default' => false,
  ),
  'SUBDISPOSITION' => 
  array (
    'type' => 'dynamicenum',
    'studio' => 'visible',
    'label' => 'LBL_SUBDISPOSITION',
    'width' => '10%',
    'default' => false,
  ),
  'IS_PERFORMING' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_IS_PERFORMING',
    'width' => '10%',
  ),
  'HALF_PAID_UP' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_HALF_PAID_UP',
    'width' => '10%',
  ),
  'LOCATION' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_LOCATION',
    'width' => '10%',
    'default' => false,
  ),
  'RISK_GRADE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_RISK_GRADE',
    'width' => '10%',
    'default' => false,
  ),
  'EVER_30_DPD' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_EVER_30_DPD',
    'width' => '10%',
  ),
  'LOAN_STATUS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_LOAN_STATUS',
    'width' => '10%',
    'default' => false,
  ),
  'APP_ID' => 
  array (
    'type' => 'int',
    'label' => 'LBL_APP_ID',
    'width' => '10%',
    'default' => false,
  ),
);
?>
