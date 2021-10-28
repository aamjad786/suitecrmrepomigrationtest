<?php
 // created: 2021-08-27 17:17:47
$dictionary['Lead']['fields']['product_type_c']['inline_edit']=true;
$dictionary['Lead']['fields']['product_type_c']['merge_filter']='selected';
$dictionary['Lead']['fields']['product_type_c']['unified_search']=true;

$dictionary['Lead']['fields']['product_type_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Product Type!");
            return false;
        };
        return true;
    }',
);

 ?>