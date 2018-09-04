<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    //echo "<pre>";
	//print_r($getprod_types);
    //die();
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<div class="row">
        <div class="col-sm-6 marginBottom10px">
            <div class="row">
                <div class="col-sm-5">
                    <select class="form-control" name="PRODUCT_TYPE" id="PRODUCT_TYPE" title="Filter by Product Type" style="width:208px;" onchange="javascript:return filterBy($(this).val());">
                        <option value="">Select Product Types</option>
                        <option value="0" <?php if(isset($search_by_type) == "0") { echo "selected"; } ?>>ALL</option> <?php
                        foreach($getprod_types as $getprod_type) { ?>
                            <option value="<?php echo $getprod_type->PROD_TYPE_ID;?>" <?php if(isset($search_by_type)) { if($getprod_type->PROD_TYPE_ID==$search_by_type){ echo "selected"; } } ?>><?php echo $getprod_type->PROD_TYPE_NAME; ?></option> <?php
                        } ?>
                    </select>
                </div> 
            </div>   
		</div>
		<div class="col-sm-6 marginBottom10px"> <?php
			if(isset($_COOKIE["user_type"]) !== "525874964125375325") { ?>
                <a href="<?= base_url('Product/productType'); ?>" class="myRingButton pull-right marginLeft10px">Add Product Type</a>
                <a href="<?= base_url('Product/addProduct'); ?>" class="myRingButton pull-right marginLeft10px">Add New Product</a> <?php
            } ?>
            <a href="<?= base_url('Product/productList'); ?>" class="myRingButton pull-right marginLeft10px">Active Products</a>
		</div>
	</div>
	<div class="table-container">
		<table id="prduct-table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Product Type</td>
					<td>Product Name</td>
					<td>Provider</td>
					<td>Discount</td>
					<td>Small File</td>
                    <td>Front File</td>
                    <td>Back File</td>
                    <td>Poster</td>
                    <td>Active</td>
                    <td>Web</td>
                    <td>Disclaimer</td>
                    <td>Edit</td>
                    <td>Fav</td>
                    <td>Update</td>
				</tr>
			</thead>
			<tbody> <?php
				foreach($productsList as $product) 
				{  ?>
					<tr id="tr_<?php echo $product->PROD_ID; ?>">
                        <form method="post" action="#">
                            <td><?php echo $product->PROD_TYPE_NAME; ?></td>
                            <td onclick="javascript:window.location.assign('Product/cardList?prod_id=<?php $product->PROD_ID; ?>');" title="Go to Cards" style="cursor:pointer">
                                <?php echo $product->PROD_NAME; ?>
                            </td>
                            <td><?php echo $product->NP_SHORT; ?></td>
                            <td>
                                <input type="text" name="discount" class="form-control" value="<?php echo $product->PROD_DISCOUNT; ?>" required size="5" />
                            </td>
                            <td>
                                <i class="fa fa-upload fa-2x" data-toggle="modal" data-target="#uploadImage" onclick="return updateImage('SMALL','<?php echo $product->PROD_ID; ?>','<?php echo $product->PROD_SMALL_PIC; ?>');"></i>
                            </td>
                            <td>
                                <i class="fa fa-upload fa-2x" data-toggle="modal" data-target="#uploadImage" onclick="return updateImage('FRONT','<?php $product->PROD_ID; ?>','<?php $product->PROD_FRONT_PIC; ?>');"></i>
                            </td>
                            <td>
                                <i class="fa fa-upload fa-2x" data-toggle="modal" data-target="#uploadImage" onclick="return updateImage('BACK','<?php $product->PROD_ID; ?>','<?php $product->PROD_BACK_PIC; ?>');"></i>
                            </td>
                            <td>
                                <i class="fa fa-upload fa-2x" data-toggle="modal" data-target="#uploadImage" onclick="return updateImage('POSTER','<?php $product->PROD_ID; ?>','<?php $product->PROD_POSTER_PIC; ?>');"></i>
                            </td>
                            <td> <?php
                                if($product->PROD_STATUS == "1"){ ?>
									<input type="checkbox" name="status" onchange="javascript:return updateProduct('status','0','<?php echo $product->PROD_ID; ?>');" checked data-toggle="toggle"><?php
								} else { ?>
									<input type="checkbox" name="status" onchange="javascript:return updateProduct('status','1','<?php echo $product->PROD_ID; ?>');" data-toggle="toggle"><?php 
								}  ?>
                            </td>
                            <td><?php
                                if($product->PROD_STATUS == "1"){ ?>
									<input type="checkbox" name="status" onchange="javascript:return updateProduct('status','0','<?php echo $product->PROD_ID; ?>');" checked data-toggle="toggle"><?php
								} else { ?>
									<input type="checkbox" name="status" onchange="javascript:return updateProduct('status','1','<?php echo $product->PROD_ID; ?>');" data-toggle="toggle"><?php 
								}  ?>
                            </td>
                            <td> <?php 
								$pproduct_details = $product->PROD_DETAIL; ?>
								<i class="fa fa-info-circle fa-2x" style="cursor:pointer" data-toggle="modal" data-target="#uploadDetailModal" onclick="return uploadDetails('<?php echo $product->PROD_ID; ?>','');"></i>
								<div id="prod_detail_div_<?php echo $product->PROD_ID ?>" style="display:none;">
									<?php echo $pproduct_details; ?>
								</div> 
							</td>
                            <td>
                                <a href="<?php echo base_url('Product/productAdd?id='.$product->PROD_ID) ?>" title=" Edit Provider"><i class="fa fa-edit fa-2x" aria-hidden="true"></i></a>
                            </td>
                            <td>
                                <input type="checkbox" <?php if($product->PROD_FAV == "1") {  echo "checked"; } ?> name="PROD_FAV" class="PROD_FAV" value="1"/>
                            </td>	
                            <td><?php $prod_id = $product->PROD_ID; ?>
                                <input type="hidden" name="id" class="prod_id" value="<?php $product->PROD_ID;?>" />
                                <input type="button" onclick="return update_discount_func('tr_<?php echo $prod_id;?>');" class="btn myRingButton" name="save_discount" value="Save"/>
                            </td>
                         </form>    
					</tr> <?php
				} ?>
			</tbody>
		</table>
	</div>
    <? //////////////////UPLOAD IMAGE MODAL/////////////////?>
	<div class="modal fade" id="uploadImage" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Upload <span class="image_type"></span> Image</h4>
				</div>
				<form name="add_balance_to_master" id="add_balance_to_master" method="post" action="<?= base_url('Product/uploadImages'); ?>" enctype="multipart/form-data" id="upload_image">
					<div class="modal-body">
						<div class="row"> 
							<div class="col-sm-12">
								<div class="col-sm-12">
									<div id="view_image" class="view_image"></div>
									<div class="form-group text-center">
										<input type="file" name="upload_image" required="yes" />
										<input type="hidden" name="Prodid" value="" id="Prodid" />
										<input type="hidden" name="type" value="" id="type" />
										<input type="hidden" name="filter" value="<?php if(isset($_GET["filter"])) { echo $_GET["filter"]; } ?>"/>
									</div>
								</div>
							</div>
						</div>	
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-default myRingButton pull-right marginLeft10px" data-dismiss="modal">Cancel</button>
						<button type="submit" name="upload" class="btn btn-primary myRingButton pull-right" >Upload Image</button>
					</div>
				</form>
			</div>
		</div>
	</div>
    <? //////////////////UPLOAD DETAIL MODAL/////////////////?>
	<div class="modal fade" id="uploadDetailModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Upload Details</h4>
				</div>
				<form name="upload_details" id="upload_details" method="post" action="<?= base_url('Product/saveDetails'); ?>" enctype="multipart/form-data" id="detailsForm">
					<div class="modal-body">
						<div class="row"> 
							<div class="col-sm-12">
								<div class="col-sm-12">
									<div class="form-group text-center">
									<textarea name="details_product" id="details_product" cols="40" rows="5" /></textarea>
									<input type="hidden" name="prod_details_id" value="" id="prod_details_id" />
									<input type="hidden" name="filter" value="<?php if(isset($_GET["filter"])) { echo $_GET["filter"]; } ?>"/>
									</div>
								</div>
							</div>
						</div>	
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-default myRingButton pull-right marginLeft10px" data-dismiss="modal">Cancel</button>
						<button type="submit" name="save_details" class="btn btn-primary myRingButton pull-right" >Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>	
<div class="alert_message_container" id="alert_message_container">
    <div class="alert_message" id="alert_message"></div>
</div>
<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#prduct-table').DataTable({
            "aoColumnDefs" : [ 
                {"aTargets" : [3], "sClass":  "custom-td"},
				{"aTargets" : [4], "sClass":  "custom-td"},
                {"aTargets" : [5], "sClass":  "custom-td"},
				{"aTargets" : [6], "sClass":  "custom-td"},
                {"aTargets" : [7], "sClass":  "custom-td"},
				{"aTargets" : [8], "sClass":  "custom-td"},
                {"aTargets" : [9], "sClass":  "custom-td"},
                {"aTargets" : [10], "sClass":  "custom-td"},
                {"aTargets" : [11], "sClass":  "custom-td"},
                {"aTargets" : [12], "sClass":  "custom-td"},
                {"aTargets" : [13], "sClass":  "custom-td"}
            ]
		});
		tinymce.init({selector:'#details_product'});
    });
    
    function filterBy($val){
		if($val == "")
		{
			window.location.assign("productInactiveList");
		}else{
			window.location.assign("productInactiveList?filter="+$val);
		}
	}

    function updateImage(type,id,name)
	{
		$(".image_type").html(type);
		$("#Prodid").val(id);
		$("#type").val(type);
		if(name !== "") {
			$("#view_image").html("<img src='<?php echo base_url("products/");?>"+name+"' border='0' width='100'>");
		}
	}

	function uploadDetails(id,val)
	{
		tinyMCE.activeEditor.setContent($("#prod_detail_div_"+id).html());
		$("#prod_details_id").val(id);
	}

	function update_discount_func(val){
		var discount = $("#"+val).find("#discount").val();
		var fav_prod = $("#"+val).find("#PROD_FAV").val();
		var id = $("#"+val).find("#id").val();
		if(discount == "")
		{
			$("#"+val+" #messages").html('<div class="error">Please fill.</div>');
			setTimeout('$("#messages").html("")',5000);
			$("#"+val+" #discount").focus();
			return false;
		}
		if(isNaN(discount))
		{
			$("#"+val+" #messages").html('<div class="error">Should be numeric.</div>');
            setTimeout('$("#messages").html("")',5000);
			$("#"+val+" #discount").focus();
			return false;
		}
		window.location.assign("updateDiscount?discount="+discount+"&fav_prod="+fav_prod+"&id="+id);
	}

	function updateProduct(status,val,id)
	{
		window.location.assign("updateSWA?status="+status+"&val="+val+"&id="+id);
	}
</script>