<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class Ticket extends MY_Controller
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
        $this->load->model('tickets');
        $this->load->model('customers');
		$this->load->library('form_validation');
    }

    public function tickets(){
        if(!isset($_COOKIE["user_type"]) OR (isset($_COOKIE["user_type"]) AND ($_COOKIE["user_type"] == 956314127503977533))){
            redirect(base_url('home'));
        }
        if(isset($_POST["submit"])){
            $result = $this->tickets->create_ticket($form);
            if($result !== "not"){
                $data["msg"] = "Message sent Sucessfully";
            } else {
                $data["error"] = "There are some problem, please try again.";
            }
        }
        $data["get_reasons"] = $this->tickets->get_reasons();
        $data["get_reasons_history"] = $this->tickets->get_reasons_history();
        $data["getlevels"] = $this->tickets->getlevels();
        
        $parent = $this->customers->getParentAcc($cust_acc=$_COOKIE["user_account_id"],$field="CUSTOMER_ENC");
        $PARENT_ACCOUNT_ID = $parent[0]->PARENT_ACCOUNT_ID;
        $parent_data = $this->customers->getParentAcc($cust_acc=$PARENT_ACCOUNT_ID,$field="CUSTOMER_ENC");

        $data["parent"] = $parent;
        $data["parent_data"] = $parent_data;
        $data['title'] = lang('create_ticket');
		$this->load->view('header', $data);
		$this->load->view('tickets/createTickets', $data);
		$this->load->view('footer');
    }
}