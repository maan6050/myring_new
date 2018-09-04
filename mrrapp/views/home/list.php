<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<div class="row">
		<div class="col-sm-12 marginBottom10px">
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
			<tbody>
			</tbody>
		</table>
	</div>
</div>
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
?>		
<script type="text/javascript">
$(document).ready(function() {
    $('#customer-table').DataTable({
        "ajax": {
            url : "<?php echo site_url("Customer/customer_page") ?>",
			type : 'GET'
		},
		"aoColumnDefs" : [ 
                    {"aTargets" : [8], "sClass":  "custom-td"},
					{"aTargets" : [9], "sClass":  "custom-td"},
					{"aTargets" : [10], "sClass":  "custom-td"} 
        ]
	});
});
</script>
