<?php
//$DBHandle  = DbConnect();

include("confi.php");
include("checkout_confirmation.php");
//header( "refresh:1;url=https://billing.adoreinfotech.co.in/crm/customer/verify.php" );

$idaccount = $result_card_info[0][cardid];
$y = $pay_amount;

echo "amount to be paid".$pay_amount;
$x = (int)$y;
$v = $x;
$am = "0".$v;	
$tm = $am."00";

?>


tdrfygjukkl;,