<?php
require_once('CurlReq.php');

$curl_req = new CurlReq();


$app_id=$_REQUEST['app_id'];

$url = "localhost:3033/crm/covid_app_details?new_app_id=$_REQUEST[app_id]";
$response = $curl_req->curl_req($url);

    $responseArray = json_decode($response, true);


    $PBAL=$responseArray['covid_details']['PBAL'];
    $LBAL_c=$responseArray['covid_details']['LBAL_c'];
    $InterestAccruednotReceived=$responseArray['covid_details']['InterestAccruedButNotReceived'];
    $InterestDueTillFeb2020=$responseArray['covid_details']['InterestDueTillFeb2020'];
    $PrincipleDueTillFeb2020=$responseArray['covid_details']['PrincipleDueTillFeb2020'];
    $EMI=$responseArray['covid_details']['EMI'];
    $Installment=$responseArray['covid_details']['Installment'];
    $APR=$responseArray['covid_details']['APR'];
    $OldAppIdTotalCollection=$responseArray['covid_details']['OldAppIdTotalCollection'];
    $frequency=$responseArray['covid_details']['Frequency'];
    $NumberOfMADs=$responseArray['covid_details']['NumberOfMADs'];
    $InstallmentPriorToRebooking=$responseArray['covid_details']['InstallmentPriorToRebooking'];
    $InterestPartOfInstallment=$responseArray['covid_details']['InterestPartOfInstallment'];
    $PrincipalPartOfTheDue=$responseArray['covid_details']['PrincipalPartOfTheDue'];
    $IsApplicableForFunding=$responseArray['covid_details']['IsApplicableForFunding'];
    $LBAL_29Feb=$responseArray['covid_details']['LBAL_29Feb'];
    $PBAL_29Feb=$responseArray['covid_details']['PBAL_29Feb'];
    $InterestPaid_Mar_Aug=$responseArray['covid_details']['InterestPaid_Mar_Aug'];
    $PrincipalPaid_Mar_Aug=$responseArray['covid_details']['PrincipalPaid_Mar_Aug'];
    $OtherUnpaid_29Feb=$responseArray['covid_details']['OtherUnpaid_29Feb'];
    $OtherUnpaid_Mar_Aug=$responseArray['covid_details']['OtherUnpaid_Mar_Aug'];
    $Penal_29Feb=$responseArray['covid_details']['Penal_29Feb'];
    $Penal_Mar_Aug=$responseArray['covid_details']['Penal_Mar_Aug'];
    $MoratoriumInterest_29Feb=$responseArray['covid_details']['MoratoriumInterest_29Feb'];
    $MoratoriumInterest_Mar_Aug=$responseArray['covid_details']['MoratoriumInterest_Mar_Aug'];
    $DPD_29Feb=$responseArray['covid_details']['DPD_29Feb'];
    $DPD_31Aug=$responseArray['covid_details']['DPD_31Aug'];
    $IncrementalUnPaidInterest=$responseArray['covid_details']['IncrementalUnPaidInterest'];
    $DPD=$responseArray['DPD'];
    $variance=$responseArray['variance'];
    $runningLBAL=$responseArray['lbal'];
    $runningPBAL=$responseArray['pbal'];
    $standstillDPD=$responseArray['old_stand_still_DPD'];
    $oldTenure=$responseArray['old_tenure'];
    $newTenure=$responseArray['new_tenure'];
    $oldAppId=$responseArray['covid_details']['OldApplicationId'];
    $aug_31_variance=$responseArray['aug_31_variance'];

    $freq=array(1=>'Daily',2=>'Weekly',3=>'FortNightly',4=>'Monthly');
    $frequency=$freq[$frequency];


    $url = getenv('SCRM_AS_API_BASE_URL')."/get_application_deal_details?ApplicationID=$_REQUEST[app_id]";
			
			$response = $curl_req->curl_req($url);
			
      $json_response = json_decode($response, true);
      if(!empty($json_response) && count($json_response)>0){
        $repayment_amount =$json_response[0]['Repayment Amount'];

      }

      /*$url = getenv('SCRM_AS_API_BASE_URL')."/get_application_deal_details?ApplicationID=$oldAppId";
			
			$response = $curl_req->curl_req($url);
			
      $json_response = json_decode($response, true);
      if(!empty($json_response) && count($json_response)>0){
        $old_funded_date =date_create($json_response[0]['Funded Date']);

      }
if(!empty($new_funded_date))
{
      $new_residual_tenure=date_diff($new_funded_date,date_create(date("Y-m-d")));
      $new_residual_tenure=$new_residual_tenure->format("%a");
}
if(!empty($old_funded_date))
{
      $old_residual_tenure=date_diff($old_funded_date,date_create(date("2020-02-29")));
      $old_residual_tenure=$old_residual_tenure->format("%R%a");
}
if(!empty($new_funded_date) && !empty($newTenure))
{
      $new_residual_tenure=$newTenure-$new_residual_tenure;

      if($new_residual_tenure<0)
      {
        $new_residual_tenure=0;
      }
  }
 
  if(!empty($old_funded_date) && !empty($oldTenure))
  {
    if($old_residual_tenure>0)
    {
        $old_residual_tenure=$oldTenure-$old_residual_tenure;
    }
    else{
      $old_residual_tenure="NA";
    }

    if($old_residual_tenure<0)
    {
      $old_residual_tenure=0;
    }
  }
  */



echo "<b>Loan Deal Distribution for App Id:".$_REQUEST['app_id']."</b><br><br>";
$i=0;

$s=
"
<style>
tr:nth-child(even){
    background-color: #eee;
  }
  th {
    background-color: black;
    color: white;
  }
</style>
<table class =table style='border:2px solid black'>
<tr>
<th colspan='2'>Illustration - Term Loans</th>
<th colspan='3'>Old App ID</th>
<th>New App Id</th>
</tr>

<tr>
<th>S No.</th>
<th>Parameter</th>
<th>29th Feb</th>
<th> 1st March - 31st Aug</th>
<th>31st Aug</th>
<th>1st Sept</th>
</tr>

<tr>
<td>".(++$i).".</td>
<td>PBAL</td>
<td>(NA)</td>
<td>(NA)</td>
<td>$PBAL</td>
<td>".($PBAL+$InterestAccruednotReceived)."</td>
</tr>

<tr>
<td>".(++$i).".</td>
<td>LBAL</td>
<td>(NA)</td>
<td>(NA)</td>
<td>$LBAL_c</td>
<td>$repayment_amount</td>
</tr>

<tr>
<td>".(++$i).".</td>
<td>Principal paid between 1st March to 31st Aug</td>
<td>(NA)</td>
<td>$PBAL_29Feb</td>
<td>(NA)</td>
<td>(NA)</td>
</tr>

<tr>
<td>".(++$i).".</td>
<td>Interest paid between 1st March to 31st Aug</td>
<td>(NA)</td>
<td>$InterestPaid_Mar_Aug</td>
<td>(NA)</td>
<td>(NA)</td>
</tr>

<tr>
<td>".(++$i).".</td>
<td>Paid between 1st March to 31st Aug</td>
<td>(NA)</td>
<td>".($PBAL_29Feb+$InterestPaid_Mar_Aug)."</td>
<td>(NA)</td>
<td>(NA)</td>
</tr>

<tr>
<td>".(++$i).".</td>
<td>Unpaid Interest incrementally Accrued <br>(during moratorium period)</td>
<td>(NA)</td>
<td>(NA)</td>
<td>$InterestAccruednotReceived</td>
<td>(NA)</td>
</tr>

<tr>
<td>".(++$i).".</td>
<td>Installment Frequency</td>
<td>$frequency</td>
<td>(NA)</td>
<td>$frequency</td>
<td>$frequency</td>
</tr>

<tr>
<td>".(++$i).".</td>
<td>Installment Amount</td>
<td>$Installment</td>
<td>(NA)</td>
<td>$Installment</td>
<td>$Installment</td>
</tr>


<tr>
<td>".(++$i).".</td>
<td>Residual Tenure (days)</td>
<td>(NA)</td>
<td>(NA)</td>
<td>(NA)</td>
<td>$newTenure</td>
</tr>

</table>";

echo $s;

?>