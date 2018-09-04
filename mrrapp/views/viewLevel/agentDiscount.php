<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $allow_prod_types = $getcust_prod_types[0]->allow_prod_types;
    $explode_allow_prod_types = explode(",",$allow_prod_types);
    $size_of_allow_prod_types = sizeof($explode_allow_prod_types);
    $urlCompany = "0";
?>
<div class="container">
    <h4 class="modal-title marginBottom10px">Discounts <?php 
        if(isset($_GET['company']) AND $_GET['company']!="") { 
            echo "for ".strtoupper($_GET['company']);  
        } else {
            $urlCompany = "1";
        } ?>
    </h4>
    <div class="row">
        <div class="col-sm-6 marginBottom10px">
            <div class="row">
                <div class="col-sm-5">
                    <select class="form-control" name="PRODUCT_TYPE" id="PRODUCT_TYPE" title="Filter by Product Type" style="width:208px;" onchange="filterBy($(this).val());">
                        <option value="">Select Product Types</option> <?php
                        foreach($getprod_types as $getprod_type) { ?>
                            <option value="<?php echo $getprod_type->PROD_TYPE_ID;?>" <?php if($search_by_type == $getprod_type->PROD_TYPE_ID) { echo "selected"; } ?>><?php echo $getprod_type->PROD_TYPE_NAME; ?></option> <?php
                        } ?>
                    </select>
                </div> 
            </div>   
        </div>
        <div class="col-sm-6 marginBottom10px"> 
            <a href="<?= base_url('ViewLevel/viewList'); ?>" class="myRingButton pull-right marginLeft10px">Back to <?php echo $account_title; ?></a> <?php
            if($_COOKIE['user_type'] !== "415285967837575867"){ ?>
                <button onclick="manage_prod_types('<?php echo $_GET["company"]; ?>');" class="myRingButton pull-right marginLeft10px" data-toggle="modal" data-target="#manageProductTypes">Manage Product Types</button> <?php
            } ?>
        </div>
    </div>    
    <div class="table-container">
        <table id="update-discount" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <td>Product Name</td>
                    <?php
                        if(($_COOKIE['user_type'] == "638545125236524578") OR ($_COOKIE['user_type'] == "956314127503977533") OR ($_COOKIE['user_type'] == "525874964125375325")){ ?>
                            <td>Provider</td> <?php 
                        }?>    
                    </td>
                    <td>Product Type</td>
                    <td>My Commision</td>
                    <td>Commision</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody> 
                <?php
                $myList = "";
                $myArray = Array();
                foreach($getProducts as $getProduct){
                    if($myList !== ""){
                        $myList = $myList. ",".$getProduct->PROD_ID;
                    } else {
                        $myList = $getProduct->PROD_ID;
                    }
                    if(!in_array($getProduct->PROD_ID, $myArray, true)){
                        array_push($myArray, $getProduct->PROD_ID); ?>
                        <tr id="tr_<?php echo $getProduct->PROD_ID ; ?>">
                            <td><?php echo $getProduct->PROD_NAME; ?></td> <?php
                            if(($_COOKIE['user_type'] == "638545125236524578") OR ($_COOKIE['user_type'] == "956314127503977533") OR ($_COOKIE['user_type'] == "525874964125375325")){ ?>
                                <td><?php echo $getProduct->NP_SHORT; ?></td> <?php
                            } ?>
                            <td><?php echo $getProduct->PROD_TYPE_NAME; ?></td>
                            <td>
                                <?php echo $getProduct->PROD_DISCOUNT."%";?>
                            </td> <?php
                            if($_GET['acc_enc'] == $getProduct->ACCOUNT){
                                $commision_val = $getProduct->PROD_COMM;
                                $status_val = $getProduct->COMM_STATUS;
                            } else {
                                $commision_val = "";
                                $status_val = "";
                            } ?>
                            <td><?php echo $commision_val; ?></td>
                            <td> <?php
                                if($status_val == "1"){ 
                                    echo "Active" ;
                                } else { 
                                    echo "Inactive";
                                } ?>
                            </td>
                        </tr> <?php    
                    }
                }?>
            </tbody>
        </table> 
    </div>
    <? //////////////////Manage Product Types Modal/////////////////?>
    <div class="modal fade" id="manageProductTypes" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form name="manageProductTypesForm" id="manageProductTypesForm" method="post" action="<?php echo base_url('ViewLevel/agentDiscounts?acc_enc='.$_GET["acc_enc"].'&company='.$_GET["company"]); ?>">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Manage Product Types</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row"> 
                            <div class="col-sm-6">
                                <div class="form-group"> <?php
                                    $count = "1";
                                    foreach($manage_getprod_types as $manage_getprod_type) { 
                                        if(($count == "10") OR ($count == "20")){
                                            echo "</div></div><div class='col-sm-6'><div class='form-group'>";
                                            
                                        }?>
                                        <label class="checkbox-inline" for="id_<?php echo $manage_getprod_type->PROD_TYPE_ID; ?>"><?php echo $manage_getprod_type->PROD_TYPE_NAME; ?></label>
                                        <input type="checkbox" name="active" <?php 
                                        if(isset($_GET["acc_enc"])) { 
                                            for($i=0;$i<$size_of_allow_prod_types;$i++){
                                                if($manage_getprod_type->PROD_TYPE_ID==$explode_allow_prod_types[$i]){ 
                                                    echo "checked"; 
                                                } 
                                            }
                                        } ?> id="active" value="1"> <?php
                                        $count = $count+1;
                                    } ?>
                                </div>
                            </div>
                        </div>	
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default myRingButton pull-right marginLeft10px" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_balance_btn" class="btn btn-primary myRingButton pull-right" >Submit</button>
                    </div>
                </form>
            </div>
        </div>
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
		});
    });

    function filterBy($val){
		if($val == "")
		{
			window.location.assign("agentDiscounts?acc_enc=<?php echo $_GET["acc_enc"] ?>&company=<?php echo $_GET["company"] ?>");
		}else{
			window.location.assign("agentDiscounts?acc_enc=<?php echo $_GET["acc_enc"] ?>&company=<?php echo $_GET["company"] ?>&filter="+$val);
		}
    }

    //function manage_prod_types(){
    //     window.location.assign("agentDiscounts?acc_enc=<?php //echo $_GET["acc_enc"] ?>&company=<?php //echo $_GET["company"] ?>&filter="+$val);
    // }
</script>

