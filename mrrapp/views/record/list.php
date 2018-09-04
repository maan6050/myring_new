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
			<button type="submit" name="submit" onClick="showAll();" class="btn btn-primary myRingButton">Show All</button>
		</div>
	</div><!--/.row-->
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
						<input class="form-control datepicker" type="text" size="15" readonly name="start_date" id="start_date" <?php if(isset($_GET["sDate"]) AND ($_GET["sDate"] !=="")) { echo "value=".$_GET["sDate"] ; }?>>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-group">
						<input class="form-control timepicker" type="text" size="8" name="sTime" id="sTime" <?php if(isset($_GET["sDate"]) AND ($_GET["sTime"] !=="")) { echo "value=".$_GET["sTime"] ; }?>>
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
						<input class="form-control datepicker" type="text" size="15" readonly name="end_date" id="end_date" <?php if(isset($_GET["eDate"]) AND ($_GET["eDate"] !=="")) { echo "value=".$_GET["eDate"] ; }?>>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-group">
						<input class="form-control timepicker" type="text" size="8" name="eTime" id="eTime" <?php if(isset($_GET["eTime"]) AND ($_GET["eTime"] !=="")) { echo "value=".$_GET["eTime"] ; }?>>
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
				<button type="submit" name="today" onClick="changedate(0);" class="btn btn-primary myRingButton marginLeft10px">Today</button>
				</div>
			</div><!--/.row-->	
		</div>
		<div class="col-sm-12 text-right">
			<button type="submit" name="submit" class="btn btn-primary myRingButton">SEARCH <i class="fa fa-search" aria-hidden="true" style="color:#fff;"></i></button>
			<button type="submit" name="submit" class="btn btn-primary myRingButton marginLeft10px">XLS <i class="fa fa-angle-double-right" aria-hidden="true" style="color:#fff;"></i></button>
			<button type="submit" name="submit" class="btn btn-primary myRingButton marginLeft10px">CSV <i class="fa fa-angle-double-right" aria-hidden="true" style="color:#fff;"></i></button>
		</div>
	</div>
	<div class="table-container">
		<table id="report_table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Date(EST)</td>
					<td>Time(EST)</td>
					<td>Customer</td>
					<td>Level</td>
					<td>Activity</td>
                    <td>IP</td>
                    <td>User Name</td>
                    <td>Description</td>
                </tr>
			</thead>
			<tbody> 
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>	
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
		ColdFusion.navigate("logReport_div.cfm","cfdiv_main_content",show_content);
		$('#report_table').dataTable();
	} );
	
	var show_content = function(){
		$("#cfdiv_main_content,#main").show();
		$('#report_table').dataTable();
		$( ".datepicker" ).datepicker();
		$('.timepicker').timepicker({
        timeFormat: 'H:i:s',maxTime: '24:00'});
		$('.timepicker').mask('99:99:99');
	};
	
	function filterBy(){
		var sDate = $("#start_date").val();
		var eDate = $("#end_date").val();
		var sTime = $("#sTime").val();
		var eTime = $("#eTime").val();
		$("#cfdiv_main_content,#main").hide();
		ColdFusion.navigate("logReport_div.cfm?sDate="+sDate+"&eDate="+eDate+"&sTime="+sTime+"&eTime="+eTime,"cfdiv_main_content",show_content);
	}
	
	function showAll(){
		$("#cfdiv_main_content,#main").hide();
		ColdFusion.navigate("logReport_div.cfm?&show=all","cfdiv_main_content",show_content);
	}
</script>
