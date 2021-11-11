<?php
 // created: 2021-10-26 12:21:07
$dictionary['Case']['fields']['merchant_email_id_c']['inline_edit']='1';
$dictionary['Case']['fields']['merchant_email_id_c']['labelValue']='Email ID';
$dictionary['Case']['fields']['merchant_email_id_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z0-9.!#$%&^_{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Correct Mail Id.");
            return false;
        };
        return true;
    }',
);
 ?>