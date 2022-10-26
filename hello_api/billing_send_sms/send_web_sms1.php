<?php
include_once('../confi.php');
require('../twilio-php-master/Twilio.php');
$inst_table = new Table();
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['username'] && $_POST['password']  && $_POST['receiver'] && $_POST['r_message'] )
{
    $username               = Security::decrypt(sanitize_data($_POST['username']), KEY_SECURE);        
    //$username               = sanitize_data($_POST['username']);		
    $password               = Security::decrypt(sanitize_data($_POST['password']), KEY_SECURE);    
    //$password               = sanitize_data($_POST['password']);    
    $rec_phn_wid_ctry_code  = Security::decrypt(sanitize_data($_POST['receiver']), KEY_SECURE);    
    //$rec_phn_wid_ctry_code  = sanitize_data($_POST['reciever']);    
    $message_to_send        = Security::decrypt(sanitize_data($_POST['r_message']), KEY_SECURE);    
     //$message_to_send        = sanitize_data($_POST['r_message']);    
    
    /*$sql9 = "SELECT charge FROM cc_sms_rate WHERE countryprefix = '$countrycode'";
          $ctime = date("h:i:s", time() + 9060);  */
    $rec_phn_wid_ctry_code = ltrim(str_replace('+','',$rec_phn_wid_ctry_code),'0');
    $rec_phn_wid_ctry_code = ereg_replace("[^0-9]", "", $rec_phn_wid_ctry_code);    
	$message_to_send = urlencode($message_to_send);
   // $message_to_send = htmlentities($message_to_send);
    //$chargesms = '0.1';
    
    $query_card_new = "SELECT * FROM cc_sms_rate_new WHERE countrycode='".$ccode."'";
    $result_card_info_new = $inst_table -> SQLExec($DBHandle, $query_card_new);
	$charge = $result_card_info_new[0]['charge'];
	
	$chargesms = $charge;
	$destination = $ccode.$rec_phn_wid_ctry_code;
	
	$query_card = "SELECT username,credit,phone,currency FROM cc_card WHERE username='".$username."' AND uipass = '$password'";
    $result_card_info = $inst_table -> SQLExec($DBHandle, $query_card);
	
    if (!$result_card_info || !is_array($result_card_info)) 
    {
        echo json_encode(array("result" => "Error loading your account information!"));
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
            $client = new Services_Twilio(TWILIO_SID, TWILIO_TOKEN);
			$twilio_message = (array)$client->account->messages->create(array(
                                                                "From" => ACCOUNT_KEY, 
                                                                "To" => "+".$rec_phn_wid_ctry_code,
                                                                "Body" => $message_to_send
                                                                ));
			$send = json_encode($twilio_message);													
																
																
                if($send=='OK')
            {  
                $inst_table_update = new Table('cc_card','*');
                $return = $inst_table_update -> Update_table($DBHandle, "credit = '$usercredit'", "username = '$username' AND uipass = '$password'");
                if($return)
                {
                    $instance_sub_table = new Table('cc_sms_report', 'SenderAccount,PhoneNumber,Destination,Charge,Date,Time');
                    $id_cc_sms_report = $instance_sub_table->Add_table($DBHandle, "'$username','$rec_phn_wid_ctry_code','$ccode',$chargesms, '".date("Y-m-d")."','".date("h:i:s A", time() + 34232)."'", null, null, 'id');
                    if($id_cc_sms_report > 0)
                    {
                        $result = array("result"=>"success","msg"=>"Message has been sent");
                    }
                    else
                    {
                        $result = array("result"=>"success","msg"=>"Message has been sent. ");   
                    }
                    
                }
                else 
                {
                    $result = array("result"=>"success","msg"=>"Message has been sent but credit not deducted");
                    
                }
   
            }
            else
            {
                $result = array("result"=>"failure","msg"=>"Some error occured, Try again.");
                
            }
                    
        }
        catch (Exception $e) 
        {
            $msg = $e->getMessage();
            $result = array("result"=>"failure","msg" => "SMS Error");
            
            
        } 
    }  
   else
   {
      $result = array("result"=>"failure","msg" =>  htmlentities("Credit insuffisent")); 
   }    
        
    echo json_encode($result);
    die();
}
 else
 {
     echo json_encode(array("result"=>"data invalid"));
     die;
 } 

/*Array
(
    [messagecount] => 1
    [messages] => Array
        (
            [0] => stdClass Object
                (
                    [to] => 919899864699
                    [messageid] => 02000000FC0D6404
                    [status] => 0
                    [remainingbalance] => 9.72150000
                    [messageprice] => 0.00800000
                    [network] => 40411
                )

        )

    [cost] => 0.008
)*/
?>