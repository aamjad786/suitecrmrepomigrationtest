<?php

require_once('include/DetailView/DetailView2.php');

class CallsViewDetail extends SugarView {

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
        $beanId = $this->bean->id;
        $phoneNumber = $this->bean->contact_number;
        ?>
        <script>          
            var phoneNumber =  "<?php echo $phoneNumber; ?>";
            var moduleNm = "Calls";
            var bucket = "neo-ivrs-recordings-prod";//To-do: make it as environmental variable
            var beanId = "<?php echo $beanId; ?>";
            $('#contact_number').append('&nbsp;&nbsp;<img style="border:none;cursor:pointer;" title="Click to make a call. Opening the editor may take a moment. Please give it some time." src="custom/themes/default/images/cellphone.gif" onclick="openPopupAjax(\''+phoneNumber+'\',\''+moduleNm+'\', \''+beanId+'\', \'0\');">&nbsp;');

            var app_id = $("#app_id").html();
            var url = "index.php?module=Cases&action=customer_application_profile&applicationID="+app_id;
            $( "<p id = 'app_detail_link'></p>" ).insertAfter( "#app_id" );
            if(app_id !== ""){
                $("#app_detail_link").html("<a target='_blank' href='"+url+"''>View Application Details</a>");
            }
            $( "#description" ).click(function() {
                var fileUrl = '';
                $('#description > a').each(function(index){
                    fileUrl = $(this ).attr('href');
                });
                var subStr = fileUrl.match("ozonetel/(.*)/file");
                var file_path = 'ozonetel/'+subStr[1];
                var file_name = getLastPart(fileUrl,subStr[1]+'/');

                $.ajax({
                    type: "POST",
                    data: {
                      fileName: file_name,
                      application: 'calls',
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
        </script>
    <?php }
}
?>
