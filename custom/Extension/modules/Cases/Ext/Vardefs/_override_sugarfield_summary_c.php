<?php
 // created: 2021-08-20 11:22:06
$dictionary['Case']['fields']['summary_c']['inline_edit']=1;
$dictionary['Case']['fields']['summary_c']['massupdate']=true;
/*$dictionary['Case']['fields']['summary_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Invalid Value.Special characters and numbers are not allowed.");
            return false;
        };
        return true;
    }',
);*/
 ?>