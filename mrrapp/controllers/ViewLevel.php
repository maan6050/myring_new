<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class ViewLevel extends MY_Controller
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
        $this->load->model('ViewLevels');
		$this->load->library('form_validation');
	}
    public function viewList()
    {
        if(isset($_GET['cust']) AND $_GET['cust'] !== ""){
            //$acc_dec = $this->customers->customDecryptFunction($_GET['cust']);
            $data['acc_dec'] = $_GET['cust'];
        } else {
            $data['acc_dec'] = $_COOKIE["user_account_id"];
        }
        $data['viewLevels'] = $this->customers->getCustomersList($cust_enc='',$cust_id='',$parent_acc = $data['acc_dec']);
        $data['acc_type'] = $this->customers->getAccountType($cust_acc = $data['acc_dec']);
        $data['current_acc_type'] = $this->customers->getAccountType();
        // echo "<pre>";
        // print_r($data['current_acc_type']);
        switch ($data['acc_type']) {
            case 1:
                $account_title = "Masters";
                $return_to = "";
                break;
            case 2:
                $account_title = "Distributors";
                $return_to = "Masters";
                break;
            case 3:
                $account_title = "Sub-Distributors";
                $return_to = "Distributors";
                break;
            case 4:
                $account_title = "Stores";
                $return_to = "Sub-Distributors";
            break;
            case 5:
                $account_title = "End Users";
                $return_to = "Stores";
            break;    
            default:
                $data['title'] = lang('view_levels');
                $this->load->view('header', $data);
                $data['viewLevels'] = $this->customers->getCustomersList($parent_acc = $data['acc_dec']);
                $this->load->view('viewLevel/list', $data, $return_to);
                $this->load->view('footer');
            break;
        }
        if(($data['acc_type'] > 1) AND ($data['acc_type'] > $data['current_acc_type'])) {
            $parent_acc = $this->customers->getParentAcc($data['acc_dec']);
            if(!empty($parent_acc)){
                //$this->Customers->customEncryptFunction($parent_acc);
                $data['acc_enc_gain'] = $parent_acc[0]->PARENT_ACCOUNT_ID;
            }
        }
        $data['return_to'] = $return_to;
        $data['title'] = $account_title;
		$this->load->view('header', $data);
        $this->load->view('viewLevel/list', $data);
		$this->load->view('footer');
    }
    public function agentDiscounts()
    {
        if(!isset($_COOKIE['user_type']) OR (isset($_COOKIE['user_type']) AND ($_COOKIE['user_type'] == "415285967837575867"))) {
            redirect(base_url('home'));
        }
        $data['acc_type'] = $this->customers->getAccountType();
        if($data['acc_type'] > "4" AND $data['acc_type'] < "8"){
            redirect(base_url('home'));
        }
        if(!isset($_GET['acc_enc']) OR (isset($_GET['acc_enc']) AND ($_GET['acc_enc'] == ""))) {
            redirect(base_url('home'));
        }
        //$this->Customers->customDecryptFunction($_GET['acc_enc']);
        $parent_acc = $this->customers->getParentAcc($_GET['acc_enc']);
        if(!empty($parent_acc)){
            //$this->Customers->customEncryptFunction($parent_acc);
            $data['$acc_enc_gain'] = $parent_acc[0]->PARENT_ACCOUNT_ID;
            $data['account_type'] = $parent_acc[0]->ACCOUNT_TYPE;
            switch ($data['account_type']) {
                case 2:
                    $account_title = "Masters";
                break;
                case 3:
                    $account_title = "Distributors";
                    break;
                case 4:
                    $account_title = "Sub-Distributors";
                    break;
                case 5:
                    $account_title = "Stores";
                break;
                default:
                 redirect(base_url('ViewLevel/viewList'));
                break;
            }
            $data["search_by_type"] = "";
            $search_val = "0";
            if(isset($_GET["filter"])) {
                $data["search_by_type"] = $_GET["filter"]; 
                if($_GET["filter"] == ""){
                    $search_val = "0";
                } else if($_GET["filter"] == "0"){
                    $search_val = "";
                } else {
                    $search_val = $_GET["filter"];
                }
            }
            if($data['account_type'] == "2"){
                $data['getProducts'] = $this->customers->listOfProductsToUpdate($_GET["acc_enc"],$search_val);
            } else{
                $data['getProducts'] = $this->customers->listOfProductsToUpdateROW($_GET["acc_enc"],$search_val,$check="1",$data['$acc_enc_gain']);
            }
            $data['getprod_types'] = $this->customers->getprod_types($prod_url_id='',$status=1,$alltypes='',$customer_id=$_GET["acc_enc"]);
            $data['manage_getprod_types'] = $this->customers->getprod_types($prod_url_id='',$status=1,$alltypes='',$customer_id='');
            $data['getcust_prod_types'] = $this->customers->getCustNames($_GET["acc_enc"]);
            $data['title'] = lang('view_levels');
            $data['account_title'] = $account_title;
            $this->load->view('header', $data);
            $this->load->view('viewLevel/agentDiscount.php', $data);
            $this->load->view('footer');
        } else {
           $this->viewlist();
        }
    }
}