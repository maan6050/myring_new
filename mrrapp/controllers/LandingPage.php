<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class LandingPage extends MY_Controller
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
		$this->load->library('form_validation');
    }
    public function landingPage(){
        redirect(base_url('home'));
    }
}