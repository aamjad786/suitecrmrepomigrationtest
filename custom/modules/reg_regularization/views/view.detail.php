<?php

require_once('include/DetailView/DetailView2.php');

class reg_regularizationViewDetail extends SugarView {

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
            var app_id = $("#app_id").html();
            var url = "index.php?module=Cases&action=customer_application_profile&applicationID="+app_id;
            $( "<p id = 'app_detail_link'></p>" ).insertAfter( "#app_id" );
            if(app_id !== ""){
                $("#app_detail_link").html("<a target='_blank' href='"+url+"''>View Application Details</a>");
            }

            var app_id = $("#app_id").html();
            
                
        </script><?php
        echo '<br>';
        echo'<iframe style="margin-top: 13px;margin-left: -279px;" class= "test" id="mapDisplayIframe" src="index.php?module=Cases&amp;action=customer_application_profile&amp;applicationID='.$this->bean->app_id.'"
         width="100%" height="800" frameborder="0" marginheight="0" marginwidth="0"
         scrolling="auto"><p>Sorry, your browser does not support iframes.</p></iframe>';
        
        
    }
} 
?>
