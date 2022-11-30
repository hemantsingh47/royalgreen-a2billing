<?php
include './lib/customer.defines.php';

getpost_ifset(array('transactionID', 'sess_id', 'key', 'currency', 'md5sig', 'status'));

$trans_str = "transactionID=$transactionID";

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."EPAYMENT : $trans_str - transactionKey=$key \n -POST Var \n".print_r($_POST, true));

if (!intval($transactionID) > 0) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : Wrong transactionID ($transactionID) provided in request");
    exit();
}

if (empty($sess_id)) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : Error no session id provided in return url to payment module");
    exit();
}

include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './lib/epayment/classes/payment.php';
include './lib/epayment/classes/order.php';
include './lib/epayment/classes/currencies.php';
include './lib/epayment/includes/general.php';
include './lib/epayment/includes/html_output.php';
include './lib/epayment/includes/configure.php';
include './lib/epayment/includes/loadconfiguration.php';

$DBHandle_max  = DbConnect();
$paymentTable = new Table();

if (DB_TYPE == "postgres") {
    $NOW_2MIN = " creationdate <= (now() - interval '2 minute') ";
} else {
    $NOW_2MIN = " creationdate <= DATE_SUB(NOW(), INTERVAL 2 MINUTE) ";
}

// Status - New 0 ; Proceed 1 ; In Process 2
$QUERY = "SELECT * " .
         " FROM cc_epayment_log " .
         " WHERE id = ".$transactionID." AND (status = 0 OR (status = 2 AND $NOW_2MIN))";
$transaction_data = $paymentTable->SQLExec ($DBHandle_max, $QUERY);
if (empty($transaction_data) || (!is_array($transaction_data) && count($transaction_data) == 0)) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).
        ' line:'.__LINE__."- $trans_str : ERROR INVALID TRANSACTION ID PROVIDED, TRANSACTION ID =".$transactionID);
    Header ("Location: userinfo.php");
    exit();
}
$transaction_data = reset($transaction_data);
$card_id = $transaction_data['cardid'];
$item_id = $transaction_data['item_id'];
$amount = $transaction_data['amount'];
$currency = $transaction_data['currency'];
$item_type = $transaction_data['item_type'];
$payment_method = $transaction_data['paymentmethod'];

//GETTING CUSTOMER INFORMATION
$inst_table = new Table();
$CUSTOMER_QUERY = "SELECT * FROM cc_card  WHERE id = '".$card_id."' LIMIT 1";
$customer_res = $inst_table -> SQLExec($DBHandle_max, $CUSTOMER_QUERY);

if (!$customer_res || !is_array($customer_res)) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).
        ' line:'.__LINE__."- $trans_str : ERROR LOADING ACCOUNT INFORMATION, CARD ID =".$card_id);
    Header ("Location: userinfo.php");
    exit ();
}
write_log(LOGFILE_EPAYMENT, basename(__FILE__).
    ' line:'.__LINE__."- $trans_str : EPAYMENT INITIATED: TRANSACTIONID = ".$transactionID.
    " FROM ".$payment_method."; FOR CUSTOMER ID ".$card_id."; OF AMOUNT ".$amount);
//array custome rinformation
$customer = reset($customer_res);

//Update the Transaction Status to 1 In progress
$QUERY = "UPDATE cc_epayment_log SET status = 1 WHERE id = ".$transactionID;
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY = $QUERY");

$paymentTable->SQLExec ($DBHandle_max, $QUERY);

//DECLAIRING PAYMENT MODULE FOR FURTHER PROCESS
$payment_module = new payment($payment_method);

$post_data = array();
$post_data['total_amount'] = $amount;
$post_data['currency'] = $currency;
$post_data['tran_id'] = $transactionID;

# CUSTOMER INFORMATION
$post_data['cus_name'] = !empty($customer['firstname']) ? $customer['firstname'] : "NA";
$post_data['cus_email'] = !empty($customer['email']) ? $customer['email'] : "NA";
$post_data['cus_add1'] = !empty($customer['address']) ? $customer['address'] : "NA";
$post_data['cus_add2'] = !empty($customer['address']) ? $customer['address'] : "NA";
$post_data['cus_city'] = !empty($customer['city']) ? $customer['city'] : "NA";
$post_data['cus_state'] = !empty($customer['state']) ? $customer['state'] : "NA";
$post_data['cus_postcode'] = !empty($customer['zipcode']) ? $customer['zipcode'] : "NA";
$post_data['cus_country'] = !empty($customer['country']) ? $customer['country'] : "NA";
$post_data['cus_phone'] = !empty($customer['phone']) ? $customer['phone'] : "0";
$post_data['cus_fax'] = "0";

# SHIPMENT INFORMATION
$post_data["shipping_method"] = "NO";
$post_data['ship_name'] = "Store Test";
$post_data['ship_add1'] = !empty($customer['address']) ? $customer['address'] : "NA";
$post_data['ship_add2'] = !empty($customer['address']) ? $customer['address'] : "NA";
$post_data['ship_city'] = !empty($customer['city']) ? $customer['country'] : "NA";
$post_data['ship_state'] = !empty($customer['country']) ? $customer['country'] : "NA";
$post_data['ship_postcode'] = !empty($customer['country']) ? $customer['country'] : "NA";
$post_data['ship_phone'] = "";
$post_data['ship_country'] = !empty($customer['country']) ? $customer['country'] : "NA";

$post_data['emi_option'] = "1";
$post_data["product_category"] = "Voip";
$post_data["product_profile"] = "general";
$post_data["product_name"] = "Recharge";
$post_data["num_of_item"] = "1";
$post_data["value_a"] = $key;
$post_data["value_b"] = $sess_id;
$security_verify = true;


$transaction_detail = serialize($_POST);

$currencyObject = new currencies();
$currencies_list = get_currencies();

switch ($payment_method) {
    case "sslcommerz":
        $payment_result = $payment_module->make_payment($post_data, 'hosted');
        if(!empty($payment_result['failedreason'])) {
            write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- SSLCommerz ERROR : \n\n".print_r($payment_result, true));
            Header ("Location: checkout_success.php?errcode=-2");
            exit();
            //$security_verify = false;
        }
        

        break;
    default:
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-NO SUCH EPAYMENT FOUND");
        Header ("Location: checkout_success.php?errcode=6");
        exit();
        
}
exit();
