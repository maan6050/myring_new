<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class Profile extends MY_Controller
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
		$this->load->model('profiles');
		$this->load->model('customers');
		$this->load->library('form_validation');
	}
    public function customerEdit(){
		if((isset($_COOKIE["user_type"]))  AND ($_COOKIE["user_type"] == "415285967837575867")) {
			redirect(base_url('onlineStore'));
		}
        if((isset($_COOKIE["user_account_id"]) AND $_COOKIE["user_account_id"] !== "")){
            $data['cust_id'] = $_COOKIE["user_account_id"];
            $data['add_edit'] = 1;
        } else {
			redirect(base_url('login'));
		}
		if(isset($_POST["cust_id"])){
			$data['msg'] = 'Form Update Successfully';
		}
		$data['title'] = "Edit Customer";
		$this->load->view('header', $data);
		$data['customers'] = $this->customers->getCustomersList($cust_enc='',$data['cust_id']);
		$data['checkParentmoduleEnable'] = $this->customers->checkParentmoduleEnable();
		$data['getCountryCodes'] = $this->customers->getCountryCodes();
		$data['getQuestionList'] = $this->customers->getSecurityQuestion();
		$this->load->view('profile/editProfile', $data);
		$this->load->view('footer');
	}
	public function profileFormUpdate()
    {
		if((isset($_COOKIE["user_account_id"]) AND $_COOKIE["user_account_id"] !== "")){
            $data['cust_id'] = $_COOKIE["user_account_id"];
            $data['add_edit'] = 1;
        } else {
			redirect(base_url('login'));
		}	
		if(isset($_POST["submit"])){
			$error = FALSE;
			$msg = "";
			if(isset($_POST["cust_id"]) AND ($_POST["cust_id"] !== "")){
				$this->form_validation->set_rules('f_name', 'First Name', 'required',
					array('required' => 'Please fill in your first name.')
				);
				$this->form_validation->set_rules('l_name', 'Last Name', 'required',
					array('required' => 'Please fill in your last name.')
				);
				$this->form_validation->set_rules('v_email', 'Email Address', 'required|valid_email',
					array('required' => 'Please fill in your email.',
						'valid_email' => 'Please fill in your valid email address.',
					)
				);
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
				$this->form_validation->set_rules('v_country', 'Country', 'required',
					array('required' => 'Please Select your country.')
				);
				$cust_id = $this->input->post('cust_id');
				$msg = FALSE;
				$items = $this->input->post(NULL, TRUE);
				$data['customers'] = $this->customers->getCustomersList($cust_enc='',$data['cust_id']);
				$data['checkParentmoduleEnable'] = $this->customers->checkParentmoduleEnable();
				$data['getCountryCodes'] = $this->customers->getCountryCodes();
				$data['getQuestionList'] = $this->customers->getSecurityQuestion();
				if($this->form_validation->run() !== FALSE) {
					$row = $this->profiles->editProfile($items,$cust_id);
					if(!empty($row)){
						// $data['msg'] = 'Form Update Successfully';
						// $data['title'] = "Edit Customer";
						// $this->load->view('header', $data);
						// $this->load->view('profile/editProfile', $data);
						// $this->load->view('footer');	
						$this->customerEdit();
					}
				} else {
					$data['title'] = "Edit Customer";
					$this->load->view('header', $data);
					$this->load->view('profile/editProfile', $data);
					$this->load->view('footer');
				}
			}
		}
	}
}