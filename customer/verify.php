<?php
include("confi.php");
header( "refresh:2;url=https://billing.adoreinfotech.co.in/crm/customer/userinfo.php" );

$result = array();

$idaccount = $result_card_info[0][cardid];

$y = $result_card_info[0][amount];
$x = (int)$y;
//The parameter after verify/ is the transaction reference to be verified
$url = 'https://api.paystack.co/transaction/verify/'. $_GET['reference'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt(
$ch, CURLOPT_HTTPHEADER, [
'Authorization: Bearer sk_live_aa485b34e992d7f8ad45542e9c5df573fc01a0ea']
);
$request = curl_exec($ch);
curl_close($ch);

if ($request) {
$result = json_decode($request, true);
}

if (array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success')) {
echo "Transaction was successful";

	$DBHandle  = DbConnect();
	$inst_table = new Table();
	$qury_update="UPDATE  cc_card set credit = credit + $x WHERE id = $idaccount"; 
	
	//print_r($qury_update);
	$otp_update_result = $inst_table -> SQLExec($DBHandle, $qury_update);  
	
	print_r($otp_update_result);
	
	//echo "Updated balance".$x;
	
//Perform necessary action
}else{
echo "Transaction was unsuccessful";
}



?>