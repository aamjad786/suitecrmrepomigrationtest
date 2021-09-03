<?php
 if (!empty($app_id)) {
            $callsData = BeanFactory::getBean('Calls')->get_full_list('', "calls.app_id = '$app_id'");
                echo $HTML = <<<DISP11
                <div>
                    <h4 style='padding-top:30px'>
                        Application-Call log
                    </h4>
DISP11;
            if (!empty($callsData)) {
                $i = 1;
                foreach ($callsData as $callsLog) {
                    $eachCallData = $callsLog->fetched_row;
                    $callId = $eachCallData['id'];
                    $callName = $eachCallData['name'];
                    if (!empty($callId)) {
                    $url = (getenv('SCRM_SITE_URL')."/index.php?module=Calls&action=DetailView&record=".$callId);
                        echo "$i.  <a target='_blank' href='$url'>  $callName</a></br>";
                    }
                    $i++;
                }
            } 
            echo " </div> ";
        }
		echo "
			<div>
				<div id='detailpanel_4' class='detail view  detail508 expanded'>
				<h4>
					<a href='javascript:void(0)' class='collapseLink' onclick='collapsePanel(4);'>
					<img border='0' id='detailpanel_4_img_hide' src='themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
					<a href='javascript:void(0)' class='expandLink' onclick='expandPanel(4);'>
					<img border='0' id='detailpanel_4_img_show' src='themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
					Queries/Requests/Complaints
					<script>
						document.getElementById('detailpanel_4').className += ' expanded';
					</script>
				</h4>
				<table border='0' cellpadding='0' cellspacing='0' width='100%' class='panelContainer list view table default footable-loaded footable'>
			";		
?>