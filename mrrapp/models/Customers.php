<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class Customers extends CI_Model
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
	
	/**
	 * create
	 * Inserta el registro del cliente usando los datos recibidos del formulario y retorna el ID de dicho registro.
	 */
	public function create($data)
	{
		$this->db->insert('customers', $data);
		return $this->db->insert_id();  // Devuelve el id del registro reci�n insertado.
	}

	/**
	 * delete
	 * Inactiva el registro del cliente.
	 */
	public function delete($id, $type)
	{
		$this->db->set('status', 'i');
		$this->db->where(array('id' => $id, 'type' => $type));
		return $this->db->update('customers');
	}

	/**
	 * getAll
	 * Obtiene los datos de todos los clientes en orden alfab�tico.
	 */
	public function getEmployee($username, $pass = '')
	{
		$this->db->select('	customers.COMPANY,customers.COUNTRY_CODE,customers.COUNTRY,CL.CTY_SHORT, ACC.PARENT_ACCOUNT_ID,CL.CTY_NAME, U.ENABLED, customers.CUSTOMER_ENC, U.FIRST_NAME AS UFIRST_NAME, U.LAST_NAME AS ULAST_NAME, UG.USER_GROUP_ENC, ACC.ACCOUNT_ID, ACC.ACCOUNT_ENC, U.LOGIN_NAME, customers.allow_prod_types,customers.THERMAL_RECEIPT,customers.TOUCHSCREEN,customers.CC_ENABLED,U.USER_TYPE,U.END_USER_VARIFY, UG.USER_GROUP_NAME');
		
		$this->db->join('accounts ACC', 'customers.CUSTOMER_ID = ACC.CUSTOMER_ID', 'INNER');
		$this->db->join('account_types AT', 'ACC.ACCOUNT_TYPE = AT.ACCOUNT_TYPE', 'INNER');
		$this->db->join('users U', 'customers.CUSTOMER_ENC = U.CUSTOMER_ID_ENC', 'INNER');
		$this->db->join('user_groups UG', 'U.USER_TYPE = UG.USER_GROUP_ID', 'INNER');
		$this->db->join('country_list CL', ' (customers.COUNTRY = CL.CTY_ID) ', 'LEFT');
		$query = $this->db->get_where('customers', array('U.LOGIN_NAME' => $username, 'U.LOGIN_PASSWORD' => $pass));//, 'LOGIN_PASSWORD_NEW' => $pass
		$rows = $query->result();
		return $rows;
	}

	/**
	 * getById
	 * Obtiene los datos del cliente indicado por $id.
	 */
	public function getById($id)
	{
		$this->db->select('*');
		$query = $this->db->get_where('CUSTOMERS', array('id' => $id));
		return $query->row();
	}

	/**
	 * getByUsername
	 * Obtiene los datos del cliente indicado por $username.
	 */
	
	/**
	 * update
	 * Actualiza el registro del CUSTOMERS usando los datos recibidos del formulario.
	 */
	public function update($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update('CUSTOMERS', $data);  // Devuelve TRUE en caso de �xito.
	}

	/////////////////////CUSTOM ENCRYPT FUNCTION//////////////////
	public function customEncryptFunction($enc_val)
	{
		$encryptionKey = "enzimaNadi";
		$encryption_val = encrypt(
			$enc_val,
			$encryptionKey,
			"CFMX_COMPAT",
			"hex"
		);
		return $encryption_val;
	}
	public function customDecryptFunction($dec_val)
	{
		$decryptionKey = "enzimaNadi";
        $decryption_val = decrypt(
                $dec_val,
                $decryptionKey,
                "CFMX_COMPAT",
                "hex"
        );
        return $decryption_val;
	}

	public function insertLogTable($activity,$description){
		$sql_insert_log_table = "INSERT INTO LOG_TABLE (ACCOUNT_ID,ACTIVITY,IP,DESCRIPTION,USERNAME) VALUES('".$_COOKIE['user_account_id']."','$activity','".$_COOKIE['user_ip']."','$description','".$_COOKIE['user_name']."')"; 
		$query = $this->db->query($sql_insert_log_table);
	}

	public function getCustomersList($cust_enc='', $cust_id='', $parent_acc='', $service=''){	
		$sql_select_customers = "SELECT * FROM customers C JOIN accounts A ON C.CUSTOMER_ID = A.CUSTOMER_ID "; 
		if(isset($service) AND ($service=="1")){
			$sql_select_customers .= " JOIN users u on C.CUSTOMER_ENC = u.CUSTOMER_ID_ENC";
		}
		$sql_select_customers .= " WHERE 1=1";
		if(isset($service) AND ($service=="1")){
			$sql_select_customers .= " AND u.USER_TYPE ='5'"; 
		} else {
			if(isset($cust_id) AND ($cust_id =="")) {
				$sql_select_customers .= " AND A.PARENT_ACCOUNT_ID  ="; 
				if(isset($parent_acc) AND ($parent_acc>"0")) {
					$sql_select_customers .= "'$parent_acc'";
				} else {
					$sql_select_customers .= "'".$_COOKIE["user_account_id"]."'";
				}
			}
		}
		if(isset($cust_enc) AND ($cust_enc !=="")) {
			if($cust_enc>"0"){
				$sql_select_customers .= " AND C.CUSTOMER_ENC = '$cust_enc'";
			} else {
				$sql_select_customers .= " AND 1 = 0";
			}
		}
		if(isset($cust_id) AND ($cust_id!=="")) {
			$sql_select_customers .= " AND C.CUSTOMER_ENC = '$cust_id'";
		}	
		$sql_select_customers .=" ORDER BY C.COMPANY";
		$query = $this->db->query($sql_select_customers);
		$result = $query->result();
		return $result;
	}

	///////////////////GET FROM customers TABLE///////////////////////////////////////
	public function getCustomersData(){
		$sql_select_customers = "SELECT * FROM customers WHERE CUSTOMER_ENC ='".$_COOKIE['user_account_id']."'";
		$query = $this->db->query($sql_select_customers);
		$result = $query->result();
		return $result;
	}

	//////////////////////////////////ADD CUSTOMERS////////////////////////////////
	public function addCustomers($items)
	{	
		$ok = FALSE;
		$msg = "";
		if($_COOKIE["user_type"] == 956314127503977533) { ///////OWNER/////
			$rate_schedule_id = 2000;
			$account_group_id = 2;
			$account_type = 2;
			$billPayment_disc = 67.40;
		}
		if($_COOKIE["user_type"] == 638545125236524578) { ///////Master/////
			$rate_schedule_id = 3000;
			$account_group_id = 3;
			$account_type = 3;
			$billPayment_disc = 59.89;
		}
		if($_COOKIE["user_type"] == 325210258618165451) { ///////Distributors Menu/////
			$rate_schedule_id = 4000;
			$account_group_id = 4;
			$account_type = 4;
			$billPayment_disc = 49.87;
		}
		if($_COOKIE["user_type"] == 125458968545678354) { ///////Sub Distributors Menu/////
			$rate_schedule_id = 5000;
			$account_group_id = 5;
			$account_type = 5;
			$billPayment_disc = 37.50;
		}
		if($_COOKIE["user_type"] == 415285967837575867) { ///////Stores Menu/////
			$rate_schedule_id = 6000;
			$account_group_id = 6;
			$account_type = 6;
			$billPayment_disc = 0.0;
		}
		if($_COOKIE["user_type"] == 863252457813278645) { ///////Stores Menu/////
			$rate_schedule_id = 7000;
			$account_group_id = 7;
			$account_type = 7;
			$billPayment_disc = 0.0;
		}
		if(isset($items["cc_fee_enabled"])){
			$cc_fee_enabled = $items["cc_fee_enabled"];
		}
		$first_name = $items["f_name"];
		$last_name = $items["l_name"];
		$company = $items["f_company"];
		$address1 = $items["v_address1"];
		$address2 = $items["v_suite"];
		$city = $items["v_cty"];
		$state = $items["v_state"];
		$zip_code = $items["v_zip"];
		$country = $items["v_country"];
		$latitude = $items["latitude"];
		$longitude = $items["longitude"];
		$local_phone = $items["v_phone"];
		$email = $items["v_email"];
		$question = $items["v_question"];
		$answer = $items["v_answer"];
		$tax_id = $items["tax_id"];
		$fax = $items["v_fax"];
		$plan_prod = $items["plan_prod"];

		if(isset($_COOKIE["CC_ENABLED"])){
			$public_customer = $_COOKIE["CC_ENABLED"];
		} else {
			$public_customer = "";
		}
		if(isset($_COOKIE["country"])){
			$country_code = $_COOKIE["country"];
		} else {
			$country_code = "";
		}
		if(isset($_COOKIE["user_prod_types"])){
			$user_prod_types = $_COOKIE["user_prod_types"];
		} else {
			$user_prod_types = "";
		}
		if(isset($_COOKIE["Touch_screen"])){
			$Touch_screen = $_COOKIE["Touch_screen"];
		} else {
			$Touch_screen = "";
		}
		if(isset($_COOKIE["THERMAL_RECEIPT"])){
			$THERMAL_RECEIPT = $_COOKIE["THERMAL_RECEIPT"];
		} else {
			$THERMAL_RECEIPT = "";
		}
		$credit_limit = 0;

		///////////////////SELECTION FROM PIN_CONTROL TABLE///////////////////////////////////////
		$sql_select_pin_control = "SELECT * FROM PIN_CONTROL WHERE PIN_STATUS = 0 ORDER BY PIN_ID asc limit 1";
		$query = $this->db->query($sql_select_pin_control);
		$data = $query->result_array();
		$pin_id = $data[0]["PIN_ID"]; 
		$pin_account = $data[0]["PIN_ACCOUNT"];
		
		///////////////////UPDATION OF PIN_CONTROL TABLE///////////////////////////////////////
		$sql_update_pin_control = " UPDATE PIN_CONTROL SET  PIN_STATUS = 1 WHERE PIN_ID = '$pin_id'";
		$query = $this->db->query($sql_update_pin_control);
		
		////////////////////////INSERTION IN customers TABLE//////////////////////////
		$sql_insert_customers = "INSERT INTO customers (CUSTOMER_ENC,CUSTOMER,FIRST_NAME,LAST_NAME,COMPANY,ADDRESS1,ADDRESS2,CITY,STATE_REGION,POSTAL_CODE,COUNTRY,USER_7,USER_8,LOCAL_PHONE,E_MAIL,USER_1,USER_2,USER_3,USER_4,FAX,USER_6";
		if(isset($cc_fee_enabled)){
			$sql_insert_customers .=",PUBLIC_CUSTOMER";
		}
		$sql_insert_customers .=",COUNTRY_CODE,allow_prod_types,TOUCHSCREEN,THERMAL_RECEIPT)
		VALUES ('$pin_account','$pin_account','$first_name','$last_name','$company','$address1','$address2','$city','$state','$zip_code','$country','$latitude','$longitude','$local_phone','$email','0','$question','$answer','$tax_id','$fax','$plan_prod'";
		if(isset($cc_fee_enabled)){
			$sql_insert_customers .=",'$public_customer'";
		}
		$sql_insert_customers .=",'$country_code','$user_prod_types','$Touch_screen','$THERMAL_RECEIPT')"; 
		$query = $this->db->query($sql_insert_customers);

		///////////////////INSERTION IN LOG_TABLE///////////////////////////////////////	
		$this->insertLogTable("Insert","Added New Customer- $first_name'  '$last_name");

		$result_bit = $this->addDiscounts($plan_prod, $pin_account, $account_type, $billPayment_disc);

		///////////////////INSERTION IN LOG_TABLE///////////////////////////////////////
		$this->insertLogTable("Products assigned","Assigned all Product Discounts to $first_name");

		///////////////////INSERTION IN CLASS_OF_SERVICE TABLE///////////////////////////////////////
		$sql_insert_class_of_service = "INSERT INTO CLASS_OF_SERVICE (COS_ENC,COS,OPTIONS,EXPIRE_DATE,GMT_OFFSET,CURRENCY_ID,LANGUAGE_ID,RATE_SCHEDULE_ID,SALES_GROUP_ID,TAX_GROUP_ID,PUBLIC_COS)
		VALUES (0,'Master $pin_account','MAXCONCURRENT=1','2050-12-31 00:00:00','-5','1','1','$rate_schedule_id','0','0','1')"; 
		$query = $this->db->query($sql_insert_class_of_service);

		///////////////////SELECTION FROM CLASS_OF_SERVICE TABLE///////////////////////////////////////
		$sql_select_class_of_service = "SELECT max(COS_ID) as cos_id1 FROM  CLASS_OF_SERVICE WHERE rate_schedule_id = '$rate_schedule_id'"; 
		$query = $this->db->query($sql_select_class_of_service);
		$data = $query->result_array();
		$cos_id1 = $data[0]["cos_id1"];
		
		///////////////////SELECTION FROM customers TABLE///////////////////////////////////////
		$sql_select_customers = "SELECT * FROM  customers WHERE CUSTOMER_ENC = '$pin_account'";
		$query = $this->db->query($sql_select_customers); 
		$data = $query->result_array(); 
		$customer_id = $data[0]["CUSTOMER_ID"];

		///////////////////INSERTION IN accounts TABLE///////////////////////////////////////
		$current_date = date("Y/m/d");
		$sql_insert_accounts = "INSERT INTO accounts (ACCOUNT_ENC,ACCOUNT,PIN,CUSTOMER_ID,BATCH_ID,ACCOUNT_GROUP_ID,ACCOUNT_TYPE,PARENT_ACCOUNT_ID,ENABLED,BILLING_TYPE,STARTING_BALANCE,CREDIT_LIMIT, BALANCE,STARTING_PACKAGED_BALANCE1,COS_ID,WRITE_CDR,CREATION_DATE_TIME,SEQUENCE_NUMBER,PACKAGED_BALANCE1,SERVICE_CHARGE_STATUS,CALLS_TO_DATE,MINUTES_TO_DATE_BILLED,MINUTES_TO_DATE_ACTUAL,PACKAGED_BALANCE2,PACKAGED_BALANCE3,PACKAGED_BALANCE4,PACKAGED_BALANCE5,STARTING_PACKAGED_BALANCE2,STARTING_PACKAGED_BALANCE3,STARTING_PACKAGED_BALANCE4,STARTING_PACKAGED_BALANCE5) VALUES
		('$pin_account','$pin_account',0,'$customer_id','0','$account_group_id','$account_type','".$_COOKIE['user_account_id']."','1','1','0','$credit_limit','0','0','$cos_id1','0','$current_date',0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
		$query = $this->db->query($sql_insert_accounts);		  

		if($query){
			return $msg = "Form Submit Successfully";
		}	
	}

	//////////////////////////////////UPDATION OF customers TABLE////////////////////////////////
	public function updateCustomers($items,$cust_enc){
		$credit_limit = 0;
		$first_name = $items["f_name"];
		$last_name = $items["l_name"];
		$company = $items["f_company"];
		$address1 = $items["v_address1"];
		$address2 = $items["v_suite"];
		$city = $items["v_cty"];
		$state = $items["v_state"];
		$zip_code = $items["v_zip"];
		$country = $items["v_country"];
		$latitude = $items["latitude"];
		$longitude = $items["longitude"];
		$local_phone = $items["v_phone"];
		$email = $items["v_email"];
		$question = $items["v_question"];
		$answer = $items["v_answer"];
		$tax_id = $items["tax_id"];
		$fax = $items["v_fax"];
		$plan_prod = $items["plan_prod"];
		$v_enabled = $items["v_enabled"];
		if(isset($items["CC_ENABLED"])){
			$CC_ENABLED = $items["cc_fee_enabled"];
		}
				
		$sql_update_customers = " UPDATE customers SET  FIRST_NAME = '$first_name',LAST_NAME = '$last_name',COMPANY = '$company',ADDRESS1 = '$address1',ADDRESS2 = '$address2',CITY = '$city',STATE_REGION = '$state',POSTAL_CODE = '$zip_code',COUNTRY = '$country',USER_7 = '$latitude',USER_8 = '$longitude',LOCAL_PHONE = '$local_phone',E_MAIL = '$email',USER_2 = '$question',USER_3 = '$answer',USER_4 = '$tax_id',FAX = '$fax'";
		if(isset($_COOKIE["user_type"])!== "525874964125375325"){
			$sql_update_customers .= ",USER_6 = '$plan_prod'";
		}
		if(isset($cc_fee_enabled)) {
			$sql_update_customers .= ",public_customer = '".$items['cc_fee_enabled']."'";
		}
		$sql_update_customers .= ",COUNTRY_CODE =";
		if(isset($country_code)) { 
			$sql_update_customers .= "'$country_code'"; 
		} else {
			$sql_update_customers .= " '1'"; 
		}
		$sql_update_customers .= " ,allow_prod_types = '".$_COOKIE['user_prod_types']."'";
		$sql_update_customers .= ",TOUCHSCREEN = ";
		if(isset($TOUCHSCREEN)){
			$sql_update_customers .= "'1'";
		}else {
			$sql_update_customers .= "'0'";
		}
		$sql_update_customers .= " ,THERMAL_RECEIPT= ";
		if(isset($THERMAL_RECEIPT)){
			$sql_update_customers .= "'1'";
		}else {
			$sql_update_customers .= "'0'";
		}
		if(isset($CC_ENABLED)) {
			$sql_update_customers .= ",CC_ENABLED = ";
			if($CC_ENABLED ==1){
				$sql_update_customers .= "'1'";
			} else { 
				$sql_update_customers .= " '0'";
			}
			$sql_update_customers .= ",CC_ENABLED_BY = '".$_COOKIE['user_account_id']."'";
		}

		$sql_update_customers .= "WHERE CUSTOMER_ENC = '$cust_enc'";
		$query = $this->db->query($sql_update_customers);

		if(isset($CC_ENABLED)){ 
			$updatePyamentModule = $this->updateCCPayment($CC_ENABLED,$cust_enc);
		}
		$this->insertLogTable("Update","Updated CUSTOMERS Table for CUSTOMER_ENC = $cust_enc");
		
		if(isset($_COOKIE['user_type']) !== "525874964125375325"){
			if(isset($IFDISCOUNTS) AND ($IFDISCOUNTS) == "1"){
				$sql_select_customers = "SELECT A.account_types FROM customers C JOIN ACCOUNTS A ON(C.CUSTOMER_ENC = A.ACCOUNT_ENC) WHERE C.CUSTOMER_ENC = '".$_COOKIE['user_account_id']."'";
				$query = $this->db->query($sql_select_customers);
				$data = $query->result_array();
				$accountType = 	$data[0]["ACCOUNT_TYPE"];
				$result_bit = $this->UpdateDiscounts($plan_prod,$cust_enc,$accountType);

				$this->insertLogTable("Products assigned","Update all Product Discounts to $cust_enc customer While updating");
			}
		}
		if(isset($credit_limit)){
			$sql_update_accounts = "UPDATE accounts SET credit_limit = '$credit_limit',ENABLED = '$v_enabled' WHERE ACCOUNT_ENC = '$cust_enc'";
			$query = $this->db->query($sql_update_accounts); 
			 
			$this->insertLogTable("Update","Updated accounts Table for ACCOUNT_ENC = $cust_enc");
		}
		if($query){
			return $msg = "Form Update Successfully";
		}
	}

	//////////////////////////////////UPDATE CC PAYMENT////////////////////////////////
	public function updateCCPayment($data,$acc){
		$acc_enc = $acc;
		$sql_update_customers ="UPDATE customers SET CC_ENABLED = '$data',CC_ENABLED_BY = '".$_COOKIE['user_account_id']."' WHERE CUSTOMER_ENC = '$acc_enc'";
		$query = $this->db->query($sql_update_customers);
				
		$sql_select_accounts ="SELECT ACCOUNT_ENC AS LEVEL1, ACCOUNT_TYPE AS ACCOUNT_TYPE1 FROM accounts 
		WHERE PARENT_ACCOUNT_ID = '$acc_enc' GROUP BY ACCOUNT_ENC";
		$query = $this->db->query($sql_select_accounts);
		$get_accounts = $query->result_array();
		for($i=0;$i<sizeof($get_accounts);$i++){
			if($i<(sizeof($get_accounts)-1)){
				$comma = ",";
			} else {
				$comma = "";
			}
			$level1 .= $get_accounts[$i]["LEVEL1"].$comma;	
		}
		if(($get_accounts > "0") AND ($get_accounts["0"]["ACCOUNT_TYPE1"] <= "5")){
			$sql_update_customers = "UPDATE customers SET CC_ENABLED = '$data',CC_ENABLED_BY ='".$_COOKIE['user_account_id']."' WHERE CUSTOMER_ENC in ($level1)";
			$query = $this->db->query($sql_update_customers);
			
			if($get_accounts[0]["ACCOUNT_TYPE1"] < "5"){
				$sql_select_accounts = "SELECT A2.ACCOUNT_ENC AS LEVEL2, A2.ACCOUNT_TYPE AS ACCOUNT_TYPE2 FROM  accounts A JOIN accounts A2 ON A2.PARENT_ACCOUNT_ID = A.ACCOUNT_ENC WHERE A.PARENT_ACCOUNT_ID = '$acc_enc' GROUP BY A2.ACCOUNT_ENC";
				$query = $this->db->query($sql_select_accounts);
				$getSubDist = $query->result_array();
				for($i=0;$i<sizeof($getSubDist);$i++){
					if($i<(sizeof($getSubDist)-1)){
						$comma = ",";
					} else {
						$comma = "";
					}
					$level2 .= $getSubDist[$i]["LEVEL2"].$comma;	
				}
				if(($getSubDist > "0") AND ($getSubDist["0"]["ACCOUNT_TYPE2"] <= "5")){
					$sql_update_customers = "UPDATE customers SET CC_ENABLED = '$data'
					,CC_ENABLED_BY = '".$_COOKIE['user_account_id']."' WHERE CUSTOMER_ENC in ($level2)";
					$query = $this->db->query($sql_update_customers);

					if($getSubDist[0]["ACCOUNT_TYPE2"] < "5"){
						$sql_select_accounts = "SELECT A3.ACCOUNT_ENC AS LEVEL3, A3.ACCOUNT_TYPE AS ACCOUNT_TYPE3 FROM  accounts A JOIN accounts A2 ON A2.PARENT_ACCOUNT_ID = A.ACCOUNT_ENC JOIN accounts A3 ON A3.PARENT_ACCOUNT_ID = A2.ACCOUNT_ENC WHERE A.PARENT_ACCOUNT_ID = '$acc_enc'
						GROUP BY A3.ACCOUNT_ENC";
						$query = $this->db->query($sql_select_accounts);
						$getStore = $query->result_array();	
						for($i=0;$i<sizeof($getStore);$i++){
							if($i<(sizeof($getStore)-1)){
								$comma = ",";
							} else {
								$comma = "";
							}
							$level3 .= $getStore[$i]["LEVEL3"].$comma;	
						}
						if(($getStore > "0") AND ($getStore["0"]["ACCOUNT_TYPE3"] <= "5")){
							$sql_update_customers ="UPDATE customers SET CC_ENABLED = $data ,CC_ENABLED_BY = '".$_COOKIE['user_account_id']."' WHERE CUSTOMER_ENC in ($level3)";
							$query = $this->db->query($sql_select_accounts);
						}
						
					}
				}
			}
		}
		return $ok;	
	}

	//////////////////////////////////CHECK EMAIL EXIST CUSTOMERS FORM////////////////////////////////
	public function checkEmailExist($items){
		$this->db->select('customers.E_MAIL');
		$query = $this->db->get_where('customers', array('customers.E_MAIL' => $items["v_email"])); $rows = $query->result();
		return $rows;
	}
	public function checkParentmoduleEnable()
	{
		$sql_select_accounts = " SELECT PARENT_ACCOUNT_ID FROM accounts WHERE ACCOUNT_ENC ='".$_COOKIE['user_account_id']."'";
		$query = $this->db->query($sql_select_accounts);
		$data = $query->result_array(); 
		$parent_account_id = $data[0]["PARENT_ACCOUNT_ID"];
		if( $parent_account_id== 0){
        	return 0;
		}
        $sql_select_customers = " SELECT CC_ENABLED FROM customers WHERE CUSTOMER_ENC ='$parent_account_id'";
        $query = $this->db->query($sql_select_customers);
		$data = $query->result_array();
		$cc_enabled = $data[0]["CC_ENABLED"];
        $stausval = $cc_enabled;
        if(isset($_COOKIE['user_type']) == "956314127503977533") {
        	$stausval = 1;
		}
		return $stausval;
	}

	public function addDiscounts($plan_prod, $pin_account, $account_type, $billPayment_disc)
	{
		if($account_type == "1"){
			$stored_prc = "AssignDiscounts";
		}else {
			$stored_prc = "AssignDiscountsExceptOwner";
		}
		/*<cfstoredproc procedure="#stored_prc#" dataSource ="#request.db_dsn#">
			<cfprocparam cfsqltype="cf_sql_integer" value="#arguments.plan_id#">
			<cfprocparam cfsqltype="cf_sql_varchar" value="#arguments.account#">
			<cfprocparam cfsqltype="cf_sql_varchar" value="#cookie.user_account_id#">
			<cfprocparam cfsqltype="cf_sql_double" value="#arguments.billPaymentDiscount#">
		</cfstoredproc>*/
	}

	public function addBalance($items)
	{
		$msg = "";
		$users = $this->getHeaderInfo();
		$TOTALSUM = $users[0]->TOTALSUM;
		$CREDIT_LIMIT = $users[0]->CREDIT_LIMIT;
		$BALANCE = $users[0]->BALANCE;
		$cre_det = $items["cre_det"];
		$cre_desc = $items["cre_desc"];
		$cre_amount = $items["cre_amount"];
		$customer_enc = $items["customer_enc"];
		if(!isset($customer_enc)){
			redirect(base_url('Customer/viewList'));
		}
		if(isset($_COOKIE["user_type"])!="956314127503977533"){
			if($cre_amount >0){
				if($TOTALSUM = ""){
					$TOTALSUM = 0;
				}
				if($CREDIT_LIMIT =  ""){
					$CREDIT_LIMIT = $CREDIT_LIMIT;
				}    
				if (($TOTALSUM + $cre_amount) > ($CREDIT_LIMIT + $BALANCE)){
					$msg = "Not authorized. Please increase balance.";
				}
			}
			if((!isset($security_pin)) OR (isset($security_pin) and ($security_pin == ""))){
				$msg = "Please enter security pin.";
			} else {	
				$v_secure = $security_pin;
				////Check if security pin is correct
				//<cfobject name="user" component="cfc.Users">
				$checksec_pin = checkCurrentPINValid($v_secure);
				if($checksec_pin != true){
					$msg = "Wrong security PIN.";
				}   
			}
		}
		if($msg == "" ){
			$addPayments = $this->addBalanceToMaster($items);
			if($addPayments == true){
				return true;
			}
		} else {
			return $msg;
		}
	}

	public function addBalanceToMaster($items)
	{
		$cre_det = $items["cre_det"];
		$cre_desc = $items["cre_desc"];
		$cre_amount = $items["cre_amount"];
		$customer_enc = $items["customer_enc"];
		$sql_select_customers = "SELECT * FROM customers c JOIN accounts ac ON c.customer_id = ac.customer_id
			JOIN ACCOUNT_GROUPS ag ON ac.account_group_id = ag.account_group_id WHERE ac.account_enc = '$customer_enc'";
		$query = $this->db->query($sql_select_customers);
		$data = $query->result_array(); 

		$ACCOUNT_ID = $data[0]["ACCOUNT_ID"];
		$BALANCE = $data[0]["BALANCE"];
		$ACCOUNT = $data[0]["ACCOUNT"];
		$ACCOUNT_GROUP = $data[0]["ACCOUNT_GROUP"];
		$current_date_time = date("Y-m-d H:i:sa");

		if($cre_amount <= '0'){
			$cre_amo	=  $cre_amount ;
			$cre_amo1	=  $cre_amount;
			$curr_credit =  $BALANCE;
			$new_credit = $curr_credit + $cre_amo;
			
			$sql_insert_billing = "INSERT into BILLING
				(ENTRY_TYPE, ACCOUNT_ID, ACCOUNT, ACCOUNT_GROUP, START_DATE_TIME, CONNECT_DATE_TIME, DISCONNECT_DATE_TIME,LOGIN_NAME, NODE, NODE_TYPE, DESCRIPTION, DETAIL, PER_CALL_CHARGE, PER_MINUTE_CHARGE, PER_CALL_SURCHARGE, PER_MINUTE_SURCHARGE, ACTUAL_DURATION, QUANTITY, AMOUNT,  CONVERSION_RATE, RATE_INTERVAL, DISCONNECT_CHARGE, BILLING_DELAY, GRACE_PERIOD, ACCOUNT_TYPE,ORIGIN, PARENT_ACCOUNT, PARENT_ACCOUNT_ID, USER_4) VALUES
			
				('6', '$ACCOUNT_ID','$ACCOUNT','$ACCOUNT_GROUP','$current_date_time', '$current_date_time','$current_date_time', '".$_COOKIE['user_account_id']."','TMC','1', '$cre_desc','$cre_det','0','0','0','0','0', '0', '$cre_amount', '0', '0', '0', '0', '0', '2','0','".$_COOKIE['user_account_id']."','".$_COOKIE['user_account_id']."','$new_credit')";
			$query = $this->db->query($sql_insert_billing);
			
			$sql_update_accounts = "UPDATE accounts
				SET	last_credit_date_time = '$current_date_time',balance	= '$new_credit'
				WHERE	account_id = '$ACCOUNT_ID'";
			$query = $this->db->query($sql_update_accounts);
		} else {
			$curr_credit = $BALANCE;
            if($curr_credit==""){
            	$curr_credit  = "0";
			}
			$cre_amo	= $cre_amount ;
			$new_credit = $curr_credit + $cre_amo;
			$sql_insert_billing = " INSERT into BILLING (ENTRY_TYPE, ACCOUNT_ID, ACCOUNT, ACCOUNT_GROUP, START_DATE_TIME, CONNECT_DATE_TIME, DISCONNECT_DATE_TIME,LOGIN_NAME, NODE, NODE_TYPE, DESCRIPTION, DETAIL, PER_CALL_CHARGE, PER_MINUTE_CHARGE, PER_CALL_SURCHARGE, PER_MINUTE_SURCHARGE, ACTUAL_DURATION, QUANTITY, AMOUNT,  CONVERSION_RATE, RATE_INTERVAL, DISCONNECT_CHARGE, BILLING_DELAY, GRACE_PERIOD, ACCOUNT_TYPE,ORIGIN, PARENT_ACCOUNT, PARENT_ACCOUNT_ID, USER_4) VALUES ('6','$ACCOUNT_ID','$ACCOUNT','$ACCOUNT_GROUP','$current_date_time','$current_date_time', '$current_date_time','".$_COOKIE['user_account_id']."', 'TMC', '1', '$cre_desc', '$cre_det', '0', '0', '0', '0','0', '0', '$cre_amount', '0', '0', '0', '0', '0', '2','0','".$_COOKIE['user_account_id']."','".$_COOKIE['user_account_id']."','$new_credit')";
			$query = $this->db->query($sql_insert_billing);	
			
			$sql_update_accounts = "UPDATE accounts
				SET	last_credit_date_time = '$current_date_time',BALANCE	= '$new_credit' WHERE	account_id = '$ACCOUNT_ID'";
			$query = $this->db->query($sql_update_accounts);	

			
			$this->insertLogTable("Update","Update balace by $cre_amount");	
			return true;
		}
	}

	public function getHeaderInfo()
	{
		///////////////////SELECTION FROM users TABLE///////////////////////////////////////
		$sql_select_users = "SELECT C.COMPANY, A.BALANCE, A.CREDIT_LIMIT, U.FIRST_NAME , U.LAST_NAME, (SELECT SUM(BALANCE) FROM accounts WHERE PARENT_ACCOUNT_ID = '".$_COOKIE['user_account_id']."' AND BALANCE > 0) AS TOTALSUM FROM users U LEFT JOIN customers C ON (C.CUSTOMER_ENC = U.CUSTOMER_ID_ENC) LEFT JOIN accounts A ON (U.CUSTOMER_ID_ENC = A.ACCOUNT_ENC) WHERE U.CUSTOMER_ID_ENC = '".$_COOKIE['user_account_id']."' AND U.LOGIN_NAME = '".$_COOKIE['user_name']."'";
		$query = $this->db->query($sql_select_users);	
		$data = $query->result(); 
		return $data;
	}

	public function checkCurrentPINValid($v_secure)
	{
		$enc_pin = encrypt(left($v_secure,4), encryptionUserKey, "CFMX_COMPAT",	"hex" );
		$sql_select_users = "SELECT * FROM USERS WHERE SEC_PIN_NEW = '$enc_pin' AND LOGIN_NAME = '".$_COOKIE['user_name']."'";
		$query = $this->db->query($sql_select_users);
		$recordcount = $query->num_rows();
		if($recordcount > 0){
			$ok = true ;
		} else {
			$ok = false ;
		}
		return $ok;
	}
	
	public function getAccountType($cust_acc='')
	{
		if((isset($cust_acc)) AND ($cust_acc !=="")){
			$sql_select_customers = "SELECT A.account_type FROM customers C JOIN accounts A ON (A.ACCOUNT_ENC = C.CUSTOMER_ENC) WHERE C.CUSTOMER_ENC = '$cust_acc'";
			$query = $this->db->query($sql_select_customers);
			$recordcount = $query->num_rows();
			$data = $query->result(); 
		} else {
			$sql_select_account_type = "SELECT ACCOUNT_TYPE FROM account_types WHERE ACCOUNT_TYPE_ENC = '".$_COOKIE['user_type']."'";
			$query = $this->db->query($sql_select_account_type);
			$recordcount = $query->num_rows();
			$data = $query->result(); 
		}
		if($recordcount>0){
			if((isset($cust_acc)) AND ($cust_acc !== '')){
				$current_acc = $data[0]->account_type;
				return $current_acc;
			} else {
				$current_acc = $data[0]->ACCOUNT_TYPE;
				return $current_acc;
			}
		}
	}
	public function getParentAcc($cust_acc='', $field='') {
		$sql_select_customers = "SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.* FROM customers C
		JOIN accounts A ON (A.ACCOUNT_ENC = C.CUSTOMER_ENC) WHERE ";
		if(isset($field) AND ($field!=="")){
			$sql_select_customers .=" $field = '$cust_acc'";
		}else {
			$sql_select_customers .=" C.CUSTOMER_ENC = '$cust_acc'";
		}
		$query = $this->db->query($sql_select_customers);
		$result = $query->result();
		return $result;
	}
	public function listOfProductsToUpdate($customer_id, $prod_type_id='')
	{
		$sql_select_products = " SELECT * FROM PRODUCTS P LEFT  JOIN PROD_COMM PC ON(P.PROD_ID = PC.PROD_ID)
		JOIN PRODUCT_TYPE PT ON P.PROD_TYPE_ID = PT.PROD_TYPE_ID INNER JOIN PROVIDER PR ON (P.PROD_PROVIDER = PR.NP_ID) WHERE P.PROD_STATUS = '1' AND PC.ACCOUNT = '$customer_id'";
		if(isset($prod_type_id) AND ($prod_type_id !== "")){
			$sql_select_products .= " AND P.PROD_TYPE_ID = '$prod_type_id'";
		} else {
			if(isset($customer_id) AND ($customer_id !== "")){
				$active_prod_types = $this->getCustNames($customer_id);
				if($active_prod_types->ALLOW_PROD_TYPES == ""){
					$sql_select_products .= " AND P.PROD_TYPE_ID = '0'";
				} else {
					$sql_select_products .= " AND P.PROD_TYPE_ID in ($active_prod_types->ALLOW_PROD_TYPES)";
				}
			} else {
				if(isset($_COOKIE['user_type']) !== "956314127503977533"){
					$sql_select_products .= " AND P.PROD_TYPE_ID in ('".$_COOKIE['user_prod_types']."')";
				}
			}
		}
		$sql_select_products .= " ORDER BY PROD_NAME"; 
		$query = $this->db->query($sql_select_products);
		$result = $query->result();
		return $result;
	}
	
	public function listOfProductsToUpdateROW($customer_id, $prod_type_id='',$check='',$list='',$parent_account_id='')
	{
		if(isset($check) AND ($check) == "1"){
			$sql_select_products = " SELECT PC.*, PC1.PROD_COMM AS PROD_DISCOUNT, P.PROD_NAME, PT.PROD_TYPE_NAME, PR.N_PROVIDER, PR.NP_SHORT FROM PROD_COMM PC LEFT JOIN 
			PROD_COMM PC1 ON (PC.PARENT_ACCOUNT_ID = PC1.ACCOUNT) LEFT JOIN PRODUCTS P ON (P.PROD_ID = PC.PROD_ID) LEFT JOIN PRODUCT_TYPE PT ON (PT.PROD_TYPE_ID = P.PROD_TYPE_ID) INNER JOIN PROVIDER PR ON (P.PROD_PROVIDER = PR.NP_ID) WHERE PC.PARENT_ACCOUNT_ID = "; 
			if(($parent_account_id) AND ($parent_account_id) != ""){
				$sql_select_products .= "'$parent_account_id'";
			} else {
				$sql_select_products .= "'".$_COOKIE['user_account_id']."'";
			}
			$sql_select_products .= " AND PC.ACCOUNT = '$customer_id' AND PC.PROD_ID = PC1.PROD_ID 
			AND PC1.COMM_STATUS = 1 AND P.PROD_STATUS = 1";
			if(($prod_type_id) AND ($prod_type_id)!=""){
				$sql_select_products .= " AND P.PROD_TYPE_ID = '$prod_type_id'";
			} else {
				if(($customer_id) AND ($customer_id)!=""){
					$active_prod_types = $this->getCustNames($customer_id);
					$in_prod_types = $active_prod_types[0]->allow_prod_types;
					if($active_prod_types[0]->allow_prod_types == ""){
						$sql_select_products .= " AND P.PROD_TYPE_ID = 0";
					} else {
						$sql_select_products .= " AND P.PROD_TYPE_ID in ($in_prod_types)";
					}
				} else {
					if(isset($_COOKIE['user_type']) != 956314127503977533){
						$sql_select_products .= " AND P.PROD_TYPE_ID in ('".$_COOKIE['user_prod_types']."')";
					}
				}
			}
		} else {
			$sql_select_products = "SELECT PC.*, PC.PROD_COMM AS PROD_DISCOUNT, P.PROD_NAME PT.PROD_TYPE_NAME, PR.N_PROVIDER, PR.NP_SHORT FROM PROD_COMM PC LEFT JOIN PRODUCTS P ON (P.PROD_ID = PC.PROD_ID)
			LEFT JOIN 
				PRODUCT_TYPE PT ON (PT.PROD_TYPE_ID = P.PROD_TYPE_ID)
			INNER JOIN PROVIDER PR ON (P.PROD_PROVIDER = PR.NP_ID)
			WHERE PC.ACCOUNT = '".$_COOKIE['user_prod_type']."' AND PC.COMM_STATUS = 1 AND P.PROD_STATUS = 1";
				
			if(($prod_type_id) AND ($prod_type_id)!=""){
				$sql_select_products .= "AND P.PROD_TYPE_ID = $prod_type_id";
			} else {
				if(isset($_COOKIE['user_type']) != 956314127503977533){
					$sql_select_products .= "AND P.PROD_TYPE_ID in(".$_COOKIE['user_prod_types'].")";
				}
			}
			if(($list) AND ($list)!=""){
				$sql_select_products .= "AND PC.PROD_ID NOT IN ($list)";
			}
		}
		$sql_select_products .= " ORDER BY P.PROD_NAME";
		$query = $this->db->query($sql_select_products);
		$result = $query->result();
		return $result;
	}
	public function getprod_types($prod_url_id='',$status='',$alltypes='',$customer_id='') 
    {
        $sql_select_products =" SELECT * FROM PRODUCT_TYPE 	WHERE 1=1";
		if(isset($prod_url_id) AND ($prod_url_id!=='')){ 
            if(!isset($alltypes)){
                $sql_select_products .=" AND PROD_TYPE_ID = '$prod_url_id'";
            }
        }
        if(isset($status) AND ($status!=='')){
            $sql_select_products .=" AND PROD_TYPE_STATUS = '1'";
            if(isset($customer_id) AND ($customer_id!=="")){
				$active_prod_types = $this->getCustNames($customer_id);
				if($active_prod_types[0]->allow_prod_types == ""){
                    $sql_select_products .=" AND PROD_TYPE_ID = 0";
                } else {
					$active_products = $active_prod_types[0]->allow_prod_types;
                    $sql_select_products .=" AND PROD_TYPE_ID in ($active_products)";
                }
            } else {
                if(isset($_COOKIE["user_type"]) AND ($_COOKIE["user_type"] !== "956314127503977533") AND ($_COOKIE["user_type"] !== "525874964125375325")) {
                    $sql_select_products .=" AND PROD_TYPE_ID in ('".$_COOKIE["user_prod_types"]."')";
                }
            }
        }
        $query = $this->db->query($sql_select_products);
        $getProductstypes = $query->result();
        
        return $getProductstypes;	
    }

	public function getCustNames($id)
	{
		$sql_select_customers = "SELECT * FROM customers WHERE CUSTOMER_ENC IN ($id)";
		$query = $this->db->query($sql_select_customers);
		$result = $query->result();
		return $result;
	}
	public function UpdateMann_all_prod($form, $key, $val)
	{
		if(($val == 1) OR ($val == 0)){
			$search_val = $form->PRODUCT_TYPE;
			if($_COOKIE['user_type'] == "956314127503977533"){
				$getProducts = $this->listOfProductsToUpdate($key, $search_val);
			}else {
				$getProducts= $this->listOfProductsToUpdateROW($key, $search_val,1);
			}
			$sql_update_prod_comm = " UPDATE PROD_COMM SET MAN_STATUS = '$val' WHERE 
			PROD_ID in ('$getProducts->PROD_ID') AND (PARENT_ACCOUNT_ID = '".$_COOKIE['user_accout_id']."' AND ACCOUNT =  '$key')";
			$query = $this->db->query($sql_update_prod_comm);
			
			$this->insertLogTable("UPDATE","Updated COMMISSION status to manualy for product id in $PRODUCTID");
		}
		return true;
	}
	
	public function UpdateCommall_prod($form, $acc_enc)
	{
		$query = "";
		if($_COOKIE['user_type'] == "956314127503977533"){
			$getProducts= $this->listOfProductsToUpdate($acc_enc,"");
		} else {
			$getProducts= $this->listOfProductsToUpdateROW($acc_enc,"",1);
		}
		$sql_select_prod_comm = "SELECT * FROM PROD_COMM WHERE ACCOUNT = '$acc_enc'";
		$query = $this->db->query($sql_select_prod_comm);
		$result = $query->result();
		/*<!---<cfoutput query="getProducts">--->
		<cfloop list="#productID#" delimiters="," index="PROD_ID">
			<cfif isdefined("Form.prod_status_#PROD_ID#")>
				<cfset status= 1>
			<cfelse>
				<cfset status= 0>
			</cfif>
			<cfquery name="check" dbtype="query">
				SELECT * FROM getAllComm WHERE PROD_ID = #PROD_ID#
			</cfquery>
			<cfif check.RecordCount GT 0>
				<!---update prod_comm--->
				<cfif Evaluate('form.orig_commision_#PROD_ID#') eq ''>
					<cfset orig_commision =0>
				<cfelse>
					<cfset orig_commision = Evaluate('form.orig_commision_#PROD_ID#') >
				</cfif>
				<cfif orig_commision GTE Evaluate('Form.commision_#PROD_ID#') >
					<cfstoredproc procedure="UpdatePD_main" dataSource ="#request.db_dsn#">
						<cfprocparam cfsqltype="cf_sql_integer" value="#PROD_ID#">
						<cfprocparam cfsqltype="cf_sql_varchar" value="#cookie.user_account_id#">
						<cfprocparam cfsqltype="cf_sql_varchar" value="#form.acc_enc#">
						<cfprocparam cfsqltype="cf_sql_bit" value="#status#">
						<cfprocparam cfsqltype="cf_sql_double" value="#Evaluate('form.commision_#PROD_ID#')#">
					</cfstoredproc>
					
				</cfif>
			</cfif>
		</cfloop>*/
		$this->insertLogTable("UPDATE","Updated commissions of all the products for customer = $acc_enc");
		if($query !== ""){
			return true;
		} else {
			return false;
		}
	}

	//////////////GET COUNTRY WITH CODES////////////////
	public function getCountryCodes()
	{
		$sql_select_country_list = "SELECT * FROM COUNTRY_LIST WHERE CTY_STATUS = '1' ORDER BY CTY_NAME";
		$query = $this->db->query($sql_select_country_list);
		$getCountryCode = $query->result();
		return $getCountryCode;
	}

	public function getSecurityQuestion()
	{
		$sql_select_security_question = "SELECT * FROM SECURITY_QUESTION ORDER BY SEC_Q_ID";
		$query = $this->db->query($sql_select_security_question);
		$get_security_question = $query->result();
		return $get_security_question;
	}

	public function addUpdateComm($form){
		$prod_id_val = $form["prod_id_val"];
		$prod_type = $form["prod_type"];
		$acc_enc = $form["acc_enc"];

		//<cfset form = structcopy(arguments)>
		
		//$this->customDecryptFunction($acc_enc);
		
		if(($_COOKIE['user_type'] !== "956314127503977533")  AND ($prod_type !== "25") AND ($prod_type !== "20") OR ($_COOKIE['user_type'] == "956314127503977533")) {
			// <cfstoredproc procedure="UpdatePD_main" dataSource ="#request.db_dsn#">
			// 	<cfprocparam cfsqltype="cf_sql_integer" value="#form.prod_id_val#">
			// 	<cfprocparam cfsqltype="cf_sql_varchar" value="#cookie.user_account_id#">
			// 	<cfprocparam cfsqltype="cf_sql_varchar" value="#form.acc_enc#">
			// 	<cfprocparam cfsqltype="cf_sql_bit" value="#form.status_val#">
			// 	<cfprocparam cfsqltype="cf_sql_double" value="#form.commision_val#">
			// </cfstoredproc>
			
			$this->insertLogTable("UPDATE","Updated PROD_COMM table having PROD_ID = $prod_id_val");
			
		}
		if(isset($query)){
			return true;
		} else {
			return false;
		}
		
	}

	public function store_template($acc_enc){
		$sql_update_customers = "UPDATE customers SET  STORE_TEMPLATE='$acc_enc' WHERE CUSTOMER_ENC ='".$_COOKIE['user_account_id']."'";
		$query = $this->db->query($sql_update_customers);
		
		$this->insertLogTable("Update","Selected store template of STORE = $acc_enc");
		return true;
	}

	public function apply_store_template($acc_enc,$tempID){
		
		if($tempID !== "" AND $tempID !== 0){
			/*<cfstoredproc datasource="#request.db_dsn#" procedure="ApplyStoreTemplate">
				<cfprocparam value="#COOKIE.USER_ACCOUNT_ID#" cfsqltype="cf_sql_integer">
				<cfprocparam value="#arguments.acc_enc#" cfsqltype="cf_sql_integer">
				<cfprocparam value="#arguments.tempID#" cfsqltype="cf_sql_integer">
			</cfstoredproc>*/
			
			
			$this->insertLogTable("Update","Appied store template from store = $tempID to STORE = $acc_enc");
		}
			
		return true;		
	}
}