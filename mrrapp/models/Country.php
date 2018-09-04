<?php
/**
 * Modelo para la realización de operaciones sobre las tablas de la BD relacionadas con los países.
 * Creado: Marzo 10, 2017
 * Modificaciones: CZapata
 */

class Country extends CI_Model
{
	/**
	 * __construct
	 * Método constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * deleteProducts
	 * Elimina la información de las tablas client_product y product asociada al país $id.
	 */
	public function deleteProducts($id)
	{
		$where = 'productId IN (SELECT id FROM product WHERE countryId="'.$id.'")';
		$this->db->where($where);
		$this->db->delete('client_product');
		return $this->db->delete('product', array('countryId' => $id));
	}

	/**
	 * getAll
	 * Obtiene los datos de todos los países en orden alfabético.
	 */
	public function getAll($status = NULL)
	{
		$this->db->select('*');
		$this->db->order_by('CTY_STATUS ASC, CTY_NAME ASC');
		if(!is_null($status))
		{
			$st=" CTY_STATUS='".$status."' AND ISO2_CODE !='' ";
  			$this->db->where($st, NULL, FALSE); 
		}
		$query = $this->db->get('country_list');
		$rows = $query->result();
		foreach($rows as &$row)
		{
			//$row->preferred = $row->preferred == 'y' ? 'Yes' : 'No';
			$row->status = $row->CTY_STATUS == '1' ? 'Active' : 'Inactive';
		}
		return $rows;
	}

	/**
	 * getById
	 * Obtiene los datos del país indicado por $id.
	 */
	public function getById($id)
	{
		$this->db->select('*');
		$query = $this->db->get_where('country', array('id' => $id));
		return $query->row();
	}

	/**
	 * update
	 * Actualiza el registro del país usando los datos recibidos del formulario.
	 */
	public function update($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update('country', $data);  // Devuelve TRUE en caso de éxito.
	}
}