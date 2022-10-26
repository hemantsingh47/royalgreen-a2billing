<?php

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include '../lib/epayment/includes/configure.php';
include '../lib/epayment/classes/payment.php';
include '../lib/epayment/classes/order.php';
include '../lib/epayment/classes/currencies.php';
include '../lib/epayment/includes/general.php';
include '../lib/epayment/includes/html_output.php';
include '../lib/epayment/includes/loadconfiguration.php';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$currencies_list = get_currencies();
$two_currency = false;
if (!isset($currencies_list[strtoupper($_SESSION['currency'])][2]) || !is_numeric($currencies_list[strtoupper($_SESSION['currency'])][2]) ) {
    $mycur = 1;
} else {
    $mycur = $currencies_list[strtoupper($_SESSION['currency'])][2];
    $display_currency =strtoupper($_SESSION['currency']);
    if(strtoupper($_SESSION['currency'])!=strtoupper(BASE_CURRENCY))$two_currency=true;
}

$vat=$_SESSION["vat"];

getpost_ifset(array('amount','payment','authorizenet_cc_expires_year','authorizenet_cc_owner','authorizenet_cc_expires_month','authorizenet_cc_number','authorizenet_cc_expires_year'));
// PLUGNPAY
getpost_ifset(array('credit_card_type', 'plugnpay_cc_owner', 'plugnpay_cc_number', 'plugnpay_cc_expires_month', 'plugnpay_cc_expires_year', 'cvv'));
//invoice
getpost_ifset(array('item_id','item_type'));

$vat_amount= $amount*$vat/100;
$total_amount = $amount+($amount*$vat/100);

$HD_Form = new FormHandler("cc_payment_methods","payment_method");

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();
$_SESSION["p_module"] = $payment;
$_SESSION["p_amount"] = 3;

$paymentTable = new Table();
$time_stamp = date("Y-m-d H:i:s");

if (strtoupper($payment)=='PLUGNPAY') {
    $QUERY_FIELDS = "agent_id, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, cvv, credit_card_type, currency";
    $QUERY_VALUES = "'".$_SESSION["agent_id"]."','$total_amount', '".$_SESSION["vat"]."', '$payment','$plugnpay_cc_owner','".substr($plugnpay_cc_number,0,4)."XXXXXXXXXXXX','".$plugnpay_cc_expires_month."-".$plugnpay_cc_expires_year."','$time_stamp', '$cvv', '$credit_card_type', '".BASE_CURRENCY."'";
} else {
    $QUERY_FIELDS = "agent_id, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, currency";
    $QUERY_VALUES = "'".$_SESSION["agent_id"]."','$total_amount', '".$_SESSION["vat"]."', '$payment','$authorizenet_cc_owner','".substr($authorizenet_cc_number,0,4)."XXXXXXXXXXXX','".$authorizenet_cc_expires_month."-".$authorizenet_cc_expires_year."','$time_stamp', '".BASE_CURRENCY."'";
}
$transaction_no = $paymentTable->Add_table ($HD_Form -> DBHandle, $QUERY_VALUES, $QUERY_FIELDS, 'cc_epayment_log_agent', 'id');

$key = securitykey(EPAYMENT_TRANSACTION_KEY, $time_stamp."^".$transaction_no."^".$total_amount."^".$_SESSION["agent_id"]);

if (empty($transaction_no)) {
    exit(gettext("No Transaction ID found"));
}

$HD_Form -> create_toppage ($form_action);

$payment_modules = new payment($payment);
$order = new order($total_amount);

if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
}

// #### HEADER SECTION
$smarty->display( 'main.tpl');

if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
} else {
    $form_action_url = tep_href_link("checkout_process.php", '', 'SSL');
}

echo tep_draw_form('checkout_confirmation.php', $form_action_url, 'post');

if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button($transaction_no, $key);
}
?>



<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Order Confirmation                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Payments                 </a>
							<span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Order Confirmation                     </a>
                         
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
       
    </div>
</div>

<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h1 class="kt-portlet__head-title">
				<?php echo gettext("Please confirm your order")?>
				
			</h1>
		</div>
	</div>
	
	 <div class="kt-portlet__body">
	 
	 <div class="col-md-12">



<table width=100% align=center class="infoBox">
 
<tr>
    <td width=50%>&nbsp;</td>
    <td width=50%>&nbsp;</td>
</tr>
<tr>
    <td width=50%><div align="right"><?php echo gettext("Payment Method");?>:&nbsp;</div></td>
    <td width=50%><?php echo strtoupper($payment)?></td>
</tr>
<tr>
    <td align=right><?php echo gettext("Amount")?>: &nbsp;</td>
    <td align=left>
    <?php
     echo round($amount,2)." ".strtoupper(BASE_CURRENCY);
     if ($two_currency) {
                    echo " - ".round($amount/$mycur,2)." ".strtoupper($_SESSION['currency']);
     }
     ?> </td>
</tr>
<tr>
    <td align=right><?php echo gettext("VAT")."(".$vat."%)"?>: &nbsp;</td>
    <td align=left>
    <?php
     echo round($vat_amount,2)." ".strtoupper(BASE_CURRENCY);
     if ($two_currency) {
                    echo " - ".round($vat_amount/$mycur,2)." ".strtoupper($_SESSION['currency']);
     }
     ?> </td>
</tr>
<tr>
    <td align=right><?php echo gettext("Total Amount Incl. VAT")?>: &nbsp;</td>
    <td align=left>
    <?php
     echo round($total_amount,2)." ".strtoupper(BASE_CURRENCY);
     if ($two_currency) {
                    echo " - ".round($total_amount/$mycur,2)." ".strtoupper($_SESSION['currency']);
     }
     ?> </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
</table>
<br>


<div align="center" class="kt-portlet__foot">
                <div class="form-actions">
                    <b><?php echo gettext("Please click this button to confirm your order")?></b>&nbsp;
                    <input type="submit" name="confirm" alt="Confirm Order" value="Confirm Order" class="btn btn-brand">
                </div>
            </div>  

<!--<table class="infoBox" width="100%" cellspacing="0" cellpadding="2" align=center>
   <tr height="25">
   <td  align=left class="main"> <b><?php echo gettext("Please click button to confirm your order")?>.</b>
   </td>
          <td align=right halign=center >
            <input type="image" src="<?php echo Images_Path;?>/button_confirm_order.gif" alt="Confirm Order" border="0" title="Confirm Order">
             &nbsp;</td>
          </tr>
</table>-->
</form>
</div>
</div>
</div>
</div>

<?php

// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
