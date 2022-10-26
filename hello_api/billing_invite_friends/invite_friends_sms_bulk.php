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
    $rec_phn_wid_ctry_code = preg_replace("[^0-9]", "", $rec_phn_wid_ctry_code);      
   // $message_to_send = htmlentities($message_to_send);
   
    $query_card = "SELECT username,credit,phone,currency FROM cc_card WHERE username='".$username."' AND uipass = '$password'";
    $result_card_info = $inst_table -> SQLExec($DBHandle, $query_card);
    if (!$result_card_info || !is_array($result_card_info)) 
    {
        echo json_encode(array("result" => "Error loading your account information!"));
        exit ();
    }
   
   
    try
        {
          /* $type = "2";
            $dlr = "1";
            $obj = new BulkSender("login.gmsbulksms.com","8080",BULK_USERNAME,BULK_PASSORD,COMPANY_NAME,$message_to_send,$rec_phn_wid_ctry_code,$type,$dlr);
           // response
			if(strpos($obj->Submit(),"1701")!== false )
            { */
		
			 $nexmo_sms = new NexmoMessage(NEXMO_USERAME,NEXMO_PASSORD);   
            $response = $nexmo_sms->sendText( $rec_phn_wid_ctry_code, COMPANY_NAME, $message_to_send);
            $response = (array)$response;
           //print_r($response);die;
            
            if($response['messagecount'] > 0)
			{	
		
		
                $result = array("result"=>"success","msg"=>"Message has been sent");
                
   
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
        
    echo json_encode($result);
    die();
}


?>
