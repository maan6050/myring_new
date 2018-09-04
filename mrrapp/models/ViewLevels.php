<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class ViewLevels extends CI_Model
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
		$current_date = date("Y/m/d");
	}
	
	/**
	 * create
	 * Inserta el registro del cliente usando los datos recibidos del formulario y retorna el ID de dicho registro.
	 */
    //public function getUsersList($userid='', $custid='', $searchStr='')
    
}