save_settings = function () {
	ajaxStatus.showStatus('Saving Settings...');
	var url = "./index.php?module=Administration&action=FireTextSMS&sugar_body_only=1&option=save";
	$("div#response_text").html("Saving... Please wait!"); 
	
	var success = function(data) {
		ajaxStatus.showStatus('Saving Done');
		window.setTimeout('ajaxStatus.hideStatus()', 1000);
		$("div#response_text").html(data.responseText);
		return false;
	}
	
	YAHOO.util.Connect.setForm('frm_settings', true, true);
	var cObj = YAHOO.util.Connect.asyncRequest('POST', url, {success: success, failure: success, upload:success});
	
	return false;
};
check_credit = function () { 
	ajaxStatus.showStatus('Check Credit...');
	var url = "./index.php?module=Administration&action=FireTextSMS&sugar_body_only=1&option=smsCredit";
	$("div#response_text").html("Establishing connection to FireText SMS server... Please wait!");  
	
	$.ajax({
		type: "POST",
		url: url,
		success: function(data){//alert(data);
			ajaxStatus.showStatus('Credit Checking Done');
			window.setTimeout('ajaxStatus.hideStatus()', 1000);
			$("div#response_text").html(data);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert(textStatus);
		}
	});
	
	return false;
	
};