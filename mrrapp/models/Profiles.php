<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class Profiles extends CI_Model
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
	}
	
	/**
	 * create
	 * Inserta el registro del cliente usando los datos recibidos del formulario y retorna el ID de dicho registro.
	 */
	public function editProfile($items,$cust_id){
		$ok = false;
		$sql_update_customers = " UPDATE customers SET first_name='".$items["f_name"]."',last_name = '".$items["l_name"]."',company ='".$items["f_company"]."',";
		if(isset($items["v_address1"])){
			$sql_update_customers .=" address1='".$items["v_address1"]."',";
		}	 
		if(isset($items["v_suite"])){
			$sql_update_customers .=" address2='".$items["v_suite"]."',"; 
		}
			if(isset($items["v_cty"])){
			$sql_update_customers .=" city='".$items["v_cty"]."',"; 
		}
			if(isset($items["v_state"])){
			$sql_update_customers .=" state_region='".$items["v_state"]."',"; 
		}
			if(isset($items["v_zip"])){
			$sql_update_customers .=" postal_code='".$items["v_zip"]."',";
		} 
			if(isset($items["v_country"])){	
			$sql_update_customers .=" country='".$items["v_country"]."',"; 
		}
		$sql_update_customers .=" local_phone='".$items["v_phone"]."',FAX ='".$items["v_fax"]."',
		e_mail='".$items["v_email"]."',user_2='".$items["v_question"]."',user_3='".$items["v_answer"]."', user_4='".$items["tax_id"]."',COUNTRY_CODE = ";
		if(isset($items['country_code'])){
			$sql_update_customers .=" '".$items["country_code"]."'";
		} else {
			$sql_update_customers .=" '1'";
		}
		$sql_update_customers .=" ,TOUCHSCREEN =";
		if(isset($items['TOUCHSCREEN'])){
			$sql_update_customers .=" '1'";
		} else {
			$items['TOUCHSCREEN'] = "0";
			$sql_update_customers .=" '0'";
		}
		$sql_update_customers .=" ,THERMAL_RECEIPT=";
		if(isset($items['THERMAL_RECEIPT'])){
			$sql_update_customers .=" '1'";
		} else {
			$items['THERMAL_RECEIPT'] = "0";
			$sql_update_customers .=" '0'";
		}
		if(isset($items['cc_enabled'])){
			$sql_update_customers .=" ,CC_ENABLED="; 
			if(isset($items["cc_enabled"]) == "1"){
				$sql_update_customers .=" '1'";
			} else {
				$sql_update_customers .=" '0'";
			}
			$sql_update_customers .=" ,CC_ENABLED_BY = '".$_COOKIE['user_account_id']."'";
		}
		$sql_update_customers .=" WHERE CUSTOMER_ENC = '".$items["cust_id"]."'";
		$query = $this->db->query($sql_update_customers);

		if(isset($items['cc_enabled'])){
			if($_COOKIE['user_type'] == "956314127503977533"){
				$sql_update_customers = "UPDATE customers SET CC_ENABLED = '".$items["cc_enabled"]."'
					,CC_ENABLED_BY = '".$_COOKIE['user_account_id']."'";
				$query = $this->db->query($sql_update_customers);
			} else {
				$updatePyamentModule =  $this->customers->updateCCPayment($items["cc_enabled"],$items["cust_id"]);
			}

		}
		$this->customers->insertLogTable("Update","Updated own Profile into CUSTOMERS Table");
		return $msg = "Form Update Successfully";		
	}
}
