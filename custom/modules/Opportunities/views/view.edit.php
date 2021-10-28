<?php
// Sorting opportunities in reverse chronological order by default (on any date created)
require_once('include/MVC/View/views/view.edit.php');
class OpportunitiesViewEdit extends ViewEdit {


	public function __construct() {
		parent::__construct();
	}

	function display() {

		$id = $this->bean->id;
		$opp = BeanFactory::getBean('Opportunities', $id);

		$assigned_user_id = $opp->assigned_user_id;
		$assigned_user_name = $opp->assigned_user_name;
		global $current_user;

		$roleObj = new ACLRole();
		$role = $roleObj->getUserRoleNames($current_user->id);
		
		if(($role[0] == 'Customer Acquisition Manager' || $role[0] == 'Cluster Manager' || $role[0] == 'Regional Manager' || $role[0] == 'Functional Head') && empty($this->bean->fetched_row)){
			echo '<b style="color:red">You do not have access to this area. Contact your site administrator to obtain access.</b>';
			sugar_die("");
		}

		if (!in_array('EditEOS', $role) && !$current_user->is_admin) {
?>
			<script>
				$(document).ready(function() {
					$('#eos_opportunity_status_c').parent().parent().hide();
					$('#eos_sub_disposition_c').parent().parent().hide();
					$('#eos_sub_status_c').parent().parent().hide();
					$('#eos_disposition_c').parent().parent().hide();
				})
			</script>
<?php
		}

		global $sugar_config;
		$sep = $sugar_config['default_number_grouping_seperator'];

		echo $Currency_comma_sep_script = <<<EOQ
	<script>
	
	$(document).ready(function(){
		$("#opportunity_status_c option[value='Appointment fixed']").hide();
		$("#opportunity_status_c option[value='Not Interested/Eligible']").hide();
		$("#opportunity_status_c option[value='Follow up']").hide();
		$("#opportunity_status_c option[value='Documents pick up']").hide();
		
		
		$('#amount').after('<br/>');
		$('#loan_amount_c').after('<br/>');
		$('#sales_stage').prop('disabled',true);

		
		test_skill('amount');
		test_skill('loan_amount_c');
		
		$("#amount").keyup(function(){
			test_skill('amount');
			document.getElementById('amount').value =test_remove_comma('amount');
			});
			
		$("#loan_amount_c").keyup(function(){
			test_skill('loan_amount_c');
			document.getElementById('loan_amount_c').value =test_remove_comma('loan_amount_c');	
		});

		$("#cam_auto_assign").change(function() {
			camToggle();
		});
		
	});

	$(document).on('change','#cam_c',function(){
	
		var id = $(this).val();
		var name=$('#cam_c :selected').text();
		// console.log('id: '+id);
		// console.log('name: '+name);
		$('#assigned_user_id').val(id);
		$('#assigned_user_name').val(name);
	});
	
	function camToggle() {
		// console.log('toggle called')
		if ($("#cam_auto_assign").is(':checked')) {
			$('#cam_c').attr('disabled', true);
			$('#cam_c').val('');
			$('#assigned_user_name').val('$assigned_user_name');
			// console.log('checked true')
		} else {
			// console.log('checked false ','$assigned_user_name');
			$('#assigned_user_name').val('$assigned_user_name');
			$('#cam_c').attr('disabled', false);
		}
	}

	camToggle();

	
	
	function test_remove_comma(amount_ID) {
		var sep='$sep';
		var amount=document.getElementById(amount_ID).value;
		var regex = new RegExp(',', 'g');
		//replace via regex
		amount = amount.replace(regex, '');	
				var x=amount;
    			x=x.toString();
				var lastThree = x.substring(x.length-3);
                var otherNumbers = x.substring(0,x.length-3);
                   if(otherNumbers != '')
					lastThree = sep + lastThree;
						var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, sep) + lastThree;
				return res;
	}
	
	function test_skill(amount_ID) {
		var amount=document.getElementById(amount_ID).value;
		var regex = new RegExp(',', 'g');
			//replace via regex
		amount = amount.replace(regex, '');	
		var junkVal=amount;
		junkVal=Math.floor(junkVal);
		var obStr=new String(junkVal);
		numReversed=obStr.split("");
		actnumber=numReversed.reverse();

		if(Number(junkVal) >=0){
			//do nothing
			if(amount_ID=='amount'){
			$('.remove_amount').html('');
			}
			if(amount_ID=='loan_amount_c'){
			$('.remove_loan_amount_c').html('');
			}
		}
		else{
			//~ alert('wrong Number cannot be converted');
		   if(amount_ID=='amount'){
			$('.remove_amount').html('');
			$('#amount').parent().append('<span id="amount_word" class = \"remove_amount\" style=\"color:red\">Wrong Number cannot be converted</span>');
			}
			if(amount_ID=='loan_amount_c'){
			$('.remove_loan_amount_c').html('');
			$('#loan_amount_c').parent().append('<span id="loan_amount_c_word" class = \"remove_loan_amount_c\" style=\"color:red\">Wrong Number cannot be converted</span>');
			}
			return false;
		}
    if(Number(junkVal)==0){
        //~Rupees Zero Only
        
        return false;
		}
		if(actnumber.length>9){
			//~ alert('Oops!!!! the Number is too big to covertes');
			if(amount_ID=='amount'){
			$('.remove_amount').html('');
			$('#amount').parent().append('<span id="amount_word" class = \"remove_amount\" style=\"color:red\">Oops!!!! the amount is too big to convert</span>');
			}
			if(amount_ID=='loan_amount_c'){
			$('.remove_loan_amount_c').html('');
			$('#loan_amount_c').parent().append('<span id="loan_amount_c_word" class = \"remove_loan_amount_c\" style=\"color:red\">Oops!!!! the amount is too big to convert</span>');
			}
			return false;
		}

		var iWords=["Zero", " One", " Two", " Three", " Four", " Five", " Six", " Seven", " Eight", " Nine"];
		var ePlace=['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
		var tensPlace=['dummy', ' Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety' ];

		var iWordsLength=numReversed.length;
		var totalWords="";
		var inWords=new Array();
		var finalWord="";
		j=0;
		for(i=0; i<iWordsLength; i++){
			switch(i)
			{
			case 0:
				if(actnumber[i]==0 || actnumber[i+1]==1 ) {
					inWords[j]='';
				}
				else {
					inWords[j]=iWords[actnumber[i]];
				}
				inWords[j]=inWords[j]+' Only';
				break;
			case 1:
				tens_complication();
				break;
			case 2:
				if(actnumber[i]==0) {
					inWords[j]='';
				}
				else if(actnumber[i-1]!=0 && actnumber[i-2]!=0) {
					inWords[j]=iWords[actnumber[i]]+' Hundred and';
				}
				else {
					inWords[j]=iWords[actnumber[i]]+' Hundred';
				}
				break;
			case 3:
				if(actnumber[i]==0 || actnumber[i+1]==1) {
					inWords[j]='';
				}
				else {
					inWords[j]=iWords[actnumber[i]];
				}
				if(actnumber[i+1] != 0 || actnumber[i] > 0){
					inWords[j]=inWords[j]+" Thousand";
				}
				break;
			case 4:
				tens_complication();
				break;
			case 5:
				if(actnumber[i]==0 || actnumber[i+1]==1) {
					inWords[j]='';
				}
				else {
					inWords[j]=iWords[actnumber[i]];
				}
				if(actnumber[i+1] != 0 || actnumber[i] > 0){
					inWords[j]=inWords[j]+" Lakh";
				}
				break;
			case 6:
				tens_complication();
				break;
			case 7:
				if(actnumber[i]==0 || actnumber[i+1]==1 ){
					inWords[j]='';
				}
				else {
					inWords[j]=iWords[actnumber[i]];
				}
				inWords[j]=inWords[j]+" Crore";
				break;
			case 8:
				tens_complication();
				break;
			default:
				break;
			}
			j++;
		}

		function tens_complication() {
			if(actnumber[i]==0) {
				inWords[j]='';
			}
			else if(actnumber[i]==1) {
				inWords[j]=ePlace[actnumber[i-1]];
			}
			else {
				inWords[j]=tensPlace[actnumber[i]];
			}
		}
		inWords.reverse();
		for(i=0; i<inWords.length; i++) {
			finalWord+=inWords[i];
		}
		//~ document.getElementById(amount_ID).innerHTML=obStr+'  '+finalWord;
		
		if(amount_ID=='amount'){
			
		$('.remove_amount').html('');
		$('#amount').parent().append('<span id="amount_word" class = \"remove_amount\" style=\"color:green\"><br></span>');
		var span = document.getElementById('amount_word');
		while( span.firstChild ) {
			span.removeChild( span.firstChild );
		}
		span.appendChild( document.createTextNode(finalWord) ); 
		}
			
		if(amount_ID=='loan_amount_c'){
		$('.remove_loan_amount_c').html('');
		$('#loan_amount_c').parent().append('<span id="loan_amount_c_word" class = \"remove_loan_amount_c\" style=\"color:green\"></br></span>');
		var span = document.getElementById('loan_amount_c_word');
		while( span.firstChild ) {
			span.removeChild( span.firstChild );
		}
		span.appendChild( document.createTextNode(finalWord) );
		}
	
	}
	</script>
EOQ;
		parent::display();
	}
}
