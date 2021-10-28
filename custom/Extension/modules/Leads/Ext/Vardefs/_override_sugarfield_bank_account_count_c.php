<?php
 // created: 2021-08-27 17:12:57
$dictionary['Lead']['fields']['bank_account_count_c']['inline_edit']=true;
$dictionary['Lead']['fields']['bank_account_count_c']['merge_filter']='enabled';

$dictionary['Lead']['fields']['bank_account_count_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{1,2}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Number Of Bank Account Name!");
            return false;
        };
        return true;
    }',
);
 ?>