<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class Products extends CI_Model
{
	/**
	 * __construct
	 * M�todo constructor.
	 */
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('cookie');
	}
	
	/**
	 * create
	 * Inserta el registro del cliente usando los datos recibidos del formulario y retorna el ID de dicho registro.
	 */

    //////////////////////////VIEW PRODUCTS//////////////////////////////// 
    public function getprod_types($prod_url_id='',$status='',$customer_id='') 
    {
        $sql_select_products =" SELECT * FROM PRODUCT_TYPE 	WHERE 1=1";
		if(isset($prod_url_id) AND ($prod_url_id!=='')){ 
            if(!isset($alltypes)){
                $sql_select_products .=" AND PROD_TYPE_ID = '$prod_url_id'";
            }
        }
        if(isset($status) AND ($status!=='')){
            $sql_select_products .=" AND PROD_TYPE_STATUS = '1'";
            if(isset($customer_id) AND ($customer_id!=="")){
                $active_prod_types = $this->getCustNames($customer_id);
                if($active_prod_types->ALLOW_PROD_TYPES == ""){
                    $sql_select_products .=" AND PROD_TYPE_ID = 0";
                } else {
                    $sql_select_products .=" AND PROD_TYPE_ID in ($active_prod_types->ALLOW_PROD_TYPES)";
                }
            } else {
                if(isset($_COOKIE["user_type"]) AND ($_COOKIE["user_type"] !== "956314127503977533") AND ($_COOKIE["user_type"] !== "525874964125375325")) {
                    $sql_select_products .=" AND PROD_TYPE_ID in ('".$_COOKIE["user_prod_types"]."')";
                }
            }
        }
        $query = $this->db->query($sql_select_products);
        $getProductstypes = $query->result();
        return $getProductstypes;	
    }

    public function getCustNames($attributes='')
    {
        $sql_select_customers ="SELECT * FROM customers WHERE CUSTOMER_ENC IN ($attributes)";
        $query = $this->db->query($sql_select_customers);
		$getCustNames = $query->result();
		return $getCustNames;	
	}

    public function getProducts($prod_type='',$status='',$ifStore='',$prod_id='')
    {
        if(isset($ifStore) AND ($ifStore == 1)){
            $sql_select_products =" SELECT * FROM PRODUCTS"; 
            if(isset($prod_type) AND ($prod_type !== '')){
                $sql_select_products .=" WHERE PROD_TYPE_ID = '$prod_type'";
            }
        } else {
            $sql_select_products =" SELECT PD.*, PR.N_PROVIDER, PR.NP_SHORT,PR.NP_ID,pt.PROD_TYPE_NAME,pt.PROD_TYPE_ID FROM PRODUCTS PD INNER Join PRODUCT_TYPE pt ON PD.PROD_TYPE_ID=pt.PROD_TYPE_ID 
            INNER JOIN PROVIDER PR ON (PD.PROD_PROVIDER = PR.NP_ID) WHERE 1=1";
            if(isset($prod_type) AND ($prod_type == '0')){
                $sql_select_products .=" AND 1=1";
            } else if(isset($prod_type) AND ($prod_type !== '')){
                $sql_select_products .=" AND PD.PROD_TYPE_ID = '$prod_type'";
            } else {
                $sql_select_products .=" AND 1=0";
            }
            if(isset($prod_id) AND ($prod_id !== '')){
                $sql_select_products .=" AND PD.PROD_ID = '$prod_id'";
            }
            if(isset($status) AND ($status) !== ''){
                $sql_select_products .=" AND PD.PROD_STATUS = '$status'";
            }
            $sql_select_products .=" ORDER BY PD.PROD_NAME";
        }
        $query = $this->db->query($sql_select_products);
        $getProduct = $query->result();
        return $getProduct;
    }

    public function addproduct($items)
    {   
        if($items["acc_length"] == ""){
            $items["acc_length"] = "0";
        }
        if($items["PROD_ID"] > "0") {
            $sql_update_products = "UPDATE PRODUCTS SET
            PROD_PROVIDER ='".$items['PROVIDER']."',
            PROD_CODE_MAIN ='".$items['Prod_Code']."',
            PROD_TYPE_ID ='".$items['PRODUCT_TYPE']."',
            PROD_NAME = '".$items['PRODUCT_NAME']."',
            PROD_OPERATOR ='".$items['operator_Code']."',
            PROD_ACC_MASK = '".$items['acc_mask']."',
            PROD_FEE = ";
            if(isset($items['prod_fee']) AND $items['prod_fee'] !== ""){
                $sql_update_products .= "'".$items['prod_fee']."',";
            } else {
                $sql_update_products .= "'0',";
            }
            if(isset($items['acc_length'])){
                $sql_update_products .= " PROD_ACC_LENGTH='".$items['acc_length']."',";
            }
            $sql_update_products .= " PROD_ACC_TYPE= '".$items['PROD_ACC_TYPE']."'";
            if(isset($items['PROD_CHECK_BAL'])){
                $sql_update_products .= " ,PROD_CHECK_BAL = '".$items['PROD_CHECK_BAL']."'";
            }
            if(isset($items['ACC_NAME_REQ'])){
                $sql_update_products .= " ,ACC_NAME_REQ ='".$items['ACC_NAME_REQ']."'";
            }
            if(isset($items['PROD_CURRENCY'])){
                $sql_update_products .= " ,PROD_CURRENCY='".$items['PROD_CURRENCY']."'";
            }
            /*<!--- <cfif IsDefined("form.PROD_ZIP")>
                ,PROD_ZIP='#form.PROD_ZIP#'
            </cfif>
            <cfif IsDefined("form.PROD_AREACODE")>
                ,PROD_AREACODE='#form.PROD_AREACODE#'
            </cfif>
            <cfif IsDefined("form.PROD_SERIAL")>
                ,PROD_SERIAL='#form.PROD_SERIAL#'
            </cfif>
            <cfif IsDefined("form.PROD_SIMCARD")>
                ,PROD_SIMCARD='#form.PROD_SIMCARD#'
            </cfif>--->*/
            $sql_update_products .= " ,PROD_ZIP_REQ =";
            if(isset($items['PROD_ZIP_REQ'])){
                $sql_update_products .= "'".$items['PROD_ZIP_REQ']."'";
            } else {
                $sql_update_products .= "'0'";
            }
            $sql_update_products .= ",PROD_AREACODE_REQ =";
            if(isset($items['PROD_AREACODE_REQ'])){
                $sql_update_products .= "'".$items['PROD_AREACODE_REQ']."'";
            } else {
                $sql_update_products .= "'0'";
            }
            $sql_update_products .= ",PROD_SERIAL_REQ =";
            if(isset($items['PROD_SERIAL_REQ'])){
                $sql_update_products .= "'".$items['PROD_SERIAL_REQ']."'";
            } else {
                $sql_update_products .= "'0'";
            }
            $sql_update_products .= ",PROD_SIMCARD_REQ =";
            if(isset($items['PROD_SIMCARD_REQ'])){
                $sql_update_products .= "'".$items['PROD_SIMCARD_REQ']."'";
            } else {
                $sql_update_products .= "'0'";
            }
            $sql_update_products .= ",PROD_HOURS_FULLFILL =";
            if(isset($items['PROD_HOURS_FULLFILL'])){
                $sql_update_products .= "'".$items['PROD_HOURS_FULLFILL']."'";
            } else {
                $sql_update_products .= "'0'";
            }
            $sql_update_products .= ",PROD_SUPPORT_PAYMENT =";
            if(isset($items['supports_partial_payments'])){
                $sql_update_products .= "'".$items['supports_partial_payments']."'";
            } else {
                $sql_update_products .= "'0'";
            }
            $sql_update_products .= "WHERE PROD_ID = '".$items['PROD_ID']."'";
            $query = $this->db->query($sql_update_products);

            $this->customers->insertLogTable("update","Update Product");
        } else {
            $sql_insert_products = " INSERT INTO  PRODUCTS
            (PROD_PROVIDER,PROD_NAME,PROD_CODE_MAIN,PROD_TYPE_ID,PROD_OPERATOR,PROD_ACC_MASK,PROD_ACC_LENGTH,PROD_ACC_TYPE,PROD_CHECK_BAL";
            if(isset($items['ACC_NAME_REQ'])){
                $sql_insert_products .= ",ACC_NAME_REQ";
            }
            $sql_insert_products .= ",PROD_CURRENCY";
            //<!---PROD_ZIP,PROD_AREACODE,PROD_SERIAL,PROD_SIMCARD--->
            $sql_insert_products .= ", PROD_SIMCARD_REQ, PROD_SERIAL_REQ, PROD_AREACODE_REQ, PROD_ZIP_REQ, PROD_HOURS_FULLFILL, PROD_SUPPORT_PAYMENT,PROD_FEE)
            values
            ('".$items["PROVIDER"]."','".$items["PRODUCT_NAME"]."','".$items["Prod_Code"]."','".$items["PRODUCT_TYPE"]."','".$items["operator_Code"]."','".$items["acc_mask"]."','".$items["acc_length"]."'";
            if(isset($items['PROD_CHECK_BAL'])){
                $sql_insert_products .= ",'".$items["PROD_ACC_TYPE"]."'";
            }
            if(isset($items['PROD_CHECK_BAL'])){
                $sql_insert_products .= ",'".$items["PROD_CHECK_BAL"]."'";
            } else {
                $sql_insert_products .= ",'0'";
            }
            if(isset($items['ACC_NAME_REQ'])){
                $sql_insert_products .= ",'".$items["ACC_NAME_REQ"]."'";
            }
            if(isset($items['PROD_CURRENCY'])){
                $sql_insert_products .= ",'".$items["PROD_CURRENCY"]."'";
            } else {
                $sql_insert_products .= "','";
            }
            /* <!---,<cfif IsDefined("form.PROD_ZIP")> #form.PROD_ZIP#<cfelse>0</cfif>,'#form.PROD_AREACODE#','#form.PROD_SERIAL#','#form.PROD_SIMCARD#'--->*/
            if(isset($items['PROD_SIMCARD_REQ'])){
                $sql_insert_products .= ",'".$items["PROD_SIMCARD_REQ"]."'";
            } else {
            $sql_insert_products .= ",'0'";
            } 
            if(isset($items['PROD_SERIAL_REQ'])){
                $sql_insert_products .= ",'".$items["PROD_SERIAL_REQ"]."'";
            } else {
            $sql_insert_products .= ",'0'";
            }
            if(isset($items['PROD_AREACODE_REQ'])){
                $sql_insert_products .= ",'".$items["PROD_AREACODE_REQ"]."'";
            } else {
            $sql_insert_products .= ",'0'";
            }
            if(isset($items['PROD_ZIP_REQ'])){
                $sql_insert_products .= ",'".$items["PROD_ZIP_REQ"]."'";
            } else {
                $sql_insert_products .= ",'0'";
            }
            if(isset($items['PROD_HOURS_FULLFILL'])){
                $sql_insert_products .= ",'".$items["PROD_HOURS_FULLFILL"]."'";
            } else {
                $sql_insert_products .= ",'0'";
            }
            if(isset($items['supports_partial_payments'])){
                $sql_insert_products .= ",'".$items["supports_partial_payments"]."'";
            } else {
                $sql_insert_products .= ",'0'";
            }
            if(isset($items['prod_fee']) AND $items['prod_fee'] !== ""){
                $sql_insert_products .= ",'".$items["prod_fee"]."'";
            } else {
                $sql_insert_products .= ",'0'";
            } 
            $sql_insert_products .= ")";
            $query = $this->db->query($sql_insert_products);
            
        
            $this->customers->insertLogTable("INSERT","Added New Product");

            /*$product_id = product_result.generatedkey>
            <!---call storedprocedure to add/update commisions--->*/

            if(($items['PRODUCT_TYPE'] == "5") AND ($items['PROVIDER'] == "5") OR ($items['PRODUCT_TYPE'] == "20") AND ($items['PROVIDER'] == "10")){
                $sql_update_products = "UPDATE PRODUCTS SET PROD_DISCOUNT = '67.41' WHERE PROD_ID = '$product_id'";
                $query = $this->db->query($sql_update_products);
                $sql_update_products = $query->result();
                
            }else if($items['PRODUCT_TYPE'] == "25"){
                $sql_product_products = "UPDATE PRODUCTS SET PROD_DISCOUNT = '55.55' WHERE PROD_ID = '$product_id'";
                $query = $this->db->query($sql_update_products);
            }
            /*<cfstoredproc procedure="UpdateProductDiscount" dataSource ="#request.db_dsn#">
                <cfprocparam cfsqltype="cf_sql_integer" value="#product_id#">
                <cfprocparam cfsqltype="cf_sql_varchar" value="#cookie.user_account_id#">
                <cfprocparam cfsqltype="cf_sql_integer" value="#form.PRODUCT_TYPE#">
                <cfprocparam cfsqltype="cf_sql_integer" value="#form.PROVIDER#">
            </cfstoredproc>*/
            
            $this->customers->insertLogTable("INSERT","Added commissions to PROD_COMM for product id = $product_id");
            return $ok;
        }
        if($query){
            return true;
        } else{
            return false;
        }
    }
    
    ///////////////////////////PROD TYPES///////////////////////
    public function addUpdateProdTypes($items){
        $ok = "";
        $uploadPath =  $_SERVER['DOCUMENT_ROOT']."/productTypes/";
        $config1 = array(
        'upload_path' => $uploadPath,
        'allowed_types' => "gif|jpg|png|jpeg",
        'overwrite' => TRUE,
        'max_size' => "2048000",
        'max_height' => "768",
        'max_width' => "1024"
        );
        $this->load->library('upload', $config1);
        $this->upload->initialize($config1);
        
        if($this->upload->do_upload("prodImage")) {
            $success = array('upload_data' => $this->upload->data());
        } else {
            $error = array('error' => $this->upload->display_errors());
        }
        if(($_FILES['prodImage']['name'] !=="")){
            $ImageName = $_FILES['prodImage']['name'];
        } else {
            $ImageName = $items["old_image"];
        }
        $sql_select_product_type = "SELECT * FROM PRODUCT_TYPE WHERE PROD_TYPE_ID = '".$items["prod_typeid"]."'";
		$query = $this->db->query($sql_select_product_type);
        $product_type_count = $query->num_rows();		
        if($product_type_count > "0"){
            $sql_update_product_type = "UPDATE PRODUCT_TYPE SET 
            PROD_TYPE_NAME = '".$items["prod_type"]."',
            PROD_TYPE_STATUS = '".$items["status"]."',
            PROD_ICON = '$ImageName' WHERE 
            PROD_TYPE_ID = '".$items["prod_typeid"]."'"; 
            $query = $this->db->query($sql_update_product_type);
            return $msg = "Data Update Successfully";		
        }else {
            $sql_insert_product_type = "INSERT INTO PRODUCT_TYPE (PROD_TYPE_NAME, PROD_TYPE_STATUS, PROD_ICON) VALUES ('".$items["prod_type"]."','".$items["status"]."', '$ImageName')";
            $query = $this->db->query($sql_insert_product_type);
        }
        return $msg = "Data Submit Successfully";
    }

    //////////////////////////////////PRODUCT PLANS///////////////////////////
    public function getAllprodplans($status=''){
        $sql_select_prod_plans = "SELECT * FROM PROD_PLAN WHERE PPLAN_ACCOUNT = '".$_COOKIE["user_account_id"]."'";
        if((isset($status)) AND ($status == "active")){
            $sql_select_prod_plans .= " AND PPLAN_STATUS = '1'";
        }
        $query = $this->db->query($sql_select_prod_plans);
        $getProducts = $query->result();
        return $getProducts;
    }
    
    public function getprod_plans($plan_id=''){
        $sql_select_prod_plan = "SELECT * FROM PROD_PLAN PP JOIN PROD_DISC PD ON(PP.PPLAN_ID = PD.PPLAN_ID)
        WHERE PP.PPLAN_ACCOUNT = '".$_COOKIE["user_account_id"]."'";
        if(isset($plan_id)){
            $sql_select_prod_plan .= " AND PP.PPLAN_ID ='$plan_id'";
        }
        $sql_select_prod_plan .= " ORDER BY PD.PPLAN_FROM DESC";
		$query = $this->db->query($sql_select_prod_plan);
        $getProducts = $query->result(); 
		return $getProducts;
    }
    
    public function addUpdateprod_plans($items){
        $i = "";
        $j = "";
        $k = "";
        $sql_select_prod_plan = "SELECT * FROM PROD_PLAN PP LEFT JOIN PROD_DISC PD ON(PP.PPLAN_ID = PD.PPLAN_ID)
        WHERE PP.PPLAN_ACCOUNT = '".$_COOKIE["user_account_id"]."'"; 
        if(isset($items["plan_id"])) {
            $sql_select_prod_plan .= " AND PP.PPLAN_ID='".$items['plan_id']."'";
            $query = $this->db->query($sql_select_prod_plan);
            $product_plan_count = $query->num_rows();
        }
        if($product_plan_count>"0") {
            $sql_update_prod_plan = "UPDATE PROD_PLAN SET PPLAN_NAME = '".$items['prod_plan']."', PPLAN_STATUS = '".$items['status']."' WHERE PPLAN_ID = '".$items['plan_id']."' AND PPLAN_ACCOUNT = '".$_COOKIE["user_account_id"]."'";
            $query = $this->db->query($sql_update_prod_plan);
            for($i="0";$i<=$items["discounts_count"];$i++) {
                $disc_from = $items["disc_from_".$i];
                $disc_to = $items["disc_to_".$i];
                $disc_points = $items["disc_points_".$i];
                $disc_id_val = 'disc_id_'.$i;
                if(isset($disc_id_val) AND ($disc_id_val) !== "" ) {
                    $disc_id = $items[$disc_id_val];
                    $sql_update_prod_disc = "UPDATE PROD_DISC SET PPLAN_FROM = '$disc_from', PPLAN_TO = '$disc_to', PPLAN_DISC = '$disc_points' WHERE PPLAN_ID = '".$items['plan_id']."' AND PPLAN_DISC_ID = '$disc_id'"; 
                    $query = $this->db->query($sql_update_prod_disc);
                } else {
                    $sql_insert_prod_disc = "INSERT INTO PROD_DISC (PPLAN_ID, PPLAN_FROM, PPLAN_TO, PPLAN_DISC)
                    VALUES ('".$items['plan_id']."','$disc_from', '$disc_to', '$disc_points')";
                    $query = $this->db->query($sql_insert_prod_disc);    
                }
                if(isset($items["deleted_rows"]) AND ($items["deleted_rows"] !== "")) {
                    $explode_deleted_rows = explode(",", $items["deleted_rows"]);
                    for($k="0";$k<sizeof($explode_deleted_rows);$k++) {
                        $sql_delete_prod_disc = "DELETE FROM PROD_DISC WHERE PPLAN_DISC_ID = '$k' AND 
                        PPLAN_ID = '".$items['plan_id']."'";
                        $query = $this->db->query($sql_delete_prod_disc);
                    }
                }
            } 

            $this->customers->insertLogTable("UPDATE","Updated Plan ".$items['prod_plan']);

            // <!---call storedprocedure to add/update commisions--->
            // <cfstoredproc procedure="UpdateDiscountFromPlans" dataSource ="#request.db_dsn#">
            //     <cfprocparam cfsqltype="cf_sql_integer" value="#form.plan_id#">
            //     <cfprocparam cfsqltype="cf_sql_varchar" value="#cookie.user_account_id#">
            // </cfstoredproc>

            $this->customers->insertLogTable("INSERT","Updated commissions to PROD_COMM for plan id ".$items['prod_plan']);

            return $msg = "Data Update Successfully";
            
        } else {
            $sql_insert_prod_plan = " INSERT INTO PROD_PLAN (PPLAN_NAME, PPLAN_STATUS, PPLAN_ACCOUNT, PPLAN_DATE)
            VALUES ('".$items['prod_plan']."','".$items['status']."', '".$_COOKIE["user_account_id"]."','".date("Y-m-d H:i:s")."')";
            $query = $this->db->query($sql_insert_prod_plan);
            $getplanID = $this->db->insert_id();
            // $gen_Plan_id = $getplanID.generatedkey>
            for($j="1";$j<=$items["discounts_count"];$j++) {
                $disc_from = $items["disc_from_".$j];
                $disc_to = $items["disc_to_".$j];
                $disc_points = $items["disc_points_".$j];
                $sql_insert_prod_disc = " INSERT INTO PROD_DISC (PPLAN_ID, PPLAN_FROM, PPLAN_TO, PPLAN_DISC)
                VALUES ('$getplanID','$disc_from', '$disc_to', '$disc_points' )";
                $query = $this->db->query($sql_insert_prod_disc);

            }
            $this->customers->insertLogTable("INSERT","Added new Plan".$items['prod_plan']);
            return $msg = "Data Submit Successfully";
        }
    }

    public function uploadImage($form){
        $uploadPath =  $_SERVER['DOCUMENT_ROOT']."/products/";
        $config = array(
        'upload_path' => $uploadPath,
        'allowed_types' => "gif|jpg|png|jpeg",
        'overwrite' => TRUE,
        'max_size' => "2048000",
        'max_height' => "768",
        'max_width' => "1024"
        );
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
        if($this->upload->do_upload("upload_image")) {
            $success = array('upload_data' => $this->upload->data()); 
        } else {
            $error = array('error' => $this->upload->display_errors()); 
        }
        if(($_FILES['upload_image']['name'] !=="")){
            $ImageName = $_FILES['upload_image']['name'];
        } else {
            $ImageName = "";
        }
        $sql_update_products = "UPDATE PRODUCTS SET PROD_".$form['type']."_PIC = '$ImageName' WHERE 
        PROD_ID = '".$form["Prodid"]."'";
        $query = $this->db->query($sql_update_products);
            
        $this->customers->insertLogTable("UPDATE","Updated PROD_".$form['type']."_PIC in PRODUCTS table having PROD_ID =".$form['Prodid']);
        return true;
    }
    
    public function saveDetails($form){
        $sql_update_products = "UPDATE PRODUCTS SET PROD_DETAIL = '".$form['details_product']."' WHERE PROD_ID = '".$form['prod_details_id']."'";
        $query = $this->db->query($sql_update_products);

        $this->customers->insertLogTable("UPDATE","Updated PROD_DETAIL in PRODUCTS table having PROD_ID =".$form['prod_details_id']);
        return true;
    }

    public function prodtax($id,$tax){
        $sql_update_prod_comm = "UPDATE PROD_COMM SET TAX_CHARGE  = '$tax' WHERE PROD_ID = '$id' 
        AND (PARENT_ACCOUNT_ID = '".$_COOKIE["user_account_id"]."' OR ACCOUNT =  '".$_COOKIE["user_account_id"]."')";
        $query = $this->db->query($sql_update_prod_comm);
    
        $this->customers->insertLogTable("UPDATE","Updated PRODUCTS tax having PROD_ID =".$id);

        if($query){
            return true;
        } else {
            return false; 
        }
    }

    public function getProductsForSelling($prod_type_id='',$prod_id=''){
        $sql_select_products = "SELECT P.*,PC.*,PC.PROD_FAV as store_fav,PT.*,CL.CTY_NAME, CL.CTY_Flag FROM PRODUCTS P LEFT  JOIN PROD_COMM PC ON(P.PROD_ID = PC.PROD_ID) JOIN PRODUCT_TYPE PT ON P.PROD_TYPE_ID = PT.PROD_TYPE_ID JOIN COUNTRY_LIST CL ON CL.CTY_ID = P.PROD_CODE_MAIN WHERE (PC.COMM_STATUS = 1 OR (PC.OWN_STATUS = 1 AND PC.COMM_STATUS = 0)) AND PC.ACCOUNT = '".$_COOKIE["user_account_id"]."'";
        if(isset($prod_id) AND ($prod_id !== "")) {
            $sql_select_products .=" AND P.PROD_ID = '$prod_id'";
        }
        if(isset($prod_type_id) AND ($prod_type_id !== "")) {
            $sql_select_products .=" AND P.PROD_TYPE_ID = '$prod_type_id'";
        } else {
            if($_COOKIE["user_type"] !== "956314127503977533") {
                $sql_select_products .=" AND P.PROD_TYPE_ID in(".$_COOKIE['user_prod_types'].")";
            }
        }
        $query = $this->db->query($sql_select_products);
        $getProducts = $query->result();
		return $getProducts;    
    }
    function updateDiscount($discount,$fav_prod,$id){
        
		$sql_update_products = "UPDATE PRODUCTS SET PROD_DISCOUNT = '$discount'";
        if(isset($fav_prod) AND ($fav_prod == "1")){
            $sql_update_products .= " , PROD_FAV=1";
        } else {
            $sql_update_products .= " , PROD_FAV=0";
        }
        $sql_update_products .=" WHERE PROD_ID = '$id'";
        $query = $this->db->query($sql_update_products);        
                
        $sql_select_prod_provider = "SELECT PROD_PROVIDER, PROD_TYPE_ID FROM PRODUCTS WHERE PROD_ID = '$id'";
        $query = $this->db->query($sql_select_prod_provider);
        $getProdProvider = $query->result();
                
        $this->customers->insertLogTable("UPDATE","Updated DISCOUNT in PRODUCTS table having PROD_ID =".$id);        
        
        /////////////THIS IS PENDING//////////////////////////////        
        // <!---call storedprocedure to add/update commisions--->
        // <cfstoredproc procedure="UpdateProductDiscount" dataSource ="#request.db_dsn#">
        //     <cfprocparam cfsqltype="cf_sql_integer" value="#arguments.id#">
        //     <cfprocparam cfsqltype="cf_sql_varchar" value="#cookie.user_account_id#">
        //     <cfprocparam cfsqltype="cf_sql_integer" value="#getProdID.PROD_TYPE_ID#">
        //     <cfprocparam cfsqltype="cf_sql_integer" value="#getProdID.PROD_PROVIDER#">
        // </cfstoredproc>

        $this->customers->insertLogTable("UPDATE","Updated commissions to PROD_COMM table for product id =".$id);        
        return true;
    }

    public function updateSWA($status,$val,$id){
        $sql_update_products = "UPDATE PRODUCTS SET PROD_STATUS = '$val' WHERE PROD_ID = '$id'";
        $query = $this->db->query($sql_update_products);
        
        if($status == "STATUS") {
            $sql_update_prod_comm = " UPDATE PROD_COMM SET COMM_STATUS = '$val' WHERE PROD_ID = '$id'"; 
            $query = $this->db->query($sql_update_prod_comm);
        }
        
        $this->customers->insertLogTable("UPDATE","Updated PRODUCTS table having PROD_ID =".$id);
        if($query){
            return true;
        }  else {
            return false;
        }      
    }

    public function updateProdTypes($ids,$acc){
    
        //$acc_enc = $this->Customers->customDecryptFunction($acc);
        $sql_update_customers = "UPDATE customers SET allow_prod_types = '$ids' WHERE CUSTOMER_ENC = $acc";
        $query = $this->db->query($sql_update_customers);
        
        /////getDist query///////
        $sql_select_accounts = "SELECT ACCOUNT_ENC AS LEVEL1, ACCOUNT_TYPE AS ACCOUNT_TYPE1 FROM accounts 
        WHERE PARENT_ACCOUNT_ID = '$acc' GROUP BY ACCOUNT_ENC";
        $query = $this->db->query($sql_select_accounts);
        $data = $query->result_array();
		$ACCOUNT_TYPE1 = $data[0]["ACCOUNT_TYPE1"]; 
		
        if($ACCOUNT_TYPE1 <= "5"){
            $sql_update_customers = "UPDATE customers SET allow_prod_types = '$ids' WHERE CUSTOMER_ENC in ('#valueList(getDist.LEVEL1)#')";
            $query = $this->db->query($sql_update_customers);
            
            if($ACCOUNT_TYPE1 < "5"){
                /////<!---get sub-distributors--->
                $sql_select_accounts = "SELECT A2.ACCOUNT_ENC AS LEVEL2, A2.ACCOUNT_TYPE AS ACCOUNT_TYPE2 FROM  accounts A JOIN ACCOUNTS A2 ON A2.PARENT_ACCOUNT_ID = A.ACCOUNT_ENC WHERE A.PARENT_ACCOUNT_ID = '$acc' GROUP BY A2.ACCOUNT_ENC";
                $query = $this->db->query($sql_select_accounts);
                $data = $query->result_array();
                $ACCOUNT_TYPE2 = $data[0]["ACCOUNT_TYPE2"];
                
                if($ACCOUNT_TYPE2 <= "5"){
                    $sql_update_customers = "UPDATE customers SET allow_prod_types = '$ids' WHERE CUSTOMER_ENC in (#valueList(getSubDist.LEVEL2)#)";
                    $query = $this->db->query($sql_update_customers);
                    
                    if($ACCOUNT_TYPE2 < "5"){
                        ///<!---get store--->
                        $sql_select_accounts ="SELECT A3.ACCOUNT_ENC AS LEVEL3, A3.ACCOUNT_TYPE AS ACCOUNT_TYPE3 FROM  accounts A JOIN ACCOUNTS A2 ON A2.PARENT_ACCOUNT_ID = A.ACCOUNT_ENC JOIN ACCOUNTS A3 ON A3.PARENT_ACCOUNT_ID = A2.ACCOUNT_ENC WHERE A.PARENT_ACCOUNT_ID = '$acc' GROUP BY A3.ACCOUNT_ENC";
                        $query = $this->db->query($sql_select_accounts);
                        $data = $query->result_array();
                        $ACCOUNT_TYPE3 = $data[0]["ACCOUNT_TYPE3"];
                        
                        if($ACCOUNT_TYPE3 <= "5"){
                            $sql_update_customers = "UPDATE customers SET allow_prod_types = '$ids' WHERE CUSTOMER_ENC in (#valueList(getStore.LEVEL3 )#)";
                            $query = $this->db->query($sql_update_customers);
                        }
                    }
                }
            }
        }
        if($query){
            return true;
        } else {
            return false;
        }
    }

    public function prodStatusManualy($id,$vals,$key){
        //$acc_enc = $this->customers->customDecryptFunction($key);
        $acc_enc = $key;
        if($vals == 1 OR $vals == 0){
            $sql_update_prod_comm = "UPDATE PROD_COMM SET MAN_STATUS = '$vals' WHERE PROD_ID = '$id' AND (PARENT_ACCOUNT_ID = '".$_COOKIE["user_account_id"]."' AND ACCOUNT =  '$acc_enc')";
            $query = $this->db->query($sql_update_prod_comm);
        
            $this->customers->insertLogTable("UPDATE","Updated COMMISSION status to manualy for product id = $id");
        }
        return true;
    }
	
	
	public function billingRecords($v_phone,$ExternalID,$gen_code,$CARD_ID_FROM_FORM,$v_balance,$TotalAmountIncExtraCharge, $extratax, $ACTUAL_AMOUNT, $prod_type_name, $prod_provider, $prod_id, $card_id, $prod_name)
	{
		$cookie_country = 1;
		$current_date = date("Y/m/d");
		$current_date_time = date("Y-m-d H:i:sa");
		//$v_phone = "9542245215";//#form.v_phone#;
		$cookie_country_form_v_phone = $cookie_country.$v_phone;
//		$ExternalID = '120';
	//	$gen_code = '15154541';
	//	$CARD_ID_FROM_FORM = '102';
	//	$v_balance = '12';
	//	$TotalAmountIncExtraCharge = '150';
		$actual_amount_currency = $TotalAmountIncExtraCharge.' USD';
		//$extratax = '0';
		$currency = "USD";
		
		$CONVERSION_RATE = "1";
		//$ACTUAL_AMOUNT = '10';
		//$prod_type_name = '5';
		//$prod_provider = '5';
		$api_type = '10';
		$apidiffFee = '0';
		//$prod_id = '5';
		//$card_id = '0';
		//$prod_name ='test';
		
		//<cfquery name="get_level7" datasource="#request.db_dsn#">
		$query_select_accounts = "SELECT * 
		FROM  accounts a
		JOIN customers c
		ON a.customer_id = c.customer_id
		JOIN ACCOUNT_GROUPS ag
		ON a.account_group_id = ag.account_group_id
		WHERE c.LOGIN_NAME = '$v_phone'"; //<!---'#phone_entered_in_form#'--->
		$query = $this->db->query($query_select_accounts);
		$data = $query->result_array(); 
		
		
		$recordcount = $query->num_rows();
		//</cfquery>
					
		//<cfif get_level7.RecordCount EQ '0'>
		if($recordcount == 0) {
			
			//<!---- CREATE END USER ---->
	
			//<cfquery name="get_pinact" datasource="#request.db_dsn#">
			$query_select_PIN_CONTROL = "SELECT *
			FROM PIN_CONTROL
			WHERE pin_status = 0 
			ORDER BY pin_id asc
			limit 1";
			$query = $this->db->query($query_select_PIN_CONTROL);
			$data = $query->result_array();
			$pin_id = $data[0]["PIN_ID"];
			$pin_account = $data[0]["PIN_ACCOUNT"];
			//</cfquery>
	
			//<cfquery datasource="#request.db_dsn#">
			$query_update_PIN_CONTROL = "UPDATE PIN_CONTROL SET pin_status = 1 WHERE pin_id = '$pin_id'";
			$query = $this->db->query($query_update_PIN_CONTROL);
			//</cfquery>
	
			//<cfquery name="ins_cust" datasource="#request.db_dsn#">
			$query_insert_customers = "insert into customers (customer_enc, customer, local_phone, PUBLIC_CUSTOMER, LOGIN_NAME, COUNTRY_CODE)
			values ('$pin_account','$pin_account', '$v_phone', 0, '$cookie_country_form_v_phone', '".$cookie_country."')";
			$query = $this->db->query($query_insert_customers);
			//</cfquery>
	
			$rate_schedule_id = 7000;
			$account_group_id = 7;
			$account_type = 7;
	
			//<cfquery name="ins_rcos" datasource="#request.db_dsn#">
			$query_insert_CLASS_OF_SERVICE = "INSERT into CLASS_OF_SERVICE
			(COS_ENC ,cos, options, expire_date, gmt_offset, currency_id, language_id, rate_schedule_id, sales_group_id, tax_group_id, public_cos)
			VALUES
			(0,'End User $pin_account', 'MAXCONCURRENT=1', '2050-12-31 00:00:00', '-5', '1', '1', '$rate_schedule_id', '0', '0', '1')";
			$query = $this->db->query($query_insert_CLASS_OF_SERVICE);
			//</cfquery>
	
			//<cfquery name="get_cos" datasource="#request.db_dsn#">
			$query_select_CLASS_OF_SERVICE = "SELECT max(cos_id) as cos_id1
			FROM  CLASS_OF_SERVICE
			WHERE rate_schedule_id = '$rate_schedule_id'";
			$query = $this->db->query($query_select_CLASS_OF_SERVICE);
			$data = $query->result_array();
			$cos_id1 = $data[0]["cos_id1"];	
			//</cfquery>
	
			//<cfquery name="sel_cust" datasource="#request.db_dsn#">
			$query_select_customers = "SELECT *
			FROM  customers
			WHERE CUSTOMER_ENC = '$pin_account'";
			$query = $this->db->query($query_select_customers);
			$data = $query->result_array(); 
			$customer_id = $data[0]["CUSTOMER_ID"];
			//</cfquery>
	
			//<cfquery name="ins_cust" datasource="#request.db_dsn#">
			$query_insert_accounts = "insert into accounts
			(account_enc, account, pin, customer_id, batch_id, account_group_id, account_type, parent_account_id, enabled, billing_type,
			starting_balance, credit_limit, balance, starting_packaged_balance1, cos_id, write_cdr, creation_date_time,SEQUENCE_NUMBER,PACKAGED_BALANCE1,SERVICE_CHARGE_STATUS,CALLS_TO_DATE,MINUTES_TO_DATE_BILLED,MINUTES_TO_DATE_ACTUAL,PACKAGED_BALANCE2,PACKAGED_BALANCE3,PACKAGED_BALANCE4,PACKAGED_BALANCE5,STARTING_PACKAGED_BALANCE2,STARTING_PACKAGED_BALANCE3,STARTING_PACKAGED_BALANCE4,STARTING_PACKAGED_BALANCE5)
			values
			('$pin_account', '$pin_account', '0', '$customer_id', '0', '$account_group_id','$account_type', '".$_COOKIE['user_account_id']."', '1', '1', '0', 
			'0', '0', '0', '$cos_id1', '0', '$current_date',0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
			$query = $this->db->query($query_insert_accounts);		
			//</cfquery>
	
			//<cfquery name="get_level7" datasource="#request.db_dsn#">
			$query_select_customers = "SELECT * 
			FROM  accounts A
			JOIN customers C
			ON A.customer_id = C.customer_id
			JOIN ACCOUNT_GROUPS AG
			ON A.account_group_id = AG.account_group_id
			WHERE C.LOGIN_NAME = '$cookie_country_form_v_phone'";
			$query = $this->db->query($query_select_customers);
			$data = $query->result_array(); 
			$CUSTOMER_ENC = $data[0]["CUSTOMER_ENC"];
			$ACCOUNT_ID = $data[0]["ACCOUNT_ID"];
			$ACCOUNT = $data[0]["ACCOUNT"];
			$ACCOUNT_GROUP = $data[0]["ACCOUNT_GROUP"];
			$ACCOUNT_ENC = $data[0]["ACCOUNT_ENC"];
			$PARENT_ACCOUNT_ID = $data[0]["PARENT_ACCOUNT_ID"];
			$Balance = $data[0]["BALANCE"];
			//</cfquery>
			
			//<!--- END CUSTOMER ENTRY --->
		}else{
			$Balance = $data[0]["BALANCE"];
			$CUSTOMER_ENC = $data[0]["CUSTOMER_ENC"];
			$ACCOUNT_ID = $data[0]["ACCOUNT_ID"];
			$ACCOUNT = $data[0]["ACCOUNT"];
			$ACCOUNT_GROUP = $data[0]["ACCOUNT_GROUP"];
			$ACCOUNT_ENC = $data[0]["ACCOUNT_ENC"];
			$PARENT_ACCOUNT_ID = $data[0]["PARENT_ACCOUNT_ID"];
		}
			
		//<!---Update Prod_sold--->
		//<cfquery name="ins_cust" datasource="#request.db_dsn#">
		$query_update_PROD_SOLD = "UPDATE PROD_SOLD
		SET PROD_ACCOUNT_ID = '$CUSTOMER_ENC'
		WHERE  PROD_SOLD_ID = '$ExternalID'";
		$query = $this->db->query($query_update_PROD_SOLD);
		//</cfquery>
			
		//<!--- BEGIN RECORDS FOR PRODUCT PURCHASED ---->
		//<!--- BILLING RECORD FOR END USER ---->
		//<cfquery datasource="#request.db_dsn#">
		$query_insert_BILLING = "INSERT into BILLING 
			(call_session_id, entry_type, account_id, account, account_group, start_date_time, connect_date_time, disconnect_date_time, 
			login_name, node, node_type, description, detail, per_call_charge, per_minute_charge, per_call_surcharge, 
			per_minute_surcharge, actual_duration, quantity, amount,   rate_interval, 
			
			disconnect_charge, billing_delay, grace_period, account_type, parent_account_id, prod_id, card_id,ORIGIN,user_1,USER_2,USER_3,USER_4,CURRENCY,CONVERSION_RATE, ACTUAL_AMOUNT)
			VALUES
			('$gen_code', '9', '$ACCOUNT_ID', '$ACCOUNT', '$ACCOUNT_GROUP', '$current_date_time', '$current_date_time', 
			'$current_date_time', '".$_COOKIE['user_name']."', 'Nadi', '0', 'Purchase of Product ".$prod_name."', 'Item Id ".$card_id." for $actual_amount_currency', '0', '0', '0', '0',
			'0', '0'";
			if($TotalAmountIncExtraCharge > 0){
				$query_insert_BILLING .= ",'$TotalAmountIncExtraCharge'";
			} else {  
				$query_insert_BILLING .= ",'$v_balance'";
			}
			$query_insert_BILLING .= ",'0', '0', '0', '0', '7', '$PARENT_ACCOUNT_ID', '".$prod_id."', '".$card_id."','0','$ExternalID'";
			if($extratax > 0){
				$query_insert_BILLING .= ",'$extratax'";
			} else {
				$query_insert_BILLING .= ",''";
			}
			if($extratax > 0){
				$query_insert_BILLING .= ",'($v_balance * $extratax)/100'";
			} else {
				$query_insert_BILLING .= ",''";
			}
			$query_insert_BILLING .= ",'$Balance','$currency','$CONVERSION_RATE','$ACTUAL_AMOUNT')";
		$query = $this->db->query($query_insert_BILLING);	 
		//</cfquery>
	
		//<cfquery datasource="#request.db_dsn#">
		$query_update_accounts = "UPDATE  accounts
		SET	last_credit_date_time = '$current_date'
		WHERE	ACCOUNT_ENC = '$ACCOUNT_ENC'";
		$query = $this->db->query($query_update_accounts);
		//</cfquery>
	
		//<!---- RECORD FOR STORE LEVEL 5 WITH COMMISSION ---->
		//<cfquery name="get_level5Com" datasource="#request.db_dsn#">
		$query_select_PROD_COMM = "SELECT * 
		FROM  PROD_COMM PC
		JOIN CARDS C
		ON PC.PROD_ID = C.PROD_ID
		JOIN PRODUCTS P
		ON C.PROD_ID = P.PROD_ID
		WHERE PC.ACCOUNT = '".$_COOKIE['user_account_id']."'
		AND C.CARD_ID = '$CARD_ID_FROM_FORM'";
		$query = $this->db->query($query_select_PROD_COMM);
		$data = $query->result_array();
		$get_level5Com_PROD_COMM = $data[0]["PROD_COMM"];
		//</cfquery>
			
		//<cfquery name="get_level5" datasource="#request.db_dsn#">
		$query_select_accounts = "SELECT * 
		FROM  accounts a
		JOIN customers c
		ON a.customer_id = c.customer_id
		JOIN ACCOUNT_GROUPS ag
		ON a.account_group_id = ag.account_group_id
		WHERE account = '".$_COOKIE['user_account_id']."'";
		$query = $this->db->query($query_select_accounts);
		$data = $query->result_array();
		$get_level5_BALANCE = $data[0]["BALANCE"];
		$get_level5_ACCOUNT_ID = $data[0]["ACCOUNT_ID"];
		$get_level5_ACCOUNT = $data[0]["ACCOUNT"];
		$get_level5_ACCOUNT_GROUP = $data[0]["ACCOUNT_GROUP"];
		$get_level5_PARENT_ACCOUNT_ID = $data[0]["PARENT_ACCOUNT_ID"];
		//</cfquery>
	
	
		//<!---If regalo, regalii Payment then assign commission on the basis of $3--->
		if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15){
			$discount = ($apidiffFee*$get_level5Com_PROD_COMM)/100;
			$com5_val = $v_balance - $discount;
			$lev5_com = $v_balance-$com5_val;
			$lev5_nbal = $get_level5_BALANCE-$com5_val;
		} else {
			$com5_val = ($v_balance*$get_level5Com_PROD_COMM)/100;		
			$lev5_com = $v_balance-$com5_val;
			$lev5_nbal = $get_level5_BALANCE-$lev5_com;
		}
	
		//<cfquery datasource="#request.db_dsn#">
		$query_insert_BILLING = "INSERT into BILLING 
			(call_session_id, entry_type, account_id, account, account_group, start_date_time, connect_date_time, disconnect_date_time, 
			login_name, node, node_type, description, detail, per_call_charge, per_minute_charge, per_call_surcharge, 
			per_minute_surcharge, actual_duration, quantity, amount,   rate_interval, 
			disconnect_charge, billing_delay, grace_period, account_type, parent_account_id, prod_id, card_id, user_7, user_8, user_9,ORIGIN,user_4,CURRENCY,CONVERSION_RATE,ACTUAL_AMOUNT)
			VALUES
			('$gen_code', '9', '$get_level5_ACCOUNT_ID', '$get_level5_ACCOUNT', '$get_level5_ACCOUNT_GROUP', '$current_date_time', '$current_date_time', 
			'$current_date_time', '".$_COOKIE['user_name']."', 'Nadi', '0', 'Purchase of Product ".$prod_name."', 'Item Id ".$card_id." for $actual_amount_currency', '0', '0', '0', '0',
			'0', '0'"; 
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15) {
				$query_insert_BILLING .= ",'-$com5_val'";
			} else {
				$query_insert_BILLING .= ",'-$lev5_com'";
			}
			$query_insert_BILLING .= ", '0', '0', '0', '0', '5', '$get_level5_PARENT_ACCOUNT_ID', '".$prod_id."', '".$card_id."', '$v_balance'"; 
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15){
				$query_insert_BILLING .= ",'$lev5_com'";
			} else {
				$query_insert_BILLING .= ",'$com5_val'";
			}
			$query_insert_BILLING .= ", '$get_level5Com_PROD_COMM','0','$lev5_nbal','$currency','$CONVERSION_RATE','$ACTUAL_AMOUNT')";
		$query = $this->db->query($query_insert_BILLING);
		//</cfquery>
			
		//<cfquery datasource="#request.db_dsn#">
		$query_update_accounts = "UPDATE  accounts
			SET	last_credit_date_time = '$current_date',
			balance	= '$lev5_nbal'
			WHERE	account_enc = '".$_COOKIE['user_account_id']."'";
		$query = $this->db->query($query_update_accounts);	
		//</cfquery>
	
		//<!---- RECORD FOR SUBDISTRIBUTOR LEVEL 4 WITH COMMISSION ---->
		//<cfquery name="get_level4Com" datasource="#request.db_dsn#">
		$query_select_PROD_COMM = "SELECT * 
			FROM  PROD_COMM PC
			JOIN CARDS C
			ON PC.PROD_ID = C.PROD_ID
			JOIN PRODUCTS P
			ON C.PROD_ID = P.PROD_ID
			WHERE PC.ACCOUNT = '$get_level5_PARENT_ACCOUNT_ID'
			AND C.CARD_ID = '$CARD_ID_FROM_FORM'";
		$query = $this->db->query($query_select_PROD_COMM);
		$data = $query->result_array();
		$get_level4Com_PROD_COMM = $data[0]["PROD_COMM"];
		//</cfquery>
	
		//<cfquery name="get_level4" datasource="#request.db_dsn#">
		$query_select_accounts = "SELECT * 
			FROM  accounts a
			JOIN customers c
			ON a.customer_id = c.customer_id
			JOIN ACCOUNT_GROUPS ag
			ON a.account_group_id = ag.account_group_id
			WHERE account_enc = '$get_level5_PARENT_ACCOUNT_ID'";
		$query = $this->db->query($query_select_accounts);
		$data = $query->result_array();
		$get_level4_BALANCE = $data[0]["BALANCE"];
		$get_level4_ACCOUNT_ID = $data[0]["ACCOUNT_ID"];
		$get_level4_ACCOUNT = $data[0]["ACCOUNT"];
		$get_level4_ACCOUNT_GROUP = $data[0]["ACCOUNT_GROUP"];
		$get_level4_PARENT_ACCOUNT_ID = $data[0]["PARENT_ACCOUNT_ID"];	
		$get_level4_account_enc = $data[0]["ACCOUNT_ENC"];
		//</cfquery>
				
		//<!---If regalo Payment then assign commission on the basis of $3--->
		if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15){
			$discount = ($apidiffFee*$get_level4Com_PROD_COMM)/100;
			$com4_val = $v_balance-$discount;
			$lev4_com = $v_balance-$com4_val;
			$lev4_nbal = $get_level4_BALANCE-$com4_val;
		} else {
			$com4_val = ($v_balance*$get_level4Com_PROD_COMM)/100;
			$lev4_com = $v_balance-$com4_val;
			$lev4_nbal = $get_level4_BALANCE-$lev4_com;
		}
	
		//<cfquery datasource="#request.db_dsn#">
		$query_insert_BILLING = "INSERT into BILLING 
			(call_session_id, entry_type, account_id, account, account_group, start_date_time, connect_date_time, disconnect_date_time, 
			login_name, node, node_type, description, detail, per_call_charge, per_minute_charge, per_call_surcharge, 
			per_minute_surcharge, actual_duration, quantity, amount,   rate_interval, 
			disconnect_charge, billing_delay, grace_period, account_type, parent_account_id, prod_id, card_id, user_7, user_8, user_9,ORIGIN,user_4,CURRENCY,CONVERSION_RATE,ACTUAL_AMOUNT)
			VALUES
			('$gen_code', '9', '$get_level4_ACCOUNT_ID', '$get_level4_ACCOUNT', '$get_level4_ACCOUNT_GROUP', '$current_date_time', '$current_date_time', 
			'$current_date_time', '".$_COOKIE['user_name']."', 'Nadi', '0', 'Purchase of Product ".$prod_name."', 'Item Id ".$card_id." for $actual_amount_currency', '0', '0', '0', '0',
			'0', '0'"; 
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15 ){
				$query_insert_BILLING .= ",'-$com4_val'";
			} else {
				$query_insert_BILLING .= ",'-$lev4_com'";
			}
			$query_insert_BILLING .= ", '0', '0', '0', '0', '4', '$get_level4_PARENT_ACCOUNT_ID', '".$prod_id."', '".$card_id."', '$v_balance'"; 
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15 ){
				$query_insert_BILLING .= ",'$lev4_com'";
			} else {
				$query_insert_BILLING .= ",'-$com4_val'";
			}
			$query_insert_BILLING .= ", '$get_level4Com_PROD_COMM','0','$lev4_nbal','$currency','$CONVERSION_RATE','$ACTUAL_AMOUNT')";
		$query = $this->db->query($query_insert_BILLING);	
		//</cfquery>
	
		//<cfquery datasource="#request.db_dsn#">
		$query_update_accounts = "UPDATE  accounts
			SET	last_credit_date_time = '$current_date',
			balance	= '$lev4_nbal'
			WHERE	account_enc = '$get_level4_account_enc'";
		$query = $this->db->query($query_update_accounts);	
		//</cfquery>
			
		//<!---- RECORD FOR DISTRIBUTOR LEVEL 3 WITH COMMISSION ---->
		//<cfquery name="get_level3Com" datasource="#request.db_dsn#">
		$query_select_PROD_COMM = "SELECT * 
			FROM  PROD_COMM PC
			JOIN CARDS C
			ON PC.PROD_ID = C.PROD_ID
			JOIN PRODUCTS P
			ON C.PROD_ID = P.PROD_ID
			WHERE PC.ACCOUNT = '$get_level4_PARENT_ACCOUNT_ID'
			AND C.CARD_ID = '$CARD_ID_FROM_FORM'";
		$query = $this->db->query($query_select_PROD_COMM);
		$data = $query->result_array();
		$get_level3Com_PROD_COMM = $data[0]["PROD_COMM"];	
		//</cfquery>
	
		//<cfquery name="get_level3" datasource="#request.db_dsn#">
		$query_select_accounts = "SELECT * 
			FROM  accounts a
			JOIN customers c
			ON a.customer_id = c.customer_id
			JOIN ACCOUNT_GROUPS ag
			ON a.account_group_id = ag.account_group_id
			WHERE account_enc = '$get_level4_PARENT_ACCOUNT_ID'";
		$query = $this->db->query($query_select_accounts);
		$data = $query->result_array();
		$get_level3_BALANCE = $data[0]["BALANCE"];
		$get_level3_ACCOUNT_ID = $data[0]["ACCOUNT_ID"];
		$get_level3_ACCOUNT = $data[0]["ACCOUNT"];
		$get_level3_ACCOUNT_GROUP = $data[0]["ACCOUNT_GROUP"];
		$get_level3_PARENT_ACCOUNT_ID = $data[0]["PARENT_ACCOUNT_ID"];	
		$get_level3_account_enc = $data[0]["ACCOUNT_ENC"];
		//</cfquery>
	
		//<!---If regalo Payment then assign commission on the basis of $3--->
		if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15){
			$discount = ($apidiffFee*$get_level3Com_PROD_COMM)/100;
			$com3_val = $v_balance-$discount;
			$lev3_com = $v_balance-$com3_val;
			$lev3_nbal = $get_level3_BALANCE-$com3_val;
		} else {
			$com3_val = ($v_balance*$get_level3Com_PROD_COMM)/100;
			$lev3_com = $v_balance-$com3_val;
			$lev3_nbal = $get_level3_BALANCE-$lev3_com;
		}
	
		//<cfquery datasource="#request.db_dsn#">
		$query_insert_BILLING = "INSERT into BILLING 
			(call_session_id, entry_type, account_id, account, account_group, start_date_time, connect_date_time, disconnect_date_time, 
			login_name, node, node_type, description, detail, per_call_charge, per_minute_charge, per_call_surcharge, 
			per_minute_surcharge, actual_duration, quantity, amount,   rate_interval, 
			disconnect_charge, billing_delay, grace_period, account_type, parent_account_id, prod_id,card_id, user_7, user_8, user_9,ORIGIN,user_4,CURRENCY,CONVERSION_RATE,ACTUAL_AMOUNT)
			VALUES
			('$gen_code', '9', '$get_level3_ACCOUNT_ID', '$get_level3_ACCOUNT', '$get_level3_ACCOUNT_GROUP', '$current_date_time', '$current_date_time', 
			'$current_date_time', '".$_COOKIE['user_name']."', 'Nadi', '0', 'Purchase of Product #prod_name#', 'Item Id #card_id# for $actual_amount_currency', '0', '0', '0', '0',
			'0', '0'"; 
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15 ){
				$query_insert_BILLING .= ",'-$com3_val'";
			} else {
				$query_insert_BILLING .= ",'-$lev3_com'";
			}
			$query_insert_BILLING .= ", '0', '0', '0', '0', '3', '$get_level3_PARENT_ACCOUNT_ID', '#prod_id#', '#card_id#', '$v_balance'";
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15 ){
				$query_insert_BILLING .= ",'$lev3_com'";
			} else {
				$query_insert_BILLING .= ",'$com3_val'";
			}
			$query_insert_BILLING .= ", '$get_level3Com_PROD_COMM','0','$lev3_nbal','$currency','$CONVERSION_RATE','$ACTUAL_AMOUNT')";
		$query = $this->db->query($query_insert_BILLING);
		//</cfquery>
	
		//<cfquery datasource="#request.db_dsn#">
		$query_update_accounts = "UPDATE  accounts
			SET	last_credit_date_time = '$current_date',
			balance	= '$lev3_nbal'
			WHERE	account_enc = '$get_level3_account_enc'";
		$query = $this->db->query($query_update_accounts);		
		//</cfquery>
	
		//<!---- RECORD FOR MASTER LEVEL 2 WITH COMMISSION ---->
		//<cfquery name="get_level2Com" datasource="#request.db_dsn#">
		$query_select_PROD_COMM = "SELECT * 
			FROM  PROD_COMM PC
			JOIN CARDS C
			ON PC.PROD_ID = C.PROD_ID
			JOIN PRODUCTS P
			ON C.PROD_ID = P.PROD_ID
			WHERE PC.ACCOUNT = '$get_level3_PARENT_ACCOUNT_ID'
			AND C.CARD_ID = '$CARD_ID_FROM_FORM'";
		$query = $this->db->query($query_select_PROD_COMM);
		$data = $query->result_array();
		$get_level2Com_PROD_COMM = $data[0]["PROD_COMM"];
		//</cfquery>
	
		//<cfquery name="get_level2" datasource="#request.db_dsn#">
		$query_select_accounts = "SELECT * 
			FROM  accounts a
			JOIN customers c
			ON a.customer_id = c.customer_id
			JOIN ACCOUNT_GROUPS ag
			ON a.account_group_id = ag.account_group_id
			WHERE account_enc = '$get_level3_PARENT_ACCOUNT_ID'";
		$query = $this->db->query($query_select_accounts);
		$data = $query->result_array();
		$get_level2_BALANCE = $data[0]["BALANCE"];
		$get_level2_ACCOUNT_ID = $data[0]["ACCOUNT_ID"];
		$get_level2_ACCOUNT = $data[0]["ACCOUNT"];
		$get_level2_ACCOUNT_GROUP = $data[0]["ACCOUNT_GROUP"];
		$get_level2_PARENT_ACCOUNT_ID = $data[0]["PARENT_ACCOUNT_ID"];	
		$get_level2_account_enc = $data[0]["ACCOUNT_ENC"];	
		//</cfquery>
	
		//<!---If regalo Payment then assign commission on the basis of $3--->
		if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15){
			$discount = ($apidiffFee*$get_level2Com_PROD_COMM)/100;
			$com2_val = $v_balance-$discount;
			$lev2_com = $v_balance-$com2_val;
			$lev2_nbal = $get_level2_BALANCE-$com2_val;
		} else {
			$com2_val = ($v_balance*$get_level2Com_PROD_COMM)/100;
			$lev2_com = $v_balance-$com2_val;
			$lev2_nbal = $get_level2_BALANCE-$lev2_com;
		}
	
		//<cfquery datasource="#request.db_dsn#">
		$query_insert_BILLING = "INSERT into BILLING 
			(call_session_id, entry_type, account_id, account, account_group, start_date_time, connect_date_time, disconnect_date_time, 
			login_name, node, node_type, description, detail, per_call_charge, per_minute_charge, per_call_surcharge, 
			per_minute_surcharge, actual_duration, quantity, amount,   rate_interval, 
			disconnect_charge, billing_delay, grace_period, account_type, parent_account_id, prod_id, card_id, user_7, user_8, user_9,ORIGIN,user_4,CURRENCY,CONVERSION_RATE,ACTUAL_AMOUNT)
			VALUES
			('$gen_code', '9', '$get_level2_ACCOUNT_ID', '$get_level2_ACCOUNT', '$get_level2_ACCOUNT_GROUP', '$current_date_time', '$current_date_time', 
			'$current_date', '".$_COOKIE['user_name']."', 'Nadi', '0', 'Purchase of Product #prod_name#', 'Item Id #card_id# for $actual_amount_currency', '0', '0', '0', '0',
			'0', '0'";
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15 ){
				$query_insert_BILLING .= ",'-$com2_val'";
			} else {
				$query_insert_BILLING .= ",'-$lev2_com'";
			}
			$query_insert_BILLING .= ", '0', '0', '0', '0', '2', '$get_level2_PARENT_ACCOUNT_ID', '#prod_id#', '#card_id#', '$v_balance'";
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15 ){
				$query_insert_BILLING .= ",'$lev2_com'";
			} else {
				$query_insert_BILLING .= ",'$com2_val'";
			}
			$query_insert_BILLING .= ", '$get_level2Com_PROD_COMM','0','$lev2_nbal','$currency','$CONVERSION_RATE','$ACTUAL_AMOUNT')";
		$query = $this->db->query($query_insert_BILLING);	
		//</cfquery>
	
		//<cfquery datasource="#request.db_dsn#">
		$query_update_accounts = "UPDATE  accounts
			SET	last_credit_date_time = '$current_date',
			balance	= '$lev2_nbal'
			WHERE	account_enc = '$get_level2_account_enc'";
		$query = $this->db->query($query_update_accounts);		
		//</cfquery>
			
		//<!---- RECORD FOR OWNER LEVEL 1 WITH COMMISSION ---->
		//<cfquery name="get_level1Com" datasource="#request.db_dsn#">
		$query_select_cards = "SELECT * 
			FROM  PROD_COMM PC
			JOIN CARDS C
			ON PC.PROD_ID = C.PROD_ID
			JOIN PRODUCTS P
			ON C.PROD_ID = P.PROD_ID
			WHERE PC.ACCOUNT = '$get_level2_PARENT_ACCOUNT_ID'
			AND C.CARD_ID = '$CARD_ID_FROM_FORM'";
		$query = $this->db->query($query_select_cards);
		$data = $query->result_array();
		
		$get_level1Com_PROD_COMM = (isset($data[0]["PROD_COMM"])?$data[0]["PROD_COMM"]:0);
		$get_level1com_PROD_DISCOUNT = (isset($data[0]["PROD_DISCOUNT"])?$data[0]["PROD_DISCOUNT"]:0);
		//</cfquery>
	
		//<cfquery name="get_level1" datasource="#request.db_dsn#">
		$query_select_accounts = "SELECT * 
			FROM  accounts a
			JOIN customers c
			ON a.customer_id = c.customer_id
			JOIN ACCOUNT_GROUPS ag
			ON a.account_group_id = ag.account_group_id
			WHERE account_enc = '$get_level2_PARENT_ACCOUNT_ID'";
		$query = $this->db->query($query_select_accounts);
		$data = $query->result_array();
		$get_level1_BALANCE = $data[0]["BALANCE"];
		$get_level1_ACCOUNT_ID = $data[0]["ACCOUNT_ID"];
		$get_level1_ACCOUNT = $data[0]["ACCOUNT"];
		$get_level1_ACCOUNT_GROUP = $data[0]["ACCOUNT_GROUP"];
		$get_level1_PARENT_ACCOUNT_ID = $data[0]["PARENT_ACCOUNT_ID"];	
		$get_level1_account_enc = $data[0]["ACCOUNT_ENC"];	
		//</cfquery>
	
		//<!---If regalo Payment then assign commission on the basis of apidiffFee--->
		if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15){
			$discount = ($apidiffFee*$get_level1Com_PROD_COMM)/100;
			$com1_val = $v_balance-$discount;
			$lev1_com = $v_balance-$com1_val;
			$lev1_nbal = $get_level1_BALANCE-$com1_val;
		} else {
			$com1_val = ($v_balance*$get_level1Com_PROD_COMM)/100;
			$lev1_com = $v_balance-$com1_val;
			$lev1_nbal = $get_level1_BALANCE-$lev1_com;
		}
	
		//<cfquery datasource="#request.db_dsn#">
		$query_insert_BILLING = "INSERT into BILLING 
			(call_session_id, entry_type, account_id, account, account_group, start_date_time, connect_date_time, disconnect_date_time, 
			login_name, node, node_type, description, detail, per_call_charge, per_minute_charge, per_call_surcharge, 
			per_minute_surcharge, actual_duration, quantity, amount,   rate_interval, 
			disconnect_charge, billing_delay, grace_period, account_type, parent_account_id, prod_id, card_id, user_7, user_8, user_9,ORIGIN,user_4,CURRENCY,CONVERSION_RATE,ACTUAL_AMOUNT)
			VALUES
			('$gen_code', '9', '$get_level1_ACCOUNT_ID', '$get_level1_ACCOUNT', '$get_level1_ACCOUNT_GROUP', '$current_date_time', '$current_date_time', 
			'$current_date_time', '".$_COOKIE['user_name']."', 'Nadi', '0', 'Purchase of Product $prod_name', 'Item Id $card_id for $actual_amount_currency', '0', '0', '0', '0',
			'0', '0'"; 
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15 ){
				$query_insert_BILLING .= ",'-$com1_val'";
			} else {
				$query_insert_BILLING .= ",'-$lev1_com'";
			}
			$query_insert_BILLING .= ", '0', '0', '0', '0', '1', '$get_level1_PARENT_ACCOUNT_ID', '$prod_id', '$card_id', '$v_balance'"; 
			if(($prod_type_name == 5 AND $prod_provider == 5) OR $api_type == 15 ){
				$query_insert_BILLING .= ",'$lev1_com'";
			} else {
				$query_insert_BILLING .= ",'$com1_val'";
			}
			$query_insert_BILLING .= ", '$get_level1com_PROD_DISCOUNT','0','$lev1_nbal','$currency','$CONVERSION_RATE','$ACTUAL_AMOUNT')";
		$query = $this->db->query($query_insert_BILLING);
		//</cfquery>
	
		//<cfquery datasource="#request.db_dsn#">
		$query_update_accounts = "UPDATE  accounts
			SET	last_credit_date_time = '$current_date',
			balance	= '$lev1_nbal'
			WHERE	account_enc = '$get_level1_account_enc'";
		$query = $this->db->query($query_update_accounts);		
		//</cfquery>
	}
	
	
	public function getAll($countryId, $onlyActives = FALSE)
	{
		
		if(!empty($countryId))
		{
			$this->db->select('cty_id');
			$queryCountry = $this->db->get_where('country_list', array('ISO2_CODE' => $countryId));
			$rowCountry = $queryCountry->row();
			$countryId = $rowCountry->cty_id;
			
		}
		$this->db->select('*');
		$this->db->order_by('PROD_CODE_MAIN', 'asc');
		$this->db->order_by('PROD_NAME', 'asc');
		if(!empty($countryId))
		{
			$this->db->where('PROD_CODE_MAIN', $countryId);
		}
		if($onlyActives)
		{
			$this->db->where('PROD_STATUS', '1');
		}
		$query = $this->db->get('products');
		
		$rows = $query->result();
		foreach($rows as &$row)
		{
			if($row->PROD_CODE_MAIN != '')
			{
				$this->db->select('cty_name');
				$queryCountry = $this->db->get_where('country_list', array('CTY_id' => $row->PROD_CODE_MAIN));
				$rowCountry = $queryCountry->row();
				$row->countryName = $rowCountry->cty_name;
			}
			else
			{
				$row->countryName = 'n/a';
			}
			if($row->PROD_PROVIDER != '')
			{
				$this->db->select('n_provider');
				$queryProvider = $this->db->get_where('provider', array('np_id' => $row->PROD_PROVIDER));
				$rowProvider = $queryProvider->row();
				$row->providerName = $rowProvider->n_provider;
			}
			else
			{
				$row->providerName = 'n/a';
			}
			
			$cards = $this->getCards($row->PROD_ID,1,'online');
			
			$row->rangeMin = '1';
			$row->rangeMax = '100';
			$denomination_amount = 0;
			$card_face_arr = array();
			$card_ids_arr = array();
			if(!empty($cards)){
				foreach($cards as $card){
					if($card->FACE_VALUE != ''){
						$denomination_amount = $denomination_amount + $card->FACE_VALUE;
						$card_face_arr[] = $card->CARD_ID."|".$card->FACE_VALUE;
					}
					$card_ids_arr[]  = $card->CARD_ID;
				}
			}
			
			if($denomination_amount > 0){
				$row->fixed = implode($card_face_arr,",");
				$row->card_ids = implode($card_ids_arr, ",");
				$row->type ='f';
			}else{
				$row->type ='r';
				$row->fixed = '';
				$row->card_ids = implode($card_ids_arr, ",");
			}	
			
			
			if($row->fixed != '')
			{
				$row->denominations = strlen($row->fixed) <= 20 ? $row->fixed : substr($row->fixed, 0, 20).'...';
			}
			else
			{
				$row->denominations = 'From '.$row->rangeMin.' to '.$row->rangeMax;
			}
			
			
			if($row->PROD_SMALL_PIC != '' && is_file(UPLOADS_DIR.$row->PROD_SMALL_PIC))
			{
				$row->image = '<img src="'.base_url(UPLOADS.$row->PROD_SMALL_PIC).'" width="80">';
			}
			else
			{
				$row->image = '';
			}
			$row->statusClass = $row->PROD_STATUS == '1' ? '' : 'red lineThrough';
		}
		
		return $rows;
	}
	
	
	
	 public function getCards($prod_id,$check,$store=''){
	 	if( $check == 1){
        	$sql_select_products = "SELECT C.*,P.PROD_NAME,P.PROD_POSTER_PIC,P.PROD_DETAIL as descrption, P.PROD_CODE_MAIN,P.PROD_ACC_TYPE, P.PROD_ACC_MASK ,PROD_SIMCARD_REQ, PROD_SERIAL_REQ, PROD_AREACODE_REQ, PROD_ZIP_REQ  FROM CARDS C LEFT JOIN PRODUCTS P ON C.PROD_ID = P.PROD_ID WHERE C.PROD_ID = '$prod_id'";
			if (isset($store) and $store == "online"){
				$sql_select_products .=" and ENABLED = 1";
			}
			$sql_select_products .= " ORDER BY C.FACE_VALUE";
		}else{
			$sql_select_products = "SELECT PROD_NAME FROM PRODUCTS WHERE PROD_ID = '$prod_id'";
		}
        $query = $this->db->query($sql_select_products);
        $getProducts = $query->result();
		return $getProducts;    
    }

	
	function validateFundsWithAllLevels($v_balance){
		
		$level5 = '1';
		$rflag 	= '1';
		//CHECK STORE BALANCE 
		$query_select_accounts = "SELECT * 
			FROM  accounts
			WHERE ACCOUNT_ENC = '".$_COOKIE['user_account_id']."'";
		$query = $this->db->query($query_select_accounts);
		$data = $query->result_array();
		
		$get_level5_balance = $data[0]["BALANCE"];
		$get_level5_credit_limit = $data[0]["CREDIT_LIMIT"];
		$get_level5_parent_account_id = $data[0]["PARENT_ACCOUNT_ID"];
		
		if($get_level5_balance < $v_balance){
			//<!---check for credit limit--->
			if($get_level5_credit_limit > 0 AND (abs($get_level5_balance) + $v_balance > $get_level5_credit_limit)){
				return false;
			}
			elseif(!isset($get_level5_credit_limit) OR $get_level5_credit_limit == 0){
				$rflag = 0;
				$level5 = 0;
			}
		}
		
		if($level5 == 1){
			//CHECK SUB-DISTRIBUTOR BALANCE 
			$query_select_accounts = "SELECT * 
				FROM  accounts
				WHERE account = '$get_level5_parent_account_id'";
			$query = $this->db->query($query_select_accounts);
			$data = $query->result_array();
			$get_level4_balance = $data[0]["BALANCE"];
			$get_level4_credit_limit = $data[0]["CREDIT_LIMIT"];
			$get_level4_parent_account_id = $data[0]["PARENT_ACCOUNT_ID"];

			$level4 = 1;
			if($get_level4_balance < $v_balance){
				if($get_level4_credit_limit > 0 AND (abs($get_level4_balance) + $v_balance > $get_level4_credit_limit)){
					return false;
					//////////////////////////////
				} elseif(!isset($get_level4_credit_limit) OR $get_level4_credit_limit == 0){
					$rflag = 0;
					$level4 = 0;
				}
			}
	
			if($level4 == 1){
				//<!--- CHECK DISTRIBUTOR BALANCE ---->
				$query_select_accounts = "SELECT * 
					FROM  accounts
					WHERE account = '$get_level4_parent_account_id'";
				$query = $this->db->query($query_select_accounts);
				$data = $query->result_array();
				$get_level3_balance = $data[0]["BALANCE"];
				$get_level3_credit_limit = $data[0]["CREDIT_LIMIT"];
				$get_level3_parent_account_id = $data[0]["PARENT_ACCOUNT_ID"];
				//</cfquery>
				$level3 = 1;
				if($get_level3_balance < $v_balance){
					//<!---check for credit limit--->
					if($get_level3_credit_limit > 0 AND (abs($get_level3_balance) + $v_balance > $get_level3_credit_limit)){
						//////THIS IS PENDING///////////
						return false;
						///////////////////////
					} elseif(!isset($get_level3_credit_limit) OR $get_level3_credit_limit == 0){
						$rflag = 0;
						$level3 = 0;
					}
				}
		
				if($level3 == 1){
					//<!--- CHECK MASTER BALANCE ---->
					$query_select_accounts = "SELECT * 
						FROM  accounts
						WHERE account = '$get_level3_parent_account_id'";
					$query = $this->db->query($query_select_accounts);
					$data = $query->result_array();
					$get_level2_balance = $data[0]["BALANCE"];
					$get_level2_credit_limit = $data[0]["CREDIT_LIMIT"];
					$get_level2_parent_account_id = $data[0]["PARENT_ACCOUNT_ID"];
					//</cfquery>
					$level2 = 1;
					if($get_level2_balance < $v_balance){
						if($get_level2_credit_limit > 0 AND (abs($get_level2_balance) + $v_balance > $get_level2_credit_limit)){
							/////////////////THIS IS PENDING//////
							return false;//<!---Amount Exceeds credit limit.--->
							
							//////////////////////////////////
						} elseif(!isset($get_level2_credit_limit) OR $get_level2_credit_limit == 0){
							$rflag = 0;
							$level2 = 0;
						}
					}
				}
		
			}
	
		}
		if($rflag == 1){
			return true;
		}else{
			return false;
		}				
	}//end function
	
	
	function getCardProdDetails($card_id){
	
		$query_select_CARDS = "SELECT * 
			FROM  CARDS C
			JOIN PRODUCTS P
			ON C.PROD_ID = P.PROD_ID
			JOIN PRODUCT_TYPE PT
			ON P.PROD_TYPE_ID = PT.PROD_TYPE_ID
			WHERE C.CARD_ID = '$card_id'";
		$query = $this->db->query($query_select_CARDS);
		return $query->row();	
	
	}
	
	function getById($prod_id){
	
		$query_select_CARDS = "SELECT * 
			FROM  
			PRODUCTS P
			WHERE P.PROD_ID = '$prod_id'";
		$query = $this->db->query($query_select_CARDS);
		return $query->row();	
	
	}
	
	function addSoldEntry($prod_id,$item_id,$prod_provider,$prod_code,$current_date_time){
		$query_insert_PROD_SOLD =  "INSERT into PROD_SOLD
			(PROD_PROD_ID, PROD_VENDOR_ID, PROD_CARD_ID, PROD_CODE_ID, PROD_DATE, PROD_SOLD_BY)
		VALUES
			('$prod_id','$prod_provider', '$item_id', '$prod_code',
			'$current_date_time', '".$_COOKIE['user_account_id']."')";
		$query = $this->db->query($query_insert_PROD_SOLD);

		return $this->db->insert_id();
	}
	
}

