<?php
$dictionary['Opportunity']['fields']['name']['validation']= array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        console.log("fron src/custom/Extension/modules/Opportunities/Ext/Vardefs/opportunity_validation.php.php added");
        var regEx=/^\d*[a-zA-Z][a-zA-Z \d]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Scheme Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);


$dictionary['Opportunity']['fields']['loan_amount_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/\d{1,2}[\,\.]{1}\d{1,2}/;
        var value=$("#" + nameIndex).val();
        console.log("from loan amount added");
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Loan Amount!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['amount']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/\d{1,2}[\,\.]{1}\d{1,2}/;
        var value=$("#" + nameIndex).val();
        console.log("from loan amount added");
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter ValidLoan Amount Disbursed!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['loan_amount_sanctioned_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/\d{1,2}[\,\.]{1}\d{1,2}/;
        var value=$("#" + nameIndex).val();
        console.log("from loan amount added");
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Loan Amount Sanctioned!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['pickup_appointment_address_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[#.0-9a-zA-Z\s,-]+$/;
        var value=$("#" + nameIndex).val();
        
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Pickup/ appointment address!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['pickup_appointment_contact_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[0-9]{10}$/;
        var value=$("#" + nameIndex).val();
        console.log("from loan amount added");
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Pickup / appointment Contact Number!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['pickup_appointment_pincode_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[1-9][0-9]{5}$/;
        var value=$("#" + nameIndex).val();
        console.log("from loan amount added");
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Please Enter Valid Pickup/ appointment pin code!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['scheme_c']['validation'] = array (
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

$dictionary['Opportunity']['fields']['Alliance_Lead_Docs_shared_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[a-zA-Z ]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Contain Alphabets Only!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['dsa_code_c']['validation'] = array (
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

$dictionary['Opportunity']['fields']['advanced_suite_id_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "DSA Code Should Not Contain Alphabets and Numbers!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['application_id_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
      
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "DSA Code Should Not Contain Numbers Only!");
            return false;
        };
        return true;
    }',
);


$dictionary['Opportunity']['fields']['product_type_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Product Type Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['seller_id_online_platform_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['seller_customer_rating_online_platform_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['seller_partner_rating_online_platform_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);


$dictionary['Opportunity']['fields']['settlement_cycle_in_days_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);



$dictionary['Opportunity']['fields']['partner_id_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['business_age_in_months_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[0-9]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Contain Digit Only!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['industry_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['referral_agent_id_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);

$dictionary['Opportunity']['fields']['sales_3_month_c']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        
        var regEx=/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Should Not Contain Any Special Character!");
            return false;
        };
        return true;
    }',
);





