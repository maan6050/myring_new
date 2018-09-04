<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    if(isset($_GET["cust_enc"])){ 
        $action = base_url('Customer/customerformUpdate');
    } else {
        $action = base_url('Customer/customerformSubmit');
    }
    
    //////////////////////ARRAY FOR SECURITY QUESTION DROP DOWN////////////////////////
    $security_questions = array("1"=>"What was the name of your elementary / primary school?", "2"=>"What is the name of the company of your first job?", "3"=>"What was your favorite place to visit as a child?", "4"=>"What is your spouse's mother's maiden name?", "5"=>"What is the country of your ultimate dream vacation?", "6"=>"What is the name of your favorite childhood teacher?", "7"=>"To what city did you go on your honeymoon?", "8"=>"What time of the day were you born?", "9"=>"What was your dream job as a child?", "10"=>"What is the street number of the house you grew up in?", "11"=>"What is the license plate (registration) of your dad's first car?", "12"=>"Who was your childhood hero?", "13"=>"What was the first concert you attended?", "14"=>"What are the last 5 digits of your credit card?", "15"=>"What are the last 5 of your Social Security number?", "16"=>"What is your current car registration number?", "17"=>"What are the last 5 digits of your driver's license number?", "18"=>"What month and day is your anniversary?", "19"=>"What is your grandmother's first name?", "20"=>"What is your mother's middle name?", "21"=>"What is the last name of your favorite high school teacher?", "22"=>"What was the make and model of your first car?", "23"=>"Where did you vacation last year?", "24"=>"What is the name of your grandmother's dog?", "25"=>"What is the name, breed, and color of current pet?", "26"=>"What is your preferred musical genre?", "27"=>"In what city and country do you want to retire?", "28"=>"What is the name of the first undergraduate college you attended?", "29"=>"What was your high school mascot?", "30"=>"What year did you graduate from High School?", "31"=>"What is the name of the first school you attended?");

    //////////////////////ARRAY FOR PLAN PROD DROP DOWN////////////////////////
    $plan_prod = array("1"=>"Test Plan", "6"=>"Wm Testing", "15"=>"Temporal Wm", "18"=>"NADI API Master");

    //////////////////////ARRAY FOR COUNTRY DROP DOWN////////////////////////
    $countries = array("22"=>"ARGENTINA", "2"=>"COLOMBIA", "9"=>"CUBA", "17"=>"DOMINICAN REPUBLIC", "5"=>"ECUADOR", "13"=>"EL SALVADOR", "14"=>"GUATEMALA", "24"=>"HAITI", "12"=>"HONDURAS", "4"=>"INDIA", "23"=>"JAMAICA", "10"=>"MEXICO", "11"=>"PAKISTAN", "20"=>"PANAMA", "21"=>"PERU", "1"=>"UNITED STATES");
?>
<div class="container">
    <h1 class="page-title"><?= $title; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
    <form name="customer_form" id="customer_form" method="post" action="<?= $action; ?>">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div>
                    <div class="titulos_forms_blue">Contact Information</div>
                </div>
                <div class="row"> 
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="f_name">First Name</label>
                            <input type="text" class="form-control" name="f_name" id="f_name" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]["FIRST_NAME"]; } ?>" title="Please fill in your First name." required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="l_name">Last Name</label>
                            <input type="text" class="form-control" name="l_name" id="l_name" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]["LAST_NAME"]; } ?>" title="Please fill in your last name." required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="v_fax">Work Phone No.</label>
                            <input type="number" name="v_fax" id="v_fax" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]["FAX"]; } ?>" class="form-control"  pattern="[0-9]{5,}" onkeypress="isNNumeric(event)" title="" aria-describedby="ui-tooltip-13" title="Please fill in Work Phone number." required>
                            
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="v_phone">Mobile Phone</label>
                            <input type="number" name="v_phone"  id="v_phone" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]["LOCAL_PHONE"]; } ?>" class="form-control"  pattern="[0-9]{5,}" onkeypress="isNNumeric(event)" title="" aria-describedby="ui-tooltip-13" title="Should be Numeric." required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                        <div class="form-group">
                            <label for="v_email">Email Address</label>
                            <input type="email" class="form-control" value="<? if(isset($_GET["cust_enc"])){ echo $customers[0]["E_MAIL"]; } ?>" name="v_email"  id="v_email" pattern="(([^<>()[\]\\.,;:\s@\&quot;]+(\.[^<>()[\]\\.,;:\s@\&quot;]+)*)|(\&quot;.+\&quot;))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))" message="Not a valid email address." required>
                        </div>
                    
                        <div class="form-group">
                            <label for="tax_id">Tax ID</label>        
                            <input type="text" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["USER_4"]; } ?>" class="form-control" name="tax_id"  id="tax_id" title="Please fill in Tax ID field." aria-describedby="ui-tooltip-37" required>
                            
                        </div>
                    
                        <div class="form-group">
                            <label for="f_company">Company Name</label>
                            <input type="text" class="form-control" name="f_company" id="f_company" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["COMPANY"]; } ?>" title="Please fill in your Store name." required>
                        </div>
                    
                        <div class="form-group">
                            <label for="v_question">Please select a Security Question.</label>
                            <select class="form-control" name="v_question" id="v_question" title="Please select your security Question." required="yes" aria-describedby="ui-tooltip-0">
                                <option value="">Select Your Question</option> <?php
                                foreach($security_questions as $question_key => $question_value) { ?>
                                    <option value="<?php echo $question_key;?>" <?php if(isset($_GET["cust_enc"])) { if($customers[0]["USER_2"]==$question_key){ echo "selected"; } } ?>><?php echo $question_value; ?></option> <?
                                }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="v_answer">Security Answer is:</label>
                            <input type="text" class="form-control" name="v_answer" id="v_answer" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["USER_3"]; } ?>" title="Please answer your security Question." required>
                        </div>
                    </div>  
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="plan_prod">Select Plan</label>
                            <select class="form-control" name="plan_prod" id="plan_prod" title="Please Select Product Plan." required="yes">
                                <option value="">Select</option> <?php
                                foreach($plan_prod as $plan_prod_key => $plan_prod_value) { ?>
                                    <option value="<?php echo $plan_prod_key;?>" <?php if(isset($_GET["cust_enc"])) { if($customers[0]["USER_6"]==$plan_prod_key){ echo "selected"; } } ?>><?php echo $plan_prod_value; ?></option> <?
                                }?>
                            </select>
                        </div>
                    </div>
                </div>
            <div>
                <div class="titulos_forms_blue">Company Address</div>
            </div>
            <div class="row">  
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="v_address1">Address</label>
                        <input type="text" class="form-control" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["ADDRESS1"]; } ?>" name="v_address1">
                    </div>
                    <div class="form-group">
                        <label for="v_cty">City</label>
                        <input type="text" class="form-control" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["CITY"]; } ?>" name="v_cty">
                    </div>
                    <div class="form-group">
                        <label for="v_zip">Zip Code</label>
                        <input type="text" class="form-control" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["POSTAL_CODE"]; } ?>" name="v_zip">
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="v_suite">Suite/Office</label>
                        <input type="text" class="form-control" name="v_suite" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["ADDRESS2"]; } ?>">
                    </div>
                    <div class="form-group">
                        <label for="v_state">State/ Province</label>
                        <input type="text" class="form-control" name="v_state" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["STATE_REGION"]; } ?>">
                    </div>
                    <div class="form-group">
                        <label for="v_country">Country</label>
                        <select name="v_country" class="form-control" id="v_country"  required="yes">
                            <option value="">Select Your Country</option> <?php
                            foreach($countries as $countries_key => $countries_value) { ?>
                                <option value="<?php echo $countries_key;?>" <?php if(isset($_GET["cust_enc"])) { if($customers[0]["COUNTRY"]==$countries_key){ echo "selected"; } } ?>><?php echo $countries_value; ?></option> <?
                            }?>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" name="latitude" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["USER_7"]; } ?>" onkeypress="isNFloat(event)">
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" name="longitude" value="<? if(isset($_GET["cust_enc"])) { echo $customers[0]["USER_8"]; } ?>" onkeypress="isNFloat(event)">
                    </div>
                </div>
            </div>
            <?php if(isset($_GET["cust_enc"])){ ?>
                    <input type="hidden" name="cust_enc" value="<?php echo $_GET["cust_enc"]; ?>";>
            <?php } ?>
            <br>
            </div>
            <!--<div style="text-align:center"> -->
            <div class="col-xs-12">
                <? if(isset($_GET["cust_enc"])) { ?>
                    <button type="submit" name="submit" class="btn btn-primary myRingButton">Update</button>   
                <? } else { ?>
                    <button type="submit" name="submit" class="btn btn-primary myRingButton">Submit</button>
                <? }  ?>  
            </div>
            <!--</div> -->
        </div>    
    </form>
</div>
<br>
<br>

<div class="alert_message" id="alert_message"></div>

<?php
	if(isset($msg)) { 
		?><script type="text/javascript"> 
            document.getElementById("alert_message").innerHTML="<?php echo $msg; ?>";
			document.getElementById("alert_message").style.opacity="1";
			document.getElementById("alert_message").style.marginTop="170px";
			setTimeout(function(){ document.getElementById("alert_message").style.opacity="0"; }, 3000);
		</script><?
    }
    if(isset($error)) { 
        ?><script type="text/javascript">
            document.getElementById("alert_message").innerHTML="<?php echo $error; ?>";
			document.getElementById("alert_message").style.opacity="1";
			document.getElementById("alert_message").style.marginTop="170px";
			setTimeout(function(){ document.getElementById("alert_message").style.opacity="0"; }, 3000);
        </script><?
    } 
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#customer_form').validate({
            errorPlacement: function(error, element){
                // Adiciona el error dentro de la etiqueta asociada.
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });
    });
</script>
