<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/


require_once('include/MVC/View/views/view.detail.php');

class OpportunitiesViewDetail extends ViewDetail {

 	function __construct(){
 		parent::__construct();
 	}

 	function display() {

echo $Currency_comma_sep_script=<<<EOQ
	<script>
	
	$(document).ready(function(){
		//var username = $('#current_user_name').val().toLowerCase();
		$('#loan_amount_c').after('<br/>');
		test_skill();
		$('#amount').after('<br/>');
		test_skill_amount();
		
		});
	
	function test_skill() {
		
		var amount =$(loan_amount_c).text().trim();
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
			$('.remove_loan_amount_c').html('');
		}
		else{
			//~ alert('wrong Number cannot be converted');
		   	$('.remove_loan_amount_c').html('');
			$('#loan_amount_c').parent().append('<span id="loan_amount_c_word" class = \"remove_loan_amount_c\" style=\"color:red\">Wrong number cannot be converted</span>');
			return false;
		}
		if(Number(junkVal)==0){
			//~Rupees Zero Only
			
			return false;
			}
			if(actnumber.length>9){
				//~ alert('Oops!!!! the Number is too big to covertes');
				$('.remove_loan_amount_c').html('');
				$('#loan_amount_c').parent().append('<span id="loan_amount_c_word" class = \"remove_loan_amount_c\" style=\"color:red\">Oops!!!! the amount is too big to convert</span>');
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
		
		$('.remove_loan_amount_c').html('');
		$('#loan_amount_c').parent().append('<span id="loan_amount_c_word" class = \"remove_loan_amount_c\" style=\"color:green\"></br></span>');
		var span = document.getElementById('loan_amount_c_word');
		while( span.firstChild ) {
			span.removeChild( span.firstChild );
		}
		span.appendChild( document.createTextNode(finalWord) );
		
		
	}
	function test_skill_amount() {
		
		var amount_san =$(amount).text().trim();
		var regex = new RegExp(',', 'g');
			//replace via regex
		amount_san = amount_san.replace(regex, '');	
		var junkVal=amount_san;
		junkVal=Math.floor(junkVal);
		var obStr=new String(junkVal);
		numReversed=obStr.split("");
		actnumber=numReversed.reverse();

		if(Number(junkVal) >=0){
			//do nothing
			$('.remove_amount_san').html('');
		}
		else{
			//~ alert('wrong Number cannot be converted');
		   	$('.remove_amount_san').html('');
			$('#amount').parent().append('<span id="amount_san_word" class = \"remove_amount_san\" style=\"color:red\">Wrong number cannot be converted</span>');
			return false;
		}
		if(Number(junkVal)==0){
			//~Rupees Zero Only
			
			return false;
			}
			if(actnumber.length>9){
				//~ alert('Oops!!!! the Number is too big to covertes');
				$('.remove_amount_san').html('');
				$('#amount').parent().append('<span id="amount_san_word" class = \"remove_amount_san\" style=\"color:red\">Oops!!!! the amount is too big to convert</span>');
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
				
		$('.remove_amount_san').html('');
		$('#amount').parent().append('<span id="amount_san_word" class = \"remove_amount_san\" style=\"color:green\"></br></span>');
		var span = document.getElementById('amount_san_word');
		while( span.firstChild ) {
			span.removeChild( span.firstChild );
		}
		span.appendChild( document.createTextNode(finalWord) );
		
		
	}

	</script>
EOQ;


//END For loan amount 
 		parent::display();
 	}
}
