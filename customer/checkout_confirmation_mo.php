<?php

include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './lib/epayment/includes/configure.php';
include './lib/epayment/classes/payment.php';
include './lib/epayment/classes/order.php';
include './lib/epayment/classes/currencies.php';
include './lib/epayment/includes/general.php';
include './lib/epayment/includes/html_output.php';
include './lib/epayment/includes/loadconfiguration.php';
include './lib/customer.smarty.php';

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}
$currencies_list = get_currencies();
$mycur = 1;
$two_currency = false;
$vat = $_SESSION["vat"];
$DBHandle = DbConnect();

if (isset($currencies_list[strtoupper($_SESSION['currency'])][2]) && is_numeric($currencies_list[strtoupper($_SESSION['currency'])][2])) {
    $mycur = $currencies_list[strtoupper($_SESSION['currency'])][2];
    $display_currency =strtoupper($_SESSION['currency']);
    if(strtoupper($_SESSION['currency'])!=strtoupper(BASE_CURRENCY))$two_currency=true;
}


getpost_ifset(array('amount','payment','authorizenet_cc_expires_year','authorizenet_cc_owner','authorizenet_cc_expires_month','authorizenet_cc_number','authorizenet_cc_expires_year'));
// PLUGNPAY
getpost_ifset(array('credit_card_type', 'plugnpay_cc_owner', 'plugnpay_cc_number', 'plugnpay_cc_expires_month', 'plugnpay_cc_expires_year', 'cvv'));
//Iridium
getpost_ifset(array('CardName', 'CardNumber', 'ExpiryDateMonth', 'ExpiryDateYear', 'CV2'));
// Invoice
getpost_ifset(array('item_id','item_type'));


$vat_amount= $amount * $vat / 100;
$total_amount = $amount + ($amount * $vat / 100);
if (empty($item_id)) {
    $item_id = 0;
}
if (!isset($item_type) || is_null($item_type)) {
    $item_type = '';
}
if(!isset($amount)) {
    Header ("Location: checkout_payment_mo.php");
    die;
}

if ($item_type == "invoice" && is_numeric($item_id)) {
    $table_invoice = new Table("cc_invoice", "status, paid_status");
    $clause_invoice = "id = ".$item_id;
    $result= $table_invoice -> Get_list($DBHandle,$clause_invoice);
    if (is_array($result) && $result[0]['status']==1 && $result[0]['paid_status']==0 ) {
        $table_invoice_item = new Table("cc_invoice_item","COALESCE(SUM(price*(1+(vat/100))),0)");
        $clause_invoice_item = "id_invoice = ".$item_id;
        $result= $table_invoice_item -> Get_list($DBHandle,$clause_invoice_item);
        $amount = $result[0][0];
        $amount = ceil($amount*100) / 100;
        $vat_amount= $amount * $vat / 100;
        $total_amount = $amount + ($amount * $vat / 100);
    } else {
        Header ("Location: checkout_payment_mo.php");
        die;
    }
}

$HD_Form = new FormHandler("cc_payment_methods", "payment_method");
$_SESSION["p_module"] = $payment;
$_SESSION["p_amount"] = 3;

$HD_Form -> setDBHandler(DbConnect());
$HD_Form -> init();

$paymentTable = new Table();
$time_stamp = date("Y-m-d H:i:s");
$amount_string = sprintf("%.3F", $total_amount);

if (strtoupper($payment)=='PLUGNPAY') {
    $QUERY_FIELDS = "cardid, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, cvv, credit_card_type, currency , item_id , item_type";
    $QUERY_VALUES = "'".$_SESSION["card_id"]."','$amount_string', '".$_SESSION["vat"]."', '$payment','$plugnpay_cc_owner','".substr($plugnpay_cc_number,0,4)."XXXXXXXXXXXX','".$plugnpay_cc_expires_month."-".$plugnpay_cc_expires_year."','$time_stamp', '$cvv', '$credit_card_type', '".BASE_CURRENCY."' , '$item_id', '$item_type'";
} elseif (strtoupper($payment)=='IRIDIUM') {
    $QUERY_FIELDS = "cardid, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, currency, item_id, item_type";
    $QUERY_VALUES = "'".$_SESSION["card_id"]."','$amount_string', '".$_SESSION["vat"]."', '$payment','$CardName','".substr($CardNumber,0,4)."XXXXXXXXXXXX','".$ExpiryDateMonth."-".$ExpiryDateYear."','$time_stamp', '".BASE_CURRENCY."' , '$item_id','$item_type'";
} else {
    $QUERY_FIELDS = "cardid, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, currency, item_id, item_type";
    $QUERY_VALUES = "'".$_SESSION["card_id"]."','$amount_string', '".$_SESSION["vat"]."', '$payment','$authorizenet_cc_owner','".substr($authorizenet_cc_number,0,4)."XXXXXXXXXXXX','".$authorizenet_cc_expires_month."-".$authorizenet_cc_expires_year."','$time_stamp', '".BASE_CURRENCY."' , '$item_id','$item_type'";
}

$transaction_no = $paymentTable -> Add_table($HD_Form -> DBHandle, $QUERY_VALUES, $QUERY_FIELDS, 'cc_epayment_log', 'id');

$key = securitykey(EPAYMENT_TRANSACTION_KEY, $time_stamp."^".$transaction_no."^".$amount_string."^".$_SESSION["card_id"]."^".$item_id."^".$item_type);
if (empty($transaction_no)) {
    exit(gettext("No Transaction ID found"));
}

$HD_Form -> create_toppage ($form_action);

$payment_modules = new payment($payment);
$order = new order($amount_string);

if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
}

// #### HEADER SECTION
$smarty->display( 'header.tpl');

if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
} else {
    $form_action_url = tep_href_link("checkout_process.php", '', 'SSL');
}

echo tep_draw_form('checkout_confirmation_mo.php', $form_action_url, 'post', null, $payment);

if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button($transaction_no, $key, '_APP');
}
?>
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content" style="padding: 0px!important;
    height: -webkit-fill-available;!important"> 
    <div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
                   
                    <font class="kt-portlet__head-title"><?php echo gettext("Please confirm your order"); ?></font>
				</div>
			</div>
			<!--begin::Form-->
			<form action="" method="" class="kt-form">
				<div class="kt-portlet__body">
                    <table width=80% align=center class="infoBox">
                        <tr>
                            <td width=50%><div align="right"><?php echo gettext("Payment Method");?>:&nbsp;</div></td>
                            <td width=50%><?php echo strtoupper($payment)?></td>
                        </tr>
                        <?php if (strcasecmp("invoice",$item_type)!=0) {?>
    						<tr>
                                <td align=right><?php echo gettext("Amount")?>: &nbsp;</td>
                                <td align=left>
                                    <?php
                                        echo round($amount,2)." ".strtoupper(BASE_CURRENCY);
                                        if ($two_currency) {
                                           echo " - ".round($amount/$mycur,2)." ".strtoupper($_SESSION['currency']);
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align=right><?php echo gettext("VAT")."(".$vat."%)"?>: &nbsp;</td>
                                <td align=left>
                                    <?php
                                        echo round($vat_amount,2)." ".strtoupper(BASE_CURRENCY);
                                        if ($two_currency) {
                                            echo " - ".round($vat_amount/$mycur,2)." ".strtoupper($_SESSION['currency']);
                                        }
                                    ?> 
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td align=right><?php echo gettext("Total Amount Incl. VAT")?>: &nbsp;</td>
                            <td align=left>
                                <?php
                                       echo round($total_amount,2)." ".strtoupper(BASE_CURRENCY);
                                    
    									
    								// 	$pay_amount = round($total_amount / $mycur, 2);
    								// 	echo $pay_amount;
                                       // echo " - ".round($total_amount / $mycur, 2)." ".strtoupper($_SESSION['currency']);
                                    
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>

                    </table>
                </div>
                <div class="kt-portlet__foot text-center">
                    <?php echo gettext("Please click this button to confirm your order")?>
                    <input type="submit" name="confirm" alt="Pay Now" value="<?php echo gettext("Pay Now")?>" class="btn btn-brand">
                </div>
            </form>
        </div> 
    </div>
</div>
<?php
$smarty->display( 'footer_mo.tpl');
