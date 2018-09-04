<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    // echo "<pre>";
    // print_r($single_product_plan);
    // die();
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<form name="plan_discount_form" id="plan_discount_form" method="post" action="<?= base_url('Product/productPlanformSubmit'); ?>">
        <input type="hidden" name="__auth_key" value="4c4d33303139372e31342e3038352e3535393231">
	    <input type="hidden" name="__confirmation_page" value="">
	    <input type="hidden" name="__email_subject" value="">
        <input type="hidden" name="plan_id" value="<?php if(isset($_GET["plan_id"])){ echo $_GET["plan_id"]; } else { echo "0"; } ?>">
        <input type="hidden" name="discounts_count" id="discounts_count" value="<?php if(isset($_GET["plan_id"])){ echo sizeof($single_product_plan)-1; } else { echo "1"; } ?>" />
        <div class="row"> 
			<div class="col-sm-offset-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div id="tr_top">
                    <div class="form-group">
                        <label for="prod_plan">Product Plan <?php echo form_error('prod_plan', '<span class="error">', '</span>'); ?></label>
                        <input class="form-control" type="text" size="25" name="prod_plan" id="prod_plan" value="<? if(isset($_GET["plan_id"])){ echo $single_product_plan[0]->PPLAN_NAME; } ?>" required="yes" title="Please fill in prod plans.">
                    </div>
                    <div class="form-group">
                        <label for="status">Status <?php echo form_error('status', '<span class="error">', '</span>'); ?></label>
                        <select class="form-control" name="status" id="status" title="Please select status." required="yes" aria-describedby="ui-tooltip-0">
                            <option>Please Select Status</option>
                            <option value="1" <?php if(isset($_GET["plan_id"])) { if($single_product_plan[0]->PPLAN_STATUS==1){ echo "selected"; } } ?>>Enabled</option>
                            <option value="0" <?php if(isset($_GET["plan_id"])) { if($single_product_plan[0]->PPLAN_STATUS==0){ echo "selected"; } } ?>>Disabled</option> 
                        </select>
                    </div> 
                <?php
                if(isset($_GET["plan_id"])){ ?>
                    <div class="col-sm-12 col-xs-12 text-right">
                        <i style="cursor:pointer; display:<?php if(isset($_GET["plan_id"]) AND (sizeof($single_product_plan)>1)) { echo 'inline'; } else { echo 'none'; } ?>;" id="lessDiscount" class="fa fa-minus-circle fa-2x marginLeft10px marginTop10px" aria-hidden="true"></i>
                        <i style="cursor:pointer" id="addMore" class="fa fa-plus-circle fa-2x marginLeft10px marginTop10px" aria-hidden="true"></i>
                    </div> <?php
                    $totalProdPlanRecords = (sizeof($single_product_plan)-1);
                    for($i=$totalProdPlanRecords;$i>="0";$i--){ ?>
                        <div class="form-group" id="tr_<?php echo $i; ?>">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="disc_from_<?php echo $i; ?>">From</label>
                                    <input class="form-control" type="text" size="25" name="disc_from_<?php echo $i; ?>" id="disc_from_<?php echo $i; ?>" value="<? if(isset($_GET["plan_id"])){ echo $single_product_plan[$i]->PPLAN_FROM; } ?>" required="yes" title="Should be Numeric between 0.00 to 99.99"/>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="disc_to_<?php echo $i; ?>">To</label>
                                    <input class="form-control" type="text" size="25" name="disc_to_<?php echo $i; ?>" id="disc_to_<?php echo $i; ?>" value="<? if(isset($_GET["plan_id"])){ echo $single_product_plan[$i]->PPLAN_TO; } ?>" required="yes" title="Should be Numeric between 0.00 to 99.99"/>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="disc_points_<?php echo $i; ?>">Discount</label>
                                    <input class="form-control" type="text" size="25" name="disc_points_<?php echo $i; ?>" id="disc_points_<?php echo $i; ?>" value="<? if(isset($_GET["plan_id"])){ echo $single_product_plan[$i]->PPLAN_DISC; } ?>" required="yes" title="Should be Numeric between 0.01 to 99.99"/>
                                </div>
                                <input type="hidden" name="disc_id_<?php echo $i; ?>" class="disc_id_field" value="<? if(isset($_GET["plan_id"])){ echo $single_product_plan[$i]->PPLAN_DISC_ID; }?>" pattern="[0-9]?[0-9]?(\.[0-9][0-9]?)?" />
                            </div>
                        </div> <?php
                    } 
                } else { ?>
                    <div class="form-group" id="tr_1">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label for="disc_from_1">From</label>
                                <input class="form-control" type="text" size="25" name="disc_from_1" id="disc_from_1" value="" required="yes" title="Should be Numeric between 0.00 to 99.99"/>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label for="disc_to_1">To</label>
                                <input class="form-control" type="text" size="25" name="disc_to_1" id="disc_to_1" value="" required="yes" title="Should be Numeric between 0.00 to 99.99"/>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label for="disc_points_1">Discount</label>
                                <input class="form-control" type="text" size="25" name="disc_points_1" id="disc_points_1" value="" required="yes" title="Should be Numeric between 0.01 to 99.99"/>
                            </div>
                            <div class="col-sm-12 col-xs-12 text-right">
                                <i style="cursor:pointer" id="lessDiscount" class="fa fa-minus-circle fa-2x marginLeft10px marginTop10px" aria-hidden="true"></i>
                                <i style="cursor:pointer" id="addMore" class="fa fa-plus-circle fa-2x marginLeft10px marginTop10px" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div> <?php
                } ?>
                </div>
                <input type="hidden" name="deleted_rows" id="deleted_rows" value="" />
				<button type="submit" name="submit" class="btn btn-primary myRingButton pull-left"><? if(isset($_GET["plan_id"])){ echo "Update";} else { echo "Submit"; } ?></button>
                <a href="<?= base_url('Product/productPlans'); ?>" class="myRingButton pull-left marginLeft10px">Cancel</a>
			</div>	
		</div>
	</from>
	<br>
	<div class="table-container">
		<table id="product_plans" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Plan Name</td>
					<td>Plan Date</td>
					<td>Status</td>
                    <td>Action</td>
				</tr>
			</thead>
			<tbody> <?php
				foreach($product_plans as $product_plan) 
				{  ?>
					<tr>
						<td><?php echo $product_plan->PPLAN_NAME; ?></td>
                        <td><?php  
                            $origin_date = $product_plan->PPLAN_DATE;
                            echo $newDate = date('Y-m-d', strtotime($origin_date));?>
                        </td>
                        <td><?php 
							if($product_plan->PPLAN_STATUS == "1"){
								echo "YES";	
							} else {
								echo "NO";
							} ?>
						</td>
						<td>
							<a href="<?php echo base_url('Product/productPlans?plan_id='.$product_plan->PPLAN_ID) ?>" title=" Edit Product Plan"><i class="fa fa-edit fa-2x" aria-hidden="true"></i></a>
						</td>
					</tr> <?php
				} ?>
			</tbody>
		</table>
	</div>
</div>	
<div class="alert_message_container" id="alert_message_container">
    <div class="alert_message" id="alert_message"></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#product_plans').DataTable({
			"aoColumnDefs" : [ 
				{"aTargets" : [1], "sClass":  "custom-td"},
                {"aTargets" : [2], "sClass":  "custom-td"},
                {"aTargets" : [3], "sClass":  "custom-td"}
			]
		});

		$('#plan_discount_form').validate({
            errorPlacement: function(error, element){
                // Adiciona el error dentro de la etiqueta asociada.
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });

        $("#plan_discount_form").submit(function(){
			$return = true;
			$("#plan_discount_form input[name^=disc_points_]").each(function(){
				$thisval = $(this).val();
				if(isNaN($thisval)){
					$(this).focus();
					$return = false;
				}
				$if = $thisval > 0 && $thisval < 100;
				if( !$if ){
					$(this).focus();
					$return = false;
				}
			});
			return $return;
		});

        
    });

    $(document).ready(function(){
        $("#addMore").click(function(){
			var $curr_count = $("#discounts_count").val();
            var $new_count = parseInt($curr_count) + parseInt(1);
            $("#tr_top").after('<div class="form-group" id="tr_'+ $new_count +'"><div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label for="disc_from_'+ $new_count +'">From</label><input class="form-control" type="text" size="25" name="disc_from_'+ $new_count +'" id="disc_from_'+ $new_count +'" value="" required="yes" title="Should be Numeric between 0.00 to 99.99"/></div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label for="disc_to_'+ $new_count +'">To</label><input class="form-control" type="text" size="25" name="disc_to_'+ $new_count +'" id="disc_to_'+ $new_count +'" value="" required="yes" title="Should be Numeric between 0.00 to 99.99"/></div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label for="disc_points_'+ $new_count +'">Discount</label><input class="form-control" type="text" size="25" name="disc_points_'+ $new_count +'" id="disc_points_'+ $new_count +'" value="" required="yes" title="Should be Numeric between 0.01 to 99.99"/></div><div class="col-sm-12 col-xs-12"></div></div></div></div>');
            $("#discounts_count").val($new_count);
			if($new_count > 1){
				$("#lessDiscount").show();
			}
        });

         $("#lessDiscount").click(function(){
            $curr_count = parseInt($("#discounts_count").val());
            $delete_row = $("#tr_"+$curr_count).find(".disc_id_field").val();
            if($delete_row != "" && $delete_row > "0")
            {
                $curr_val = $("#deleted_rows").val();
                if($curr_val != ""){
                    $("#deleted_rows").val($curr_val + "," + $delete_row);
                }else{
                    $("#deleted_rows").val($delete_row);
                }
            }
            if($curr_count > 1)
            {
                $new_count = $curr_count - 1;
                $("#tr_"+ $curr_count).remove();
                $("#discounts_count").val($new_count);
                if($new_count == 1)
                {
                    $(this).hide();
                }
            }
            else{
                $(this).hide();
            }				
        });
    });
</script>