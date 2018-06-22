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
				$row = $row[0];
				//declare cookies variable
				setcookie("user_company", $row->company, time() + (86400 * 30));
				setcookie("user_status", $row->enabled, time() + (86400 * 30));
				setcookie("user_ip", $row->ADDR, time() + (86400 * 30));
				setcookie("user_account_id", $row->customer_enc, time() + (86400 * 30));
				setcookie("user_customer", $row->UFIRST_NAME, time() + (86400 * 30));
				setcookie("user_type", $row->group_enc, time() + (86400 * 30));
				setcookie("user_name", $row->login_name, time() + (86400 * 30));
				setcookie("THERMAL_RECEIPT", $row->THERMAL_RECEIPT, time() + (86400 * 30));
				setcookie("CC_ENABLED", $row->CC_ENABLED, time() + (86400 * 30));
				setcookie("Touch_screen", $row->TOUCHSCREEN, time() + (86400 * 30));	
				if (is_numeric($row->COUNTRY_CODE))
				{
					setcookie("country", $row->COUNTRY_CODE, time() + (86400 * 30));
				}
				else
				{
					setcookie("country", 1, time() + (86400 * 30));
				}
				
				setcookie("current_cur", "USD", time() + (86400 * 30));
				setcookie("conversion_rate", "1", time() + (86400 * 30));
							
				if (empty($row->ALLOW_PROD_TYPES)){
					setcookie("user_prod_types", "0", time() + (86400 * 30));
				}else{
					setcookie("user_prod_types", $row->ALLOW_PROD_TYPES, time() + (86400 * 30));
				}

				//declare session variables
				$_SESSION['userId'] = $row->ACCOUNT_ID;
				$_SESSION['userName'] = $row->LOGIN_NAME;
				$_SESSION['userType'] = $row->USER_GROUP_NAME;
				redirect(base_url('home'));
				
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
