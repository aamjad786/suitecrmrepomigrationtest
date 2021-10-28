<?php
 // created: 2021-08-27 17:04:12
$dictionary['Lead']['fields']['business_vintage_c']['inline_edit']='1';
$dictionary['Lead']['fields']['business_vintage_c']['labelValue']='Business vintage';

$dictionary['Lead']['fields']['business_vintage_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^\d{1,2}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Year!");
            return false;
        };
        return true;
    }',
);
 ?>