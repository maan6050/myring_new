<?php
/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class WebContentCtrl extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();

		if (!isset($_SESSION['userId']) || !isset($_SESSION['userType']))
		{
			redirect(base_url('login'));
		}
		$this->lang->load('invoices_lang', $this->getLanguage());
    }
    public function storeDailyPromos()
    {
        $data['title'] = lang('store_daily_promos');
		$this->load->view('header', $data);
		$this->load->view('webContent/storeDailyPromos', $data);
		$this->load->view('footer');
	}
}
?>