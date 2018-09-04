<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <h1 class="page-title"><?= $title; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
    <form name="provider_form" id="provider_form" method="post" action="<?= base_url('Provider/providerFormSubmit'); ?>">
        <input type="hidden" name="__auth_key" value="4c4d33303139372e31342e3038352e3535393231">
        <input type="hidden" name="__confirmation_page" value="">
        <input type="hidden" name="__email_subject" value="">
        <div class="row"> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="titulos_forms_blue">Setting</div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="N_STATUS">Status</label>
                            <select class="form-control" name="N_STATUS" id="N_STATUS" title="Please select your status." required="yes" aria-describedby="ui-tooltip-0">
                                <option value="1" <?php if(isset($_GET["NP_ID"])) { if($provider[0]->N_STATUS==1){ echo "selected"; } } ?>>Enabled</option>
                                <option value="0" <?php if(isset($_GET["NP_ID"])) { if($provider[0]->N_STATUS==0){ echo "selected"; } } ?>>Disabled</option> 
                            </select>
                        </div>
                    </div>        
                </div>
            </div> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="titulos_forms_blue">Personal Information</div>
                        <div class="form-group">
                            <label for="N_PROVIDER">Name <?php echo form_error('N_PROVIDER', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" type="text" maxlength="256" name="N_PROVIDER" id="N_PROVIDER" value="<? if(isset($_GET["NP_ID"])){ echo $provider[0]->N_PROVIDER; } else { echo set_value('N_PROVIDER');} ?>" required="yes" title="Please fill in your Name."/>
                        </div>
                        <div class="form-group">
                            <label for="NP_EMAIL">Email Address <?php echo form_error('NP_EMAIL', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" type="email" size="30" maxlength="100" name="NP_EMAIL" id="NP_EMAIL" value="<? if(isset($_GET["NP_ID"])){ echo $provider[0]->NP_EMAIL; } else { echo set_value('NP_EMAIL');} ?>" required="yes" pattern="(([^<>()[\]\\.,;:\s@\&quot;]+(\.[^<>()[\]\\.,;:\s@\&quot;]+)*)|(\&quot;.+\&quot;))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))" title="Please fill email address.">
                        </div>
                        <div class="form-group">
                            <label for="NP_CONTACT">Contact Name <?php echo form_error('NP_CONTACT', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" type="text" size="30" maxlength="20" name="NP_CONTACT" id="NP_CONTACT" value="<? if(isset($_GET["NP_ID"])){ echo $provider[0]->NP_CONTACT; } else { echo set_value('NP_CONTACT');} ?>" autocomplete="false" required="yes" pattern="[^#]{8,}" title="Please fill your Contact Name."/>
                        </div> 
                        <div class="form-group">
                            <label for="NP_ADDRESS">Address <?php echo form_error('NP_ADDRESS', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" type="text" size="25" name="NP_ADDRESS" id="NP_ADDRESS" value="<? if(isset($_GET["NP_ID"])){ echo $provider[0]->NP_ADDRESS; } else { echo set_value('NP_ADDRESS');} ?>" required="yes" title="Please fill in address.">
                        </div>
                        <div class="form-group">
                            <label for="NP_CITY">City <?php echo form_error('NP_CITY', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" type="text" size="25" name="NP_CITY" id="NP_CITY" value="<? if(isset($_GET["NP_ID"])){ echo $provider[0]->NP_CITY; } else { echo set_value('NP_CITY');} ?>" required="yes" title="Please fill in City.">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="titulos_forms_blue">Security Information</div>
                        <div class="form-group">
                            <label for="NP_SHORT">Short Name <?php echo form_error('NP_SHORT', '<span class="error">', '</span>'); ?></label>
                            <input class="form-control" type="text" size="25" name="NP_SHORT" id="NP_SHORT" value="<? if(isset($_GET["NP_ID"])){ echo $provider[0]->NP_SHORT; } else { echo set_value('NP_SHORT');} ?>" required="yes" title="Please fill in Short.">
                        </div>
                        <div class="form-group">
                            <label for="NP_PHONE">Mobile Phone <?php echo form_error('NP_PHONE', '<span class="error">', '</span>'); ?></label>
                            <input type="number" name="NP_PHONE"  id="NP_PHONE" value="<? if(isset($_GET["NP_ID"])){ echo $provider[0]->NP_PHONE; } else { echo set_value('NP_PHONE');} ?>" class="form-control"  pattern="[0-9]{5,}" onkeypress="isNNumeric(event)" title="" aria-describedby="ui-tooltip-13" title="Should be Numeric." required>
                        </div>
                        <div class="form-group">
                            <label for="NP_ZIP">Zip Code <?php echo form_error('NP_ZIP', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" value="<? if(isset($_GET["NP_ID"])) { echo $provider[0]->NP_ZIP; } else { echo set_value('NP_ZIP');} ?>" name="NP_ZIP" id="NP_ZIP" title="Please fill in Zip." required>
                        </div>
                        <div class="form-group">
                            <label for="NP_COUNTRY">Country <?php echo form_error('NP_COUNTRY', '<span class="error">', '</span>'); ?></label>
                            <select class="form-control" name="NP_COUNTRY" id="NP_COUNTRY" title="Please select your country." required="yes" aria-describedby="ui-tooltip-0">
                                <option value="">Select Your Country</option> <?php
                                foreach($getCountryCodes as $getCountryCode) { ?>
                                    <option value="<?php echo $getCountryCode->CTY_ID; ?>" <?php if(isset($_GET["NP_ID"])) { if($provider[0]->NP_COUNTRY == $getCountryCode->CTY_ID){ echo "selected"; } } ?>><?php echo $getCountryCode->CTY_NAME; ?></option> <?
                                }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="NP_STATE">State/ Province <?php echo form_error('NP_STATE', '<span class="error">', '</span>'); ?></label>
                            <input type="text" class="form-control" name="NP_STATE" id="NP_STATE" value="<? if(isset($_GET["NP_ID"])) { echo $provider[0]->NP_STATE; } else { echo set_value('NP_STATE');} ?>" title="Please fill in State." required>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(isset($_GET["NP_ID"])){ ?>
                    <input type="hidden" name="NP_ID" value="<?php echo $provider[0]->NP_ID; ?>";>
            <?php } ?>
            <div class="col-xs-12">
                <? if(isset($_GET["NP_ID"])) { ?>
                    <button style="margin-left:0px;" type="submit" name="submit" class="btn btn-primary myRingButton">Update</button>   
                <? } else { ?>
                    <button style="margin-left:0px;" type="submit" name="submit" class="btn btn-primary myRingButton">Submit</button>
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
        $('#provider_form').validate({
            errorPlacement: function(error, element){
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });
    });
</script>

