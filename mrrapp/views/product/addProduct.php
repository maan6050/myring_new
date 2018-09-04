<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    // echo "<pre>";
    // print_r($product);
?>
<div class="container">
    <h1 class="page-title"><?= $title; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
    <form name="product_form" id="product_form" method="post" action="<?= base_url('Product/productformSubmit'); ?>">
        <input type="hidden" name="__auth_key" value="4c4d33303139372e31342e3038352e3535393231">
        <input type="hidden" name="__confirmation_page" value="">
        <input type="hidden" name="__email_subject" value="">
        <input type="hidden" name="PROD_ID" value="<?php if(isset($_GET["ID"])) { echo $product[0]->PROD_ID; } ?>">
        <div class="row"> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-sm-12 marginBottom10px">
                        <a href="<?= base_url('Product/productList'); ?>" class="myRingButton pull-right"> Product List</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-offset-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="PRODUCT_NAME">Brand Name <?php echo form_error('PRODUCT_NAME', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" type="text" size="25" name="PRODUCT_NAME" id="PRODUCT_NAME" value="<? if(isset($_GET["ID"])){ echo $product[0]->PROD_NAME; } else { echo set_value('f_name');} ?>" required="yes" title="Please fill in short.">
                        </div>
                        <div class="form-group">
                            <label for="PROVIDER">Provider <?php echo form_error('PROVIDER', '<span class="error">', '</span>'); ?></label>
                            <select class="form-control" name="PROVIDER" id="PROVIDER" title="Please select provider." required="yes" aria-describedby="ui-tooltip-0">
                                <option value="">Select Provider</option> <?php
                                foreach($providers as $provider) { ?>
                                    <option value="<?php echo $provider->NP_ID;?>" <?php if(isset($_GET["ID"])) { if($provider->NP_ID==$product[0]->NP_ID){ echo "selected"; } } ?>><?php echo $provider->NP_SHORT; ?></option> <?
                                }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="PRODUCT_TYPE">Product Type <?php echo form_error('PRODUCT_TYPE', '<span class="error">', '</span>'); ?></label>
                            <select class="form-control" name="PRODUCT_TYPE" id="PRODUCT_TYPE" title=" Please select product type." required>
                                <option value="">Select Product Types</option> <?php
                                foreach($getprod_types as $getprod_type) { ?>
                                    <option value="<?php echo $getprod_type->PROD_TYPE_ID;?>" <?php if(isset($_GET["ID"])) { if($getprod_type->PROD_TYPE_ID==$product[0]->PROD_TYPE_ID){ echo "selected"; } } ?>><?php echo $getprod_type->PROD_TYPE_NAME; ?></option> <?php
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Prod_Code">Product Country</label>
                            <select class="form-control" name="Prod_Code" id="Prod_Code" title="Please select country." required="yes" aria-describedby="ui-tooltip-0">
                                <option value="">Select Your Country</option> <?php
                                foreach($getCountryCodes as $getCountryCode) { 
                                    if(isset($_GET["ID"])){
                                        if($product[0]->PROD_CODE_MAIN !== "") {?>
                                            <option value="<?php echo $getCountryCode->CTY_ID ?>" <?php if($getCountryCode->CTY_ID == $product[0]->PROD_CODE_MAIN) { echo "selected"; } ?> ><?php echo $getCountryCode->CTY_NAME; ?></option> <?php
                                        }
                                    } else { ?>
                                        <option value="<?php echo $getCountryCode->CTY_ID ?>" <?php if($getCountryCode->CTY_ID == "1") { echo "selected"; } ?> ><?php echo $getCountryCode->CTY_NAME; ?> </option> <?php
                                    }    
                                }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="operator_Code">Operator <?php echo form_error('operator_Code', '<span class="error">', '</span>'); ?></label>
                            <select class="form-control" name="operator_Code" id="operator_Code" title="Please select operator." required="yes" aria-describedby="ui-tooltip-0">
                                <option value="">Select Operator</option>
                                <option value="1296">USA MOBILE SAMOA</option>
                            </select>
                            <!--<cfselect id="operator_Code" selected="#PROD_OPERATOR#" name="operator_Code" value="DID_RAND_ID" display="DID_ROUTE_NAME" class="require"  bind="cfc:cfc.product.getOperatorCode({Prod_Code@change})" bindonload="true" ></cfselect>-->
                        </div>
                        <div class="form-group">
                            <label for="Prod_Fee">Product Fee <?php echo form_error('Prod_Fee', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" type="text" size="30" maxlength="256" name="Prod_Fee" id="Prod_Fee" value="<? if(isset($_GET["ID"])){ echo $product[0]->PROD_FEE; } else { echo "0";} ?>" required="yes" title="Please fill in product fee.">
                        </div>
                        <div class="form-group pago_de" style="display:none;">
                            <label for="PROD_ACC_TYPE">Biller Type</label>
                            <input class="form-control" type="text" size="30" maxlength="256" name="PROD_ACC_TYPE" id="PROD_ACC_TYPE" value="<? if(isset($_GET["ID"])){ echo $product[0]->PROD_ACC_TYPE; } ?>">
                        </div>
                        <div class="form-group pago_de BLA011_tr" style="display:none;">
                            <label for="acc_mask">Account Mask</label>
                            <input class="form-control" type="text" size="30" maxlength="256" name="acc_mask" id="acc_mask" value="<? if(isset($_GET["ID"])){ echo $product[0]->PROD_ACC_MASK; } ?>">
                        </div>
                        <div class="form-group pago_de BLA011_tr" style="display:none;">
                            <label for="acc_length">Account length</label>
                            <input class="form-control" type="text" size="30" maxlength="256" name="acc_length" id="acc_length" value="<? if(isset($_GET["ID"])){ echo $product[0]->PROD_ACC_LENGTH; } ?>">
                        </div>
                        <div class="form-group pago_de" style="display:none;">
                            <label for="PROD_CHECK_BAL">Can Check Balance?</label>    
                            <div class="radio">
                                <label for="Yes"><input type="radio" name="PROD_CHECK_BAL" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_CHECK_BAL=="1"){ echo "checked"; } } ?> id="PROD_CHECK_BAL" value="1">Yes</label>
                            </div>
                            <div class="radio">
                                <label for="No"><input type="radio" name="PROD_CHECK_BAL" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_CHECK_BAL=="0"){ echo "checked"; } } ?> id="PROD_CHECK_BAL" value="0">No</label>
                            </div>
                        </div>
                        <div class="form-group pago_de" style="display:none;">
                            <label for="acc_length">Account Name Required?</label>    
                            <div class="radio">
                                <label for="Yes"><input type="radio" name="ACC_NAME_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->ACC_NAME_REQ=="1"){ echo "checked"; } } ?> id="ACC_NAME_REQ" value="1">Yes</label>
                            </div>
                            <div class="radio">
                                <label for="No"><input type="radio" name="ACC_NAME_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->ACC_NAME_REQ=="0"){ echo "checked"; } } ?> id="ACC_NAME_REQ" value="0">No</label>
                            </div>
                        </div>
                        <div class="form-group pago_de" style="display:none;">
                            <label for="PROD_CURRENCY">Local Currency</label>
                            <input class="form-control" type="text" size="30" maxlength="256" name="PROD_CURRENCY" id="PROD_CURRENCY" value="<? if(isset($_GET["ID"])){ echo $product[0]->PROD_CURRENCY; } ?>">
                        </div>
                        <div class="form-group pago_de" style="display:none;">
                            <label for="PROD_HOURS_FULLFILL">Hours to Full fill</label>
                            <input class="form-control" type="text" size="30" maxlength="256" name="PROD_HOURS_FULLFILL" id="PROD_HOURS_FULLFILL" value="<? if(isset($_GET["ID"])){ echo $product[0]->PROD_HOURS_FULLFILL; } else { echo "0"; } ?>">
                        </div>
                        <div class="form-group pago_de" style="display:none;">
                            <label for="supports_partial_payments">Supports Partial Payments</label>    
                            <div class="radio">
                                <label for="Yes"><input type="radio" name="supports_partial_payments" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_SUPPORT_PAYMENT=="1"){ echo "checked"; } } ?> id="supports_partial_payments" value="1">Yes</label>
                            </div>
                            <div class="radio">
                                <label for="No"><input type="radio" name="supports_partial_payments" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_SUPPORT_PAYMENT=="0"){ echo "checked"; } } ?> id="supports_partial_payments" value="0">No</label>
                            </div>
                        </div>
                        <div class="form-group sim_activate" style="display:none;">
                            <label for="PROD_SIMCARD_REQ">SIM Number</label>    
                            <div class="radio">
                                <label for="Yes"><input type="radio" name="PROD_SIMCARD_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_SIMCARD_REQ=="1"){ echo "checked"; } } ?> id="PROD_SIMCARD_REQ" value="1">Yes</label>
                            </div>
                            <div class="radio">
                                <label for="No"><input type="radio" name="PROD_SIMCARD_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_SIMCARD_REQ=="0"){ echo "checked"; } } ?> id="PROD_SIMCARD_REQ" value="0">No</label>
                            </div>
                        </div>
                        <div class="form-group sim_activate" style="display:none;">
                            <label for="PROD_SERIAL_REQ">Serial Number</label>    
                            <div class="radio">
                                <label for="Yes"><input type="radio" name="PROD_SERIAL_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_SERIAL_REQ=="1"){ echo "checked"; } } ?> id="PROD_SERIAL_REQ" value="1">Yes</label>
                            </div>
                            <div class="radio">
                                <label for="No"><input type="radio" name="PROD_SERIAL_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_SERIAL_REQ=="0"){ echo "checked"; } } ?> id="PROD_SERIAL_REQ" value="0">No</label>
                            </div>
                        </div>
                        <div class="form-group sim_activate" style="display:none;">
                            <label for="PROD_ZIP_REQ">Zip Code</label>    
                            <div class="radio">
                                <label for="Yes"><input type="radio" name="PROD_ZIP_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_ZIP_REQ=="1"){ echo "checked"; } } ?> id="PROD_ZIP_REQ" value="1">Yes</label>
                            </div>
                            <div class="radio">
                                <label for="No"><input type="radio" name="PROD_ZIP_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_ZIP_REQ=="0"){ echo "checked"; } } ?> id="PROD_ZIP_REQ" value="0">No</label>
                            </div>
                        </div>
                        <div class="form-group sim_activate" style="display:none;">
                            <label for="PROD_AREACODE_REQ">Area Code</label>    
                            <div class="radio">
                                <label for="Yes"><input type="radio" name="PROD_AREACODE_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_AREACODE_REQ=="1"){ echo "checked"; } } ?> id="PROD_AREACODE_REQ" value="1">Yes</label>
                            </div>
                            <div class="radio">
                                <label for="No"><input type="radio" name="PROD_AREACODE_REQ" <?php if(isset($_GET["ID"])) { if($product[0]->PROD_AREACODE_REQ=="0"){ echo "checked"; } } ?> id="PROD_AREACODE_REQ" value="0">No</label>
                            </div>
                        </div>
                        <?php if(isset($_GET["ID"])){ ?>
                                <input type="hidden" name="ID" value="<?php echo $_GET["ID"]; ?>";>
                        <?php } ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" name="submit" class="btn btn-primary myRingButton">Submit</button>
                                <button type="reset" name="reset" class="btn btn-primary myRingButton">Reset</button>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </form>
</div>
<div class="alert_message_container" id="alert_message_container">
    <div class="alert_message" id="alert_message"></div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#product_form').validate({
            errorPlacement: function(error, element){
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });
        if($("#PROVIDER option[value='"+$("#PROVIDER").val()+"']").html()=="REGALI"){
			$(".pago_de").show();
		}else if($("#PROVIDER option[value='"+$("#PROVIDER").val()+"']").html()=="PPC"){
			$(".sim_activate").show();
		}else if($("#PROVIDER option[value='"+$("#PROVIDER").val()+"']").html()=="BLA011"){
			$(".BLA011_tr").show();
		}
		else{
			$(".pago_de,.sim_activate,.BLA011_tr").hide();
		}
		$("#PROVIDER").change(function(){
			if($("#PROVIDER option[value='"+$(this).val()+"']").html()=="REGALI"){
				$(".pago_de").show();
			}else if($("#PROVIDER option[value='"+$(this).val()+"']").html()=="PPC"){
				$(".sim_activate").show();
			}else if($("#PROVIDER option[value='"+$("#PROVIDER").val()+"']").html()=="BLA011"){
				$(".BLA011_tr").show();
			}
			else{
				$(".pago_de,.sim_activate,.BLA011_tr").hide();
			}
		})
    });
</script>

