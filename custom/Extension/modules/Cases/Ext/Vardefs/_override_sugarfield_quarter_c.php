<?php
 // created: 2021-08-20 11:22:03
$dictionary['Case']['fields']['quarter_c']['inline_edit']=true;
$dictionary['Case']['fields']['quarter_c']['duplicate_merge_dom_value']='0';
$dictionary['Case']['fields']['quarter_c']['merge_filter']='disabled';
$dictionary['Case']['fields']['quarter_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z -]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Special characters and Numbers are not allowed.");
            return false;
        };
        return true;
    }',
);
 ?>