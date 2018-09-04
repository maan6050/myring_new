<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<form name="add_product_type_form" id="add_product_type_form" method="post" action="<?= base_url('Product/productTypeformSubmit'); ?>" enctype="multipart/form-data">
		<input type="hidden" name="__auth_key" value="4c4d33303139372e31342e3038352e3535393231">
		<input type="hidden" name="__confirmation_page" value="">
		<input type="hidden" name="__email_subject" value="">
        <input type="hidden" name="prod_typeid" value="<?php if(isset($_GET["prod_typeid"])){ echo $_GET["prod_typeid"]; } else { echo "0"; } ?>">
        <div class="row"> 
			<div class="col-sm-offset-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="prod_type">Product Type Name</label>
					<input class="form-control" type="text" size="25" name="prod_type" id="prod_type" value="<? if(isset($_GET["prod_typeid"])){ echo $getprod_types_prodid[0]->PROD_TYPE_NAME; } ?>" required="yes" title="Please fill in prod type value.">
				</div>
				<div class="form-group">
					<label for="status">Status</label>
					<select class="form-control" name="status" id="status" title="Please select product status." required >
						<option value="">Select Status</option>
						<option value="1" <?php if(isset($_GET["prod_typeid"])) { if($getprod_types_prodid[0]->PROD_TYPE_STATUS==1){ echo "selected"; } } ?>>Enabled</option>
						<option value="0" <?php if(isset($_GET["prod_typeid"])) { if($getprod_types_prodid[0]->PROD_TYPE_STATUS==0){ echo "selected"; } } ?>>Disabled</option> 
					</select>
				</div>
				<div class="form-group">
					<label for="prod_type_image">Product Icon</label>
					<input type="file" size="20" name="prodImage" class="form-control"/>
					<input type="hidden" name="old_image" value="<? if(isset($_GET["prod_typeid"])){ echo $getprod_types_prodid[0]->PROD_ICON; } ?>"> <?php
					if(isset($_GET["prod_typeid"])){
						if($getprod_types_prodid[0]->PROD_ICON !== ""){ ?>
							<br />
							<img align="top" src="<?php echo base_url("productTypes/").$getprod_types_prodid[0]->PROD_ICON; ?>" width="100" alt="<?php $getprod_types_prodid[0]->PROD_ICON?>"/> <?php
						} 
					}?>
				</div>
				<?php if(isset($_GET["prod_typeid"])){ ?>
                    <input type="hidden" name="prod_typeid" value="<?php echo $_GET["prod_typeid"]; ?>";>
            	<?php } ?>
				<button type="submit" name="submit" class="btn btn-primary myRingButton pull-left"><?php if(isset($_GET["prod_typeid"])){ echo "Update";} else { echo "Submit"; } ?></button>
				<a href="<?= base_url('Product/addProductType'); ?>" class="myRingButton pull-left marginLeft10px">Cancel</a>
			</div>	
		</div>
	</from>	
	<br>
	<div class="table-container">
		<table id="add_product_type" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Product Type Name</td>
					<td>Product Status</td>
					<td>Edit</td>
				</tr>
			</thead>
			<tbody> <?php
				foreach($getprod_types as $getprod_type) 
				{  ?>
					<tr>
						<td><?php echo $getprod_type->PROD_TYPE_NAME; ?></td>
						<td><?php 
							if($getprod_type->PROD_TYPE_STATUS == "1"){
								echo "YES";	
							} else {
								echo "NO";
							} ?>
						</td>
						<td>
							<a href="<?php echo base_url('Product/addProductType?prod_typeid='.$getprod_type->PROD_TYPE_ID) ?>" title=" Edit Product Type"><i class="fa fa-edit fa-2x" aria-hidden="true"></i></a>
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
		$('#add_product_type').DataTable({
			"aoColumnDefs" : [ 
				{"aTargets" : [2], "sClass":  "custom-td"}
			]
		});
		$('#add_product_type_form').validate({
            errorPlacement: function(error, element){
                // Adiciona el error dentro de la etiqueta asociada.
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });
    });
</script>