<?php
 // created: 2021-08-20 11:22:00
$dictionary['Case']['fields']['tat_status_c']['inline_edit']=true;
$dictionary['Case']['fields']['tat_status_c']['duplicate_merge_dom_value']='0';
$dictionary['Case']['fields']['tat_status_c']['merge_filter']='disabled';
$dictionary['Case']['fields']['tat_status_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[ A-Za-z0-9_]*$/;
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