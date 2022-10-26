<?php

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_BILLING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id'));

if (empty($id)) {
    header("Location: billing_entity_payment.php?atmenu=payment&section=2");
}

$DBHandle  = DbConnect();

$payment_table = new Table('cc_logpayment','*');
$payment_clause = "id = ".$id;
$payment_result = $payment_table -> Get_list($DBHandle, $payment_clause, 0);
$payment = $payment_result[0];

if (empty($payment)) {
    header("Location: billing_entity_payment.php?atmenu=payment&section=2");
}

// #### HEADER SECTION
$smarty->display('main.tpl');
?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Payment Info                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                    </a>
                                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer's Payments                    </a>
							 
                         
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
       
    </div>
</div>

<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h1 class="kt-portlet__head-title">
				<?php echo gettext("PAYMENT INFO"); ?>
				
			</h1>
		</div>
	</div>
	
	 <div class="kt-portlet__body">
	 
	 <div class="col-md-12">


<table style="border-left: 1px solid #eee; border-right: 1px solid #eee; border-top: 1px solid #eee;" class="table widget-box">
   
   <tr height="20px">
        <td  class="form_head">
            <?php echo gettext("ACCOUNT NUMBER") ?> :
        </td>
        <td class="tableBodyRight" >
            <?php
            if (has_rights (ACX_CUSTOMER)) {
                echo infocustomer_id($payment['card_id']);
            } else {
                echo nameofcustomer_id($payment['card_id']);
            }
            ?>
        </td>
   </tr>
   <tr height="20px">
        <td  class="form_head">
            <?php echo gettext("AMOUNT") ?> :
        </td>
        <td class="tableBodyRight" >
            <?php echo $payment['payment']." ".strtoupper(BASE_CURRENCY);?>
        </td>
   </tr>
       <tr height="20px">
        <td  class="form_head">
            <?php echo gettext("CREATION DATE") ?> :
        </td>
        <td class="tableBodyRight" >
            <?php echo $payment['date']?>
        </td>
    </tr>
   <tr height="20px">
        <td  class="form_head">
            <?php echo gettext("PAYMENT TYPE") ?> :
        </td>
        <td class="tableBodyRight" >
            <?php
            $list_type = Constants::getRefillType_List();
            echo $list_type[$payment['payment_type']][0];?>
        </td>
   </tr>
   <tr height="20px">
        <td  class="form_head">
            <?php echo gettext("DESCRIPTION ") ?> :
        </td>
        <td class="tableBodyRight" >
            <?php echo $payment['description']?>
        </td>
    </tr>
       <?php if (!empty($payment['id_logrefill'])) { ?>
       <tr height="20px">
        <td  class="form_head">
            <?php echo gettext("LINK REFILL") ?> :
        </td>
        <td class="tableBodyRight" >
            <a href="billing_refill_info.php?id=<?php echo $payment['id_logrefill']?>"> <img src="<?php echo Images_Path."/link.png"?>" border="0" title="<?php echo gettext("Link to the refill")?>" alt="<?php echo  gettext("Link to the refill")?>"></a>
        </td>
    </tr>
       <?php } ?>

 </table>
 <br/>
<div style="width : 100%; text-align : right; margin-left:auto;margin-right:auto;" >
     <a class="btn btn-primary"  href="billing_entity_payment.php?atmenu=payment&section=2">
        <!--<img src="<?php echo Images_Path_Main;?>/icon_arrow_orange.gif"/>-->
        <?php echo gettext("CUSTOMER'S PAYMENT LIST"); ?>
    </a>
</div>


</div>
</div>
</div>
</div>

<?php

$smarty->display( 'footer.tpl');
