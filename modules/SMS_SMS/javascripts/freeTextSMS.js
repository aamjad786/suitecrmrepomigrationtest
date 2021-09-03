// JavaScript Document
$(document).ready(function()
{
    var tmp_call = true;
    
	$('.phone,#phone_work,#phone_other,#phone_mobile,#phone_mobile_span,#merchant_contact_number_c').each(function()
	{
		var phoneNumber = $(this).text().trim();
		// console.lxog(phoneNumber);
		if(phoneNumber.length > 1 && !/(class="phone"|id="?#phone)/.test($(this).html()))
		{
			var leadId = $('input[name="record"]', document.forms['DetailView']).attr('value');
			var moduleNm = '';
			if (!leadId)
			{
				leadId = $('input[name="mass[]"]', $(this).parents('tr:first')).attr('value');
				moduleNm = $('input[name="module"]', document.forms['displayMassUpdate']).attr('value');
			}else{
				moduleNm = $('input[name="module"]', document.forms['DetailView']).attr('value');
			}
			
			$(this).append('&nbsp;&nbsp;<img style="border:none;cursor:pointer;" title="Click to make a call. You should be logged in to cloudagent, in the bottom link." src="custom/themes/default/images/cellphone.gif" onclick="openPopupAjax(\''+phoneNumber+'\',\''+moduleNm+'\', \''+leadId+'\', \'0\');">&nbsp;');
			tmp_call = false;	
		}
	});
	
	if(tmp_call == true)
    {
        var moduleNm = $('input[name="module"]', document.forms['DetailView']).attr('value');
        if(moduleNm == 'Contacts' || moduleNm == 'Leads' || moduleNm == 'Cases'){
            var leadId = $('input[name="record"]', document.forms['DetailView']).attr('value');
            
            $('.panelContainer td').each(function()
            {
               var fieldNm = $(this).attr('field'); 
               if(fieldNm == 'phone_mobile' || fieldNm == 'phone_work' || fieldNm == 'phone_other' || fieldNm == 'merchant_contact_number_c'){
                   var phoneNumber = encodeURI($(this).text().trim());
                   if(phoneNumber.length > 1){
                   $(this).append('&nbsp;&nbsp;<img style="border:none;cursor:pointer;" title="Click to send an SMS. Opening the editor may take a moment. Please give it some time." src="custom/themes/default/images/cellphone.gif" onclick="openPopupAjax(\''+phoneNumber+'\',\''+moduleNm+'\', \''+leadId+'\', \'0\');">&nbsp;');
                   }
               }
            });
        }
    }
});

openPopupAjax = function (phoneNumber, moduleNm, pid, masssms) { 
	if(masssms == 0){
		// myoverlay_reorder_SMSCall("Neogrowth IVR Call", "Calling API...");
		console.log(moduleNm);
		console.log(pid);
		var username = $('#current_user_name').val();
		console.log(username);
		var header = "Neogrowth IVR Call";
		var txt = "<div id='rel_sms'>Calling API...</div>";
		//var url = "https://api1.cloudagent.in/CAServices/AgentManualDial.php?api_key=KK6c2a74f7da9381fa80451cd0b0650de5&username=neogrowth&agentID="+username+"&campaignName=Inbound_912267304969&customerNumber="+phoneNumber+"&uui="+moduleNm+"|"+pid;
		var url = "./callCustomer.php?agentID="+username+"&customerNumber="+phoneNumber+"&uui="+moduleNm+"&pid="+pid;  
	}else{
		var txt = '<img src="custom/themes/default/images/progress1.gif" alt="Progress Bar" id="loading_img" align="middle" style="position:relative; left:50%; margin:0px 0px 0px -127px;"/><div id="rel_sms" style="overflow:auto; height:auto; width:auto;"></div>';
			// alert(pid);
		var url = "./index.php?module=Administration&action=FireTextSMS&sugar_body_only=1&option=editor&phoneNumber=" + phoneNumber + "&moduleNm=" + moduleNm + "&pid=" + pid +"&masssms=" +masssms;  
		var header = "Neogrowth SMS";
	}
	$("#loading_img").show();
	console.log("final : " + url);
	$.ajax({
		type: "GET",
		url: url,
		beforeSend: function(xhr) {
			myoverlay_reorder_SMSCall(header, txt);
		},
		success: function(data){//alert(data);
			console.log(data);
			$("#loading_img").hide();
			$('#rel_sms').html(data);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert('Somer error Occured');
			console.log(textStatus+' '+errorThrown);
		}
	});
}

SMSCallmySimpleDialog = new YAHOO.widget.SimpleDialog("Neogrowth SMS", { 
    width: "30em",
	
    effect:{
        effect: YAHOO.widget.ContainerEffect.FADE,
        duration: 0.25
    }, 
    fixedcenter: true,
    modal: true,
    visible: true,
    draggable: true,
	close:true
});
function myoverlay_reorder_SMSCall(reqtitle,body){
	
	SMSCallmySimpleDialog.setHeader(reqtitle);
	SMSCallmySimpleDialog.setBody(body);
	SMSCallmySimpleDialog.cfg.setProperty("icon", YAHOO.widget.SimpleDialog.ICON_WARN);
	
	
	var handleCancel = function() { 
		this.hide(); 
	};

	SMSCallmySimpleDialog.render(document.body);
    SMSCallmySimpleDialog.show();
};

load_template = function (id, moduleNm, pid, masssms) {
	$('#load_template_id').show();
	var url = "./index.php?module=Administration&action=FireTextSMS&sugar_body_only=1&option=smstemplate&pid="+pid+"&id="+id+"&moduleNm="+moduleNm+"&masssms="+masssms;
	$.ajax({
		type: "POST",
		url: url,
		success: function(data){//alert(data);
			$('#load_template_id').hide();
			var text = data;
			text = text.replace(/<br>/gi, "\n").replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
			//text = text.replace(/(\r\n|\n|\r)/gm,"");
			$("#sms_messag").val(text);
			checksms_len();
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert(textStatus);
		}
	});
}

checksms_len = function () { 
	var limit = parseInt($("#char_limit").val());
	var smsLen = $("#sms_messag").val();
    if(smsLen.length > limit) {
        $("#sms_messag").val(smsLen.substring(0, limit));
    }
	$("span#max_sms_len").html(smsLen.length + "/" + limit + " characters.");
};

validate_sms = function (masssms){
	var num = $("input[type=text]#number").val();
	var msg = $("#sms_messag").val();
	var limit = $("#char_limit").val();
	if(masssms==0){
		if(num.length>0 && msg.length>0)
		{
			if (msg.length > parseInt(limit)) {
				alert("Please message character exceed maximum length.");	
			}	
			return true;	
		}else{
			alert("Please enter phone number and message.");	
		}
	}else{
		if(msg.length>0){
			if (msg.length > parseInt(limit)) {
				alert("Please message character exceed maximum length.");	
			}
			return true;
		}else{
			alert("Please enter message.");
		}
	}
	return false;
}

function compress(data) {
    data = data.replace(/([^&=]+=)([^&]*)(.*?)&\1([^&]*)/g, "$1$2,$4$3");
    return /([^&=]+=).*?&\1/.test(data) ? compress(data) : data;
}

send_sms = function (moduleNm, parent_id) { 
	var data123 = $("#form_fireTextSMS123").serialize();
	//console.log(data123);
	//alert(data123);
	$("#form_fireTextSMS123").html("Sending SMS... Please wait!");
	$("#loading_img").show();
	var url = "./index.php";
	$.ajax({
		type: "POST",
		url: url,
		data: data123,
		async: true,
		beforeSend:function(){
			$("#form_fireTextSMS123").html("<div align=center><br>Sending SMS ... Please wait!<br></div>");
		},
		success: function(data){//alert(data);
			$("#loading_img").hide();

			$("#form_fireTextSMS123").html(data);
			//$("#form_fireTextSMS123").html("Messages Successfully queued.");
			//SMSCallmySimpleDialog.hide();
			//alert("You will be taken to response screen");
			// $("#loading_img").hide();
			//window.location = window.location.pathname+'?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSMS_SMS%26action%3Dindex%26parentTab%3DAll';
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			$("#loading_img").hide();
			//$("#form_fireTextSMS123").html(data);
			$("#form_fireTextSMS123").html("Messages queued. Contact Administrator.");
			//SMSCallmySimpleDialog.hide();
			//alert(textStatus);
		}
	});
	return false;
};