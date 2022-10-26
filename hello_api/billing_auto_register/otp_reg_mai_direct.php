<?php
include_once('../confi.php');
include '../NexmoMessage.php';
include 'useragent.php';
$inst_table = new Table();
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['phone'])
{
    $username = Security::decrypt(sanitize_data($_POST['phone']), KEY_SECURE);		
    //$username = ltrim(str_replace('+','',$username),'0');
    
	if(strlen($username) < 10)
     {  
            //check the length of mobile
           $result = array("result"=>"failure","msg"=>"Invalid Phone number", 'userinfo'=>'');
            echo json_encode($result);  
            exit();
     } 
    $query_card = "SELECT username,firstname,uipass,email,phone FROM cc_card WHERE username='".$username."'";
	$message = '';
    $userinfo = array("");
    $result_card_info = $inst_table -> SQLExec($DBHandle, $query_card);
	//echo $username; die();
	if($result_card_info)
	{
        $result = array("result"=>"failure","msg"=>"You are already registered, please go to forgot password", 'userinfo'=>'');
        /*$userinfo = array('username' => $result_card_info[0]['username'],'password' => $result_card_info[0]['uipass']);
        $message = "Your existing password is ".$result_card_info[0]['uipass']." ";
        *///send_to_email
	}
    else
    {
		$uipassword = MDP_NUMERIC(4).MDP_STRING(4);
        $tarff_id = '1';
        //$group = '1';
        $initial_amount='0.000';
		 $useragent = new UserAgent();
         $phone_type = $useragent->get_user_agent();
		$instance_sub_table = new Table('cc_card', 'creationdate,username,useralias, uipass,phone,tariff,credit,phone_type,reg_status,email,currency');
        $id_cc_card = $instance_sub_table->Add_table($DBHandle, "NOW(),'$username','$username','$uipassword', '$username','$tarff_id','$initial_amount','".$phone_type."','Registered','".trim($email)."','GBP'", null, null, 'id');
        $instance_sip_table = new Table('cc_sip_buddies', 'name, accountcode, regexten, amaflags, callerid, context, dtmfmode, host, type, username, allow, secret, id_cc_card, nat,  qualify');
        if($id_cc_card > 0)
        {
            $id_sip = $instance_sip_table->Add_table($DBHandle, "'$username','$username','$username','billing','$username','ittech','RFC2833','dynamic','friend','$username','g729,ulaw,alaw,gsm','$uipassword','$id_cc_card','yes','yes'", null, null, 'id');    
            if($id_sip > 0)
            {
				 $username1=ltrim($username,"0");
                 $callerid = DIAL_PREFIX."$username1";
                 $instance_caller_table = new Table('cc_callerid', 'cid, id_cc_card, activated');
                 $id_caller = $instance_caller_table->Add_table($DBHandle, "'$callerid','$id_cc_card','t'", null, null, 'id'); 
                 if($id_caller > 0)
                 {
                     
                     $query_card = "SELECT * FROM cc_card WHERE id = '".$id_cc_card."'";
                     $result_card_info = $inst_table -> SQLExec($DBHandle, $query_card);
                     $message = "Your password is : ".$result_card_info[0]['uipass']." ";
                     $userinfo = $result_card_info[0];
                 }
            }
            $result = array("result"=>"success","msg"=>"Registered successfully",'userinfo'=>$userinfo);
            
			$nexmo_sms = new NexmoMessage(NEXMO_USERAME,NEXMO_PASSORD);   
            $response = $nexmo_sms->sendText( DIAL_PREFIX.$username, COMPANY_NAME, $message);
            $response = (array)$response;
            if($response['messagecount'] > 0)
            {
                $result = array("result"=>"success","msg"=>"Your Account Number & Password is sent to your phone number.");
            }
            else
            {
                $result = array("result"=>"failure","msg"=>"Some error occured, Try again.");
            }
               
        }
        else
        {
            $result = array("result"=>"failure","msg"=>"Some error occured, Try again.", 'userinfo'=>'');
        }
    }
            
   
    echo json_encode($result);
    die();
}

?>