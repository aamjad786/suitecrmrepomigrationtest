<?php
 // created: 2021-08-27 17:04:17
$dictionary['Opportunity']['fields']['sub_source_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['sub_source_c']['labelValue']='Sub Source';

$dictionary['Lead']['fields']['sub_source_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var firstName=$("#" + nameIndex).val();
        if (regEx.test(firstName)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Sub Source!");
            return false;
        };
        return true;
    }',
);
 ?>