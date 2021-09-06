<?php

// user module custom fields
$fields = array (

    array(
            'name' => 'reportsto_designation_c',
            'vname' => 'Reporting manager designation',
           'type' => 'varchar',
           'len' => '100',
           'module' => 'Users',
        ),

    array(
            'name' => 'sub_department_c',
           'vname' => 'Sub Department',
           'type' => 'varchar',
            'len' => '100',
            'module' => 'Users',
        ),
    array(
           'name' => 'reportsto_email_c',
            'vname' => 'Reporting Manager EmailID',
           'type' => 'varchar',
            'len' => '100',
            'module' => 'Users',
       ),
    array(
            'name' => 'last_date_c',
            'vname' => 'Last working day',
            'type' => 'datetime',
            'required' => false,
            'module' => 'Users',
        ),
    array(
           'name' => 'is_dst_c',
            'vname' => 'is dst',
            'type' => 'bool',
            'default' => '0',
            'studio' => array('listview' => false, 'searchview' => false, 'related' => false),
            'module' => 'Users',
        ),
    array(
            'name' => 'joining_date_c',
            'vname' => 'joining_date',
            'type' => 'datetime',
            'required' => false,
            'module' => 'Users',
        ),
    array(
           'name' => 'designation_c',
            'vname' => 'LBL_DESIGNATION',
            'type' => 'varchar',
            'len' => '50',
            'module' => 'Users',
        ),
    );
require_once('ModuleInstall/ModuleInstaller.php');
    $moduleInstaller = new ModuleInstaller();
   $moduleInstaller->install_custom_fields($fields);
	echo 'custom field created';
?>

