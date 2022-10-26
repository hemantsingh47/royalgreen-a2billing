<?php
include_once('../confi.php');
include '../BulkSender.php';
$inst_table = new Table();
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['phone'] && $_POST['ccode'] )
{
    $username = Security::decrypt(sanitize_data($_POST['phone']), KEY_SECURE);		
    //$email = Security::decrypt(sanitize_data($_POST['email']), KEY_SECURE);
    $country_code = Security::decrypt(sanitize_data($_POST['ccode']), KEY_SECURE);
	$phone_no = ltrim(str_replace('+','',$username),'0');
	$country_code = ltrim(str_replace('+','',$country_code),'0');
	
	 ///$phone_no = $username;
	 
	    //$DBHandle_max  = DbConnect();
		$QUERY1 = "SELECT cc_agent.id as agent_id  FROM cc_card LEFT JOIN cc_card_group ON cc_card.id_group = cc_card_group.id LEFT JOIN cc_agent ON cc_agent.id = cc_card_group.id_agent WHERE cc_card.id_group='6'";
        $agentinfo = $inst_table -> SQLExec($DBHandle, $QUERY1);
		$agent_id = $agentinfo[0]['agent_id'];
		//echo $agent_id; die();
		
    if(strlen($username) < 9)
     {  
            //check the length of mobile
           $result = array("result"=>"failure","msg"=>"Invalid Phone number", 'userinfo'=>'');
            echo json_encode($result);  
            exit();
     } 
	$group = '6';
    $query_card = "SELECT username,firstname,uipass,email,phone FROM cc_card WHERE phone ='".$username."' AND account_type ='".$agent_id."'";
	
	$message = '';
    $userinfo = array("");
    $result_card_info = $inst_table -> SQLExec($DBHandle, $query_card);
	if($result_card_info)
		
	{
        
	   $userinfo = array('username' => $result_card_info[0]['username'],'uipass' => $result_card_info[0]['uipass'], 'phone' => $result_card_info[0]['phone'], 'agent_id' => $agent_id);
	   //$userinfo = array('username' => $result_card_info[0]['username'],'uipass' => $result_card_info[0]['uipass']);
       $message = "Your existing password is ".$result_card_info[0]['uipass']."Username is". $result_card_info[0]['username']." ";
	   $result = array("result"=>"success","msg"=>"Registered successfully and password has been sent to your phone number", 'userinfo'=>$userinfo);
        ///send_to_email
		
		
		//$result = array("result"=>"failure","msg"=>"You are already registered, please go to forgot password", 'userinfo'=>$userinfo);
		$result = array("result"=>"failure","msg"=>"You are already registered, please go to forgot password", 'userinfo'=>$userinfo);
        //$userinfo = array('username' => $result_card_info[0]['username'],'password' => $result_card_info[0]['uipass']);
        $message = "Your existing password is ".$result_card_info[0]['uipass']." ";
        
		//send_to_email
	}
	
    else
    {
		$uipassword = rand(10000,99999);
		$user = rand(10000,99999);
        $tarff_id = '18';
        $initial_amount='1.0000';
		$instance_sub_table = new Table('cc_card', 'creationdate,username,useralias, uipass,phone,tariff, credit,email,id_group,firstname,currency,ccode,account_type');
        $id_cc_card = $instance_sub_table->Add_table($DBHandle, "NOW(),'$user','$user',$uipassword, '$username','$tarff_id','$initial_amount','".trim($email)."','".$group."','$firstname','EUR','$country_code','$agent_id'", null, null, 'id');
        $instance_sip_table = new Table('cc_sip_buddies', 'name, accountcode, regexten, amaflags, callerid, context, dtmfmode, host, type, username, allow, secret, id_cc_card, nat,  qualify');
        if($id_cc_card > 0)
        {
            $id_sip = $instance_sip_table->Add_table($DBHandle, "'$user','$user','$user','billing','$username','ittech','RFC2833','dynamic','friend','$user','g729,ulaw,alaw,gsm','$uipassword','$id_cc_card','yes','yes'", null, null, "id");    
            
			//if($id_sip > 0)
            //{
                 $callerid = '+'."$username";
                 $instance_caller_table = new Table('cc_callerid', 'cid, id_cc_card, activated');
                 $id_caller = $instance_caller_table->Add_table($DBHandle, "'$callerid','$id_cc_card','t'", null, null, 'id'); 
                // if($id_caller > 0)
                 //{
                     
                     $query_card = "SELECT * FROM cc_card WHERE id = '".$id_cc_card."'";
                     $result_card_info = $inst_table -> SQLExec($DBHandle, $query_card);
                     $message = "Your username is : ".$result_card_info[0]['phone']." And Your password is : ".$result_card_info[0]['uipass']." ";
                     //$userinfo = $result_card_info[0];
					 $userinfo = array('username' => $result_card_info[0]['username'],'uipass' => $result_card_info[0]['uipass'],'agent_id' => $agent_id);
                // }
           // }
            $result = array("result"=>"success","msg"=>"Registered successfully and password has been sent to your phone number",'userinfo'=>$userinfo);         
            $type = "2";
            $dlr = "1"; 
			//$type = "0";
           // $dlr = "1";
            $obj = new BulkSender("","8080",BULK_USERNAME,BULK_PASSORD,COMPANY_NAME,$message,$country_code.$phone_no,$type,$dlr);          

			 
			if(strpos($obj->Submit(),"1701")!== false )
            {
                 $result = array("result"=>"success","msg"=>"Registered successfully and password has been sent to your phone number",'userinfo'=>$userinfo);
            }
            else
            {
                $result = array("result"=>"failure","msg"=>"Some error occured, Try again.", 'userinfo'=>'');
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