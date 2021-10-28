<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 0);
$filename = "uploadfile-".date("Y-m-d").".txt";
// echo readfile($filename);
// die();
function ob_file_callback($buffer)
{
   $filename = "uploadfile-".date("Y-m-d").".txt";

   $ob_file = fopen($filename,'a');
  // global $ob_file;
  fwrite($ob_file,$buffer);
}
ob_start('ob_file_callback');
if ( !defined( 'sugarEntry' ) || !sugarEntry ) die( 'Not A Valid Entry Point' );
$api_key ='c120c70bedcd2e7dee4565fd39c7bd71';
global $db;
define ('PHP_EOR',"<br/>");
$target_url = 'https://' . $_SERVER['SERVER_NAME'].'/targets/targets.csv';
echo PHP_EOR."File to uploaded is ".$target_url.PHP_EOR;
// die($target_url);
$prospect_list_id=$_REQUEST['record'];
$target_list_name = "SELECT name,description from prospect_lists where id='$prospect_list_id'";
$result_target_name = $db->query( $target_list_name );
$row_target_name = $db->fetchByAssoc( $result_target_name );
$target_name = clean_custom($row_target_name['name']);
// die($target_name);
$target_description = $row_target_name['description'];


$query_prospect = "SELECT related_id from prospect_lists_prospects where prospect_list_id='$prospect_list_id' and deleted=0 and related_type='Prospects'";
$result_prospect = $db->query( $query_prospect );
$output="EMAIL\n";
$data = array();
$r=1;
$i=0;
while ( $row_prospect = $db->fetchByAssoc( $result_prospect ) ) {
	$prospect_id = $row_prospect['related_id'];
	// $prospects_info ="SELECT first_name,last_name,phone_mobile from prospects where id='$prospect_id'";
	// $results_prospects_info = $db->query( $prospects_info );
	// $row_prospects_info = $db->fetchByAssoc( $results_prospects_info );
	// $first_name = $row_prospects_info['first_name'];
	// $last_name = $row_prospects_info['last_name'];
	// $phone_mobile = $row_prospects_info['phone_mobile'];
	$email_info = "SELECT E_table.email_address as email FROM email_addresses E_table JOIN email_addr_bean_rel EB_table ON EB_table.email_address_id=E_table.id WHERE EB_table.bean_id='".$prospect_id."' AND EB_table.bean_module='Prospects'";
	$result_email_info = $db->query( $email_info );
	$row_email_info = $db->fetchByAssoc( $result_email_info );
	$email_address = $row_email_info['email'];
	//$content. = $email_address.",".$first_name.",".$last_name.",".$email_address."\n";
	$output .='"'.$email_address.'"';
	$i++;

	$output .="\n";

}
echo "file written with $i rows".PHP_EOR;
// $_SERVER['CONTEXT_DOCUMENT_ROOT'] = '/Users/nikhilkumar/Sites/SuiteCRM-7.6.4';
$filename = $_SERVER['CONTEXT_DOCUMENT_ROOT']."/targets/targets.csv";
echo $filename;
file_put_contents( $filename, $output );
// die();
$xml_data ="type=list&activity=Add&data=<DATASET><CONSTANT><ApiKey>{$api_key}</ApiKey><RefIp></RefIp><RefWeb></RefWeb></CONSTANT><INPUT><Name>$target_name</Name><Active>1</Active></INPUT></DATASET>";
$ch = curl_init( 'https://api.exacttouch.com/API/mailing/' );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt( $ch, CURLOPT_POST, 1 );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_data );
$result = curl_exec( $ch );
curl_close( $ch );
echo (htmlspecialchars($result));

if ( stripos( $result, 'error' ) !== false ) {
	echo PHP_EOR."Error performing request".PHP_EOR;
}
else {
	$xml_doc = simplexml_load_string( $result );
	$list_id= $xml_doc->OUTPUT->LID;
	echo PHP_EOR."List with ".$target_name." created successfully list id = ".$list_id.PHP_EOR;
	$XML_DATA = "type=list&activity=DataUpload&data=<DATASET>
	<CONSTANT><ApiKey>{$api_key}</ApiKey>
	<RefIp></RefIp><RefWeb></RefWeb>
	</CONSTANT>
	<INPUT><LID>$list_id</LID><Operation>Add</Operation>
	<Path>$target_url</Path>
	<NotifyEmail>nikhil.kumar@neogrowth.in</NotifyEmail>
	<TaskPriority>1</TaskPriority>
	</INPUT>
</DATASET>";

	$url = "https://api.exacttouch.com/API/mailing/";
	//echo $url ; exit;
	$ch2 = curl_init();
	curl_setopt( $ch2, CURLOPT_URL, $url );
	curl_setopt( $ch2, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch2, CURLOPT_POST, true );
	curl_setopt( $ch2, CURLOPT_POSTFIELDS, $XML_DATA );
	curl_setopt( $ch2, CURLOPT_SSL_VERIFYPEER, true );
	//curl_setopt($ch, CURLOPT_GETFIELDS, $XML_DATA);
	$Result = curl_exec( $ch2 );
	//print_r($XML_DATA);
	curl_close( $ch2 );
	echo (htmlspecialchars($Result));
	// var_dump( $Result );
	if ( stripos( $Result, 'error' ) !== false ) {
		echo PHP_EOR."Error performing request" .  PHP_EOR;
	}
	else {
		$xml_doc = simplexml_load_string( $Result );
		
		// var_dump( $xml_doc );
		// print_r( $xml_doc->TYPE );
		// echo '<br>status is ', $xml_doc->DATASET->TYPE, '<br/>';
		if ( $xml_doc->TYPE == 'success' ) {
			print_r( "<br>Success</br>" );
		} else {
			echo '<br>Error is ', $xml_doc->errormessage, '<br/>';
		}
	}

}
ob_end_flush();
$filename = "uploadfile-".date("Y-m-d").".txt";
echo readfile($filename);
die();
function clean_custom($string) {
   $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
?>

