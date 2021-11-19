<?php
 // created: 2021-08-20 11:21:40
$dictionary['Case']['fields']['age_c']['inline_edit']='1';
$dictionary['Case']['fields']['age_c']['labelValue']='Age';
 $dictionary['Case']['fields']['age_c']['validation'] = array (
   'type' => 'callback',
     'callback' => 'function(formname, nameIndex) {
         var regEx=/^[ A-Za-z0-9_-]*$/;
        var value=$("#" + nameIndex).val();
         //console.log("field value"+value);
         if (regEx.test(value)== false) {
             add_error_style(formname, nameIndex, "Special characters are not allowed.");
              return false;
          };
         return true;
      }',
  );
 ?>