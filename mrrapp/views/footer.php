<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
		if($_SESSION['userType'] == STORE || $_SESSION['userType'] == CUSTOMER)
		{ ?>
			<div class="clear10"></div>
			<div class="footer">
				<p><?= lang('customer_service'); ?> <strong>1 888 8137485</strong></p>
				<p><small>All rights reserved &copy;</small></p>
			</div><?
		} ?>
		<div class="footer-container"></div>
	</body>
	<?php
		if(isset($msg)) { 
			?><script type="text/javascript"> 
				document.getElementById("alert_message").innerHTML = "<?php echo $msg; ?>";
				document.getElementById("alert_message").style.boxShadow = "0px 0px 2px 0px green";
				document.getElementById("alert_message").style.background = "#7bc691";
				document.getElementById("alert_message").style.opacity = "1";
				document.getElementById("alert_message_container").style.top = "0px";
				document.getElementById("alert_message").style.marginTop = "170px";
				setTimeout(function(){ document.getElementById("alert_message_container").style.display = "none"; }, 3000);
				setTimeout(function(){ document.getElementById("alert_message").style.opacity = "0"; }, 3000);
			</script><?
		}
		if(isset($error)) { 
			?><script type="text/javascript">
				document.getElementById("alert_message").innerHTML = "<?php echo $error; ?>";
				document.getElementById("alert_message").style.boxShadow = "0px 0px 2px 0px red";
				document.getElementById("alert_message").style.background = "#e89292";
				document.getElementById("alert_message").style.opacity = "1";
				document.getElementById("alert_message_container").style.top = "0px";
				document.getElementById("alert_message").style.marginTop = "170px";
				setTimeout(function(){ document.getElementById("alert_message_container").style.display = "none"; }, 3000);
				setTimeout(function(){ document.getElementById("alert_message").style.opacity = "0"; }, 3000);
			</script><?
		} 
	?>
	<? /* Custom Java Script */ ?>
	<script src="<?= base_url('js/customJavaScript.js'); ?>"></script>
</html>