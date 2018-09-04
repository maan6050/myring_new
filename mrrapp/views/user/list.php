<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	//echo"<pre>";
    //print_r($users);
    //die();
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
	<div class="row">
		<div class="col-sm-12 marginBottom10px">
			<a href="<?= base_url('User/add'); ?>" class="myRingButton pull-right">Add User</a>
		</div>
	</div>	
	<div class="table-container">
		<table id="user-table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Customer</td>
					<td>First Name</td>
					<td>Last Name</td>
					<td>Phone</td>
					<td>Role</td>
					<td>Login</td>
					<td>Active</td>
					<td>Edit</td>
				</tr>
			</thead>
			<tbody> <?php
				foreach($users as $user) {  ?>
					<tr>
						<td><?php echo $user->COMPANY; ?></td>
						<td><?php echo $user->UserFirstName; ?></td>
						<td><?php echo $user->UserLastName; ?></td>
						<td>
                            <?php 
                                if(($user->UserLocalPhone)!=""){
                                   echo "+".$_COOKIE["country"]."-".$user->UserLocalPhone;
                                } 
                            ?>
                        </td> 
                        <?php
                            $expression = $user->USER_TYPE;
                            switch ($expression) {
                                case 1:
                                    $type = "Owner";
                                    break;
                                case 2:
                                    $type = "Master";
                                    break;
                                case 3:
                                    $type = "Distributor";
                                    break;
                                case 4:
                                    $type = "Sub Distributor";
                                	break;
                                case 4:
                                    $type = "Store";
                                	break;    
                                case 6:
                                    $type = "Clerk";
                                	break;
                                case 8:
                                    $type = "Customer Service";
                                	break;
                                default:
                                    $type = "";
                            }
                        ?>
						<td><?php echo $type; ?></td>
						<td><?php echo $user->LOGIN_NAME; ?></td>
						<td>
							<?php if($user->UserEnabled==1){
								echo "YES";	
							} else {
								echo "NO";
							} ?>
						</td>
						<td>
							<a href="<?php echo base_url('User/add?userid='.$user->USER_ID) ?>" title=" Edit User"><i class="fa fa-edit fa-2x" aria-hidden="true"></i></a>
						</td>
					</tr> <?php
				} ?>
			</tbody>
		</table>
	</div>
</div>
<div class="alert_message_container" id="alert_message_container">
    <div class="alert_message" id="alert_message"></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#user-table').DataTable({
			"aoColumnDefs" : [ 
				{"aTargets" : [7], "sClass":  "custom-td"}
			]
		});
    });
</script>


