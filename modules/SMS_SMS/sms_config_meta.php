<?php
// SYNC TEST
// This $config_meta array is used by firesms.php to automatically generate the .tpl file html
// The default type = "varchar" & by default it's 'required'
// Put all the config params in order.  They're processed sequentially, each time the 'section' changes a new section header is placed in the template.

$config_meta['firesms_url'] = array('default' => 'https://www.firetext.co.uk/api', 'section'=>'License');
$config_meta['firesms_api_key'] = array('default' => '', 'section' => 'License');
$config_meta['firesms_username'] = array('default' => '', 'section' => 'License');
$config_meta['firesms_password'] = array('default' => '', 'section' => 'License');
$config_meta['firesms_sender'] = array('default' => '', 'section' => 'License');
$config_meta['firesms_msg_length'] = array('default' => '160', 'section' => 'License');