<?php
 // created: 2021-10-12 15:29:43
$dictionary['Lead']['fields']['last_name']['inline_edit']=true;
$dictionary['Lead']['fields']['last_name']['comments']='Last name of the contact';
$dictionary['Lead']['fields']['last_name']['merge_filter']='disabled';
$dictionary['Lead']['fields']['last_name']['required']=true;
$dictionary['Lead']['fields']['last_name']['importable']='false';

$dictionary['Lead']['fields']['last_name']['validation'] = array (
    'type' => 'callback',
    'callback' => 'function(formname, nameIndex) {
        var regEx=/^[A-Za-z]+$/;
        var value=$("#" + nameIndex).val();
        if (value != "" && regEx.test(value)== false) {
            add_error_style(formname, nameIndex, "Last Name Should Contain Alphabets Only!");
            return false;
        };
        return true;
    }',
);

 ?>