<?php

include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/customer.smarty.php';
include './lib/support/classes/ticket.php';
include './lib/support/classes/comment.php';
include './lib/epayment/includes/general.php';

if (!has_rights(ACX_SUPPORT)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array (
    'result',
    'id',
    'action',
    'status',
    'comment',
    'idc'
));

if ($result == "success") {
    $message = gettext("Ticket updated successfully");
}

if (isset ($id)) {
    $ticketID = $id;
} else {
    exit (gettext("Ticket ID not found"));
}

if (tep_not_null($action)) {
    switch ($action) {
        case 'change' :
            $DBHandle = DbConnect();
            $instance_sub_table = new Table("cc_ticket", "*");
            $instance_sub_table->Update_table($DBHandle, "status = '" . $status . "'", "id = '" . $id . "'");
            $ticket = new Ticket($ticketID);
            $ticket->insertComment($comment, $_SESSION['card_id'], 0);
            tep_redirect("billing_ticket_view.php?" . "id=" . $id . "&result=success");
        case 'view_comment' :
            $DBHandle = DbConnect();
            $instance_sub_table = new Table("cc_ticket_comment", "*");
            $instance_sub_table->Update_table($DBHandle, "viewed_cust = '0'", "id = '" . $idc . "'");
            tep_redirect("billing_ticket_view.php?id=" . $id . "#nav" . $idc);
            break;
        case 'view_ticket' :
            $DBHandle = DbConnect();
            $instance_sub_table = new Table("cc_ticket", "*");
            $instance_sub_table->Update_table($DBHandle, "viewed_cust = '0'", "id = '" . $id . "'");
            tep_redirect("billing_ticket_view.php?id=" . $id);
            break;
    }
}

$ticket = new Ticket($ticketID);
$comments = $ticket->loadComments();

$ticket = new Ticket($ticketID);
$comments = $ticket->loadComments();
$DBHandle = DbConnect();
$instance_sub_table = new Table("cc_ticket", "*");
    if ($ticket->getViewed(2)) {
    $instance_sub_table->Update_table($DBHandle, "viewed_cust = '0'", "id = '" . $id . "'");
    }
$instance_sub_table = new Table("cc_ticket_comment", "*");
foreach ($comments as $comment) {
    if ($comment->getViewed(2)) {
    $instance_sub_table->Update_table($DBHandle, "viewed_cust = '0'", "id = '" . $comment->getId() . "'");
    }
}

$smarty->display('main.tpl');

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Support                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer Tickets                      </a>
							<span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Ticket View                      </a>
                         
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
       
    </div>
</div>

<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h1 class="kt-portlet__head-title">
				<?php echo gettext("Customer Ticket View"); ?>
				
			</h1>
		</div>
	</div>
	
	 <div class="kt-portlet__body">
	 
	 <div class="col-md-12">

<table class="table widget-box" style="border-left: 1px solid #eee; border-right: 1px solid #eee; border-top: 1px solid #eee;">
    <tr class="form_head">
        <td ><b><?php echo gettext("TICKET : "); ?></b> </td> <td><b><?php echo $ticket->getTitle();  ?></b></td></tr>
		<tr>
		
		
        <td align="left" ><b>Number :</b> </td><td> <font color="Red"> <b><?php echo $ticket->getId(); ?></b></font></td>
    </tr>
    
    <tr>
        <td >
         <font style="font-weight:bold; " ><?php echo gettext("BY : "); ?></font> </td><td> <?php echo $ticket->getCreatorname();  ?>
        </td>
    </tr>
    <tr>
        <td>
         <font style="font-weight:bold; " ><?php echo gettext("PRIORITY : "); ?></font> </td><td> <?php echo $ticket->getPriorityDisplay();  ?>
         </td>
		 
		 </tr>
		 <tr>
        <td align="left">
        <font style="font-weight:bold; " ><?php echo gettext("DATE : "); ?></font> </td><td> <?php echo $ticket->getCreationdate();  ?>
        </td>
    </tr>
    <tr>
        <td >
         <font style="font-weight:bold; " ><?php echo gettext("COMPONENT : "); ?></font> </td>
<td>		 <?php echo $ticket->getComponentname();  ?>
        </td>
    </tr>
    <tr>
        <td>
        
        <font style="font-weight:bold; " ><?php echo gettext("DESCRIPTION : "); ?></font>  </td>
<td align="left">		<?php echo $ticket->getDescription();  ?></td>
    </tr>
    <?php if ($ticket->getViewed(0)) { ?>
    <tr>
        <td colspan="2" align="right">
         
        <strong style="font-size:8px; color:#B00000; "> &nbsp;NEW&nbsp;</strong> </td>
    </tr>
    <?php } else {
        ?>

    <?php
    } ?>
    <tr >
    <td colspan="2" align="center"><br/><font color="Green"><b><?php echo $message ?></b></font></td>
    </tr>
</table>

 
  <form action="<?php echo $PHP_SELF.'?id='.$ticket->getId(); ?>" method="post" >
     <input id="action" type="hidden" name="action" value="change"/>
    <input id="idc" type="hidden" name="idc" value=""/>
    <table class="table">
      <?php
       $return_status = Ticket::getPossibleStatus($ticket->getStatus(),true);
      if (!is_null($return_status)) {

           ?>
        <tr>
            <td>	<font style="font-weight:bold; " ><?php echo gettext("STATUS : "); ?></font>
</td>
<td>
            <select name="status" class="form-control">
             <?php
                foreach ($return_status as $value) {
                     if ($ticket->getStatus()==$value["id"]) {
                         echo '<option selected "value="'.$value["id"] .'"> '.$value["name"].'</option> ' ;
                     } else {
                         echo '<option value="'.$value["id"] .'"> '.$value["name"].'</option> ' ;
                     }
                }
              ?>

            </select>
            </td>
        </tr>
     <?php } ?>

        <tr>
            <td valign="top"><font style="font-weight:bold; " ><?php echo gettext("COMMENT : "); ?>

             </td>
      
            <td >
             <textarea class="form-control" name="comment" cols="100" rows="10" style="width: 100%!important;"></textarea>

             </td>
        </tr>
        <tr>
            <td align="right">
			</td>
			 <td align="right">

                <input class="btn btn-primary" type="submit" value="UPDATE"/>

             </td>
        </tr>

    </table>
  </form>
  
 

<?php

foreach ($comments as $comment) {

?>

 
     <table id="nav<?php echo $comment->getId(); ?>" class="table widget-box">
      <tr class="form_head">
          <td>
           BY :  <?php echo $comment->getCreatorname(); ?>  </td>
           <td align="right"> <?php echo $comment->getCreationdate() ?> </td>
      </tr>
    
    <tr>
        <td colspan="2"> <pre><?php echo $comment->getDescription(); ?></pre> </td>
    </tr>

    <?php if ($comment->getViewed(0)) { ?>
    <tr>
        <td colspan="2" align="right">
        
        <strong style="font-size:8px; color:#B00000; "> &nbsp;NEW&nbsp;</strong> </td>
    </tr>
    <?php } else {
        ?>

    <?php
    } ?>
    </table>
	 

	 
	
<?php

}

$smarty->display('footer.tpl');
