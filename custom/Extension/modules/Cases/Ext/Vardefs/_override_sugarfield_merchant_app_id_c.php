<?php
 // created: 2021-08-20 11:21:44
$dictionary['Case']['fields']['merchant_app_id_c']['inline_edit']='1';
$dictionary['Case']['fields']['merchant_app_id_c']['labelValue']='App ID';
$dictionary['Case']['fields']['merchant_app_id_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[ A-Za-z0-9_/]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Only Numbers and Alphabet are allowed.");
            return false;
        };
        return true;
    }',
);
 ?>