<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    if(isset($_GET["userid"])){ 
        $user['v_secure'] = "";
    }
    // echo "<pre>";
    // print_r($user);
    // die();
?>
<div class="container">
    <h1 class="page-title"><?= $title; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
    <form name="user_form" id="user_form" method="post" action="<?= base_url('User/userformSubmit'); ?>">
        <input type="hidden" name="__auth_key" value="4c4d33303139372e31342e3038352e3535393231">
        <input type="hidden" name="__confirmation_page" value="">
        <input type="hidden" name="__email_subject" value="">
        <div class="row"> <?php
            if($add_edit == 0) { ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="titulos_forms_blue">Security Information</div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="v_secure">Security Code <?php echo form_error('v_secure', '<span class="error">', '</span>'); ?></label>
                                <input class="form-control" required type="text" size="30" maxlength="4" name="v_secure" id="v_secure" value="<?php echo set_value('v_secure');?>"  pattern="[0-9]{4}" title="Please fill in your security code." />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="confirm_v_secure">Confirm Security Code <?php echo form_error('confirm_v_secure', '<span class="error">', '</span>'); ?></label>
                                <input class="form-control" required type="text" size="30" maxlength="4" name="confirm_v_secure" id="confirm_v_secure" value="<?php echo set_value('confirm_v_secure');?>"  pattern="[0-9]{4}" title="Please Confirm your security code." />
                            </div>
                        </div> 
                    </div>
                </div> <?php
            } else { ?>
                <input type="hidden" name="v_secure" value="<?php if(isset($_GET["userid"])){ echo $user['v_secure']; } ?>"> <?php
            }?>   
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="titulos_forms_blue">General Information</div>
                        <div class="form-group">
                            <label for="f_name">First Name <?php echo form_error('f_name', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" type="text" maxlength="40" name="f_name" id="f_name" value="<? if(isset($_GET["userid"])){ echo $user[0]->UserFirstName; } else { echo set_value('f_name');} ?>" required title="Please fill first name"/>
                        </div>
                        <div class="form-group">
                            <label for="l_name">Last Name <?php echo form_error('l_name', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control"  required type="text" maxlength="40" name="l_name" id="l_name" value="<? if(isset($_GET["userid"])){ echo $user[0]->UserLastName; } else { echo set_value('l_name');} ?>"  title="Please fill last name"/>
                        </div>
                        <div class="form-group">
                            <label for="login_name">Username <?php echo form_error('login_name', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control"  required type="text" size="30" maxlength="20" name="login_name" id="login_name" value="<? if(isset($_GET["userid"])){ echo $user[0]->LOGIN_NAME; } else { echo set_value('login_name');} ?>" autocomplete="false"  pattern="[^#]{8,}" title="Should have at least 8 characters excluding '#'."/>
                        </div> <?php
                        if($add_edit == "0") { ?>
                            <div class="form-group">
                                <label for="f_login">Password <?php echo form_error('f_login', '<span class="error">', '</span>'); ?></label>
                                <input class="form-control" required type="password" size="30" maxlength="60" name="f_login" id="f_login" value="<?php echo set_value('f_login'); ?>" autocomplete="false" pattern="[^#]{8,}"  title="Please enter a Password">
                            </div>
                            <div class="form-group">
                                <label for="f_password">Confirm Password <?php echo form_error('f_password', '<span class="error">', '</span>'); ?></label>
                                <input class="form-control" required type="password" size="30" maxlength="60" name="f_password" id="f_password" value="<?php echo set_value('f_password'); ?>" autocomplete="false" pattern=""  title="Please confirm your Password">
                            </div> <?php
                        } ?>
                        <div class="form-group">
                            <label for="v_email">Email <?php echo form_error('v_email', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" required type="email" size="30" maxlength="100" name="v_email" id="v_email" value="<? if(isset($_GET["userid"])){ echo $user[0]->E_MAIL; } else { echo set_value('v_email');} ?>"  pattern="(([^<>()[\]\\.,;:\s@\&quot;]+(\.[^<>()[\]\\.,;:\s@\&quot;]+)*)|(\&quot;.+\&quot;))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))" title="Please fill email address.">
                        </div>
                        <div class="form-group">
                            <label for="v_phone">Phone <?php echo form_error('v_phone', '<span class="error">', '</span>'); ?></label>
                            <div class="input-container">
                                <span class="input_custom_icon">+1</span>
                                <input class="form-control" required type="text" size="25" maxlength="20" name="v_phone" id="v_phone" value="<? if(isset($_GET["userid"])){ echo $user[0]->UserLocalPhone; } else { echo set_value('v_phone');} ?>" title="Should be Numeric.">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="assigncmp">Assign to:</label> <?php
                            if($_COOKIE["user_type"] == "415285967837575867"){ ?>
                                <input class="form-control" readonly="yes" type="text" size="25" maxlength="20" name="assigncmp" id="assigncmp" value="<?php echo $getcompanyList->COMPANY;  ?>">
                                <input type="hidden" name="f_company" id="f_company" value="<?php echo $getcompanyList->CUSTOMER_ENC; ?>"> <?php
                            } else { ?>
                                <select class="form-control" name="f_company" id="f_company" title="Please select one Assign to option." required>
                                    <option value="">Select a Company</option> <?php 
                                    foreach($getcompanyList as $company){ ?>
                                        <option value="<?php echo $company->CUSTOMER_ENC; ?>" <?php if(isset($_GET["userid"])){ if($company->CUSTOMER_ENC == $user[0]->CUSTOMER_ID_ENC){ echo "selected"; } } ?> ><?php echo $company->COMPANY; ?></option> <?php
                                    } ?>
                                </select> <?php   
                            } ?>
                        </div>
                        <div class="form-group">
                            <label for="assigncmp">Role as</label> 
                            <select class="form-control" name="role" id="role" title="Please select one role to assign." required>
                                <option value="">Select a Role</option> <?php 
                                foreach($getRoleList as $role){ ?>
                                    <option value="<?php echo $role->ACCOUNT_TYPE ?>" <?php if(isset($_GET["userid"])){ if($role->ACCOUNT_TYPE == $user[0]->USER_TYPE){ echo "selected"; } } ?> ><?php echo $role->DESCRIPTION; ?></option> <?php
                                } ?>
                            </select>
                        </div>
                    </div>    
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="titulos_forms_blue">Security Information</div>
                        <div class="form-group">
                            <label class="checkbox-inline" for="active">
                                <input type="checkbox" name="active" <?php if(isset($_GET["userid"])) { if($user[0]->UserEnabled=="1"){ echo "checked"; } } ?> id="active" value="1">Active?
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="radio">
                                <label for="ipAccess1"><input type="radio" name="ipAccess" checked="checked" id="ipAccess1" value="1">Access for any IP Address</label>
                            </div>
                            <div class="radio">
                                <label for="ipAccess2"><input type="radio" name="ipAccess" id="ipAccess2" value="2">Access from these IP address</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="v_question">Please select a Security Question. <?php echo form_error('v_question', '<span class="error">', '</span>'); ?></label>
                            <select class="form-control" required name="v_question" id="v_question" title="Please select your security Question." aria-describedby="ui-tooltip-0">
                                <option value="">Select Your Question</option> <?php
                                foreach($getQuestionList as $getQuestion) { ?>
                                    <option value="<?php echo $getQuestion->SEC_Q_ID;?>" <?php if(isset($_GET["userid"])) { if($user[0]->SECURITY_QUESTION==$getQuestion->SEC_Q_ID){ echo "selected"; } } ?>><?php echo $getQuestion->SEC_QUESTION; ?></option> <?
                                }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="v_answer">Security Answer is: <?php echo form_error('v_answer', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" required name="v_answer" id="v_answer" value="<? if(isset($_GET["userid"])) { echo $user[0]->SECURITY_ANSWER; } else { echo set_value('v_answer');} ?>" title="Please answer your security Question." >
                        </div>
                    </div>
                </div>
            </div>
            <?php if(isset($_GET["userid"])) { ?>
                    <input type="hidden" name="userid" value="<?php echo $_GET["userid"]; ?>"/>
            <?php } ?>
            <div class="col-xs-12">
                <button type="submit" name="submit" class="btn btn-primary myRingButton" ><?php if(isset($_GET["userid"])) { echo "Update"; } else { echo "Submit"; } ?></button>   
            </div>
         </div>    
    </form>
</div>
<div class="alert_message_container" id="alert_message_container">
    <div class="alert_message" id="alert_message"></div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#user_form').validate({
            errorPlacement: function(error, element){
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });
    });
</script>

