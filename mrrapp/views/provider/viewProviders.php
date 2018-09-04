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
			<a href="<?= base_url('Provider/addProvider'); ?>" class="myRingButton pull-right">Add Provider</a>
		</div>
	</div>
	<div class="table-container">
		<table id="provider-table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Name</td>
					<td>Contact</td>
					<td>Phone Number</td>
					<td>Active</td>
					<td>Edit</td>
				</tr>
			</thead>
			<tbody> <?php
				foreach($providers as $provider) 
				{  ?>
					<tr>
						<td><?php echo $provider->N_PROVIDER; ?></td>
						<td><?php echo $provider->NP_CONTACT; ?></td>
						<td><?php echo $provider->NP_PHONE; ?></td>
						<td>
							<?php if($provider->N_STATUS==1){
								echo "YES";	
							} else {
								echo "NO";
							} ?>
						</td>
						<td>
							<a href="<?php echo base_url('Provider/addProvider?NP_ID='.$provider->NP_ID) ?>" title=" Edit Provider"><i class="fa fa-edit fa-2x" aria-hidden="true"></i></a>
						</td>
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
		$('#provider-table').DataTable({
			"aoColumnDefs" : [ 
				{"aTargets" : [4], "sClass":  "custom-td"}
			]
		});
    });
</script>