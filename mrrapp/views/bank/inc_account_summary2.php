<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	// echo"<pre>";
    // print_r($getprod_types);
    // die();
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<div class="row">
		<div class="col-sm-12 marginBottom10px">
            <button type="submit" name="addAccount" class="btn btn-primary myRingButton pull-right marginLeft10px" data-toggle="modal" data-target="#choiceDiv">Add Account</button> <?php
            if($_COOKIE["user_type"] !== "258968745812378564"){ ?>
                <button type="submit" name="makeAPayment" class="btn btn-primary myRingButton pull-right" data-toggle="modal" data-target="#makePayment">Make a Payment</button> <?php
            } ?>
		</div>
	</div>	
	<div class="table-container">
		<table id="account-summary-table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Card Name</td> <?php
                    if($_COOKIE["user_type"] !== "258968745812378564") { ?>
					    <td>Times Used</td>
					    <td>Last Used</td> <?php
                    } ?>    
					<td>Action</td>
				</tr>
			</thead>
			<tbody> 
                <tr>
                    <td colspan="4" class="greyBackground">Saved Credit Accounts</td>
                </tr><?php
                if(!empty($getcreditinfo)) {    
                    foreach($getcreditinfo as $getcredit) {  ?>
                        <tr class="dataInfoBank_tr">
                            <td data-toggle="modal" data-target="<?php echo "#credit_account_info_".$getcredit->CCMOD_ID;?>" style="cursor:pointer;"><?php echo $getcredit->SAVE_CCNAME; ?></td><?php
                            if($_COOKIE["user_type"] !== "258968745812378564") { ?>
                                <td></td>
                                <td></td> <?php
                            } ?>
                            <td style="text-align: center;">
                                <a href="#" title="Delete this account" onclick="deleteAccount(<?php echo $getcredit->CCMOD_ID;?>,0);"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>
                            </td>
                        </tr> 
                        <div class="modal fade" id="credit_account_info_<?php echo $getcredit->CCMOD_ID;?>" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Account Details</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <strong>Expiration Date: </strong>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php echo $getcredit->CC_EXP_DATE; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <strong>Card Name: </strong>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php echo $getcredit->SAVE_CCNAME; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <strong>Cardholder Name: </strong>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php echo $getcredit->FIRST_NAME; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <strong>Address: </strong>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php echo $getcredit->CC_ADDRESS1; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <strong>City: </strong>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php echo $getcredit->CC_CITY; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <strong>State: </strong>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php echo $getcredit->CC_STATE_REGION; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <strong>Zip Code: </strong>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php echo $getcredit->CC_POSTAL_CODE; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <strong>Country: </strong>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php echo $getcredit->CC_COUNTRY; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-default myRingButton pull-right marginLeft10px" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div><?php
                    } 
                }else { ?>
                    <tr>
                        <td colspan="4"><span class="redfont">No Credit account information saved.</span></td>
                    </tr> <?php
                } 
                if($_COOKIE["user_type"] !== "258968745812378564") { ?>
                    <tr>
                        <td colspan="4" class="greyBackground">Saved Bank Accounts</td>
                    </tr> <?php
                    if(!empty($getbankinfo)) { 
                        foreach($getbankinfo as $getbank) {  ?>
                            <tr class="dataInfoCredit_tr">
                                <td data-toggle="modal" data-target="<?php echo "#bank_account_info_".$getbank->ACH_ID;?>"><?php $getbank->ach_nickname; ?></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: center;">
                                    <a href="#" title="Delete this account" onclick="deleteAccount(<?php echo $getbank->ACH_ID;?>,1);"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="bank_account_info_<?php echo $getbank->ACH_ID;?>" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Account Details</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <strong>Account Holder's Name: </strong>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <?php echo $getbank->ach_holder; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <strong>Account Type: </strong>
                                                            </div>
                                                            <div class="col-sm-6"><?php
                                                                if($getbank->ach_type_id == "1") {
                                                                    echo "Checking";
                                                                } else {
                                                                    echo "Saving";
                                                                } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <strong>Card Name: </strong>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <?php echo $getbank->ach_nickname; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-default myRingButton pull-right marginLeft10px" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div> <?php
                        }
                    } else { ?>
                        <tr>
                            <td colspan="4"><span class="redfont">No Bank account information saved.</span></td>
                        </tr> <?php
                    }
                }?>
			</tbody>
		</table>
    </div>
    <!-- Add Account Choice section -->
    <div class="modal fade" id="choiceDiv" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Account Type</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>
                                    <input type="radio" name="back_details" id="back_details" value="1" onclick="openDetailssection($(this));" data-dismiss="modal"/> Credit Card Details
                                </label>
                            </div>
                        </div>
                    </div>    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default myRingButton pull-right marginLeft10px" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!--./Add Account Choice section ---->

    <!---Credit ACCount details ---->
    <input type="hidden" id="bankDetailsHidden" data-toggle="modal" data-target="#bankDetails"/>
    <?php $records_excedded = 0; ?>
    <div class="modal fade" id="bankDetails" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" name="credit_card_info_form" id="credit_card_info_form" action="<?= base_url('Bank/creditCardFormAdd'); ?>">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body"> <?php
                        $getcreditinfoSize = sizeof($getcreditinfo);
                        if($getcreditinfoSize < "2") { ?>
                            <div class="row"> 
                                <div class="col-sm-12">
                                    <input type="hidden" name="__auth_key" value="4c4d33303139372e31342e3038352e3535393231">
                                    <input type="hidden" name="__confirmation_page" value="">
                                    <input type="hidden" name="__email_subject" value="">
                                    <div class="col-sm-6">
                                        <div class="titulos_forms_blue">Credit Card Information</div>
                                        <div class="form-group">
                                            <label for="cc_number">Credit Card Number: <?php echo form_error('cc_number', '<span class="error">', '</span>'); ?></label>
                                            <input class="form-control" type="text" size="25" maxlength="25" name="cc_number" id="cc_number" value="<?php echo $cc_number;?>"  pattern="[0-9]{15,16}" title="Should be Numeric and have 15 or 16 digits." required> 
                                        </div>
                                        <div class="form-group">
                                            <label for="cc_month">Expiration Date: <?php echo form_error('cc_month', '<span class="error">', '</span>'); ?></label>
                                            <select class="form-control" name="cc_month" id="select" >
                                                <option value="" title="Please fill in your exp month." required >Month</option>
                                                <option value="01" <?php if($cc_month == '01') { echo  "selected"; } ?>>January</option>
                                                <option value="02" <?php if($cc_month == '02') { echo  "selected"; } ?>>February</option>
                                                <option value="03" <?php if($cc_month == '03') { echo  "selected"; } ?>>March</option>
                                                <option value="04" <?php if($cc_month == '04') { echo  "selected"; } ?>>April</option>
                                                <option value="05" <?php if($cc_month == '05') { echo  "selected"; } ?>>May</option>
                                                <option value="06" <?php if($cc_month == '06') { echo  "selected"; } ?>>June</option>
                                                <option value="07" <?php if($cc_month == '07') { echo  "selected"; } ?>>July</option>
                                                <option value="08" <?php if($cc_month == '08') { echo  "selected"; } ?>>August</option>
                                                <option value="09" <?php if($cc_month == '09') { echo  "selected"; } ?>>September</option>
                                                <option value="10" <?php if($cc_month == '10') { echo  "selected"; } ?>>October</option>
                                                <option value="11" <?php if($cc_month == '11') { echo  "selected"; } ?>>November</option>
                                                <option value="12" <?php if($cc_month == '12') { echo  "selected"; } ?>>December</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="cc_year" id="select" >
                                                <option value="16" <?php if($cc_year == '16') { echo  "selected"; } ?>>2016</option>
                                                <option value="17" <?php if($cc_year == '17') { echo  "selected"; } ?>>2017</option>
                                                <option value="18" <?php if($cc_year == '18') { echo  "selected"; } ?>>2018</option>
                                                <option value="19" <?php if($cc_year == '19') { echo  "selected"; } ?>>2019</option>
                                                <option value="20" <?php if($cc_year == '20') { echo  "selected"; } ?>>2020</option>
                                                <option value="21" <?php if($cc_year == '21') { echo  "selected"; } ?>>2021</option>
                                                <option value="22" <?php if($cc_year == '22') { echo  "selected"; } ?>>2022</option>
                                                <option value="23" <?php if($cc_year == '23') { echo  "selected"; } ?>>2023</option>
                                                <option value="24" <?php if($cc_year == '24') { echo  "selected"; } ?>>2024</option>
                                                <option value="25" <?php if($cc_year == '25') { echo  "selected"; } ?>>2025</option>
                                                <option value="26" <?php if($cc_year == '26') { echo  "selected"; } ?>>2026</option>
                                                <option value="27" <?php if($cc_year == '27') { echo  "selected"; } ?>>2027</option>
                                                <option value="28" <?php if($cc_year == '28') { echo  "selected"; } ?>>2028</option>
                                                <option value="29" <?php if($cc_year == '29') { echo  "selected"; } ?>>2029</option>
                                                <option value="30" <?php if($cc_year == '30') { echo  "selected"; } ?>>2030</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="cc_ccv">Verification Code: <?php echo form_error('cc_ccv', '<span class="error">', '</span>'); ?></label>
                                            <input class="form-control" type="text" size="10" maxlength="10"  value="<?php echo $cc_ccv;?>" name="cc_ccv" id="cc_ccv" pattern="[0-9]{3,}" title="Should be Numeric and have at least 3 digits." required>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-offset-4 col-sm-4">
                                                    <img class="img-responsive" src="<?php echo base_url("/images/ccv-amex.jpg");?>"/>
                                                </div>
                                                <div class="col-sm-4">
                                                    <img class="img-responsive pull-right" src="<?php echo base_url("/images/ccv-mastervisa.jpg");?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="nickname">Card Name: <?php echo form_error('nickname', '<span class="error">', '</span>'); ?></label>
                                            <input class="form-control" type="text" size="25" value="<?php echo $nickname;?>" name="nickname" id="nickname" title="Please fill in Card Name field." required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="titulos_forms_blue">Billing Information</div>
                                        <div class="form-group">
                                            <label for="f_name">Cardholder Name: <?php echo form_error('f_name', '<span class="error">', '</span>'); ?></label>
                                            <input class="form-control" type="text" size="25" value="<?php echo $f_name;?>" name="f_name" id="f_name" title="Please fill in your first name." required>
                                        </div>
                                        <div class="form-group">
                                            <label for="c_address1">Address: <?php echo form_error('c_address1', '<span class="error">', '</span>'); ?></label>
                                            <input class="form-control" type="text" size="25" value="<?php echo $c_address1;?>" name="c_address1" id="c_address1" title="Please fill in your address." required>
                                        </div>
                                        <div class="form-group">
                                            <label for="v_city">City: <?php echo form_error('v_city', '<span class="error">', '</span>'); ?></label>
                                            <input class="form-control" type="text" size="25" value="<?php echo $v_city;?>" name="v_city" id="v_city" title="Please fill in your city." required>
                                        </div>
                                        <div class="form-group">
                                            <label for="v_state">State: <?php echo form_error('v_state', '<span class="error">', '</span>'); ?></label>
                                            <input class="form-control" type="text" size="25" value="<?php echo $v_state;?>" name="v_state" id="v_state" title="Please fill in your state." required>
                                        </div>
                                        <div class="form-group">
                                            <label for="v_zip">Zip Code: <?php echo form_error('v_zip', '<span class="error">', '</span>'); ?></label>
                                            <input class="form-control" type="text" size="25" value="<?php echo $v_zip;?>" name="v_zip" id="v_zip" title="Please fill in your zip postal code." required>
                                        </div>
                                        <div class="form-group">
                                            <label for="cty">Country: <?php echo form_error('cty', '<span class="error">', '</span>'); ?></label>
                                            <input class="form-control" type="text" size="25" value="<?php echo $cty;?>" name="cty" id="cty" title="Please fill in your country." required>
                                        </div>
                                    </div>
                                </div>
                            </div> <?php
                        } else { ?>
                            <p class="text-center">You can not add more than 2 credit card accounts.</p> <?php
                            $records_excedded = 1;
                        }?> 
                    </div>
                    <div class="modal-footer">
                        <button type="reset"  class="btn btn-default myRingButton pull-right marginLeft10px" data-dismiss="modal" onclick="$('#credit_card_info_form').trigger('reset');">Cancel</button> <?php
                        if($getcreditinfoSize < "2") { ?>
                            <button type="submit" name="addBankDetails" class="btn btn-default myRingButton pull-right marginLeft10px">Submit</button> <?php
                        } ?>
                    </div>
                </form>    
            </div>
        </div>
    </div>
    <!--./Bank Details Form -->

    <!-- Make Payment Section -->
    <div class="modal fade" id="makePayment" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form name="make_a_payment_form" onsubmit="return makePaymentValidation();" id="make_a_payment_form" action="<?= base_url("Bank/addPayment"); ?>" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="titulos_forms_blue">Make Payment</div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong>Balance </strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <?php echo "<i class='fa fa-usd'></i> ".$getBalance[0]["BALANCE"]; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong>Total Amount due </strong>
                                        </div>
                                        <div class="col-sm-6"><?php
                                            if($getBalance[0]["BALANCE"] < "0"){ 
                                                echo "<i class='fa fa-usd'></i> ".$getBalance[0]["BALANCE"];
                                            } else {
                                                echo "<i class='fa fa-usd'></i> 0.00";
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong>Amount to Pay </strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-container">
                                                <span class="input_custom_icon"><i class="fa fa-usd"></i></span>
                                                <input type="text" name="cc_amount"  id="cc_amount" value="<?php echo $cc_amount; ?>" class="form-control" title="Should be between $25 to $500." required/>
                                            </div> 
                                            <span class="amount_msg">Should be between $25 to $500.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group marginTop20px">
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <button type="submit" name="addAccount" class="btn btn-primary myRingButton" data-toggle="modal" data-target="#choiceDiv" data-dismiss="modal">Add Account</button> 
                                            <h5 class="marginBottom10px">OR</h5>
                                            <select class="form-control" name="selectAccount" id="selectAccount" required="required" onchange="if(this.value != ''){ $('.addPayment1').show();} else { $('.addPayment1').hide();}">
                                                <option value="">Select Existing account</option> <?php
                                                foreach($getcreditinfo as $getcredit) {  ?>
                                                    <option value="<?php echo $getcredit->CCMOD_ID.'_credit'; ?>" <?php if($selectAccount == $getcredit->CCMOD_ID.'_credit') { echo  "selected" ; } ?>>
                                                        <?php echo $getcredit->SAVE_CCNAME; ?>
                                                    </option> <?php
                                                }
                                                foreach($getbankinfo as $getbank) {  ?>
                                                    <option value="<?php $getbank->ACH_ID.'_Bank'; ?>" <?php if($selectAccount == $getbank->ACH_ID.'_Bank') { echo "selected"; } ?>><?php echo $getbank->ACH_NICKNAME; ?></option> <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group addPayment1" style="display:none;">
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <input type="checkbox" name="paypal_pay" id="paypal_pay" value="1" style="display:none;" />       
                                        </div>
                                    </div>                        
                                </div>
                                <div class="form-group addPayment1" style="display:none;">
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <input type="text" class="addPayment1 form-control" name="cerditNumber" id="cerditNumber"  value="" placeholder="Last 8 digits of credit card" style="display:none;" pattern="[0-9]{8}" title="Should be at least 8 characters" required/><br class="addPayment1" />
                                        </div>
                                    </div>                        
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-default myRingButton pull-right marginLeft10px" data-dismiss="modal" onclick="$('#make_a_payment_form').trigger('reset');">Cancel</button>

                        <button type="submit" class="btn btn-default addPayment1 myRingButton pull-right marginLeft10px" name="addPayment" id="addPayment1" style="display:none;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--./Make Payment Section -->
</div>
<div class="alert_message_container" id="alert_message_container">
    <div class="alert_message" id="alert_message"></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
        $('#credit_card_info_form').validate({
            errorPlacement: function(error, element){
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });

		$('#account-summary-table').DataTable({});
    });

    function deleteAccount(id,check){
		if(confirm("Are you sure you want to delete this account?"))
		{
			var $q_string = "";
			if(check == 0)
			{
				$q_string = "ccmod_id="+id;
			}
			else if(check == 1)
			{
			    $q_string = "ach_id="+id;
			}
			window.location.assign("accountSummary?action=delete&"+$q_string);
		}
	}

    function openDetailssection($this){
        var record_excedded = <?php echo $records_excedded; ?>;
		if($this.val() == "1")
		{
            $("#bankDetailsHidden").click();
			if(record_excedded == "0"){
                $("#bankDetails .modal-dialog").css("cssText", "width: 70% !important;");
            }
		}
		else if($this.val() == "0"){
        
        }
		else{
			return false;
		}
	}

    function makePaymentValidation(){
        var cc_amount = $("#cc_amount").val();
        var cerditNumber = $("#cerditNumber").val();
        var a,b,c;
        if(cc_amount == '') {
			$(".amount_msg").css('color','red');
            a=1;
		} else {
            $(".amount_msg").css('color','black');
        }
        if((cc_amount < 25) || (cc_amount >500)){
			$(".amount_msg").css('color','red');
            b=1;
		} else {
            $(".amount_msg").css('color','black');
        }
        if((cerditNumber == '') || (cerditNumber.length < 8 )){
			$("#cerditNumber").css('border','1px solid red');
            c=1;
		} else{
            $("#cerditNumber").css('border','1px solid #ccc');
        }
        if((a==1)||(b==1)||(c==1)){
            return false;
        }
    }
</script>