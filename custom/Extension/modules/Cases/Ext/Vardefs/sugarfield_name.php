<?php
 // created: 2016-12-14 17:28:21
$dictionary['Case']['fields']['name']['inline_edit']=true;
$dictionary['Case']['fields']['name']['comments']='The short description of the bug';
$dictionary['Case']['fields']['name']['merge_filter']='disabled';
$dictionary['Case']['fields']['name']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[ A-Za-z0-9_:_></()Ã~¥¿±!Â¤´.¯[#-]*$]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Invalid Value.Some Special characters are not allowed.");
            return false;
        };
        return true;
    }',
);
 ?>