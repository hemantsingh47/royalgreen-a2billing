<?php
include('../confi.php');
$inst_table = new Table();  

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'])
{       
        $SenderAccount = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);     
        $SenderAccount = ltrim(str_replace('+','',$SenderAccount),'0');  
        
         $array_result = array();
         // for sender account number to send the amount
         $sender_account_report = "SELECT * from cc_sms_report where SenderAccount = '".$SenderAccount."'";
         $customer_res = $inst_table -> SQLExec($DBHandle, $sender_account_report);  
         //print_r($customer_res);  
		 foreach($customer_res as $transfer_record)
         {
          $sender_no   =  $transfer_record['SenderAccount'];
          $reciever_no =  $transfer_record['PhoneNumber']; 
          $Sendername =  $transfer_record['Sender_from']; 
          $amount      =  $transfer_record['Charge'];
          $date        =  $transfer_record['Date'];  
		  $time	      =  $transfer_record['Time']; 
		 $status        =  $transfer_record['status'];  
				
           // for currency value  
          //$total_amount = round($converted_currency=currencyConverter(strtoupper(BASE_CURRENCY), $currency, $amount)*$amount,2); 
           
           
          array_push($array_result,array("sender" => $sender_no,"reciever" =>  $reciever_no,"sender_name"=>$Sendername, "amount" =>$amount, "date" =>$date, "time" =>$time, "status" =>$status)); 
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
