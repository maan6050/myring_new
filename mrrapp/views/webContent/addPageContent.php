<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
    </div>
    <form name="add_product_type_form" id="add_product_type_form" method="post" action="<?= base_url('WebContent/PageContentFormSubmit'); ?>" enctype="multipart/form-data">
        <div class="titulos_forms_blue">Page Setting</div>
        <div class="row marginBottom10px">
            <div class="col-sm-2">
                <label for="language">Language</label>
            </div>
            <div class="col-sm-2">
                <select name="language">
                    <option <?php if($getpagedata->language == "1"){ echo "selected"; } ?> value="1">English</option>
                </select>
            </div>
            <div class="col-sm-2">
                <label for="displayContent">Display</label>
            </div>
            <div class="col-sm-2">
                <select name="displayContent">
                    <option value="1" <?php if($getpagedata->isactive == "1"){ echo "selected"; } ?>>Active</option>
                    <option value="0" <?php if($getpagedata->isactive == "0"){ echo "selected"; } ?> >Inactive</option>
                </select> 
            </div> <?php
            if(isset($_GET["page"]) AND ($_GET["page"] == "storeAdvertisment") AND ($getpagedata > "0")){ ?>
                <div class="col-sm-2">
                    <label for="displayContent">Rank</label>
                </div> 
                <div class="col-sm-2">
                    <select name="sort_order">
                        <option value="0">Select </option> <?php
                        for($i="0";$i<sizeof($storecount);$i++){ ?>
                            <option <?php if($getpagedata->sort_order == $i) { echo "selected";} ?> ><?php echo $i; ?></option> <?php
                        } ?>
                    </select>
                </div> <?php  
            } ?>
        </div><!--./row...-->
        <div class="titulos_forms_blue">Page Setting</div>
        <div class="row marginBottom10px">
            <div class="col-sm-2">
            <label for="title">Title</label>
            </div>
            <div class="col-sm-10"> <?php
                if(isset($_GET["page"]) AND ($_GET["page"] == "about") AND (!isset($_GET["sub"]))){
                    if($getpagetitle > "0"){
                        $titleA = $getpagetitle->Title;
                    } else { 
                        $titleA = "About Us";
                    } ?>
                    <input class="form-control" type="text" size="30" maxlength="256" value="<?php echo $titleA; ?>"  name="pageTitle"> <?php					
                } else {
                    if(isset($_GET["page"]) AND ($_GET["page"] == "storeAdvertisment")){ ?>
                        <textarea name="pageTitle" class="form-control classFormat"><?php echo $getpagetitle->Title; ?></textarea> <?php
                    } else{ ?>
                        <input class="form-control" type="text" size="30" maxlength="256" value="<?php echo $getpagetitle->Title; ?>" name="pageTitle">	<?php
                    }
                } ?>
            </div>
        </div><!--./row...-->  
        <div class="row marginBottom10px">
            <div class="col-sm-2">
            <label for="description">Description</label>
            </div>
            <div class="col-sm-10"> <?php
                $classFormat = "";
                if(isset($_GET["page"]) AND ($_GET["page"] == "storeAdvertisment")){
                    $classFormat = "classFormat";
                } ?>
                <textarea name="description" class="form-control <?php echo $classFormat; ?> "><?php echo $getpagetitle->Description; ?></textarea>
            </div>
        </div><!--./row...--> 
        <div class="row marginBottom10px">
            <div class="col-sm-2">
            <label for="description">Page Content</label>
            </div>
            <div class="col-sm-10"> 
                <textarea name="description2" class="form-control classFormat"><?php echo $getpagetitle->line_2; ?></textarea>
            </div>
        </div><!--./row...--> 
        <div class="row marginBottom10px">
            <div class="col-sm-2">
            <label for="description">Select Image</label>
            </div>
            <div class="col-sm-10"> <?php
            if(isset($_GET["page"]) AND ($_GET["page"] !== 'home') AND ($_GET["page"] !== 'faqs')){ ?>
                <input type="file" name="pageImage"> <?php
                if($getpagedata->picture_small !== "") { ?>
                    <img src="<?php echo base_url("images/pageContent/").$getpagedata->picture_small; ?>"/>
                    <input type="hidden" value="<?php echo $getpagedata->picture_small; ?>" name="pageold_Image"> <?php
                } 
            } else { ?>
                <input type="hidden" value="" name="pageImage"> <?php
            } ?>
            </div>
        </div><!--./row...--> 

        <input type="hidden" name="pageName" value="<?php echo $_GET["page"]; ?>"/>
        <input type="hidden" name="customer_id" value="<?php echo $customerID; ?>">
		<input type="hidden" name="sectionid" value="<?php echo $_GET["sectionid"]; ?>"> <?php
		if(isset($_GET["sub"]) AND $_GET["sub"] !== "") { ?>
			<input type="hidden" name="sub" value="<?php echo $_GET["sub"]; ?>"> <?php
        }
        if(isset($_GET["check"]) AND $_GET["check"] !== "") { ?>
			<input type="hidden" name="checkSub" value="<?php echo $_GET["check"]; ?>"> <?php
        } ?>
        <div class="row marginBottom10px">
            <div class="col-sm-12">
                <input type="submit" name="<?php echo $formtype.'Form';?>" class="btn primary" value="<?php echo $formtype.'Content';?>">
                <input type="reset" name="reset" class="btn primary" value="Clear Form">
            </div>
        </div><!--./row...--> 
    </form>
</div>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script>tinymce.init({
  selector: '.classFormat',
  height: 200,
  theme: 'modern',
  plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern'
  ],
  toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  toolbar2: 'print preview media | forecolor backcolor emoticons',
  image_advtab: true,
  templates: [
    { title: 'Test template 1', content: 'Test 1' },
    { title: 'Test template 2', content: 'Test 2' }
  ],
  content_css: [
    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
    '//www.tinymce.com/css/codepen.min.css'
  ]
 });</script>
