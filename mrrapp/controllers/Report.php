<?php

/**
 * Controlador para reportes administrativos
 * Creado: Julio 05, 2017
 * Modificaciones: Jorge Mario Romero Arroyo
 * Version 1.0
 */

class Report extends MY_Controller
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
		$this->load->model('customers');
		$this->load->model('reports');
		$this->load->model('records');
		$this->load->library('form_validation');
	}

    public function viewReports(){
		$data["getreportsNumRows"] = "0";
		if(isset($_GET["loadfirst"])){
			$loadfirst = $_GET["loadfirst"];
		} else {
			$loadfirst = "0";
		}
		if($_COOKIE['user_type'] == "USA"){
			$data['storeCurrency'] = "USD";
		} else {
			$data['storeCurrency'] = $_COOKIE['current_cur'];
		}
		$data['owner'] = "0";
		$data['master'] = "0";
		$data['sub_dis'] = "0";
		$data['store'] = "0";
		if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "956314127503977533")) {
			$data['owner'] = "1";
		}
		if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "525874964125375325")) {
			$data['owner'] = "1";
		}  
		if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "638545125236524578")) {
			$data['master'] = "1";
		}
		if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "125458968545678354")) {
			$data['sub_dis'] = "1";
		}
		if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "415285967837575867")) {
			$data['store'] = "1";
		}
		$data['roleUser'] = $this->reports->getChildRoles();
		 
		if((isset($_GET["report"]) AND $_GET["report"] == "1")){
			$title = "Product Type";
		} else if((isset($_GET["report"]) AND $_GET["report"] == "2")){
			$title = "Product";
		} else if((isset($_GET["report"]) AND $_GET["report"] == "3")){
			$title = $data['roleUser'] ;
			$data["getHeaders"] = $this->customers->getHeaderInfo();
			$data["accountType"] = $this->customers->getAccountType();
		} else if((isset($_GET["report"]) AND (($_GET["report"] == "4") OR ($_GET["report"] == "5")))){
			$title = "Provider";
		} else {
			$_GET["report"] = "1";
			$title = "Product Type";
		}
		if(isset($_GET["report"])){
			$report_type = $_GET["report"];
		}
		$data['repSearchField'] = $this->reports->getRepSearchField($report_type);
		// echo "<pre>";
		// print_r($data['repSearchField']);
		if(isset($_GET["show"]) AND ($_GET["show"] == "all")){
			$data['sDate'] = "";
			$data['eDate'] = "";
			$data['sTime'] = "";
			$data['eTime'] = "";
		} else {
			$data['sDate'] = date("m/d/y");
			$data['eDate'] = date("m/d/y");
			$data['sTime'] = "00:00:00";
			$data['eTime'] = date(" ");      
		}
		if(isset($_GET["user"])){
			$data["user"] = $_GET["user"];
		} else {
			$data["user"] = "";
		}
		if($data['sTime']=="") {
			$data['sTime'] ="00:00:01";
		}
		if($data['eTime']=="") {
			$data['eTime'] ="23:59:59";
		}
		if($data['eTime']=="24:00:00") {
			$data['eTime'] ="23:59:59";
		}
		$starttime = $data['sDate']. " " . $data['sTime'];
		$endttime = $data['eDate']. " " .$data['eTime'];
		$data['getreports'] = "0";

		if(isset($loadfirst) AND ($loadfirst !== "1")) {
			$data['getreports'] = $this->reports->getReports($report_type,$starttime,$endttime,$data["user"]);
		}
		$data["getprodFeeProducts"] = $this->reports->getprodFeeProducts($report_type,$starttime,$endttime,$data["user"],$PROD_SOLD_ID='0');
		$data['report_type'] = $report_type;
		$data['title'] = $title;
		$this->load->view('header', $data);
		$this->load->view('report/list', $data);
		$this->load->view('footer');
	}

	public function prodsoldreport(){
		$data["userType"] = "";
		if($_COOKIE['user_type'] == "415285967837575867"){
			$data["userType"] = "store";
		}else if(($_COOKIE['user_type'] == "956314127503977533") OR ($_COOKIE['user_type'] == "525874964125375325")) {
			$data["userType"] = "owner";
		}
		
		if($_COOKIE['user_type'] == "USA"){
			$data['storeCurrency'] = "USD";
		} else {
			$data['storeCurrency'] = $_COOKIE['current_cur'];
		}

		
		if(isset($_GET["show"]) AND ($_GET["show"] == "all")){
			$data['sDate'] = "";
			$data['eDate'] = "";
			$data['sTime'] = "";
			$data['eTime'] = "";
		} else {
			$data['sDate'] = date("m/d/y");
			$data['eDate'] = date("m/d/y");
			$data['sTime'] = "00:00:00";
			$data['eTime'] = date(" ");      
		}
		if(isset($_GET["phone_number"])){
			$data["phone_number"] = $_GET["phone_number"];
		} else {
			$data["phone_number"] = "";
		}
		if($data['sTime']=="") {
			$data['sTime'] ="00:00:01";
		}
		if($data['eTime']=="") {
			$data['eTime'] ="23:59:59";
		}
		if($data['eTime']=="24:00:00") {
			$data['eTime'] ="23:59:59";
		}
		$starttime = $data['sDate']. " " . $data['sTime'];
		$endttime = $data['eDate']. " " .$data['eTime'];
		$data['getreports'] = "0";

		$data["getreports"] = $this->reports->getProdSoldReport($data["phone_number"],$starttime,$endttime,1);
		
		$data['title'] = "Sold Products Report";
		$this->load->view('header', $data);
		$this->load->view('report/prodsold', $data);
		$this->load->view('footer');
	}

	public function prodSoldDataReport(){
		$data["userType"] = "";
		if($_COOKIE['user_type'] == "415285967837575867"){
			$data["userType"] = "store";
		}else if(($_COOKIE['user_type'] == "956314127503977533") OR ($_COOKIE['user_type'] == "525874964125375325")) {
			$data["userType"] = "owner";
		}
		
		if($_COOKIE['user_type'] == "USA"){
			$data['storeCurrency'] = "USD";
		} else {
			$data['storeCurrency'] = $_COOKIE['current_cur'];
		}

		
		if(isset($_GET["show"]) AND ($_GET["show"] == "all")){
			$data['sDate'] = "";
			$data['eDate'] = "";
			$data['sTime'] = "";
			$data['eTime'] = "";
		} else {
			$data['sDate'] = date("m/d/y");
			$data['eDate'] = date("m/d/y");
			$data['sTime'] = "00:00:00";
			$data['eTime'] = date(" ");      
		}
		if(isset($_GET["phone_number"])){
			$data["phone_number"] = $_GET["phone_number"];
		} else {
			$data["phone_number"] = "";
		}
		$data['sTime'] = $_GET["sTime"];
		if(isset($_GET["sTime"]) AND ($_GET["sTime"]=="")) {
			$data['sTime'] ="00:00:01";
		}
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="")) {
			$data['eTime'] ="23:59:59";
		}
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="24:00:00")) {
			$data['eTime'] ="23:59:59";
		}
		$starttime = $_GET['sDate']. " " . $data['sTime'];
		$endttime = $_GET['eDate']. " " .$data['eTime'];
		$data["getreports"] = $this->reports->getProdSoldReport($data["phone_number"],$starttime,$endttime,1);
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="23:59:59")) {
			$data['eTime'] ="24:00:00";
		}
		$data['title'] = "Sold Products Report";
		$this->load->view('header', $data);
		$this->load->view('report/prodsold', $data);
		$this->load->view('footer');
	}

	public function unsuccessTransReport(){
		if($_COOKIE['user_type'] == "956314127503977533"){
			$userType = "owner";
		}else if($_COOKIE['user_type'] == "525874964125375325"){
			$userType = "owner";
		} else {
			redirect(base_url('home'));	
		}

		if(isset($_GET["show"]) AND ($_GET["show"] == "all")){
			$data['sDate'] = "";
			$data['eDate'] = "";
			$data['sTime'] = "";
			$data['eTime'] = "";
		} else {
			$data['sDate'] = date("m/d/y");
			$data['eDate'] = date("m/d/y");
			$data['sTime'] = "00:00:00";
			$data['eTime'] = date(" ");      
		}
		if($data['sTime']=="") {
			$data['sTime'] ="00:00:01";
		}
		if($data['eTime']=="") {
			$data['eTime'] ="23:59:59";
		}
		if($data['eTime']=="24:00:00") {
			$data['eTime'] ="23:59:59";
		}
		$starttime = $data['sDate']. " " . $data['sTime'];
		$endttime = $data['eDate']. " " .$data['eTime'];

		$data['getreports'] = $this->reports->getUnsuccessfulTrans($starttime,$endttime);
		$data['title'] = "Unsuccessful Transactions";
		$this->load->view('header', $data);
		$this->load->view('report/unsuccessTrans', $data);
		$this->load->view('footer');
	}

	public function unSuccessReportData(){
		$data['sTime'] = $_GET["sTime"];
		if(isset($_GET["sTime"]) AND ($_GET["sTime"]=="")) {
			$data['sTime'] ="00:00:01";
		}
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="")) {
			$data['eTime'] ="23:59:59";
		}
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="24:00:00")) {
			$data['eTime'] ="23:59:59";
		}
		$starttime = $_GET['sDate']. " " . $data['sTime'];
		$endttime = $_GET['eDate']. " " .$data['eTime'];
		$data['getreports'] = $this->reports->getUnsuccessfulTrans($starttime,$endttime);
		if(isset($_GET["eTime"]) AND ($_GET["eTime"]=="23:59:59")) {
			$data['eTime'] ="24:00:00";
		}
		$data['title'] = "Unsuccessful Transactions";
		$this->load->view('header', $data);
		$this->load->view('report/unsuccessTrans', $data);
		$this->load->view('footer');
	}

	public function customerGroupReport(){
		if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "956314127503977533")) {
			$userType = "owner";
		} else if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "638545125236524578")) {
			$userType = "master";
		} else if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "325210258618165451")) {
			$userType = "distributer";
		} else if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "125458968545678354")) {
		
			$userType = "subdistributer";
		} else if(isset($_COOKIE['user_type']) AND  ($_COOKIE['user_type'] == "415285967837575867")) {
			$userType = "store";
		} else {
			redirect(base_url('home'));
		}
		$data["getcustomers"] = $this->reports->getallUsersList();
		$data["currentAccount"] = $this->customers->getAccountType();
		$data['userType'] = $userType;
		$data['title'] = "Report by Customer Type";
		$this->load->view('header', $data);
		$this->load->view('report/customerGroup', $data);
		$this->load->view('footer');
	}

}

















/*if(isset($_GET["report"]) AND ($_GET["report"] == "3"))
	$getsales_comm = $this->reprts->getReports($report_type,'','',$user);
	<div style="float:right;" id="stats">
		
		
		$getHeaders = $this->customers->getHeaderInfo();
		$accountType = $this->customers->getAccountType();
		if($accountType > 2 AND $accountType < 4){
			<table>
				<tr>
					<td><b>Balance</b></td>
					<td><b>Available</b></td>
				</tr>
				<tr>
					<td><?php echo "<i class='fa fa-usd'></i> ".$getHeaders->BALANCE; ?></td>
					<td> <?php
						if($getHeaders->BALANCE == ""){
							$getHeaders->BALANCE = "0";
						}
						if($getHeaders->CREDIT_LIMIT EQ ""){
							$getHeaders->CREDIT_LIMIT = "0";
						}
						if($getHeaders->TOTALSUM EQ ""){
							$getHeaders->TOTALSUM = "0";
						}
						#DollarFormat((getHeaders.BALANCE + getHeaders.CREDIT_LIMIT) - getHeaders.TOTALSUM)#
						echo "<i class='fa fa-usd'></i> ".($getHeaders->BALANCE+$getHeaders->CREDIT_LIMIT)-$getHeaders->TOTALSUM;
						?>	
					</td>
				</tr>
			</table>
		}
		<!--- William disabled this part of the report as it was giving error
		
		<table>
			<tr>
				<td><strong>Sales</strong></td>
			</tr>
			<tr>
				<td align="center"><strong>Last Month</strong>
				<br />
				<!---Last Month--->
				<cfset lastM = CreateDate(Year(Now()),Month(Now())-1,01)>
				<cfset dtMonthStart = (lastM - Day( lastM ) + 1) />
				<cfset dtMonthEnd = (lastM + (DaysInMonth( lastM ) - Day( lastM ))) />
				<!--- Last Week--->
				<cfset dtWeekStart = (now() - DayOfWeek( now() ) -5) />
				<cfset dtWeekEnd = (now() + (1 - DayOfWeek(now()) )) />
	
				<cfquery name="get_today" dbtype="query">
					select sum(SUM_AMNT) as SUM_AMNT from getsales_comm where START_DATE_TIME >= '#DateFormat(dtMonthStart,"yyyy-mm-dd")#' and  
					START_DATE_TIME <= '#DateFormat(dtMonthEnd,"yyyy-mm-dd")#' 
				</cfquery>
				<cfoutput><cfif get_today.SUM_AMNT NEQ "">#dollarformat(get_today.SUM_AMNT)#<cfelse>$0</cfif></cfoutput>
				</td>
				<td align="center"><strong>Last Week</strong>
				<br />
				<cfquery name="get_today" dbtype="query">
					select sum(SUM_AMNT) as SUM_AMNT from getsales_comm where START_DATE_TIME >= '#DateFormat(dtWeekStart,"yyyy-mm-dd")#' and  
					START_DATE_TIME <= '#DateFormat(dtWeekEnd,"yyyy-mm-dd")#' 
				</cfquery>
				<cfoutput><cfif get_today.SUM_AMNT NEQ "">#dollarformat(get_today.SUM_AMNT)#<cfelse>$0</cfif></cfoutput>

				</td>
			</tr>
			<tr>
				<td align="center"><strong>Yesterday</strong>
				<br />
				<cfset yesterday = CreateDate(Year(Now()),Month(Now()),Day(Now()-1))>
				<cfquery name="get_today" dbtype="query">
					select sum(SUM_AMNT) as SUM_AMNT from getsales_comm where START_DATE_TIME = '#DateFormat(yesterday,"yyyy-mm-dd")#' 
				</cfquery>
				<cfoutput><cfif get_today.SUM_AMNT NEQ "">#dollarformat(get_today.SUM_AMNT)#<cfelse>$0</cfif></cfoutput>
				</td>
				<td align="center"><strong>Today</strong> 
				<br />
				<cfquery name="get_today" dbtype="query">
					select sum(SUM_AMNT) as SUM_AMNT from getsales_comm where START_DATE_TIME = '#DateFormat(now(),"yyyy-mm-dd")#' 
				</cfquery><cfoutput><cfif get_today.SUM_AMNT NEQ "">#dollarformat(get_today.SUM_AMNT)#<cfelse>$0</cfif></cfoutput></td>
			</tr>
		</table>
		<table border="0" cellpadding="3" cellspacing="4">
			<tr>
				<td colspan="2" align="center"><strong>Commissions</strong></td>
			</tr>
			<tr>
				<td align="center"><strong>Last Month</strong>
				<br />
				<cfquery name="get_today" dbtype="query">
						select sum(SUM_PRO) as SUM_PRO from getsales_comm where START_DATE_TIME >= '#DateFormat(dtMonthStart,"yyyy-mm-dd")#' and  
						START_DATE_TIME <= '#DateFormat(dtMonthEnd,"yyyy-mm-dd")#' 
					</cfquery>
					<cfoutput><cfif get_today.SUM_PRO NEQ "">#dollarformat(get_today.SUM_PRO)#<cfelse>$0</cfif></cfoutput>
				</td>
				<td align="center"><strong>Last Week</strong>
				<br />
				<cfquery name="get_today" dbtype="query">
						select sum(SUM_PRO) as SUM_PRO from getsales_comm where START_DATE_TIME >= '#DateFormat(dtWeekStart,"yyyy-mm-dd")#' and  
						START_DATE_TIME <= '#DateFormat(dtWeekEnd,"yyyy-mm-dd")#' 
					</cfquery>
					<cfoutput><cfif get_today.SUM_PRO NEQ "">#dollarformat(get_today.SUM_PRO)#<cfelse>$0</cfif></cfoutput>
	
				</td>
			</tr>
			<tr>
				<td align="center"><strong>Yesterday</strong>
				<br />
				<cfquery name="get_today" dbtype="query">
						select sum(SUM_PRO) as SUM_PRO from getsales_comm where START_DATE_TIME = '#DateFormat(yesterday,"yyyy-mm-dd")#' 
					</cfquery><cfoutput><cfif get_today.SUM_PRO NEQ "">#dollarformat(get_today.SUM_PRO)#<cfelse>$0</cfif></cfoutput>
				</td>
				<td align="center"><strong>Today</strong>
				<br />
				<cfquery name="get_today" dbtype="query">
						select sum(SUM_PRO) as SUM_PRO from getsales_comm where START_DATE_TIME = '#DateFormat(now(),"yyyy-mm-dd")#' 
					</cfquery><cfoutput><cfif get_today.SUM_PRO NEQ "">#dollarformat(get_today.SUM_PRO)#<cfelse>$0</cfif></cfoutput></td>
				</td>
			</tr>
		</table>--->
	</div>
</cfif>*/