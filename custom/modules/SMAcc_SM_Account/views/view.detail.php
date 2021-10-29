<?php

require_once('include/DetailView/DetailView2.php');

class SMAcc_SM_AccountViewDetail extends SugarView {

    public $type = 'detail';
    public $dv;

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
        $this->dv->process();
        echo $this->dv->display();
        ?>
        <script>
            $(document).ready(function() {
                 var date = "<?php echo $this->bean->funded_date;?>";
                $('#funded_date').text(date);
            });
            var app_id = $("#app_id").html();
            var url = "index.php?module=Cases&action=customer_application_profile&applicationID="+app_id;
            $( "<p id = 'app_detail_link'></p>" ).insertAfter( "#app_id" );
            if(app_id !== ""){
                $("#app_detail_link").html("<a target='_blank' href='"+url+"''>View Application Details</a>");
            }
        </script><?php
        global $app_list_strings, $db;
        $onboardingChecklist = $app_list_strings['onboarding_list'];  
        $beanId = $this->bean->id;
        $phoneNumber = $this->bean->contact;
        ?>
        <div style="margin-top: 30px">
            <h2><b>Onboarding checklist</b></h2>
            <div id="detailpanel_1" class="detail view  detail508 expanded">
                <table id="DEFAULT" class="panelContainer" cellspacing="0">
                    <tbody> 
                        <?php
                            foreach ($onboardingChecklist as $key=>$value){
                                if(!empty($key) && !empty($value)){
                                ?>
                                <tr>
                                   <td width="12.5%" scope="col">
                                       <?php echo $value ?>:
                                   </td>
                                   <td class ="onboarding_list" type="varchar" field="<?php echo $key ?>" width="37.5%">
                                       <span class="sugar_field" id="<?php echo ($key != 'repayment_mode'?$key:'repayment_mode_data'); ?>"></span>
                                   </td>
                                   <td width="12.5%" scope="col">
                                     Notes : 
                                   </td>
                                   <td class ="Notes" type="varchar" field="<?php echo $key ?>" width="37.5%">
                                       <span class="sugar_field" id="description<?php echo $key ?>"></span>
                                   </td>
                               </tr>
                            <?php }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>          
            var phoneNumber =  "<?php echo $phoneNumber; ?>";

            var moduleNm = "SMAcc_SM_Account";

            var beanId = "<?php echo $beanId; ?>";

            $('#contact').append('&nbsp;&nbsp;<img style="border:none;cursor:pointer;" title="Click to make a call. Opening the editor may take a moment. Please give it some time." src="custom/themes/default/images/cellphone.gif" onclick="openPopupAjax(\''+phoneNumber+'\',\''+moduleNm+'\', \''+beanId+'\', \'0\');">&nbsp;');

            $('#create_link').hide();

            var app_id = $('#app_id').text();

            var url = "index.php?module=scrm_Custom_Reports&action=CustomerProfile&applicationID=" + app_id + "&details=Get+Details";

            $('#app_id').html("<a href='" + url + "''>" + app_id + "</a>");

            var contact_number = $('#contact').html();
        </script>
        <?php
        $onboardingChecklistArray = array();
        $onboardingChecklistQuery = "SELECT * from onboarding_checklist";
        $responseOfOnboardingChecklist = $db->query($onboardingChecklistQuery);
        while ($row = $db->fetchByAssoc($responseOfOnboardingChecklist)) {
            $onboardingChecklistArray[$row['id']] = $row['list'];
        }
        $getOnboardingMapping = "SELECT * from smaccount_onboarding_mapping where smacc_sm_account_id = '$beanId'";
        $onboardingListSavedData = $db->query($getOnboardingMapping);
        while ($row = $db->fetchByAssoc($onboardingListSavedData)) {
            $onboardingChecklistId = $row['onboarding_checklist_id'];
            $statusCode = $row['status'];
            $description = $row['description'];
            $option = !empty($onboardingChecklistArray[$onboardingChecklistId])?$onboardingChecklistArray[$onboardingChecklistId]:'';
            $status = array_search ($statusCode, $app_list_strings['onboarding_checklist_status']);
           
            ?>
            <script>
                var status = '<?php echo ucwords(strtolower(($status)));?>';
                var description = '<?php echo ucwords(strtolower(($description)));?>';
                var element = '<?php echo ($option !='repayment_mode'?$option:'repayment_mode_data');?>';
               
                $('#'+element).html(status);
                $('#description'+element).html(description);
            </script>
<?php  }
     echo '<br><style>body .navbar .navbar-inverse .navbar-fixed-top{margin-top: -54px;}</style>';
     echo'<iframe style="margin-top: 13px;margin-left: -279px;" class= "test" id="mapDisplayIframe" src="index.php?module=Cases&amp;action=customer_application_profile&amp;applicationID='.$this->bean->app_id.'"
      width="100%" height="800" frameborder="0" marginheight="0" marginwidth="0"
      scrolling="auto"><p>Sorry, your browser does not support iframes.</p></iframe>';
    }
} 
?>
