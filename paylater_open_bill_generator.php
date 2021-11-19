<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
require_once('include/entryPoint.php'); 
$application_id = $_REQUEST['application_id'];
if (!empty($application_id)) {
    $url = getenv('SCRM_LMM_URI') .'/api/v2/paylater_accounts/'.$application_id.'/account_statement';
//    $url = 'https://uat.advancesuite.in:3039/api/v2/paylater_accounts/0247437373/account_statement';
    $response = customCurlRequest($url);
    header('Content-Type: application/pdf');
    header('Content-Length: ' . strlen($response));
    header('Content-Disposition: inline; filename="YourFileName.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    ini_set('zlib.output_compression', '0');
    echo $response;
    if (!empty($response)) {
        require_once('fpdf181/fpdf.php');
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, $response);
        $pdf->Output();
    }
}
function customCurlRequest($url) {
    $bearerPassword = getenv('LMS_BEARER_PASSWORD');
    $headers = array(
        "authorization: Bearer $bearerPassword",
        'content-type' => 'application/json'
    );

    require_once('custom/include/CurlReq.php');
    $curl_req = new CurlReq();

    $output = $curl_req->curl_req($url, 'get', '', $headers);

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_HTTPGET, 1);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // $output = curl_exec($ch);
    // curl_close($ch);

    // $logger = new CustomLogger('LMM_APIs');
	// $logger->log('debug', "curl URL : $url");
	// $logger->log('debug', "Response : " . var_export($output, true));

    return $output;
}

?>