<html>
<head>
  <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
</head>
<body>
<?php
	global $db;
	// $customers = new Neo_customers();
	$res = $db->query('select * from neo_customers where renewal_eligible=0 and deleted=0');
	echo '<h1> Not eligible Queue</h1><br/><table id="example" style="display:none;">
    <thead><tr>
    <th>Name</th>
    <th>> 50% paid up</th>
    <th> Renewal Eligible</th>
    </tr></thead><tbody>';
	while($row = $db->fetchByAssoc($res)){
            // $userID = $row['id'];
            // foreach($row as $k=>$v){
            echo "<tr>";
            echo '<td>'.$row['name'].'</td>';
            echo '<td>'.$row['half_paid_up'].'</td>';
            echo '<td>'.$row['renewal_eligible'].'</td>';
            echo '</tr>';
            // }
        }
      
    
    
      // <tr><td>SitePoint</td></tr>
      // <tr><td>Learnable</td></tr>
      // <tr><td>Flippa</td></tr>
        echo '
    </tbody>
  </table>';
  ?>
  <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
  <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
  <script>
  $(function(){
    $("#example").dataTable({"serverSide": true});
    $("#example").show();
  })
  </script>
</body>
</html>