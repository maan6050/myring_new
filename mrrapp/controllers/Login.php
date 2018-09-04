<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		if(isset($_SESSION['userId']))  // El usuario ya inició sesión.
		{
			redirect(base_url('home'));
		}
		else
		{
			$data['title'] = 'Login';
			$this->load->view('login', $data);
		}
	}

	public function authenticate()
	{
		$error = FALSE;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('un', 'Username', 'required');
		$this->form_validation->set_rules('pw', 'Password', 'required');
		if($this->form_validation->run() !== FALSE)
		{
			$items = $this->input->post(NULL, TRUE);
			$this->load->model('customers');
			$row = $this->customers->getEmployee($items['un'],$items['pw']);  // Ahora busco si es un cliente.
			if(!empty($row))
			{
				// set cookie 
				$this->load->helper('cookie');	
				$row = $row[0];
				//declare cookies variable
				set_cookie("user_company", $row->COMPANY, time() + (86400 * 30));
				set_cookie("user_status", $row->ENABLED, time() + (86400 * 30));
				//set_cookie("user_ip", $row->ADDR, time() + (86400 * 30));
				set_cookie("user_ip", $_SERVER['REMOTE_ADDR'], time() + (86400 * 30));
				set_cookie("user_account_id", $row->CUSTOMER_ENC, time() + (86400 * 30));
				set_cookie("user_customer", $row->UFIRST_NAME, time() + (86400 * 30));
				set_cookie("user_type", $row->USER_GROUP_ENC, time() + (86400 * 30));
				set_cookie("user_name", $row->LOGIN_NAME, time() + (86400 * 30));
				set_cookie("THERMAL_RECEIPT", $row->THERMAL_RECEIPT, time() + (86400 * 30));
				set_cookie("CC_ENABLED", $row->CC_ENABLED, time() + (86400 * 30));
				set_cookie("Touch_screen", $row->TOUCHSCREEN, time() + (86400 * 30));	
				if (is_numeric($row->COUNTRY_CODE))
				{
					set_cookie("country", $row->COUNTRY_CODE, time() + (86400 * 30));
				}
				else
				{
					set_cookie("country", 1, time() + (86400 * 30));
				}
				
				set_cookie("current_cur", "USD", time() + (86400 * 30));
				set_cookie("conversion_rate", "1", time() + (86400 * 30));
							
				if(empty($row->allow_prod_types)){
					set_cookie("user_prod_types", "0", time() + (86400 * 30));
				}else{
					set_cookie("user_prod_types", $row->allow_prod_types, time() + (86400 * 30));
				}
				//declare session variables
				$_SESSION['userId'] = $row->ACCOUNT_ID;
				$_SESSION['userName'] = $row->LOGIN_NAME;
				$_SESSION['userType'] = $row->USER_GROUP_NAME;

				if($_COOKIE['user_type'] == "956314127503977533") {
					redirect(base_url('Customer/viewList'));
				} else if($_COOKIE['user_type'] == "638545125236524578") {
					redirect(base_url('Customer/viewList'));
				} else if($_COOKIE['user_type'] == "325210258618165451") {
					redirect(base_url('Customer/viewList'));
				} else if($_COOKIE['user_type'] == "125458968545678354") {
					redirect(base_url('Customer/viewList'));
				}else if($_COOKIE['user_type'] == "415285967837575867") {
					redirect(base_url('home'));
				}else {
					redirect(base_url('home'));
				}
			}
			else
				{
					$error = TRUE;
					$data['un'] = $this->input->post('un');
					$data['error'] = 'Invalid Username or Password.';
				}
		}
		else
		{
			$error = TRUE;
			$data['un'] = $this->input->post('un');
			$data['error'] = validation_errors();
		}
		if($error)
		{
			$data['title'] = 'Login';
			$this->load->view('login', $data);
		}
	}

	public function generate($str)
	{
		$hash = password_hash($str, PASSWORD_DEFAULT);
		echo '<pre>'.$str.' => '.$hash.'</pre>';
	}
}
