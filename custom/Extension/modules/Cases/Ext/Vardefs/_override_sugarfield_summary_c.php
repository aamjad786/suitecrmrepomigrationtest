<?php
 // created: 2021-08-20 11:22:06
$dictionary['Case']['fields']['summary_c']['inline_edit']=1;
$dictionary['Case']['fields']['summary_c']['massupdate']=true;
$dictionary['Case']['fields']['summary_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[ A-Za-z0-9_.-_-:\/&=.<>()|?#,;"%]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Invalid Value. A-Z, a-z, 0-9 and &=.<>()|?#,;"%\/:-.-_ allowed only.");
            return false;
        };
        return true;
    }',
);
 ?>