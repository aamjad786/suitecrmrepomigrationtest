 <html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$comment = $_POST['comment'];
	$time = date("d/m/Y H:i:s");
	$duration = $_POST['duration'];
	$unit = $_POST['unit'];
require_once 'modules/Configurator/Configurator.php';
$configurator = new Configurator();
$configurator->loadConfig();
$configurator->config['announcement'] = $comment;
$configurator->config['announcement_time'] = $time;
$configurator->config['announcement_duration'] = $duration;
$configurator->config['announcement_unit'] = $unit;
$configurator->saveConfig();
}

global $sugar_config;
$comment = $sugar_config['announcement'];

?>
 
<table width="100%" class="edit view">
<tr><td><h2>Configure Announcement</h2></td></tr>
<tr><td><form method="post" action="<?php echo "index.php?module=Administration&action=configureAnnouncement";?>"></td></tr>	

  <tr><td>Comment:</td><td> <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
  <span class="error"> <?php echo $commentErr;?></span></td></tr>
  <tr><td>
  Duration:</td><td>
	  <select id="duration" name="duration">
	  <option value="1">1</option>
	  <option value="2">2</option>
	  <option value="3">3</option>
	  <option value="4">4</option>
	  <option value="5">5</option>
	  <option value="6">6</option>
	  <option value="7">7</option>
	  <option value="8">8</option>
	  <option value="9">9</option>
	  <option value="10">10</option>
	  <option value="11">11</option>
	  <option value="12">12</option>
	  </select>
	  <select id="unit" name="unit">
	  <option value="minutes">Minutes</option>
	  <option value="hours">hours</option>
	  </select>
  </td></tr>
  
	<tr><td><input type="submit" name="submit" value="Submit">  
	<input type="button" id="save" onclick="CancelRecord()" value="Cancel" />
	</td></tr>
  </table>
</form>
</body>
<script type="text/javascript">
function CancelRecord()
		{
			location.href="index.php?module=Administration";
		}
</script>
</html>


