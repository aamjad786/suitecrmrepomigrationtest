<?php
 // created: 2021-08-27 17:04:15
$dictionary['Opportunity']['fields']['loan_amount_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['loan_amount_c']['options']='numeric_range_search_dom';
$dictionary['Opportunity']['fields']['loan_amount_c']['labelValue']='Loan Amount  Requested';
$dictionary['Opportunity']['fields']['loan_amount_c']['enable_range_search']='1';

$dictionary['Opportunity']['fields']['loan_amount_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var firstName=$("#" + nameIndex).val();
        if (regEx.test(firstName)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Loan Amount!");
            return false;
        };
        return true;
    }',
);
 ?>