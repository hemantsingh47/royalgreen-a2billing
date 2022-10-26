<?php
include('../confi.php');
$inst_table = new Table();  

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'])
{       
        $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);     
        $username = ltrim(str_replace('+','',$username),'0');  
		
         $customerinfo = "SELECT id, ccode from cc_card where username = '".$username."'";
         $customer_id_cust = $inst_table -> SQLExec($DBHandle, $customerinfo); 
    	 $customer_id = $customer_id_cust[0]['id'];
		 $cust_curr = $customer_id_cust[0]['ccode'];   
		 
		     $customerinfocode = "SELECT currencycode from cc_country where countryprefix = '".$cust_curr."'";
			 $customer_id_cur = $inst_table -> SQLExec($DBHandle, $customerinfocode); 
			 $customer_curs = $customer_id_cur[0]['currencycode'];
		 
		 //echo $customer_curs;
		
         $array_result = array();
         // for sender account number to send the amount
         $sender_account_report = "select * from cc_report_demo WHERE user_id ='".$username."' ORDER BY Date DESC LIMIT 0, 20";
         $customer_res = $inst_table -> SQLExec($DBHandle, $sender_account_report);  
         //print_r($customer_res);
         
	
         foreach($customer_res as $transfer_record)
         {
          
          $Destination      =  $transfer_record['Destination'];
          $Duration        =  $transfer_record['Duration'];
          $Amount    =  $transfer_record['Amount'];   
		  $Date     =  $transfer_record['Date'];   
		  $status     =  $transfer_record['status'];   
		  
		  
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
		
		
		  array_push($array_result,array("Destination" =>$Destination, "Duration" =>$Duration, "Amount" => $Amount, "Date" => $Date, "status" => $status)); 
		  }
		  $sender_account_report = "select ca.calledstation as called_user, ca.destination as Dest, cp.destination as country, ca.buycost as Rates , ca.sessiontime as duration , ca.sessionbill as cost, ca.starttime as calldate from cc_card cc LEFT JOIN cc_call ca ON cc.id=ca.card_id LEFT JOIN cc_prefix cp ON cp.prefix=ca.destination WHERE cc.id ='".$customer_id."' AND ca.terminatecauseid = '1' ORDER BY ca.starttime DESC LIMIT 0, 20";
         $customer_res = $inst_table -> SQLExec($DBHandle, $sender_account_report);  
         //print_r($customer_res);
         
         foreach($customer_res as $transfer_record)
         {
          $called_user   =  $transfer_record['called_user'];
          //$destination =  $transfer_record['Dest'];
          //$country      =  $transfer_record['country'];
          $Rates        =  $transfer_record['Rates'];
          $duration    =  $transfer_record['duration'];   
		  $cost     =  $transfer_record['cost'];   
		  $Date     =  $transfer_record['calldate'];   
		  
		  
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
		$Rates = ($amnt)*($Rates);
		$Rates = round($Rates, 2).' '.$to;
		
		
		  array_push($array_result,array("Destination" => $called_user, "Duration" => $duration, "Amount" =>$cost, "Date" =>$Date,  "status" => 'Call')); 
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