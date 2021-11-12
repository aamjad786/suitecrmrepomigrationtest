<html>
<style>
tr:nth-child(even){
    background-color: #eee;
  }
  th {
    background-color: black;
    color: white;
  }
</style>
<script>
  $(document).ready(function(){
  $("<span class='required'> *</span> ").insertAfter(".req");
});
</script>
<H1>Email Delivery Status</H1><br>
<form method='post'>
<label for='status' class='req'> Email status:</label><br>
<input name= 'status' type='radio' value='hardbounce' required> Hard Bounce</input>
<input name= 'status' type='radio' value='softbounce'> Soft Bounce</input>
<input name= 'status' type='radio' value='dropped'> Dropped</input><br><br>
<label for='fromdate' class='req'>From date:</label><br>
<input type='date' id='from' name='fromdate' required><br><br>
<label for='todate' class='req'>To date:</label><br>
<input type='date' id='to' name='todate' required><br><br>
<label for='fromemail'>Sent from email id:</label><br>
<input type='email' name='fromemail'></input><br><br>
<label for='toemail'>Sent to email id:</label><br>
<input type='email' name='toemail'></input><br><br>
<input type='submit'></input><br><br>
<form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST["status"];
    $todate = $_POST["todate"];
    $fromdate = $_POST["fromdate"];
    $fromemail = $_POST["fromemail"];
    $toemail = $_POST["toemail"];

    echo "<b>Status: </b>".$status."<br>";
    echo "<b>From Date: </b>".$fromdate."<br>";
    echo "<b>To Date: </b>".$todate."<br>";
    echo "<b>Sent from email id: </b>".$fromemail."<br>";
    echo "<b>Sent to email id: </b>".$toemail;
  }

?>
<br><br>
<?php
  $url="https://api.pepipost.com/v5.1/events?email=$toemail&enddate=$todate&events=$status&fromaddress=$fromemail&limit=10000&startdate=$fromdate";
  $headers = array('api_key:ca8866bcafa408491438c65eea6840b6');
  // $ch = curl_init();
  // curl_setopt($ch, CURLOPT_URL, $url);
  // curl_setopt($ch, CURLOPT_HTTPGET, 1);
  // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  //   'api_key:ca8866bcafa408491438c65eea6840b6'));
  //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  //   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  //   $output = curl_exec($ch);
  //   curl_close($ch);
  
    require_once('custom/include/CurlReq.php');
    $curl_req       = new CurlReq();

    $output         = $curl_req->curl_req($url,'get','',$headers);

    $output=json_decode($output);
    //print_r($output->data);
    $result=$output->data;
    echo "<b>Total results: ".$output->totalRecords."<br><br></b>";
?>
<table class =table style='border:2px solid black'>
  <tr>
    <th>Sr No.</th>
    <th>TRID</th>
    <th>Recipient Email</th>
    <th>From Address</th>
    <th>Requested Time</th>
    <th>Delivery Time</th>
    <th>Modified Time</th>
    <th>Status</th>
    <th>Size</th>
    <th>Remarks</th>
    <th>Subject</th>
    <th>xapiheader</th>
    <th>Tags</th>
  </tr>
  <?php 
  $i=0;
  foreach($result as $item){
    echo "<tr><td>".++$i."</td>";
    foreach ($item as $key=>$val)
    {
      echo "<td>$val</td>";
    }
    echo "</tr>";
  }
  ?>
</table>
<html>