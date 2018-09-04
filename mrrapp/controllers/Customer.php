<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class Customer extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();

		if (!isset($_SESSION['userId']) || !isset($_SESSION['userType']))
		{
			redirect(base_url('login'));
		}
		$this->lang->load('invoices_lang', $this->getLanguage());
		$this->load->library('pagination');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->model('customers');
		$this->load->model('products');
		$this->load->library('form_validation');
	}

	//////////////////////CUSTOMERS VIEW LIST////////////////////////
    public function viewList()
    {
		$data['getHeaders'] = $this->customers->getHeaderInfo();
		$data['accountType'] = $this->customers->getAccountType();
		$data['customers_data'] = $this->customers->getCustomersData();
		$data['customers'] = $this->customers->getCustomersList();
		if(isset($_POST["cust_enc"])){
			$data['msg'] = 'Form Update Successfully';
		}
		if(isset($_POST["add_balance_btn"])){
			$data['msg'] = 'Payment Details inserted Successfully.';
		}
		$data['title'] = lang('customers');
		$this->load->view('header', $data);
		$this->load->view('customer/list', $data);
		$this->load->view('footer');
	}
	
	public function add()
    {	
		$data['accountType'] = $this->customers->getAccountType();
		$data['checkParentmoduleEnable'] =$this->customers->checkParentmoduleEnable();
		$data['getQuestionList'] = $this->customers->getSecurityQuestion();
		if(isset($_COOKIE["CC_ENABLED"]) AND $_COOKIE["CC_ENABLED"]== "0"){
			$data['checkParentmoduleEnable'] = "0";
		}	
		$data["get_all_prod_plands"] = $this->products->getAllprodplans('active');
		if(isset($_GET["cust_enc"]) AND $_GET["cust_enc"]!=""){
			$cust_enc = $_GET["cust_enc"];
			$data['add_edit'] = "1";
			if($data['add_edit'] == "1"){
				if($_COOKIE["user_type"] == "525874964125375325"){
					$data['customers'] = $this->customers->getCustomersList($cust_enc,$service="1");
				} else {
					$data['customers'] = $this->customers->getCustomersList($cust_enc);
				}
			}else {
				$data['customers'] = $this->customers->getCustomersList($cust_enc = "0");
			}
			$data['title'] = "Edit Customer";
			$this->load->view('header', $data);
			$data['getCountryCodes'] = $this->customers->getCountryCodes();
			$this->load->view('customer/addList', $data);
			$this->load->view('footer');
		} else {	
			$data['add_edit'] = "0";
			/*if($data['add_edit'] = "0"; !== 1){
				if(!isset($v_secure) AND !isset($confirm_v_secure)){
					$msg = "<br>-Please fill in your security code.";
				}elseif(($v_secure != $confirm_v_secure) AND ($v_secure != "")){
					$msg = "<br>-Please Confirm your security code.";
				}
			}*/
			$data['title'] = "Add Customer";
			$this->load->view('header', $data);
			$data['getCountryCodes'] = $this->customers->getCountryCodes();
			$this->load->view('customer/addList', $data);
			$this->load->view('footer');
		}
	}

	//////////////////////CUSTOMER FORM SUBMIT////////////////////////
	public function customerformSubmit()
    {
		if(isset($_POST["submit"])){
			$error = FALSE;
			$msg = "";
			$this->form_validation->set_rules('f_name', 'First Name', 'required',
				array('required' => 'Please fill in your first name.')
			);
			$this->form_validation->set_rules('l_name', 'Last Name', 'required',
				array('required' => 'Please fill in your last name.')
			);
			$this->form_validation->set_rules('v_email', 'Email Address', 'required|valid_email',
				array('required' => 'Please fill in your email.',
					'valid_email' => 'Please fill in your valid email address.'
				)
			);
			if(!isset($_POST["cust_enc"]) AND (isset($_POST["cust_enc"]) !== "")){
				$this->form_validation->set_rules('v_email', 'Email Address', 'is_unique[customers.E_MAIL]',
						array('is_unique' => 'Email already exist.')
				);
			}
			$this->form_validation->set_rules('f_company', 'Company Name', 'required',
				array('required' => 'Please fill in your Store name.')
			);
			$this->form_validation->set_rules('tax_id', 'Tax ID', 'required|min_length[9]',
				array('required' => 'Please fill in Tax ID field.',
					'min_length' => 'Should have at least 9 digits.'
				)
			);
			$this->form_validation->set_rules('v_answer', 'Security Answer is:', 'required',
				array('required' => 'Please answer your security Question.')
			);
			$this->form_validation->set_rules('v_question', 'Please select a Security Question.', 'required',
				array('required' => 'Please select your security Question.')
			);
			$this->form_validation->set_rules('v_fax', 'Work Phone No.', 'required|numeric|min_length[5]',
				array('required' => 'Please fill in Work Phone number.',
					'numeric' => 'Should be number',
					'min_length' => 'Should have at least 5 digits.'
				)
			);
			$this->form_validation->set_rules('v_phone', 'Mobile Phone', 'required|numeric|min_length[5]',
				array('required' => 'Please fill in phone Number.',
					'numeric' => 'Should be number',
					'min_length' => 'Should have at least 5 digits.'
				)
			);
			$this->form_validation->set_rules('plan_prod', 'Select Plan', 'required',
				array('required' => 'Please Select Product Plan.')
			);
			$this->form_validation->set_rules('v_country', 'Country', 'required',
				array('required' => 'Please Select your country.')
			);
			$data['getHeaders'] = $this->customers->getHeaderInfo();
			$data['accountType'] = $this->customers->getAccountType();
			$data['checkParentmoduleEnable'] =$this->customers->checkParentmoduleEnable();
			$data['customers'] = $this->customers->getCustomersList();
			$data['customers_data'] = $this->customers->getCustomersData();
			
			$items = $this->input->post(NULL, TRUE);
			if($this->form_validation->run() !== FALSE) {	
				if(isset($_POST["cust_enc"]) AND ($_POST["cust_enc"] !== "")){
					$cust_enc = $this->input->post('cust_enc');
					$msg = FALSE;
					$row = $this->customers->updateCustomers($items,$cust_enc);
					if(!empty($row)){
						$msg = TRUE;
						$this->viewList();
					}	
				} else {
					$row = $this->customers->addCustomers($items);
					if(!empty($row)){
						$msg = TRUE;
						$data['msg'] = 'Form Submit Successfully';
						$data['title'] = lang('add_list');
						$this->load->view('header', $data);
						$this->load->view('customer/addList', $data);
						$this->load->view('footer');	
					}	
				}
			} else {
				$data['title'] = lang('add_list');
				$this->load->view('header', $data);
				$this->load->view('customer/addList', $data);
				$this->load->view('footer');
			}
		}
	}

	//////////////////////ADD BALANCE TO MASTER////////////////////////
	public function addBalance()
    {
		if(isset($_POST["add_balance_btn"])){
			$msg = "";
			$error = False;
			$this->form_validation->set_rules('cre_det', 'Details', 'required',
				array('required' => 'Please fill in Details field.')
			);
			$this->form_validation->set_rules('cre_desc', 'Description', 'required',
				array('required' => 'Please fill in Description field.')
			);
			$this->form_validation->set_rules('cre_amount', 'Amount', 'required|numeric',
				array('required' => 'Please fill in Amount field.',
					'numeric' => 'Should be number'
				)
			);
			if(!isset($_POST["customer_enc"])){
				$this->viewList();
			}

			$data['accountType'] = $this->customers->getAccountType();
			$data['getHeaders'] = $this->customers->getHeaderInfo();
			$data['customers'] = $this->customers->getCustomersList();
			$data['customers_data'] = $this->customers->getCustomersData();
			if($_COOKIE["user_type"] !== "956314127503977533"){
				if($_POST["cre_amount"] > "0"){
					if($data['getHeaders'][0]->TOTALSUM == ""){
						$data['getHeaders'][0]->TOTALSUM = "0";						
					}
					if($data['getHeaders'][0]->CREDIT_LIMIT == ""){
						$data['getHeaders'][0]->CREDIT_LIMIT = "0";
					} 
					if(($data['getHeaders'][0]->TOTALSUM + $_POST["cre_amount"]) > ($data['getHeaders'][0]->CREDIT_LIMIT + $data['getHeaders'][0]->BALANCE)){
						$error = True;
						$data['error'] = "Not authorized. Please increase balance.";
						$data['title'] = lang('masters');
						$this->load->view('header', $data);
						$this->load->view('customer/list', $data);
						$this->load->view('footer');
					}
				}
				/*if not IsDefined("form.security_pin") OR (IsDefined("form.security_pin") and form.security_pin EQ "")>
					<cfset msg = msg & "<br>-Please enter security pin.">
				<cfelse>
					<cfset form.v_secure = form.security_pin>
					<!---Check if security pin is correct--->
					<cfobject name="user" component="cfc.Users">
					<cfset checksec_pin = user.checkCurrentPINValid(form.v_secure)>
					<cfif checksec_pin NEQ true>
						<cfset msg = msg & "<br>-Wrong security PIN.">
					</cfif>    
				</cfif>*/
			}
			if($error == False) {	
				$items = $this->input->post(NULL, TRUE);
				if($this->form_validation->run() !== FALSE){
					$row = $this->customers->addBalance($items);
					if($row==true){
						// $data['msg'] = 'Payment Details inserted Successfully.';
						// $data['title'] = lang('masters');
						// $this->load->view('header', $data);
						// $this->load->view('customer/list', $data);
						// $this->load->view('footer');	
						$this->viewList();
					} else {
						$data['error'] = "There is problem please try again.";
						$data['title'] = lang('masters');
						$this->load->view('header', $data);
						$this->load->view('customer/list', $data);
						$this->load->view('footer');
					}	
				}
			}	
		}	
	}

	//////////////////////UPDATE DISCOUNTS////////////////////////
	public function updateDiscount(){
		$data['acc_type'] = $this->customers->getAccountType();
		if($data['acc_type'] > "4"){
			$this->viewList();
		}
		$data['getcustomer_data'] = $this->customers->getCustomersData();
		
		if(!isset($_GET["acc_enc"]) OR (isset($_GET["acc_enc"]) AND ($_GET["acc_enc"] == ""))){
			$this->viewList(); 
		}
		//$acc_enc = $this->customers->customEncryptFunction($_GET["acc_enc"]);
		$acc_enc = $_GET["acc_enc"];
		if(isset($_GET["template"]) AND ($_GET["template"] == 1)){
			$store_template = $this->customers->store_template($acc_enc);
			if($store_template == true){
				$this->load->view('header', $data);
				$this->load->view('customer/updateDiscount?acc_enc='.$_GET['acc_enc']);
				$this->load->view('footer'); 
			}
		}
		if(isset($_GET["apply"]) AND ($_GET["apply"]=="1")){
			$apply_store_template = $this->customers->apply_store_template($acc_enc, $data['getcustomer_data'][0]->STORE_TEMPLATE);
			$_SESSION['applied'] = 1;
			$this->load->view('header', $data);
			$this->load->view('customer/updateDiscount?acc_enc='.$_GET['acc_enc']);
			$this->load->view('footer'); 
		}
		/*if(StructKeyExists($session, "applied"){
			//StructDelete($session, "applied");
			$success_msg = "Template applied Successfully.";
		}*/
		if(isset($_GET["PROD_ID"]) AND ($_GET["PROD_ID"] !== "") AND (isset($_GET["vals"])) AND ($_GET["vals"] !== "")){
			/*<cfset attributes = structNew()>
			<cfset StructInsert(attributes,"id",url.PROD_ID)>
			<cfset StructInsert(attributes,"vals",URL.vals)>
			<cfset StructInsert(attributes,"key",acc_enc)>*/
			$prod_status = $this->products->prodStatusManualy($_GET["PROD_ID"],$_GET["vals"],$acc_enc);
			if($prod_status == true){
				$dats["msg"] = "Updated Successfully.";
			} else {
				$dats["error"] = "There was some problem while submitting form. Please try again.";
			}
		}
		$data["search_by_type"] = "";
		$search_val = "0";
		if(isset($_GET["filter"])) {
			$data["search_by_type"] = $_GET["filter"]; 
			if($_GET["filter"] == ""){
				$search_val = 0;
			} else if($_GET["filter"] == 0){
				$search_val = "";
			} else {
				$search_val = $_GET["filter"];
			}
		}
		if(isset($_POST["update_disc"])) {
			$items = $this->input->post(NULL, TRUE);
			$commisions = $this->customers->UpdateCommall_prod($items,$acc_enc);
			if($commisions == true){
				$data["msg"] = "Updated Successfully.";
			} else {
				$data["error"] = "There was some problem. Please try again.";
			}
		}
		if(isset($_POST["Manual_on_all"])) {
			$items = $this->input->post(NULL, TRUE);
			$commisions = $this->customers->UpdateMann_all_prod($items,$acc_enc,1);
			if($commisions == true){
				$dats["msg"] = "Updated Successfully.";
			} else {
				$dats["error"] = "There was some problem. Please try again.";
			}
		}
		if(isset($_POST["Manual_off_all"])) {
			$items = $this->input->post(NULL, TRUE);
			$commisions = $this->customers->UpdateMann_all_prod($items,$acc_enc, 0);
			if($commisions == true){
				$dats["msg"] = "Updated Successfully.";
			} else {
				$dats["error"] = "There was some problem while submitting form. Please try again.";
			}
		}
		if(isset($_POST["addUpdateComm"])){
			$items = $this->input->post(NULL, TRUE);
			$addUpdateComm = $this->customers->addUpdateComm($items);
			if($addUpdateComm == true){
				$data["msg"] = "Update successfully.";
			} else {
				$data["error"] = "There was some problem. Please try again.";
			}
		}
		////////////////////This is Pending///////////
		// $data['getfreshProducts'] = $this->customers->listOfProductsToUpdateROW($_GET["acc_enc"],$search_val,0,$myList);
		if($_COOKIE['user_type'] == "956314127503977533"){
			$data['getProducts'] = $this->customers->listOfProductsToUpdate($_GET["acc_enc"],$search_val);
		} else{
			$data['getProducts'] = $this->customers->listOfProductsToUpdateROW($_GET["acc_enc"],$search_val,1);
			// echo"<pre>";
			// print_r($data['getProducts']);
		}
		$data["acc_enc"] = $acc_enc;
		$data['getprod_types'] = $this->customers->getprod_types($prod_url_id='',$status=1,$alltypes='', $customer_id=$_GET["acc_enc"]);
		$data['title'] = lang('masters');
		$this->load->view('header', $data);
		$this->load->view('customer/updateDiscount', $data);
		$this->load->view('footer'); 
	}
}
?>