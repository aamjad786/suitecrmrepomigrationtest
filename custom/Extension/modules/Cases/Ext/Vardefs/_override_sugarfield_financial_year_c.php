<?php
 // created: 2021-08-20 11:22:02
$dictionary['Case']['fields']['financial_year_c']['inline_edit']=true;
$dictionary['Case']['fields']['financial_year_c']['duplicate_merge_dom_value']='0';
$dictionary['Case']['fields']['financial_year_c']['merge_filter']='disabled';
$dictionary['Case']['fields']['financial_year_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9\s-]*$/;
        var value=$("#" + nameIndex).val();
        //console.log("field value"+value);
        if (regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Alphabet are not allowed.");
            return false;
        };
        return true;
    }',
);
 ?>