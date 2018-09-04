<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    // echo"<pre>";
    // print_r($getCountryCodes);
    // die();
?>
<div class="container">
    <h1 class="page-title"><?= $title; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
    <form name="customer_form" id="customer_form" method="post" action="<?= base_url('Customer/customerformSubmit'); ?>">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <?php
                if(isset($add_edit) AND ($add_edit !== "1")){ 
                     if($accountType == "2") { ?>
                        <div>
                            <div class="titulos_forms_blue">Security Information</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="cc_fee_enabled">CC Fee Enabled</label>
                                    <select class="form-control" name="cc_fee_enabled" id="cc_fee_enabled"  aria-describedby="ui-tooltip-0">
                                        <option value="1" <?php  if(isset($_GET["cust_enc"])) { if($customers[0]->PUBLIC_CUSTOMER=="1"){ echo "selected"; } } ?>>Enabled</option>
                                        <option value="0" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->PUBLIC_CUSTOMER=="0"){ echo "selected"; } }  ?>>Disabled</option> 
                                    </select>
                                </div>
                            </div>        
                        </div> <?php    
                    }
                } else { ?>
                    <div>
                        <div class="titulos_forms_blue">Configuration</div>
                    </div> 
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="v_enabled">Status</label>
                                <select class="form-control" name="v_enabled" id="v_enabled" title="Please select your status."  aria-describedby="ui-tooltip-0">
                                    <option value="1" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->ENABLED==1){ echo "selected"; } } ?>>Enabled</option>
                                    <option value="0" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->ENABLED==0){ echo "selected"; } } ?>>Disabled</option> 
                                </select>
                            </div>
                        </div> 
                    </div> <?php   
                    if($accountType == 2) { ?>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="cc_fee_enabled">CC Fee Enabled</label>
                                    <select class="form-control" name="cc_fee_enabled" id="cc_fee_enabled"  aria-describedby="ui-tooltip-0">
                                        <option value="1" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->PUBLIC_CUSTOMER==1){ echo "selected"; } } ?>>Enabled</option>
                                        <option value="0" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->PUBLIC_CUSTOMER==0){ echo "selected"; } } ?>>Disabled</option> 
                                    </select>
                                </div>
                            </div>        
                        </div> <?php 
                    }
                } ?>
                <div class="titulos_forms_blue">Contact Information</div>
                <div class="row"> 
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="f_name">First Name <?php echo form_error('f_name', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" name="f_name" id="f_name" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]->FIRST_NAME; } else { echo set_value('f_name');} ?>" title="Please fill in your First name." required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="l_name">Last Name <?php echo form_error('l_name', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" name="l_name" id="l_name" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]->LAST_NAME; } else { echo set_value('l_name');} ?>" title="Please fill in your last name." required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="v_fax">Work Phone No. <?php echo form_error('v_fax', '<span class="error">', '</span>'); ?></label>
                            <input type="number" name="v_fax" id="v_fax" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]->FAX; } else { echo set_value('v_fax');} ?>" class="form-control"  pattern="[0-9]{5,}" onkeypress="isNNumeric(event)" title="" aria-describedby="ui-tooltip-13" title="Please fill in Work Phone number." required>
                            
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="v_phone">Mobile Phone <?php echo form_error('v_phone', '<span class="error">', '</span>'); ?></label>
                            <input type="number" name="v_phone"  id="v_phone" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]->LOCAL_PHONE; } else { echo set_value('v_phone');} ?>" class="form-control"  pattern="[0-9]{5,}" onkeypress="isNNumeric(event)" title="" aria-describedby="ui-tooltip-13" title="Should be Numeric." required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                        <div class="form-group">
                            <label for="v_email">Email Address <?php echo form_error('v_email', '<span class="error">', '</span>'); ?></label>
                            <input type="email" class="form-control" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]->E_MAIL; } else { echo set_value('v_email');} ?>" name="v_email"  id="v_email" pattern="(([^<>()[\]\\.,;:\s@\&quot;]+(\.[^<>()[\]\\.,;:\s@\&quot;]+)*)|(\&quot;.+\&quot;))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))" message="Not a valid email address." required>
                        </div>
                    
                        <div class="form-group">
                            <label for="tax_id">Tax ID <?php echo form_error('tax_id', '<span class="error">', '</span>'); ?></label>        
                            <input type="text" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->USER_4; } else { echo set_value('tax_id');} ?>" class="form-control" name="tax_id"  id="tax_id" aria-describedby="ui-tooltip-37" title="Please fill in Tax ID field." required>
                            
                        </div>
                    
                        <div class="form-group">
                            <label for="f_company">Company Name <?php echo form_error('f_company', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" name="f_company" id="f_company" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->COMPANY; } else { echo set_value('f_company');} ?>" title="Please fill in your Store name." required>
                        </div>
                    
                        <div class="form-group">
                            <label for="v_question">Please select a Security Question. <?php echo form_error('v_question', '<span class="error">', '</span>'); ?></label>
                            <select class="form-control" name="v_question" id="v_question" aria-describedby="ui-tooltip-0" title="Please select your security Question." required>
                                <option value="">Select Your Question</option> <?php
                                foreach($getQuestionList as $getQuestion) { ?>
                                    <option value="<?php echo $getQuestion->SEC_Q_ID;?>" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->USER_2==$getQuestion->SEC_Q_ID){ echo "selected"; } } ?>><?php echo $getQuestion->SEC_QUESTION; ?></option> <?
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="v_answer">Security Answer is: <?php echo form_error('v_answer', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" name="v_answer" id="v_answer" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->USER_3; } else { echo set_value('v_answer');} ?>" title="Please answer your security Question." required >
                        </div>
                    </div>  
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="plan_prod">Select Plan <?php echo form_error('plan_prod', '<span class="error">', '</span>'); ?></label>
                            <select class="form-control" name="plan_prod" id="plan_prod" title="Please Select Product Plan." required>
                                <option value="">Select</option> <?php
                                foreach($get_all_prod_plands as $get_all_prod_pland) { ?>
                                    <option value="<?php echo $get_all_prod_pland->PPLAN_ID;?>" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->USER_6==$get_all_prod_pland->PPLAN_ID){ echo "selected"; } } ?>><?php echo $get_all_prod_pland->PPLAN_NAME; ?></option> <?
                                }?>
                            </select>
                        </div>
                        <div class="form-group" style="display: <?php if($_COOKIE["user_type"] == "125458968545678354") { echo "block"; } else { echo "none"; }?>">
                            <label for="TOUCHSCREEN">Touchscreen</label>
                            <input type="checkbox" name="TOUCHSCREEN" value="1" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->TOUCHSCREEN == "1") { echo "checked"; } }?> ></td>
                            <label for="THERMAL_RECEIPT">Thermal Print</label>
                            <input type="checkbox" name="THERMAL_RECEIPT" value="1" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->THERMAL_RECEIPT == "1"){ "checked"; } } ?> > </td>
                        </div>
                    </div>
                </div>
            <div class="titulos_forms_blue">Company Address</div>
            <div class="row">  
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="v_address1">Address</label>
                        <input type="text" class="form-control" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->ADDRESS1; } else { echo set_value('v_address1');} ?>" name="v_address1">
                    </div>
                    <div class="form-group">
                        <label for="v_cty">City</label>
                        <input type="text" class="form-control" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->CITY; } else { echo set_value('v_cty');} ?>" name="v_cty">
                    </div>
                    <div class="form-group">
                        <label for="v_zip">Zip Code</label>
                        <input type="text" class="form-control" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->POSTAL_CODE; } else { echo set_value('v_zip');} ?>" name="v_zip" id="v_zip">
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="v_suite">Suite/Office</label>
                        <input type="text" class="form-control" name="v_suite" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->ADDRESS2; } else { echo set_value('v_suite');} ?>">
                    </div>
                    <div class="form-group">
                        <label for="v_state">State/ Province</label>
                        <input type="text" class="form-control" name="v_state" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->STATE_REGION; } else { echo set_value('v_state');} ?>">
                    </div>
                    <div class="form-group">
                        <label for="v_country">Country <?php echo form_error('v_country', '<span class="error">', '</span>'); ?></label>
                        <select name="v_country" class="form-control" id="v_country" title="Please select your country" required >
                            <option value="">Select Your Country</option> <?php
                            foreach($getCountryCodes as $getCountryCode) { ?>
                                <option value="<?php echo $getCountryCode->CTY_ID; ?>" <?php if(isset($_GET["cust_enc"])) { if($customers[0]->COUNTRY == $getCountryCode->CTY_ID){ echo "selected"; } } ?>><?php echo $getCountryCode->CTY_NAME; ?></option> <?
                            }?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" name="latitude" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->USER_7; } else { echo set_value('latitude');} ?>" onkeypress="isNFloat(event)">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" name="longitude" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]->USER_8; } else { echo set_value('longitude');}  ?>" onkeypress="isNFloat(event)">
                    </div>
                </div>
            </div> <?php
            if($checkParentmoduleEnable == "1") { ?>
                <div class="titulos_forms_blue">Payment Module</div>   
                <div class="row">  
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="cc_enabled">Credit Card Payment Enabled?</label>
                            <div class="radio">
                                <label for="cc_enabled"><input type="radio" name="cc_enabled"  id="cc_enabled" value="1" <?php if(isset($_GET["cust_enc"]))  { if($customers[0]->CC_ENABLED=="1"){ echo "hello checked"; } } ?>>Yes</label>
                            </div>
                            <div class="radio">
                                <label for="cc_enabled"><input type="radio" name="cc_enabled" id="cc_enabled" value="0" <?php if(isset($_GET["cust_enc"]))  { if($customers[0]->CC_ENABLED=="0"){ echo "checked"; } } ?>>No</label>
                            </div>
                        </div>
                    </div>
                </div> <?php 
            } ?>
            <?php if(isset($_GET["cust_enc"])){ ?>
                    <input type="hidden" name="cust_enc" value="<?php echo $_GET["cust_enc"]; ?>";>
            <?php } ?>
            </div>
            <div class="col-xs-12">
                <? if(isset($_GET["cust_enc"])) { ?>
                    <button type="submit" name="submit" class="btn btn-primary myRingButton">Update</button>   
                <? } else { ?>
                    <button type="submit" name="submit" class="btn btn-primary myRingButton">Submit</button>
                <? }  ?>  
            </div>
        </div>    
    </form>
</div>
<div class="alert_message_container" id="alert_message_container">
    <div class="alert_message" id="alert_message"></div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#customer_form').validate({
            errorPlacement: function(error, element){
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });
    });
</script>

