<?php
include_once('../confi.php');
include '../NexmoMessage.php';
$inst_table = new Table();
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['phone'] && $_POST['ccode'] )
{
    $username = Security::decrypt(sanitize_data($_POST['phone']), KEY_SECURE);		
    //$email = Security::decrypt(sanitize_data($_POST['email']), KEY_SECURE);
    $country_code = Security::decrypt(sanitize_data($_POST['ccode']), KEY_SECURE);
    $username = ltrim(str_replace('+','',$username),'0');
     if(strlen($username) < 9)
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
	if($result_card_info)
	{
        $result = array("result"=>"failure","msg"=>"You are already registered, please go to forgot password", 'userinfo'=>'');
        /*$userinfo = array('username' => $result_card_info[0]['username'],'password' => $result_card_info[0]['uipass']);
        $message = "Your existing password is ".$result_card_info[0]['uipass']." ";
        *///send_to_email
	}
    else
    {
		$uipassword = rand(10000,99999);
        $tarff_id = '1';
        $initial_amount='0.0500';
		$instance_sub_table = new Table('cc_card', 'creationdate,username,useralias, uipass,phone,tariff, credit,email');
        $id_cc_card = $instance_sub_table->Add_table($DBHandle, "NOW(),'$username','$username',$uipassword, '$username','$tarff_id','$initial_amount','".trim($email)."'", null, null, 'id');
        $instance_sip_table = new Table('cc_sip_buddies', 'name, accountcode, regexten, amaflags, callerid, context, dtmfmode, host, type, username, allow, secret, id_cc_card, nat,  qualify');
        if($id_cc_card > 0)
        {
            $id_sip = $instance_sip_table->Add_table($DBHandle, "'$username','$username','$username','billing','$username','ittech','RFC2833','dynamic','friend','$username','g729,ulaw,alaw,gsm','$uipassword','$id_cc_card','yes','yes'", null, null, 'id');    
            if($id_sip > 0)
            {
                 $callerid = DIAL_PREFIX."$username";
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
            $result = array("result"=>"success","msg"=>"Registered successfully",'userinfo'=>$userinfo);
            $email_response = FALSE;
             try {
                   
                  $mail = new Mail(Mail :: $TYPE_SIGNUPCONFIRM, $userinfo['id'], $userinfo['language']);
                    $mail -> send();
                    $result = array("result"=>"success","msg"=>"Registered successfully and password has been sent to your Email ID",'userinfo'=>$userinfo);
                    $email_response = TRUE;
                } catch (A2bMailException $e) {
                     $result = array("result"=>"failure","msg"=>gettext("Error : Mail sender").$e,'userinfo'=>$userinfo);
                    
                }
               try
               {
                    $nexmo_sms = new NexmoMessage(NEXMO_USERAME,NEXMO_PASSORD);   
                    $response = $nexmo_sms->sendText( $ccode.$username, COMPANY_NAME, $message);
                    $response = (array)$response;
                    if($response['messagecount'] > 0)
                    {
                        $result = array("result"=>"success","msg"=>"Registered successfully and password has been sent to your Email ID and phone number",'userinfo'=>$userinfo);
                        
                    }
                    else
                    {
                        $result = array("result"=>"success","msg"=>"Registered successfully and password has been sent to your Email ID but not on phone number",'userinfo'=>$userinfo);
                        //$result = array("result"=>"failure","OTP"=>"Some error occured, Try again.", 'userinfo'=>$userinfo);
                    }
               }  
              catch (Exception e)
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