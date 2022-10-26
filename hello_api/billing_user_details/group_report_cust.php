<?php
include('../confi.php');
$inst_table = new Table();  

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'])
{       
        $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);      
        $username = ltrim(str_replace('+','',$username),'0');  
		
         $customerinfo = "SELECT id, ccode,credit from cc_card where username = '".$username."'";
         $customer_id_cust = $inst_table -> SQLExec($DBHandle, $customerinfo); 
    	 $customer_id = $customer_id_cust[0]['id'];
         $cust_curr = $customer_id_cust[0]['ccode'];
		 $cust_credit = $customer_id_cust[0]['credit'];
        
                                                               
         $cust_call_amount = "SELECT sum(sessionbill) as credit from cc_call where card_id = '".$customer_id."' AND terminatecauseid = '1'";
         $call_amount = $inst_table -> SQLExec($DBHandle, $cust_call_amount); 
         $total_call_amount = round($call_amount[0]['credit'],2);
         
         
        //for total voucher amount 
         $cust_voucher_amount = "SELECT sum(credit) as credit from cc_voucher where usedcardnumber = '".$username."'";
         $voucher_amount = $inst_table -> SQLExec($DBHandle, $cust_voucher_amount); 
         $total_voucher_recharge = $voucher_amount[0]['credit'];
         
          //for total paypal amount 
         $cust_payment_amount = "SELECT sum(orders_amount) as orders_amount from cc_payments where customers_id = '".$customer_id."'";
         $payment_amount = $inst_table -> SQLExec($DBHandle, $cust_payment_amount); 
         $total_payment_amount = $payment_amount[0]['orders_amount'];           
         
          //for total mobile topup amount 
         $cust_mobile_amount = "SELECT sum(amt) as topup_amount from cc_friend_recharge where username = '".$username."'";
         $mobile_amount = $inst_table -> SQLExec($DBHandle, $cust_mobile_amount); 
         $total_mobile_amount = $mobile_amount[0]['topup_amount']; 
         
         //for total balance transfer amount 
         $cust_transfer_amount = "SELECT sum(Amount) as balance_transfer from cc_credit where transferFrom = '".$username."'";
         $transfer_amount = $inst_table -> SQLExec($DBHandle, $cust_transfer_amount); 
         $total_trasfer_amount = $transfer_amount[0]['balance_transfer']; 
         
         //total customer amount
         
         $total_cust_amount =  ($total_voucher_recharge + $total_payment_amount +  $total_mobile_amount + $total_trasfer_amount);
         $total_cust_amount = round($total_cust_amount,3);
         
        
       
         
         
	     $customerinfocode = "SELECT currencycode from cc_country where countryprefix = '".$cust_curr."'";
	     $customer_id_cur = $inst_table -> SQLExec($DBHandle, $customerinfocode); 
	    $customer_curs = $customer_id_cur[0]['currencycode'];
		 
		 //echo $customer_curs;
		
         $array_result1 = array();
         // for sender account number to send the amount
         $sender_account_report = "select * from cc_report_bank WHERE user_id ='".$username."'";
         $customer_res = $inst_table -> SQLExec($DBHandle, $sender_account_report);  
         //print_r($customer_res);
         
         
          $from = "GBP";
        $to = trim($customer_curs);
        $ch = curl_init();                                           
        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://free.currencyconverterapi.com/api/v5/convert?q={$from}_{$to}&compact=ultra");                                                                                                                                          
        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                     
        // $output contains the output string 
        $output = curl_exec($ch);
        // close curl resource to free up system resources 
        curl_close($ch); 
        $data = explode(':', $output);
        $data = explode(" ", $data[1]);
        $amnt = round($data[0], 8);
        $cost = ($amnt)*($cost);
        $total_cust_amount = ($amnt)*($total_cust_amount); 
       
	
         foreach($customer_res as $transfer_record)
         {     
          $Destination      =  $transfer_record['Destination'];   
          $Amount    =    $transfer_record['Amount'];   
          $credit_amount    =    $transfer_record['credit_amount']; 
		  $total_amount     =  $transfer_record['total_amount']; 
          $status     =  $transfer_record['status'];  
          $Date     =  $transfer_record['Date'];     
		  
	    $from = "GBP";
       $to = trim($customer_curs);
        $ch = curl_init();                                           
        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://free.currencyconverterapi.com/api/v5/convert?q={$from}_{$to}&compact=ultra");                                                                                                                                          
        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                     
        // $output contains the output string 
        $output = curl_exec($ch);
        // close curl resource to free up system resources 
        curl_close($ch); 
        $data = explode(':', $output);
        $data = explode(" ", $data[1]);
        $amnt = round($data[0], 8);
		$cost = ($amnt)*($cost);
        $Amount = ($amnt)*($Amount);  
		$Amount = round($Amount, 2).' '.$to;        
		   
		  array_push($array_result1,array("status" => $status,"destination" =>$Destination, "debit_balance" => $Amount, "credit_balance"=>$credit_amount ,"total_amount"=>$total_amount,"Date" => $Date)); 
		  }
		  $sender_account_report = "select ca.calledstation as called_user, ca.destination as Dest, cp.destination as country, ca.buycost as Rates , ca.sessiontime as duration , ca.sessionbill as cost, ca.starttime as calldate from cc_card cc LEFT JOIN cc_call ca ON cc.id=ca.card_id LEFT JOIN cc_prefix cp ON cp.prefix=ca.destination WHERE cc.id ='".$customer_id."' AND ca.terminatecauseid = '1'";
          $customer_res = $inst_table -> SQLExec($DBHandle, $sender_account_report);  
         //print_r($customer_res);
         
         
		  
		$from = "GBP";
        $to = trim($customer_curs);
        //$to = 'INR';
        $ch = curl_init();                                           
        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://free.currencyconverterapi.com/api/v5/convert?q={$from}_{$to}&compact=ultra");                                                                                                                                          
        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                     
        // $output contains the output string 
        $output = curl_exec($ch);
        // close curl resource to free up system resources 
        curl_close($ch); 
        $data = explode(':', $output);
        $data = explode(" ", $data[1]);
        $amnt = round($data[0], 8);
        //echo $total_call_amount;  
		  $total_cust_amount = ($amnt)*($total_call_amount);
		
          $total_call_amount = round($total_cust_amount, 2).' '.$to;
		     
		//array_push($array_result1,array("Total deducted amount" => $total_call_amount));
        
        array_push($array_result1,array("status" => 'Global Call out ',"destination" =>'0', "debit_balance" => '0', "credit_balance"=>'0' ,"call_amount"=>$total_call_amount,"Date" => '')); 
		                                 
		  header('Content-Type: application/json');               
          
         $result =  array("result"=>"success","msg" => $array_result1);
          echo json_encode($result); 
 }
		  else
{
     header('Content-Type: application/json');
    echo json_encode(array("result"=>"failure","msg"=> "Invalid data!"));
    die;
}

?>