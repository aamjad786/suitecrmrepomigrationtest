<h2>&nbsp;Quick2SMS Account Settings</h2>
<script type="text/javascript" src="custom/modules/Administration/firesms.js"></script>
<form method='post' id='frm_settings' action='index.php'>
<input type='hidden' name='action' value='FireTextSMS'/>
<input type='hidden' name='module' value='Administration'/>
<input type='hidden' name='option' value='save'/>
<input type="hidden" name="sugar_body_only" value="1" />
<span class='error'>{$error.main}</span>
<table  border='1' cellspacing='0' cellpadding='0' class='other view'>
	<tr>
    	<td style='text-align:left;' scope='row' width='20%'>Fire SMS Center</td>
		<td width='80%'>
        {if empty($config.firesms_url )}
            {assign var='firesms_url' value=$sms_config.firesms_url}
        {else}
            {assign var='firesms_url' value=$config.firesms_url}
        {/if}
        <input type='text' style='width:100%;' name='firesms_url' value='{$firesms_url}'></td>
    </tr>
	<tr>
    	<td style='text-align:left;' scope='row' width='20%'>Api Key</td>
		<td width='80%'>
        {if empty($config.firesms_api_key )}
            {assign var='firesms_api_key' value=$sms_config.firesms_api_key}
        {else}
            {assign var='firesms_api_key' value=$config.firesms_api_key}
        {/if}
        <input type='text' style='width:100%;' name='firesms_api_key' value='{$firesms_api_key}' title='API Key for Fire SMS'></td>
    </tr>
	<tr>
    	<td style='text-align:left;' scope='row' width='20%'>Username:</td>
		<td width='80%'>
        {if empty($config.firesms_username )}
            {assign var='firesms_username' value=$sms_config.firesms_username}
        {else}
            {assign var='firesms_username' value=$config.firesms_username}
        {/if}
        <input type='text' style='width:100%;' name='firesms_username' value='{$firesms_username}'></td>
    </tr>
	<tr>
    	<td style='text-align:left;' scope='row' width='20%'>Password:</td>
		<td width='80%'>
        {if empty($config.firesms_password )}
            {assign var='firesms_password' value=$sms_config.firesms_password}
        {else}
            {assign var='firesms_password' value=$config.firesms_password}
        {/if}
        <input type='text' style='width:100%;' name='firesms_password' id='firesms_password' value='{$firesms_password}' maxlength='25'></td>
    </tr>
    <tr>
    	<td style='text-align:left;' scope='row' width='20%'>Sender name:</td>
		<td width='80%'>
        {if empty($config.firesms_sender )}
            {assign var='firesms_sender' value=$sms_config.firesms_sender}
        {else}
            {assign var='firesms_sender' value=$config.firesms_sender}
        {/if}
        <input type='text' style='width:100%;' name='firesms_sender' id='firesms_sender' value='{$firesms_sender}' maxlength='11'></td>
    </tr>
	<tr>
    	<td style='text-align:left;' scope='row' width='20%'>Message Length:</td>
		<td width='80%'>
        {if empty($config.firesms_msg_length )}
            {assign var='firesms_msg_length' value=$sms_config.firesms_msg_length}
        {else}
            {assign var='firesms_msg_length' value=$config.firesms_msg_length}
        {/if}
        <input type='text' style='width:100%;' name='firesms_msg_length' id='firesms_msg_length' value='{$firesms_msg_length}' maxlength='15'></td>
    </tr>
    <tr>
    	<td style='text-align:left;' scope='row' width='20%'>FireText Receiving SMS API:</td>
		<td width='80%'>{$sms_sms_webhook_url}</td>
    </tr>
</table> 

<div id='response_text' style='color:red;'></div> 
<table border='0' width='100%'><tr><td><input type='submit' class='button' value='Save' onclick='return save_settings();' ></td>
<td align='right'><input type='button' onclick='return check_credit();' class='button' value='Check Credit'></td>
</tr></table>
</div><div class='mr'></div></div></div>
<div class='ft'><div class='bl'></div><div class='ft-center'></div><div class='br'></div></div></div>
</form>
<br /><br />
