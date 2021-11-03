<?php
 // created: 2021-08-27 17:12:59
$dictionary['Lead']['fields']['sub_source_c']['inline_edit']='1';
$dictionary['Lead']['fields']['sub_source_c']['duplicate_merge_dom_value']='0';
$dictionary['Lead']['fields']['sub_source_c']['merge_filter']='disabled';
$dictionary['Lead']['fields']['sub_source_c']['labelValue']='Ad Source';

$dictionary['Lead']['fields']['sub_source_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Sub Source!");
            return false;
        };
        return true;
    }',
);
 ?>