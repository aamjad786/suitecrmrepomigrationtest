<?php
 // created: 2021-08-20 11:22:10
$dictionary['Case']['fields']['maker_comment_c']['inline_edit']=1;
$dictionary['Case']['fields']['maker_comment_c']['duplicate_merge_dom_value']=0;
$dictionary['Case']['fields']['maker_comment_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
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