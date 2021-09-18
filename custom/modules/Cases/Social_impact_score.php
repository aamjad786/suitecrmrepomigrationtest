<?php
$environment = getenv('SCRM_SITE_URL');
require_once('custom/modules/Cases/Cases_functions.php');
$casesCommonFunctions = new Cases_functions();
$authentication = $casesCommonFunctions->casesAuthentication();
global $db;
if (!$authentication) {
    die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
}


$queryToGetSocialImpactScore = "SELECT * from social_impact_score where called = '0' AND deleted = 0";

$socialImpactScore = $db->query($queryToGetSocialImpactScore);
?>
<link rel="stylesheet" type="text/css" href="custom/include/css/dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="custom/include/css/buttons.dataTables.min.css"/>
<center><h1 style="color:#3C8DBC; margin-bottom: 40px; margin-top:20px"><b>Social Impact Score </b></h1></center>


<?php
if (!empty($_REQUEST['messageType']) && !empty($_REQUEST['Message'])) {
    if ($_REQUEST['messageType'] == "SUCCESS") {
        ?>
        <h2 style="color:green; text-align: center; margin: 20px 0px 20px 0px" class="error_message"><?php echo $_REQUEST['Message']; ?></h2>
        <?php
    } else if ($_REQUEST['messageType'] == "FAILURE") {
        ?>
        <h2 style="color:red; text-align: center; margin-bottom: 20px" class="error_message"><?php echo $_REQUEST['Message']; ?></h2>
        <?php
    }
}
?>
<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }
    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
    th {
        background-color: #ccd;
    }
    tr:nth-child(even) {
        background-color: #edf5ff;
    }
    tr:nth-child(odd) {
        background-color: #fff;
    }

</style>

<p id="Social_Impact_validatation_message" style="color:red; text-align: center; margin-bottom: 20px"></p>

<div style="margin-top: 30px">
    <div class="" >
        <table id="tblData" class="myTable" table table-striped style="width:100%">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Application ID</th>
                    <th>Merchant Name</th>
                    <th>Contact Number</th>
                    <th>Total Number of Employees</th>
                    <th>Number of Female Employees</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $db->fetchByAssoc($socialImpactScore)) {
                    ?>
                    <tr>
                        <td><input type="checkbox" class="checkItem" id="check_box_<?php echo $row['id']; ?>" name="check[]" value="<?php echo $row["id"]; ?>"></td>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["as_app_id"]; ?></td>
                        <td><?php echo $row["merchant_name"]; ?></td>
                        <td><?php echo $row["merchant_contact_number"]; ?>
                            <img style="border:none;cursor:pointer;" title="Click to send an SMS. Opening the editor may take a moment. Please give it some time." src="custom/themes/default/images/cellphone.gif" onclick="openPopupAjax('<?php echo $row["merchant_contact_number"]; ?>', 'SocialImpact', '<?php echo $row["id"]; ?>', '0');"></td>
                        <td><input type="text" value="" class="staff_count" id="staff_count_<?php echo $row['id']; ?>" name="staff_count_<?php echo $row['id']; ?>" placeholder="Enter staff count"></td>
                        <td><input type="text" value="" class="female_staff_count" id="female_staff_count_<?php echo $row['id']; ?>" name="female_staff_count_<?php echo $row['id']; ?>" size="25" placeholder="Enter female staff count"></td>
                    </tr>
                <?php }
                ?>  
            </tbody>
        </table>
        <p align="right" style="margin-top: 30px" ><button id ="social_impact_save_button" class="btn btn-success" name="save">SAVE</button></p>
    </div>
    <!--</form>-->
</div>
<form method="post" action="" id="table_form" style="display:none;">

</form>
<br>
<center><h1 style="color:#3C8DBC; margin-bottom: 40px; margin-top:20px"><b>Social Impact Score List View</b></h1></center>

<?php 
       
        $res=$db->query('select * from social_impact_score WHERE deleted = 0');

        echo '<table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th style="text-align: left;">S.No</th>
                <th style="text-align: left;">App ID</th>
                <th style="text-align: left;">Merchant Name</th>
                <th style="text-align: left;">Merchant Contact<br>No</th>
                <th style="text-align: left;">Totol No.of <br>Staff</th>
                <th style="text-align: left;">Total No.of <br>Female Staff</th>
                <th style="text-align: left;">Case   Closed</th>
            </tr>
        </thead>
        <tbody>';

        $i = 1;

        
        while($row = $db->fetchByAssoc($res)){
           //print_r($row);exit;
           echo "<tr>";
           echo   "<td>".$i++."</td>";
           echo   "<td>".ucwords($row['as_app_id'])."</td>";
           echo   "<td>".$row['merchant_name']."</td>";
           echo   "<td>".$row['merchant_contact_number']."</td>";
           echo   "<td>".$row['total_number_of_staff']."</td>";
           echo   "<td>".$row['total_number_of_female_staff']."</td>";
           echo   "<td>".date('d-m-Y',strtotime($row['case_closed_date']))."</td>";
           echo "</tr>";
        }
      echo  '</tbody>
    </table>';
   
?>
<script>

    $(document).on("click", "#social_impact_save_button", function () {
        $(".checkItem").each(function () {
            var checked = $(this).prop("checked")
            var id = $(this).val()
            if (checked) {
                $("#table_form").append("<div id='copied_row_" + id + "' class='copied_row'></div>")
                $("#staff_count_" + id).clone().appendTo("#copied_row_" + id)
                $("#female_staff_count_" + id).clone().appendTo("#copied_row_" + id)
            } else {
                $("#copied_row_" + id).remove();
            }
        })
        var checkedValue = null;
        var inputElements = document.getElementsByClassName('checkItem');
        var ck_box = $('input[type="checkbox"]:checked').length;
        if (ck_box > 0) {
            for (var i = 0; inputElements[i]; ++i) {
                if (inputElements[i].checked) {
                    checkedValue = inputElements[i].value;
                    var staff_count_id = 'staff_count_' + checkedValue;
                    var female_staff_count_id = 'female_staff_count_' + checkedValue;
                    var staff_count = $('#' + staff_count_id).val();
                    var female_staff_count = $('#' + female_staff_count_id).val();

                    if (isInteger(staff_count) && isInteger(female_staff_count) && staff_count !== '' && female_staff_count !== '') {
                        if (parseInt(staff_count) < 0 || parseInt(female_staff_count) < 0) {
                            $('.error_message').hide();
                            $('#Social_Impact_validatation_message').html("Please enter positive numbers only");
                            $("#copied_row_" + checkedValue).remove()
                            return false;
                        }
                        if (parseInt(female_staff_count) > parseInt(staff_count)) {
                            $('.error_message').hide();
                            $('#Social_Impact_validatation_message').html("Female employee count is greater than total employee count");
                            $("#copied_row_" + checkedValue).html("hello")
                            return false;
                        }

                    } else {
                        if (!isInteger(staff_count) || staff_count === '') {
                            $("#" + staff_count_id).focus();
                            document.getElementById(staff_count_id).style.borderColor = "red";
                        }
                        if (!isInteger(female_staff_count) || female_staff_count === '') {
                            $("#" + female_staff_count_id).focus();
                            document.getElementById(female_staff_count_id).style.borderColor = "red";
                        }

                        $('.error_message').hide();
                        $('#Social_Impact_validatation_message').html("Validation falied, Please enter only numbers for the employee count");
                        $("#copied_row_" + checkedValue).remove()
                        return false;
                    }
                }
            }
        } else {
            $('.error_message').hide();
            $('#Social_Impact_validatation_message').html("Please select atleast one application");
            return false;
        }

        if ($(".copied_row").length > 0) {
            $("#table_form").trigger("submit")
        } else {
            alert("Please select atleast one row")
        }
        return true;
    });
    $(document).on("change", ".checkItem", function () {
        var checked = $(this).prop("checked")
        var id = $(this).val()
        if (!checked) {
            $("#copied_row_" + id).remove();
        }
    })

    var SocialImpactSave = document.getElementById('social_impact_save_button');
    //Validating for employee count 
    $(".staff_count").change(function () {
        $('.error_message').hide();
        $('#Social_Impact_validatation_message').html("");
        var staff_count_id = $(this).attr('id');
        var split_number = staff_count_id.split('staff_count_')[1];
        if ($('#check_box_' + split_number).is(":checked")) {
            validateTheCount(split_number);
        } else {
            $('#Social_Impact_validatation_message').html("Please select the application");
            return false;
        }
    });
    $(".female_staff_count").change(function () {
        $('.error_message').hide();
        $('#Social_Impact_validatation_message').html("");
        var staff_count_id = $(this).attr('id');
        var split_number = staff_count_id.split('staff_count_')[1];
        if ($('#check_box_' + split_number).is(":checked")) {
            validateTheCount(split_number);
        } else {
            $('#Social_Impact_validatation_message').html("Please select the application");
            return false;
        }
    });

    function validateTheCount(split_number) {
        var constructed_staff_count_id = "staff_count_" + split_number;
        var constructed_female_staff_count_id = "female_staff_count_" + split_number;
        var current_staff_count = $("#" + constructed_staff_count_id).val();
        var current_female_staff_count = $("#" + constructed_female_staff_count_id).val();
        if (isInteger(current_staff_count) && isInteger(current_female_staff_count)) {
            if (parseInt(current_staff_count) < 0 || parseInt(current_female_staff_count) < 0) {
                document.getElementById(constructed_staff_count_id).style.borderColor = "red";
                document.getElementById(constructed_female_staff_count_id).style.borderColor = "red";
                $('#Social_Impact_validatation_message').html("Please enter positive numbers only");
                return false;
            } else if (parseInt(current_female_staff_count) > parseInt(current_staff_count)) {
                document.getElementById(constructed_staff_count_id).style.borderColor = "red";
                document.getElementById(constructed_female_staff_count_id).style.borderColor = "red";
                $('#Social_Impact_validatation_message').html("Female employee count cannot be greater than total count");
                return false;
            } else {
                document.getElementById(constructed_staff_count_id).style.borderColor = "#cccccc";
                document.getElementById(constructed_female_staff_count_id).style.borderColor = "#cccccc";
                return true;
            }
        } else {
            $("#" + constructed_staff_count_id).focus();
            $("#" + constructed_female_staff_count_id).focus();
            document.getElementById(constructed_staff_count_id).style.borderColor = "red";
            document.getElementById(constructed_female_staff_count_id).style.borderColor = "red";
            $('#Social_Impact_validatation_message').html("Only number allowed");
            return false;
        }
    }
</script>
<script type="text/javascript" src="custom/include/js/jquery.min.js"></script>
<script type="text/javascript" src="custom/include/js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="custom/include/js/moment.min.js"></script>
<script type="text/javascript" src="custom/include/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="custom/include/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="custom/include/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="custom/include/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="custom/include/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
            dom: 'Bfrtip',
            buttons: [
            { 
                extend: 'csv',
                text: 'Export Social Impact Score'
            }
            ]
        } );
    });
    </script>
<?php

   
$environment = getenv('SCRM_SITE_URL');
if (isset($_REQUEST)) {
    foreach ($_REQUEST as $key => $value) {
        if (strpos($key, "staff_count_") !== false) {
            $id = array_pop(explode('_', $key));
            if (isset($_REQUEST['staff_count_' . $id]) && isset($_REQUEST['female_staff_count_' . $id])) {
                $staffCount = $_REQUEST['staff_count_' . $id];
                $femaleStaffCount = $_REQUEST['female_staff_count_' . $id];
                $todaysDate = $date = date('Y-m-d H:i:s');
                $updateSocialImpactScore = "UPDATE social_impact_score SET total_number_of_staff ='$staffCount', total_number_of_female_staff = '$femaleStaffCount', update_date ='$todaysDate', called='1' where id = '$id'";
                $db->query($updateSocialImpactScore);
            } else {
                $message = "Staff count or Female staff count is empty for the id $id";
                $messageType = "FAILURE";
            }
            $message = "Application employee count is updated successfully";
            $messageType = "SUCCESS";

            //change
            header("Location:" . $environment . "/index.php?module=Cases&action=Social_impact_score&messageType=$messageType&Message=$message");
        }
    }
}

?>