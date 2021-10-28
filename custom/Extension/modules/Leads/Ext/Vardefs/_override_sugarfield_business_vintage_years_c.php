<?php
 // created: 2021-08-27 17:12:58
$dictionary['Lead']['fields']['business_vintage_years_c']['inline_edit']='1';
$dictionary['Lead']['fields']['business_vintage_years_c']['duplicate_merge_dom_value']='0';
$dictionary['Lead']['fields']['business_vintage_years_c']['merge_filter']='disabled';
$dictionary['Lead']['fields']['business_vintage_years_c']['labelValue']='Business Year(Estabilished)';

$dictionary['Lead']['fields']['business_vintage_years_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^(18|19|20)\d{2}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Year!");
            return false;
        };
        return true;
    }',
);
 ?>