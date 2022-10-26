<?php
include('../confi.php');
$inst_table = new Table();
$cc_table = new Table('cc_card','*');


 function currencyConverter($from_Currency,$to_Currency,$amounts)
    {
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);
        $get = file_get_contents("https://finance.google.com/finance/converter?a=1&from=$from_Currency&to=$to_Currency");
        $get = explode("<span class=bld>",$get);
        $get = explode("</span>",$get[1]);
        $converted_currency = preg_replace("/[^0-9\.]/", null, $get[0]);
       // print_r($amounts);   
        return $converted_currency;
    }

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'] && $_POST['cust_pass'] && $_POST['credit'] && $_POST['transferaccount'])
{       
        $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);
        $password = Security::decrypt(sanitize_data($_POST['cust_pass']), KEY_SECURE);
        $credit = Security::decrypt(sanitize_data($_POST['credit']), KEY_SECURE);
        $transferaccount = Security::decrypt(sanitize_data($_POST['transferaccount']), KEY_SECURE);
        $username = ltrim(str_replace('+','',$username),'0');
        $transferaccount = ltrim(str_replace('+','',$transferaccount),'0');
        
        
         // for sender account number to send the amount
        $sender_account = "SELECT ccode,credit from cc_card where username = '".$username."'";
        $sender_account_info = $inst_table -> SQLExec($DBHandle, $sender_account); 
        $sender_currency = $inst_table->SQLExec($DBHandle , "SELECT currencycode from cc_country where countryprefix = '".$sender_account_info[0]['ccode']."'");  
        
		$receiver_account = "SELECT currency from cc_card where username = '".$transferaccount."'";
        $receiver_account_info = $inst_table -> SQLExec($DBHandle, $receiver_account); 
		$receiver_currency = $inst_table->SQLExec($DBHandle , "SELECT currencycode from cc_country where countryprefix = '".$receiver_account_info[0]['ccode']."'");
		//echo $receiver_currency = $sender_account_info[0]['currencycode'];
		
        $QUERY = "SELECT username, credit FROM cc_card WHERE username = '" .$username. "' AND uipass = '" . $password . "'";
        $result_message = "failure";
        $customer_res = $inst_table -> SQLExec($DBHandle, $QUERY);
        $serder_actual_amount = $customer_res[0]['credit'];
        $sender_amount = round($converted_currency=currencyConverter($receiver_currency, $sender_currency, $serder_actual_amount)*$serder_actual_amount,4);
        
        $from = trim($receiver_currency);
        $to = trim($sender_currency);
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
        $sender_amount = ($amnt)*($serder_actual_amount);
        
        if($customer_res)
        {      
              if(($customer_res[0]['username'])!=$transferaccount && $customer_res[0]['credit'] > 0 && ($sender_amount-$credit)>=0 && $credit>0)
            {
                //Finding transfer account
                $query_transfer = "SELECT username,credit FROM cc_card WHERE username = '" .$transferaccount. "' ";
                $customer_transfer = $inst_table -> SQLExec($DBHandle, $query_transfer);
               
                if($customer_transfer)
                {
                    //update customer
                    $query_card_update_result = $cc_table -> Update_table($DBHandle, "credit =credit-'".$credit."'", "username='".$username."' AND uipass = '" . $password . "'");
                    if( $query_card_update_result)
                    {             
                      // update transfer account
                         $query_transfer_card_update_result = $cc_table -> Update_table($DBHandle, "credit =credit+'".$credit."'", "username='".$transferaccount."' ");
                        if($query_transfer_card_update_result)
                        {
                            $result = array("result"=>"success","msg" => "The balance was transferred successfully.");
                            $result_message = "success";
                      
                            $cdate = date("Y-m-d");
                            $ctime = date("h:i:s", time() + 9060);
                            $query_transfer_credit= "INSERT INTO cc_credit(transferFrom,transferTo,Amount,date,time,result_value,currency_code)VALUES('".$username."','".$transferaccount."','".$credit."','".$cdate."','".$ctime."','".$result_message."','".trim($sender_currency[0]['currencycode'])."')";
                           $credit_transfer = $inst_table -> SQLExec($DBHandle, $query_transfer_credit);

                             }
                        else
                        {
                            
                            //update customer
                             
                            $query_card_update_result_rollback = $cc_table -> Update_table($DBHandle, "credit =credit+'".$credit."'", "username='".$username."' AND uipass = '" . $password . "'");  
                            if($query_card_update_result_rollback)
                            {
                                  $result = array("result"=>"failure","msg" => "The Transfer account credit not updated rollback!");
                            }
                            else
                            {
                                 $result = array("result"=>"failure","msg" => "The Transfer account credit not updated!");
                            }
                           
                        }
                         
                    }
                    else
                    {
                          $result = array("result"=>"failure","msg" => "The user credit not updated!");
                    }  
                    
                }
                else
                {
                     $result = array("result"=>"failure","msg" => "The number entered has not been found.");
                }
                   
            }
            else
            {
                $result = array("result"=>"failure","msg" => "Not enough balance available, please top up.");    
            } 
        }
        else
        {
            $result = array("result"=>"failure","msg" => "Customer not Found!");
        }
         /*
         $cdate = date("Y-m-d");
         $ctime = date("h:i:s", time() + 9060);
         $query_transfer_credit= "INSERT INTO cc_credit(transferFrom,transferTo,Amount,date,time,result_value)VALUES('".$username."','".$transferaccount."','".$credit."','".$cdate."','".$ctime."','".$result_message."')";
         $credit_transfer = $inst_table -> SQLExec($DBHandle, $query_transfer_credit);
       */
          
        echo json_encode($result);
        die;
}
else
{
    echo json_encode(array("result"=>"failure","msg"=> "Invalid data!"));
    die;
}

?>
