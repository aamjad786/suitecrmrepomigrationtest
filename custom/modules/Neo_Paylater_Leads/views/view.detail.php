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
class Neo_Paylater_LeadsViewDetail extends SugarView {

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
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    function LeadsViewDetail() {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
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
        global $app_list_strings;
        $total_sales = 0;
        $beanID = $this->bean->id;
        $url = "?module=Neo_Paylater_Leads&action=getDocuments&id=$beanID";
        
        $this->dv->process();
        echo $this->dv->display();
        
        $bean = BeanFactory::getBean('Neo_Paylater_Leads', $beanID);
        // echo $bean->phone_mobile;
        $allDocuments = "";
        if ($bean->documents_uploaded_c) {
            $string = $bean->documents_json_c;
            $string = htmlspecialchars_decode($string); //"'".$string."'";
            $allDocuments = json_decode($string);
        }
        ?>
        <style> 
        .customTable {
            border: 1px solid #dddddd;
            border-collapse: collapse;
            height: 50px;
            margin: 20px 20px 20px 0px;

        }
        
        .button {
            border: none;
            padding: 5px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        </style>        
        <h4 style="margin-top: 20px;"><b>Uploaded Documents</b></h4>
            <?php
            $mapping = array('ap_form'=>'Enquiry Form',
                            'pan'=>'Personal PAN of Application',
                            'res_proof'=>'Proof of Residence',
                            'bus_add_proof'=>'Business Address Proof',
                            'bus_reg_proof'=>'Business Registration Proof',
                            'agreement'=>'Agreement',
                            'others'=>'Others',
                            'approved_by_credit_agreement' => 'Agreement',
                            'nach_form' => 'NACH Form', 
                            'cancelled_cheque' => 'Cancelled Cheque',
                            'application_form' => 'Application Form',
                            'aadhaar_application' => 'Aadhaar of Application', 
                            'business_pan' => 'Business PAN',
                            'bank_statement' => 'Bank Statement',
                            'business_constitution_proof' => 'Business Constitution Proof',
                            'gst_returns' => 'GST Returns',
                            'audited_financials' => 'Audited Financials'
                );
            if(!empty($allDocuments)){ ?>
                <table style="border: 1px solid #D3D3D3; width: 60%;" class="customTable">
                <?php
                foreach ($allDocuments as $k =>$valueArray){
                    $keyvalue = $mapping[$k];
                    ?>
                <tr class="customTable">
                    <td class="customTable col-sm-4">
                        <?php echo $keyvalue;?>
                    </td>
                    <td class="customTable col-sm-6">
                        <?php
                        foreach($valueArray as $i=>$value){
                            $urlArray = explode('/',$value);
                            echo ($i+1). ". <span><a class ='aws_url' href='$value'>".$urlArray[count($urlArray)-1]."</a></span><br/>";
                        } ?>
                    </td> 
                </tr>
                    <?php 
                } ?>
                </table>
                <?php
            } else { ?>
                <center><span style="color: red"><b>Documents not uploaded yet</b></span></center>
            <?php }
            $bucket =  "neo-paylater-prod";//getenv('SCRM_PAYLATER_LEADS_BUCKET');
            ?>
        <script>
            var lead_id = "<?= $this->bean->id; ?>";
            var bucket = "<?php echo $bucket; ?>";
            $(document).ready(function () {

                // $('#leads_scrm_disposition_history_1_create_button').hide();

                $('#campaign').parent().parent().parent().append('<tr><td scope="col">id:</td><td>' + lead_id + '</td></tr>');
                $('#assigned_user_id').append('   &nbsp;&nbsp;<a href="?module=Audit&action=Popup&query=true&record=' + lead_id + '&module_name=Neo_Paylater_Leads&mode=single&create=false&field=assigned_user_id" target="blank">View History</a>');

            });
            $('#edit_upload_link').click(function () {
                $('#uploadDoc').src = "<?= $url; ?>";
                $('#uploadDoc').toggle();

            });
            $( ".aws_url" ).click(function() {
                var file_name = $(this).text();
                var file_complete_path = $(this).attr("href");
                if (file_complete_path.includes("lead_form")) {
                    var file_path = 'lead_form';
                  } else {
                    var file_path = 'leads/'+lead_id;
                  }
                $.ajax({
                    type: "POST",
                    data: {
                      fileName: file_name,
                      application: 'ng_paylater',
                      path : file_path,
                      bucket : bucket
                    },
                    url: "custom/aws_api/aws_download_api.php",
                    async: false,
                    success: function(data) {
//                        alert(data);
                        window.open(data, '_blank');
                    }
                  });   
                return false;
            });
        </script>
        <?php
    }

}
