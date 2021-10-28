<?php
 // created: 2021-08-27 17:12:57
$dictionary['Lead']['fields']['bank_account_name_c']['inline_edit']=1;
$dictionary['Lead']['fields']['bank_account_name_c']['merge_filter']='enabled';

$dictionary['Lead']['fields']['bank_account_name_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Bank Account Name!");
            return false;
        };
        return true;
    }',
);
 ?>