<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
?>
<div class="container">
	<h1 class="page-title">Sales By <?= "$title"; ?>
		<?php if($report_type == "4") { ?>
			<a href="reports?report=5">(Show Summary)</a> <?php
		}else if($report_type == "5") { ?>
			<a href="reports?report=4">(Show Details</a>) <?php
		} ?>
	</h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<div class="row marginBottom10px text-right ">
		<div class="col-sm-4 pull-right"> <?php
			if(isset($_GET["report"]) AND $_GET["report"] == "3"){
				if(($accountType >= "2") AND ($accountType <= "4")) { ?>
					<table class="table table-bordered table-striped table-hover">
						<tr align="center">
							<td>
								<b>Balance</b>
							</td>
							<td>
								<b>Available</b>
							</td>
						</tr>
						<tr align="center">
							<td> 
								<?php echo "<i class='fa fa-usd'></i> ".$getHeaders[0]->BALANCE.""; ?>
							</td>
							<td><?php 
								if($getHeaders[0]->BALANCE == ""){
									$getHeaders[0]->BALANCE ="0";
								}
								if($getHeaders[0]->CREDIT_LIMIT == ""){
									$getHeaders[0]->CREDIT_LIMIT ="0";
								}
								if($getHeaders[0]->TOTALSUM == ""){
									$getHeaders[0]->TOTALSUM ="0";
								}
								$avalable_balance = ($getHeaders[0]->TOTALSUM)-($getHeaders[0]->BALANCE + $getHeaders[0]->CREDIT_LIMIT) ;
								$formatedNumber = number_format($avalable_balance, 2, '.', '');
								echo "(<i class='fa fa-usd'></i> ".$formatedNumber.")"; ?>
							</td>
						</tr>
					</table> <?php
				}
			}	?>
		</div>
	</div><!--/.row-->
	<div class="row marginBottom10px text-right">
		<div class="col-sm-12">
			<button type="submit" name="submit" onClick="showAll('<?php echo $report_type; ?>');" class="btn btn-primary myRingButton">Show All</button>
		</div>
	</div><!--/.row-->
	<div class="row marginBottom10px">
		<div class="col-sm-offset-3 col-sm-6">
			<div class="row marginBottom10px">
				<div class="col-sm-2">
					<div class="form-group">
					<label for="bType"><?= "$title"; ?></label>
					</div>
				</div>
				<div class="col-sm-10">
					<div class="form-group">
						<select name="user" id="user" class="form-control">
						<option value="">Select <?php if($title == "End USER") { echo "User";} else { echo $title;} ?> </option> <?php
							if(($_GET["report"] == "1") AND ($store == "1") AND ($_COOKIE["CURRENT_CUR"] !== "USA")) {
								foreach($repSearchField as $repSearch) { 
									if($repSearch->OP_VAL == "4" OR $repSearch->OP_VAL == "20") { ?>
										<option value="<?php echo $repSearch->OP_VAL; ?>" <?php if(isset($_GET["user"]) AND $_GET["user"] == $repSearch->OP_VAL){ echo "selected";} ?> > <?php echo $repSearch->OP_NAME; ?></option> <?php
									}
								}
							} else {
								foreach($repSearchField as $repSearch) { ?>	
									<option value="<?php echo $repSearch->OP_VAL; ?>" <?php if(isset($_GET["user"]) AND $_GET["user"] == $repSearch->OP_VAL){ echo "selected";} ?> > <?php echo $repSearch->OP_NAME; ?></option> <?php
								}
							}	?>				
						</select>
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
		<div class="col-sm-12 text-right"> <?php
			if($sub_dis == "1" AND $report_type == "3" AND $user !== ""){ ?>
				<button type="submit" name="exportToINV" title="Export to INV file" class="btn btn-primary myRingButton marginLeft10px" onclick="exportFile('INV')">INV <i class="fa fa-angle-double-right" aria-hidden="true" style="color:#fff;"></i></button> <?php
			} ?>
		</div>
	</div>
	<div class="table-container">
		<table id="report_table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Date(EST)</td> <?php
					if(($report_type == "2") AND ($sub_dis == "1") OR ($store == "1")){
						if($store !== "1") { ?>
							<td>Store Name</td> <?php
						} ?>
						<td>Product</td>
						<td>Username</td>
						<td>PIN / Phone</td>
						<td>Amount</td>
						<td>Serial</td>
						<td>Product Type</td>
						<td>Auth. Number</td> <?php
						if($report_type == "2" AND $store == "1"){ ?>
							<td>My Commission</td> <?php
						}
						if($report_type == "2"){ ?>
							<td>Action</td> <?php
						}
					} 
					if($report_type == "3"){ ?>
						<td><?php echo $title; ?></td> <?php
					}  else if(($owner == "1") OR ($master == "1")){ ?>
						<td>Provider</td> <?php
					}
					if (($report_type == "2")  AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== "1")){ ?>
						<td>Product Type</td> <?php
					} else if(($report_type !== "2")  AND ($sub_dis !== "1") OR ($report_type !== "2") AND ($store !== "1")) { ?>
						<td>Product Type</td> <?php
					} 
					if(($report_type == "2")  AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== "1")){?>
						<td>Product</td> <?php
					} else if(($report_type !== "2")  AND ($sub_dis !== "1") OR ($report_type !== "2") AND ($store !== "1")) { ?>
						<td>Product</td>
						<td> <?php
							if($owner == "1" and $report_type == "4"){
								echo "Recharge";
							} else {
								echo "Entry Type";
							} ?>
						</td> <?php
					} 
					
					if($owner == "1" AND $report_type == "4"){ ?>
						<td>Cost</td> <?php
					}
					if(($report_type == "2")  AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== "1")) { ?>
						<td>Quantity</td>
						<td>Revenue</td>	
						<td>Percentage</td>	
						<td>My Commission</td> <?php
				 	} else if (($report_type !== "2")  AND ($sub_dis !== "1") OR ($report_type !== "2") AND ($store !== "1")) { ?>
						<td>Quantity</td>
						<td>Revenue</td>	
						<td>Percentage</td>	
						<td>My Commission</td> <?php
					} ?>
				</tr>
			</thead>
			<tbody> <?php
				$count = 0;
				$total_amnt = 0;
				$total_profit = 0;
				$total_quan = 0;
				$total_avg = 0;
				$total_deno = 0;
				$total_cost = 0;
				foreach($getreports as $getreport) 
				{  ?>	
					<tr <?php if($Entry_Type == "10") { echo "style='color:red;'"; } ?> >
						<td><?php 
							echo date("Y-m-d", strtotime($getreport->ACTIVITY_TIME));
							?>
						</td> <?php
						if(($report_type == "2") AND (($sub_dis == "1") OR ($store == "1"))){
							if($store !== "1") { ?>
								<td> <?php echo $getreport->COMPANY; ?></td> <?php
							} ?>
							<td><?php echo $getreport->PROD_NAME; ?></td>
							<td><?php echo $getreport->CUSTOMER_NAME; ?></td>
							<td><?php echo $getreport->LOCAL_PHONE; ?></td>
							<td>
								<span class="amount"> <?php
									if(($store == "1") AND ($_COOKIE["CURRENT_CUR"] !== "USA")) {
										echo ($getreport->SUM_AMNT*$_COOKIE["CONVERSION_RATE"])." ".$storeCurrency;
									} else {
										echo "<i class='fa fa-usd'></i>".$getreport->SUM_AMNT;
									} ?>
								</span>
							</td>	
							<td><?php echo $getreport->PROD_SOLD_ID; ?></td>
							<td><?php echo $getreport->PROD_TYPE_NAME; ?></td> <?php
							$AuthCode = "";
							////////////////////////This is Pending//////////////////
							// if($getreport->PROD_RESPONSE_INFO !== ""){
							// 	<cfsavecontent variable="test">
							// 		<cfoutput>#PROD_RESPONSE_INFO#</cfoutput>
							// 	</cfsavecontent>
							// 	<cfset arr = test.split("&,&")>
								
							// 	<cfif arr[1] NEQ "" >
							// 		<cfif ListLen(arr[1],'=') GT 1>
							// 			<cfset AuthCode = ListGetAt(arr[1],2,'=')>
							// 		</cfif>
							// 	</cfif>
							// } ?>
							<td><?php echo $getreport->AuthCode;?></td> <?php
							if($report_type = "2" AND $store == "1") { ?>
								<td><?php echo $getreport->SUM_PRO; ?></td>	 <?php
							}
							if($report_type == "2") { ?>
								<td align="center">
									<button type="button" name="print" onclick="javascript:return printResponse('<?php echo $getreports->PROD_SOLD_ID; ?>');" class="btn btn-primary myRingButton">Print <i class="fa fa-print" aria-hidden="true" style="color:#fff;"></i></button>
								</td> <?php
							}
						}
						if($report_type == "3"){ ?>
							<td><?php echo $getreports->CUSTOMER_NAME; ?></td> <?php
						} else if(($owner == "1") OR ($master == "1")){ ?>
							<td><?php echo $getreports->N_PROVIDER; ?></td> <?php
						}
						if(($report_type == "2")  AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== "1")){ ?>
							<td><?php echo $getreports->PROD_TYPE_NAME;?></td> <?php
						}else if(($report_type !== "2")  AND ($sub_dis !== "1") OR ($report_type !== "2") AND ($store !== "1")) { ?>
							<td><?php echo $getreports->PROD_TYPE_NAME; ?></td> <?php
						}    
						if(($report_type == "2") AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== 1)){ ?>
							<td><?php echo $getreports->PROD_NAME; ?></td> <?php
						} else if(($report_type !== "2")  AND ($sub_dis !== "1") OR ($report_type !== "2" AND $store !== "1")){ ?>
							<td><?php echo $getreports->PROD_NAME; ?></td>
							<td> <?php
								if(($owner == "1") AND ($report_type == "4")){
									echo $getreports->PROD_RECHARGE;
								} else {
									if($Entry_Type == "10"){
										echo "Refund";
									} else {
										echo "Recharge";
									}
								} ?>
							</td> <?php
						}
						if(($owner == "1") AND ($report_type == "4")){ ?>
							<td align="center">
								<?php echo "<i class='fa fa-usd'></i>".$getreports->COST; ?>
							</td> <?php
						}
						if(($report_type == "2") AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== "1")){ ?>
							<td><?php echo $getreports->QUANTITY; ?></td>
							<td>
								<span class="amount"> <?php echo "<i class='fa fa-usd'></i>".$getreport->SUM_AMNT; ?></span>
							</td>	
							<td><?php echo $getreports->AVG_DIS."%"; ?></td>	
							<td><?php echo $getreports->SUM_PRO; ?></td> <?php
						}else if(($report_type !== "2")  AND ($sub_dis !== "1") OR ($report_type !== "2") AND $store !== "1") { ?>
							<td><?php echo $getreports->QUANTITY; ?></td>
							<td>
								<span class="amount"> <?php
									if(($store == "1") AND ($_COOKIE["CURRENT_CUR"] !== "USA")) {
										echo ($getreport->SUM_AMNT*$_COOKIE["CONVERSION_RATE"])." ".$storeCurrency;
									} else {
										echo "<i class='fa fa-usd'></i>".$getreport->SUM_AMNT;
									} ?>?>
								</span>
							</td>	
							<td><?php echo $getreports->AVG_DIS."%"; ?></td>	
							<td> <?php
								if(($store == "1") AND ($_COOKIE["CURRENT_CUR"] !== "USA")){
									echo ($getreport->SUM_PRO*$_COOKIE["CONVERSION_RATE"])." ".$storeCurrency;
								} else {
									echo $getreport->SUM_PRO;
								} ?>
							</td>	<?php
						} ?>
					</tr> <?php
					if(!empty($getprodFeeProducts)){ ?>
						<tr <?php if($getprodFeeProducts->Entry_Type == "10") { echo "style=color:red;"; }?>>
							<td><?php 
								echo date("Y-m-d", strtotime($getprodFeeProducts->ACTIVITY_TIME));
								?>
							</td> <?php
							if(($report_type == "2") AND (($sub_dis == "1") OR ($store == "1"))){
								if($store != "1"){ ?>
									<td><?php echo $getreport->COMPANY; ?></td> <?php
								} ?>
								<td><?php echo $getprodFeeProducts->PROD_NAME."of".$getreport->PROD_NAME; ?></td>
								<td><?php echo $getreport->CUSTOMER_NAME; ?></td>
								<td><?php echo $getreport->LOCAL_PHONE; ?></td>
								<td>
									<span class="amount"> <?php
										if(($store == "1") AND ($_COOKIE["CURRENT_CUR"] !== "USA")){
											echo ($getprodFeeProducts->SUM_AMNT*$_COOKIE["CONVERSION_RATE"])." ".$storeCurrency;
										} else {
											echo $getprodFeeProducts->SUM_AMNT;
										} ?> 
									</span>
								</td>	
								<td><?php echo $getreport->PROD_SOLD_ID; ?></td>
								<td><?php echo $getprodFeeProducts->PROD_TYPE_NAME; ?></td> <?php
								$AuthCode = "";
								//////////////////////This is Pending
								// if($getprodFeeProducts->PROD_RESPONSE_INFO !== ""){
								// 	<cfsavecontent variable="test">
								// 		<cfoutput>#getprodFeeProducts.PROD_RESPONSE_INFO#</cfoutput>
								// 	</cfsavecontent>
								// 	<cfset arr = test.split("&,&")>
									
								// 	<cfif arr[1] NEQ "" >
								// 		<cfif ListLen(arr[1],'=') GT 1>
								// 			<cfset AuthCode = ListGetAt(arr[1],2,'=')>
								// 		</cfif>
								// 	</cfif>
								// } ?>
								<td><?php echo $getreport->AuthCode; ?></td> <?php
								if($report_type == "2" AND $store == "1"){ ?>
									<td><?php echo $getprodFeeProducts->SUM_PRO; ?></td> <?php	
								} 
								if($report_type == "2") { ?>
									<td>-</td>	<?php 
								} 
							}
							if($report_type == "3"){ ?>
								<td><?php echo $getreport->CUSTOMER_NAME; ?></td> <?php
							} else if($owner == "1" OR $master == "1"){ ?> 
								<td ><?php echo $getreport->N_PROVIDER; ?></td> <?php
							}
							if(($report_type == "2")  AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== "1")){ ?>
								<td>-</td> <?php
							} else if(($report_type !== "2") AND ($sub_dis !== "1") OR ($report_type !== "2") AND ($store !== 1)) { ?>
								<td>-</td> <?php
							}    
							if(($report_type == "2") AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== "1")){ ?>
								<td><?php echo $getprodFeeProducts->PROD_NAME."of".$getreport->PROD_NAME;?></td> <?php
							} else if(($report_type !== "2") AND ($sub_dis !== "1") OR ($report_type !== "2") AND ($store !== "1")){ ?>
								<td>
									<?php echo $getprodFeeProducts->PROD_NAME."of". $getreport->PROD_NAMEPROD_NAME;?>
								</td>
								<td> <?php
									if(($owner == "1") AND ($report_type == "4")){
										echo $getprodFeeProducts->PROD_RECHARGE;
									} else {
										if($Entry_Type == "10"){
											echo "Refund";
										} else { 
											echo "Recharge";
										}
									}?>
								</td> <?php
							}
							if($owner == "1" AND $report_type == "4"){ ?>
								<td><?php echo "<i class='fa fa-usd'></i>".$getprodFeeProducts->COST;?></td> <?php
							}
							if(($report_type == "2")  AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== "1")){ ?>
								<td>-</td>
								<td>
									<span class="amount"><?php echo "<i class='fa fa-usd'></i>".$getprodFeeProducts->SUM_AMNT; ?>
									</span>
								</td>	
								<td><?php echo $getprodFeeProducts->AVG_DIS."%"; ?></td>	
								<td><?php echo $getprodFeeProducts->SUM_PRO;?></td> <?php
							} else if(($report_type !== "2")  AND ($sub_dis !== "1") OR ($report_type !== "2") AND ($store !== "1")){ ?>
								<td>-</td>
								<td>
									<span class="amount"> <?php
									if(($store == "1") AND ($_COOKIE["CURRENT_CUR"] !== "USA")){
										echo ($getprodFeeProducts->SUM_AMNT*$_COOKIE["CONVERSION_RATE"])." ".$storeCurrency;
									} else {
										echo "<i class='fa fa-usd'></i>".$getprodFeeProducts->SUM_AMNT;
									} ?>
									</span>
								</td>	
								<td><?php echo $getprodFeeProducts->AVG_DIS."%"; ?></td>	
								<td> <?php
									if(($store == "1") AND ($_COOKIE["CURRENT_CUR"] !== "USA")){
										echo ($getprodFeeProducts->SUM_PRO*$_COOKIE["CONVERSION_RATE"])." ".$storeCurrency;
									} else {
										echo $getprodFeeProducts->SUM_PRO;
									} ?>
								</td><?php	
							} ?>
						</tr> <?php
						if($getprodFeeProducts->SUM_AMNT  !== ""){ 
							$total_amnt = $total_amnt + $getprodFeeProducts->SUM_AMNT;
						}
					}
					if($getreports->SUM_AMNT  !== ""){ 
						$total_amnt = $total_amnt + $getreports->SUM_AMNT;
					}
					if($getreports->QUANTITY !== ""){
						$total_quan = $total_quan + $getreports->QUANTITY;
					}
					if($getreports->SUM_PRO  !== ""){
						$total_profit = $total_profit + $getreports->SUM_PRO;
					}
					if($getreports->AVG_DIS !== ""){
						$total_avg = $total_avg + $getreports->AVG_DIS;
					}
					if($getreports->FACE_VALUE !== ""){
						$total_deno = $total_deno + $getreports->FACE_VALUE;
					}
					if($getreports->COST !== ""){
						$total_cost = $total_cost + $getreports->COST;
					}
					$count = $count + 1;
				}?>
			</tbody>
			<tfoot>
			<tr>
					<td>Totals</td> <?php
					if(($report_type == "2" AND $sub_dis == "1" ) OR ($report_type == "2") AND ($store == "1"))
					{
						if($store !== "1"){ ?>
							<td></td> <?php
						} ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td> <?php 
							if($store == "1" AND $_COOKIE["CURRENT_CUR"] !== "USA"){
								echo $total_amnt*$_COOKIE["CONVERSION_RATE"]."".$storeCurrency;
							} else {
								echo "<i class='fa fa-usd'></i>".$total_amnt;
							} ?>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td> <?php
						if($report_type == "2" AND $store == "1") { ?>
							<td><?php echo $total_profit; ?></td> <?php
						}
						if($report_type == "2"){ ?>
							<td>&nbsp;</td> <?php
						}
					}
					if($report_type == "3"){ ?>
						<td>&nbsp;</td> <?php
					} else if($owner == "1" OR $master == "1") { ?>
						<td>&nbsp;</td> <?php
					}
					if(($report_type == "2") AND ($sub_dis == "1") AND ($report_type == "2") AND ($store == "1")){ ?>
						<td>&nbsp;</td> <?php
					}
					if(($report_type == "2") AND ($sub_dis !== "1") AND ($report_type == "2") AND ($store !== "1")){ ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><?php echo $total_quan; ?></td>
						<td><?php echo "<i class='fa fa-usd'></i>".$total_amnt; ?></td>
						<td> <?php
							if($count > "0"){
								echo ($total_avg/$count)." %";
							} else{ 
								echo "0.00 %";
							} ?>
						</td>
						<td><?php echo $total_profit; ?></td> <?php
					} else if(($report_type !== "2")  AND ($sub_dis !== "1") OR ($report_type !== "2") AND ($store !== "1")) { ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td> <?php
						if($owner == "1" AND $report_type == "4"){ ?>
							<td><?php echo "<i class='fa fa-usd'></i>".$total_cost; ?></td> <?php
						} ?>
						<td><?php echo $total_quan; ?></td>
						<td> <?php
							if($store == "1" AND $_COOKIE["CURRENT_CUR"] !== "USA"){
								echo $total_amnt*$_COOKIE["CONVERSION_RATE"]."".$storeCurrency;
							} else {
								echo "<i class='fa fa-usd'></i>".$total_amnt;
							} ?>
						</td>
						<td> <?php
							if($count > "0"){
								echo ($total_avg/$count)." %";
							} else {
								echo "0.00 %";
							} ?>
						</td>
						<td> <?php
							if($store == "1" AND$_COOKIE["CURRENT_CUR"] !== "USA"){
								#NumberFormat(total_profit*cookie.CONVERSION_RATE,0.0000)# #storeCurrency#
								echo $total_profit*$_COOKIE["CONVERSION_RATE"]."".$storeCurrency;
							} else {
								echo $total_profit;
							} ?>
						</td> <?php
					} ?>
				</tr>
			</tfoot>
		</table>
		<input type="hidden" id="reportType" value="<?php echo $report_type; ?>" />
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
				{ extend: 'csv', text: 'CSV <i class="fa fa-angle-double-right" aria-hidden="true" style="color:#fff;"></i>', className: 'dataTableButton dataTableButtonMarginLeft10px' }
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
		var user = $("#user").val();
		window.location.assign("viewReports?user="+user+"&sDate="+sDate+"&eDate="+eDate+"&sTime="+sTime+"&eTime="+eTime);
	}
	function showAll(reportType){
		window.location.assign("viewReports?report="+reportType+"&show=all");
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


