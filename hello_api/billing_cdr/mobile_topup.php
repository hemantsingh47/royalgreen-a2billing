<?php
include('../confi.php');
$inst_table = new Table();  

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'])
{       
        $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);     
        $username = ltrim(str_replace('+','',$username),'0');  
		
		 $customerinfo = "SELECT id, ccode from cc_card where username = '".$username."'";
         $customer_id_cust = $inst_table -> SQLExec($DBHandle, $customerinfo); 
		 $cust_curr = $customer_id_cust[0]['ccode'];
		 
		$customerinfocode = "SELECT currencycode from cc_country where countryprefix = '".$cust_curr."'";
		$customer_id_cur = $inst_table -> SQLExec($DBHandle, $customerinfocode); 
		$customer_curs = $customer_id_cur[0]['currencycode'];
			 
         $array_result = array();
         // for sender account number to send the amount
         $sender_account_report = "select msdest as destination , amt as Amount , trans_time as Time from cc_friend_recharge WHERE username ='".$username."' ORDER BY  trans_time DESC LIMIT 0, 10";
         $customer_res = $inst_table -> SQLExec($DBHandle, $sender_account_report);  
         //print_r($customer_res);
         
         foreach($customer_res as $transfer_record)
         {
          $destination   =  $transfer_record['destination'];
          $Amount =  $transfer_record['Amount'];
          $Time      =  $transfer_record['Time'];
          
		  $Amount = substr($Amount, 0, -4);
		  
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
		$Amount = ($amnt)*($Amount);
		$Amount = round($Amount, 2).' '.$to;
		
		//echo $Amount;
		
		  array_push($array_result,array("destination" => $destination,"Amount" => $Amount, "Time" =>$Time)); 
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