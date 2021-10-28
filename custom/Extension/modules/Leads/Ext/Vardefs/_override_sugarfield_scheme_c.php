<?php
 // created: 2021-08-27 17:17:47
$dictionary['Lead']['fields']['scheme_c']['inline_edit']=1;
$dictionary['Lead']['fields']['scheme_c']['merge_filter']='disabled';
$dictionary['Lead']['fields']['scheme_c']['unified_search']=true;


$dictionary['Lead']['fields']['scheme_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^\d*[a-zA-Z][a-zA-Z \d]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Scheme Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);
 ?>