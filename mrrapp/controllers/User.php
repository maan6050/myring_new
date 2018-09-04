<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class User extends MY_Controller
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
		$this->load->model('users');
		$this->load->model('customers');
		$this->load->library('form_validation');
	}
    public function viewList()
    {
		$data['title'] = lang('users');
		$this->load->view('header', $data);
		$data['users'] = $this->users->getUsersList();
		$this->load->view('user/list', $data);
		$this->load->view('footer');
	}
	
	//////////////////////ADD USER PAGE////////////////////////
    public function add()
    {
        $data["roles"] = '';
		if(isset($_SESSION["userlist"]) AND ($_SESSION["userlist"] !== "usersSearch.cfm")){
			$data['getcompanyList'] = $this->users->getCompanies();
			$$data["roles"] = 'all';
		}
		$data['getcompanyList'] = $this->users->getCompanies();
		$data["getRoleList"] = $this->users->getRoles($data["roles"]);
		$data['getQuestionList'] = $this->customers->getSecurityQuestion();
		if(isset($_GET['userid']) AND ($_GET['userid']!=="")){
			$data['add_edit'] = "1";
			$data['user'] = $this->users->getuserslist($_GET['userid']);
			$data['getUsersIps'] = $this->users->getUsersIps($_GET['userid']);
			if($data['user'] == ""){
				redirect(base_url('User/viewList'));
			}
			$data['title'] = "Edit User";
			$this->load->view('header', $data);
			$this->load->view('user/addUser', $data);
			$this->load->view('footer');
        } else {
            $data['add_edit'] = "0";
			$data['user'] = $this->users->getuserslist($userid="0");
			$data['getUsersIps'] = $this->users->getUsersIps($userid="0"); 
			$data['title'] = "New User";
			$this->load->view('header', $data);
			$this->load->view('user/addUser', $data);
			$this->load->view('footer');
        }
	}
	//////////////////////USER FORM SUBMIT////////////////////////
	public function userformSubmit()
    {
		if(isset($_POST["submit"])){

			if(!isset($_POST['userid'])) {
				$this->form_validation->set_rules('v_secure', 'Security Code', 'required',
					array('required' => 'Please fill in your security code.')
				);
				$this->form_validation->set_rules('confirm_v_secure', 'Confirm Security Code', 'required|matches[v_secure]',
					array('required' => 'Please fill in your confirm security code.',
						'matches' => 'Please Confirm your security code.'
					)
				);
				$this->form_validation->set_rules('f_login', 'Password', 'required',
					array('required' => 'Please fill in your Username.')
				);
				$this->form_validation->set_rules('f_password', 'Confirm Password', 'required|matches[f_login]',
					array('required' => 'Please fill in your Username.',
					'matches' => 'Please Confirm your security code.'
					)
				);
				$this->form_validation->set_rules('v_email', 'Email Address', 'is_unique[users.E_MAIL]',
						array('is_unique' => 'Email already exist.')
				);
				$this->form_validation->set_rules('v_phone', 'Phone', 'is_unique[users.LOCAL_PHONE]',
						array('is_unique' => 'Phone already exist.')
				);
				$this->form_validation->set_rules('login_name', 'Username', 'is_unique[users.LOGIN_NAME]',
					array('is_unique' => 'Username already exist')
				);
			}
			$this->form_validation->set_rules('f_name', 'First Name', 'required',
				array('required' => 'Please fill in your first name.')
			);
			$this->form_validation->set_rules('l_name', 'Last Name', 'required',
				array('required' => 'Please fill in your last name.')
			);
			$this->form_validation->set_rules('login_name', 'Username', 'required|min_length[8]',
				array('required' => 'Please fill in your Username.',
					'min_length' => 'Should have at least 8 characters.'
				)
			);
			$this->form_validation->set_rules('v_email', 'Email', 'required|valid_email',
				array('required' => 'Please fill in your email.',
					'valid_email' => 'Please fill in your valid email address.',
				)
			);
			$this->form_validation->set_rules('v_phone', 'Phone', 'required|numeric|min_length[5]',
				array('required' => 'Please fill in phone Number.',
					'numeric' => 'Should be number',
					'min_length' => 'Should have at least 5 digits.'
				)
			);
			$this->form_validation->set_rules('v_answer', 'Security Answer is:', 'required',
				array('required' => 'Please answer your security Question.')
			);
			$this->form_validation->set_rules('v_question', 'Please select a Security Question.', 'required',
				array('required' => 'Please select your security Question.')
			);

			$items = $this->input->post(NULL, TRUE);

			// $data["roles"] = '';
			// if(isset($_SESSION["userlist"]) AND ($_SESSION["userlist"] !== "usersSearch.cfm")){
			// 	$data['getcompanyList'] = $this->users->getCompanies();
			// 	$$data["roles"] = 'all';
			// }

			// $data['getQuestionList'] = $this->customers->getSecurityQuestion();
			// $data['getcompanyList'] = $this->users->getCompanies();
			// $data["getRoleList"] = $this->users->getRoles($data["roles"]);
			if($this->form_validation->run() !== FALSE) {	
				if(isset($_POST["userid"])){
					$userid = $this->input->post('userid');
					$items = $this->input->post(NULL, TRUE);
					$row = $this->users->updateUsers($items,$userid);
					if($row == true){
						$msg = TRUE;
						$data['msg'] = 'Form Update Successfully';
						$data['title'] = lang('users');
						$this->load->view('header', $data);
						$data['users'] = $this->users->getUsersList();
						$this->load->view('user/list', $data);
						$this->load->view('footer');	
					}
				} else {
					$row = $this->users->addUser($items);
					if($row == true){
						$msg = TRUE;
						$data['msg'] = 'Form Submit Successfully';
						$data['title'] = lang('users');
						$this->load->view('header', $data);
						$data['users'] = $this->users->getUsersList();
						$this->load->view('user/list', $data);
						$this->load->view('footer');	
					}
				}
			} else {
				$userid = $this->input->post('userid');
				if(isset($userid) AND ($userid !== "")){
					$data['add_edit'] = "1";
					$data['user'] = $this->users->getuserslist($userid);
					$data['getUsersIps'] = $this->users->getUsersIps($userid);
					if($data['user'] == ""){
						redirect(base_url('User/viewList'));
					}
					$data['title'] = "Edit User";
					$this->load->view('header', $data);
					$this->load->view('user/addUser', $data);
					$this->load->view('footer');
				} else {
					$data['add_edit'] = "0";
					$data['user'] = $this->users->getuserslist($userid="0");
					$data['getUsersIps'] = $this->users->getUsersIps($userid="0"); 
					$data['title'] = "New User";
					$this->load->view('header', $data);
					$this->load->view('user/addUser', $data);
					$this->load->view('footer');
				}
			}
		}
	}
}