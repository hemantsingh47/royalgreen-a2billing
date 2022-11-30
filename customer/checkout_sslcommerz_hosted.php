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



            write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." Got " . curl_error($ch) . " when processing IPN data");
            // The IPN is verified, process it
            write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-PAYPAL Transaction Verification Status: Verified ");
            // IPN invalid, log for manual investigation
            write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-PAYPAL Transaction Verification Status: Failed \nreq: $req\nres: $res");
if (empty($transaction_data['vat']) || !is_numeric($transaction_data['vat'])){
    $VAT = 0;
} else {
    $VAT = $transaction_data['vat'];
}

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."curr amount $currAmount $currCurrency BASE_CURRENCY=".BASE_CURRENCY);
$amount_paid = convert_currency($currencies_list, $currAmount, $currCurrency, BASE_CURRENCY);
$amount_without_vat = $amount_paid / (1+$VAT/100);

//If security verification fails then send an email to administrator as it may be a possible attack on epayment security.
if ($security_verify == false) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- security_verify == False | END");
    try {
        //TODO create mail class for agent
        $mail = new Mail('epaymentverify', $id);
    } catch (A2bMailException $e) {
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : ERROR NO EMAIL TEMPLATE FOUND");
        exit();
    }
    $mail->replaceInEmail(Mail::$TIME_KEY,date("y-m-d H:i:s"));
    $mail->replaceInEmail(Mail::$PAYMENTGATEWAY_KEY, $payment_method);
    $mail->replaceInEmail(Mail::$ITEM_AMOUNT_KEY, $amount_paid.$currCurrency);

    // Add Post information / useful to track down payment transaction without having to log
    $mail->AddToMessage("\n\n\n\n"."-POST Var \n".print_r($_POST, true));
    $mail->send(ADMIN_EMAIL);
    exit();
}

$newkey = securitykey(EPAYMENT_TRANSACTION_KEY, $transaction_data['creationdate']."^".$transactionID."^".$amount."^".$card_id."^".$item_id."^".$item_type);
if ($newkey == $key) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."----------- Transaction Key Verified ------------");
} else {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."----NEW KEY =".$newkey." OLD KEY= ".$key." ------- Transaction Key Verification Failed:".$transaction_data['creationdate']."^".$transactionID."^".$amount."^".$card_id." ------------\n");
    exit();
}
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : ---------- TRANSACTION INFO ------------\n".print_r($transaction_data,1));
$payment_modules = new payment($payment_method);
// load the before_process function from the payment modules
//$payment_modules->before_process();

$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, currency, useralias, uipass " .
         "FROM cc_card WHERE id = '".$card_id."'";
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax) {
    $numrow = $resmax -> RecordCount();
} else {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : ERROR NO SUCH CUSTOMER EXISTS, CUSTOMER ID = ".$card_id);
    exit(gettext("No Such Customer exists."));
}
$customer_info = $resmax -> fetchRow();
$nowDate = date("Y-m-d H:i:s");

$pmodule = $payment_method;

$orderStatus = $payment_modules->get_OrderStatus();

if (empty($item_type)) {
    $transaction_type = 'balance';
} else {
    $transaction_type = $item_type;
    //Check amount
    $table_invoice_item = new Table("cc_invoice_item","COALESCE(SUM(price*(1+(vat/100))),0)");
    $clause_invoice_item = "id_invoice = ".$item_id;
    $result= $table_invoice_item -> Get_list($DBHandle,$clause_invoice_item);
    $inv_amount = ceil($result[0][0] * 100) / 100;
    $inv_vat_amount= $inv_amount * $VAT / 100;
    $inv_total_amount = $inv_amount + ($inv_amount * $VAT / 100);
    if ($inv_total_amount != $amount) {
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : ERROR PAYMENT INVOICE $inv_total_amount != $amount_paid");
        exit();
    }
}

$Query = "INSERT INTO cc_payments ( customers_id, customers_name, customers_email_address, item_name, item_id, item_quantity, payment_method, cc_type, cc_owner, " .
            " cc_number, cc_expires, orders_status, last_modified, date_purchased, orders_date_finished, orders_amount, currency, currency_value) values (" .
            " '".$card_id."', '".$customer_info[3]." ".$customer_info[2]."', '".$customer_info["email"]."', '$transaction_type', '".
            $customer_info[0]."', 1, '$pmodule', '".$_SESSION["p_cardtype"]."', '".$transaction_data[5]."', '".$transaction_data[6]."', '".
            $transaction_data[7]."',  $orderStatus, '".$nowDate."', '".$nowDate."', '".$nowDate."',  ".$amount_paid.",  '".$currCurrency."', '".
            $currencyObject->get_value($currCurrency)."' )";
$result = $DBHandle_max -> Execute($Query);

// UPDATE THE CARD CREDIT
$id = 0;
if ($customer_info[0] > 0 && $orderStatus == 2) {
    /* CHECK IF THE CARDNUMBER IS ON THE DATABASE */
    $instance_table_card = new Table("cc_card", "username, id");
    $FG_TABLE_CLAUSE_card = " username='".$customer_info[0]."'";
    $list_tariff_card = $instance_table_card -> Get_list ($DBHandle, $FG_TABLE_CLAUSE_card, null, null, null, null, null, null);
    if ($customer_info[0] == $list_tariff_card[0][0]) {
        $id = $list_tariff_card[0][1];
    }
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : CARD FOUND IN DB ($id)");
} else {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : ERROR CUSTOMER INFO OR ORDERSTATUS ($orderStatus)\n".print_r($_POST, true)."\n");
}

if ($id > 0) {
    if (strcasecmp("invoice",$item_type)!=0) {
        #Payment not related to a Postpaid invoice
        $addcredit = $amount;
        $instance_table = new Table("cc_card", "username, id");
        $param_update .= " credit = credit+'".$amount_without_vat."'";
        $FG_EDITION_CLAUSE = " id='$id'";
        $instance_table -> Update_table ($DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : Update_table cc_card : $param_update - CLAUSE : $FG_EDITION_CLAUSE");

        $table_transaction = new Table();
        $result_agent = $table_transaction -> SQLExec($DBHandle,"SELECT cc_card_group.id_agent FROM cc_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card.id = $id");
        if (is_array($result_agent) && !is_null($result_agent[0]['id_agent']) && $result_agent[0]['id_agent']>0 ) {
            $id_agent =  $result_agent[0]['id_agent'];
            $id_agent_insert = "'$id_agent'";
        } else {
            $id_agent = null;
            $id_agent_insert = "NULL";
        }

        $field_insert = "date, credit, card_id, description, agent_id";
        $value_insert = "'$nowDate', '".$amount_without_vat."', '$id', '".$payment_method."',$id_agent_insert";
        $instance_sub_table = new Table("cc_logrefill", $field_insert);
        $id_logrefill = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null, 'id');
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : Add_table cc_logrefill : $field_insert - VALUES $value_insert");

        $field_insert = "date, payment, card_id, id_logrefill, description, agent_id";
        $value_insert = "'$nowDate', '".$amount_paid."', '$id', '$id_logrefill', '".$payment_method."',$id_agent_insert ";
        $instance_sub_table = new Table("cc_logpayment", $field_insert);
        $id_payment = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : Add_table cc_logpayment : $field_insert - VALUES $value_insert");

        //ADD an INVOICE
        $reference = generate_invoice_reference();
        $field_insert = "date, id_card, title ,reference, description,status,paid_status";
        $date = $nowDate;
        $card_id = $id;
        $title = gettext("CUSTOMER REFILL");
        $description = gettext("Invoice for refill");
        $value_insert = " '$date' , '$card_id', '$title','$reference','$description',1,1 ";
        $instance_table = new Table("cc_invoice", $field_insert);
        $id_invoice = $instance_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
        //load vat of this card
        if (!empty($id_invoice)&& is_numeric($id_invoice)) {
            $amount = $amount_without_vat;
            $description = gettext("Refill ONLINE")." : ".$payment_method;
            $field_insert = "date, id_invoice ,price,vat, description";
            $instance_table = new Table("cc_invoice_item", $field_insert);
            $value_insert = " '$date' , '$id_invoice', '$amount','$VAT','$description' ";
            $instance_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
        }
        //link payment to this invoice
        $table_payment_invoice = new Table("cc_invoice_payment", "*");
        $fields = " id_invoice , id_payment";
        $values = " $id_invoice, $id_payment	";
        $table_payment_invoice->Add_table($DBHandle, $values, $fields);
        //END INVOICE

        // Agent commision
        // test if this card have a agent
        if (!empty($id_agent)) {

            //test if the agent exist and get its commission
            $agent_table = new Table("cc_agent", "commission");
            $agent_clause = "id = ".$id_agent;
            $result_agent= $agent_table -> Get_list($DBHandle,$agent_clause);
            if (is_array($result_agent) && is_numeric($result_agent[0]['commission']) && $result_agent[0]['commission']>0) {

                $field_insert = "id_payment, id_card, amount,description,id_agent,commission_percent,commission_type";
                $commission = ceil(($amount_without_vat * ($result_agent[0]['commission'])/100)*100)/100;
                $commission_percent = $result_agent[0]['commission'];

                $description_commission = gettext("AUTOMATICALY GENERATED COMMISSION!");
                $description_commission.= "\nID CARD : ".$id;
                $description_commission.= "\nID PAYMENT : ".$id_payment;
                $description_commission.= "\nPAYMENT AMOUNT: ".$amount_without_vat;
                $description_commission.= "\nCOMMISSION APPLIED: ".$commission_percent;

                $value_insert = "'".$id_payment."', '$id', '$commission','$description_commission','$id_agent','$commission_percent','0'";
                $commission_table = new Table("cc_agent_commission", $field_insert);
                $id_commission = $commission_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
                write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : Add_table cc_agent_commission : $field_insert - VALUES $value_insert");

                $table_agent = new Table('cc_agent');
                $param_update_agent = "com_balance = com_balance + '".$commission."'";
                $clause_update_agent = " id='".$id_agent."'";
                $table_agent -> Update_table ($DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
                write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : Update_table cc_agent : $param_update_agent - CLAUSE : $clause_update_agent");
            }

        }
    } else {
        #Payment related to a Postpaid invoice
        if ($item_id > 0) {
            $invoice_table = new Table('cc_invoice','reference');
            $invoice_clause = "id = ".$item_id;
            $result_invoice = $invoice_table->Get_list($DBHandle,$invoice_clause);

            if (is_array($result_invoice) && sizeof($result_invoice)==1) {
                $reference =$result_invoice[0][0];

                $field_insert = "date, payment, card_id, description";
                $value_insert = "'$nowDate', '".$amount_paid."', '$id', '(".$payment_method.") ".gettext('Invoice Payment Ref: ')."$reference '";
                $instance_sub_table = new Table("cc_logpayment", $field_insert);
                $id_payment = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
                write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : Add_table cc_logpayment : $field_insert - VALUES $value_insert");

                //update invoice to paid
                $invoice = new Invoice($item_id);
                $invoice -> addPayment($id_payment);
                $invoice -> changeStatus(1);
                $items = $invoice -> loadItems();
                foreach ($items as $item) {
                    if ($item -> getExtType() == 'DID') {
                        $QUERY = "UPDATE cc_did_use set month_payed = month_payed+1 , reminded = 0 WHERE id_did = '" . $item -> getExtId() .
                                 "' AND activated = 1 AND ( releasedate IS NULL OR releasedate < '1984-01-01 00:00:00') ";
                        $instance_table->SQLExec($DBHandle, $QUERY, 0);
                    }
                    if ($item -> getExtType() == 'SUBSCR') {
                        //Load subscription
                        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- Type SUBSCR");
                        $table_subsc = new Table('cc_card_subscription','paid_status');
                        $subscr_clause = "id = ".$item -> getExtId();
                        $result_subscr = $table_subsc -> Get_list($DBHandle,$subscr_clause);
                        if (is_array($result_subscr)) {
                            $subscription = $result_subscr[0];
                            write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- cc_card_subscription paid_status : ".$subscription['paid_status']);
                            if ($subscription['paid_status']==3) {
                                $billdaybefor_anniversery = $A2B->config['global']['subscription_bill_days_before_anniversary'];
                                $unix_startdate = time();
                                $startdate = date("Y-m-d",$unix_startdate);
                                $day_startdate = date("j",$unix_startdate);
                                $month_startdate = date("m",$unix_startdate);
                                $year_startdate= date("Y",$unix_startdate);
                                $lastday_of_startdate_month = lastDayOfMonth($month_startdate,$year_startdate,"j");

                                $next_bill_date = strtotime("01-$month_startdate-$year_startdate + 1 month");
                                $lastday_of_next_month= lastDayOfMonth(date("m",$next_bill_date),date("Y",$next_bill_date),"j");

                                if ($day_startdate > $lastday_of_next_month) {
                                    $next_limite_pay_date = date ("$lastday_of_next_month-m-Y" ,$next_bill_date);
                                } else {
                                $next_limite_pay_date = date ("$day_startdate-m-Y" ,$next_bill_date);
                                }

                                $next_bill_date = date("Y-m-d",strtotime("$next_limite_pay_date - $billdaybefor_anniversery day")) ;
                                $QUERY = "UPDATE cc_card SET status=1 WHERE id=$id";
                                $result = $instance_table->SQLExec($DBHandle, $QUERY, 0);
                                write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY : $QUERY - RESULT : $result");

                                $QUERY = "UPDATE cc_card_subscription SET paid_status = 2, startdate = '$startdate' ,limit_pay_date = '$next_limite_pay_date', 	next_billing_date ='$next_bill_date' WHERE id=" . $item -> getExtId();
                                write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY : $QUERY");
                                $instance_table->SQLExec($DBHandle, $QUERY, 0);
                            } else {
                                $QUERY = "UPDATE cc_card SET status=1 WHERE id=$id";
                                $result = $instance_table->SQLExec($DBHandle, $QUERY, 0);
                                write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY : $QUERY - RESULT : $result");

                                $QUERY = "UPDATE cc_card_subscription SET paid_status = 2 WHERE id=". $item -> getExtId();
                                write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY : $QUERY");
                                $instance_table->SQLExec($DBHandle, $QUERY, 0);
                            }
                        }
                    }
                }
            }
        }
    }
}

$_SESSION["p_amount"] = null;
$_SESSION["p_cardexp"] = null;
$_SESSION["p_cardno"] = null;
$_SESSION["p_cardtype"] = null;
$_SESSION["p_module"] = null;
$_SESSION["p_module"] = null;

//Update the Transaction Status to 1 (Proceed 1)
$QUERY = "UPDATE cc_epayment_log SET status = 1, transaction_detail ='".addslashes($transaction_detail)."' WHERE id = ".$transactionID;
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY = $QUERY");
$paymentTable->SQLExec ($DBHandle_max, $QUERY);

switch ($orderStatus) {
    case -2:
        $statusmessage = "Failed";
        break;
    case -1:
        $statusmessage = "Denied";
        break;
    case 0:
        $statusmessage = "Pending";
        break;
    case 1:
        $statusmessage = "In-Progress";
        break;
    case 2:
        $statusmessage = "Successful";
        break;
}

if ( ($orderStatus != 2) && ($payment_method=='plugnpay')) {
    $url_forward = "checkout_payment.php?payment_error=plugnpay&error=The+payment+couldnt+be+proceed+correctly";
    if(!empty($item_id) && !empty($item_type)) $url_forward .= "&item_id=".$item_id."&item_type=".$item_type;
    Header ("Location: $url_forward");
    die();
}

if ( ($orderStatus == 0) && ($payment_method=='iridium')) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : EPAYMENT ORDER STATUS  = ".$statusmessage);
    die();
}

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : EPAYMENT ORDER STATUS  = ".$statusmessage);

// CHECK IF THE EMAIL ADDRESS IS CORRECT
if (preg_match("/^[a-z]+[a-z0-9_-]*(([.]{1})|([a-z0-9_-]*))[a-z0-9_-]+[@]{1}[a-z0-9_-]+[.](([a-z]{2,3})|([a-z]{3}[.]{1}[a-z]{2}))$/i", $customer_info["email"])) {
    // FIND THE TEMPLATE APPROPRIATE

    try {
        $mail = new Mail(Mail::$TYPE_PAYMENT,$id);
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-SENDING EMAIL TO CUSTOMER ".$customer_info["email"]);
        $mail->replaceInEmail(Mail::$ITEM_AMOUNT_KEY,$amount_paid);
        $mail->replaceInEmail(Mail::$ITEM_ID_KEY,$id_logrefill);
        $mail->replaceInEmail(Mail::$ITEM_NAME_KEY,'balance');
        $mail->replaceInEmail(Mail::$PAYMENT_METHOD_KEY,$pmodule);
        $mail->replaceInEmail(Mail::$PAYMENT_STATUS_KEY,$statusmessage);
        $mail->send($customer_info["email"]);

        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-SENDING EMAIL TO CUSTOMER ".$customer_info["email"]);
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str :- MAILTO:".$customer_info["email"]."-Sub=".$mail->getTitle()." , mtext=".$mail->getMessage());

        // Add Post information / useful to track down payment transaction without having to log
        $mail->AddToMessage("\n\n\n\n"."-POST Var \n".print_r($_POST, true));
        $mail->setTitle("COPY FOR ADMIN : ".$mail->getTitle());
        $mail->send(ADMIN_EMAIL);

    } catch (A2bMailException $e) {
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : ERROR NO EMAIL TEMPLATE FOUND");
    }

} else {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : Customer : no email info !!!");
}

// load the after_process function from the payment modules
$payment_modules->after_process();
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : EPAYMENT ORDER STATUS ID = ".$orderStatus." ".$statusmessage);
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-$trans_str : ----EPAYMENT TRANSACTION END----");

if ($payment_method=='plugnpay') {
    Header ("Location: userinfo.php");
    die;
}
