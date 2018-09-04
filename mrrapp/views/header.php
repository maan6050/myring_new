<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?= $title; ?> | My Ring Ring</title>

		<? /* Hojas de estilo complementarias */ ?>
		<link rel="stylesheet" type="text/css" href="<?= base_url('js/bxslider/jquery.bxslider.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('js/intl-tel-input/css/intlTelInput.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('js/sweetalert/sweetalert.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('js/fancybox/jquery.fancybox.css'); ?>" media="screen" />

		<? /* Favicon */ ?>
		<link rel="icon" type="image/png" href="<?= base_url('images/logo-myringring.png'); ?>">
		<? /* Bootstrap Css*/ ?>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
		<? /* Font Awesome */ ?>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<? /* Custom Bootstrap Css */ ?>
		<link rel="stylesheet" type="text/css" href="<?= base_url('css/customStyle.css'); ?>">

		<? /* Data Tables Files*/ ?>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.css"/>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css" />

		<? /* Time Picker Css*/ ?>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
		<? /* Date Picker Css*/ ?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<? /* Etiquetas responsive */ ?>
		<meta name="viewport" content="width=device-width">
		<meta name="format-detection" content="telephone=no">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<? /* Bootstrap Js*/ ?>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="<?= base_url('js/jquery.validate.min.js'); ?>"></script>
		<script src="<?= base_url('js/bxslider/jquery.bxslider.min.js'); ?>"></script>
		<script src="<?= base_url('js/intl-tel-input/js/intlTelInput.js'); ?>"></script>
		<script src="<?= base_url('js/intl-tel-input/js/utils.js'); ?>"></script>
		<script src="<?= base_url('js/sweetalert/sweetalert.min.js'); ?>"></script>
		<script src="<?= base_url('js/fancybox/jquery.fancybox.pack.js'); ?>"></script>
		<script src="<?= base_url('js/modules/utilities.js'); ?>"></script>
		<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
		

		<? /* Data Tables */ ?>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> 
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>

		<? /* Time Picker js*/ ?>
		<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
		<? /* Date Picker js*/ ?>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
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
			</div>
			<div class="headerTopRight">
				<div class="headerTopPhone"><?= lang('customer_service'); ?> <a href="tel:18888137485">1 888 8137485</a></div>
				<div class="headerTopAccounts text-right">
					[ <strong><?= $_SESSION['userName']; ?></strong> ]&nbsp; &nbsp;
					<a href="<?= base_url('home/account'); ?>"><?= lang('my_account'); ?></a>&nbsp; |&nbsp;
					<a href="<?= base_url('home/logout'); ?>"><?= lang('logout'); ?></a>
				<br><br><?
			//	if($_SESSION['userType'] == STORE)
				//{
					$balanceStore = getCurrentUserBalance(); ?>
					<?= lang('balance'); ?>  <strong>$<?= number_format($balanceStore->balance, 2, '.', ','); ?></strong><!--&nbsp; |&nbsp;-->
					<?php /*?><?= lang('available'); ?>  <strong>$<?= number_format($balanceStore->available, 2, '.', ','); ?></strong>&nbsp;&nbsp;&nbsp;<?php */?>
					<!--<a href="" onClick="changeLanguage('en')"><img src="<?= base_url('images/EN.png'); ?>" alt="">&nbsp;<?= lang('english'); ?></a>&nbsp; |&nbsp;
					<a href="" onClick="changeLanguage('es')"><img src="<?= base_url('images/ES.png'); ?>" alt="">&nbsp;<?= lang('spanish'); ?></a>--><?
			//	} ?>
				</div>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav"> <?
					/////////////////////////OWNER LEVEL//////////////////////////
					if($_SESSION['userType'] == "OWNER")
					{ ?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="<?= base_url('home'); ?>"><?= lang('home'); ?> <!--<span class="caret"></span>--></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Customer/viewList'); ?>"><?= lang('masters'); ?></a></li>
								<li><a href="<?= base_url('User/viewList'); ?>"><?= lang('users'); ?></a></li>
								<li><a href="<?= base_url('Profile/customerEdit'); ?>"><?= lang('profile'); ?></a></li>
								<li><a href="<?= base_url('ViewLevel/viewList'); ?>"><?= lang('view_levels'); ?></a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_providers'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Provider/providerList'); ?>"><?= lang('view_providers'); ?></a></li>
								<li><a href="<?= base_url('Product/productList'); ?>"><?= lang('view_products'); ?></a></li>
								<li><a href="<?= base_url('Product/productPlans'); ?>"><?= lang('product_plans'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_records'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Record/logReport'); ?>"><?= lang('transaction_logs'); ?></a></li>
								<li><a href="<?= base_url('Record/paymentReport'); ?>"><?= lang('transaction_history'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_reports'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Report/viewReports'); ?>"><?= lang('sales_by_product_type'); ?></a></li>
								<li><a href="<?= base_url('Report/viewReports?report=2'); ?>"><?= lang('sales_by_product'); ?></a></li>
								<li><a href="<?= base_url('Report/viewReports?report=3'); ?>"><?= lang('sales_by_masters'); ?></a></li>
								<li><a href="<?= base_url('Report/viewReports?report=4'); ?>"><?= lang('sales_by_provider'); ?></a></li>
								<li><a href="<?= base_url('Report/prodsoldreport'); ?>"><?= lang('sales_by_end_users'); ?></a></li>
								<li><a href="<?= base_url('Report/unsuccessTransReport?report=3'); ?>"><?= lang('unsuccess_transactions'); ?></a></li>
								<li><a href="<?= base_url('Report/customerGroupReport?report=3'); ?>"><?= lang('customer_report'); ?></a></li>
								<? /*<li><a href="<?= base_url('Report/paymentReport'); ?>"><?= lang('payment_report'); ?></a></li> */ ?>
							</ul>
						</li> 
						<? /*<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_web_content'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('WebContent/storeAdvertisment'); ?>"><?= lang('store_daily_promos'); ?></a></li>
							</ul>
						</li>*/?> <?
					} 
					/////////////////////////MASTER LEVEL//////////////////////////
					if($_SESSION['userType'] == "MASTER")
					{ ?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="<?= base_url('home'); ?>"><?= lang('home'); ?> <!--<span class="caret"></span>--></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Profile/customerEdit'); ?>"><?= lang('profile'); ?></a></li>
								<li><a href="<?= base_url('Customer/viewList'); ?>"><?= lang('distributers'); ?></a></li>
								<li><a href="<?= base_url('User/viewList'); ?>"><?= lang('users'); ?></a></li>
								<li><a href="<?= base_url('Product/sellingProduct'); ?>"><?= lang('products'); ?></a></li>
								<li><a href="<?= base_url('Product/productPlans'); ?>"><?= lang('product_plans'); ?></a></li>
								<li><a href="<?= base_url('ViewLevel/viewList'); ?>"><?= lang('view_levels'); ?></a></li>
							</ul>
						</li>
						<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_bank'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Bank/accountSummary'); ?>"><?= lang('summary'); ?></a></li>
								<li><a href="<?= base_url('Record/paymentReport'); ?>"><?= lang('historic'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_records'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Record/logReport'); ?>"><?= lang('transaction_logs'); ?></a></li>
								<li><a href="<?= base_url('Record/paymentReport'); ?>"><?= lang('transaction_history'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_reports'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Report/viewReports'); ?>"><?= lang('sales_by_product_type'); ?></a></li>
								<li><a href="<?= base_url('Report/viewReports?report=2'); ?>"><?= lang('sales_by_product'); ?></a></li>
								<li><a href="<?= base_url('Report/viewReports?report=3'); ?>"><?= lang('sales_by_distributers'); ?></a></li>
								<li><a href="<?= base_url('Report/customerGroupReport'); ?>"><?= lang('customer_report'); ?></a></li>
							</ul>
						</li> <?php
					} 
					/////////////////////////DISTRIBUTOR LEVEL//////////////////////////
					if($_SESSION['userType'] == "DISTRIBUTOR")
					{ ?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="<?= base_url('home'); ?>"><?= lang('home'); ?> <!--<span class="caret"></span>--></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Profile/customerEdit'); ?>"><?= lang('profile'); ?></a></li>
								<li><a href="<?= base_url('Customer/viewList'); ?>"><?= lang('sub distributors'); ?></a></li>
								<li><a href="<?= base_url('User/viewList'); ?>"><?= lang('users'); ?></a></li>
								<li><a href="<?= base_url('Product/sellingProduct'); ?>"><?= lang('products'); ?></a></li>
								<li><a href="<?= base_url('Product/productPlans'); ?>"><?= lang('product_plans'); ?></a></li>
								<li><a href="<?= base_url('ViewLevel/viewList'); ?>"><?= lang('view_levels'); ?></a></li>
								<li><a href="<?= base_url('LandingPage/landingPage'); ?>"><?= lang('landing_page'); ?></a></li>
								<li><a href="<?= base_url('Ticket/tickets'); ?>"><?= lang('tickets'); ?></a></li>
							</ul>
						</li>
						<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_bank'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Bank/accountSummary'); ?>"><?= lang('summary'); ?></a></li>
								<li><a href="<?= base_url('Record/paymentReport'); ?>"><?= lang('historic'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_records'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Record/logReport'); ?>"><?= lang('transaction_logs'); ?></a></li>
								<li><a href="<?= base_url('Record/paymentReport'); ?>"><?= lang('transaction_history'); ?></a></li>
								<li><a href="<?= base_url('Record/balanceReport'); ?>"><?= lang('balance_report'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_reports'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Report/viewReports'); ?>"><?= lang('sales_by_product_type'); ?></a></li>
								<li><a href="<?= base_url('Report/viewReports?report=2'); ?>"><?= lang('sales_by_product'); ?></a></li>
								<li><a href="<?= base_url('Report/viewReports?report=3'); ?>"><?= lang('sales_by_distributers'); ?></a></li>
								<li><a href="<?= base_url('Report/customerGroupReport'); ?>"><?= lang('customer_report'); ?></a></li>
							</ul>
						</li> <?php
					} /////////////////////////DISTRIBUTOR LEVEL//////////////////////////
					if($_SESSION['userType'] == "SUBDISTRIBUTOR")
					{ ?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="<?= base_url('home'); ?>"><?= lang('home'); ?> <!--<span class="caret"></span>--></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Profile/customerEdit'); ?>"><?= lang('profile'); ?></a></li>
								<li><a href="<?= base_url('Customer/viewList'); ?>"><?= lang('stores'); ?></a></li>
								<li><a href="<?= base_url('User/viewList'); ?>"><?= lang('users'); ?></a></li>
								<li><a href="<?= base_url('Product/sellingProduct'); ?>"><?= lang('products'); ?></a></li>
								<li><a href="<?= base_url('Product/productPlans'); ?>"><?= lang('product_plans'); ?></a></li>
								<li><a href="<?= base_url('ViewLevel/viewList'); ?>"><?= lang('view_levels'); ?></a></li>
								<li><a href="<?= base_url('LandingPage/landingPage'); ?>"><?= lang('landing_page'); ?></a></li>
								<li><a href="<?= base_url('Ticket/tickets'); ?>"><?= lang('tickets'); ?></a></li>
							</ul>
						</li>
						<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_bank'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Bank/accountSummary'); ?>"><?= lang('summary'); ?></a></li>
								<li><a href="<?= base_url('Record/paymentReport'); ?>"><?= lang('historic'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_records'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Record/logReport'); ?>"><?= lang('transaction_logs'); ?></a></li>
								<li><a href="<?= base_url('Record/paymentReport'); ?>"><?= lang('transaction_history'); ?></a></li>
								<li><a href="<?= base_url('Record/balanceReport'); ?>"><?= lang('balance_report'); ?></a></li>
							</ul>
						</li> 
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= lang('menu_reports'); ?></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url('Report/viewReports'); ?>"><?= lang('sales_by_product_type'); ?></a></li>
								<li><a href="<?= base_url('Report/viewReports?report=2'); ?>"><?= lang('sales_by_product'); ?></a></li>
								<li><a href="<?= base_url('Report/viewReports?report=3'); ?>"><?= lang('sales_by_stores'); ?></a></li>
								<li><a href="<?= base_url('Report/customerGroupReport'); ?>"><?= lang('customer_report'); ?></a></li>
							</ul>
						</li> <?php
					}if($_SESSION['userType'] == "STORE")
						{ ?>
							<li><a href="<?= base_url('pinlessAdminCtrl'); ?>"><?= lang('link_pinless'); ?></a></li>
							<li>
								<a href="#"><?= lang('customer_support'); ?></a>
								<ul class="submenu">
									<li><a href="<?= base_url('clientCtrl/transactionsList'); ?>"><?= lang('recent_transactions'); ?></a></li>
									<li><a href="<?= base_url('clientCtrl/pinlessAccess'); ?>"><?= lang('pinless_access'); ?></a></li>
									<li><a href="<?= base_url('clientCtrl/ratesList'); ?>"><?= lang('rates_list'); ?></a></li>
									<li><a href="<?= base_url('clientCtrl/feeList'); ?>"><?= lang('fee_list'); ?></a></li>
								</ul>
							</li>
							<li>
								<a href="#"><?= lang('reports'); ?></a>
								<ul class="submenu">
									<li><a href="<?= base_url('reportsCtrl/invoices'); ?>"><?= lang('reports_invoices'); ?></a></li>
									<li><a href="<?= base_url('report/recentDeposit'); ?>"><?= lang('my_payments'); ?></a></li>
									<li><a href="<?= base_url('report/salesByProduct'); ?>"><?= lang('sales_by_product'); ?></a></li>
									<li><a href="<?= base_url('report/guestsNumbers'); ?>"><?= lang('guest_numbers'); ?></a></li>
									<!--li><a href="<?= base_url('report/pdf'); ?>">Pdf</a></li-->
								</ul>
							</li><?
						}?>
				</ul>
			</div>
		</div>
	</nav>
	