<?php
//$DBHandle  = DbConnect();

include("confi.php");
//include("checkout_confirmation.php");
//header( "refresh:1;url=https://billing.adoreinfotech.co.in/crm/customer/verify.php" );

$idaccount = $result_card_info[0][cardid];
$y = $result_card_info[0][amount];
$x = (int)$y * "355.000";

$v = $x;
$am = "0".$v;	
$tm = $am."00";

function genReference($qtd){
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
    $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
    $QuantidadeCaracteres = strlen($Caracteres);
    $QuantidadeCaracteres--;

    $Hash=NULL;

    for($x=1;$x<=$qtd;$x++){
        $Posicao = rand(0,$QuantidadeCaracteres);
        $Hash .= substr($Caracteres,$Posicao,1);
    }

    return $Hash;
}


$result = array();
	
$curl = curl_init();

$email = "willssculs2@gmail.com";
$amount = $tm;  
echo $am;

$result = array();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'amount'=>$amount,
    'email'=>$email,
	"reference" => genReference(10)
  ]),
  CURLOPT_HTTPHEADER => [
    "authorization: Bearer sk_live_aa485b34e992d7f8ad45542e9c5df573fc01a0ea", //replace this with your own test key
    "content-type: application/json",
    "cache-control: no-cache"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($ch);


if($err){
  // there was an error contacting the Paystack API
  die('Curl returned error: ' . $err);
}
//$result = "Your Payment approved.";
//$tranx = json_decode($response, true);

if ($response) {

    $result = json_decode($response, true);

    header('Location: ' . $result['data']['authorization_url']);

}


?>