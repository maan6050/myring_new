<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<div class="row">
		<div class="col-sm-6 marginBottom10px"> <?php
			if(($accountType >= "2") AND ($accountType <= "4")) { ?>
				<span class="available_bal"><b>Available: </b> <?php 
					if($getHeaders[0]->BALANCE == ""){
						$getHeaders[0]->BALANCE ="0";
					}
					if($getHeaders[0]->CREDIT_LIMIT == ""){
						$getHeaders[0]->CREDIT_LIMIT ="0";
					}
					if($getHeaders[0]->TOTALSUM == ""){
						$getHeaders[0]->TOTALSUM ="0";
					}
					$avalable_balance = ($getHeaders[0]->TOTALSUM)-($getHeaders[0]->BALANCE + $getHeaders[0]->CREDIT_LIMIT);
					$formatedNumber = number_format($avalable_balance, 2, '.', '');
					echo "(<i class='fa fa-usd'></i> ".$formatedNumber.")"; ?>
				</span> <?php
			}	?>
		</div>
		<div class="col-sm-6 marginBottom10px">
			<a href="<?= base_url('Customer/add'); ?>" class="myRingButton pull-right">Add Customer</a>
		</div>
	</div>	
	<div class="table-container">
		<table id="customer-table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>ID</td>
					<td>Company</td>
					<td>First Name</td>
					<td>Last Name</td>
					<td>Phone Number</td>
					<td>Balance</td>
					<td>Credit Limit</td>
					<td>Active</td>
					<td>Edit</td>
					<td>Add</td>
					<td>Products</td>
				</tr>
			</thead>
			<tbody> <?php
				$total_sum = "0";
				foreach($customers as $r) {  ?>
					<tr <?php if(($customers_data[0]->STORE_TEMPLATE ) == ($r->CUSTOMER_ENC)){ echo "style='background-color:##99FF66;' title='This store is selected as product template'";} ?>>
						<td><?php echo $r->ACCOUNT_ID; ?></td>
						<td><?php echo $r->COMPANY; ?></td>
						<td><?php echo $r->FIRST_NAME; ?></td>
						<td><?php echo $r->LAST_NAME; ?></td>
						<td><?php echo $r->LOCAL_PHONE; ?></td>
						<td>
							<?php $balance = number_format($r->BALANCE, 2, '.', ''); ?>
							<i class="fa fa-usd"></i><?php echo " ".$balance; ?>
							<input type="hidden" value="<?php echo $balance; ?>" name="bal_<?php $r->CUSTOMER_ENC; ?>" id="bal_<?php $r->CUSTOMER_ENC; ?>" />
						</td>
						<td>
							<?php $credit_limit = number_format($r->CREDIT_LIMIT, 2, '.', ''); ?>
							<i class="fa fa-usd"></i><?php echo " ".$credit_limit; ?></td>
						<td>
							<?php if($r->ENABLED=="1"){
								echo "YES";	
							} else {
								echo "NO";
							} ?>
						</td>
						<td>
							<a href="<?php echo base_url('Customer/add?cust_enc='.$r->CUSTOMER_ENC) ?>" title=" Edit Customer"><i class="fa fa-edit fa-2x" aria-hidden="true"></i></a>
						</td>
						<?php
							$cmpnayName = str_replace("'"," ",$r->COMPANY);
						?>
						<td>
							<a href="#" onclick="openDialog2('<?php echo $r->CUSTOMER_ENC; ?>' , '<?php echo $cmpnayName; ?>');" data-toggle="modal" data-target="#addBalanceDiv" title="Add Balance"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i></a>
						</td>
						<?php
							////////////THIS IS PENDING////////////////
							//$encryptionKey = "enzimaNadi";
							//$acc_enc = encrypt($r->CUSTOMER_ENC,$encryptionKey,"CFMX_COMPAT","hex");
						?>
						<td>
							<a href="<?php echo base_url('Customer/updateDiscount?acc_enc='.$r->CUSTOMER_ENC.'&company='.$r->COMPANY) ?>"><i class="fa fa-product-hunt fa-2x" aria-hidden="true" title="Add Discount"></i></a>
						</td>
					</tr> <?php
					$total_sum = $total_sum+$r->BALANCE;
				} ?>
			</tbody>
		</table>
	</div>

	<? //////////////////ADD BALANCE MODAL/////////////////?>
	<div class="modal fade" id="addBalanceDiv" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Balance to <span id="company_name"></span></h4>
				</div>
				<form name="add_balance_to_master" id="add_balance_to_master" method="post" action="<?= base_url('Customer/addBalance'); ?>">
					<div class="modal-body">
						<div class="row"> 
							<div class="col-sm-12">
								<input type="hidden" name="if_submit_form" id="if_submit_form" value="0" />
								<input type="hidden" name="total_amounts" id="total_amounts" value="<?php echo $total_sum; ?>"/>
								<input type="hidden" name="total_creditbalance" id="total_creditbalance" value="0" />
								<input type="hidden" name="total_creditlimit" id="total_creditlimit" value="0" />
								<input type="hidden" name="__auth_key" value="4c4d33303139372e31342e3038352e3535393231">
								<input type="hidden" name="__confirmation_page" value="">
								<input type="hidden" name="__email_subject" value="">
								<div class="col-sm-12">
									<div class="form-group">
										<label for="cre_det">Details <?php echo form_error('cre_det', '<span class="error">', '</span>'); ?></label>
										<input type="text" class="form-control" name="cre_det" id="cre_det" value="" title="Please fill in Details field." required>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label for="cre_desc">Description <?php echo form_error('cre_desc', '<span class="error">', '</span>'); ?></label>
										<input type="text" class="form-control" name="cre_desc" id="cre_desc" value="" title="Please fill in Description field." required>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label for="cre_amount">Amount <?php echo form_error('cre_amount', '<span class="error">', '</span>'); ?></label>
										<input type="number" class="form-control" name="cre_amount" id="cre_amount" value="" title="Please fill in Amount field.." required>
									</div>
								</div>
							</div>
						</div>	
					</div>
					<input type="hidden" name="companyNamehidden" id="companyNamehidden" value="" />
            		<input type="hidden" name="customer_enc" id="customer_enc_val" value="" />
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
<script type="text/javascript">
	$(document).ready(function() {
		$('#customer-table').DataTable({
			"aoColumnDefs" : [ 
						{"aTargets" : [8], "sClass":  "custom-td"},
						{"aTargets" : [9], "sClass":  "custom-td"},
						{"aTargets" : [10], "sClass":  "custom-td"} 
			]
		});
		$('#add_balance_to_master').validate({
            errorPlacement: function(error, element){
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });

		$("#total_creditlimit").val($('#credit_limit').val());
		$("#total_creditbalance").val($("#credit_balance").val());
	});
	function openDialog2(cust_enc, company){
		$("#addBalanceDiv form input[type='text']" ).val("");
		$("#addBalanceDiv #company_name").html(company);
		$("#addBalanceDiv #companyNamehidden").val(company);
		$("#addBalanceDiv input[name='customer_enc']" ).val(cust_enc);
	}
	

	/*function validate_balance(){
		<cfif cookie.user_type Neq "956314127503977533">
			if(((parseFloat($("#total_amounts").val())+ parseFloat($("#cre_amount").val()))) <= (parseFloat($("#total_creditlimit").val())+parseFloat($("#total_creditbalance").val())) ){
				if($("#if_submit_form").val() != 1)	{
		
					$("#check_security_pin").attr("required","yes");
					$(".card_table_dialog tbody tr:not(:last-child)").hide();
					$(".card_table_dialog tbody tr.security_pin_question").show();
					$("#if_submit_form").val(1);
					$("#country_list").removeAttr("disabled");
					return false;
				}else{
					return true;
				}
			}
			else{
				if($("#cre_amount") > 0){
					alert("Not authorized. Please increase balance.");
					$("#cre_amount").focus();
					return false;
				}else{
					if($("#if_submit_form").val() != 1)	{
		
						$("#check_security_pin").attr("required","yes");
						$(".card_table_dialog tbody tr:not(:last-child)").hide();
						$(".card_table_dialog tbody tr.security_pin_question").show();
						$("#if_submit_form").val(1);
						$("#country_list").removeAttr("disabled");
						return false;
					}else{
						return true;
					}		
				}
			}
		<cfelse>
			return true;
		</cfif>
	}*/
</script>


