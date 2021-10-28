<?php
 // created: 2021-08-27 17:04:12
$dictionary['Lead']['fields']['dsa_code_c']['inline_edit']='1';
$dictionary['Lead']['fields']['dsa_code_c']['labelValue']='DSA Code';

$dictionary['Lead']['fields']['dsa_code_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^\d*[a-zA-Z][a-zA-Z \d]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "DSA Code Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);
 ?>