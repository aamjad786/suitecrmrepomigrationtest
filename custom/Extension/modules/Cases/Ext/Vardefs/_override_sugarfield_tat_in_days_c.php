<?php
 // created: 2021-08-20 11:21:59
$dictionary['Case']['fields']['tat_in_days_c']['inline_edit']=true;
$dictionary['Case']['fields']['tat_in_days_c']['duplicate_merge_dom_value']='0';
$dictionary['Case']['fields']['tat_in_days_c']['merge_filter']='disabled';
$dictionary['Case']['fields']['tat_in_days_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Number!");
            return false;
        };
        return true;
    }',
);
 ?>