<?php

 $dictionary['Lead']['fields']['primary_address_street']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[#.0-9a-zA-Z\s,-]+$/;
        var value=$("#" + nameIndex).val();
        
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Street Address msg!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['alt_address_street']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[#.0-9a-zA-Z\s,-]+$/;
        var value=$("#" + nameIndex).val();
        
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Street Address!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['primary_address_postalcode']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[1-9][0-9]{5}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Postal Code!");
            return false;
        };
        return true;
    }',
);


$dictionary['Lead']['fields']['alt_address_postalcode']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[1-9][0-9]{5}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Postal Code!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['alt_landline_number_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{10}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid 10 Digit Number! ");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['phone_work']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{10}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid 10 Digit Number! ");
            return false;
        };
        return true;
    }',
);


$dictionary['Lead']['fields']['average_settlements_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Only Digits!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['average_total_monthly_sales_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Only Digits!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['bank_account_count_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{1,2}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Number Of Bank Account Name!");
            return false;
        };
        return true;
    }',
);


$dictionary['Lead']['fields']['bank_account_name_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Bank Account Name!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['bank_account_type_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Bank Account Type!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['business_vintage_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^\d{1,2}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Year!");
            return false;
        };
        return true;
    }',
);

// Validation Removed for Business Year(Estabilished)	business_vintage_years_c

// $dictionary['Lead']['fields']['business_vintage_years_c']['validation'] = array (
//     'type' => 'callback',
//     'callback' => 'function(formname, nameIndex) {
//         var regEx=/^(18|19|20)\d{2}$/;
//         var value=$("#" + nameIndex).val();
//         if (value != "" && regEx.test(value)== false) {
//             add_error_style(formname, nameIndex, "Please Enter Valid Year!");
//             return false;
//         };
//         return true;
//     }',
// );


$dictionary['Lead']['fields']['phone_mobile']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{10}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid 10 Digit Mobile Number!");
            return false;
        };
        return true;
    }',
);

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


$dictionary['Lead']['fields']['first_name']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[A-Za-z ]+$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "First Name Should Contain Alphabets Only!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['fse_name_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Reference Name!");
            return false;
        };
        return true;
    }',
);


$dictionary['Lead']['fields']['fse_number_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Reference Number!");
            return false;
        };
        return true;
    }',
);
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

$dictionary['Lead']['fields']['last_name']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[A-Za-z ]+$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Last Name Should Contain Alphabets Only!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['loan_amount_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Loan Amount!");
            return false;
        };
        return true;
    }',
);

$dictionary['Lead']['fields']['mention_the_detail_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        console.log("mention_the_detail_c value: "+value);
        if (value !="N/A" && value != "" && regEx.test(value)== false) {
            console.log("mention_the_detail_c error value: "+value);
            add_error_style(formname, nameIndex, "Please Enter Only Alphabet!");
            return false;
        };
        return true;
    }',
);
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

$dictionary['Lead']['fields']['residence_vintage_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^\d{1,2}$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Year!");
            return false;
        };
        return true;
    }',
);

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

$dictionary['Lead']['fields']['turnover_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Only Digits!");
            return false;
        };
        return true;
    }',
);