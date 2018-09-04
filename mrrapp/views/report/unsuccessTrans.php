<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<div class="row marginBottom10px">
		<div class="col-sm-offset-3 col-sm-6">
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
					<td>Sold ID</td>
					<td>Date(EST)</td>
					<td>Product Name</td>
					<td>Amount $</td>
					<td>Vendor</td>
					<td>Store Name</td>
                    <td>Phone Number</td>
                    <td>Recharge</td>
					<td>Action</td>
                </tr>
			</thead>
			<tbody> <?php
				foreach($getreports as $getreport) 
				{  ?>	
					<tr>
						<td>Date(EST)</td>
						<td><?php 
							echo date("Y-m-d", strtotime($getreport->ACTIVITY_TIME));
							?>
						</td>
						<td><?php echo $getreport->COMPANY;?></td>
						<td><?php echo $getreport->FIRST_NAME." ".$getreport->LAST_NAME;?></td>
						<td><?php echo $getreport->BILLING_ID;?></td>
						<td><?php echo $getreport->ENTRY_TYPE_NAME;?></td>
						<td><?php echo $getreport->DESCRIPTION;?></td>
						<td><?php echo $getreport->DETAIL;?></td>
						<td></td>
					</tr> <?php
				}	?>
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
				{ extend: 'csv', text: 'CSV <i class="fa fa-angle-double-right" aria-hidden="true" style="color:#fff;"></i>', className: 'dataTableButton dataTableButtonMarginLeft10px' }
			],
			"aoColumnDefs" : [ 
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
		window.location.assign("unSuccessReportData?sDate="+sDate+"&eDate="+eDate+"&sTime="+sTime+"&eTime="+eTime);
	}
	function showAll(reportType){
		window.location.assign("unSuccessReportData?show=all");
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