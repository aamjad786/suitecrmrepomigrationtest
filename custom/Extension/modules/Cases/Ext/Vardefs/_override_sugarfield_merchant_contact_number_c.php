<?php
 // created: 2021-08-20 11:21:44
$dictionary['Case']['fields']['merchant_contact_number_c']['inline_edit']='1';
$dictionary['Case']['fields']['merchant_contact_number_c']['labelValue']='Merchant Contact Number';
$dictionary['Case']['fields']['merchant_contact_number_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{10}$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Mobile Number!");
            return false;
        };
        return true;
    }',
);
 ?>