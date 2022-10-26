<?php
//echo "hello test";

function currencyConverter($from_Currency,$to_Currency,$amount)
{	
	$from_Currency = urlencode($from_Currency);
	$to_Currency = urlencode($to_Currency);	
	$get = file_get_contents("https://finance.google.com/finance/converter?a=1&from=$from_Currency&to=$to_Currency");
	$get = explode("<span class=bld>",$get);
	$get = explode("</span>",$get[1]);
	$rate= preg_replace("/[^0-9\.]/", null, $get[0]);
	$converted_amount = $amount*$rate;
	$data = array( 'rate' => $rate, 'converted_amount' =>$converted_amount, 'from_Currency' => strtoupper($from_Currency), 'to_Currency' => strtoupper($to_Currency));
	echo json_encode( $data );	
}
?> 