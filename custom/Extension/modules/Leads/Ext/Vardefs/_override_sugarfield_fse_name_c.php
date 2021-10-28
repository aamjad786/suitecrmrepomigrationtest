<?php
 // created: 2021-08-27 17:04:12
$dictionary['Lead']['fields']['fse_name_c']['inline_edit']='1';
$dictionary['Lead']['fields']['fse_name_c']['labelValue']='Reference Name';

$dictionary['Lead']['fields']['fse_name_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Reference Name!");
            return false;
        };
        return true;
    }',
);
 ?>