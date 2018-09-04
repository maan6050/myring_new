<?php
/**
 * Modelo para la realizaci�n de operaciones sobre las tablas de la BD relacionadas con los clientes.
 * Creado: Enero 20, 2017
 * Modificaciones: CZapata
 */
 
class WebContents extends CI_Model
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
    public function delete_section($pageName,$sectionid=''){
		/*SELECT * FROM pageContent WHERE page = <cfqueryparam value="#arguments.pageName#" cfsqltype="cf_sql_varchar">and section_id = <cfqueryparam value="#arguments.sectionid#" cfsqltype="cf_sql_numeric"> and Customer_id = <cfqueryparam value="#cookie.user_account_id#" cfsqltype="cf_sql_numeric">
			
		<cfif page.recordcount GT 0>
			DELETE  FROM pageContentWHERE page = <cfqueryparam value="#arguments.pageName#" cfsqltype="cf_sql_varchar"> and section_id = <cfqueryparam value="#arguments.sectionid#" cfsqltype="cf_sql_numeric"> and Customer_id = <cfqueryparam value="#cookie.user_account_id#" cfsqltype="cf_sql_numeric">
			<cfreturn "deleted">
		</cfif>
		<cfreturn "not"> */                 
	}
	public function getPageContent($customer_id,$pageName,$sectionid='',$sectionPartCheck=''){
		/*SELECT * FROM pageContent WHERE page = <cfqueryparam value="#arguments.pageName#" cfsqltype="cf_sql_varchar">
			<cfif IsDefined("arguments.customer_id") and arguments.customer_id NEQ 0>
				and Customer_id = <cfqueryparam value="#arguments.customer_id#" cfsqltype="cf_sql_numeric">
			</cfif>
			<cfif isdefined("arguments.sectionid") and arguments.sectionid neq 0 >
				and section_id = <cfqueryparam value="#arguments.sectionid#" cfsqltype="cf_sql_numeric">
			</cfif> 

			<cfif isdefined("arguments.sectionPartCheck") AND arguments.sectionPartCheck Neq '' >
				and check_section = <cfqueryparam value="#arguments.sectionPartCheck#" cfsqltype="cf_sql_varchar">
			<cfelse>
				and (check_section = '' or check_section IS NULL)
			</cfif>
			
			<cfif IsDefined("cookie.user_type") and cookie.user_type EQ 956314127503977533>
				and isactive in(0,1)
			<cfelse>
				and isactive in(1)
			</cfif>
			and url is null
		order by sort_order,section_id
		<cfreturn page>*/
	}
	public function getLatestSectionID($customer_id,$pageName){
		/* SELECT * FROM pageContent WHERE page = <cfqueryparam value="#arguments.pageName#" cfsqltype="cf_sql_varchar"> and Customer_id = <cfqueryparam value="#arguments.customer_id#" cfsqltype="cf_sql_numeric"> order by section_id desc Limit 1
			
		<cfif getSectionID.Recordcount gt 0>
			<cfreturn getSectionID.section_id + 1>
		<cfelse>
			<cfreturn 10>
		</cfif>	*/
	}
}