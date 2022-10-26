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

require 'vendor/autoload.php';
require_once 'constants/SampleCodeConstants.php';
//include("confi.php");

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

define("AUTHORIZENET_LOG_FILE", "phplog");

if (!has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}



$DBHandle = $DBHandle_max  = DbConnect();
$inst_table = new Table();

$QUERY = "SELECT email FROM cc_card WHERE username = '" . $_SESSION["pr_login"] ."' AND uipass = '" . $_SESSION["pr_password"] . "'";

//$DBHandle = DbConnect();

$customer_res = $inst_table -> SQLExec($DBHandle, $QUERY);
$customer_res = json_encode($customer_res);

$paymentTable = new Table();
$time_stamp = date("Y-m-d H:i:s"); 
$amount_string = $amount_pay;

// #### HEADER SECTION
$smarty->display( 'main.tpl');


    
if(isset($_POST['submit']))
{
	$name = $_POST['card-name']; 
	$amount_pay = $_POST['card_amount'];
	//function chargeCreditCard($amount_pay)
	//{
	$email = $_POST['card_email']; 
    $card_number = preg_replace('/\s+/', '', $_POST['card_number']); 
    $card_exp_month = $_POST['card_exp_month']; 
    $card_exp_year = $_POST['card_exp_year']; 
    $card_exp_year_month = $card_exp_year.'-'.$card_exp_month; 
    $card_cvc = $_POST['card_cvc'];
		
		/* Create a merchantAuthenticationType object with authentication details
		   retrieved from the constants file */
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
		$merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);
		
		// Set the transaction's refId
		$refId = 'ref' . time();

		// Create the payment data for a credit card
		$creditCard = new AnetAPI\CreditCardType();
		$creditCard->setCardNumber($card_number);
		$creditCard->setExpirationDate($card_exp_year_month);
		$creditCard->setCardCode($card_cvc);

		// Add the payment data to a paymentType object
		$paymentOne = new AnetAPI\PaymentType();
		$paymentOne->setCreditCard($creditCard);

		// Create order information
		$order = new AnetAPI\OrderType();
		$order->setInvoiceNumber("");
		$order->setDescription("");

		// Set the customer's Bill To address
		$customerAddress = new AnetAPI\CustomerAddressType();
		$customerAddress->setFirstName("");
		$customerAddress->setLastName("");
		$customerAddress->setCompany("");
		$customerAddress->setAddress("");
		$customerAddress->setCity("");
		$customerAddress->setState("");
		$customerAddress->setZip("");
		$customerAddress->setCountry("");

		// Set the customer's identifying information
		$customerData = new AnetAPI\CustomerDataType();
		$customerData->setType("individual");
		$customerData->setId("");
		$customerData->setEmail($email);

		// Add values for transaction settings
		$duplicateWindowSetting = new AnetAPI\SettingType();
		$duplicateWindowSetting->setSettingName("duplicateWindow");
		$duplicateWindowSetting->setSettingValue("60");

		// Add some merchant defined fields. These fields won't be stored with the transaction,
		// but will be echoed back in the response.
		$merchantDefinedField1 = new AnetAPI\UserFieldType();
		$merchantDefinedField1->setName("");
		$merchantDefinedField1->setValue("");

		$merchantDefinedField2 = new AnetAPI\UserFieldType();
		$merchantDefinedField2->setName("");
		$merchantDefinedField2->setValue("");

		// Create a TransactionRequestType object and add the previous objects to it
		$transactionRequestType = new AnetAPI\TransactionRequestType();
		$transactionRequestType->setTransactionType("authCaptureTransaction");
		$transactionRequestType->setAmount($amount_pay);
		$transactionRequestType->setOrder($order);
		$transactionRequestType->setPayment($paymentOne);
		$transactionRequestType->setBillTo($customerAddress);
		$transactionRequestType->setCustomer($customerData);
		$transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
		$transactionRequestType->addToUserFields($merchantDefinedField1);
		$transactionRequestType->addToUserFields($merchantDefinedField2);

		// Assemble the complete transaction request
		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId($refId);
		$request->setTransactionRequest($transactionRequestType);

		// Create the controller and get the response
		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
		

		if ($response != null) {
			// Check to see if the API request was successfully received and acted upon
			if ($response->getMessages()->getResultCode() == "Ok") {
				// Since the API request was successful, look for a transaction response
				// and parse it to display the results of authorizing the card
				$tresponse = $response->getTransactionResponse();
				$transaction_id = $tresponse->getTransId();
				$payment_status = $response->getMessages()->getResultCode();
				$payment_response = $tresponse->getResponseCode();
				$statusMsg = $tresponse->getMessages()[0]->getDescription(); 
				
				if ($tresponse != null && $tresponse->getMessages() != null) {
					echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
					echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
					echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
					echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
					echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";
										
					$sql = "INSERT INTO orders(name,email,item_name,item_number,item_price,item_price_currency,card_number,card_exp_month,card_exp_year,paid_amount,txn_id,payment_status,payment_response,created,modified,cardid) VALUES('".$name."','".$email."',' ',' ','".$amount_pay."','USD','".$card_number."','".$card_exp_month."','".$card_exp_year."','".$amount_pay."','".$transaction_id."','".$payment_status."','".$payment_response."',NOW(),NOW(),'".$_SESSION["card_id"]."')"; 
	
					$payment_log = $inst_table -> SQLExec($DBHandle, $sql);
					
					$info_table = new Table();
					$query_card = "SELECT amount, cardid, currency FROM cc_epayment_log order by id DESC LIMIT 1";
					$result_card_info = $info_table -> SQLExec($DBHandle, $query_card);
					
					
					$idaccount = $result_card_info[0][cardid];
					
					$up_table = new Table();
					$qury_update="UPDATE  cc_card set credit = credit + $amount_pay WHERE id = $idaccount"; 
	
					//print_r($qury_update);
					$otp_update_result = $up_table -> SQLExec($DBHandle, $qury_update); 
					?>
					<br><br>
					<a href="https://billing.aritel.mobi/customer/userinfo.php"><input type="button" value="Go back to my account" class="btn btn-success" align="center"></a>
					
					<?php
									
				} else {
					echo "Transaction Failed \n";
					if ($tresponse->getErrors() != null) {
						echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
						echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
					}
				}
				// Or, print errors if the API request wasn't successful
			} else {
				echo "Transaction Failed \n";
				$tresponse = $response->getTransactionResponse();
			
				if ($tresponse != null && $tresponse->getErrors() != null) {
					echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
					echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
				} else {
					echo " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
					echo " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
				}
			}
		} else {
			echo  "No response returned \n";
		}

		return $response;
	//}

	if (!defined('DONT_RUN_SAMPLES')) {
		chargeCreditCard("2.23");
	}
}
?>

    <?php if(!empty($message)) { ?>
    <div id="response-message" class="<?php echo $reponseType; ?>"><?php echo $message; ?></div>
    <?php  } ?>
	
	<style>
		#frmPayment 
		{
			max-width: 400px;
			padding: 25px;
			border: #D0D0D0 1px solid;
			border-radius: 4px;
		}
		
		.btnAction {
			background-color: #ff7000;
			padding: 10px 40px;
			color: #FFF;
			border: #ef6901 1px solid;
			border-radius: 4px;
			cursor: pointer;
		}

		#error-message {
			margin: 0px 0px 10px 0px;
			padding: 5px 25px;
			border-radius: 4px;
			line-height: 25px;
			font-size: 0.9em;
			color: #ca3e3e;
			border: #ca3e3e 1px solid;
			display: none;
			width: 300px;
		}

		#response-message {
			margin: 0px 0px 10px 0px;
			padding: 5px 25px;
			border-radius: 4px;
			line-height: 25px;
			font-size: 0.9em;
			width: 300px;
		}

	</style>
	<div id="page_content_inner">
  <h3 class="heading_b uk-margin-bottom">Authorize.net Payment</h3>
  <div class="md-card">
  <div class="md-card-content">
  <div class="uk-grid" data-uk-grid-margin="">
  <div class="uk-width-1-1 uk-row-first">

<center>
<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5><?php echo gettext("Make Payment")?></h5>
        </div>
		
		
    <div id="error-message"></div>
            <div class="field-row">
			<br><br>
			</div>    
            <form id="frmPayment" action="payment.php" method="post" onSubmit="return cardValidation();">
						
                <div class="field-row">
                    <b><label>Customer's Name</label></b> 
					<span id="card-name-info" class="info"></span> <input type="text" id="card-name" name="card_name" class="demoInputBox">

                </div>
				<div class="field-row">
                    <b><label>Card Number</label></b> 
					<span id="card-number-info" class="info"></span><input type="text" id="card-number" name="card_number" class="demoInputBox">
                </div>
				<div class="field-row">
                    <b><label>Amount</label> </b>
					<span id="amount-info" class="info"></span><input type="text" id="card-amount" name="card_amount" class="demoInputBox">
                </div>
				<div class="field-row">
                    <b><label>Email</label></b> 
					<span id="card-email-info" class="info"></span><input type="email" id="card-email" name="card_email" class="demoInputBox">
                </div>
                <div class="field-row">
                    <div class="contact-row column-right">
                       <b> <label>Expiry Month / Year</label> </b>
					   <span id="userEmail-info" class="info"></span>
                        <select name="card_exp_month" id="month" class="demoSelectBox" style="width:16%;">
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select> <select name="card_exp_year" id="year" class="demoSelectBox" style="width:27%;">
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                        </select>
                    </div>
                </div>
				<div class="field-row">
                    <b><label>CVC code</label></b> 
					<span id="card-cvc-info" class="info"></span><input type="password" id="card-cvc" name="card_cvc" class="demoInputBox"><br>
                </div>
				
                <div>
                    <br><input type="submit" name="submit" value="Submit" id="submit" class="btnAction">

                   <!-- <div id="loader">
                        <img alt="loader" src="LoaderIcon.gif">
                    </div>
                </div>
               <!-- <input type='hidden' name='amount' value='151.51'> -->
            </form>
    <div class="test-data">
      
        <table class="tutorial-table" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <th>CARD NUMBER</th>
                <th>BRAND</th>
            </tr>
            <tr>
                <td>4111111111111111</td>
                <td>Visa</td>
            </tr>
           
        </table>
    </div>
	
	
	
    <script src="vendor/jquery/jquery-3.2.1.min.js"
        type="text/javascript"></script>
    <script>
function cardValidation () {
    var valid = true;
    var cardNumber = $('#card-number').val();
    var month = $('#month').val();
    var year = $('#year').val();

    $("#error-message").html("").hide();

    if (cardNumber.trim() == "") {
    	   valid = false;
    }

    if (month.trim() == "") {
    	    valid = false;
    }
    if (year.trim() == "") {
        valid = false;
    }

    if(valid == false) {
        $("#error-message").html("All Fields are required").show();
    }

    return valid;
}
</script>
