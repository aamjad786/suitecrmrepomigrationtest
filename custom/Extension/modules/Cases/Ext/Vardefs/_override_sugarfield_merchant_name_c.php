<?php
 // created: 2021-08-20 11:21:44
$dictionary['Case']['fields']['merchant_name_c']['inline_edit']='1';
$dictionary['Case']['fields']['merchant_name_c']['labelValue']='Merchant Name';
$dictionary['Case']['fields']['merchant_name_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Name!");
            return false;
        };
        return true;
    }',
);
 ?>