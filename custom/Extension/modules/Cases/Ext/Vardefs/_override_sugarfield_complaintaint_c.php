<?php
 // created: 2021-08-20 11:21:42
$dictionary['Case']['fields']['complaintaint_c']['inline_edit']='1';
$dictionary['Case']['fields']['complaintaint_c']['labelValue']='Complainant';
$dictionary['Case']['fields']['complaintaint_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[ A-Za-z0-9_]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Invalid Value.Special characters are not allowed.");
            return false;
        };
        return true;
    }',
);
 ?>