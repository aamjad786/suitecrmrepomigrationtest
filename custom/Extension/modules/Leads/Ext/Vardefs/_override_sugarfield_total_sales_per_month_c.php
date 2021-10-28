<?php
 // created: 2021-08-27 17:04:14
$dictionary['Lead']['fields']['total_sales_per_month_c']['inline_edit']='1';
$dictionary['Lead']['fields']['total_sales_per_month_c']['labelValue']='Total Sales Per Month';

$dictionary['Lead']['fields']['total_sales_per_month_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/(?=.*?\d)^\$?(([1-9]\d{0,2}(,\d{3})*)|\d+)?(\.\d{1,2})?$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Only Digits!");
            return false;
        };
        return true;
    }',
);
 ?>