<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */
class Product extends MY_Controller
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
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
		$this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->helper('date');
		$this->load->model('products');
        $this->load->model('providers');
        $this->load->model('customers');
    }
    //////////////////////////VIEW PRODUCTS/////////////////////////
    public function productList(){
        if(!isset($_COOKIE["user_type"]) OR (isset($_COOKIE["user_type"]) AND  ($_COOKIE["user_type"] !== "956314127503977533")) AND ($_COOKIE["user_type"] !== "525874964125375325")) {
            redirect(base_url('home'));
        }

        $data['search_by_type'] = "";
        if(isset($_GET["filter"]) AND ($_GET["filter"] !== "")){
            $data['search_by_type'] = $_GET["filter"];
        }
        if(isset($_GET["uploadImage"])){
            $data['msg'] = 'Uploaded successfully.';
        }
        if(isset($_GET["saveDetails"])){
            $data['msg'] = 'Details saved successfully.';
        }
        $data['title'] = "Product List";
        $this->load->view('header', $data);
        $data['getprod_types'] = $this->products->getprod_types($prod_url_id='',$status="1");
        $data['productsList'] = $this->products->getProducts($prod_type=$data['search_by_type'],$status=1);
		$this->load->view('product/viewProducts', $data);
		$this->load->view('footer');
    }

    ////////////////////////UPLOAD IMAGES//////////////
    public function uploadImages(){
        $data['search_by_type'] = "";
        if(isset($_POST["filter"]) AND ($_POST["filter"] !== "")){
            $data['search_by_type'] = $_POST["filter"];
        }
        $items = $this->input->post(NULL, TRUE);
        $upload = $this->products->uploadImage($items);

        if($upload == true){
            // $data['title'] = "Product List";
            // $this->load->view('header', $data);
            // $data['getprod_types'] = $this->products->getprod_types($prod_url_id='',$status="1");
            // $data['productsList'] = $this->products->getProducts($prod_type=$data['search_by_type'],$status=1);
            // $data['msg'] = 'Uploaded Successfully.';
            // $this->load->view('product/viewProducts', $data);
            // $this->load->view('footer');
            redirect(base_url('Product/productList?uploadImage&filter='.$_POST["filter"]));
        } else {
            $data['title'] = "Product List";
            $this->load->view('header', $data);
            $data['getprod_types'] = $this->products->getprod_types($prod_url_id='',$status="1");
            $data['productsList'] = $this->products->getProducts($prod_type=$data['search_by_type'],$status=1);
            $data['error']  = "There was some problem.Please try again.";
            $this->load->view('product/viewProducts', $data);
            $this->load->view('footer');	
        }
    }
    ////////////////////////SAVE DETAILS//////////////
    public function saveDetails(){
        if(isset($_POST["save_details"])){
            $data['search_by_type'] = "";
            if(isset($_GET["filter"]) AND ($_GET["filter"] !== "")){
                $data['search_by_type'] = $_GET["filter"];
            }
            $items = $this->input->post(NULL, TRUE);
            $saveDetails = $this->products->saveDetails($items);  
            if($saveDetails == true){
                // $data['title'] = "Product List";
                // $this->load->view('header', $data);
                // $data['getprod_types'] = $this->products->getprod_types($prod_url_id='',$status="1");
                // $data['productsList'] = $this->products->getProducts($prod_type=$data['search_by_type'],$status=1);
                // $data['msg'] = 'Uploaded Successfully.';
                // $this->load->view('product/viewProducts', $data);
                // $this->load->view('footer');
                redirect(base_url('Product/productList?saveDetails&filter='.$_POST["filter"]));
            } else {
                $data['title'] = "Product List";
                $this->load->view('header', $data);
                $data['getprod_types'] = $this->products->getprod_types($prod_url_id='',$status="1");
                $data['productsList'] = $this->products->getProducts($prod_type=$data['search_by_type'],$status=1);
                $data['error']  = "There was some problem.Please try again.";
                $this->load->view('product/viewProducts', $data);
                $this->load->view('footer');	
            }
        }
        
    }

    /////////////////////////PRODUCT INACTIVE LIST/////////////////////
    public function productInactiveList(){
        if(isset($_GET["res_msg"]) AND $_GET["res_msg"] !== ""){
            $data['msg'] ="Product ".$_GET["res_msg"]." Successfully";
        }
        $search_by_type = "";
        if(isset($_GET["filter"]) AND ($_GET["filter"] !== "")){
            $search_by_type = $_GET["filter"];
        }
        $data['title'] = "Inactive Product List";
        $this->load->view('header', $data);
        $data['search_by_type']= $search_by_type;
        $data['getprod_types']= $this->products->getprod_types($prod_url_id='',$status=1);
		$data['productsList'] = $this->products->getProducts($prod_type=$search_by_type,$status="0");
		$this->load->view('product/inactiveProducts', $data);
		$this->load->view('footer');
    }

    //////////////////////////ADD PRODUCT/////////////////////////
    public function addProduct(){
        if(isset($_GET["ID"])){
            $data["product"] = $this->products->getProducts(0,'',$ifStore='',$_GET["ID"]);
            $data['title'] = "Edit Product";
            $this->load->view('header', $data);
            $data['getprod_types']= $this->products->getprod_types($prod_url_id='',$status=1);
            $data['providers'] = $this->providers->getProviderList();
            $data['getCountryCodes'] = $this->customers->getCountryCodes();
            $this->load->view('product/addProduct', $data);
            $this->load->view('footer');
            
        } else {
            $data['title'] = "Add Product";
            $this->load->view('header', $data);
            $data['getprod_types']= $this->products->getprod_types($prod_url_id='',$status=1);
            $data['providers'] = $this->providers->getProviderList();
            $data['getCountryCodes'] = $this->customers->getCountryCodes();
            $this->load->view('product/addProduct', $data);
            $this->load->view('footer');
        }
    }

    //////////////////////////ADD PRODUCT FORM SUBMIT/////////////////////////
    public function productformSubmit(){
        if(isset($_POST["submit"])){
            $error = FALSE;
			$msg = "";
			$this->form_validation->set_rules('PRODUCT_NAME', 'Brand Name', 'required');
			$this->form_validation->set_rules('PROVIDER', 'Provider', 'required');
			$this->form_validation->set_rules('PRODUCT_TYPE', 'Product Type', 'required');
            $this->form_validation->set_rules('Prod_Code', 'Product Country', 'required');
            $this->form_validation->set_rules('operator_Code', 'Operator', 'required');
            $this->form_validation->set_rules('Prod_Fee', 'Product Fee', 'required');
            $items = $this->input->post(NULL, TRUE);
            if($this->form_validation->run() !== FALSE)
			{
                $row = $this->products->addproduct($items);
                if($row == true){
                    $msg = true;
                    $search_by_type = "";
                    if(isset($_GET["filter"]) AND ($_GET["filter"] !== "")){
                        $search_by_type = $_GET["filter"];
                    }
                    $data['title'] = "Product List";
                    $this->load->view('header', $data);
                    $data['search_by_type']= $search_by_type;
                    $data['getprod_types'] = $this->products->getprod_types($prod_url_id='',$status="1");
                    $data['productsList'] = $this->products->getProducts($prod_type=$search_by_type,$status=1);
                    if(isset($_POST["ID"]) AND $_POST["ID"] !== ""){
                        $data['msg'] = "Data Update Successfully";     
                    } else {
                        $data['msg'] = "Data Inserted Successfully";
                    }
                    $this->load->view('product/viewProducts', $data);
                    $this->load->view('footer');
                } else {
                    $error = TRUE;
                    $data['error'] = 'There was some problem while adding product.Please try again.'; 
                }        
            } else {
                $this->addProduct();
            }
            if($error == TRUE)
            {
                $search_by_type = "";
                if(isset($_GET["filter"]) AND ($_GET["filter"] !== "")){
                    $search_by_type = $_GET["filter"];
                }
                $data['title'] = "Product List";
                $this->load->view('header', $data);
                $data['search_by_type']= $search_by_type;
                $data['getprod_types']= $this->products->getprod_types($status=1);
                $data['productsList'] = $this->products->getProducts($prod_type=$search_by_type,$status=1);
                $this->load->view('product/viewProducts', $data);
                $this->load->view('footer');
            }
        }
    }
    
    public function addProductType(){
        if((isset($_GET["prod_typeid"])) AND ($_GET["prod_typeid"] !== "")) {
            $title = "Edit";
            $data['getprod_types_prodid'] = $this->products->getprod_types($_GET["prod_typeid"]);
        } else {
            $title = "Add";
        }
        $title .= " Product Type";
        $data['getprod_types']= $this->products->getprod_types();
        $data['title'] = $title;
        $this->load->view('header', $data);
        $this->load->view('product/addProductType', $data);
		$this->load->view('footer');
    }

    public function productTypeformSubmit(){
        if(isset($_POST["submit"])){
            $error = FALSE;
            $msg = "";
            $this->form_validation->set_rules('prod_type', 'Product Type Name', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            $items = $this->input->post(NULL, TRUE);
            if($this->form_validation->run() !== FALSE) {
                $addprod_type = $this->products->addUpdateProdTypes($items);
                if(!empty($addprod_type)) {
                    $data['msg'] = $addprod_type;
                    $data['getprod_types']= $this->products->getprod_types();
                    $data['title'] = "Add Product Type";
                    $this->load->view('header', $data);
                    $this->load->view('product/addProductType', $data);
                    $this->load->view('footer');
                }
            } 
        }
    }
    
    /////////////////////////PRODUCT PLAN/////////////////////
    public function productPlans(){
        $acc_type = $this->customers->getAccountType();
        if($acc_type > "4"){
            redirect(base_url('home'));
        }  
        if((isset($_GET["plan_id"])) AND ($_GET["plan_id"] !== "")) {
            $planID = $_GET["plan_id"];
            $title = "Edit";
            $data['single_product_plan'] = $this->products->getprod_plans($planID);
        } else {
            $planID = "0";
            $title = "Add";
        }
        $title .= " Product Plan";
        $data['product_plans'] = $this->products->getAllprodplans();
        $data['title'] = $title;
        $this->load->view('header', $data);
        $this->load->view('product/productPlans', $data);
		$this->load->view('footer');      
    }

    public function productPlanformSubmit(){
        if(isset($_POST["submit"])){
            $error = FALSE;
            $msg = "";
            $this->form_validation->set_rules('prod_plan', 'Product Plan', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            $items = $this->input->post(NULL, TRUE);
            if($this->form_validation->run() !== FALSE) {
                $addUpdateprod_plans = $this->products->addUpdateprod_plans($items);
                if(!empty($addUpdateprod_plans)) {
                    $data['msg'] = $addUpdateprod_plans;
                    $data['product_plans']= $this->products->getAllprodplans();
                    $data['title'] = "Add Product Plan";
                    $this->load->view('header', $data);
                    $this->load->view('product/productPlans', $data);
                    $this->load->view('footer');
                }
            } else {
            
            }
        }
    }

    public function sellingProduct(){
        if(((isset($_GET["PROD_ID"])) AND ($_GET["PROD_ID"]) !== "") AND (isset($_GET["tax"])) AND ($_GET["tax"]) !== "") {
            $prod_tax = $this->products->prodtax($_GET["PROD_ID"],$_GET["tax"]);
            if($prod_tax == true){
                $data["msg"] = "Updated Successfully.";
            } else {
                $data["error"] = "There was some problem while submitting form. Please try again.";
            }
        }
        $data["search_by_type"] = "";
        $data["search_val"] = "0";
        if(isset($_GET["filter"])){
            $data["search_by_type"] = $_GET["filter"];
            if($_GET["filter"] == ""){
                $data["search_val"] = "0";
            } else if($_GET["filter"] == "0") {
                $data["search_val"] = "";
            } else {
                $data["search_val"] = $_GET["filter"];
            }
        }
        $data["acc_type"] = $this->customers->getAccountType();
        $data["getProducts"] = $this->products->getProductsForSelling($data["search_val"]);
        $data["getprod_types"] = $this->products->getprod_types($prod_url_id='',$status=1,$customer_id='');
        $data['title'] = "Products Commission Status / Update";
        $this->load->view('header', $data);
        $this->load->view('product/sellingProduct', $data);
        $this->load->view('footer');
    }

    public function updateDiscount(){
        $updateDiscountFunc = $this->products->updateDiscount($_GET["discount"],$_GET["fav_prod"],$_GET["id"]);
        if($updateDiscountFunc==true){
            $data['search_by_type'] = "";
            if(isset($_GET["filter"]) AND ($_GET["filter"] !== "")){
                $data['search_by_type'] = $_GET["filter"];
            }
            $data['title'] = "Product List";
            $data["msg"] = "Updated Successfully.";
            $this->load->view('header', $data);
            $data['getprod_types'] = $this->products->getprod_types($prod_url_id='',$status="1");
            $data['productsList'] = $this->products->getProducts($prod_type=$data['search_by_type'],$status=1);
            $this->load->view('product/viewProducts', $data);
            $this->load->view('footer');
        } 
    }

    public function updateSWA(){
        $updateSWA = $this->products->updateSWA($_GET["status"],$_GET["val"],$_GET["id"]);
        if($updateSWA == true){
            $data['search_by_type'] = "";
            if(isset($_GET["filter"]) AND ($_GET["filter"] !== "")){
                $data['search_by_type'] = $_GET["filter"];
            }
            $data['title'] = "Product List";
            $data["msg"] = "Updated Successfully.";
            $this->load->view('header', $data);
            $data['getprod_types'] = $this->products->getprod_types($prod_url_id='',$status="1");
            $data['productsList'] = $this->products->getProducts($prod_type=$data['search_by_type'],$status=1);
            $this->load->view('product/viewProducts', $data);
            $this->load->view('footer');
        } else {
            
        }
    }
}