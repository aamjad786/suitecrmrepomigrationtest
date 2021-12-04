<?php
 // created: 2018-08-30 11:48:34
$dictionary['Case']['fields']['attended_by_c']['inline_edit']='1';
$dictionary['Case']['fields']['attended_by_c']['labelValue']='Case Owner';
/*$dictionary['Case']['fields']['attended_by_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[ A-Za-z0-9_-]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Invalid Value.Some Special characters are not allowed.");
            return false;
        };
        
        return true;
    }',
);*/
 ?>