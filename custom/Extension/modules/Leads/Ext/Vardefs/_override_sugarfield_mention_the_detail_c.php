<?php
 // created: 2021-09-01 14:54:42
$dictionary['Lead']['fields']['mention_the_detail_c']['inline_edit']='1';
$dictionary['Lead']['fields']['mention_the_detail_c']['labelValue']='Please specify';

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
 ?>