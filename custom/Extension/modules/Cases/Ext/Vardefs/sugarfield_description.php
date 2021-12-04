<?php
 // created: 2019-07-16 17:20:12
$dictionary['Case']['fields']['description']['inline_edit']=true;
$dictionary['Case']['fields']['description']['comments']='Full text of the note';
$dictionary['Case']['fields']['description']['merge_filter']='disabled';
$dictionary['Case']['fields']['description']['massupdate']=false;
/*$dictionary['Case']['fields']['description']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[ A-Za-z0-9_.-_-:/&=.<>()|?#,;"%]*$/;
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