<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div> 
    <div class="table-container">
		<table id="" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Types of Customers</td>
					<td>Balances</td>
					<td>Master Bal</td>
					<td>Dist Bal</td>
					<td>Sub Bal</td>
					<td>Store Bal</td>
                    <td>Qty</td> 
                    <td>View</td> 
                </tr>
			</thead>
			<tbody> <?php
                $searchENC = "";
                $distributorENC = "";
                $subdistributorENC = "";
                $storeENC = "";
                $enduserENC = "";
				if($_COOKIE['user_type'] == "956314127503977533"){
                    //<cfquery name="level3" dbtype="query">
                        echo $sql_select_getCustomers = "SELECT CUSTOMER_ENC, ACCOUNT_TYPE FROM IN ('getcustomers') WHERE PARENT_ACCOUNT_ID = '".$_COOKIE['USER_ACCOUNT_ID']."'";
                        $query = $this->db->query($sql_select_getCustomers);
                        $level3RecordCount = $query->num_rows();
                    //</cfquery>
                    if($level3RecordCount > "0"){
                        /*<cfloop query="level3">
                            <cfif searchENC IS "">
                                <cfset searchENC = "#level3.CUSTOMER_ENC#">
                            <cfelse>
                                <cfset searchENC = "#searchENC#,#level3.CUSTOMER_ENC#">
                            </cfif>
                            <cfif distributorENC IS "">
                                <cfset distributorENC = "#level3.CUSTOMER_ENC#">
                            <cfelse>
                                <cfset distributorENC = "#distributorENC#,#level3.CUSTOMER_ENC#">
                            </cfif>
                            
                            <cfif level3.ACCOUNT_TYPE LTE 5>
                                <cfquery name="level4" dbtype="query">
                                    SELECT CUSTOMER_ENC, ACCOUNT_TYPE
                                    FROM getcustomers
                                    WHERE PARENT_ACCOUNT_ID = #level3.CUSTOMER_ENC#
                                </cfquery>
                                <cfloop query="level4">
                                    <cfif searchENC IS "">
                                        <cfset searchENC = "#level4.CUSTOMER_ENC#">
                                    <cfelse>
                                        <cfset searchENC = "#searchENC#,#level4.CUSTOMER_ENC#">
                                    </cfif>
                                    <cfif subdistributorENC IS "">
                                        <cfset subdistributorENC = "#level4.CUSTOMER_ENC#">
                                    <cfelse>
                                        <cfset subdistributorENC = "#subdistributorENC#,#level4.CUSTOMER_ENC#">
                                    </cfif>
                                    
                                    <cfif level4.ACCOUNT_TYPE LTE 5>
                                        <cfquery name="level5" dbtype="query">
                                            SELECT CUSTOMER_ENC, ACCOUNT_TYPE
                                            FROM getcustomers
                                            WHERE PARENT_ACCOUNT_ID = #level4.CUSTOMER_ENC#
                                        </cfquery>
                                        <cfloop query="level5">
                                            <cfif searchENC IS "">
                                                <cfset searchENC = "#level5.CUSTOMER_ENC#">
                                            <cfelse>
                                                <cfset searchENC = "#searchENC#,#level5.CUSTOMER_ENC#">
                                            </cfif>
                                            <cfif storeENC IS "">
                                                <cfset storeENC = "#level5.CUSTOMER_ENC#">
                                            <cfelse>
                                                <cfset storeENC = "#storeENC#,#level5.CUSTOMER_ENC#">
                                            </cfif>
                                            
                                            <cfif level5.ACCOUNT_TYPE LTE 5>
                                                <cfquery name="level6" dbtype="query">
                                                    SELECT CUSTOMER_ENC, ACCOUNT_TYPE
                                                    FROM getcustomers
                                                    WHERE PARENT_ACCOUNT_ID = #level5.CUSTOMER_ENC#
                                                </cfquery>
                                                <cfloop query="level6">
                                                    <cfif searchENC IS "">
                                                        <cfset searchENC = "#level6.CUSTOMER_ENC#">
                                                    <cfelse>
                                                        <cfset searchENC = "#searchENC#,#level6.CUSTOMER_ENC#">
                                                    </cfif>
                                                    <cfif enduserENC IS "">
                                                        <cfset enduserENC = "#level5.CUSTOMER_ENC#">
                                                    <cfelse>
                                                        <cfset enduserENC = "#enduserENC#,#level5.CUSTOMER_ENC#">
                                                    </cfif>
                                                </cfloop>
                                            </cfif>
                                            
                                        </cfloop>
                                    </cfif>

                                </cfloop>
                            </cfif>
                            
                        </cfloop>*/
                    }
                }
                $showToolTip = "0";
                if(($userType == "owner") OR ($userType == "master")){
                    $showToolTip = "1";
                } 
                /*<cfquery name="getGroupedCust"datasource="#request.db_dsn#">
                    SELECT COUNT(ACCOUNT_TYPE) AS customers,
                    SUM(BALANCE) AS BALANCE,
                    ACCOUNT_TYPE,
                    DESCRIPTION
                    FROM ACCOUNT_SUMMARY
                    WHERE ACCOUNT_TYPE != 6 <cfif currentAccount GT 1> AND ACCOUNT_TYPE > #currentAccount#</cfif>
                    <cfif cookie.user_type NEQ 956314127503977533>
                        AND parent_account_id= '#cookie.user_account_id#'
                    </cfif>
                    <cfif searchENC NEQ "">AND CUSTOMER_ENC IN (#searchENC#) </cfif>
                    GROUP BY ACCOUNT_TYPE, DESCRIPTION
                </cfquery>*/?>
			</tbody>
		</table>
	</div>
</div>
<div class="alert_message_container" id="alert_message_container">
    <div class="alert_message" id="alert_message"></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#customer-group-table').DataTable({
		});
    });
</script>


