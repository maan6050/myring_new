<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    // echo "<pre>";
    // print_r($get_reasons_history);
?>
<div class="container">
	<h1 class="page-title"><?= "$title"; ?></h1>
	<div class="breadcrumbs">
		<a href="<?= base_url('home'); ?>">Home</a> / <?= $title; ?>
	</div>
    <div class="row marginBottom10px">
        <div class="col-sm-offset-3 col-sm-6">
            <form action="<?= base_url("ticket/tickets"); ?>" name="ticket-form" id="ticket-form" method="post">
                <div class="form-group">
                    <label for="ticket_type">Select Type</label><br />
                    <label class="radio-inline"><input type="radio" value="Message" name="ticket_type" checked onClick="$('.message_recipts').show()"> Message</label>
                    <label class="radio-inline"><input type="radio" value="Ticket" name="ticket_type" onClick="$('.message_recipts').hide()"> Ticket</label>
                </div>
                <div class="form-group message_recipts">
                    <label for="levels">Select Recipent</label>
                    <select class="form-control" name="levels" id="levels" onChange="getsublevels(this.value,'ACCOUNT_TYPE',0)">
                        <option value="">Select All</option>
                        <option value="1" data-parent="Parent"><?php echo $parent_data[0]->COMPANY; ?></option> <?php
                        foreach($getlevels as $getlevel) { ?>
                            <option value="<?php echo $getlevel->ACCOUNT_TYPE; ?>" data-parent="child"><?php echo $getlevel->DESCRIPTION; ?></option> <?php
                        } ?>
                    </select>
                    <input type="hidden" name="parent_Customer" id="parent_Customer" value="">
                    <div class="dynamic_selects"></div>
                </div>
                <div class="form-group">
                    <label for="reasons">Select Reason</label>
                    <select class="form-control" name="reasons"> <?php
                        foreach($get_reasons as $get_reason) { ?>
                            <option value="<?php echo $get_reason->reason_id; ?>"><?php echo $get_reason->reason; ?></option> <?php
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input class="form-control" id="subject" type="text" name="subject" required />
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea class="form-control" name="comment" id="comment" rows="5" cols="60" required ></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" class="btn myRingButton" value="Submit" />
                </div>
            </form>
        </div>
    </div>
    <div class="table-container">
        <h2>My tickets</h2>                
        <?php $total_unread = 0; ?>             
		<table id="ticket-table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<td>Tickets</td>
				</tr>
			</thead>
			<tbody> <?php
                $currentrow = 1;
				foreach($get_reasons_history as $get_reasons) { 
					if($get_reasons->CommentBy == $_COOKIE["user_account_id"]){
                        $chat_class = "current_customer";
                    } else {
                        $chat_class = "admin_customer";
                    } ?>
                    <tr>
                        <td>
                            <div class="accordion">
                                <div class="accordion-section"> <?php
                                    if($get_reasons->TicketStatus == 1){
                                        $solved_class= "solved";
                                    } else {
                                        $solved_class= "";
                                    }
                                    $get_all_history = $this->tickets->get_reasons_history($get_reasons->ID);
                        
                                    $is_read= 0;
                                    //savecontent variable="tickets_discuss">
                                        foreach($get_all_history as $get_all_his){
                                            if($get_all_his->IsRead == 0 AND $get_all_his->CommentBy !== $_COOKIE["user_account_id"]) {
                                                $is_read= $is_read + 1;
                                            }
                                            if($get_all_his->CommentBy == $_COOKIE["user_account_id"]){ ?>
                                                <p class="comment"> 
                                                    <?php echo $get_all_his->comment; ?> <br /> <small><span><strong>You</strong> at <strong><?php echo $get_reasons->CreatedAt; ?></strong></span></small>
                                                </p> <?php
                                            } else { ?> 
                                                <p class="reply">
                                                    <?php echo $get_all_his->comment; ?><br /> <small><span><strong>Admin</strong> at <strong><?php echo $get_reasons->CreatedAt; ?></strong></span></small>
                                                </p> <?php
                                            }
                                        }
                                    //</cfsavecontent> ?>
                                    <div data-toggle="collapse" data-target="#accordion-<?php echo $get_reasons->ID; ?>"class="accordion-section-title <?php if(isset($_GET["ticketid"]) AND $_GET["ticketid"] == $get_reasons->ID){ echo "active"; } echo $solved_class; ?>">
                                        <i class="fa fa-ticket" aria-hidden="true"></i>
                                        <?php echo "#".$get_reasons->ID." ".$get_reasons->subject;
                                        if($is_read !== 0){ 
                                            ?><span class="unread_count" title="Unread message" id="<?php echo $get_reasons->ID; ?>"><?php echo $is_read; ?></span> <?php
                                        } ?>
                                    </div> <?php
                                    $total_unread = $total_unread + $is_read; ?>
                                    <div id="accordion-<?php echo $get_reasons->ID; ?>" <?php if(isset($_GET["ticketid"]) AND $_GET["ticketid"] == $get_reasons->ID){ echo " style='display:block'"; } ?> class="accordion-section-content <?php if(isset($_GET["ticketid"]) AND $_GET["ticketid"] == $get_reasons->ID){ echo 'open'; } ?> collapse">
                                        <div class="comments"> <?php
                                            if($get_reasons->CommentBy == $_COOKIE["user_account_id"]){ ?>
                                                <p class="comment"><?php echo $get_reasons->Comment; ?><br /><small><span><strong>You</strong> at <strong><?php echo $get_reasons->CreatedAt; ?></strong></span></small></p> <?php
                                            } else { ?>
                                                <p class="reply"><?php echo $get_reasons->Comment; ?><br /> <small><span><strong>Admin</strong> at <strong><?php echo $get_reasons->CreatedAt; ?></strong></span></small></p> <?php
                                            }
                                            $tickets_discuss;?>
                                        </div>
                                        <hr /> <?php
                                        if($get_reasons->TicketStatus == 0){ ?>
                                            <form action="" method="post" id="<?php echo $currentrow; ?>" class="comment_form right">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <input type="hidden" name="ticket_id" value="<?php echo $get_reasons->ID; ?>" />
                                                            <input type="hidden" name="subject" value="<?php echo $get_reasons->subject; ?>" />
                                                            <input type="hidden" name="reasons" value="<?php echo $get_reasons->ReasonID; ?>" />
                                                            <input type="hidden" name="commentID" value="<?php echo $get_reasons->ID; ?>"  />
                                                            
                                                            <textarea class="form-control" name="comment" placeholder="Enter your Text." rows="4" cols="80"></textarea><br /><br />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="button" class="btn myRingButton submit_form" name="submit" value="Submit" />   
                                                    </div>
                                                </div>    
                                            </form>
                                        <span class="loading"></span> <?php
                                        } else {
                                            echo "Ticket Resolved."; 
                                        } ?>
                                    </div><!--end .accordion-section-content-->
                                </div><!--end .accordion-section-->
                            </div><!--end .accordion-->
                        </td>
                    </tr> <?php
                    $currentrow = $currentrow + 1;
				} ?>
			</tbody>
		</table>
        <span class="unread_count total pull-right"><strong>Total Unread</strong>: <?php echo $total_unread; ?></span>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#ticket-table').DataTable({
		});

        function close_accordion_section() {
			$('.accordion .accordion-section-title').removeClass('active');
			$('.accordion .accordion-section-content').slideUp(300).removeClass('open');
		}

        $('#ticket-form').validate({
            errorPlacement: function(error, element){
                $(element).closest('form').find('label[for="' + element.attr('id') + '"]').append(error);
            },
            errorElement: 'em'
        });
	});
    function getsublevels(val,fieldname,count){
        count = count +1
		if($("#levels option:selected").attr("data-parent") != "Parent"){
			$("#parent_Customer").val('');	
			if(val != ""){
                alert("hello");
				$.post('/cfc/utils.cfc?method=getsubusers&returnFormat=plain&fieldname='+fieldname+'&fieldval='+val+'&count='+count,function(data){
					data = $.trim(data);
					if(data){
						if(data != "false"){
							if(count==1){
								$(".dynamic_selects").html("<br>"+data);
							}else{
								count = count-1;
								$(".dynamic_selects .outer_"+count+ " div").html("<br>"+data);
							}
						}
					}
				});
			}else{
				$(".dynamic_selects").html('');
			}
			close_loading();
		}else{
			$("#parent_Customer").val($("#levels option:selected").val());
			$(".dynamic_selects").html('');	
		}
	}
</script>    
