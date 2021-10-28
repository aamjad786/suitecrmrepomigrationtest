<?php
 // created: 2021-08-27 17:04:11
$dictionary['Lead']['fields']['alt_landline_number_c']['inline_edit']='1';
$dictionary['Lead']['fields']['alt_landline_number_c']['labelValue']='Alternate Landline Number';
$dictionary['Lead']['fields']['alt_landline_number_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{10}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid 10 Digit Number! ");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['phone_work']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{10}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid 10 Digit Number! ");
            return false;
        };
        return true;
    }',
);
 ?>