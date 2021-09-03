<?php
/*
Created By : Nikhil Kumar
Purpose : Compute and display employee hierarchy
*/
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/MVC/View/SugarView.php');
require 'vendor/autoload.php';
use Tree\Node\Node;
class scrm_Custom_ReportsViewhierarchy extends SugarView {

	function display()
	{
		?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
		<style type="text/css">
		 .jstree li > a > .jstree-icon {  display:none !important; } 
		 a.search { color:green !important; }
		</style>
		<script>
			$(document).ready(function(){
				$('#jstree').jstree({
	                
	                "plugins": ["search"],
	                "search": {
	                    "case_sensitive": false,
	                    "show_only_matches": true,
	                    "show_only_matches_children":true
	                }
	            });

				$(".search-input").keyup(function () {
	                var searchString = $(this).val();
	                $('#jstree').jstree('search', searchString);
	            });
	            $("#jstree").on("click", "a", 
			        function() {
			            window.open( this, '_blank');
			        }
			    );

			});
			
		</script>
		<input id="search-input" class="search-input" value="start typing to search" onclick="this.value=''" style="border-color:aquamarine;width:250px;border-width: 2px;"/>
		<br/>
		<?php
		
		$bean = BeanFactory::getBean('Users');
		$query = 'users.deleted=0 and (users.reports_to_id = "" or users.reports_to_id is null)';
		$items = $bean->get_full_list('',$query);
		$arr = array();
		// var_dump($items);
		if ($items){
		    foreach($items as $item){

		    	$node = new Node(1);
		    	$link = "?module=Users&action=DetailView&record=".$item->id;
				$node->setValue("<a href='$link'>$item->name</a>");
				$arr[]=$node;
				$this->createChildren($node,$item->id);

		    }
		}

		$html .= "<div id='jstree'><ul>";
		foreach($arr as $node){
			$html .= $this->printChildren($node);

		}
		$html .= "</ul></div>";
		echo $html;

	}

	function printChildren($node){
		$html = "<li>".$node->getValue()." (".($node->getSize()-1).")";
		$children = $node->getChildren();
		$html .= "<ul>";
		foreach($children as $child){
			$html .= $this->printChildren($child);
		}
		$html .= "</ul></li>";
		return $html;
	}

	function createChildren($father,$id){
		$bean = BeanFactory::getBean('Users');
		$query = "users.deleted=0 and (users.reports_to_id = '$id')";
		$items = $bean->get_full_list('',$query);
		$count=0;
		if ($items){
		    foreach($items as $item){
		    	$node = new Node(1);
		    	$link = "?module=Users&action=DetailView&record=".$item->id;
				$node->setValue("<a href='$link'>$item->name</a>");
		    	$father->addChild($node);
		    	$this->createChildren($node,$item->id);
		    }
		}
	}
}

