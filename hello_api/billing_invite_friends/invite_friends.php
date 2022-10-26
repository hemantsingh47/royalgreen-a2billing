<?php
include_once('../confi.php');
include '../BulkSender.php';
$inst_table = new Table();
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['username'] && $_POST['password']  && $_POST['receiver'] && $_POST['r_message'] )
{
    $username               = Security::decrypt(sanitize_data($_POST['username']), KEY_SECURE);        
    $password               = Security::decrypt(sanitize_data($_POST['password']), KEY_SECURE);    
    $rec_phn_wid_ctry_code  = Security::decrypt(sanitize_data($_POST['receiver']), KEY_SECURE);    
    $message_to_send        = Security::decrypt(sanitize_data($_POST['r_message']), KEY_SECURE);  
    
    /*$sql9 = "SELECT charge FROM cc_sms_rate WHERE countryprefix = '$countrycode'";
          $ctime = date("h:i:s", time() + 9060);  */
    $rec_phn_wid_ctry_code = ltrim(str_replace('+','',$rec_phn_wid_ctry_code),'0');
    $rec_phn_wid_ctry_code = preg_replace("[^0-9]", "", $rec_phn_wid_ctry_code);   
    $chargesms = '0.1';   
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
            $login = 'mohamed.salim';
            $pass = '@Valuetel2019@#';
            $originator = 'Valuetel';		
			$phone_number = $username;
            //$url = "https://portal.uwaziimobile.com/bulksms/bulksend.go?username=$login&password=$pass&to=$phone_number&text=$message";
            $url = "http://107.20.199.106/sms/1/text/single?username=mohamed.salim&password=@Valuetel2019@#&to=$phone_number&text=$message";
            $response = file_get_contents($url);
            
            if($response['messagecount'] >= 0)
            {
                $result = array("result"=>"success","msg"=>"Invite has been sent to your friend");
                
   
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
