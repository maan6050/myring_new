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
		$this->db->select('	customers.COMPANY,customers.COUNTRY_CODE,customers.COUNTRY,CL.CTY_SHORT, ACC.PARENT_ACCOUNT_ID,CL.CTY_NAME, U.ENABLED, customers.CUSTOMER_ENC, U.FIRST_NAME AS UFIRST_NAME, U.LAST_NAME AS ULAST_NAME, UG.USER_GROUP_ENC, ACC.ACCOUNT_ID, ACC.ACCOUNT_ENC, U.LOGIN_NAME, customers.ALLOW_PROD_TYPES,customers.THERMAL_RECEIPT,customers.TOUCHSCREEN,customers.CC_ENABLED,U.USER_TYPE,U.END_USER_VARIFY, UG.USER_GROUP_NAME');
		
		$this->db->join('accounts ACC', 'customers.CUSTOMER_ID = ACC.CUSTOMER_ID', 'INNER');
		$this->db->join('account_types AT', 'ACC.ACCOUNT_TYPE = AT.ACCOUNT_TYPE', 'INNER');
		$this->db->join('users U', 'customers.CUSTOMER_ENC = U.CUSTOMER_ID_ENC', 'INNER');
		$this->db->join('user_groups UG', 'U.USER_TYPE = UG.USER_GROUP_ID', 'INNER');
		$this->db->join('country_list CL', ' (customers.COUNTRY = CL.CTY_ID) ', 'LEFT');
		
		$query = $this->db->get_where('customers', array('U.LOGIN_NAME' => $username, 'U.LOGIN_PASSWORD' => $pass)); //, 'LOGIN_PASSWORD_NEW' => $pass
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

}
