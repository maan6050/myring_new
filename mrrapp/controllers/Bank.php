<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class Bank extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();

		if (!isset($_SESSION['userId']) || !isset($_SESSION['userType']))
		{
			redirect(base_url('login'));
		}
		$this->lang->load('invoices_lang', $this->getLanguage());
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->helper('cookie');
		$this->load->model('customers');
		$this->load->model('products');
		$this->load->model('banks');
		$this->load->model('users');
		$this->load->library('form_validation');
	}

	//////////////////////CUSTOMERS VIEW LIST////////////////////////
    public function accountSummary()
    {
		//------Credit Cards Detail Form Default Parameters--->	
		$data["cc_number"] = "";
		$data["cc_month"] = "";
		$data["cc_year"] = "";
		$data["cc_ccv"] = "";
		$data["nickname"] = "";
		$data["f_name"] = "";
		$data["c_address1"] = "";
		$data["v_city"] = "";
		$data["v_state"] = "";
		$data["v_zip"] = "";
		$data["cty"] = "";
		//------./Credit Cards Detail Form Default Parameters--->

		$data["cc_amount"] = "";
		$data["selectAccount"] = "";
		$data["getBalance"] = $this->banks->getbalanceinfo();
		
		//------Delete Credit Details--->
		if(isset($_GET["action"]) AND ($_GET["action"] == "delete") AND isset($_GET["ccmod_id"]) AND ($_GET["ccmod_id"] !== "")) {
			$creditCardInfo = $this->banks->deleteCreditInfo($_GET["ccmod_id"]);
			if($creditCardInfo == true){
				$data["msg"] = "Account has been deleted successfully.";
				
			} else {
				$data["error"] = "There was some problem deleting the account.Please try again.";
			}
		}//------./Delete Credit Details--->

		if(isset($_COOKIE["user_type"])  AND ($_COOKIE["user_type"] == "415285967837575867") AND ($_COOKIE["CURRENT_CUR"] !== "USA")) {
			redirect(base_url('onlineStore'));
		}
		if($_COOKIE["user_type"] !== "258968745812378564") { ?>
			<script src="https://www.paypalobjects.com/js/external/dg.js" type="text/javascript"></script> <?php

			if(!isset($_COOKIE["user_type"])  OR ($_COOKIE["user_type"] == "") AND ($_COOKIE["user_type"] == "956314127503977533") OR ($_COOKIE["user_type"] == "")) {
				redirect(base_url('home'));
			}

			//<cfinclude template="ResponsiveCss.cfm">
			/*if(isset($_POST["submit_pin"])){
				$form = $this->input->post(NULL, TRUE);
				$checksec_pin = $this->users->checkCurrentPINValid($_POST["security_pin"]);
				if($checksec_pin == true){
					$submitAgree = $this->banks->submitTNC($form);
					if($submitAgree == true){ ?>
						<p style="color:green"><strong>Success:</strong> Congratulations, you can use now the payments module.</p> <?php
					} else { ?>
						<p style="color:red;">There are some errors please try again.</p> <?php
					}
				} else { ?>
					<p style="color:red;">Wrong security pin.</p> <?php
				}
			}*/
			$checkIfAgree = $this->banks->checkTNC();
			if($checkIfAgree == false){
				if(isset($_POST["submit_agreement"])){
					if(isset($_POST["Ifagree"]) AND $_POST["Ifagree"]=="1"){ ?>
						<form action="" method="post">
							<br />
							<br />
							<table align="center">
								<tr>
									<td>
										Security pin:
									</td>
									<td>
										<input type="password" name="security_pin" required="yes" />
									</td>
									<td>
										<input type="submit" name="submit_pin" value="Submit" />
									</td>
								</tr>
							</table>
						</form> <?php
					} else { ?>
						<p style="color:red;">Please accept terms and conditions to continue.</p> <?php
					}
				} else {
					$data['title'] = "Terms & Conditions";
					$this->load->view('header', $data);
					$this->load->view('bank/termsnconditions', $data);
					$this->load->view('footer');
				}    
			} else {
				$data['getcreditinfo'] = $this->banks->getCreditInfo();
				$data['getbankinfo'] = $this->banks->getBankInfo();
				//$data['getRecurringinfo'] = $this->banks->getRecurringInfo();
				$data['title'] = "Account Summary";
				$this->load->view('header', $data);
				$this->load->view('bank/inc_account_summary2', $data);
				$this->load->view('footer');
			} 
		} else { ?>
			<script src="https://www.paypalobjects.com/js/external/dg.js" type="text/javascript"></script> 

			<div class="container">
				<div class="row"> <?php
					if(!isset($_COOKIE["user_type"])  OR (isset($_COOKIE["user_type"])) AND ($_COOKIE["user_type"] == "956314127503977533")) { 
						redirect(base_url('home'));
					}
					//<cfinclude template="ResponsiveCss.cfm">
					if(isset($_POST["submit_agreement"])){
						if(isset($_POST["Ifagree"]) AND $_POST["Ifagree"]=="1"){ 
							$submitAgree = $this->banks->submitTNC($form);
							if($submitAgree == true){ ?>
								<div class="alert alert-success"><strong>Success:</strong> Congratulations, you can use now the payments module.</div> <?php
							} else { ?>
								<div class="alert alert-danger">There are some errors please try again.</div> <?php
							}
						} else { ?>
							<div class="alert alert-danger">Please accept terms and conditions to continue.</div> <?php
						}
					}   
					$checkIfAgree = $this->banks->checkTNC();
					if($checkIfAgree == false){ ?>
						<h1>Terms and Conditions</h1>
						<div style="height:400px; overflow:scroll">
							<!--<cfinclude template="inc_account_summar1.cfm">-->
						</div>
						<br />
						<form action="" method="post">
							<input type="checkbox" name="Ifagree" required="yes" value="1"> I agree
							<br /><br />
							<input type="submit" class="btn btn-primary" name="submit_agreement" value="submit">
						</form> <?php
					} else {
						
						//<cfinclude template="inc_account_summary2EndUser.cfm">
					
					} ?>
				</div>
			</div> <?php   
		}
	}

	/////////////////CREDIT CARD and BILLING INFORMATION ADD///////////
    public function creditCardFormAdd()
    {
		if(isset($_POST["addBankDetails"])){
			$this->form_validation->set_rules('cc_number', 'Credit Card Number:', 'required|min_length[15]',
				array('required' => 'Please fill your correct Credit Card number.',
					'min_length' => 'Should be Numeric and have 15 or 16 digits.'
				)
			);
			$this->form_validation->set_rules('cc_ccv', 'Verification Code:', 'required|min_length[3]',
				array('required' => 'Please fill in your Credit card verification code.',
					'min_length' => 'Should be Numeric and have 3 digits.'
				)
			);
			$this->form_validation->set_rules('nickname', 'Card Name:', 'required',
				array('required' => 'Please fill in Card Name field.')
			);
			$this->form_validation->set_rules('f_name', 'Cardholder Name:', 'required',
				array('required' => 'Please fill in your first name.')
			);
			$this->form_validation->set_rules('c_address1', 'Address:', 'required',
				array('required' => 'Please fill in your address.')
			);
			$this->form_validation->set_rules('v_city', 'City:', 'required',
				array('required' => 'Please fill in your city.')
			);
			$this->form_validation->set_rules('v_state', 'State:', 'required',
				array('required' => 'Please fill in your state.')
			);
			$this->form_validation->set_rules('v_zip', 'Zip Code:', 'required',
				array('required' => 'Please fill in your zip postal code.')
			);
			$this->form_validation->set_rules('cty', 'Country:', 'required',
				array('required' => 'Please fill in your country.')
			);
			$form = $this->input->post(NULL, TRUE);
			if($this->form_validation->run() !== FALSE) {
				$addcreditinfo = $this->banks->addcreditinfo($form);
				if($addcreditinfo == TRUE){ ?>
					<script type="text/javascript">
						$(document).ready(function(){
							$("#credit_card_info_form").trigger('reset');
						});
					</script> <?php
					//------Credit Cards Detail Form Default Parameters--->	
					$data["cc_number"] = "";
					$data["cc_month"] = "";
					$data["cc_year"] = "";
					$data["cc_ccv"] = "";
					$data["nickname"] = "";
					$data["f_name"] = "";
					$data["c_address1"] = "";
					$data["v_city"] = "";
					$data["v_state"] = "";
					$data["v_zip"] = "";
					$data["cty"] = "";
					//------./Credit Cards Detail Form Default Parameters--->

					$data["cc_amount"] = "";
					$data["selectAccount"] = "";
					$data["getBalance"] = $this->banks->getbalanceinfo();
					$data["msg"] = "Credit account has been added successfully.";
					$data['getcreditinfo'] = $this->banks->getCreditInfo();
					$data['getbankinfo'] = $this->banks->getBankInfo();
					//$data['getRecurringinfo'] = $this->banks->getRecurringInfo();
					$data['title'] = "Account Summary";
					$this->load->view('header', $data);
					$this->load->view('bank/inc_account_summary2', $data);
					$this->load->view('footer');
				} else {

				}
			} else{
				$this->accountSummary();
			}
		}
	}
	/////////////////MAKE PAYMENT ADD///////////
    public function addPayment()
    {
		if(isset($_POST["addPayment"])){
			$form = $this->input->post(NULL, TRUE);
		}
	}
}