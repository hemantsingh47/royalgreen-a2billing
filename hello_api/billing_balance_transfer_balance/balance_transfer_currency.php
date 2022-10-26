<?php
include('../confi.php');
$inst_table = new Table();  

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'])
{       
        $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);   
		$username = ltrim(str_replace('+','',$username),'0');   
        
         // for sender account number to send the amount
         $sender_account = "SELECT ccode from cc_card where username = '".$username."'";
         $sender_account_info = $inst_table -> SQLExec($DBHandle, $sender_account); 
                                                                                     
         $cust_currency = $inst_table->SQLExec($DBHandle , "SELECT currencycode from cc_country where countryprefix = '".$sender_account_info[0]['ccode']."'");   
         $result = array("result"=>"success","code" => $sender_account_info[0]['ccode'],"currency" => trim($cust_currency[0]['currencycode']));   
         echo json_encode($result);                                                                   
         die;
}
else
{
    echo json_encode(array("result"=>"failure","msg"=> "Invalid data!"));
    die;
}

?>
