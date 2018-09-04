<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class Records extends CI_Model
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

    public function getLogReports($sDate='',$eDate=''){
        $sql_select_log_table = "SELECT LT.*, U.FIRST_NAME, U.LAST_NAME,act.description as level from LOG_TABLE LT LEFT JOIN users U ON (LT.ACCOUNT_ID = U.CUSTOMER_ID_ENC) join account_types as act on U.USER_TYPE= act.ACCOUNT_TYPE WHERE LT.ACCOUNT_ID = '".$_COOKIE['user_account_id']."'";
        if(isset($sDate) AND ($sDate !== "")){
            $sql_select_log_table .= " AND LT.ACTIVITY_TIME >= '".date('Y-m-d', strtotime($sDate))." ".date("H-m-s", strtotime($sDate))."'"; 
        }
        if(isset($eDate) AND ($eDate !== "")){
            $sql_select_log_table .= " AND LT.ACTIVITY_TIME <= '".date('Y-m-d', strtotime($eDate))." ".date("H-m-s", strtotime($eDate))."'"; 
        }
        $query = $this->db->query($sql_select_log_table);
        $getreports = $query->result();
        return $getreports;
    }

    public function getBillingReports($type,$sDate,$eDate){
        $sql_select_customers_table = "SELECT C.COMPANY, et.DESCRIPTION AS ENTRY_TYPE_NAME, B.* FROM BILLING B
        JOIN customers C ON B.ACCOUNT=C.CUSTOMER_ENC LEFT JOIN ENTRY_TYPES et ON et.ENTRY_TYPE = B.ENTRY_TYPE 
        WHERE"; 
        if(isset($type) AND ($type !== "")){
            $sql_select_customers_table .= " B.PARENT_ACCOUNT_ID ='".$_COOKIE['user_account_id']."'"; 
        } else {
            $sql_select_customers_table .= " B.ACCOUNT ='".$_COOKIE['user_account_id']."'"; 
        }
        if(isset($sDate) AND ($sDate !== "")){
            $sql_select_customers_table .= " AND B.START_DATE_TIME >= '".date('Y-m-d', strtotime($sDate))." ".date("H-m-s", strtotime($sDate))."'"; 
        }
        if(isset($eDate) AND ($eDate !== "")){
            $sql_select_customers_table .= " AND B.START_DATE_TIME <= '".date('Y-m-d', strtotime($eDate))." ".date("H-m-s", strtotime($eDate))."'";
        }
        if(isset($type) AND ($type !== "0")){
            $sql_select_customers_table .= " AND B.ENTRY_TYPE IN ('$type')";
        } else {
            $sql_select_customers_table .= " AND B.ENTRY_TYPE in (1,2,6)";
        }
        $query = $this->db->query($sql_select_customers_table);
        $getreports = $query->result();
        return $getreports;
    }

    public function getBillingReportsForAllLevels($sDate,$eDate,$acc,$acc_type){
        $sql_select_customers = "SELECT C.COMPANY, et.DESCRIPTION AS ENTRY_TYPE_NAME, B.* FROM BILLING B
        JOIN customers C ON B.ACCOUNT=C.CUSTOMER_ENC
        LEFT JOIN ENTRY_TYPES et ON et.ENTRY_TYPE = B.ENTRY_TYPE 
        WHERE"; 
        if(isset($acc_type) AND  ($acc_type > 1)){
            if(isset($acc) AND  ($acc !== 0)){
                $sql_select_customers .=" B.ACCOUNT ='$acc'";
            } else {
                if(isset($type) AND  ($type !== 0)){
                    $sql_select_customers .=" B.PARENT_ACCOUNT_ID ='".$_COOKIE['user_account_id']."'"; 
                } else {
                    $sql_select_customers .=" B.ACCOUNT ='".$_COOKIE['user_account_id']."'"; 
                }
            }
        } else {
            $sql_select_customers .=" 1=0";
        }
        if(isset($sDate) AND ($sDate !== "")){
            $sql_select_customers .=" AND B.START_DATE_TIME >= '".date('Y-m-d', strtotime($sDate))." ".date("H-m-s", strtotime($sDate))."'"; 
        }
        if(isset($eDate) AND ($eDate !== "")){
            $sql_select_customers .=" AND B.START_DATE_TIME <= '".date('Y-m-d', strtotime($eDate))." ".date("H-m-s", strtotime($eDate))."'";
        }
        $sql_select_customers .=" ORDER BY BILLING_ID DESC";
        $query = $this->db->query($sql_select_customers);
        $getreports = $query->result();
        return $getreports;
    }
}