<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	//  echo"<pre>";
    //  print_r($getprod_types);
    //  die();
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<div class="row">
		<div class="col-sm-12 marginBottom10px">
            <select class="form-control pull-right" name="PRODUCT_TYPE" id="PRODUCT_TYPE" title="Filter by Product Type" style="width:208px;" onchange="filterBy($(this).val());">
                <option value="">Select Product Types</option>
                <option value="0" <?php if(isset($search_by_type) == "0") { echo "selected"; } ?>>ALL</option> <?php
                foreach($getprod_types as $getprod_type) { ?>
                    <option value="<?php echo $getprod_type->PROD_TYPE_ID;?>" <?php if(isset($search_by_type)) { if($getprod_type->PROD_TYPE_ID==$search_by_type){ echo "selected"; } } ?>><?php echo $getprod_type->PROD_TYPE_NAME; ?></option> <?php
                } ?>
            </select>
		</div>
	</div>	
	<div class="table-container">
		<table id="selling-product-table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Product Name</td>
					<td>Product Type</td>
					<td>Country</td>
					<td>My Commision</td>
					<td>Status</td> <?php
                    if($_COOKIE["user_type"] == "415285967837575867"){ ?>
                        <td>Favorites</td>
                        <td>Extra Charges %</td> <?php
                    } ?>
				</tr>
			</thead>
			<tbody> <?php
				foreach($getProducts as $getProduct) {  ?>
					<tr id="<?php echo 'tr_'.$getProduct->PROD_ID; ?>">
						<td><?php echo $getProduct->PROD_NAME; ?></td>
						<td><?php echo $getProduct->PROD_TYPE_NAME; ?></td>
                        <td><?php echo $getProduct->CTY_NAME; ?></td>
                        <td><?php echo $getProduct->PROD_COMM." %"; ?></td>
                        <td><?php
							/*if($getProduct->COMM_STATUS == "1"){ ?>
								<input type="button" class="button" name="active" value="ON" onclick="change_status('<?php echo $getProduct->PROD_ID; ?>','0')" /> <?php
							} else { ?>
								<input type="button" class="button redbackground" name="active" value="OFF" onclick="change_status('<?php echo $getProduct->PROD_ID; ?>','1')" /> <?php
                            } */
                            if($getProduct->COMM_STATUS == "1"){ ?>
                                <input type="checkbox" name="active" checked onchange="change_status('<?php echo $getProduct->PROD_ID; ?>','0')" data-toggle="toggle"> <?php
                            } else { ?>    
                                <input type="checkbox" name ="active" onchange="change_status('<?php echo $getProduct->PROD_ID; ?>','1')" data-toggle="toggle"> <?php
                            } ?>
						</td> <?php
                        if($_COOKIE["user_type"] == "415285967837575867"){ ?>
							<td> <?php 
                                /*if($getProduct->store_fav == "1") {?>
                                    <input type="button" class="button" name="favoriate" value="ON" onclick="change_fav_status('<?php echo $getProduct->PROD_ID; ?>','0')" /> <?php
                                } else { ?>
                                    <input type="button" class="button redbackground" name="favoriate" value="OFF" onclick="change_fav_status('<?php echo $getProduct->PROD_ID; ?>','1')" /> <?php
                                }*/
                                if($getProduct->store_fav == "1") {?>
                                    <input type="checkbox" name="favoriate" checked onchange="change_fav_status('<?php echo $getProduct->PROD_ID; ?>','0')" data-toggle="toggle"> <?php
                                } else { ?>    
                                    <input type="checkbox" name ="favoriate" onchange="change_fav_status('<?php echo $getProduct->PROD_ID; ?>','1')" data-toggle="toggle"> <?php
                                } ?>
                            </td>
                            <td>
                                <input type="text" name="TAX_CHARGE" id="<?php echo 'TAX_CHARGE_'.$getProduct->PROD_ID; ?>" value="TAX_CHARGE" size="6"  />
                                <input type="button" class="button" name="update_tax" value="UPD" onclick="applytax($('#<?php echo "TAX_CHARGE_".$getProduct->PROD_ID; ?>).val()','<?php echo $getProduct->PROD_ID; ?>');" />
                            </td> <?php
                        }?>
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
		$('#selling-product-table').DataTable({
			"aoColumnDefs" : [ 
				{"aTargets" : [2], "sClass":  "custom-td"},
                {"aTargets" : [3], "sClass":  "custom-td"},
                {"aTargets" : [4], "sClass":  "custom-td"}
			]
		});
    });

    function filterBy($val){
		if($val == "")
		{
			window.location.assign("sellingProduct");
		}else{
			window.location.assign("sellingProduct?filter="+$val);
		}
	}

    // function change_status(id,val){
    //     $.ajax({
    //         url: '/cfc/product.cfc',
    //         data: {method: 'prodStatus',  id: id,vals: val,returnFormat:'plain'},
    //         success: function(data) {
    //         if($.trim(data)){
    //             field= "active";
    //             if(val == 1){
    //                 $("#tr_"+id).find("input[name='"+field+"']").val("ON");
    //                 $("#tr_"+id).find("input[name='"+field+"']").removeClass("redbackground");
    //                 $("#tr_"+id).find("input[name='"+field+"']").removeAttr('onclick');
    //                 $("#tr_"+id).find("input[name='"+field+"']").attr('onclick','javascript:return change_status('+id+',0);');
    //             }
    //             else{
    //                 $("#tr_"+id).find("input[name='"+field+"']").val("OFF");	
    //                 $("#tr_"+id).find("input[name='"+field+"']").addClass("redbackground");	
    //                 $("#tr_"+id).find("input[name='"+field+"']").removeAttr('onclick');
    //                 $("#tr_"+id).find("input[name='"+field+"']").attr('onclick','javascript:return change_status('+id+',1);');				
    //             }
    //             $("#messages").html('<div class="success_msg">Updated successfully!</div>');
    //             setTimeout('$("#messages").html("")',5000);
    //             //alert("Your data saved successfully");
    //         }else{
    //             $("#messages").html('<div class="error_msg">There was some problem.</div>');
    //             setTimeout('$("#messages").html("")',5000);
    //             //alert("There are some problem, please try again.");
    //         }
    //         }
    //     });
	// }
</script>