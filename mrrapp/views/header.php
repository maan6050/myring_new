<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?= $title; ?> | My Ring Ring</title>

		<? /* Fuentes */ ?>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

		<? /* Hojas de estilo complementarias */ ?>
		<link rel="stylesheet" type="text/css" href="<?= base_url('js/bxslider/jquery.bxslider.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('js/intl-tel-input/css/intlTelInput.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('js/sweetalert/sweetalert.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('js/fancybox/jquery.fancybox.css'); ?>" media="screen" />

		<? /* Favicon */ ?>
		<link rel="icon" type="image/png" href="<?= base_url('images/logo-myringring.png'); ?>">
		<? /* Bootstrap Css*/ ?>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<? /* My Custom Css */ ?>
		<link rel="stylesheet" type="text/css" href="<?= base_url('css/myCustomStyle.css'); ?>">
		<? /* Etiquetas responsive */ ?>
		<meta name="viewport" content="width=device-width">
		<meta name="format-detection" content="telephone=no">
		<? /* Bootstrap Js*/ ?>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="<?= base_url('js/jquery.validate.min.js'); ?>"></script>
		<script src="<?= base_url('js/bxslider/jquery.bxslider.min.js'); ?>"></script>
		<script src="<?= base_url('js/intl-tel-input/js/intlTelInput.js'); ?>"></script>
		<script src="<?= base_url('js/intl-tel-input/js/utils.js'); ?>"></script>
		<script src="<?= base_url('js/sweetalert/sweetalert.min.js'); ?>"></script>
		<script src="<?= base_url('js/fancybox/jquery.fancybox.pack.js'); ?>"></script>
		<script src="<?= base_url('js/modules/utilities.js'); ?>"></script>
		<? /* My Custom Java Script */ ?>
		<script src="<?= base_url('js/myCustomJavaScript.js'); ?>"></script>

		<script type="text/javascript"><!--
			document.createElement("nav");
			document.createElement("header");
			document.createElement("footer");
			document.createElement("section");
			document.createElement("article");
			document.createElement("aside");
			document.createElement("hgroup");

			function changeLanguage(lang)
			{
				var url = '<?= base_url('headerCtrl/changeLanguage/'); ?>';
				$.ajax({
					url: url,
					type: 'POST',
					data: {lang: lang},
				})
				.done(function() {
					location.reload();
				})
				.fail(function(){
					console.log('error');
				})
				.always(function(){
					console.log('complete');
				});
			}

			function viewInvoice(){
				var url = '<?= base_url('report/viewInvoice/'); ?>';
				$.ajax({
					url: url,
					type: 'POST',
					data: {lang: lang},
				})
				.done(function() {
				})
				.fail(function(){
					console.log('error');
				})
				.always(function(){
					console.log('complete');
				});
			}
		--></script>
		<? /* Hoja de estilo principal */ ?>
		<link type="text/css" href="<?= base_url('css/style.css'); ?>" rel="stylesheet">
	</head>
	<body>
	<nav class="navbar">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>                        
				</button>
				<a class="navbar-brand" href="<?= base_url(); ?>"><img src="<?= base_url('images/logo-myringring.png'); ?>" alt=""></a>
				<p class="headerTopPhone"><?= lang('customer_service'); ?> <a href="tel:18888137485">1 888 8137485</a></p>
			</div>
			<div class="headerTopRight">
				[ <strong><?= $_SESSION['userName']; ?></strong> ]&nbsp; &nbsp;
				<a href="<?= base_url('home/account'); ?>"><?= lang('my_account'); ?></a>&nbsp; |&nbsp;
				<a href="<?= base_url('home/logout'); ?>"><?= lang('logout'); ?></a><?
				if($_SESSION['userType'] == STORE)
				{
					$balanceStore = getBalanceStore(); ?>
					<?= lang('balance'); ?>  <strong>$<?= number_format($balanceStore->balance, 2, '.', ','); ?></strong>&nbsp; |&nbsp;
					<?= lang('available'); ?>  <strong>$<?= number_format($balanceStore->available, 2, '.', ','); ?></strong>&nbsp;&nbsp;&nbsp;
					<a href="" onClick="changeLanguage('en')"><img src="<?= base_url('images/EN.png'); ?>" alt="">&nbsp;<?= lang('english'); ?></a>&nbsp; |&nbsp;
					<a href="" onClick="changeLanguage('es')"><img src="<?= base_url('images/ES.png'); ?>" alt="">&nbsp;<?= lang('spanish'); ?></a><?
				} ?>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav"> <?
					if($_SESSION['userType'] == "OWNER")
					{ ?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="<?= base_url('home'); ?>"><?= lang('home'); ?> <!--<span class="caret"></span>--></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('CustomerCtrl/masters'); ?>"><?= lang('masters'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/users'); ?>"><?= lang('users'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/profile'); ?>"><?= lang('profile'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/viewLevels'); ?>"><?= lang('view_levels'); ?></a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_providers'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('CustomerCtrl/viewProviders'); ?>"><?= lang('view_providers'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/viewProducts'); ?>"><?= lang('view_products'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/productPlans'); ?>"><?= lang('product_plans'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_records'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('CustomerCtrl/transactionLogs'); ?>"><?= lang('transaction_logs'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/transactionHistory'); ?>"><?= lang('transaction_history'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_reports'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('CustomerCtrl/salesByProductType'); ?>"><?= lang('sales_by_product_type'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/salesByProduct'); ?>"><?= lang('sales_by_product'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/salesByMasters'); ?>"><?= lang('sales_by_masters'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/salesByProvider'); ?>"><?= lang('sales_by_provider'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/salesByEndUsers'); ?>"><?= lang('sales_by_end_users'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/unsuccessTransactions'); ?>"><?= lang('unsuccess_transactions'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/customerReport'); ?>"><?= lang('customer_report'); ?></a></li>
								<li><a href="<?= base_url('CustomerCtrl/paymentReport'); ?>"><?= lang('payment_report'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_web_content'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('CustomerCtrl/storeDailyPromos'); ?>"><?= lang('store_daily_promos'); ?></a></li>
							</ul>
						</li><?
					} ?>
				</ul>
			</div>
		</div>
	</nav>	
<? echo exit; ?>
	