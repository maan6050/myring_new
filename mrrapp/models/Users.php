<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class Users extends CI_Model
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
    public function getUsersList($userid='', $custid='')
    {
        $sql_select_customers = "SELECT  U.FIRST_NAME AS UserFirstName, U.LAST_NAME AS UserLastName, U.LOCAL_PHONE AS UserLocalPhone, U.ENABLED AS UserEnabled, U.*, C.*, AT.DESCRIPTION
        FROM  customers  C JOIN accounts A
        ON C.CUSTOMER_ID = A.CUSTOMER_ID
        JOIN users U ON C.CUSTOMER_ENC = U.CUSTOMER_ID_ENC
        JOIN account_types AT ON AT.ACCOUNT_TYPE = A.ACCOUNT_TYPE
        WHERE ";
        if(isset($searchStr)){
            if($searchStr == ""){
                $sql_select_customers .= " 1 = 0";
            } else {
                $sql_select_customers .= " U.LOGIN_NAME like '%$searchStr%' 
                or C.E_MAIL like '%$searchStr%' 
                or C.COMPANY like '%$searchStr%'
                or U.LOCAL_PHONE like '%$searchStr%'";
            }
        } else {
            if(($_COOKIE["user_type"] !== "956314127503977533") AND ($_COOKIE["user_type"] !== "525874964125375325")){
                if(($_COOKIE["user_type"] == "415285967837575867") OR ($_COOKIE["user_type"] == "258968745812378564") OR ($custid !== "")) {
                    if(isset($custid) AND $custid !== "") {
                        $sql_select_customers .= " U.LOGIN_NAME = '".$_COOKIE["user_name"]."' AND";
                    }
                    $sql_select_customers .= " U.CUSTOMER_ID_ENC";
                } else {
                    $sql_select_customers .= " A.PARENT_ACCOUNT_ID"; 
                }
                $sql_select_customers .= " = '".$_COOKIE["user_account_id"]."'";  
            } else {
                if(isset($custid) AND $custid !== "") {
                    $sql_select_customers .= " U.CUSTOMER_ID_ENC = '".$_COOKIE["user_account_id"]."' AND U.LOGIN_NAME = '".$_COOKIE["user_name"]."'";
                } else {
                    $sql_select_customers .= " 1 =1"; 
                }  
            }                   
            if(isset($userid) AND $userid !== ""){
                $sql_select_customers .= " AND U.USER_ID = '$userid'"; 
            } 
        }
        $sql_select_customers .= " ORDER BY C.COMPANY";
        $query = $this->db->query($sql_select_customers);
        $result = $query->result();
        return $result;
    }
    public function getUsersIps($userid='')
	{
        $sql_select_visitor_ip = "SELECT * FROM VISITOR_IP WHERE IP_UserID = '$userid'";
		$query = $this->db->query($sql_select_visitor_ip);
        $UsersIps = $query->result();
        return $UsersIps;
    }
    public function checkIfExistThere($key='',$value='',$userid='',$custid='')
	{
        
        $sql_select_users = "SElECT * FROM users where $key = '$value'";
        if((isset($_COOKIE["user_type"]) == "258968745812378564") OR (isset($custid)) AND ($custid == $_COOKIE["user_account_id"])) {
            $sql_select_users .= " AND LOGIN_NAME = '".$_COOKIE["user_name"]."'";
        }
        if(isset($userid) AND $userid !== "") {
            $sql_select_users .=" AND USER_ID != '$userid'";
        }
        $query = $this->db->query($sql_select_users);
        $recordcount = $query->num_rows();
		if($recordcount > 0){
			$ok = true ;
		} else {
			$ok = false ;
		}
		return $ok;
    }
    public function addUser($form)
	{   
        $ok = false;    
        $F_COMPANY = "";
        $form['role'] = "";
        if(isset($form['active'])){
            $active = $form['active'];
        } else {
            $active = "0"; 
        }
        if(isset($_COOKIE["user_type"]) AND ($_COOKIE["user_type"] == "956314127503977533") AND ($form['role'] == "8")) {
            $F_COMPANY = "898548569";
        }
        //$enc_pass = encrypt(	form.f_login, encryptionUserKey, "CFMX_COMPAT",	"hex" ) />
        //$set secPass = encrypt(	form.v_secure, encryptionUserKey, "CFMX_COMPAT",	"hex" ) />
        $enc_pass = $form['f_login'];
        $secPass = $form['v_secure'];
        $sql_insert_users ="INSERT INTO users
        (CUSTOMER_ID_ENC, ENABLED, USER_TYPE, FIRST_NAME, LAST_NAME, ADDRESS1, ADDRESS2, CITY, STATE_REGION, POSTAL_CODE, COUNTRY, LOCAL_PHONE, E_MAIL, LOGIN_NAME, LOGIN_PASSWORD, SEC_PIN, SECURITY_QUESTION, SECURITY_ANSWER, USER_IP,LOGIN_PASSWORD_NEW,SEC_PIN_NEW)
        VALUES ('$F_COMPANY'";
        if(isset($form["active"])){
            $sql_insert_users .="  ,'".$form['active']."'"; 
        } else {
            $sql_insert_users .=" ,'0'";
        }
        $sql_insert_users .=" ,'".$form['role']."','".$form['f_name']."','".$form['l_name']."','','','','','0','".$_COOKIE["country"]."', '".$form['v_phone']."','".$form['v_email']."','".$form['login_name']."','','0','".$form['v_question']."','".$form['v_question']."','".$form['ipAccess']."','$enc_pass','$secPass') ";
        $query = $this->db->query($sql_insert_users);
        $userID =  $this->db->insert_id();

        $userID = $userID["GeneratedKey"];
        if(isset($form['ipAccess']) AND ($form['ipAccess']) == 2){
            if(isset($form['ipaddresses'])){
                //<cfloop list="#form.ipaddresses#" index="ips" delimiters=",">
                    $sql_insert_visitor_ip ="INSERT INTO VISITOR_IP (IP_Address,IP_UserID) VALUES ('$ips', '$userId')";
                    $query = $this->db->query($sql_insert_visitor_ip);
                //</cfloop>
            }
        }
        if($query) {
            $ok = true;
        }
        return $ok;
    }
    public function updateUsers($form,$plan='',$agt_id=''){
        $ok = false;
        $sql_update_users = "UPDATE users SET ";
        if(isset($form['f_company'])){	
            $sql_update_users .= " CUSTOMER_ID_ENC='".$form['f_company']."',";
        }
        $sql_update_users .= " ENABLED=";
        if(isset($form['active'])){
            $sql_update_users .= "'".$form['active']."',"; 
        } else {
            $sql_update_users .= "'0',";
        }
        if(isset($form['role'])){
            $sql_update_users .= "USER_TYPE='".$form['role']."',"; 
        }
        $sql_update_users .= " FIRST_NAME='".$form['f_name']."', 
        LAST_NAME='".$form['l_name']."', 
        COUNTRY='".$_COOKIE["country"]."',
        LOCAL_PHONE='".$form['v_phone']."', 
        E_MAIL='".$form['v_email']."', 
        LOGIN_NAME='".$form['login_name']."', 
        SECURITY_QUESTION='".$form['v_question']."', 
        SECURITY_ANSWER='".$form['v_answer']."', 
        USER_IP='".$form['ipAccess']."' WHERE 
        USER_ID = '".$form['userid']."'";
        $sql_update_users;
        $query = $this->db->query($sql_update_users);    
        if(isset($form['ipAccess']) AND $form['ipAccess'] == "2") {
            if(isset($form['ipaddresses'])){
                $sql_update_users = " DELETE FROM VISITOR_IP WHERE IP_UserID = '".$form['userid']."'";
                /*<cfloop list="#form.ipaddresses#" index="ips">
                    $sql_insert_visitor_ip = "INSERT INTO VISITOR_IP (IP_Address,IP_UserID) VALUES
                    ('ips', '".$form['userid']."')";
                    $query = $this->db->query($sql_insert_visitor_ip);
                </cfloop>*/
            }
        }   
        if($query) {
            $ok = true;  
        }  	
        return $ok;	
    }
    
    public function getCompanies(){
        $sql_select_customers = "SELECT * FROM customers  C JOIN accounts A ON C.CUSTOMER_ID = A.CUSTOMER_ID"; 
	    if($_COOKIE["user_type"] == "415285967837575867"){
            $sql_select_customers .= " WHERE ACCOUNT_ENC = '".$_COOKIE["user_account_id"]."'";
        } else {    
            $sql_select_customers .= " WHERE PARENT_ACCOUNT_ID = '".$_COOKIE["user_account_id"]."'";
        }
        $sql_select_customers .= " ORDER BY COMPANY";
        $query = $this->db->query($sql_select_customers);
        $get_companies = $query->result();
        return $get_companies;
    }

    public function getRoles($roles){
        if($_COOKIE["user_type"] == "956314127503977533"){
			$role = "(2)";
        } else if($_COOKIE["user_type"] == "638545125236524578"){
			$role = "(2,3)";
        } else if($_COOKIE["user_type"] == "325210258618165451"){
			$role = "(3,4)";
        } else if($_COOKIE["user_type"] == "125458968545678354"){
			$role = "(4,5)";
        } else if($_COOKIE["user_type"] == "415285967837575867"){
			$role = "(5,6,7)";
        } else if($_COOKIE["user_type"] == "863252457813278645"){
			$role = "(7)";
        } else if($_COOKIE["user_type"] == "525874964125375325"){
			$role = "(8)"; 
        }
        if(isset($roles) AND ($roles == "all") AND ($_COOKIE["user_type"] == "956314127503977533") OR ($_COOKIE["user_type"] == "525874964125375325")){
       		$role = ""; 
        }
        $sql_select_account_types = "SELECT * FROM account_types"; 
		    if(isset($role) AND $role !== ""){
                $sql_select_account_types .= " WHERE ACCOUNT_TYPE IN $role";
            } else {
                $sql_select_account_types .=" WHERE ACCOUNT_TYPE != 1";
            }   
        $sql_select_account_types .= " ORDER BY DESCRIPTION";
        $query = $this->db->query($sql_select_account_types);
        $get_roles = $query->result();
        return $get_roles;
    }

    public function checkCurrentPINValid($pin){
        //$enc_pin = encrypt(	left(arguments.pin,4), encryptionUserKey, "CFMX_COMPAT",	"hex" ) />
        $sql_select_users = "SElECT * FROM users WHERE SEC_PIN_NEW = '$pin' AND LOGIN_NAME = '".$_COOKIE["user_name"]."'";
        $query = $this->db->query($sql_select_users);
		$recordcount = $query->num_rows();
        if($recordcount > "0") {
            $ok = true;
        } else {
            $ok = false;
        }
        return $ok;
    }
}