<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class Banks extends CI_Model
{
	/**
	 * __construct
	 * M�todo constructor.
	 */
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->helper('cookie');
        if( ! ini_get('date.timezone') )
		{
			date_default_timezone_set('GMT');
		}
		$current_date = date("Y/m/d");
	}
	
	public function checkTNC()
	{
		$result = false;
		$sql_select_customers = "SELECT CCV FROM customers WHERE CUSTOMER_ENC = '".$_COOKIE["user_account_id"]."'";
		$query = $this->db->query($sql_select_customers);
		$recordcount = $query->num_rows(); 
		$data = $query->result_array();
		$CCV = $data[0]["CCV"];
		if(($recordcount > "0") AND ($CCV !== "") AND ($CCV > "0")){
			$result = true;
		}
		return $result;
	}

	public function submitTNC($form)
	{
		$sql_update_customers = "UPDATE customers SET CCV = 
		(SELECT USER_ID FROM users WHERE LOGIN_NAME = '".$_COOKIE["user_name"]."')
		WHERE CUSTOMER_ENC = '".$_COOKIE["user_account_id"]."'";
		$query = $this->db->query($sql_update_customers);
		if($query){
			$result = true;
		} else {
			$result = false;
		}
		$result = true;
		return $result;
	}

	public function getCreditInfo()
	{
		$sql_select_cc_module = "SELECT * FROM CC_MODULE WHERE account_id = '".$_COOKIE["user_account_id"]."' AND SAVE_PROF = 1";
		$query = $this->db->query($sql_select_cc_module);	
		$result = $query->result();
		return $result;
	}

	public function getBankInfo()
	{
		$sql_select_ach_module = "SELECT * FROM ACH_MODULE WHERE account_id = '".$_COOKIE["user_account_id"]."' AND ACH_SAVE = 1";
		$query = $this->db->query($sql_select_ach_module);	
		$result = $query->result();
		return $result;
	}

	public function deleteCreditInfo($id)
	{
		$sql_select_cc_module = "SELECT * FROM CC_MODULE WHERE ACCOUNT_ID = '".$_COOKIE["user_account_id"]."' AND CCMOD_ID = $id";
		$query = $this->db->query($sql_select_cc_module);	
		$recordcount = $query->num_rows();
		if($recordcount > "0") {
			$sql_update_cc_module = "UPDATE CC_MODULE SET SAVE_PROF = 0,CC_NUMBER = ''
			WHERE ACCOUNT_ID = '".$_COOKIE["user_account_id"]."' and CCMOD_ID = '$id'";
			$query_cc_module = $this->db->query($sql_update_cc_module);

			$this->customers->insertLogTable("Delete","Deleted Credit Account details for id = $id");
		}
		if($query_cc_module){
			return true;
		} else {
			return false;
		}
	}

	public function getbalanceinfo()
	{
		$sql_select_accounts = "SELECT BALANCE FROM accounts WHERE ACCOUNT_ENC = '".$_COOKIE["user_account_id"]."'";
		$query = $this->db->query($sql_select_accounts);	
		$result = $query->result_array();
		$recordcount = $query->num_rows();
		if($recordcount > "0") {
			return $result;
		} else{
			return 0;
		}
	}

	public function addcreditinfo($form)
	{
		$f_name = $form["f_name"];
		$cc_number = $form["cc_number"];
		$cc_exp_date = $form["cc_month"]."".$form["cc_year"];
		$c_address1 = $form["c_address1"];
		$v_city = $form["v_city"];
		$cty = $form["cty"];
		$v_state = $form["v_state"];
		$v_zip = $form["v_zip"];
		$cc_ccv = $form["cc_ccv"];
		$saveCcName = $form["nickname"]." ".substr($cc_number, -4);
		$cc_code = $_COOKIE["user_account_id"]."".substr($cc_number,0, 2)."".substr($cc_number, -4);
		$current_date = date("Y/m/d");
		
		///-----------------This is Pending----------------------////
		///<!----American Credit cards--->
		/*if(left($form["cc_number"],2) == "37") {
			<cf_encrypt
			string="left($form["cc_number"],7)"
			key="cookie.user_account_id"
			r_encryptedString="encryptedCreditCard">    	
		} else {
			<cf_encrypt
			string="left($form["cc_number"],8)"
			key="cookie.user_account_id"
			r_encryptedString="encryptedCreditCard">
		}*/
		
		$sql_insert_cc_module = "INSERT INTO CC_MODULE (ACCOUNT_ID, CUSTOMER, FIRST_NAME, LAST_NAME, COMPANY, LOCAL_PHONE, E_MAIL,CC_NUMBER,CC_EXP_DATE, CC_NAME, CC_ADDRESS1, CC_ADDRESS2, CC_CITY, CC_COUNTRY, CC_STATE_REGION, CC_POSTAL_CODE, CCV,SAVE_PROF, SAVE_CCNAME, CC_STATUS, CC_CODE, CC_IP, SIGNUP_DATE,CC_PROJECT) VALUES ('".$_COOKIE["user_account_id"]."','".$_COOKIE["user_customer"]."','$f_name',
		'',
		'',
		'',
		'',
		'$cc_number','$cc_exp_date',
		'',
		'$c_address1',
		'',
		'$v_city','$cty','$v_state','$v_zip','$cc_ccv','1','$saveCcName','1',
		'$cc_code',
		'".$_COOKIE["user_ip"]."',
		'$current_date','0')";
		$query = $this->db->query($sql_insert_cc_module);
			
		$this->customers->insertLogTable("INSERT","Added Credit Account details having nickname = $saveCcName");

		///<!--- INSERT INTO RECURRENT--->
		$sql_insert_cc_recurrent = "INSERT into CC_RECURRENT (REC_MOD_ID,REC_FLAG,REC_RECHARGE,REC_ATTEMPTS, REC_ACCOUNT_ID,REC_DATE,REC_BANK_TYPE) VALUES ('0','0','0','3','".$_COOKIE["user_account_id"]."','$current_date','2')";
		$query1 = $this->db->query($sql_insert_cc_recurrent);	
		
		if($query AND $query1){
			$ok = "true";
		}else {
			$ok = "false";	
		}
		return $ok;
	}
}