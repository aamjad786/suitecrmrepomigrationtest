<?php
 // created: 2021-08-27 17:12:58
$dictionary['Lead']['fields']['turnover_c']['inline_edit']=1;
$dictionary['Lead']['fields']['turnover_c']['duplicate_merge_dom_value']='0';
$dictionary['Lead']['fields']['turnover_c']['merge_filter']='disabled';

$dictionary['Lead']['fields']['turnover_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Only Digits!");
            return false;
        };
        return true;
    }',
);
 ?>