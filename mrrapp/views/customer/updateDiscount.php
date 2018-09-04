<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $URLCompany = 0;
    if(isset($_GET["filter"])){ $filter = $_GET["filter"]; } else { $filter = ""; }
?>
<div class="container">
    <h4 class="modal-title marginBottom10px">Update Discounts <?php 
        if(isset($_GET['company']) AND $_GET['company']!="") { 
            echo "for ".strtoupper($_GET['company']); 
            $URLCompany = 1; 
        } ?>
    </h4>
    <div class="row">
        <form action="#" method=post>
            <div class="col-sm-5 marginBottom10px">
                <div class="row">
                    <div class="col-sm-5">
                        <select class="form-control" name="PRODUCT_TYPE" id="PRODUCT_TYPE" title="Filter by Product Type" style="width:208px;" onchange="javascript:return filterBy($(this).val());">
                            <option value="">Select Product Types</option> <?php
                            foreach($getprod_types as $getprod_type) { 
                                if((($_COOKIE['user_type'] !== "956314127503977533")  AND ($search_by_type !== "25") AND ($search_by_type !== "20" )) OR ($_COOKIE['user_type'] == "956314127503977533")){ ?>
                                    <option value="<?php echo $getprod_type->PROD_TYPE_ID;?>" <?php if($search_by_type ==  $getprod_type->PROD_TYPE_ID) { echo "selected"; } ?>><?php echo $getprod_type->PROD_TYPE_NAME; ?></option> <?php
                                }
                            } ?>
                        </select>
                    </div> 
                </div>   
            </div>
            <div class="col-sm-7 marginBottom10px"> 
                <?php
                if(!empty($getProducts)){
                    if((($_COOKIE['user_type'] !== "956314127503977533")  AND ($search_by_type !== "25") AND ($search_by_type !== "20" )) OR ($_COOKIE['user_type'] == "956314127503977533")){
                        ?><input type="submit" name="update_disc" class="button myRingButton pull-right marginLeft10px" value="Update All" /> <?php
                    } 
                    if($acc_type == 4){ ?>
                        <input type="submit" name="Manual_on_all" class="btn btn-primary myRingButton pull-right marginLeft10px" value="Manual ON all" />
                        <input type="submit" name="Manual_off_all" class="btn btn-primary myRingButton pull-right marginLeft10px" value="Manual OFF all" /> <?php
                    }	
                }
                if(($_COOKIE['user_type'] == "125458968545678354")){
                    if($getcustomer_data[0]->STORE_TEMPLATE == $acc_enc){
                        $color = "style='background:green;background-color:green;'";
                        $text = "Selected As Template";
                        $js = "";
                    } else {
                        $color = "";
                        $text = "Select As Template";
                        $js = "onClick='javascript:return select_templates();'";
                        if(($getcustomer_data[0]->STORE_TEMPLATE !== "") AND ($getcustomer_data[0]->STORE_TEMPLATE > 0)){ ?>
                            <input type="button" name="apply_template" class="btn btn-primary myRingButton pull-right marginLeft10px" value="Apply Template" onClick="return confirmApplyTemp();" /> <?php
                        }
                    } ?>
                    <input type="button" name="select_template" class="btn myRingButton pull-right marginLeft10px" <?php echo $color;?>  value="<?php echo $text;?>" <?php echo $js; ?> /> <?php
                } ?>
                <a href="<?= base_url('Customer/viewList'); ?>" class="myRingButton pull-right marginLeft10px">Customer list</a>
            </div> <?php
        if((($_COOKIE['user_type'] !== "956314127503977533")  AND ($search_by_type !== "25") AND ($search_by_type !== "20" )) OR ($_COOKIE['user_type'] == "956314127503977533")){ ?>
            <div class="col-sm-12">
                <div class="table-container">
                    <table id="update-discount" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <td>Product Name</td>
                                <?php
                                    if(($_COOKIE['user_type'] == "638545125236524578") OR ($_COOKIE['user_type'] == "956314127503977533")){ ?>
                                        <td>Provider</td> <?php 
                                    }?>    
                                </td>
                                <td>Product Type</td>
                                <td>My Commision</td>
                                <td>Commision</td>
                                <td>Status</td> <?php
                                    if(($acc_type !== "1") AND ($acc_type < "5")){ ?>
                                        <td>Manual</td> <?php 
                                    }?>    
                                </td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php
                            $myList = "";
                            $myArray = Array();
                            foreach($getProducts as $getProduct){
                                if($myList !== ""){
                                    $myList = $myList.",".$getProduct->PROD_ID;
                                } else {
                                    $myList = $getProduct->PROD_ID;
                                }
                                if(!in_array($getProduct->PROD_ID, $myArray, true)){
                                    array_push($myArray, $getProduct->PROD_ID); ?>
                                    <tr id="tr_<?php echo $getProduct->PROD_ID ; ?>">
                                        <td><?php echo $getProduct->PROD_NAME; ?></td> <?php
                                        if(($_COOKIE['user_type'] == "638545125236524578") OR ($_COOKIE['user_type'] == "956314127503977533")){ ?>
                                            <td><?php echo $getProduct->NP_SHORT; ?></td> <?php
                                        } ?>
                                        <td><?php echo $getProduct->PROD_TYPE_NAME; ?></td>
                                        <td>
                                            <?php echo $getProduct->PROD_DISCOUNT." %";?>
                                            <input type="hidden" name="orig_commision_<?php $getProduct->PROD_ID; ?>" value="<?php echo $getProduct->PROD_DISCOUNT; ?>" id="orig_commision" />
                                        </td> <?php
                                        if($_GET["acc_enc"] == $getProduct->ACCOUNT){
                                            $commision_val = $getProduct->PROD_COMM;
                                            $status_val = $getProduct->COMM_STATUS;
                                        }  else {
                                            $commision_val = "";
                                            $status_val = "";
                                        } ?>
                                        <td>
                                            <input type="text" value="<?php echo $commision_val;?>" size="15" maxlength="256" name="commision_<?php $getProduct->PROD_ID; ?>" id="commision" > %
                                            <div id="messages" style="text-align:left;margin-left: 15px;"></div>
                                        </td>
                                        <td>
                                            <input type="checkbox" value="1" name="prod_status_<?php $getProduct->PROD_ID; ?>" <?php if($status_val == "1") { echo "checked"; } ?> id="prod_status" > Active
                                        </td> <?php
                                        if(($acc_type !== "1") AND ($acc_type < "5")){ ?>
                                            <td> <?php
                                                if($getProduct->MAN_STATUS == "1"){ ?>
                                                    <input type="checkbox" onchange="javascript:return  window.location.assign('updateDiscount?acc_enc=<?php echo $_GET["acc_enc"]; if($URLCompany == "1") { ?>&company=<?php $_GET["company"] ; } ?>&PROD_ID=<?php echo $getProduct->PROD_ID ?>&vals=0');" checked data-toggle="toggle"><?php
                                                }else { ?>
                                                    <input type="checkbox" onchange="javascript:return  window.location.assign('updateDiscount?acc_enc=<?php echo $_GET["acc_enc"]; if($URLCompany == "1") { ?>&company=<?php $_GET["company"] ; } ?>&PROD_ID=<?php echo $getProduct->PROD_ID ?>&vals=0');" data-toggle="toggle"><?php
                                                } ?>
                                            </td> <?php
                                        } ?>
                                        <td>
                                            <input type="hidden" name="productID" value="<?php echo $getProduct->PROD_ID ?>">
                                            <input type="button" class="btn myRingButton" value="Update" name="UpdateComm" onclick="javascript:return submitForm(<?php echo $getProduct->PROD_ID ?>);" />
                                        </td>
                                    </tr> <?php    
                                }
                            }
                            /*if($_COOKIE['user_type'] !== "956314127503977533"){
                                if(!empty($getfreshProducts)){
                                    foreach($getfreshProducts as $getfreshProduct){ ?>
                                        <tr id="tr_<?php echo $getfreshProduct->PROD_ID ; ?>">
                                            <td><?php echo $getfreshProduct->PROD_NAME; ?></td> <?php
                                            if($_COOKIE['user_type'] == "638545125236524578") { ?>
                                            <td><?php echo $getfreshProduct->NP_SHORT; ?></td> <?php
                                            } ?>
                                            <td><?php echo $getfreshProduct->PROD_TYPE_NAME;?></td>
                                            <td>
                                            <?php echo $getfreshProduct->PROD_DISCOUNT."%";?>
                                                <input type="hidden" name="orig_commision_<?php echo $getfreshProduct->PROD_ID; ?>" value="<?php echo $getfreshProduct->PROD_DISCOUNT;?>" id="orig_commision" />
                                            </td>
                                            <td align="center"><input type="text" value="" size="15" maxlength="256" name="commision_<?php echo $getfreshProduct->PROD_ID;?>" id="commision"  onKeyPress="isNFloat(event)"> %</td>
                                            <td align="center">
                                                <input type="checkbox" value="1" name="prod_status_<?php echo $getfreshProduct->PROD_ID;?>" id="prod_status" > Active
                                            </td>
                                            <td align="center"> <?php
                                                if($getfreshProduct->MAN_STATUS == "1"){ ?>
                                                    <input type="button" class="btn myRingButton" name="active" value="ON" onclick="javascript:return  window.location.assign('updateDiscount?acc_enc=<?php echo $_GET["acc_enc"]; if($_GET["company"] == "1") { ?>&company=<?php $_GET["company"] ; } ?>&PROD_ID=<?php echo $getfreshProduct->PROD_ID ?>&vals=0');" /> <?php
                                                }else { ?>
                                                    <input type="button" class="btn myRingButton" name="active" value="OFF" onclick="javascript:return window.location.assign('update_discounts.cfm?acc_enc=<?php echo $_GET["acc_enc"]; if($_GET["company"] == "1") { echo "&company=".$_GET["company"]; }?>&PROD_ID=<?php echo $getfreshProduct->PROD_ID;?>&vals=1');" /> <?php
                                                } ?>
                                                <input type="checkbox" checked data-toggle="toggle">
                                            </td>
                                            <td align="center">
                                                <input type="hidden" name="productID" value="<?php echo $getfreshProduct->PROD_ID;?>">
                                                <input type="button" class="button" value="Update" name="UpdateComm" onclick="javascript:return submitForm(<?php echo $getfreshProduct->PROD_ID;?>);" />
                                            </td>
                                        </tr>
                                    }
                                }
                            }*/?>
                        </tbody>
                    </table> <?php
                    if(!empty($getProducts)) { ?>
                        <input type="submit" name="update_disc" class="btn myRingButton pull-right" value="Update All" /> <?php
                    } ?>
                    <input type="hidden" value="<?php echo $_GET["acc_enc"]; ?>" name="acc_enc">
                </div>
            </div>    
        </form>
        <form action="#" method="post" id="discount_form" style="display:none;">
            <input type="hidden" value="" name="my_commision" id="my_commision" />
            <input type="hidden" value="" name="commision_val" id="commision_val">
            <input type="hidden" value="" name="status_val" id="status_val">
            <input type="hidden" value="" name="prod_id_val" id="prod_id_val">
            <input type="hidden" value="" name="prod_type" id="prod_type">
            <input type="hidden" value="" name="addUpdateComm" id="addUpdateComm">
            <input type="hidden" value="<?php echo $_GET['acc_enc'];?>" name="acc_enc">
        </form> <?php
        } else { ?>
            <br /><br />
            <h4>Sorry, you have no permissions to view this type of products. </h4> <?php
        } ?> 
    </div>   
</div>
<div class="alert_message_container" id="alert_message_container">
    <div class="alert_message" id="alert_message"></div>
</div>
<?php
	if(isset($msg)) { 
        ?><script type="text/javascript"> 
			document.getElementById("alert_message").innerHTML="<?php echo $msg; ?>";
			document.getElementById("alert_message").style.opacity="1";
			document.getElementById("alert_message").style.marginTop="170px";
			setTimeout(function(){ document.getElementById("alert_message").style.opacity="0"; }, 3000);
		</script><?
	}
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#update-discount').DataTable({
            "aoColumnDefs" : [ 
                {"aTargets" : [3], "sClass":  "custom-td"},
                {"aTargets" : [4], "sClass":  "custom-td"},
                {"aTargets" : [5], "sClass":  "custom-td"},
                {"aTargets" : [6], "sClass":  "custom-td"} 
			]
		});
    });

    function filterBy($val){
		if($val == "")
		{
			window.location.assign("updateDiscount?acc_enc=<?php echo $_GET["acc_enc"] ?>&company=<?php echo $_GET["company"] ?>");
		}else{
			window.location.assign("updateDiscount?acc_enc=<?php echo $_GET["acc_enc"] ?>&company=<?php echo $_GET["company"] ?>&filter="+$val);
		}
    }

    function submitForm(prod_id)
	{
        $my_comm = $("#tr_"+prod_id+" #orig_commision").val();
		$new_comm = $("#tr_"+prod_id+" #commision").val();
        if($new_comm == "")
		{
			$("#tr_"+prod_id+" #messages").html('<div class="error">Please fill.</div>');
			setTimeout('$("#messages").html("")',5000);
			$("#tr_"+prod_id+" #commision").focus();
			return false;
		}
		if(isNaN($new_comm))
		{
			$("#tr_"+prod_id+" #messages").html('<div class="error">Should be numeric.</div>');
            setTimeout('$("#messages").html("")',5000);
			$("#tr_"+prod_id+" #commision").focus();
			return false;
		}
		if (Math.round($my_comm * 100) < Math.round($new_comm * 100))
		{
			$("#tr_"+prod_id+" #messages").html('<div class="error">Greater than Your Commision.</div>');
            setTimeout('$("#messages").html("")',5000);
			$("#tr_"+prod_id+" #commision").focus();
			return false;
		}
		
		$("#prod_id_val").val(prod_id);
		$("#my_commision").val($my_comm);
		$("#commision_val").val($new_comm);

		if($("#tr_"+prod_id+" #prod_status").is(':checked'))
		{
			$("#status_val").val(1);
		}else
		{
			$("#status_val").val(0);
		}
        $("#discount_form").submit();

        function select_templates(){
            window.location.assign("updateDiscount?acc_enc=<cfoutput><?php echo $acc_enc; ?>&template=1");
        }
        function confirmApplyTemp(){
            if(confirm("Are you sure you want to apply selected template to current store? Changes can not be reverted.")){
                window.location.assign("update_discounts.cfm?acc_enc=<?php echo $acc_enc; ?>&apply=1");
            }
            return false;
        }
	}
</script>
