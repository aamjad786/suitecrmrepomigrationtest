<?php



if(!empty($app_id)) {
			$bean = BeanFactory::getBean('Cases');
			$query = "cases.deleted=0 and cases_cstm.merchant_app_id_c='$app_id'";	
			$items = $bean->get_full_list('case_number desc',$query);
			if ($items){
				echo $HTML = <<<DISP5
					<th scope='col' data-hide="phone" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">
						<div style='white-space: normal;'width='100%' align='left'>
		                		Number
			                	&nbsp;&nbsp;
						</div>
					</th>
					<th scope='col' data-toggle="true" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">
						<div style='white-space: normal;' align='left'>
		                        Subject
								&nbsp;&nbsp;
						</div>
					</th>
					<th scope='col' data-hide="phone" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">
						<div style='white-space: normal;'width='100%' align='left'>
			                	Establishment
								&nbsp;&nbsp;
						</div>
					</th>
					
				    <th scope='col' data-hide="phone,phonelandscape" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">					
				    	<div style='white-space: normal;'width='100%' align='left'>
							Name
							&nbsp;&nbsp;
						</div>
					</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">					
						<div style='white-space: normal;'width='100%' align='left'>
								Priority
								&nbsp;&nbsp;
						</div>
					</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">					
						<div style='white-space: normal;'width='100%' align='left'>
								Status
								&nbsp;&nbsp;
						</div>
					</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">	
						<div style='white-space: normal;'width='100%' align='left'>
								Assigned to
								&nbsp;&nbsp;
						</div>
					</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">					
						<div style='white-space: normal;'width='100%' align='left'>
								Date Created
								&nbsp;&nbsp;
							</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">
						<div style='white-space: normal;'width='100%' align='left'>
								Complainant
								&nbsp;&nbsp;
						</div>
					</th>
					<th scope='col' data-hide="phone,phonelandscape,tablet" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">					
						<div style='white-space: normal;'width='100%' align='left'>
								Created By
								&nbsp;&nbsp;
						</div>
					</th>
					<th scope='col' data-hide="phone,phonelandscape,tablet" class="sorting_asc" tabindex="0" aria-controls="output" rowspan="1" colspan="1" aria-sort="ascending"  style="width: 143.889px;">					
						<div style='white-space: normal;'width='100%' align='left'>
								Modified By Name
								&nbsp;&nbsp;
						</div>
					</th>
DISP5;
			    foreach($items as $key=>$item){
			    	$key +=1;
			    	echo "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='int' field='case_number' class='footable-visible footable-first-column'>$item->case_number</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='name' field='name' class='footable-visible footable-first-column' style='white-space: normal;'><a href='index.php?module=Cases&return_module=Cases&action=DetailView&record=$item->id'><b>$item->name</b></a></td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='merchant_establisment_c' class='footable-visible footable-first-column'>$item->merchant_establisment_c</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='merchant_name_c' class='footable-visible footable-first-column'>$item->merchant_name_c</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='enum' field='priority' class='footable-visible footable-first-column'>$item->priority</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='enum' field='state' class='footable-visible footable-first-column'>$item->state</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='relate' field='assigned_user_id' class='footable-visible footable-first-column' style='white-space: normal;'><a href='index.php?module=Employees&return_module=Employees&action=DetailView&record=$item->assigned_user_id'>".getUserName($item->assigned_user_id)."</a></td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='datetime' field='date_entered' class='footable-visible footable-first-column'>$item->date_entered</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='complaintaint_c' class='footable-visible footable-first-column'>$item->complaintaint_c</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='relate' field='created_by' class='footable-visible footable-first-column' style='white-space: normal;'><a href='index.php?module=Employees&return_module=Employees&action=DetailView&record=$item->created_by'>".getUserName($item->created_by)."</a></td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='relate' field='modified_user_id' class='footable-visible footable-first-column' style='white-space: normal;'><a href='index.php?module=Employees&return_module=Employees&action=DetailView&record=$item->modified_user_id'>".getUserName($item->modified_user_id)."</a></td>";

			    	echo "</tr>";
			    }
			}else{
				echo "<tr><td>No Cases with the APP ID $app_id</h2></td></tr><br/>";

			}
		}
?>