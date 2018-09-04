<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class Providers extends CI_Model
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

    //////////////////////////////////GET PROVIDERS DATA//////////////////////////////// 
    public function getProviderList($NP_ID='')
    {
        $sql_select_provider =" SELECT * FROM PROVIDER"; 
        if(isset($NP_ID) AND ($NP_ID !== "")){
            $sql_select_provider .=" WHERE NP_ID = '$NP_ID'"; 
        } 
        $sql_select_provider .=" ORDER BY NP_ID";
        $query = $this->db->query($sql_select_provider);
        if($query->num_rows() > 0){
            $provider = $query->result();
            //echo "<pre>";
            //print_r($provider);
            //die();
            return $provider;
        }
    }

    //////////////////////////////////CHECK EMAIL EXIST CUSTOMERS FORM////////////////////////////////
    public function checkEmailExist($items)
    {
		$this->db->select('PROVIDER.NP_EMAIL');
        $query = $this->db->get_where('PROVIDER', array('PROVIDER.NP_EMAIL' => $items["NP_EMAIL"])); 
        $rows = $query->result();
		return $rows;
    }
    
    //////////////////////////////////ADD PROVIDER////////////////////////////////
    public function addProvider($items,$plan='')
    {
        $ok = "";
		$sql_insert_provider = "INSERT INTO PROVIDER (N_PROVIDER,N_STATUS,NP_CONTACT,NP_ADDRESS,NP_CITY,NP_STATE,NP_ZIP,NP_PHONE,NP_SHORT,NP_EMAIL, NP_COUNTRY) VALUES ('".$items['N_PROVIDER']."','".$items['N_STATUS']."','".$items['NP_CONTACT']."','".$items['NP_ADDRESS']."','".$items['NP_CITY']."','".$items['NP_STATE']."','".$items['NP_ZIP']."','".$items['NP_PHONE']."','".$items['NP_SHORT']."','".$items['NP_EMAIL']."','".$items['NP_COUNTRY']."')";
        $query = $this->db->query($sql_insert_provider);
        
		$sql_insert_log_table = "INSERT INTO LOG_TABLE (ACCOUNT_ID, ACTIVITY, IP, DESCRIPTION, USERNAME)
		VALUES('".$_COOKIE["user_account_id"]."','INSERT','".$_COOKIE["user_ip"]."','Added new Provider','".$_COOKIE["user_name"]."')";
        $query = $this->db->query($sql_insert_log_table);	
        $this->customers->insertLogTable("INSERT","Added new Provider");
		if($query){
            return $ok = "Form Submit Successfully";
        } else {
            return $ok;
        }		
    }
    
    //////////////////////////////////UPDATE PROVIDER////////////////////////////////
    public function updateProvider($items,$plan='')
    {
        $ok = "";
		$sql_update_provider = "UPDATE PROVIDER SET N_PROVIDER='".$items['N_PROVIDER']."', N_STATUS='".$items['N_STATUS']."',NP_CONTACT='".$items['NP_CONTACT']."',NP_ADDRESS='".$items['NP_ADDRESS']."',NP_CITY='".$items['NP_CITY']."',NP_STATE='".$items['NP_STATE']."',NP_ZIP='".$items['NP_ZIP']."',NP_PHONE='".$items['NP_PHONE']."',NP_SHORT ='".$items['NP_SHORT']."',NP_EMAIL ='".$items['NP_EMAIL']."',
		NP_COUNTRY = '".$items['NP_COUNTRY']."' WHERE  NP_ID = '".$items['NP_ID']."'";
		$query = $this->db->query($sql_update_provider);	
        	
		$this->customers->insertLogTable("UPDATE","Updated Provider having id =".$items['NP_ID']);
		if($query){
            return $ok = "Form Update Successfully";
        } else {
            return $ok;
        }	
	}
}