<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div> <?php
    if(($acc_type > 1) AND ($acc_type > $current_acc_type)) { ?>
        <div class="row">
            <div class="col-sm-12 marginBottom10px">
                <a href="<?= base_url('ViewLevel/viewList?cust='.$acc_enc_gain); ?>" class="myRingButton pull-right"><?php echo "Return to ".$return_to;?></a>
			</div>
        </div> <?php
    } ?>
	<div class="table-container">
		<table id="view-level-table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Company</td>
					<td>First Name</td>
					<td>Last Name</td>
					<td>Phone Number</td>
					<td>Balance</td>
					<td>Credit Limit</td>
                    <td>Active</td> <?php
                    if($acc_type<="4"){ ?>
                        <td>Products</td> <?php
                    } ?>
				</tr>
			</thead>
			<tbody> <?php
				foreach($viewLevels as $viewLevel) {  ?>
					<tr>
						<td>
                            <a href="<?php echo base_url('ViewLevel/viewList?cust='.$viewLevel->CUSTOMER_ENC) ?>"><?php echo $viewLevel->COMPANY; ?></a>
                        </td>
                        <td><?php echo $viewLevel->FIRST_NAME; ?></td>
						<td><?php echo $viewLevel->LAST_NAME; ?></td>
						<td><?php echo $viewLevel->LOCAL_PHONE;?></td>
                        <td>
							<i class="fa fa-usd"></i><?php echo " ".$viewLevel->BALANCE; ?>
							<input type="hidden" value="<?php echo $viewLevel->BALANCE; ?>" name="bal_<?php $viewLevel->CUSTOMER_ENC; ?>" id="bal_<?php $viewLevel->CUSTOMER_ENC; ?>" />
						</td> 
                        <td><i class="fa fa-usd"></i><?php echo " ".$viewLevel->CREDIT_LIMIT; ?></td> 
                        <td>
							<?php if($viewLevel->ENABLED==1){
								echo "YES";	
							} else {
								echo "NO";
							} ?>
						</td><?php
                        if($acc_type<="4"){ ?>
                            <td>
                                <a href="<?php echo base_url('ViewLevel/agentDiscounts?acc_enc='.$viewLevel->CUSTOMER_ENC.'&company='.$viewLevel->COMPANY) ?>" title=" Edit User"><i class="fa fa-product-hunt fa-2x" aria-hidden="true"></i></a>
                            </td> <?php
                        } ?>
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
		$('#view-level-table').DataTable({
			"aoColumnDefs" : [ 
						{"aTargets" : [7], "sClass":  "custom-td"}
			]
		});
    });
</script>


