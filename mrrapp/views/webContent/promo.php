<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>	
<div class="container">
	<div class="row marginBottom10px">
		<div class="col-sm-12"> <?php
			if(isset($admin) AND $admin == "1") { ?>
				<a href="<?= base_url('webContent/addPageContent?page='.$pageName.'&sectionid='.$newSectionId.''); ?>">Add New Promo</a> <?php
			} ?>
		</div>	
	</div> <?php
	foreach($getpagedata as $getpage) { ?>
		<div class="row marginBottom10px text-left">
			<div class="col-sm-12">
				<h4><?php echo $getpage->Description; ?></h4> <?php
				if($admin==1){ ?>
					<a href="<?= base_url('webContent/addPageContent?page="'.$pageName.'"&sectionid="'.$getpage->section_id.'"'); ?>" class="myRingButton marginRight10px"><i class="fa fa-edit fa-2x" aria-hidden="true"></i></a>	

					<a href="<?= base_url('webContent/addPageContent?sectionid="'.$getpage->section_id.'"&delete=1'); ?>" class="myRingButton marginRight10px" onclick="return confirm('Are you sure you want to delete this promo?')"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>

					<span><strong>Rank:<?php echo $sort_order; ?></strong></span> <?php
				} ?>
			</div>		
		</div>
		<div class="row marginBottom10px text-left">
			<div class="col-sm-12"> <?php
				if($getpage->picture_big !== ""){ ?>
					<img src="<?php echo base_url("images/pageContent/").$getpage->picture_big; ?>" width="100" style="float:left; padding-right:15px" /> <?php
				} ?>
				<p><?php echo $getpage->line_2; ?></p>
			</div>
		</div> <?php
	} ?>	
	<div class="row marginBottom10px">
		<div class="col-sm-12"> <?php
			if(isset($admin) AND $admin == "1") { ?>
				<a href="<?= base_url('webContent/addPageContent?page='.$pageName.'&sectionid='.$newSectionId.''); ?>">Add New Promo</a> <?php
			} ?>
		</div>	
	</div>
</div>



