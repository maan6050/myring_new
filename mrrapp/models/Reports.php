<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class Reports extends CI_Model
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

    public function getChildRoles(){
        if($_COOKIE['user_type'] == "956314127503977533") {
            $role = "Master";
        } else if($_COOKIE['user_type'] == "638545125236524578"){
            $role = "Distributor";
        } else if($_COOKIE['user_type'] == "325210258618165451"){
            $role = "Sub-Distributor";
        } else if($_COOKIE['user_type'] == "125458968545678354"){
            $role = "Store";
        } else if($_COOKIE['user_type'] == "415285967837575867"){
            $role = "User";
        } else if($_COOKIE['user_type'] == "863252457813278645"){
            $role = "Clerk";
        } else {
            $role =  "";
        }
        return $role;
    }

    public function getRepSearchField($check=''){
        if(isset($check) AND $check == "2") {
            $sql_select_products =  "SELECT PROD_ID AS OP_VAL, PROD_NAME AS OP_NAME FROM PRODUCTS 
            WHERE PROD_STATUS = 1 ORDER BY PROD_NAME";
            $query = $this->db->query($sql_select_products);
            $repSearchField = $query->result();
        } else if(isset($check) AND $check == "3") {
            if($_COOKIE['user_type'] == "415285967837575867"){
                $sql_select_customer = "SELECT U.LOGIN_NAME AS OP_NAME, U.USER_ID AS OP_VAL
                FROM CUSTOMERS C JOIN USERS U ON U.CUSTOMER_ID_ENC = C.CUSTOMER_ENC
                WHERE C.CUSTOMER_ENC = '".$_COOKIE['user_account_id']."' ORDER BY U.LOGIN_NAME";
                $query = $this->db->query($sql_select_customer);
                $repSearchField = $query->result();
            } else {
                $sql_select_customer = "SELECT C.COMPANY AS OP_NAME, C.CUSTOMER_ID AS OP_VAL FROM 
                customers C JOIN accounts A ON(A.ACCOUNT_ENC = C.CUSTOMER_ENC) WHERE A.PARENT_ACCOUNT_ID = '".$_COOKIE['user_account_id']."' ORDER BY C.COMPANY";
                $query = $this->db->query($sql_select_customer);
                $repSearchField = $query->result();
            }
        } else if((isset($check)) AND (($check == "4") OR ($check == "5"))) {
            $sql_select_provider = " SELECT NP_ID AS OP_VAL , N_PROVIDER AS OP_NAME FROM PROVIDER 
            ORDER BY N_PROVIDER";
            $query = $this->db->query($sql_select_provider);
            $repSearchField = $query->result();
        } else {
            $sql_select_product_type = " SELECT PROD_TYPE_ID AS OP_VAL, PROD_TYPE_NAME AS OP_NAME
            FROM PRODUCT_TYPE ORDER BY PROD_TYPE_NAME";
            $query = $this->db->query($sql_select_product_type);
            $repSearchField = $query->result();
        }
        return $repSearchField;
    }

    public function getReports($check='',$sDate='',$eDate='',$user='',$export='',$loadFirst=''){
        $sql_select_account_type = "SELECT ACCOUNT_TYPE FROM account_types WHERE ACCOUNT_TYPE_ENC = '".$_COOKIE['user_type']."'";
        $query = $this->db->query($sql_select_account_type);
        $result = $query->result();
        $num = $result[0]->ACCOUNT_TYPE;

        if($_COOKIE['user_type'] == "525874964125375325"){
            $num = 1;
        }
        $sql_select_prod_sum = "SELECT PS.START_DATE_TIME, PS.PROD_ID, CR.FACE_VALUE, PT.PROD_TYPE_NAME,PSL.PROD_RECHARGE, C.COMPANY,C.CUSTOMER_ENC,PS.ENTRY_TYPE,PSL.PROD_RESPONSE,PSL.PROD_RESPONSE_INFO,PS.PROD_SOLD_ID,C.LOCAL_PHONE,PS.CARD_ID, P.PROD_NAME, PS.LOGIN_NAME, PV.N_PROVIDER, PS.AMOUNT - PS.L1_COMM AS COST, PS.l5_amount,";
        if($_COOKIE['user_type'] == "415285967837575867"){
            $sql_select_prod_sum .= "U.LOGIN_NAME AS CUSTOMER_NAME";
        } else {
            $sql_select_prod_sum .= "C.COMPANY AS CUSTOMER_NAME";
        }
        
        if(isset($export) AND $export == "1") {
            $sql_select_prod_sum .= " , 1 QUANTITY,PS.AMOUNT AS SUM_AMNT, PS.L".$num."_DISC AS AVG_DIS, PS.L".$num."_PROFIT  AS SUM_PRO"; 
        } else {
            if(($_COOKIE['user_type'] == "415285967837575867") OR ($_COOKIE['user_type'] == "125458968545678354") OR ($_COOKIE['user_type'] == "956314127503977533") AND ((isset($check) AND $check == "4"))) {
            $sql_select_prod_sum .= " ,1 AS QUANTITY,PS.AMOUNT AS SUM_AMNT, PS.L".$num."_DISC AS AVG_DIS, PS.L".$num."_PROFIT  AS SUM_PRO";
            } else {
                $sql_select_prod_sum .= " , COUNT(*) AS QUANTITY,SUM(PS.AMOUNT) AS SUM_AMNT, AVG(PS.L".$num."_DISC) AS AVG_DIS, SUM(PS.L".$num."_PROFIT)  AS SUM_PRO";
            }
        }
        $sql_select_prod_sum .= " FROM PROD_SUMM PS JOIN PRODUCTS P ON(P.PROD_ID = PS.PROD_ID) LEFT JOIN PROD_SOLD PSL ON(PSL.PROD_SOLD_ID=PS.PROD_SOLD_ID)LEFT JOIN CARDS CR ON(CR.CARD_ID = PS.CARD_ID)
        JOIN PRODUCT_TYPE PT ON(PT.PROD_TYPE_ID = P.PROD_TYPE_ID) JOIN PROVIDER PV ON(PV.NP_ID = P.PROD_PROVIDER) JOIN customers C ON(C.CUSTOMER_ENC = ";
        if($num < "5"){
            $num = $num+1;
            $sql_select_prod_sum .= " PS.L".$num."_ACCOUNT";
        } else {
            $sql_select_prod_sum .= " PS.ACCOUNT";
        }
        $sql_select_prod_sum .= ")";
        if(($_COOKIE['user_type'] == "415285967837575867")) {
            $sql_select_prod_sum .= " JOIN USERS U ON(U.LOGIN_NAME=PS.LOGIN_NAME)";
        }
        $sql_select_prod_sum .= " WHERE PS.L".$num."_ACCOUNT = '".$_COOKIE['user_account_id']."'";
            
        if(isset($sDate) AND $sDate !== "") {
            $sql_select_prod_sum .= " AND PS.START_DATE_TIME >= '".date('Y-m-d', strtotime($sDate))." ".date("H-m-s", strtotime($sDate))."'";
        }
        if(isset($eDate) AND $eDate !== "") {
            $sql_select_prod_sum .= " AND PS.START_DATE_TIME <= '".date('Y-m-d', strtotime($eDate))." ".date("H-m-s", strtotime($eDate))."'";
        }
        if(isset($user) AND $user !== "") {
            $sql_select_prod_sum .= " AND"; 
            if(isset($check) AND $check == "2") {
                $sql_select_prod_sum .= " PS.PROD_ID";
            } else if(isset($check) AND $check == "3") {
                if(($_COOKIE['user_type'] == "415285967837575867")) {
                    $sql_select_prod_sum .= " U.USER_ID";
                } else {
                    $sql_select_prod_sum .= " C.CUSTOMER_ID";
                }
            } else if(isset($check) AND ($check == "4") OR ($check == "5")) {
                $sql_select_prod_sum .= " P.PROD_PROVIDER";
            
            } else {
                $sql_select_prod_sum .= " P.PROD_TYPE_ID";
            }
            $sql_select_prod_sum .= "='".$user."'";
        }
        if((isset($provider) AND $provider !== "") AND (isset($_COOKIE['user_type']) AND $_COOKIE['user_type'] ==" 956314127503977533")) {    
            $sql_select_prod_sum .= " AND P.PROD_PROVIDER = '".$provider."'";
        }
        
        if((isset($export) AND $export == "1")){

        } else {
            if(($_COOKIE['user_type'] !== "415285967837575867") AND ($_COOKIE['user_type'] !== "125458968545678354")) {
                if(isset($check) AND $check == "2") {
                    $sql_select_prod_sum .= " GROUP BY PS.PROD_ID, PS.CARD_ID";
                    
                } else if(isset($check) AND $check == "3"){
                    $sql_select_prod_sum .= " GROUP BY PS.PROD_ID, PS.CARD_ID, PS.LOGIN_NAME";
                } else if(isset($check) AND $check == "4"){
                    if(isset($_COOKIE['user_type']) AND $_COOKIE['user_type'] !== "956314127503977533") {
                        $sql_select_prod_sum .= " GROUP BY P.PROD_PROVIDER";
                    }
                } else if(isset($check) AND $check == "5"){
                    $sql_select_prod_sum .= " GROUP BY P.PROD_PROVIDER, P.PROD_ID, CR.CARD_ID"; 
                } else {
                    $sql_select_prod_sum .= " GROUP BY PS.PROD_ID";
                }
            }
        }
        if(isset($loadFirst) AND ($loadFirst == "1")) {
            $sql_select_prod_sum .= " limit 25";
        }
        $query = $this->db->query($sql_select_prod_sum);
        $getReport = $query->result();
        return $getReport;
    }

    public function getallUsersList(){
        $sql_select_account_summary = "SELECT * FROM ACCOUNT_SUMMARY";
        if($_COOKIE['user_type'] !== 956314127503977533){
            $sql_select_account_summary .= " WHERE parent_account_id= '".$_COOKIE['user_account_id']."'";
        }
        $sql_select_account_summary .= " ORDER BY ACCOUNT_TYPE";
        $query = $this->db->query($sql_select_account_summary);
        $getReport = $query->result();
        return $getReport;
    }

    public function getprodFeeProducts($check,$sDate='',$eDate='',$user='',$PROD_SOLD_ID='0'){
        $sql_select_prod_sold = "SELECT * FROM PROD_SOLD PS WHERE PROD_PARENT_SOLD_ID = $PROD_SOLD_ID";
        $query = $this->db->query($sql_select_prod_sold);
        $result = $query->result_array();
        
        $get_sold_id = $result[0]["PROD_SOLD_ID"];
        $recordcount = $query->num_rows();

        if($recordcount > "0"){
            $sql_select_account_type = "SELECT ACCOUNT_TYPE FROM account_types WHERE ACCOUNT_TYPE_ENC = '".$_COOKIE['user_type']."'";
            $query = $this->db->query($sql_select_account_type);
            $account_type = $query->result_array();
            $num = $account_type[0]["ACCOUNT_TYPE"];
            if($_COOKIE['user_type'] == "525874964125375325"){
                $num = "1";
            }
            $sql_select_prod_summ = "SELECT PS.START_DATE_TIME, PS.PROD_ID, CR.FACE_VALUE, PT.PROD_TYPE_NAME,PSL.PROD_RECHARGE, C.COMPANY,C.CUSTOMER_ENC,PS.ENTRY_TYPE,PSL.PROD_RESPONSE,PSL.PROD_RESPONSE_INFO,PS.PROD_SOLD_ID,C.LOCAL_PHONE,PS.CARD_ID, P.PROD_NAME, PS.LOGIN_NAME, PV.N_PROVIDER, PS.AMOUNT - PS.L1_COMM AS COST, PS.l5_amount,";
            if($_COOKIE['user_type'] == "415285967837575867"){
                $sql_select_prod_summ .=" U.LOGIN_NAME AS CUSTOMER_NAME";
            } else {
                $sql_select_prod_summ .=" C.COMPANY AS CUSTOMER_NAME";
            }
            if(isset($export) AND $export == "1") {
                $sql_select_prod_summ .=" , 1 QUANTITY,PS.AMOUNT AS SUM_AMNT, PS.L".$num."_DISC AS AVG_DIS, PS.L".$num."_PROFIT  AS SUM_PRO";
            } else {
                if(($_COOKIE['user_type'] == "415285967837575867") OR ($_COOKIE['user_type'] == "125458968545678354") OR ($_COOKIE['user_type'] == "956314127503977533") AND (isset($check)) AND ($check == "4")){
                    $sql_select_prod_summ .=" ,1 AS QUANTITY,PS.AMOUNT AS SUM_AMNT, PS.L".$num."_DISC AS AVG_DIS, PS.L".$num."_PROFIT  AS SUM_PRO";
                } else {
                    $sql_select_prod_summ .=" , COUNT(*) AS QUANTITY,SUM(PS.AMOUNT) AS SUM_AMNT, AVG(PS.L".$num."_DISC) AS AVG_DIS, SUM(PS.L".$num."_PROFIT)  AS SUM_PRO";
                }
            }
            $sql_select_prod_summ .=" FROM PROD_SUMM PS JOIN PRODUCTS P ON(P.PROD_ID = PS.PROD_ID)LEFT JOIN PROD_SOLD PSL ON(PSL.PROD_SOLD_ID=PS.PROD_SOLD_ID)LEFT JOIN CARDS CR ON(CR.CARD_ID = PS.CARD_ID)
            JOIN PRODUCT_TYPE PT ON(PT.PROD_TYPE_ID = P.PROD_TYPE_ID)left JOIN PROVIDER PV ON(PV.NP_ID = P.PROD_PROVIDER) left JOIN CUSTOMERS C ON(C.CUSTOMER_ENC = ";
            if($num < "5"){
                $num = $num+1;
                $sql_select_prod_summ .=" PS.L".$num."_ACCOUNT";
            }else {
                $sql_select_prod_summ .=" PS.ACCOUNT";
            }
            if($_COOKIE['user_type'] == "415285967837575867"){
                $sql_select_prod_summ .=" JOIN USERS U ON(U.LOGIN_NAME=PS.LOGIN_NAME)";
            }
            $sql_select_prod_summ .=" WHERE PS.PROD_SOLD_ID = '$get_sold_id'";
                
            if(isset($sDate) AND $sDate !== "") {
                $sql_select_prod_summ .= " AND PS.START_DATE_TIME >= '".date('Y-m-d', strtotime($sDate))." ".date("H-m-s", strtotime($sDate))."'";
            }
            if(isset($eDate) AND $eDate !== "") {
                $sql_select_prod_summ .= " AND PS.START_DATE_TIME <= '".date('Y-m-d', strtotime($eDate))." ".date("H-m-s", strtotime($eDate))."'";
            }
            $query = $this->db->query($sql_select_prod_sold);
            $getReport = $query->result();
            return $getReport;
        } else {
            return $get_sold_id;
        }    
    }

    public function getUnsuccessfulTrans($sDate='',$eDate=''){
        
        $sql_select_prod_sold = "SELECT * FROM PROD_SOLD as ps JOIN PRODUCTS as p on p.PROD_ID=ps.PROD_PROD_ID JOIN PRODUCT_TYPE as pt on pt.PROD_TYPE_ID=p.PROD_TYPE_ID
        JOIN PROVIDER pr on pr.NP_ID = ps.PROD_VENDOR_ID JOIN CARDS cr ON cr.CARD_ID = ps.PROD_CARD_ID
        JOIN customers C on ps.PROD_SOLD_BY = C.CUSTOMER_ENC LEFT JOIN PROD_SUMM psu on psu.PROD_SOLD_ID = ps.PROD_SOLD_ID WHERE 1=1";
        if(isset($sDate) AND ($sDate !== "")){
            $sql_select_prod_sold .=" AND ps.PROD_DATE >= '".date('Y-m-d', strtotime($sDate))." ".date("H-m-s", strtotime($sDate))."'"; 
        }
        if(isset($eDate) AND ($eDate !== "")){
            $sql_select_prod_sold .=" AND ps.PROD_DATE <= '".date('Y-m-d', strtotime($eDate))." ".date("H-m-s", strtotime($eDate))."'";
        }
        $sql_select_prod_sold .=" AND ps.PROD_RESPONSE != 0 ORDER BY ps.PROD_DATE DESC";
        $query = $this->db->query($sql_select_prod_sold); 
        $getUnSuccessfulReport = $query->result();
        return $getUnSuccessfulReport;  
    }

    public function getProdSoldReport($phone_number,$sDate='',$eDate='',$sucess_trans,$productType='',$recordsCount=''){
        $sql_select_prod_sold ="SELECT"; 
        if(isset($recordsCount) AND ($recordsCount !== "")){
            $sql_select_prod_sold .= " ps.PROD_RECHARGE,p.PROD_ID, p.PROD_NAME,ps.PROD_SOLD_ID,p.PROD_TYPE_ID";
        } else {
            $sql_select_prod_sold .= " ps.PROD_RECHARGE, 
            p.PROD_NAME,pt.PROD_TYPE_NAME ,ps.PROD_DATE as start_date_time,store.COMPANY as storename,p.PROD_TYPE_ID,p.PROD_ID,
            pr.NP_SHORT,p.PROD_DETAIL,cr.FACE_VALUE,
            c.LOGIN_NAME,c.COMPANY, cr.CARD_ID, ps.PROD_SOLD_ID, psu.AMOUNT, psu.L5_AMOUNT, psu.L5_DISC, psu.L5_COMM, psu.L7_TAX, psu.L7_TAX_AMOUNT, psu.L5_BALANCE,psu.L5_ACCOUNT, psu.L1_AMOUNT, psu.L1_DISC, psu.L1_COMM, psu.L1_BALANCE, ps.PROD_RECHARGE, psu.L1_PROFIT, ps.PROD_STATUS, psu.currency, psu.conversion_rate,psu.actual_amount,ps.PROD_PARENT_SOLD_ID";
        }
        $sql_select_prod_sold .=" FROM PROD_SOLD as ps
        LEFT JOIN customers AS c ON c.CUSTOMER_ENC = ps.PROD_ACCOUNT_ID
        LEFT JOIN customers AS store ON store.CUSTOMER_ENC = ps.PROD_SOLD_BY
        LEFT JOIN PRODUCTS AS p ON p.PROD_ID = ps.PROD_PROD_ID
        LEFT JOIN PRODUCT_TYPE AS pt ON pt.PROD_TYPE_ID = p.PROD_TYPE_ID
        LEFT JOIN PROVIDER pr ON pr.NP_ID = ps.PROD_VENDOR_ID
        LEFT JOIN CARDS cr ON cr.CARD_ID = ps.PROD_CARD_ID";
        
        if(($phone_number == "") AND ($_COOKIE['user_type'] !== "956314127503977533")){
            $sql_select_prod_sold .=" LEFT JOIN accounts as a ON a.ACCOUNT_ENC = c.CUSTOMER_ENC";
        }
        
        if($sucess_trans == "0") {
            $sql_select_prod_sold .=" LEFT"; 
        }
        $sql_select_prod_sold .=" LEFT JOIN PROD_SUMM psu on psu.PROD_SOLD_ID = ps.PROD_SOLD_ID";

        if(($_COOKIE['user_type'] == "415285967837575867") OR ($_COOKIE['user_type'] == "258968745812378564")){
            if($phone_number == "") {
                $sql_select_prod_sold .=" LEFT JOIN PROD_COMM pc ON pc.PROD_ID = p.PROD_ID";
            }
        }

        $sql_select_prod_sold .=" WHERE 1=1";
        // <!---<cfif arguments.phone_number NEQ "">
        // and c.LOGIN_NAME = '#arguments.phone_number#' 
        // </cfif>
        // <!---WM REMOVED CONDITION TO CHECK FOR LOGIN NAME FOR ONLY STORE END USERS--->
        // <cfif cookie.user_type NEQ 956314127503977533 AND cookie.user_type NEQ 415285967837575867>
        // 	AND a.PARENT_ACCOUNT_ID = '#COOKIE.USER_ACCOUNT_ID#'
        // </cfif>
        // <!---If stroe get only products that are assigned to it.--->
        // <cfif cookie.user_type IS 415285967837575867>
        // 	<cfif arguments.phone_number NEQ "">
        // 		AND pc.ACCOUNT = '#COOKIE.USER_ACCOUNT_ID#'
        // 	</cfif>
        // </cfif>--->
        if(($_COOKIE['country'] !== "1") AND ($_COOKIE['user_type'] == "415285967837575867") OR ($_COOKIE['user_type'] == "258968745812378564")) {
            $sql_select_prod_sold .=" and p.PROD_TYPE_ID in('".$_COOKIE['user_prod_types']."')";
        }
        
        if($phone_number !== "") {
            if(isset($recordsCount) AND ($recordsCount !== "")){
                $sql_select_prod_sold .=" and (c.LOGIN_NAME = '$phone_number' or c.LOGIN_NAME = '1".$phone_number."' )";
            } else {
                $sql_select_prod_sold .=" and c.LOGIN_NAME = '$phone_number'";
            }
        } else {
            if(($_COOKIE['user_type'] !== "956314127503977533") AND ($_COOKIE['user_type'] !== "415285967837575867") AND ($_COOKIE['user_type'] !== "525874964125375325") AND ($_COOKIE['user_type'] !== "258968745812378564")){
                
                $sql_select_prod_sold .=" AND a.PARENT_ACCOUNT_ID = '".$_COOKIE["USER_ACCOUNT_ID"]."'";
                
            }
            if(($_COOKIE['user_type'] == "415285967837575867") OR ($_COOKIE['user_type'] == "258968745812378564")){
            
                $sql_select_prod_sold .=" and (psu.l5_account= '".$_COOKIE["USER_ACCOUNT_ID"]."' or ps.PROD_SOLD_BY= '".$_COOKIE["USER_ACCOUNT_ID"]."')";
            }
            
        }
        
        if(($_COOKIE['user_type'] == "415285967837575867") or ($_COOKIE['user_type'] == "258968745812378564")){
            if($phone_number !== "") {
                $sql_select_prod_sold .=" AND pc.ACCOUNT = ".$_COOKIE["USER_ACCOUNT_ID"]."'";
            }
        }
        if(isset($phone_number) AND ($phone_number == "")) {
            if(isset($sDate) AND ($sDate !== "")) {
                $sql_select_prod_sold .=" AND ps.PROD_DATE >= '".date('Y-m-d', strtotime($sDate))." ".date("H-m-s", strtotime($sDate))."'";
            }
            if(isset($eDate) AND ($eDate !== "")){
                $sql_select_prod_sold .=" AND ps.PROD_DATE <= '".date('Y-m-d', strtotime($eDate))." ".date("H-m-s", strtotime($eDate))."'";
            }
        }
        if($sucess_trans == "0") {
            $sql_select_prod_sold .=" AND ps.PROD_RESPONSE != 0"; 
        } else {
            $sql_select_prod_sold .=" AND ps.PROD_RESPONSE = 0"; 
            
        }
        if(isset($productType) AND ($productType !== "")){
            $sql_select_prod_sold .=" AND p.PROD_TYPE_ID= '$productType'";
        }
        if(isset($recordsCount) AND ($recordsCount !== "")){
            $sql_select_prod_sold .=" group by ps.PROD_RECHARGE,p.PROD_ID,p.PROD_TYPE_ID";
        
        }                    
        $sql_select_prod_sold .=" ORDER BY ps.PROD_DATE DESC";
        if(isset($recordsCount) AND ($recordsCount !== "")){
            $sql_select_prod_sold .=" Limit $recordsCount";
        }

        $query = $this->db->query($sql_select_prod_sold); 
        $getRecords = $query->result();
		return $getRecords;
    }
}