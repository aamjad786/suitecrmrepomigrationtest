<?php

/* * *******************************************************************************
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
 * ****************************************************************************** */

require_once('include/DetailView/DetailView2.php');

/**
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class CasesViewDetail extends SugarView {

    /**
     * @see SugarView::$type
     */
    public $type = 'detail';

    /**
     * @var DetailView2 object
     */
    public $dv;

    /**
     * Constructor
     *
     * @see SugarView::SugarView()
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay() {
        $metadataFile = $this->getMetaDataFile();
        $this->dv = new DetailView2();
        $this->dv->ss = & $this->ss;
        $this->dv->setup($this->module, $this->bean, $metadataFile, get_custom_file_if_exists('include/DetailView/DetailView.tpl'));
    }

    /**
     * @see SugarView::display()
     */
    public function display() {


        global $db, $sugar_config;
        $this->dv->process();
      
        echo $this->dv->display();
        $appId =$this->bean->merchant_app_id_c;
       
        $queryToGetPaylaterOpenApplication = "select id from neo_paylater_open where application_id = '$appId'";
        $results = $db->query($queryToGetPaylaterOpenApplication);
        $paylaterOpenData = $db->fetchByAssoc($results);
        $url = "";
        if(isset($paylaterOpenData['id']) && !empty($paylaterOpenData['id'])){
            $url = (getenv('SCRM_SITE_URL')."/index.php?module=neo_Paylater_Open&action=DetailView&record=".$paylaterOpenData['id']);
        }
        ?>
        <script>
            var text = $('#description').text();
            $('#description').html(text);
            var app_id = $('#merchant_app_id_c').text().trim();
            $(document).ready(function(){
               
                $('#duplicate_button').hide();
                    
                   url = "get_merchant_details.php?application_id="+app_id;
                  
                    $.getJSON(url, function( data ) {
                        console.log(data);
                      
                        if((data.length)>0){
                         console.log(data[0]);
                          data = data[0];
                          $("#tid_c").text(data['TID']);
                        }else{
                            
                            console.log('No user found');
                        }
                      });

            });
            

            if($("#case_subcategory_c").val()=="financial_live_tds_refund"|| $("#case_subcategory_c").val()=="financial_closed_tds_refund")
            {

            
            $( "#description" ).click(function() {
                var fileUrl='';
                $('#description >p>a').each(function(index){
                    fileUrl = $(this ).attr('href');
                });

                uri= new URL(fileUrl)
                var bucket=uri.hostname.substring(0,uri.hostname.indexOf('.'));
                var file_path = uri.pathname.substring(1,uri.pathname.lastIndexOf('/'));
                if(file_path=="/")
                {
                    file_path="";
                }
                var filen=uri.pathname.substring(uri.pathname.lastIndexOf('/')+1);
                console.log(filen);
                $.ajax({
                    type: "POST",
                    data: {
                      fileName: filen,
                      application:'crm',
                      path : file_path,
                      bucket : bucket
                    },
                    url: "custom/aws_api/aws_download_api.php",
                    async: false,
                    success: function(data) {
                        window.open(data, '_blank');
                    }
                  });   
                return false;
            });
            
            function getLastPart(str, substring) {
                    return str.split(substring)[1];
            }
            }
            
            var first_number_validation = '^5[0-9].*$';
            if(app_id.match(first_number_validation)){
               var url = "<?php echo $url;?>";
                $('#merchant_app_id_c').html("<a href='" + url + "''>" + app_id + "</a>");
            } else {
                var url = "index.php?module=Cases&action=customer_application_profile&applicationID=" + app_id;
                $('#merchant_app_id_c').html("<a href='" + url + "''>" + app_id + "</a>");
            }
            //1674
            //Getting Service manager through Service Manager module
        
        
        var newRow = $('<div class="row detail-view-row"><div class="col-xs-12 col-sm-6 detail-view-row-item"><div class="col-xs-12 col-sm-4 label col-1-label">Service Manager:</div><div class="col-xs-12 col-sm-8 detail-view-field"><span class="sugar_field" id="case_service_manager"></span></div><?php global $current_user; 
        $env = getenv('SCRM_ENVIRONMENT');
        $category = $this->bean->case_subcategory_c_new_c;
        $isApproved = $this->bean->fetched_row['case_category_approval_c'];
        if($env =='prod'){
            $checker_c = $sugar_config['prod_checker_user']; // Manisha,Yogesh
        } else {
            $checker_c = $sugar_config['non_prod_checker_user']; // Nikhil, GOPI
        }

        
        if(!empty($category) && empty($isApproved)){
            
        if(in_array(strtolower($current_user->user_name),$checker_c)){ ?> <button id="approve_category" style="padding: 5px 8px 5px 8px;" class="utilsLink" data-user_id="<?php echo $current_user->id; ?>" data-id="<?php 
           echo $this->bean->id; ?>">Approve New Category</button>&nbsp;&nbsp;&nbsp;<button id="reject_category" style="padding: 5px 8px 5px 8px;" class="utilsLink" data-id="<?php 
           echo $this->bean->id; ?>">Reject New Category</button><?php }
        } ?></div></div>');
      <?php   
        
        if(in_array(strtolower($current_user->user_name),$checker_c)){ ?>
            $(document).ready(function(){
                var comment = $('#checker_comment_c').text();
                $("#checker_comment_c").html("<textarea class='sugar_field' id='checker_comments'>"+comment+"</textarea>");
            });
       <?php  } ?>
        
            newRow.insertAfter($('#tab-content-0').children().last());

            $(document).on('click','#approve_category',function(){
                if($('#checker_comments').val()==''){
                    alert('Please enter the checker comment');
                    $('#checker_comments').focus();
                    return false;
                }
                var id = $(this).attr('data-id');
                var user_id = $(this).attr('data-user_id');
                var checker_comments = $('#checker_comments').val();
                var r = confirm('Are you sure to approve this change');
                var result;
                if(r== true){
                  $.ajax({
                    url:"JavascriptAPICall.php?api=approveCategory",
                    type: "post",
                    data:{id:id,checker_comments:checker_comments,user_id:user_id},
                    success:function(result){
                       
                        if(result== 1){
                            alert('Thank you, for your approval!');
                            location.reload();
                        } else {
                            alert("Something went wrong");
                        }
                    }
                  });
                }
            });

            $(document).on('click','#reject_category',function(){
                if($('#checker_comments').val()==''){
                    alert('Please enter the checker comment');
                    $('#checker_comments').focus();
                    return false;
                }
                var id = $(this).attr('data-id');
                var checker_comments = $('#checker_comments').val();
                var r = confirm('Are you sure to reject this change');
                var result;
                if(r== true){
                    $.ajax({
                    url:"JavascriptAPICall.php?api=approveCategory",
                    type: "post",
                    data:{id:id,reject:'rejected',checker_comments:checker_comments},
                    success:function(result){
                       
                        alert('Category/SubCategory change request Rejected!');
                        $('#approve_category,#reject_category').hide();
                    }
                  });  
                  return false;
                }
            });
        </script><?php

        if (!empty($appId)) {
            $queryToGetuser = "SELECT first_name,last_name FROM users WHERE id IN (SELECT assigned_user_id FROM smacc_sm_account where app_id = '$appId')";
            $result = $db->query($queryToGetuser);
            $userData = $db->fetchByAssoc($result);
            $userName = $userData['first_name'] . " " . $userData['last_name'];
            if(!empty($userName)){
            ?> <script>
                $('#case_service_manager').html("<?php echo $userName;?>");
            </script><?php
            }
        }


        // $this->setDecodeHTML();
    }

    function setDecodeHTML() {
        $this->bean->description = html_entity_decode(($this->bean->description));
    }

}
?>
