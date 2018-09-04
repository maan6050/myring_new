<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class Record extends MY_Controller
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
		$this->load->model('records');
		$this->load->library('form_validation');
	}

    public function logReport(){
		if(isset($_GET["show"]) AND ($_GET["show"] == "all")){
			$data['sDate'] = "";
			$data['eDate'] = "";
			$data['sTime'] = "";
			$data['eTime'] = "";
		} else {
			$data['sDate'] = date("m/d/y");
			$data['eDate'] = date("m/d/y");
			$data['sTime'] = "00:00:00";
			$data['eTime'] = date(" ");      
		}
		if($data['sTime']=="") {
			$data['sTime'] ="00:00:01";
		}
		if($data['eTime']=="") {
			$data['eTime'] ="23:59:59";
		}
		if($data['eTime']=="24:00:00") {
			$data['eTime'] ="23:59:59";
		}
		$starttime = $data['sDate']. " " . $data['sTime'];
		$endttime = $data['eDate']. " " .$data['eTime'];
		$data['getreports'] = $this->records->getLogReports($starttime,$endttime);
		if($data['eTime']=="23:59:59") {
			$data['eTime'] ="24:00:00";
		}
        $data['title'] = "Log Report";
		$this->load->view('header', $data);
		$this->load->view('record/list', $data);
		$this->load->view('footer');
	}
	public function logReportData(){
		$data['sTime'] = $_GET["sTime"];
		if(isset($_GET["sTime"]) AND ($_GET["sTime"]=="")) {
			$data['sTime'] ="00:00:01";
		}
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="")) {
			$data['eTime'] ="23:59:59";
		}
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="24:00:00")) {
			$data['eTime'] ="23:59:59";
		}
		$starttime = $_GET['sDate']. " " . $data['sTime'];
		$endttime = $_GET['eDate']. " " .$data['eTime'];
		$data['getreports'] = $this->records->getLogReports($starttime,$endttime);
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="23:59:59")) {
			$data['eTime'] ="24:00:00";
		}
		$data['title'] = "Log Report";
		$this->load->view('header', $data);
		$this->load->view('record/list', $data);
		$this->load->view('footer');
	}
	public function paymentReport(){
		$userType = "";
		if((isset($_COOKIE["user_type"]) == "956314127503977533")){
			$userType = "owner";
		}
		if((isset($_GET["bType"]) AND $_GET["bType"] == "2")){
			$title = "Bank Payments";
			$billing_type = "2";
		 } else if((isset($_GET["bType"]) AND $_GET["bType"] == "1")){
			$title = "Credit Card Payments";
			$billing_type = 1;
		} else if((isset($_GET["bType"]) AND $_GET["bType"] == "6")){
			$title = "Manual Payments";
			$billing_type = 6;
		} else {
			$title = "My Payments";
			$billing_type = 0;
		}
		if(isset($_GET["show"]) AND ($_GET["show"] == "all")){
			$data['sDate'] = "";
			$data['eDate'] = "";
			$data['sTime'] = "";
			$data['eTime'] = "";
		} else {
			$data['sDate'] = date("m/d/y");
			$data['eDate'] = date("m/d/y");
			$data['sTime'] = "00:00:00";
			$data['eTime'] = date(" ");      
		}
		if($data['sTime']=="") {
			$data['sTime'] ="00:00:01";
		}
		if($data['eTime']=="") {
			$data['eTime'] ="23:59:59";
		}
		if($data['eTime']=="24:00:00") {
			$data['eTime'] ="23:59:59";
		}
		$starttime = $data['sDate']. " " . $data['sTime'];
		$endttime = $data['eDate']. " " .$data['eTime'];
		$data['getreports'] = $this->records->getBillingReports($billing_type,$starttime,$endttime);
		$data['userType'] = $userType;
		$data['title'] = $title;
		$data['billing_type'] = $billing_type;
		$this->load->view('header', $data);
		$this->load->view('record/paymentList', $data);
		$this->load->view('footer');
	}

	public function paymentReportData(){
		$userType = "";
		if((isset($_COOKIE["user_type"]) == "956314127503977533")){
			$userType = "owner";
		}
		if((isset($_GET["bType"]) AND $_GET["bType"] == 2)){
			$title = "Bank Payments";
			$billing_type = "2";
		 } else if((isset($_GET["bType"]) AND $_GET["bType"] == 1)){
			$title = "Credit Card Payments";
			$billing_type = 1;
		} else if((isset($_GET["bType"]) AND $_GET["bType"] == 6)){
			$title = "Manual Payments";
			$billing_type = 6;
		} else {
			$title = "My Payments";
			$billing_type = 0;
		}
		$sTime = $_GET["sTime"];
		$eTime = $_GET["eTime"];
		if(isset($_GET["sTime"]) == "") {
			$sTime ="00:00:01";
		}
		$starttime = $_GET['sDate']. " " . $sTime;
		$endttime = $_GET['eDate']. " " .$eTime;
		$data['getreports'] = $this->records->getBillingReports($billing_type,$starttime,$endttime);
		$data['billing_type'] = $billing_type;
		$data['title'] = $title;
		$data['userType'] = $userType;
		$this->load->view('header', $data);
		$this->load->view('record/paymentList', $data);
		$this->load->view('footer');
	}

	public function balanceReport(){
		$data["bType"] = "";
		if(isset($_GET["cust"]) AND ($_GET["cust"] !== "")){
			//$acc_dec = $this->customers->customDecryptFunction($_GET["cust"]);
			$data["acc_dec"] = $_GET["cust"];
		} else {
			$data["acc_dec"] = $_COOKIE["user_account_id"];
		}
		$data["acc_type"] = $this->customers->getAccountType($cust_acc = $data["acc_dec"]);
		
		switch($data["acc_type"]){
			case 1:
				$data["account_title"] = "Master";
				$data["return_to"] = "Owners";
			break;
			case 2:
				$data["account_title"] = "Distributor";
				$data["return_to"] = "Masters";
			break;
			case 3:
				$data["account_title"] = "Sub-Distributor";
				$data["return_to"] = "Distributors";
			break;
			case 4:
				$data["account_title"] = "Store";
				$data["return_to"] = "Sub-Distributors";
			break;
			case 5:
				$data["account_title"] = "End User";
				$data["return_to"] = "Stores";
			break;
			default :
				redirect(base_url('home'));
			break;
		}
		$data["get_dist"] = $this->customers->getcustomerslist($cust_enc='', $cust_id='',$parent_acc = $data["acc_dec"]);
		
		if(isset($_GET["show"]) AND ($_GET["show"] == "all")){
			$data['sDate'] = "";
			$data['eDate'] = "";
			$data['sTime'] = "";
			$data['eTime'] = "";
		} else if(isset($_GET["sDate"]) AND isset($_GET["eDate"]) AND isset($_GET["sTime"]) AND isset($_GET["eTime"])){
			$data['sDate'] = $_GET["sDate"];
			$data['eDate'] = $_GET["eDate"];
			$data['sTime'] = $_GET["sTime"];
			$data['eTime'] = $_GET["eTime"];
		}else {
			$data['sDate'] = date("m/d/y");
			$data['eDate'] = date("m/d/y");
			$data['sTime'] = "00:00:00";
			$data['eTime'] = date(" ");      
		}
		if($data['sTime']=="") {
			$data['sTime'] ="00:00:01";
		}
		if($data['eTime']=="") {
			$data['eTime'] ="23:59:59";
		}
		if($data['eTime']=="24:00:00") {
			$data['eTime'] ="23:59:59";
		}
		$starttime = $data['sDate']. " " . $data['sTime'];
		$endttime = $data['eDate']. " " .$data['eTime'];

		$data["getreports"] = $this->records->getBillingReportsForAllLevels($starttime,$endttime,$data["acc_dec"],$data["acc_type"]);
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="23:59:59")) {
			$data['eTime'] ="24:00:00";
		}
		$data["parent"] = $this->customers->getParentAcc($data["acc_dec"]);
		// echo "<pre>";
		// print_r($data["parent"]);
		$data['title'] = "Balance Report";
		$this->load->view('header', $data);
		$this->load->view('record/balanceReport', $data);
		$this->load->view('footer');
	}
}