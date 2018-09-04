<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<div class="row marginBottom10px text-right">
		<div class="col-sm-12">
			<button type="submit" name="submit" onClick="showAll('<?php echo $phone_number; ?>');" class="btn btn-primary myRingButton">Show All</button>
		</div>
	</div><!--/.row-->
	<div class="row marginBottom10px">
		<div class="col-sm-offset-3 col-sm-6">
			<div class="row marginBottom10px">
				<div class="col-sm-2">
					<div class="form-group">
					<label for="phone_number">Phone Number</label>
					</div>
				</div>
				<div class="col-sm-10">
					<div class="form-group">
						<input class="form-control" type="text" <?php if($_COOKIE["user_type"] == "258968745812378564") { echo "style='display:none'"; } ?> name="phone_number" value="<?php if(isset($_GET['phone_number'])) { echo $_GET["phone_number"];}?>" id="phone_number" pattern="[1-1]{1}[0-9]{10}" /> 
					</div>
				</div>
			</div><!--/.row-->	
			<div class="row marginBottom10px">
				<div class="col-sm-2">
					<div class="form-group">
						<label for="PRODUCT_NAME">Start date</label>
					</div>
				</div>	
				<div class="col-sm-5">
					<div class="form-group">
						<input class="form-control datepicker" type="text" size="15" readonly name="start_date" id="start_date" value="<?php if(isset($_GET["sDate"]) AND ($_GET["sDate"] !=="")) { echo $_GET["sDate"] ; } else { echo $sDate ;} ?>">
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-group">
						<input class="form-control timepicker" type="text" size="8" name="sTime" id="sTime" value="<?php if(isset($_GET["sDate"]) AND ($_GET["sTime"] !=="")) { echo $_GET["sTime"] ; } else { echo $sTime; }?>">
					</div>
				</div>
			</div><!--/.row-->
			<div class="row marginBottom10px">
				<div class="col-sm-2">
					<div class="form-group">
						<label for="PRODUCT_NAME">End date</label>
					</div>
				</div>	
				<div class="col-sm-5">
					<div class="form-group">
						<input class="form-control datepicker" type="text" size="15" readonly name="end_date" id="end_date" value="<?php if(isset($_GET["eDate"]) AND ($_GET["eDate"] !=="")) { echo $_GET["eDate"] ; } else { echo $eDate; }?>">
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-group">
						<input class="form-control timepicker" type="text" size="8" name="eTime" id="eTime" value="<?php if(isset($_GET["eTime"]) AND ($_GET["eTime"] !=="")) { echo $_GET["eTime"] ; } else { echo "24:00:00"; }?>">
					</div>
				</div>
			</div><!--/.row-->
			<div class="row marginBottom10px text-center">
				<div class="col-sm-12">
					<h5 style="color:red">Date Time is based on Eastern Time(EST)</h5>
				</div>
			</div>
			<div class="row marginBottom10px text-center">
				<div class="col-sm-12">
				<button type="submit" name="lastmonth" onClick="changedate('lastMonth');" class="btn btn-primary myRingButton" >Last Month</button>
				<button type="submit" name="lastweek" onclick="changedate('lastWeek');" class="btn btn-primary myRingButton marginLeft10px">Last Week</button>
				<button type="submit" name="yesterday" onclick="changedate(1);" class="btn btn-primary myRingButton marginLeft10px">Yesterday</button>
				</div>
			</div>
			<div class="row marginBottom10px text-center">
				<div class="col-sm-12">
				<button type="submit" name="thismonth" onClick="changedate('thisMonth');" class="btn btn-primary myRingButton">This Month</button>
				<button type="submit" name="thisweek" onClick="changedate('thisWeek');" class="btn btn-primary myRingButton marginLeft10px">This Week</button>
				<button type="submit" name="today" onClick="changedate('0');" class="btn btn-primary myRingButton marginLeft10px">Today</button>
				</div>
			</div><!--/.row-->	
		</div>
	</div>
	<div class="table-container">
		<table id="report_table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<?php if($userType == "owner"){ ?>

						<td>Date(EST)</td>
						<td>Product Name</td>
						<td>Amount $</td>
						<td>Cost $</td>
						<td>Comm %</td>
						<td>Comm $</td>
						<td>Profit $</td>
						<td>Store</td>
						<td>Buyer Phone</td>
						<td>Recharge Account</td>
						<td>Action</td>

					<?php } else if ($userType == "store") { ?>

						<td>Date(EST)</td>
						<td>Product Name</td>
						<td>Amount $</td>

							<?php if($phone_number == ""){ ?>

								<td align="center"><strong>Cost $</strong></td>
								<td align="center"><strong>Comm %</strong></td>
								<td align="center"><strong>Comm $</strong></td>
								<td align="center"><strong>Tax %</strong></td>
								<td align="center"><strong>Tax $</strong></td>

							<?php } ?>	

						<td align="center"><strong>Buyer Phone</strong></td>
                		<td align="center"><strong>Recharge Account</strong></td>
						<td align="center"><strong>Action</strong></td>		

					<?php } else { ?>
						<td>Sold ID</td>
						<td align="center"><strong>Date(EST)</strong></td>
						<td align="center"><strong>Product Name</strong></td>
						<td align="center"><strong>Product Type</strong></td>
						<td align="center"><strong>Amount</strong></td>

						<?php if($_COOKIE["user_type"] !== "258968745812378564") { ?>    
							<td align="center"><strong>Buyer Phone</strong></td>
						<?php } ?>

						<td align="center"><strong>Recharge Account</strong></td>
						<td align="center"><strong>Action</strong></td>	

					<?php } ?>
                </tr>
			</thead>
			<tbody> <?php
				$count = 0;
				$total_amount = 0;
				$total_cost = 0;
				$total_comm = 0;
				$total_tax = 0;
				$total_bal = 0;
				$total_profit = 0;
				foreach($getreports as $getreport) 
				{  
					if(($_COOKIE["user_type"] == "258968745812378564") AND ($getreport->AMOUNT !== "")) {
						$getreport->AMOUNT  = $getreport->AMOUNT  * -1;
					}?>	
					<tr <?php if($getreport->AMOUNT < "0") { echo "style='color:red';"; } ?> >
						<?php if($userType == "owner"){ ?>

							<td align="center"><?php $getreport->PROD_SOLD_ID; ?></td>	
							<td><?php 
								echo date("Y-m-d", strtotime($getreport->ACTIVITY_TIME));
								?>
							</td>
							<td align="center">
								<?php $getreport->getreport->PROD_NAME;?> 
								<?php if($getreport->PROD_PARENT_SOLD_ID > 0) {
									echo $getreport->PROD_PARENT_SOLD_ID." ".$getProductParent." (".$getreport->PROD_PARENT_SOLD_ID.")"; 
								} ?>
							</td>
							<td align="center">
								<?php echo "<i class='fa fa-usd'></i> ".($getreport->AMOUNT); ?>
							</td>
							<?php if($getreport->AMOUNT !== "") { 
								$total_amount = $total_amount + $getreport->AMOUNT;
							} ?>
							<td align="center">
								<?php echo "<i class='fa fa-usd'></i> ".$getreport->L1_AMOUNT ;?>
							</td>
							<?php if($getreport->L1_AMOUNT !== "") {
								$total_cost = $total_cost + $getreport->L1_AMOUNT;
							} ?>
							<td align="center">
								<?php echo $getreport->L1_DISC; ?>
							</td>
							<td align="center">
								<?php echo "<i class='fa fa-usd'></i> ".$getreport->L1_COMM; ?>
							</td>
							<?php if($getreport->L1_COMM !== "") {
								$total_comm = $total_comm + $getreport->L1_COMM;
							} ?>						
							<?php if($getreport->l1_profit !== ""){
								$total_profit = $total_profit + $getreport->l1_profit;
							} ?>
							<td align="center">
								<?php echo "<i class='fa fa-usd'></i> ".$getreport->l1_profit; ?>
							</td>
							<td align="center"><?php echo $getreport->storename;?></td>
							<td align="center"><?php echo $getreport->LOGIN_NAME; ?></td>
							<td align="center"><?php echo $getreport->PROD_RECHARGE; ?></td>
							<td align="center" nowrap="nowrap"> <?php
								if($getreport->PROD_PARENT_SOLD_ID == "0"){ ?>
									<input type="button" class="button" name="print" value="Print" onclick="javascript:return printResponse('<?php echo $getreport->PROD_SOLD_ID; ?>');" /> <?php
									if($getreport->PROD_STATUS == "1"){ ?>
										<input type="button" class="button" name="Reversed" value="Rev" style="background:##993300;" /> <?php
									} else { ?>
										<input type="button" class="button" name="reverse" value="Rev" onclick="javascript:return reverseTransaction('<?php echo $getreport->PROD_SOLD_ID; ?>');" /> <?php
									} ?>
									<input type="button" class="button" name="cst" value="CST" onclick="javascript:return openComplaintBox('<?php echo $getreport->PROD_SOLD_ID; ?>');" /> <?php 
								} ?>
							</td> <?php 
						} else if($userType == "store"){ 	
							if($getreport->conversion_rate == ""){
								$getreport->conversion_rate = "1";
							} ?>
							<td align="center"><?php $getreport->PROD_SOLD_ID; ?></td>	
							<td><?php 
								echo date("Y-m-d", strtotime($getreport->ACTIVITY_TIME));
								?>
							</td>
							<td align="center">
								<?php $getreport->getreport->PROD_NAME;?> 
								<?php if($getreport->PROD_PARENT_SOLD_ID > 0) {
									echo $getreport->PROD_PARENT_SOLD_ID." ".$getProductParent." (".$getreport->PROD_PARENT_SOLD_ID.")"; 
								} ?>
							</td>
							<td><?php 
								if($getreport->actual_amount  == "") {
									$getreports->actual_amount = 0;
								}
								if($_COOKIE["current_cur"] == "USA") {
									echo "<i class='fa fa-usd'></i> ".$getreport->AMOUNT;
								} else {
									echo $getreport->actual_amount." ".$getreport->storeCurrency;
								} ?>
							</td> <?php 
							if($getreport->AMOUNT !== "") {
								if($COOKIE["current_cur"] == "USA"){
									$total_amount = $total_amount + $getreport->AMOUNT;
								} else {
									$total_amount = $total_amount + $getreport->actual_amount;
								}
							} 
							if($phone_number == ""){
								if($getreport->L5_AMOUNT == "") {
									$getreports->L5_AMOUNT = "0";
								} ?>
								<td align="center"> <?php
									if($_COOKIE["current_cur"] == "USA") {
										echo $getreport->L5_AMOUNT;
									} else {
										echo ($getreport->L5_AMOUNT*$getreport->conversion_rate)." ".$storeCurrency;
									} ?>
								</td> <?php
								if($getreport->L5_AMOUNT !== "") {
									if($_COOKIE["current_cur"] == "USA"){
										$total_cost = $total_cost + $getreport->L5_AMOUNT;
									} else {
										$total_cost = $total_cost + ($getreport->L5_AMOUNT*$getreport->conversion_rate);
									}
								} ?>
								<td align="center"><?php echo $getreport->L5_DISC; ?></td>
								<td align="center"> <?php
									if($getreport->L5_COMM == ""){
										$getreport->L5_COMM = 0;
									}
									if($_COOKIE["current_cur"] == "USA"){
										$getreport->L5_COMM;
									} else  {
										$getreport->L5_COMM*$getreport->conversion_rate." ".$storeCurrency;
									} ?>
								</td> <?php
								if($getreport->L5_COMM !== ""){
									if($_COOKIE["current_cur"] == "USA"){
										$total_comm = $total_comm + $getreport->L5_COMM;
									} else {
										$total_comm = $total_comm + ($getreport->L5_COMM*$getreport->conversion_rate);
									}
								} ?>
								<td align="center"><?php echo $getreport->L7_TAX; ?></td>
								<td align="center"> <?php
									if($_COOKIE["current_cur"] == "USA"){
										$getreports->L7_TAX_AMOUNT;
									} else {
										if($getreport->L7_TAX_AMOUNT !== ""){
											echo $getreport->L7_TAX_AMOUNT*$getreport->conversion_rate;
										} else {
											echo $getreport->L7_TAX_AMOUNT;
										} 
										echo $storeCurrency;
									} ?>
								</td> <?php
								if($getreport->L7_TAX_AMOUNT !== ""){
									if($_COOKIE["current_cur"] == "USA"){
										$total_tax = $total_tax + $getreport->L7_TAX_AMOUNT;
									} else {
										$total_tax = $total_tax + ($getreport->L7_TAX_AMOUNT*$getreport->conversion_rate);
									}
								}
							}?>
							<td align="center"><?php echo $getreport->LOGIN_NAME; ?></td>
							<td align="center"><?php echo $getreport->PROD_RECHARGE;?></td>
							<td align="center" nowrap="nowrap"> <?php
								if($getreport->PROD_PARENT_SOLD_ID == "0") { ?>
									<input type="button" class="button" name="buy" value="Buy" onclick="javascript:return getproductpin2('<?php echo $getreport->PROD_ID; ?>','<?php echo $getreport->PROD_TYPE_ID; ?>','<?php echo $getreport->PROD_SOLD_ID; ?>');" />
								
									&nbsp;
									<input type="button" class="button" name="print" value="Print" onclick="javascript:return printResponse('<?php echo $getreport->PROD_SOLD_ID; ?>');" />
									<input type="button" class="button" name="cst" value="CST" onclick="javascript:return openComplaintBox('<?php echo $getreport->PROD_SOLD_ID; ?>');" /> <?php
								} ?>
							</td> <?php 
						} else { ?>
							<td align="center"><?php echo $getreport->PROD_SOLD_ID; ?></td>
							<td><?php 
								echo date("Y-m-d", strtotime($getreport->ACTIVITY_TIME));
								?>
							</td>
							<td align="center">
								<?php $getreport->getreport->PROD_NAME;?> 
								<?php if($getreport->PROD_PARENT_SOLD_ID > 0) {
									echo $getreport->PROD_PARENT_SOLD_ID." ".$getProductParent." (".$getreport->PROD_PARENT_SOLD_ID.")"; 
								} ?>
							</td>
							<td align="center"><?php echo $getreport->PROD_TYPE_NAME; ?></td>	
							<td align="center">  <?php
							if($COOKIE["user_type"] == "258968745812378564"){
								echo "<i class='fa fa-usd'></i> ".$getreport->AMOUNT."</td>";
								if($getreport->AMOUNT !== ""){
									$total_amount = $total_amount + $getreport->AMOUNT;
								}
							} else {
								echo "<i class='fa fa-usd'></i> ".$getreport->FACE_VALUE."</td>";
								if($getreport->FACE_VALUE !== ""){
									$total_amount = $total_amount + $getreport->FACE_VALUE;
								}
							}   
							if($_COOKIE["user_type"] !== "258968745812378564"){ ?>    
								<td align="center"> <?php echo $getreport->LOGIN_NAME; ?></td> <?php
							} ?>
							<td align="center"><?php echo $getreport->PROD_RECHARGE; ?></td>
							<td align="center" nowrap="nowrap"> <?php
								if($getreport->PROD_PARENT_SOLD_ID = "0"){
									if($_COOKIE["user_type"] == "258968745812378564"){ ?>
										<input type="button" class="button" name="buy" value="Buy" onclick="javascript:return getproductpin2('#getreports.PROD_ID#','#getreports.PROD_TYPE_ID#','#getreports.PROD_SOLD_ID#');" />
									
										<input type="button" class="button" name="print" value="Print" onclick="javascript:return printResponse('#getreports.PROD_SOLD_ID#');" /> <?php
									} ?>
									<input type="button" class="button" name="cst" value="CST" onclick="javascript:return openComplaintBox('#getreports.PROD_SOLD_ID#');" /> <?php
								} ?>
							</td> <?php	
						} ?>	
					</tr> <?php
				} ?>
			</tbody>
		</table>
	</div>
</div>	
<script>
	$(document).ready(function(){
		$('.timepicker').each(function(){
			$(this).timepicker();
		});

		$('.datepicker').each(function(){
			$(this).datepicker();
		});

	});

	$(document).ready(function() {
		$.fn.dataTable.ext.buttons.search = {
			text: 'SEARCH',
			action: function ( e, dt, node, config ) {
				dt.ajax.search();
			}
		};
		$('#report_table').DataTable( {
			dom: 'Bfrtip',
			buttons: [
				{ extend: 'search', text: 'SEARCH <i class="fa fa-search" aria-hidden="true" style="color:#fff;"></i>', className: 'dataTableButton', action: function ( e, dt, node, config ) {
                    return filterBy();
                } },
				{ extend: 'excel', text: 'XLS <i class="fa fa-angle-double-right" aria-hidden="true" style="color:#fff;"></i>', className: 'dataTableButton dataTableButtonMarginLeft10px' },
				{ extend: 'csv', text: 'CSV <i class="fa fa-angle-double-right" aria-hidden="true" style="color:#fff;"></i>', className: 'dataTableButton dataTableButtonMarginLeft10px' },
				{ extend: 'print', text: 'PRINT <i class="fa fa-angle-double-right" aria-hidden="true" style="color:#fff;"></i>', className: 'dataTableButton dataTableButtonMarginLeft10px' }
			],
			"aoColumnDefs" : [ 
                {"aTargets" : [0], "sClass":  "custom-td"},
				{"aTargets" : [1], "sClass":  "custom-td"},
                {"aTargets" : [2], "sClass":  "custom-td"},
				{"aTargets" : [3], "sClass":  "custom-td"},
				{"aTargets" : [4], "sClass":  "custom-td"}
			]
		});
	});

	function filterBy(){
		var sDate = $("#start_date").val();
		var eDate = $("#end_date").val();
		var sTime = $("#sTime").val();
		var eTime = $("#eTime").val();
		var phone_number = $("#phone_number").val();
		window.location.assign("prodSoldDataReport?phone_number="+phone_number+"&sDate="+sDate+"&eDate="+eDate+"&sTime="+sTime+"&eTime="+eTime);
	}
	function showAll(phone_number){
		window.location.assign("prodSoldDataReport?phone_number="+phone_number+"&show=all");
	}
	function changedate($val){
		if($val == "lastMonth"){
			$("#start_date").val('<?php echo date('m/d/y', strtotime('first day of last month'));?>');
			$("#end_date").val('<?php echo date('m/d/y', strtotime('last day of last month'));?>');
		}
		if($val == "lastWeek"){
			$("#start_date").val('<?php echo date('m/d/y', strtotime('monday last week'));?>');
			$("#end_date").val('<?php echo date('m/d/y', strtotime('sunday last week'));?>');
		}
		if($val == "1"){
			$("#start_date").val('<?php echo date('m/d/y',strtotime("-1 days"));?>');
			$("#end_date").val('<?php echo date("m/d/y");?>');
		}
		if($val == "thisMonth"){
			$("#start_date").val('<?php echo date("m/d/y", strtotime("first day of this month"));?>');
			$("#end_date").val('<?php echo date("m/d/y");?>');
		}
		if($val == "thisWeek"){
			$("#start_date").val('<?php echo date("m/d/y", strtotime("Monday this week"));?>');
			$("#end_date").val('<?php echo date("m/d/y");?>');
		}
		if($val == "0"){
			$("#start_date").val('<?php echo  date("m/d/y");?>');
			$("#end_date").val('<?php echo  date("m/d/y");?>');
		}
	}
</script>