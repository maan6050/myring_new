<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class Tickets extends CI_Model
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

    public function get_reasons($displayAll=''){
        
        $sql_select_ticket_reason = "SELECT * from TICKET_REASONS";

        if(empty($displayAll)){ 
            $sql_select_ticket_reason .= " where reason != 'Products complaint'"; 
        }

        $query = $this->db->query($sql_select_ticket_reason);
		$get_reason = $query->result();
            
        return $get_reason;
    }
    public function get_reasons_history($parent_id='',$reason=0,$issue=0,$type=0,$isread='',$subject=''){
    
		$sql_select_tickets = "SELECT t.*,t.Createdat as Create_date,tr.*,c.COMPANY,`at`.DESCRIPTION from TICKETS as t
        join TICKET_REASONS as tr on t.ReasonID=tr.reason_id 
        join customers as c on c.CUSTOMER_ENC = t.CustomerID
        JOIN accounts as a on a.ACCOUNT_ENC = t.CustomerID
        JOIN account_types as at on `at`.ACCOUNT_TYPE= a.ACCOUNT_TYPE

        WHERE 1=1";
        if(!isset($_COOKIE["user_type"]) OR (isset($_COOKIE["user_type"]) AND ($_COOKIE["user_type"] != 956314127503977533)) AND (isset($_COOKIE["user_type"]) AND ($_COOKIE["user_type"] != 525874964125375325))){
            $sql_select_tickets .=" AND (t.CustomerID = '".$_COOKIE["user_account_id"]."' OR commentBy = '".$_COOKIE["user_account_id"]."')";
        }
        if(isset($parent_id) AND ($parent_id !== "")){
            $sql_select_tickets .=" AND t.ParentTicket =$parent_id";
        } else {
            $sql_select_tickets .=" AND t.ParentTicket =0"; 
        }
        if(!isset($_COOKIE["user_type"]) OR (isset($_COOKIE["user_type"]) AND ($_COOKIE["user_type"] == 956314127503977533)) OR ($_COOKIE["user_type"] == 525874964125375325)){

            if(isset($parent_id) AND ($parent_id !== "")){
                if(isset($reason) AND ($reason !== "")){
                    $sql_select_tickets .=" AND t.ReasonID= '$reason'";
                    
                }
                if(isset($issue) AND ($issue !== "")){
                    if($issue == 0 OR $issue == 1){
                        $sql_select_tickets .=" AND t.mandatory = 0";
                        $sql_select_tickets .=" AND t.TicketStatus = '$issue'";
                    }else if($issue == 2){
                        $sql_select_tickets .=" AND t.mandatory = 1";    
                    }
                }
                if(isset($isread) AND ($isread !== "")){
                    $sql_select_tickets .=" AND t.isread ='$isread' AND t.CustomerID = '".$_COOKIE["user_account_id"]."'";
                }
                if(isset($type) AND ($type !== "")){
                    $sql_select_tickets .=" AND a.ACCOUNT_TYPE = '$type'";
                }
            }
        }
        $sql_select_tickets .=" ORDER BY t.id, t.subject";
		$query = $this->db->query($sql_select_tickets);
        $get_reason = $query->result();
        
        if(!isset($_COOKIE["user_type"]) OR (isset($_COOKIE["user_type"]) AND ($_COOKIE["user_type"] == 956314127503977533)) OR ($_COOKIE["user_type"] == 525874964125375325)){
            if(isset($issue) AND ($issue == 2)){
            	if(isset($subject) AND ($subject !== "")){
                    $sql_select_tickets = "SELECT t.*,t.Createdat as Create_date,tr.*,c.COMPANY,`at`.DESCRIPTION from TICKETS as t
                    join TICKET_REASONS as tr on t.ReasonID=tr.reason_id 
                    join customers as c on c.CUSTOMER_ENC = t.CustomerID
                    JOIN accounts as a on a.ACCOUNT_ENC = t.CustomerID
                    JOIN account_types as at on `at`.ACCOUNT_TYPE= a.ACCOUNT_TYPE
                    where `subject` = '$subject' and mandatory = 1 and IsRead = $isread";
                    $query = $this->db->query($sql_select_tickets);
                    $get_reason = $query->result();
                } else {
                    $sql_select_tickets = "SELECT SUBJECT, count(*) as total_tt, sum(isread) as readable, (count(*)-sum(isread)) as notread FROM TICKETS WHERE mandatory = 1 GROUP BY `subject`";
                    $query = $this->db->query($sql_select_tickets);
                    $get_reason = $query->result();
                }
            }
        }
		return $get_reason;
    }
    public function getlevels($cservice=''){
        
        if($_COOKIE["user_type"] == 956314127503977533){
            $role = 1;
        } elseif($_COOKIE["user_type"] == 638545125236524578){
            $role = 2;
        } elseif($_COOKIE["user_type"] == 325210258618165451){
            $role = 3;
        } elseif($_COOKIE["user_type"] == 125458968545678354){
            $role = 4;
        } elseif($_COOKIE["user_type"] == 415285967837575867){
            $role = 5;
        } elseif($_COOKIE["user_type"] == 863252457813278645){
            $role = 6;
        }

        if($_COOKIE["user_type"] == 525874964125375325 AND (isset($cservice) AND $cservice == 1)){
            $role = 1;
        }
        $parent_role = $role -1;

        $sql_select_account_types = "SELECT * FROM account_types";  
        if(isset($role) AND ($role !== "")){
            $sql_select_account_types .=" WHERE ACCOUNT_TYPE > '$role' AND ACCOUNT_TYPE <= 5"; 
        }     
        $sql_select_account_types .=" ORDER BY ACCOUNT_TYPE";
        $query = $this->db->query($sql_select_account_types);
        $get_roles = $query->result();
        return $get_roles;
    }

    /*public function create_ticket($form){
        
        if($form["reasons"] !== ""){
            <cfquery name="get_admin" datasource="#request.db_dsn#" >
                $sql_select_accounts = "SELECT * FROM accounts WHERE ACCOUNT_ID=1";
            </cfquery>
            if(isset($form["ticket_id"]) AND ($form["ticket_id"] !== "")){
                $ticket_parent = $form["ticket_id"];
            } else {
                $ticket_parent = 0;
            }
            <cfparam name="form.customer" default="">
            <cfparam name="form.ticket_type" default="Message">
            <cfparam name="form.PARENT_CUSTOMER" default="">
            
            <cfparam name="form.LEVELS" default="">	
            if(!isset($_COOKIE["user_type"]) OR isset($_COOKIE["user_type"]) AND ($_COOKIE["user_type"] !== 956314127503977533)){
                $customer = $form["customer"];
            } else {
                $customer = $form["customer"];
            }
            
            $lastUser = ListLast(customer,",");
            $oneSender = 0;
            if($lastUser == "none"){
                $oneSender = 1;
                $lastUser = ListGetAt(customer,ListLen(customer)-1,",");
            }
        
            if($form["ticket_type"] == "Ticket"){
                //$acc_enc = $this->customers->customEncryptFunction($get_admin[0]->ACCOUNT_ENC);
                $acc_enc = $get_admin[0]->ACCOUNT_ENC;
                $lastUser = $acc_enc;
            }
            if($lastUser == "" ){
                if($_COOKIE["user_type"] == 956314127503977533 OR $_COOKIE["user_type"] == 525874964125375325){
                    $role = 1;
                }else if($_COOKIE["user_type"] == 638545125236524578){
                    $role = 2;
                }else if($_COOKIE["user_type"] == 325210258618165451){
                    $role = 3;
                }else if($_COOKIE["user_type"] == 125458968545678354){
                    $role = 4;
                }else if($_COOKIE["user_type"] == 415285967837575867){
                    $role = 5;
                }else if($_COOKIE["user_type"] == 863252457813278645){
                    $role = 6;
                }
                $selectall= 0;
                if($form["LEVELS"] == ""){
                    $selectall= 1;
                }
                if($form["LEVELS"] < $role){
                    $form["LEVELS"] = $role;
                }else if($form["LEVELS"]  > 5 ){
                    $form["LEVELS"] = 5;
                }
                <cfobject name="utils" component="cfc.Utils">
                
                if($role == 1){
                    if($selectall == 1){
                        $parent = "and A.ACCOUNT_TYPE = '".$form["LEVELS"]."' OR A.ACCOUNT_TYPE in(2,3,4,5)";
                    } else {
                        $parent= "and A.ACCOUNT_TYPE = '".$form["LEVELS"]."'";
                    }   
                } else {
                    if($form["PARENT_CUSTOMER"] !== ""){
                        $parent = $this->customers->getParentAcc($cust_acc = $_COOKIE["user_account_id"],$field="CUSTOMER_ENC");
                        $parent = "and A.ACCOUNT_ENC = $parent->PARENT_ACCOUNT_ID";
                    } else {
                        if($selectall == 1){
                            $parent = $this->customers->getParentAcc($cust_acc=$_COOKIE["user_account_id"],$field="CUSTOMER_ENC");
                            $parent = "AND (A.ACCOUNT_ENC = $parent->PARENT_ACCOUNT_ID OR PARENT_ACCOUNT_ID= $_COOKIE["user_account_id"])";
                        } else {
                            $parent = "and A.ACCOUNT_TYPE = '".$form["LEVELS"]."' and (PARENT_ACCOUNT_ID= '".$_COOKIE["user_account_id"]."')";
                        }
                    }   
                }
                
                <cfquery name="getusers" datasource="#request.db_dsn#">
                    $sql_select_users = 'SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.*,U.FIRST_NAME,U.LAST_NAME,U.LOCAL_PHONE,U.E_MAIL,`AT`.DESCRIPTION,U.USER_ID FROM users U
                            JOIN accounts A ON (A.ACCOUNT_ENC = U.CUSTOMER_ID_ENC)
                            JOIN customers C
                                ON C.CUSTOMER_ENC = U.CUSTOMER_ID_ENC
                            JOIN account_types AT on U.USER_TYPE= `AT`.ACCOUNT_TYPE
                    WHERE A.ACCOUNT_TYPE <= 5  #parent#';
                </cfquery>    
            } else {
                $acc_enc = $this->customers->customDecryptFunction($lastUser);

                if($form["ticket_type"] == "Ticket") {
                    <cfquery name="getusers" datasource="#request.db_dsn#">
                    $sql_select_users = "SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.*,U.FIRST_NAME,U.LAST_NAME,U.LOCAL_PHONE,U.E_MAIL,`AT`.DESCRIPTION,U.USER_ID FROM users U
                            JOIN accounts A ON (A.ACCOUNT_ENC = U.CUSTOMER_ID_ENC)
                            JOIN customers C
                                ON C.CUSTOMER_ENC = U.CUSTOMER_ID_ENC
                            JOIN ACCOUNT_TYPES AT on U.USER_TYPE= `AT`.ACCOUNT_TYPE
                        WHERE C.CUSTOMER_ENC = '$acc_enc'"; 
                    </cfquery>
                } else {
                    if($oneSender == 1){
                        <cfquery name="getusers" datasource="#request.db_dsn#">
                            $sql_slect_users = "SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.*,U.FIRST_NAME,U.LAST_NAME,U.LOCAL_PHONE,U.E_MAIL,`AT`.DESCRIPTION,U.USER_ID FROM users U
                            JOIN accounts A ON (A.ACCOUNT_ENC = U.CUSTOMER_ID_ENC)
                            JOIN customers C
                                ON C.CUSTOMER_ENC = U.CUSTOMER_ID_ENC
                            JOIN ACCOUNT_TYPES AT on U.USER_TYPE= `AT`.ACCOUNT_TYPE
                            WHERE C.CUSTOMER_ENC = '$acc_enc'";
                        </cfquery>
                    } else {
                        <cfquery name="getusers" datasource="#request.db_dsn#">
                        $sql_slect_users = "SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.*,U.FIRST_NAME,U.LAST_NAME,C.LOCAL_PHONE,U.E_MAIL,`AT`.DESCRIPTION,U.USER_ID FROM users U
                            JOIN accounts A ON (A.ACCOUNT_ENC = U.CUSTOMER_ID_ENC)
                            JOIN customers C
                                ON C.CUSTOMER_ENC = U.CUSTOMER_ID_ENC
                            JOIN ACCOUNT_TYPES AT on U.USER_TYPE= `AT`.ACCOUNT_TYPE
                            WHERE C.CUSTOMER_ENC = '$acc_enc' or PARENT_ACCOUNT_ID = '$acc_enc' and A.ACCOUNT_TYPE <= 5"; 
                        </cfquery>
                    }
                }
            }
        
            if(isset($form["submitFlag"]) AND ($form["submitFlag"] == "ajax")){
                    return $getusers->recordcount;
            } elseif(isset($form["submitFlag"]) AND ($form["submitFlag"] == "export")){
            
                <cfsavecontent variable="exportData">
                    <table width="100%" border="1">
                        <tr>
                            <td><strong>Parent</strong> </td>
                            <td><strong>Customer</strong> </td>
                            <td><strong>Nombre</strong> </td>
                            <td><strong>Apellido</strong> </td>
                            <td><strong>Level</strong> </td>
                        
                            <td><strong>Telefono</strong> </td>
                            <td><strong>Correo Elelctronico</strong></td>
                        </tr>
                        <cfquery name="getusers" datasource="#request.db_dsn#">
                            SELECT C.COMPANY, U.FIRST_NAME, U.LAST_NAME, U.LOCAL_PHONE, U.E_MAIL, ATY.DESCRIPTION, C1.COMPANY AS PARENT
                            FROM USERS U JOIN ACCOUNTS AC ON U.CUSTOMER_ID_ENC=AC.ACCOUNT
                            JOIN CUSTOMERS C ON AC.CUSTOMER_ID = C.CUSTOMER_ID
                            JOIN ACCOUNT_TYPES ATY ON AC.ACCOUNT_TYPE = ATY.ACCOUNT_TYPE
                            JOIN CUSTOMERS C1 ON AC.PARENT_ACCOUNT_ID=C1.CUSTOMER_ENC
                            WHERE USER_TYPE IN (2,3,4,5,6)
                        </cfquery>
                        <cfoutput query="getusers">
                            <tr>
                            <td>#PARENT#</td>
                            <td><!---<strong>#USER_ID# userID</strong> --->#COMPANY# </td>
                            <td>#FIRST_NAME# </td>
                            <td>#LAST_NAME# </td>
                            <td>#DESCRIPTION#</td>
                        
                            <td>#LOCAL_PHONE# </td>
                            <td>#E_MAIL#</td>
                    </tr>
                    </cfoutput>
                    <cfquery name="getusers" datasource="#request.db_dsn#">
                            SELECT C.LOCAL_PHONE, ATY.DESCRIPTION, C1.COMPANY 
                            FROM CUSTOMERS C JOIN ACCOUNTS AC ON C.CUSTOMER_ID=AC.CUSTOMER_ID
                            JOIN ACCOUNT_TYPES ATY ON AC.ACCOUNT_TYPE = ATY.ACCOUNT_TYPE
                            JOIN CUSTOMERS C1 ON AC.PARENT_ACCOUNT_ID=C1.CUSTOMER_ENC
                            WHERE AC.ACCOUNT_TYPE IN (7)
                        </cfquery>
                        <cfoutput query="getusers">
                            <tr>
                            <td>#COMPANY# </td>
                            <td><!---<strong>#USER_ID# userID</strong> ---></td>
                            <td> </td>
                            <td></td>
                            <td>#DESCRIPTION#</td>
                            <td></td>
                            <td>#LOCAL_PHONE# </td>
                            <td></td>
                    </tr>
                    </cfoutput>
                                <!---             
                        <cfquery name="getsubusers#form.LEVELS#" datasource="#request.db_dsn#">
                            SELECT  C.* FROM CUSTOMERS C
                                JOIN ACCOUNTS A ON (A.ACCOUNT_ENC = C.CUSTOMER_ENC)
                                WHERE 	A.ACCOUNT_TYPE <= 5  #parent#
                            </cfquery>
                            <cfoutput>
                            <!---<cfdump var="#getCustomers#">--->
                        <!---<cfoutput query="getCustomers" group="CUSTOMER_ENC">
                            <!---		
                            <cfoutput>
                                <tr>
                                    <td><strong>#USER_ID# Outer</strong> #COMPANY# </td>
                                    <td>#FIRST_NAME# </td>
                                    <td>#LAST_NAME# </td>
                                    <td>#DESCRIPTION#</td>
                                    <td>#STATE_REGION# </td>
                                    <td>#LOCAL_PHONE# </td>
                                    <td>#E_MAIL#</td>
                                </tr>
                            </cfoutput>---->
                            <cfquery name="getsubusers#form.LEVELS+1#" datasource="#request.db_dsn#">
                            SELECT  C.CUSTOMER_ENC FROM CUSTOMERS C
                                JOIN ACCOUNTS A ON (A.ACCOUNT_ENC = C.CUSTOMER_ENC)
                                WHERE 	PARENT_ACCOUNT_ID= '#CUSTOMER_ENC#'
                                GROUP BY  C.CUSTOMER_ENC 
                            </cfquery>
                            <cfquery name="getsubusers" datasource="#request.db_dsn#">
                                SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.*,U.FIRST_NAME,U.LAST_NAME,C.LOCAL_PHONE,U.E_MAIL,`AT`.DESCRIPTION,U.USER_ID
                                FROM CUSTOMERS C 
                                JOIN ACCOUNTS A ON C.CUSTOMER_ID = A.CUSTOMER_ID
                                JOIN USERS U ON C.CUSTOMER_ENC = U.CUSTOMER_ID_ENC 
                                JOIN ACCOUNT_TYPES AT ON AT.ACCOUNT_TYPE =A.ACCOUNT_TYPE 
                                WHERE A.PARENT_ACCOUNT_ID = '#CUSTOMER_ENC#' ORDER BY C.COMPANY
                            </cfquery>
                            <!---<cfdump var="#getsubusers#">--->
                            <cfdump var="#Evaluate("getsubusers#form.LEVELS+1#")#" label="Outer">
                            <cfloop query="getsubusers">
                                    <tr>
                                        <td><strong>#USER_ID# Inside</strong> #COMPANY# </td>
                                        <td>#FIRST_NAME# </td>
                                        <td>#LAST_NAME# </td>
                                        <td>#DESCRIPTION#</td>
                                        <td>#STATE_REGION# </td>
                                        <td>#LOCAL_PHONE# </td>
                                        <td>#E_MAIL#</td>
                                    </tr>
                                
                            </cfloop>
                            --->
                            <cfloop query="getsubusers#form.LEVELS#"  >
                                <cfset counval = form.LEVELS>
                            
                                <cfloop from="#form.LEVELS+2#" to="7" index="i">
                                    <cfif IsQuery(Evaluate("getsubusers#counval#"))>
                                    <cfset queryobj = Evaluate("getsubusers#counval#")>
                                    <!---<cfdump var="#queryobj#">--->
                                    <cfif queryobj.recordcount GT 0>
                                    <cfif i EQ 7 >
                                        <cfquery name="getsubusers#i#" datasource="#request.db_dsn#">
                                        SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.*, `AT`.DESCRIPTION, C.CUSTOMER_ID as USER_ID FROM CUSTOMERS C
                                            JOIN ACCOUNTS A ON (A.ACCOUNT_ENC = C.CUSTOMER_ENC)
                                            JOIN ACCOUNT_TYPES AT on A.ACCOUNT_TYPE= `AT`.ACCOUNT_TYPE
                                            WHERE 	PARENT_ACCOUNT_ID= '#queryobj.CUSTOMER_ENC#' 
                                        </cfquery>
                                        <cfset querysubobj= "#Evaluate("getsubusers#i#")#">
                                        <cfif querysubobj.recordcount GT 0>
                                    <cfloop query="querysubobj">
                                        
                                        <tr>
                                            <td><!---<strong>#USER_ID# customerID</strong> --->#COMPANY# </td>
                                            <td>#FIRST_NAME# </td>
                                            <td>#LAST_NAME# </td>
                                            <td>#DESCRIPTION#</td>
                                            <td>#STATE_REGION# </td>
                                            <td>#LOCAL_PHONE# </td>
                                            <td>#E_MAIL#</td>
                                        </tr>
                                    </cfloop>
                                    </cfif>
                                
                                    <cfelse>
                                        <cfquery name="getsubusers#i#" datasource="#request.db_dsn#">
                                        SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.*,U.FIRST_NAME,U.LAST_NAME,C.LOCAL_PHONE,U.E_MAIL,`AT`.DESCRIPTION,U.USER_ID FROM USERS U
                                            JOIN ACCOUNTS A ON (A.ACCOUNT_ENC = U.CUSTOMER_ID_ENC)
                                            JOIN CUSTOMERS C
                                                ON C.CUSTOMER_ENC = U.CUSTOMER_ID_ENC
                                            JOIN ACCOUNT_TYPES AT on A.ACCOUNT_TYPE= `AT`.ACCOUNT_TYPE
                                            WHERE 	PARENT_ACCOUNT_ID= '#queryobj.CUSTOMER_ENC#' 
                                        </cfquery>
                                        <cfquery name="getsubchildusers" datasource="#request.db_dsn#">
                                            SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.*,U.FIRST_NAME,U.LAST_NAME,C.LOCAL_PHONE,U.E_MAIL,`AT`.DESCRIPTION,U.USER_ID
                                            FROM CUSTOMERS C 
                                            JOIN ACCOUNTS A ON C.CUSTOMER_ID = A.CUSTOMER_ID
                                            JOIN USERS U ON C.CUSTOMER_ENC = U.CUSTOMER_ID_ENC 
                                            JOIN ACCOUNT_TYPES AT ON AT.ACCOUNT_TYPE =A.ACCOUNT_TYPE 
                                            WHERE A.PARENT_ACCOUNT_ID = '#queryobj.CUSTOMER_ENC#' ORDER BY C.COMPANY
                                        </cfquery>	
                                        <cfloop query="getsubchildusers">
                                                <tr>
                                                    <td><!---<strong>#USER_ID# userID</strong> --->#COMPANY# </td>
                                                    <td>#FIRST_NAME# </td>
                                                    <td>#LAST_NAME# </td>
                                                    <td>#DESCRIPTION#</td>
                                                    <td>#STATE_REGION# </td>
                                                    <td>#LOCAL_PHONE# </td>
                                                    <td>#E_MAIL#</td>
                                                </tr>
                                            
                                        </cfloop>
                                    </cfif>
                                    
                                    <cfset counval = i>
                                    </cfif>
                                    <cfelse>
                                        <cfbreak>
                                    </cfif>
                                </cfloop>   
                            
                            </cfloop>
                            
                        </cfoutput>   --->
                    </table>
                    
                    </cfsavecontent> 
                
                
                    <!--- <cfoutput>#exportData#</cfoutput>--->
                    
                <cfset session.exportusers = exportData>
                <cfreturn "done">
                    
            }
        
            if($ticket_parent > 0){
                <cfquery name="getSender" datasource="#request.db_dsn#" >
                    $sql_select_tickets = "select * from TICKETS where id= $ticket_parent";
                </cfquery>
                if($getSender->CommentBy == $_COOKIE["user_account_id"]){
                    $customer = $getSender->CustomerID;
                } else {
                    $customer = $getSender->CommentBy;
                }
                <cfquery name="add" datasource="#request.db_dsn#" >
                    $sql_insert_tickets = "insert into TICKETS(AdminID	,CustomerID	,ReasonID	,Comment	,CommentBy	,Createdat	,IsRead	,TicketStatus	,IsDeleted	,ParentTicket,subject)values('$get_admin->ACCOUNT_ENC'	,'$customer',	'".$form["reasons"]."','".$form["comment"]."','".$_COOKIE["user_account_id"]."', $CreateODBCDateTime(now()) , 0,";
                    if(isset($form["is_solved"])){
                        $sql_insert_tickets .= 1;
                    } else {
                        $sql_insert_tickets .= 0;
                    }
                    $sql_insert_tickets .=" ,0,$ticket_parent,".$form["subject"].")";
                </cfquery>
            } else {
                <cfloop query="getusers">
                        <cfparam name="form.mandatory" default="0">
                        <cfquery name="add" datasource="#request.db_dsn#" >
                        insert into TICKETS(AdminID	,CustomerID	,ReasonID	,Comment	,CommentBy	,Createdat	,IsRead	,TicketStatus	,IsDeleted	,ParentTicket,subject,mandatory)values('#get_admin.ACCOUNT_ENC#'	,'#getusers.CUSTOMER_ENC#',	'#form.reasons#','#form.comment#','".$_COOKIE["user_account_id"]."', #CreateODBCDateTime(now())# , 0,<cfif isdefined("form.is_solved")>1<cfelse>0</cfif>,0,#ticket_parent#,'#form.subject#',#form.mandatory#)
                    </cfquery>
                    
                </cfloop>
            }
            if(isset($form["is_solved"])){
                $sql_update_tickets = "UPDATE TICKETS SET TicketStatus =1 WHERE id='$ticket_parent' AND AdminID = '$_COOKIE["user_account_id"]'";
                $query = $this->db->query($sql_update_tickets);
            }
        }
        return $form["comment"];
    }*/

    public function getsubusers($fieldname,$fieldval,$count){
        
        // if($fieldname == "PARENT_ACCOUNT_ID")
        //     <cfset encryptionKey = "enzimaNadi">
        //     <cfset acc_enc = decrypt(    fieldval,    encryptionKey,    "CFMX_COMPAT",    "hex"    ) />
        //     <cfset fieldval = acc_enc>
        // </cfif>
        // <cfif cookie.user_type EQ 956314127503977533 or cookie.user_type EQ 525874964125375325>
        //     <cfset role = 1>
        // <cfelseif cookie.user_type EQ 638545125236524578>
        //     <cfset role = 2>
        // <cfelseif cookie.user_type EQ 325210258618165451>
        //     <cfset role = 3>
        // <cfelseif cookie.user_type EQ 125458968545678354>
        //     <cfset role = 4>
        // <cfelseif cookie.user_type EQ 415285967837575867>
        //     <cfset role = 5>
        // <cfelseif cookie.user_type EQ 863252457813278645>
        //     <cfset role = 6>
        // </cfif>
        // <cfif cookie.user_type EQ 956314127503977533>
        //     <cfset where = "where #fieldname# = #fieldval# and ACCOUNT_TYPE <= 5">
        // <cfelse>
        //     <cfset where = "where #fieldname# = #fieldval# and ACCOUNT_TYPE <= 5 " >
        //     <cfif (role+1) eq fieldval>
        //         <cfset where = where & " and PARENT_ACCOUNT_ID= #cookie.user_account_id#" >
        //     </cfif>
        // </cfif>
        
        
        
        // <cfquery name="ParentAcc" datasource="#request.db_dsn#">
        //     SELECT A.PARENT_ACCOUNT_ID,A.ACCOUNT_TYPE, C.* FROM CUSTOMERS C
        //     JOIN ACCOUNTS A ON (A.ACCOUNT_ENC = C.CUSTOMER_ENC)
        //     #where#
        //     ORDER BY C.COMPANY
        // </cfquery>
    
        
        // <cfif ParentAcc.recordcount GT 0>
            
        //     <cfoutput>
        //         <cfset encryptionKey = "enzimaNadi">
        //         <div class="outer_#arguments.count#">
        //         <select name="customer" <cfif ParentAcc.account_type NEQ 7> onChange="getsublevels(this.value,'PARENT_ACCOUNT_ID',#arguments.count#)"</cfif>>
        //             <option value="">Select all</option>
        //             <cfif fieldval GT (role+1)>
        //             <option value="none">Select None</option>
        //             </cfif>
        //             <cfloop  query="ParentAcc">
                        
        //                 <cfset acc_enc = encrypt(	ParentAcc.customer_enc, encryptionKey, "CFMX_COMPAT",	"hex" ) />
                        
        //                 <option value="#acc_enc#">#ParentAcc.COMPANY#</option>
        //             </cfloop>
                
        //         </select>
        //             <div></div>
        //         </div>
        //     </cfoutput>
        // <cfelse>
        //     <cfreturn false>
        // </cfif>
    }
}