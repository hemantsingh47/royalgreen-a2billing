<?php
include('../confi.php');
$inst_table = new Table();  

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'])
{       
       $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);     
        $username = ltrim(str_replace('+','',$username),'0');  
        
         $array_result = array();
         // for sender account number to send the amount
         $sender_account_report = "SELECT * from cc_credit where transferFrom = '".$username."'";
         $customer_res = $inst_table -> SQLExec($DBHandle, $sender_account_report);  
         //print_r($customer_res);
         
         foreach($customer_res as $transfer_record)
         {
          $sender_no   =  $transfer_record['transferFrom'];
          $reciever_no =  $transfer_record['transferTo'];
          $amount      =  $transfer_record['Amount'];
          $date        =  $transfer_record['date'];
          $currency    =  'USD';   
           // for currency value  
         
          array_push($array_result,array("sender" => $sender_no,"reciever" => $reciever_no, "amount" =>$amount, "date" =>$date, "currency" => $currency)); 
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
