<?php
 // created: 2020-01-14 13:40:39
$dictionary['Case']['fields']['resolution']['inline_edit']=true;
$dictionary['Case']['fields']['resolution']['comments']='The resolution of the case';
$dictionary['Case']['fields']['resolution']['merge_filter']='disabled';
$dictionary['Case']['fields']['resolution']['massupdate']=true;
$dictionary['Case']['fields']['resolution']['audited']=true;
$dictionary['Case']['fields']['resolution']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[ A-Za-z0-9_.()@\\/%:,"!#$&-–\n\t]*$/;
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