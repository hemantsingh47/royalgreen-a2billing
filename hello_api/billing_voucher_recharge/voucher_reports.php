<?php
include('../confi.php');
$inst_table = new Table();  

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'])
{       
       $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);     
        $username = ltrim(str_replace('+','',$username),'0');  
        
         $array_result = array();
         // for sender account number to send the amount
         $sender_account_report = "SELECT * from cc_voucher where usedcardnumber = '".$username."'";
         $customer_res = $inst_table -> SQLExec($DBHandle, $sender_account_report);  
         //print_r($customer_res);
         
         foreach($customer_res as $voucher_report)
         {
          $reciever_no =  $voucher_report['usedcardnumber'];
		  $sender_no   =  $voucher_report['voucher'];
          $amount      =  $voucher_report['credit'];
          $date        =  $voucher_report['usedate'];
          $currency        =  $voucher_report['currency']; 
           // for currency value  
         
          array_push($array_result,array("Username" => $reciever_no,"Voucher" => $sender_no, "Amount" =>$amount, "Used Date" =>$date, "Currency" => $currency)); 
         }
         header('Content-Type: application/json');
         $result =  array("result"=>"success","msg" => $array_result);
          echo json_encode($result);               
}
else
{
     header('Content-Type: application/json');
    echo json_encode(array("result"=>"failure","msg"=> "Invalid data!"));
    die;
}

?>
