<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   // echo "<pre>";
    // print_r($customers);
    // die();

?>
<div class="container">
    <h1 class="page-title"><?= $title; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
    <form name="customer_form" id="customer_form" method="post" action="<?= base_url("Profile/profileFormUpdate"); ?>">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                <div>
                    <div class="titulos_forms_blue">Personal Information</div>
                </div>
                <div class="row"> 
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="f_name">First Name <?php echo form_error('f_name', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" name="f_name" id="f_name" value="<? if(isset($cust_id)){ echo $customers[0]->FIRST_NAME; } ?>" title="Please fill in your First name." required>

                            <input type="hidden" name="__auth_key" value="4c4d33303139372e31342e3038352e3535393231">
	                        <input type="hidden" name="__confirmation_page" value="">
	                        <input type="hidden" name="__email_subject" value="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="l_name">Last Name <?php echo form_error('l_name', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" name="l_name" id="l_name" value="<? if(isset($cust_id)){ echo $customers[0]->LAST_NAME; } ?>" title="Please fill in your last name." required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="v_fax">Work Phone No. <?php echo form_error('v_fax', '<span class="error">', '</span>'); ?></label>
                            <div class="input-container">
                                <span class="country_code input_custom_icon"></span>
                                <input type="number" name="v_fax" id="v_fax" value="<? if(isset($cust_id)){ echo $customers[0]->FAX; } ?>" class="form-control"  pattern="[0-9]{5,}" onkeypress="isNNumeric(event)" title="" aria-describedby="ui-tooltip-13" title="Please fill in Work Phone number." required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="v_phone">Mobile Phone <?php echo form_error('v_phone', '<span class="error">', '</span>'); ?></label>
                            <div class="input-container">
                                <span class="country_code input_custom_icon"></span>
                                <input type="number" name="v_phone"  id="v_phone" value="<? if(isset($cust_id)){ echo $customers[0]->LOCAL_PHONE; } ?>" class="form-control"  pattern="[0-9]{5,}" onkeypress="isNNumeric(event)" title="" aria-describedby="ui-tooltip-13" title="Should be Numeric." required>
                            </div>    
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                        <div class="form-group">
                            <label for="v_email">Email Address <?php echo form_error('v_email', '<span class="error">', '</span>'); ?></label>
                            <input type="email" class="form-control" value="<? if(isset($cust_id)){ echo $customers[0]->E_MAIL; } ?>" name="v_email"  id="v_email" pattern="(([^<>()[\]\\.,;:\s@\&quot;]+(\.[^<>()[\]\\.,;:\s@\&quot;]+)*)|(\&quot;.+\&quot;))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))" message="Not a valid email address." required>
                        </div>
                        <div class="form-group">
                            <label for="tax_id">Tax ID <?php echo form_error('tax_id', '<span class="error">', '</span>'); ?></label>        
                            <input type="text" value="<? if(isset($cust_id)) { echo $customers[0]->USER_4; } ?>" class="form-control" name="tax_id"  id="tax_id" title="Please fill in Tax ID field." aria-describedby="ui-tooltip-37" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="f_company">Company Name <?php echo form_error('f_company', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" name="f_company" id="f_company" value="<? if(isset($cust_id)) { echo $customers[0]->COMPANY; } ?>" title="Please fill in your Store name." required>
                        </div>
                        <div class="form-group">
                            <label for="v_question">Please select a Security Question. <?php echo form_error('v_question', '<span class="error">', '</span>'); ?></label>
                            <select class="form-control" name="v_question" id="v_question" title="Please select your security Question." required="yes" aria-describedby="ui-tooltip-0">
                                <option value="">Select Your Question</option> <?php
                                foreach($getQuestionList as $getQuestion) { ?>
                                    <option value="<?php echo $getQuestion->SEC_Q_ID;?>" <?php if(isset($cust_id)) { if($customers[0]->USER_2==$getQuestion->SEC_Q_ID){ echo "selected"; } } ?>><?php echo $getQuestion->SEC_QUESTION; ?></option> <?
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="v_answer">Security Answer is: <?php echo form_error('v_answer', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" name="v_answer" id="v_answer" value="<? if(isset($cust_id)) { echo $customers[0]->USER_3; } ?>" title="Please answer your security Question." required>
                        </div>
                        <div class="form-group" style="display:<?php if($_COOKIE['user_type'] == '') { echo 'block'; } else { echo 'none';  } ?>">
                            <label class="checkbox-inline" for="Touchscreen">
                                <input type="checkbox" name="Touchscreen" <?php if(isset($cust_id))  { if($customers[0]->TOUCHSCREEN=="1"){ echo "checked"; } } ?> id="Touchscreen" value="1">Touchscreen
                            </label>
                        </div>  
                        <div class="form-group" style="display:<?php if($_COOKIE['user_type'] == '') { echo 'block'; } else { echo 'none';  } ?>">
                            <label class="checkbox-inline" for="THERMAL_RECEIPT">
                                <input type="checkbox" name="THERMAL_RECEIPT" <?php if(isset($cust_id))  { if($customers[0]->THERMAL_RECEIPT=="1"){ echo "checked"; } } ?> id="THERMAL_RECEIPT" value="1">Thermal Print
                            </label>
                        </div>           
                    </div>  
                </div>
            <div>
                <div class="titulos_forms_blue">Shipping Address</div>
            </div>
            <div class="row">  
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="v_address1">Address</label>
                        <input type="text" class="form-control" value="<? if(isset($cust_id)) { echo $customers[0]->ADDRESS1; } ?>" name="v_address1">
                    </div>
                    <div class="form-group">
                        <label for="v_cty">City</label>
                        <input type="text" class="form-control" value="<? if(isset($cust_id)) { echo $customers[0]->CITY; } ?>" name="v_cty">
                    </div>
                    <div class="form-group">
                        <label for="v_zip">Zip Code</label>
                        <input type="text" class="form-control" value="<? if(isset($cust_id)) { echo $customers[0]->POSTAL_CODE; } ?>" name="v_zip">
                    </div>
                </div>
                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="v_suite">Suite/Office</label>
                        <input type="text" class="form-control" name="v_suite" value="<? if(isset($cust_id)) { echo $customers[0]->ADDRESS2; } ?>">
                    </div>
                    <div class="form-group">
                        <label for="v_state">State/ Province</label>
                        <input type="text" class="form-control" name="v_state" value="<? if(isset($cust_id)) { echo $customers[0]->STATE_REGION; } ?>">
                    </div>
                    <div class="form-group">
                        <label for="v_country">Country <?php echo form_error('v_country', '<span class="error">', '</span>'); ?></label>
                        <select name="v_country" class="form-control" id="v_country"  required="yes">
                            <option value="">Select Your Country</option> <?php
                            foreach($getCountryCodes as $getCountryCode) { ?>
                                <option code="<?php echo $getCountryCode->CTY_CODE; ?>" value="<?php echo $getCountryCode->CTY_ID; ?>" <?php if(isset($cust_id)) { if($customers[0]->COUNTRY == $getCountryCode->CTY_ID){ echo "selected"; } } ?>><?php echo $getCountryCode->CTY_NAME; ?></option> <?
                            }?>
                        </select>
                        <input type="hidden" id="country_code" value="1" name="country_code" />
                    </div>
               </div>
            </div> <?php
            if(($checkParentmoduleEnable == "1") AND ($customers[0]->CC_ENABLED == "1") OR  ($customers[0]->CC_ENABLED == "0")  AND ($customers[0]->CC_ENABLED_BY == $_COOKIE['user_account_id'])){ ?>
                <div class="row">  
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="titulos_forms_blue">Payment Module</div>
                        <div class="form-group">
                            <label for="cc_enabled">Credit Card Payment Enabled</label>
                            <div class="radio">
                                <label for="cc_enabled"><input type="radio" name="cc_enabled"  id="cc_enabled" value="1" <?php if(isset($cust_id))  { if($customers[0]->CC_ENABLED=="1"){ echo "checked"; } } ?>>Yes</label>
                            </div>
                            <div class="radio">
                                <label for="cc_enabled"><input type="radio" name="cc_enabled" id="cc_enabled" value="0" <?php if(isset($cust_id))  { if($customers[0]->CC_ENABLED=="0"){ echo "checked"; } } ?>>No</label>
                            </div>
                        </div>
                    </div>
                </div> <?php
            } ?>  

            <input type="hidden" name="cust_id" value="<?php echo $cust_id; ?>">
            </div>
            <div class="col-xs-12">
                <button type="submit" name="submit" class="btn btn-primary myRingButton">Submit</button>
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

        setcountryCode();
		$("#v_country").change(function(){
			setcountryCode();
		});
    });

    function setcountryCode(){
		$val = $("#v_country").val();
		$code = $("#v_country").find("option[value='"+ $val +"']").attr("code");
		$("#country_code").val($code);
        $code = "+"+$code;
		$(".country_code").html($code);
	}
</script>

