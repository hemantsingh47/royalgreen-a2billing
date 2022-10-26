<?php
include_once('../confi.php');
include_once('../NexmoMessage.php');
$inst_table = new Table();
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['username'] && $_POST['password']  && $_POST['reciever'] && $_POST['r_message'] )
{
   $username               = Security::decrypt(sanitize_data($_POST['username']), KEY_SECURE);        
    //$username               = sanitize_data($_POST['username']);        
    $password               = Security::decrypt(sanitize_data($_POST['password']), KEY_SECURE);    
    //$password               = sanitize_data($_POST['password']);    
   $rec_phn_wid_ctry_code  = Security::decrypt(sanitize_data($_POST['reciever']), KEY_SECURE);    
    //$rec_phn_wid_ctry_code  = sanitize_data($_POST['reciever']);    
    $message_to_send        = Security::decrypt(sanitize_data($_POST['r_message']), KEY_SECURE);    
     //$message_to_send        = sanitize_data($_POST['r_message']);    
    
    /*$sql9 = "SELECT charge FROM cc_sms_rate WHERE countryprefix = '$countrycode'";
          $ctime = date("h:i:s", time() + 9060);  */
	  $rec_phn_wid_ctry_code = ltrim(str_replace('+','',$rec_phn_wid_ctry_code),'0');
	  $rec_phn_wid_ctry_code = str_replace(' ', '', $rec_phn_wid_ctry_code);      
     $rec_phn_wid_ctry_code = preg_replace("[^0-9]", "", $rec_phn_wid_ctry_code);      
   // $message_to_send = htmlentities($message_to_send);
   
    $chargesms = '0.1';
    
    $query_card = "SELECT username,credit,phone,currency FROM cc_card WHERE username='".$username."' AND uipass = '$password'";
	$result_card_info = $inst_table -> SQLExec($DBHandle, $query_card);
    $phone_number = $result_card_info[0]['phone'];
	//echo $phone_number;die();
	if (!$result_card_info || !is_array($result_card_info)) 
    {
        echo json_encode(array("result" =>"failure","msg"=>"Insufficient Balance"));
        exit ();
    }
	
    $currencies_list = get_currencies();
    
    $two_currency = false;
    if (!isset ($currencies_list[strtoupper($result_card_info[0]['currency'])][2]) || !is_numeric($currencies_list[strtoupper($result_card_info[0]['currency'])][2])) 
    {
        $mycur = 1;
    } 
    else 
    {
        $mycur = $currencies_list[strtoupper($result_card_info[0]['currency'])][2];
        $display_currency = strtoupper($result_card_info[0]['currency']);
        if (strtoupper($result_card_info[0]['currency']) != strtoupper(BASE_CURRENCY))
        $two_currency = true;
    }
    $usercredit = (($result_card_info[0]['credit'])-($chargesms));
    if($usercredit >= 0)
    {
        try
        {
           
			$nexmo_sms = new NexmoMessage(NEXMO_USERAME,NEXMO_PASSORD);   
            $response = $nexmo_sms->sendText( $rec_phn_wid_ctry_code, COMPANY_NAME, $message_to_send);
            $response = (array)$response;
			
			//print_r($response);die;
			
			//$response = $base_url . http_build_query($nexmo_sms);
            		   
            if($response['messagecount'] > 0)
            {
                
              
                $inst_table_update = new Table('cc_card','*');
                $return = $inst_table_update -> Update_table($DBHandle, "credit = '$usercredit'", "username = '$username' AND uipass = '$password'");
                if($return)
                {
                    $instance_sub_table = new Table('cc_sms_report', 'SenderAccount,PhoneNumber,Destination,Charge,Date,Time');
                    $id_cc_sms_report = $instance_sub_table->Add_table($DBHandle, "'$username','$rec_phn_wid_ctry_code','',$chargesms, '".date("Y-m-d")."','".date("h:i:s A", time() + 34232)."'", null, null, 'id');
                    if($id_cc_sms_report > 0)
                    {
						 
                         $result = array("result"=>"success","msg"=>"Message has been sent");
                        //$result = array("result"=>"success","msg"=>htmlentities("SMS envoye avec succes"));
                    }
                    else
                    {
                        $result = array("result"=>"success","msg"=>"Message has been sent but no log ");   
                        //$result = array("result"=>"success","msg"=>htmlentities("Het bericht is verzonden, maar geen log "));   
                    }
                    
                }
                else 
                {
                    $result = array("result"=>"success","msg"=>"Message has been sent but credit not deducted");
                    //$result = array("result"=>"success","msg"=>htmlentities("Het bericht is verzonden, maar credit niet afgetrokken"));
                    
                }
   
            }
            else
            {
                $result = array("result"=>"failure","msg"=>"Some error occured, Try again.");
                //$result = array("result"=>"failure","msg" => htmlentities("Lenumero n'existe pas"));
                
            }
                    
        }
        catch (Exception $e) 
        {
            $msg = $e->getMessage();
            $result = array("result"=>"failure","msg"=>"Error SMS");
            
            
        } 
   }  
   else
   {
      $result = array("result"=>"failure","msg"=>"Insufficient Balance");
   }   
    echo json_encode($result);
    die();
}
 else
 {
    $result = array("result"=>"failure","msg"=>"Insufficient Balance");
     die;
 } 


?>
