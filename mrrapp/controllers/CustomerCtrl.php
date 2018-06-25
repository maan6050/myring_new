<?php
/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class CustomerCtrl extends MY_Controller
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
    //////////////////////Home Functions////////////////////////
    public function masters()
    {
        $data['title'] = lang('masters');
		$this->load->view('header', $data);
		$this->load->view('home/masters', $data);
		$this->load->view('footer');
    }
    public function users()
    {
        $data['title'] = lang('users');
		$this->load->view('header', $data);
		$this->load->view('home/users', $data);
		$this->load->view('footer');
    }
    public function profile()
    {
        $data['title'] = lang('profile');
		$this->load->view('header', $data);
		$this->load->view('home/profile', $data);
		$this->load->view('footer');
    }
    public function viewLevels()
    {
        $data['title'] = lang('view_levels');
		$this->load->view('header', $data);
		$this->load->view('home/viewLevels', $data);
		$this->load->view('footer');
    }
    //////////////////////Providers Functions////////////////////////
    public function viewProviders()
    {
        $data['title'] = lang('view_providers');
		$this->load->view('header', $data);
		$this->load->view('providers/viewProviders', $data);
		$this->load->view('footer');
    }
    public function viewProducts()
    {
        $data['title'] = lang('view_products');
		$this->load->view('header', $data);
		$this->load->view('providers/viewProducts', $data);
		$this->load->view('footer');
    }
    public function productPlans()
    {
        $data['title'] = lang('product_plans');
		$this->load->view('header', $data);
		$this->load->view('providers/productPlans', $data);
		$this->load->view('footer');
    }
    //////////////////////Records Functions////////////////////////
    public function transactionLogs()
    {
        $data['title'] = lang('transaction_logs');
		$this->load->view('header', $data);
		$this->load->view('records/transactionLogs', $data);
		$this->load->view('footer');
    }
    public function transactionHistory()
    {
        $data['title'] = lang('transaction_history');
		$this->load->view('header', $data);
		$this->load->view('records/transactionHistory', $data);
		$this->load->view('footer');
    }
    //////////////////////Reports Functions////////////////////////
    public function salesByProductType()
	{
		$data['title'] = lang('sales_by_product_type');
		$this->load->view('header', $data);
		$this->load->view('reports/salesByProductType', $data);
		$this->load->view('footer');
	}
	public function salesByProduct()
	{
		$data['title'] = lang('sales_by_product');
		$this->load->view('header', $data);
		$this->load->view('reports/salesByProduct', $data);
		$this->load->view('footer');
	}
	public function salesByMasters()
	{
		$data['title'] = lang('sales_by_masters');
		$this->load->view('header', $data);
		$this->load->view('reports/salesByMasters', $data);
		$this->load->view('footer');
	}
	public function salesByProvider()
	{
		$data['title'] = lang('sales_by_provider');
		$this->load->view('header', $data);
		$this->load->view('reports/salesByProvider', $data);
		$this->load->view('footer');
	}
	public function salesByEndUsers()
	{
		$data['title'] = lang('sales_by_end_users');
		$this->load->view('header', $data);
		$this->load->view('reports/salesByEndUsers', $data);
		$this->load->view('footer');
	}
	public function unsuccessTransactions()
	{
		$data['title'] = lang('unsuccess_transactions');
		$this->load->view('header', $data);
		$this->load->view('reports/unsuccessTransactions', $data);
		$this->load->view('footer');
	}
	public function customerReport()
	{
		$data['title'] = lang('customer_report');
		$this->load->view('header', $data);
		$this->load->view('reports/customerReport', $data);
		$this->load->view('footer');
	}
	public function paymentReport()
	{
		$data['title'] = lang('payment_report');
		$this->load->view('header', $data);
		$this->load->view('reports/paymentReport', $data);
		$this->load->view('footer');
    }
    //////////////////////Web Content Functions////////////////////////
    public function storeDailyPromos()
    {
        $data['title'] = lang('store_daily_promos');
		$this->load->view('header', $data);
		$this->load->view('webContent/storeDailyPromos', $data);
		$this->load->view('footer');
	}
}
?>