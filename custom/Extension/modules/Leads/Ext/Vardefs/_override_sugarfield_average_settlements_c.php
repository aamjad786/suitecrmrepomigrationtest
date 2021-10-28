<?php
 // created: 2021-08-27 17:04:11
$dictionary['Lead']['fields']['average_settlements_c']['inline_edit']='1';
$dictionary['Lead']['fields']['average_settlements_c']['labelValue']='Average Settlements Per Month';

$dictionary['Lead']['fields']['average_settlements_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Only Digits!");
            return false;
        };
        return true;
    }',
);
 ?>