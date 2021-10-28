<?php
 // created: 2021-08-30 12:18:18
$dictionary['Lead']['fields']['gst_registration_c']['inline_edit']=true;
$dictionary['Lead']['fields']['gst_registration_c']['duplicate_merge_dom_value']='0';
$dictionary['Lead']['fields']['gst_registration_c']['merge_filter']='disabled';

$dictionary['Lead']['fields']['gst_registration_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Provide Valid GST Number!");
            return false;
        };
        return true;
    }',
);
 ?>