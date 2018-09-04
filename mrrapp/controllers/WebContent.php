<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class WebContent extends MY_Controller
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
		$this->load->model('WebContents');
		$this->load->library('form_validation');
    }
    public function storeAdvertisment(){
        if(isset($_COOKIE['user_account_id']) AND  ($_COOKIE['user_account_id'] !== "")) {
            $customerID = $_COOKIE['user_account_id'];
        } else {
            $customerID = "0"; 
        }
        
        $pageName = $this->uri->segment("2"); 
        $data["admin"] = "0";
        $data["msg"] = "";
        if(isset($_COOKIE['user_type']) AND  (($_COOKIE['user_type'] == "956314127503977533") OR ($_COOKIE['user_type'] == "525874964125375325"))) {
            if(isset($_GET["delete"]) AND $_GET["delete"] == "1") {
                $delete_section = $this->WebContents->delete_section($pageName,$_GET["sectionid"]);
                if($delete_section == "deleted"){
                    $data["msg"] = "Promo successfully deleted.";
                }
            }
            $data["admin"] = "1";
        }
        if($data["admin"] == "1"){	
            $data["getpagedata"] = $this->WebContents->getPageContent($customerID,$pageName);
        } else {
            $data["getpagedata"] = $this->WebContents->getPageContent("0",$pageName);
        }
        $data["newSectionId"] = $this->WebContents->getLatestSectionID($customerID,$pageName);
        $data["pageName"] = $pageName;
        $this->load->view('header', $data);
		$this->load->view('webContent/promo', $data);
		$this->load->view('footer');
    }
    public function addPageContent(){
        if(isset($_GET["sectionid"])){
            $sectionid = $_GET["sectionid"];
        } else {
            $sectionid = '0';
        }
        if(isset($_GET["page"])){
            $page = $_GET["page"];
        } else {
            $page = '';
        }
        if(isset($_COOKIE['user_account_id']) AND  ($_COOKIE['user_account_id'] !== "")) {
            $customerID = $_COOKIE['user_account_id'];
        } else {
            redirect(base_url('login'));
        }
        if(isset($_GET["sub"]) AND $_GET["sub"] !== ""){
            $pageCheck = $_GET["sub"];
        } else {
            $pageCheck = $page;
        }
        if(isset($_GET["check"]) AND $_GET["check"] !== ""){
            $subSec = $_GET["check"];
        } else {
            $subSec = "";
        }
        $data["getpagedata"] = $this->webContents->getPageContent($customerID,$pageCheck,$sectionid);
        if($data["getpagedata"] > "0"){
            $data["title"] = "Update Page Content";
            $formtype = "Edit";
        } else {
            $data["title"] = "Add Page Content";
            $formtype = "Add";
        }
        if(isset($_GET["page"]) AND ($_GET["page"] == "about") AND (!isset($_GET["sub"]))){
            $data["getpagetitle"] = $this->webContents->getPageContent($customerID,$page);
        }
        $data["storecount"] = $this->WebContents->getPageContent($customerID,$page);
        $this->load->view('header', $data);
		$this->load->view('webContent/addPageContent', $data);
		$this->load->view('footer');
    }

    public function PageContentFormSubmit(){
        /*if(isset($_POST["pageName"]) AND (isset($_POST["AddForm"])) AND ($_POST["customer_id"] !== "")){
            if(isset($_POST["pageImage"]) AND len($_POST["pageImage"])){
                $config = array(
                'upload_path' => base_url("images/pageContent/"),
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite' => TRUE,
                'max_size' => "2048000",
                'max_height' => "768",
                'max_width' => "1024"
                );
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                if($this->upload->do_upload("pageImage")) {
                    $success = array('upload_data' => $this->upload->data());
                } else {
                    $error = array('error' => $this->upload->display_errors());
                }
                if(($_FILES['pageImage']['name'] !=="")){
                    $ImageName = $_FILES['pageImage']['name'];
                } else {
                    $ImageName = $items["pageold_Image"];
                }		
            } else {
                $ImageName = "" ;		
            }
       
            $sql_insert_pageContent = " INSERT INTO pageContent(page,section_id,Customer_id,language,isactive,includes,picture_big,picture_small,Title,Description,line_2";
            if(isset($_POST["checkSub"]) AND ($_POST["checkSub"] !== "")) {
                $sql_insert_pageContent .= " ,check_section";
             }
            if(isset($_GET["page"]) AND ($_GET["page"] == "storeAdvertisment")){
                $sql_insert_pageContent .= " ,sort_order";
            }
            $sql_insert_pageContent .= " ) values(";
            if(isset($_POST["sub"]) AND ($_POST["sub"] !== "")) {
                $sql_insert_pageContent .= " <cfqueryparam value=#form.sub# cfsqltype=cf_sql_varchar>,";
            } else {
                $sql_insert_pageContent .= " <cfqueryparam value=#form.pageName# cfsqltype=cf_sql_varchar>,";
            }        
            $sql_insert_pageContent .= ' <cfqueryparam value=#form.sectionid# cfsqltype=cf_sql_integer">,
            <cfqueryparam value="#form.customer_id#" cfsqltype="cf_sql_numeric">,
            <cfqueryparam value="#form.language#" cfsqltype="cf_sql_numeric">,
            <cfqueryparam value="#form.displayContent#" cfsqltype="cf_sql_numeric">,
            '',
            <cfqueryparam value="#ImageName#" cfsqltype="cf_sql_varchar">,
            '',
            <cfqueryparam value="#form.pageTitle#" cfsqltype="cf_sql_varchar">,
            <cfqueryparam value="#form.description#" cfsqltype="cf_sql_longvarchar">,
            <cfqueryparam value="#form.description2#" cfsqltype="cf_sql_longvarchar">';
            if(isset($_POST["checkSub"])  AND ($_POST["checkSub"] !== "")) {
                $sql_insert_pageContent .= " , <cfqueryparam value='#form.checkSub#' cfsqltype="cf_sql_varchar">";
            }
            if(isset($_GET["page"]) AND ($_GET["page"] == "storeAdvertisment")){
                $set storecount = $this->WebContents->getPageContent($_POST["customer_id"],$_GET["page"]);
                $sql_insert_pageContent .= " ,#storecount.recordcount + 1#";
            }
            $sql_insert_pageContent .= " )";
            $query = $this->db->query($sql_insert_pageContent);
            
            if(isset($_POST["pageName"]) AND  ($_POST["pageName"] == "about") AND  ($_POST["pageTitle"] !== "") AND (!isset($_POST["sub"]))){
                $sql_update_pageContent = "UPDATE pageContent SET Title  =  <cfqueryparam value="#form.pageTitle#" cfsqltype="cf_sql_varchar"> where page = <cfqueryparam value="#form.pageName#" cfsqltype="cf_sql_varchar"> and Customer_id = <cfqueryparam value="#form.customer_id#" cfsqltype="cf_sql_numeric">";
                $query = $this->db->query($sql_update_pageContent);
            }
            if(isset($_POST["checkSub"]) AND  ($_POST["checkSub"] == "TeamBio") {
                $sql_update_pageContent = "UPDATE pageContent SET Description  =  <cfqueryparam value="#form.description#" cfsqltype="cf_sql_longvarchar"> where page = <cfqueryparam value="#form.pageName#" cfsqltype="cf_sql_varchar"> and Customer_id = <cfqueryparam value="#form.customer_id#" cfsqltype="cf_sql_numeric"> and check_section = <cfqueryparam value="#form.checkSub#" cfsqltype="cf_sql_varchar">";
                $query = $this->db->query($sql_update_pageContent);
            }
        } else if(isset($_POST["pageName"]) AND (isset($_POST["EditForm"])) AND ($_POST["customer_id"] !== "")){
	        if(isset($_POST["pageImage"]) AND len($_POST["pageImage"])){
                $config = array(
                'upload_path' => base_url("images/pageContent/"),
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite' => TRUE,
                'max_size' => "2048000",
                'max_height' => "768",
                'max_width' => "1024"
                );
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                if($this->upload->do_upload("pageImage")) {
                    $success = array('upload_data' => $this->upload->data());
                } else {
                    $error = array('error' => $this->upload->display_errors());
                }
                if(($_FILES['pageImage']['name'] !=="")){
                    $ImageName = $_FILES['pageImage']['name'];
                }		
            } else {
                if(isset($_POST["pageold_Image"]) AND ($_POST["pageold_Image"] !== "")){
                    $ImageName = "#form.pageold_Image#";		
                } else {
                    $ImageName = "";
                }
            }
            $sql_update_pageContent = "UPDATE pageContent SET page ="; 
            if(isset($_POST["sub"]) AND ($_POST["sub"] !== "")) {
                $sql_update_pageContent .=" <cfqueryparam value="#form.sub#" cfsqltype="cf_sql_varchar">,";
            } else {
                $sql_update_pageContent .=" <cfqueryparam value="#form.pageName#" cfsqltype="cf_sql_varchar">,";
            }
            $sql_update_pageContent .=" Customer_id = <cfqueryparam value="#form.customer_id#" cfsqltype="cf_sql_numeric">,language = <cfqueryparam value="#form.language#" cfsqltype="cf_sql_numeric">,
            isactive = <cfqueryparam value="#form.displayContent#" cfsqltype="cf_sql_numeric">,
            includes = '',
            picture_big = <cfqueryparam value="#ImageName#" cfsqltype="cf_sql_varchar">,
            picture_small =  <cfqueryparam value="#ImageName#" cfsqltype="cf_sql_varchar">,
            Title=<cfqueryparam value="#form.pageTitle#" cfsqltype="cf_sql_varchar">,
            Description=<cfqueryparam value="#form.description#" cfsqltype="cf_sql_longvarchar">,
            line_2=<cfqueryparam value="#form.description2#" cfsqltype="cf_sql_longvarchar">";
            if(isset($_POST["checkSub"])  AND ($_POST["checkSub"] !== "")) {
                $sql_update_pageContent .=" ,check_section = <cfqueryparam value="#form.checkSub#" cfsqltype="cf_sql_varchar">";
            }
            if(isset($_POST["sort_order"])  AND ($_POST["sort_order"] !== "")) {
                $sql_update_pageContent .=" ,sort_order = <cfqueryparam value="#form.sort_order#" cfsqltype="cf_sql_varchar">";
            }
            $sql_update_pageContent .=" WHERE page ="; 
            if(isset($_POST["sub"]) AND ($_POST["sub"] !== "")) {
                $sql_update_pageContent .=" <cfqueryparam value="#form.sub#" cfsqltype="cf_sql_varchar">";
            } else {
                $sql_update_pageContent .=" <cfqueryparam value="#form.pageName#" cfsqltype="cf_sql_varchar">";
            }
            $sql_update_pageContent .=" and Customer_id = <cfqueryparam value="#form.customer_id#" cfsqltype="cf_sql_numeric" and section_id = <cfqueryparam value="#form.sectionid#" cfsqltype="cf_sql_integer">";
            $query = $this->db->query($sql_update_pageContent);
            
            if(isset($_POST["pageName"]) AND  ($_POST["pageName"] == "about") AND  ($_POST["pageTitle"] !== "") AND (!isset($_POST["sub"]))){
                $sql_update_pageContent  = "UPDATE pageContent SET Title  =  <cfqueryparam value="#form.pageTitle#" cfsqltype="cf_sql_varchar"> where page = <cfqueryparam value="#form.pageName#" cfsqltype="cf_sql_varchar"> and Customer_id = <cfqueryparam value="#form.customer_id#" cfsqltype="cf_sql_numeric">";
                $query = $this->db->query($sql_update_pageContent);
            }
            if(isset($_POST["checkSub"]) AND  ($_POST["checkSub"] == "TeamBio") {
                $sql_update_pageContent  = " UPDATE pageContent SET Description  =  <cfqueryparam where 
                page = <cfqueryparam value="#form.pageName#" cfsqltype="cf_sql_varchar"> and Customer_id = <cfqueryparam value="#form.customer_id#" cfsqltype="cf_sql_numeric"> and check_section = <cfqueryparam value="#form.checkSub#" cfsqltype="cf_sql_varchar">";
                $query = $this->db->query($sql_update_pageContent);
            }
        }*/
    }
}
