<?php

include_once ('../confi.php');
$logfilemtopup = "/var/log/a2billing/a2billing_api_card.log";


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['username'] && $_POST['password'] && $_POST['phonenos']) 
{
	$username = Security::decrypt(sanitize_data($_POST['username']), KEY_SECURE);
    $password = Security::decrypt(sanitize_data($_POST['password']), KEY_SECURE);
    $PhoneNos = $_POST['phonenos'];
    
	write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : query  : ".$username."\n");
	write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : query  : ".$password."\n");
	write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : query  : ".$PhoneNos."\n");
	
	$arr = explode(",", $PhoneNos);
	
	//print_r($arr);
	
    $q = "SELECT username,uipass, ccode FROM cc_card WHERE username='$username' AND uipass = '$password'";
	
	//print_r($q);
	
    $inst_table = new Table();
    $res = $inst_table->SQLExec($DBHandle, $q);
	
	$ccode = $res["0"]["ccode"];
	//print_r($ccode);
	
	$strlen = strlen($ccode);
	
	//echo $strlen;
	
	$q = "SELECT username FROM cc_card WHERE username IN ($PhoneNos)";
    $qresult = $inst_table->SQLExec($DBHandle, $q);
	
	$result['result']='international';
	$i=0;
	
	foreach($qresult as $users)
	{
		$result['phonenos'][$i]=$users['username'];
		$i++;
	}
	//echo(json_encode($result));
	
	$ph_arr = array();
	
	$count = count($arr);
	
	//$ph_arr = array();
	
	for($j=0; $j<=$count; $j++)
	{
		$phone_user[$j] = $ccode."".$arr[$j];
		
		$ph_arr[$j] = $phone_user[$j];
	}
	
	//$ph_arr =json_encode($ph_arr);
	//print_r($ph_arr);
	
	$ph_arr = implode(",", $ph_arr);
	//echo implode(",", $ph_arr);
	
	$qr = "SELECT username FROM cc_card WHERE username IN ($ph_arr)";
	
	$qrresult = $inst_table->SQLExec($DBHandle, $qr);
	
	//print_r($qrresult);
	
	$re['res']='local';
	$i=0;
	
	foreach($qrresult as $users)
	{
		$int = substr($users['username'], $strlen);
		
		
		$re['ph'][$i] = $int;
		$re['phonenos'][$i]=$users['username'];
		$i++;
		
	}
	
	
	//echo(json_encode($re));
	
	$array = array("result" => "success", "local"=>array("ph"=>$re["ph"], "phonenos"=>$re["phonenos"]), "international"=>array("phonenos"=> $result["phonenos"]));
    
	echo(json_encode($array));
	
}
else
{
    echo 'Invalid Data';
}

?>
