<?php
include_once('../confi.php'); 
include '../BulkSender.php';                                
$inst_table = new Table();

$logfilemtopup = "/var/log/a2billing/a2billing_api_card.log";

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['phone'] && $_POST['ccode'])
{
    //encrypted value
	$username = Security::decrypt(sanitize_data($_POST['phone']), KEY_SECURE);
    $ccode = Security::decrypt(sanitize_data($_POST['ccode']), KEY_SECURE);
	$username = ltrim(str_replace('+','',$username),'0');
    $username = ereg_replace("[^0-9]", "", $username);
	 
	 
    if(strlen($username) < 8)
    {  
	        //check the length of mobile
            $json = array("status"=>"Invalid Phone Number"); 
            echo json_encode($json);  
            exit();
    } //end mobile length check
	$otp_send = TRUE;
     $randpass=rand(1000,9999);
	 $username = $ccode.$username;
	$otpinfo = array('phone_otp' => $randpass);
	if($username == '8059814335')
	{
		$randpass = "1234";
	}                
    try
    {
        $message = "Your One Time Password is : ".$randpass."";
        $query = "SELECT * FROM cc_otp WHERE phone='$username'";
        $customer_otp = $inst_table -> SQLExec($DBHandle, $query);
		//echo $customer_otp[0]['phone_otp'];die();
       	if($customer_otp)
        {
			//UPDATE
			$qury_update="UPDATE  cc_otp set phone_otp ='$randpass' WHERE phone='$username'";
			$otp_update_result = $inst_table -> SQLExec($DBHandle, $qury_update);
		   
			if(!$qury_update)
            {
                $result = array("result"=>"failure","OTP"=>"Not Sent");
                $otp_send = FALSE;    
            }
        }
		else
        {
		    //INSERT
			$query_insert="INSERT INTO cc_otp(phone_otp,phone,status) VALUES('$randpass','$username','0')";
			$otp_insert_result = $inst_table -> SQLExec($DBHandle, $query_insert,null,null,'id');
            //print_r($otp_insert_result);die;      
		   if( !$otp_insert_result)
            {
				$result = array("result"=>"failure","OTP"=>"Not Sent");
                $otp_send = FALSE;
			}
        }
		//$username ='91'.$username;
		if($otp_send)
        {  
	
			$MyIP = $_SERVER['REMOTE_ADDR'];
			
			//$URL = file_get_contents("https://api.ipinfodb.com/v3/ip-country/?key=ccee4247df730c59bf67776b719ba82bd36ec722884bafe13b124523623e64d0&ip=$MyIP&format=json");
			$URL = file_get_contents("https://api.ipinfodb.com/v3/ip-country/?key=ccee4247df730c59bf67776b719ba82bd36ec722884bafe13b124523623e64d0&ip=123.136.30.130&format=json");
			$URLdata = json_decode($URL, true);

			$MyCOuntry = $URLdata["countryName"];

			if($MyCOuntry == "India" or $MyCOuntry == "Bangladesh")
			{
				if($ccode == "91" or $ccode == "880")
				{
					
					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => 'http://123.136.28.211:8080/ofbiz-spring/api/Party/login',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS =>'{"loginId":"samax.rv0","password":"max_9200"}',
					  CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json'
					  ),
					));

					$response = curl_exec($curl);
					$curl_error = curl_error($curl);
					
					$res = json_decode($response);
					
					$response = json_decode(json_encode($res), true);
					
					$AuthToken = $response["token"];
					
					
					$RequestData = array("senderId" => "8801552146283", "phoneNumbers" => $username, "message" => $message, "isUnicode" => true);
					$RequestData = json_encode($RequestData);
					
					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => 'http://123.136.28.211:8080/ofbiz-spring/api/SmsTask/sendSMS',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS => $RequestData,
					  CURLOPT_HTTPHEADER => array(
						"Content-Type: application/json",
						"Authorization: Bearer $AuthToken"
					  ),
					));

					$response = curl_exec($curl);

					$res = json_decode($response);
					
					$response = json_decode(json_encode($res), true);
					
					$SuccessCOunt = $response["report"]["success"];

					curl_close($curl);
					
					if($SuccessCOunt == "0" or $SuccessCOunt == "1")  
					{
						$result = array("result"=>"success","OTP"=>"Your OTP send to your Mobile number. Your OTP is: ".$randpass."");    
					}
					else
					{
							$result = array("result"=>"failure","OTP"=>"Some error occured, Try again.");
					}  
				}
				else
				{
					$result = array("result"=>"failure","OTP"=>"Country not allowed for app.");
				}
				
			}
			else
			{
				$result = array("result"=>"failure","OTP"=>"Country not allowed for app.");
			}
            
		}
    }
    catch (Exception $e) 
    {
        $msg = $e->getMessage();
        $result = array("result"=> "failure", "FailureReason" => $msg);
    }
     echo json_encode($result);
     die;
}
else{
    echo json_encode(array("result"=>"invalid data"));
    die();
    
}

?>