<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class Provider extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();

		if (!isset($_SESSION['userId']) || !isset($_SESSION['userType']))
		{
			redirect(base_url('login'));
        }
        if(!isset($_COOKIE["user_type"]) OR (isset($_COOKIE["user_type"]) AND  ($_COOKIE["user_type"] != 956314127503977533))) {
            redirect(base_url('home'));
        }
		$this->lang->load('invoices_lang', $this->getLanguage());
		$this->load->library('pagination');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->model('providers');
		$this->load->model('customers');
		$this->load->library('form_validation');
	}
    public function providerList(){
        $data['title'] = "Providers";
		$this->load->view('header', $data);
		$data['providers'] = $this->providers->getProviderList();
		$this->load->view('provider/viewProviders', $data);
		$this->load->view('footer');
    }
    public function addProvider(){
        if(isset($_GET["NP_ID"]) AND $_GET["NP_ID"]!=""){
            $addEdit = 1; 
            $data['title'] = "Add Provider";
			$this->load->view('header', $data);
			$data['getCountryCodes'] = $this->customers->getCountryCodes();
            $data['provider'] = $this->providers->getProviderList($NP_ID=$_GET["NP_ID"]);
			$this->load->view('provider/addProvider', $data);
			$this->load->view('footer');       
        } else {
            $addEdit = 0;
            $data['title'] = "Add Provider";
			$this->load->view('header', $data);
			$data['getCountryCodes'] = $this->customers->getCountryCodes();
			$this->load->view('provider/addProvider', $data);
			$this->load->view('footer');
        }
    }
    public function providerFormSubmit(){
        if(isset($_POST["submit"])){
            $error = FALSE;
			$msg = "";
			$this->form_validation->set_rules('N_PROVIDER', 'Name', 'required',
				array('required' => 'Please fill in your Name.')
			);
			$this->form_validation->set_rules('NP_EMAIL', 'Email Address', 'required|valid_email',
				array('required' => 'Please fill in your email address.',
				'valid_email' => 'Please fill in your valid email address.'
				)
			);
			if(!isset($_POST["NP_ID"])){
				$this->form_validation->set_rules('NP_EMAIL', 'Email Address', 'is_unique[PROVIDER.NP_EMAIL]',
						array('is_unique' => 'Email already exist.')
				);	
			}
			$this->form_validation->set_rules('NP_CONTACT', 'Contact Name', 'required',
				array('required' => 'Please fill your Contact Name.')
			);
			$this->form_validation->set_rules('NP_ADDRESS', 'Address', 'required',
				array('required' => 'Please fill in address.')
			);
			$this->form_validation->set_rules('NP_COUNTRY', 'Country', 'required',
				array('required' => 'Please fill in Country field.')
			);
			$this->form_validation->set_rules('NP_PHONE', 'Mobile Phone', 'required',
				array('required' => 'Please fill in phone Number.')
			);
			$this->form_validation->set_rules('NP_CITY', 'City', 'required',
				array('required' => 'Please fill in City.')
			);
			$this->form_validation->set_rules('NP_STATE', 'State/ Province', 'required',
				array('required' => 'Please fill in State.')
			);
			$this->form_validation->set_rules('NP_ZIP', 'Zip Code', 'required',
				array('required' => 'Please fill in Zip.')
			);
			$this->form_validation->set_rules('NP_SHORT', 'Short Name', 'required',
				array('required' => 'Please fill in Short.')
			);
			$items = $this->input->post(NULL, TRUE);
			if($this->form_validation->run() !== FALSE)
			{	
				if(isset($_POST["NP_ID"]) AND $_POST["NP_ID"] !== ""){
					$row = $this->providers->updateProvider($items);
					if(!empty($row)){
						$data['title'] = "Provider";
						$this->load->view('header', $data);
						$data['providers'] = $this->providers->getProviderList();
						$data['msg'] = 'Form Update Successfully';
						$this->load->view('provider/viewProviders', $data);
						$this->load->view('footer');	
					} else {
						$error = TRUE;
						$data['error'] = 'There was some problem while updating provider. Please try again.';
						$data['title'] = "Add Provider";
						$this->load->view('header', $data);
						$this->load->view('provider/addProvider', $data);
						$this->load->view('footer');
					}
				} else {
					$row = $this->providers->addProvider($items);
					if(!empty($row)){
						$msg = TRUE;
						$data['msg'] = 'Form Submit Successfully';
						$data['title'] = "Add Provider";
						$this->load->view('header', $data);
						$this->load->view('provider/addProvider', $data);
						$this->load->view('footer');	
					} else {
						$error = TRUE;
						$data['error'] = 'There was some problem while adding provider. Please try again.';
						$data['title'] = "Add Provider";
						$this->load->view('header', $data);
						$this->load->view('provider/addProvider', $data);
						$this->load->view('footer');
					}	
					
				}
            } else {
				$this->addProvider();
			}
        }
    }
}