<?php
include_once ('../confi.php');
include_once ("/var/www/html/crm/common/lib/phpagi/phpagi-asmanager.php"); 

//require('../twilio-php-master/Twilio.php');  

$inst_table = new Table();

if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['phone_user'] && $_POST['otp'] && $_POST['ccode']) 
{
     $username = Security::decrypt(sanitize_data($_POST['phone_user']), KEY_SECURE); ;
     $otp = Security::decrypt(sanitize_data($_POST['otp']), KEY_SECURE); ;
     $ccode = Security::decrypt(sanitize_data($_POST['ccode']), KEY_SECURE); ;
   
    $username = $ccode.$username;
    //$otp = sanitize_data($_POST['otp']);
    $query_otp_info = "SELECT * from cc_otp WHERE phone_otp = '$otp' AND phone = '$username'";
    //$username = $ccode . $username;
    $query_card = "SELECT username,firstname,lastname,uipass,email,phone,ccode FROM cc_card WHERE phone='" .
        $username . "'";
    $result_otp_info = $inst_table->SQLExec($DBHandle, $query_otp_info);
    

    //$uipassword = MDP_STRING(4).MDP_NUMERIC(4);
    // for did as a caller id
    $query_available_did =
        "SELECT id,did_value FROM cc_did_value where status = '0'";
    $did_info_available = $inst_table->SQLExec($DBHandle, $query_available_did);
    $total_available_dids = count($did_info_available);

    if ($total_available_dids <= 0) {
        //alert goest to admin as well as
        //customer see the
    } else {
        $message = '';
        $otp_send = false;
        $userinfo = array("");
        if ($result_otp_info) {
            $result_card_info = $inst_table->SQLExec($DBHandle, $query_card);
            if ($result_card_info) 
			{
                $otp_send = true;
                $userinfo = array('username' => $result_card_info[0]['phone'], 'password' => $result_card_info[0]['uipass'], 'did' => $result_card_info[0]['username'] );
                $message = "Your existing password is " . $result_card_info[0]['uipass'] . " ";
				
            } 
			else 
			{
				$uipassword = rand(10000, 99999);
				
                $tarff_id = '163';
                $initial_amount = '0.0000';
				$idDID = '96035200';
				
               // $addTab = new Table();
				//$insQuery = "INSERT INTO cc_card (creationdate,username,useralias, did, uipass,phone,tariff, credit,currency,ccode) VALUES ('NOW()','$idDID','$idDID','$idDID', '$uipassword', '$username','$tarff_id','$initial_amount','BDT','$ccode')";
				//$id_cc_card = $addTab->SQLExec($DBHandle, $insQuery);
				
				$instance_sub_table = new Table('cc_card', 'creationdate,username,useralias, did, uipass,phone,tariff, credit,currency,ccode');
                $id_cc_card = $instance_sub_table->Add_table($DBHandle, "NOW(),'$idDID','$idDID',$idDID, $uipassword, '$username','$tarff_id','$initial_amount','BDT','$ccode'", null, null,'id');
				
				$insta_table = new Table();	
				$DBHandle  = DbConnect();
				$QUERT = "SELECT cid FROM cc_callerid WHERE id_cc_card = '".$id_cc_card."' ";
				//print_r($QUERT);

				$customer_res = $insta_table -> SQLExec($DBHandle, $QUERT);
				$idDID = $customer_res["0"]["cid"];
				//print_r($idDID);
				
				
				$ins_table = new Table();
				$DBHandle  = DbConnect();
				$QUERY_id = "UPDATE cc_card set username = '".$idDID."', useralias = '".$idDID."', did = '".$idDID."'  WHERE id = '".$id_cc_card."' ";
				$cust_did = $ins_table -> SQLExec($DBHandle, $QUERY_id);
				//print_r($QUERY_id);
				
				
                $instance_sip_table = new Table('cc_sip_buddies',
                    'name, accountcode, regexten, amaflags, callerid, context, dtmfmode, host, type, username, allow, secret, id_cc_card, nat,  qualify');
                if ($id_cc_card > 0) {
                    $id_sip = $instance_sip_table->Add_table($DBHandle, "'$idDID','$idDID','$idDID','billing','$idDID','ittech','RFC2833','dynamic','friend','$idDID','g729,ulaw,alaw,gsm','$uipassword','$id_cc_card','yes','yes'", null, null,
                        'id');
                    //if($id_sip > 0)
                    //{
                   // $callerid = "$username";
                    $inst_caller_table = new Table('cc_callerid', 'cid, id_cc_card, activated');
                    $id_caller_two = $inst_caller_table->Add_table($DBHandle, "'$idDID',$id_cc_card,'t'", null, null, 'id'); 
					
					
                    //if($id_caller > 0)
                    $query_did_update = "UPDATE cc_did_value SET status = '1' WHERE id = '" . $did_info_available[0]['id'] .
                        "'";
                    $result_did_update = $inst_table->SQLExec($DBHandle, $query_did_update);
                   // if ($id_caller > 0) {
                        $otp_send = true;
                        $query_card = "SELECT phone,uipass, did FROM cc_card WHERE id = '" . $id_cc_card .
                            "'";
                        $result_card_info = $inst_table->SQLExec($DBHandle, $query_card);
                        $message = "Your password is : " . $result_card_info[0]['uipass'] . " ";
                        $userinfo = array('username' => $result_card_info[0]['phone'], 'password' => $result_card_info[0]['uipass'], 'did' => $result_card_info[0]['did']);
                    //}
                    //}
                }
            }
			
			try 
			{
               if($otp_send)
			   {      
                        //$result = array("result"=>"success","OTP"=>"Your OTP is sent to your Mobile number.",'userinfo'=>$userinfo);

                        $url = "http://123.136.24.106:8008/_matrix/client/r0/register?kind=user";
                        $data_reg = '{"username":"' . $userinfo['username'] . '", "password":"' . $userinfo['password'] .
                            '", "auth": {"type":"m.login.dummy"}}';
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_VERBOSE, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_reg);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($ch, CURLOPT_HEADER, 1);
                        $response = curl_exec($ch);
                        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);                       
						curl_close($ch);

						//print_r($response);
                        
						$header = substr($response, 0, $header_size);
                        $body = substr($response, $header_size);
                        $regresult = json_decode($body, true);
                        if ($httpcode == 200) {
                            $result = array(
                                "result" => "success",
                                "OTP" => "Your OTP is sent to your Mobile number.",
                                'userinfo' => $userinfo);

                            $data = array('displayname' => $userinfo['username']);
                            $data_json = json_encode($data);

                           $url = 'http://123.136.24.106:8008/_matrix/client/r0/profile/' . urldecode('@' . $userinfo['username'] .
                                ':123.136.24.106') . '/displayname?access_token=' . urldecode($regresult['access_token']);

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                                    'Content-Length: ' . strlen($data_json)));
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                            curl_setopt($ch, CURLOPT_HEADER, 1);
                            $response = curl_exec($ch);
							
							//print_r($response);
							
                            curl_close($ch);

							

                        } elseif ($httpcode == 400) {
                            if ($regresult['errcode'] == 'M_USER_IN_USE' or $regresult['errcode'] ==
                                'M_EXCLUSIVE') {
                                $result = array(
                                    "result" => "success",
                                    "OTP" => "Username Already Exists, Try again.",
                                    'userinfo' => $userinfo);
                            }
                            if ($regresult['errcode'] == 'M_INVALID_USERNAME') {
                                $result = array(
                                    "result" => "failure",
                                    "OTP1" => "Not a valid Username.",
                                    'userinfo' => $userinfo);
                            }
                        } else {
                            $result = array(
                                "result" => "failure",
                                "OTP1" => "Some error occured, Try again.",
                                'userinfo' => $userinfo);
                        }    
              }

            }
            catch (exception $e)
			{
                $msg = $e->getMessage();
                $result = array(
                    "result" => "failure",
                    "OTP" => "SMS Error",
                    'userinfo' => $userinfo);

            }
        } 
		else 
		{
            $result = array("result" => "failure", "OTP" =>
                    "You have Entered Wrong OTP Code.");

        }
        
        $astman = new AGI_AsteriskManager();
        $res = $astman->connect(MANAGER_HOST,MANAGER_USERNAME,MANAGER_SECRET);
        //print_r($astman);
        $cmd = "module reload";
        $response = $astman->send_request('Command',array( "Command" => $cmd));
        echo json_encode($result);                               
        die();
    }
} else {
    echo "invalid data";

}

?>
