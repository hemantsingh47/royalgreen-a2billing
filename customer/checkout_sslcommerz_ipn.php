<?php
include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './lib/epayment/classes/payment.php';
include './lib/epayment/classes/order.php';
include './lib/epayment/classes/currencies.php';
include './lib/epayment/includes/general.php';
include './lib/epayment/includes/html_output.php';
include './lib/epayment/includes/configure.php';
include './lib/epayment/includes/loadconfiguration.php';


getpost_ifset(array('tran_id', 'status', 'value_a', 'value_b', 'value_c', 'value_d', 'currency', 'failedreason', 'sessionkey', 'gw', 'GatewayPageURL', 'desc', 'amount'));

if (empty($tran_id) || empty($status)) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." SSLCOMMERZ IPN FAILED: \n -POST Var \n".print_r($_POST, true));
    exit;
}

$transactionID = $tran_id;
$key = $value_a;
$sess_id = $value_b;
$trans_str = "transactionID=$transactionID";

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." SSLCOMMERZ IPN START : $trans_str - transactionKey=$key \n -POST Var \n".print_r($_POST, true));
die;
$DBHandle_max  = DbConnect();
$paymentTable = new Table();

// Status - New 0 ; Proceed 1 ; In Process 2
$QUERY = "SELECT * " .
         " FROM cc_epayment_log " .
         " WHERE id = ".$transactionID." AND (status = 0 OR status = 1 OR status = 2)";
$transaction_data = $paymentTable->SQLExec ($DBHandle_max, $QUERY);
if (empty($transaction_data) || (!is_array($transaction_data) && count($transaction_data) == 0)) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).
        ' line:'.__LINE__."- $trans_str : ERROR INVALID TRANSACTION ID IN IPN RESPONSE, TRANSACTION ID =".$transactionID);
    exit();
}
$transaction_data = reset($transaction_data);
$card_id = $transaction_data['cardid'];
$item_id = $transaction_data['item_id'];
$item_type = $transaction_data['item_type'];
if (empty($item_type)) {
    $item_type = '';
}
// $amount = $transaction_data['amount'];
// $currency = $transaction_data['currency'];
$payment_method = $transaction_data['paymentmethod'];
//DECLAIRING PAYMENT MODULE FOR FURTHER PROCESS
$payment_module = new payment($payment_method);
$success = false;
$orderStatus = $payment_module->getSSLCommerzOrderStatus($status);


switch ($status) {
    case 'VALID':

        if ($row['status'] == 'Pending') {

            $amount   = $_POST['amount'];
            $currency = $_POST['currency'];

            if (empty($_POST['amount']) || empty($_POST['currency'])) {

                echo "Invalid Information.";
                exit;

            }

            $validation = $sslc->orderValidate($tran_id, $amount, $currency, $_POST);

            if ($validation == true) {

                $sql   = $ot->updateTransactionQuery($tran_id, 'Processing');

                if ($conn_integration->query($sql) === true) {
                    echo "Payment Record Updated Successfully";
                } else {
                    echo "Error updating record: " . $conn_integration->error;
                }

            } else {

                $sql = $ot->updateTransactionQuery($tran_id, 'Failed');
                $conn_integration->query($sql);
                echo "Payment was not valid";

            }

        } else if ($row['status'] == 'Processing') {

            echo "This order is already Successful";

        }

        break;

    case 'FAILED':

        $sql = $ot->updateTransactionQuery($tran_id, 'Failed');
        $conn_integration->query($sql);

        echo "Payment was failed";

        break;

    case 'CANCELLED':

        $sql = $ot->updateTransactionQuery($tran_id, 'Cancelled');
        $conn_integration->query($sql);

        echo "Payment was Cancelled";

        break;

    default:

        echo "Invalid Information.";

        break;
}

