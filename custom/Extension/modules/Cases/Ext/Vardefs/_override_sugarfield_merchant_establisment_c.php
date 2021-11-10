<?php
 // created: 2021-08-20 11:21:44
$dictionary['Case']['fields']['merchant_establisment_c']['inline_edit']='1';
$dictionary['Case']['fields']['merchant_establisment_c']['labelValue']='Merchant Establisment';
$dictionary['Case']['fields']['merchant_establisment_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z /]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Numbers are not allowed.");
            return false;
        };
        return true;
    }',
);
 ?>