<?php
 // created: 2021-08-27 17:04:13
$dictionary['Lead']['fields']['fse_number_c']['inline_edit']='1';
$dictionary['Lead']['fields']['fse_number_c']['labelValue']='Reference Number';

$dictionary['Lead']['fields']['fse_number_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Reference Number!");
            return false;
        };
        return true;
    }',
);
 ?>