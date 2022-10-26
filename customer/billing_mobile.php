<?php

include 'lib/customer.defines.php';
include 'lib/customer.module.access.php'; 
include 'lib/Class.RateEngine.php';
include 'lib/customer.smarty.php';
include_once('lib/BulkSender.php');

 $DBHandle  = DbConnect();
 $logfilemtopup = "/var/log/a2billing/a2billing_mtopup.log";                                                      

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}
$smarty->display('main.tpl');
 
 //currency list
$currencies_list = get_currencies();
$percentage_toadd = 47;
//if(!isset($_REQUEST["pr_login"]) || !isset($_REQUEST["pr_password"]))
//{
	//echo "wrong username or password";
	//die;
//}
 $first_login = sanitize_data($_SESSION["pr_login"]);
 $password = sanitize_data($_SESSION["pr_password"]);        
 $QUERY ="SELECT id,username,credit,currency FROM cc_card WHERE username = '".$first_login."' AND uipass = '".$password."'";
 $result=mysql_query($QUERY);
 $row=(mysql_fetch_assoc($result));
 $currency="USD";
 $card_id = $row["id"];
 $username=$row["username"];
 // TransferTo account credentials         
          
  $login="adore";  //icl phonewala
 $token="adore";//4516ddd4079   CUggxK9  
 $msg="";
 //array for countries
$country_array=array();
array_push($country_array,array("cccode"=>"select", "ccname"=>"Select Country"));
array_push($country_array,array("cccode"=>"93", "ccname"=>"Afghanistan"));
array_push($country_array,array("cccode"=>"1-684", "ccname"=>"American Samoa"));
array_push($country_array,array("cccode"=>"1-264", "ccname"=>"Anguilla"));
array_push($country_array,array("cccode"=>"1-268", "ccname"=>"Antigua and Barbuda"));
array_push($country_array,array("cccode"=>"54", "ccname"=>"Argentina"));
array_push($country_array,array("cccode"=>"374", "ccname"=>"Armenia"));
array_push($country_array,array("cccode"=>"297", "ccname"=>"Aruba"));
array_push($country_array,array("cccode"=>"1-242", "ccname"=>"Bahamas"));
array_push($country_array,array("cccode"=>"973", "ccname"=>"Bahrain"));
array_push($country_array,array("cccode"=>"880", "ccname"=>"Bangladesh"));
array_push($country_array,array("cccode"=>"1-246", "ccname"=>"Barbados"));
array_push($country_array,array("cccode"=>"501", "ccname"=>"Belize"));
array_push($country_array,array("cccode"=>"229", "ccname"=>"Benin"));
array_push($country_array,array("cccode"=>"1-441", "ccname"=>"Bermuda"));
array_push($country_array,array("cccode"=>"591", "ccname"=>"Bolivia"));
array_push($country_array,array("cccode"=>"55", "ccname"=>"Brazil"));
array_push($country_array,array("cccode"=>"1-284", "ccname"=>"British Virgin Islands"));
array_push($country_array,array("cccode"=>"226", "ccname"=>"Burkina Faso"));
array_push($country_array,array("cccode"=>"257", "ccname"=>"Burundi"));
array_push($country_array,array("cccode"=>"855", "ccname"=>"Cambodia"));
array_push($country_array,array("cccode"=>"237", "ccname"=>"Cameroon"));
array_push($country_array,array("cccode"=>"1-345", "ccname"=>"Cayman Islands"));
array_push($country_array,array("cccode"=>"236", "ccname"=>"Central African Republic"));
array_push($country_array,array("cccode"=>"56", "ccname"=>"Chile"));
array_push($country_array,array("cccode"=>"86", "ccname"=>"China"));
array_push($country_array,array("cccode"=>"57", "ccname"=>"Colombia"));
array_push($country_array,array("cccode"=>"506", "ccname"=>"Costa Rica"));
array_push($country_array,array("cccode"=>"53", "ccname"=>"Cuba"));
array_push($country_array,array("cccode"=>"599", "ccname"=>"Curacao"));
array_push($country_array,array("cccode"=>"357", "ccname"=>"Cyprus"));
array_push($country_array,array("cccode"=>"243", "ccname"=>"Democratic Republic Of Congo"));
array_push($country_array,array("cccode"=>"1-767", "ccname"=>"Dominica"));
array_push($country_array,array("cccode"=>"1-809", "ccname"=>"Dominican Republic"));
array_push($country_array,array("cccode"=>"593", "ccname"=>"Ecuador"));
array_push($country_array,array("cccode"=>"20", "ccname"=>"Egypt"));
array_push($country_array,array("cccode"=>"503", "ccname"=>"El Salvador"));
array_push($country_array,array("cccode"=>"679", "ccname"=>"Fiji"));
array_push($country_array,array("cccode"=>"33", "ccname"=>"France"));
array_push($country_array,array("cccode"=>"241", "ccname"=>"Gabon"));
array_push($country_array,array("cccode"=>"220", "ccname"=>"Gambia"));
array_push($country_array,array("cccode"=>"233", "ccname"=>"Ghana"));
array_push($country_array,array("cccode"=>"1-473", "ccname"=>"Grenada"));
array_push($country_array,array("cccode"=>"502", "ccname"=>"Guatemala"));
array_push($country_array,array("cccode"=>"224", "ccname"=>"Guinea"));
array_push($country_array,array("cccode"=>"245", "ccname"=>"Guinea-Bissau"));
array_push($country_array,array("cccode"=>"592", "ccname"=>"Guyana"));
array_push($country_array,array("cccode"=>"509", "ccname"=>"Haiti"));
array_push($country_array,array("cccode"=>"504", "ccname"=>"Honduras")); 
array_push($country_array,array("cccode"=>"91", "ccname"=>"India"));
array_push($country_array,array("cccode"=>"62", "ccname"=>"Indonesia"));
array_push($country_array,array("cccode"=>"964", "ccname"=>"Iraq"));
array_push($country_array,array("cccode"=>"255", "ccname"=>"Ivory Coast"));
array_push($country_array,array("cccode"=>"1-876", "ccname"=>"Jamaica"));
array_push($country_array,array("cccode"=>"962", "ccname"=>"Jordan"));
array_push($country_array,array("cccode"=>"254", "ccname"=>"Kenya"));
array_push($country_array,array("cccode"=>"965", "ccname"=>"Kuwait"));
array_push($country_array,array("cccode"=>"996", "ccname"=>"Kyrgyzstan"));
array_push($country_array,array("cccode"=>"856", "ccname"=>"Laos"));
array_push($country_array,array("cccode"=>"231", "ccname"=>"Liberia"));
array_push($country_array,array("cccode"=>"561", "ccname"=>"Madagascar"));
array_push($country_array,array("cccode"=>"60", "ccname"=>"Malaysia"));
array_push($country_array,array("cccode"=>"223", "ccname"=>"Mali"));
array_push($country_array,array("cccode"=>"52", "ccname"=>"Mexico"));
array_push($country_array,array("cccode"=>"373", "ccname"=>"Moldova"));
array_push($country_array,array("cccode"=>"1-664", "ccname"=>"Montserrat"));
array_push($country_array,array("cccode"=>"212", "ccname"=>"Morocco"));
array_push($country_array,array("cccode"=>"258", "ccname"=>"Mozambique"));
array_push($country_array,array("cccode"=>"95", "ccname"=>"Myanmar"));
array_push($country_array,array("cccode"=>"674", "ccname"=>"Nauru"));
array_push($country_array,array("cccode"=>"977", "ccname"=>"Nepal"));
array_push($country_array,array("cccode"=>"505", "ccname"=>"Nicaragua"));
array_push($country_array,array("cccode"=>"227", "ccname"=>"Niger"));
array_push($country_array,array("cccode"=>"234", "ccname"=>"Nigeria"));
array_push($country_array,array("cccode"=>"92", "ccname"=>"Pakistan"));
array_push($country_array,array("cccode"=>"970", "ccname"=>"Palestine"));
array_push($country_array,array("cccode"=>"507", "ccname"=>"Panama"));
array_push($country_array,array("cccode"=>"675", "ccname"=>"Papua New Guinea"));
array_push($country_array,array("cccode"=>"595", "ccname"=>"Paraguay"));
array_push($country_array,array("cccode"=>"51", "ccname"=>"Peru"));
array_push($country_array,array("cccode"=>"63", "ccname"=>"Philippines"));
array_push($country_array,array("cccode"=>"48", "ccname"=>"Poland"));
array_push($country_array,array("cccode"=>"1-787", "ccname"=>"Puerto Rico"));
array_push($country_array,array("cccode"=>"40", "ccname"=>"Romania"));
array_push($country_array,array("cccode"=>"7", "ccname"=>"Russia"));
array_push($country_array,array("cccode"=>"250", "ccname"=>"Rwanda"));
array_push($country_array,array("cccode"=>"1-869", "ccname"=>"Saint Kitts and Nevis"));
array_push($country_array,array("cccode"=>"1-758", "ccname"=>"Saint Lucia"));
array_push($country_array,array("cccode"=>"VC", "ccname"=>"Saint Vincent and The Grenadines"));
array_push($country_array,array("cccode"=>"1-784", "ccname"=>"Samoa"));
array_push($country_array,array("cccode"=>"221", "ccname"=>"Senegal"));
array_push($country_array,array("cccode"=>"232", "ccname"=>"Sierra Leone"));
array_push($country_array,array("cccode"=>"65", "ccname"=>"Singapore"));
array_push($country_array,array("cccode"=>"677", "ccname"=>"Solomon Islands"));
array_push($country_array,array("cccode"=>"252", "ccname"=>"Somalia"));
array_push($country_array,array("cccode"=>"27", "ccname"=>"South Africa"));
array_push($country_array,array("cccode"=>"34", "ccname"=>"Spain"));
array_push($country_array,array("cccode"=>"94", "ccname"=>"Sri Lanka"));
array_push($country_array,array("cccode"=>"SD", "ccname"=>"Sudan"));
array_push($country_array,array("cccode"=>"249", "ccname"=>"Suriname"));
array_push($country_array,array("cccode"=>"268", "ccname"=>"Swaziland"));
array_push($country_array,array("cccode"=>"992", "ccname"=>"Tajikistan"));
array_push($country_array,array("cccode"=>"255", "ccname"=>"Tanzania"));
array_push($country_array,array("cccode"=>"66", "ccname"=>"Thailand"));
array_push($country_array,array("cccode"=>"228", "ccname"=>"Togo"));
array_push($country_array,array("cccode"=>"676", "ccname"=>"Tonga"));
array_push($country_array,array("cccode"=>"1-868", "ccname"=>"Trinidad and Tobago"));
array_push($country_array,array("cccode"=>"216", "ccname"=>"Tunisia"));
array_push($country_array,array("cccode"=>"90", "ccname"=>"Turkey"));
array_push($country_array,array("cccode"=>"1-649", "ccname"=>"Turks and Caicos Islands"));
array_push($country_array,array("cccode"=>"256", "ccname"=>"Uganda"));
array_push($country_array,array("cccode"=>"44", "ccname"=>"UK"));
array_push($country_array,array("cccode"=>"380", "ccname"=>"Ukraine"));
array_push($country_array,array("cccode"=>"598", "ccname"=>"Uruguay"));
array_push($country_array,array("cccode"=>"1", "ccname"=>"USA"));
array_push($country_array,array("cccode"=>"998", "ccname"=>"Uzbekistan"));
array_push($country_array,array("cccode"=>"678", "ccname"=>"Vanuatu"));
array_push($country_array,array("cccode"=>"84", "ccname"=>"Vietnam"));
array_push($country_array,array("cccode"=>"967", "ccname"=>"Yemen"));
array_push($country_array,array("cccode"=>"260", "ccname"=>"Zambia"));
array_push($country_array,array("cccode"=>"ZW", "ccname"=>"Zimbabwe"));
//print_r($country_array);     
//this is for sneding new number for topup
$mixnum=null;
//give operators and charge

if($_SERVER['REQUEST_METHOD'] == "POST"  && isset($_POST['mr_phone_no']) && isset($_POST['mr_phone_prefix']) && isset($_POST['action']) )
{
    $fno= sanitize_data($_POST['mr_phone_no']);
    $action= sanitize_data($_POST['action']);
    $prefix= sanitize_data($_POST['mr_phone_prefix']);
    $company = $user;  
    $destination_msisdn="+".$prefix.$fno;
     $mixnum= $prefix.$fno;
    if(strcmp(trim($action),"msisdn_info")==0  )
        {
          
            // MD5 calculation for info only
           $key=time().rand(1,999999);
           $md5=md5($login.$token.$key);
           $url ="https://fm.transfer-to.com/cgi-bin/shop/topup?login=".$login."&key=".$key."&md5=".$md5."&destination_msisdn=".$destination_msisdn."&action=".$action."&delivered_amount_info=1&currency=".$currency;
           $responses = split("\n", file_get_contents($url) );
           $res_array=array();
            for($i=0;$i<count($responses);$i++)
            {
                  $a = explode('=', $responses[$i]);
                  $res_array[$a[0]]=($a[1]);
             
            }
            
           // echo  ($res_array);
           //print_r($res_array);
		   //die;
           
      
        }
        else{
            echo json_encode(array("status" => "You are in the wrong place..."));
            
        }    
     

}
//for topup
else if($_SERVER['REQUEST_METHOD'] == "POST"  && isset($_POST['clear_get'])  && isset($_POST['mix_number']) && isset($_POST['mix_value']) )
{
    write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : START \n Var : ".print_r($_REQUEST, true));
    if(md5("49%#%&fadsdbxhi")==$_POST['clear_get'] && $_POST['mix_number']!= NULL && $_POST['mix_number'] >0 &&  ($_POST['mix_value']>0) && ($_POST['mix_value']!= NULL ))
    {
                 $mixnum= sanitize_data($_POST['mix_number']);
                 $mixnum="+".$mixnum;
                 $mixvalue = sanitize_data($_POST['mix_value']);
                 $daily_max_count = 15;
				// $card_id=$_SESSION["card_id"];
                 $company = "Yepingo";
                 $key=time().rand(1,999999);
                 $md5=md5($login.$token.$key);
                 
				// die;
                 
                 $url ="https://fm.transfer-to.com/cgi-bin/shop/topup?login=".$login."&key=".$key."&md5=".$md5."&destination_msisdn=".$mixnum."&action=msisdn_info&currency=".$currency;
				 $responses = split("\n", file_get_contents($url) );
                 write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : URL CALLED \n Var : ".$url."\n");
				 
				 $desmond = array();
                 $strresponse_product = '';
				    for($i=0;$i<count($responses);$i++)
				    {
					      $a = explode('=', $responses[$i]);
					      $desmond[$a[0]] = ($a[1]);
                          $strresponse_product .= $a[0]." => ".$a[1]."\n";
				     
				    }
                    write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : URL RESPONSE \n Var : ".$strresponse_product."\n");
                    
			   $array_comp = array();
			   $product_arr=explode(",",$desmond["product_list"]);
			   $retail_arr=explode(",",$desmond["wholesale_price_list"]);
			   for($i=0;$i<count( $product_arr);$i++)
				{
				   $b = $product_arr[$i];
				   $a = $retail_arr[$i];
				   $array_comp[$a] = $b;
				} 
                
				$subam = array_search ((float)$mixvalue, $array_comp); 
				$CONVERTED_AMOUNT_GBP_TO_EUR = convert_currency($currencies_list, (float)$subam, "GBP", "EUR");
				
				write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : CONVERTED AMOUNT FROM GBP TO EURO SELECTED : ".$CONVERTED_AMOUNT_GBP_TO_EUR."\n");
				
				if($CONVERTED_AMOUNT_GBP_TO_EUR > 0 )
				{
					$CONVERTED_AMOUNT_GBP_TO_EUR_WITH_PERCENTAGE = $CONVERTED_AMOUNT_GBP_TO_EUR + (float)($CONVERTED_AMOUNT_GBP_TO_EUR*$percentage_toadd*0.01);
					write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : CONVERTED AMOUNT WITH PERCENTAGE  : ".$CONVERTED_AMOUNT_GBP_TO_EUR_WITH_PERCENTAGE."\n");
					//getting existance of number
					$user_info_table = new Table("cc_card", "id, credit");
		
					$clause_user = "id = ".$card_id;
					$result = $user_info_table -> Get_list($DBHandle,$clause_user);
					$current_amount = ($result[0]['credit']);
					 $remaining_credit = (float)($current_amount - $CONVERTED_AMOUNT_GBP_TO_EUR_WITH_PERCENTAGE);
					$user_id = ($result[0]['id']);
					
					if (!$result) 
					{   
						$msg="user doesn't exist";
						write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : USER NOT EXIST  : \n");
					}
					else
					{
						//checking maximum count
						$topup_count_table = new Table("cc_friend_recharge_count", "id, topup_count");
						$clause_topup = "card_id = ".$user_id." AND created_date LIKE '".date("Y-m-d")."%'";
						$result_topup_now = $topup_count_table -> Get_list($DBHandle,$clause_topup);
						$topupcount = count($result_topup_now);
						write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__."MTOPUP : USER FOUND TOPUPCOUNT  : ".$topupcount."\n");
						
						   //checking sufficient credit
						   if($topupcount < $daily_max_count)
						   {        
							   //if user exists
							   $key=time();
							   $md5=md5($login.$token.$key);
							  if(($current_amount)>0 && ($remaining_credit)>=0  )
								{
									//elligible  sending the parameters to Tshop API and processing the results
									write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : SUCCESSFULY ENTERED START MTOPUP ON MOBILE\n");														  
									$url ="https://fm.transfer-to.com/cgi-bin/shop/topup?login=".$login."&key=".$key."&md5=".$md5."&delivered_amount_info=1&destination_msisdn=".$mixnum."&currency=".$currency."&msisdn=".$company."&product=".$mixvalue."&action=topup";
									
									
									$responses = split("\n", file_get_contents($url) );
									write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : URL CALLED TOPUP : ".$url."\n");
									
									//print_r($responses); 
									$response_str="";
									$finalarray=array();
									
									for($i=0;$i<count($responses);$i++)
									{
										  $a = explode('=', $responses[$i]);
										  $finalarray[$a[0]]=($a[1]);
										  $response_str.="[$a[0]=$a[1]], " ;
									}
									write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : TOPUP RESPONSE : $response_str \n");
								   $error_code=$finalarray["error_code"];
									if(($finalarray["error_code"]==0))
										{ 
											 write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : SUCCESSFULLY RECHARGED \n");
											 $msg=  "your mobile ".$mixnum." is successfully recharged, Thank you for using our service...";
											 
											 $destination="EUR";
											 $stramount1 = $CONVERTED_AMOUNT_GBP_TO_EUR_WITH_PERCENTAGE." ".$destination;
											 
											 $recipient =$userphone='39'.ltrim($phone, '0');
											 $message_rec="User ".$recipient." has recharged Mobile number ".$mixnum." for ".$stramount1." ! ";
											 $obj = new BulkSender("smsplus.routesms.com","8080",'yepingo','s8u3c9v3','TSTMSG',$message_rec,$recipient,'0','1');
											 $msg1=$obj->Submit();
											
											//if success
											$countsql = "INSERT INTO cc_friend_recharge_count ( card_id, topup_count, created_date) VALUES('".$card_id."','1','".date("Y-m-d")."')";
											
											$resultcount = mysql_query($countsql);
											$sql = "INSERT INTO cc_friend_recharge(apilogin, keyused, mdused, amt, msdest,username,card_id, response,error_code,send_number) VALUES ('$login','$key','$md5','$stramount1','$mixnum','$username','$card_id','$response_str','$error_code','$recipient')";
											write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : RECHARGE TABLE SQL  : ".$sql."\n");
											$result = mysql_query($sql);
											if (!$result)
											{
												$msg=  "your mobile ".$mixnum." is successfully recharged.";
											}
										   
										   $sql = "UPDATE cc_card SET credit='$remaining_credit' where id = ".$card_id;
										   write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : cc_card TABLE SQL  : ".$sql."\n");
										   $result1 = mysql_query($sql);
										   if (!$result1) 
											{    $msg=  "your mobile ".$mixnum." is successfully recharged, Thank you for using our service...but not inserted in card table for credit..";
											}
										 
										}
										else
										{   
											 
											$msg=  "Mobile  '.$mixnum.' is not successfully recharged, Please try again later. Error Code : ".$finalarray["error_code"];  
										//if error
										  $sql = "INSERT INTO cc_friend_recharge(apilogin, keyused, mdused, amt, msdest,username,card_id, response,error_code,send_number) VALUES ('$login','$key','$md5','0','$mixnum','$username','$card_id','$response_str','$error_code','$recipient')";
										 $result = mysql_query($sql);
										 if (!$result) 
										 {   
											   
												  $msg= "Mobile  '.$mixnum.' is not successfully recharged, Please try again later also not inserted in friends the table";
										 }
									   
										   
										 
										 //echo "<div align='center'>Mobile  $destination_msisdn is not successfully recharged, Please try again later...</div>";
										 }


								 }
									  
								else
								{
									   
										 $msg= "NOT sufficient credit, please credit your account!"; 
										 
								}
							   
						   }
						   else
						   {
							   $msg= "Your maximum daily limit has been reached, please try tomorrow...";
						   }
							
							
						} 
					} 
					else
					{
						$msg="You are doing mistake";
					}
                                
                }
	else
	{
		//echo '<script type="text/javascript">alert("You are doing it wrong");</script>';
		$msg="You are doing it wrong";
	}
    write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : MSG SENT TO PHONE  : ".$msg."\n");
	write_log($logfilemtopup, basename(__FILE__).' line:'.__LINE__." MTOPUP : END  : \n");
    
} 

  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HEAD runat="server">
    <link rel="shortcut icon" href="templates/default/images/adore.ico">
    <title>..:: Billing Solution: CallingCard, CallBack & VOIP Billing Solution ::..</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui">
    <link href="templates/default/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!--<link href="templates/default/css/newcss.css" rel="stylesheet" type="text/css">  -->
    <link href="templates/default/css/invoice.css" rel="stylesheet" type="text/css">
    <link href="templates/default/css/receipt.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">    
        var IMAGE_PATH = "templates/default/images/";
    </script>
    <script type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"></script> 
    <script type="text/javascript" src="./javascript/jquery/jquery.debug.js"></script>
    <script type="text/javascript" src="templates/default/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="./javascript/jquery/handler_jquery.js"></script>
    <script language="javascript" type="text/javascript" src="./javascript/misc.js"></script>
     
    
    <script type="text/javascript">
    function setAmount(setvalue,setname) {
        document.getElementById("hidden_amount").value = setvalue;
        document.getElementById("amount_value").value = setname;
        document.getElementById("mix_value").value = setvalue; 
    }
</script>
<script type="text/javascript">
    function countrycode(code) {
        document.getElementById("mr_phone_prefix").value = code;
    }
</script>


 
<script type="text/javascript">
//Function to allow only numbers to textbox
function validate(key)
{
//getting key code of pressed key
var keycode = (key.which) ? key.which : key.keyCode;
var phn = document.getElementById('mr_phone_no');
//comparing pressed keycodes
if (!(keycode==8 || keycode==46)&&(keycode < 48 || keycode > 57))
{
return false;
}
else
{
//Condition to check textbox contains ten numbers or not
if (phn.value.length <10)
{
return true;
}
else
{
alert("Enter only 10 digits.");    
return false;
}
}
}
</script>
    <script src="./javascript/stuHover.js" type="text/javascript"></script>
</head>

<body>
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Mobile TopUp </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Services                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Mobile Recharge                        </a>
                </div>
            </span>
        </div>
    </div>
    <div id="mr_result" style="color: red;" align="center">
       <h4> <?php
            echo  $msg;
         ?></h4>
    </div>
</div>
<!-- end:: Subheader -->

<!-- <div id="page_content_inner">
    <h3 class="heading_b uk-margin-bottom">Mobile Top Up</h3>
	<div id="mr_result" style="color: red;" align="center">
       <h4> <?php
            echo  $msg;
         ?></h4>
    </div> -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
                    <font class="kt-portlet__head-title"><?php echo gettext("Mobile TopUp"); ?></font>
				</div>
			</div>
            
            <!--begin::Form-->
			<form name="minform" action="billing_mobile.php" class="kt-form" method="post" onSubmit="javascript:return ValidEditor(this);">
				<div class="kt-portlet__body">
					<div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-2 col-sm-12"><?php echo gettext("Select country");?></label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                            <select onchange="countrycode(this.value)" name="mr_country" class="form-control">
                        <?php
                         
                            for($i=0;$i<count($country_array);$i++)
                            {
                                echo "<option value='".$country_array[$i]["cccode"]."'";
                                if(isset($_POST['mr_country'])){if($_POST['mr_country']==$country_array[$i]["cccode"]){ echo "selected ";} }
                                echo ">".$country_array[$i]["ccname"]."</option>";
                            }
                        ?> 
						</select>           
                    </div>
        	    </div>
                
                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12"><?php echo gettext("Mobile Number");?></label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <!-- <input type="hidden" name="action" id="action" value="msisdn_info"/>  -->
                            <span class="add-on"> <input type="text" placeholder="Prefix"  id="mr_phone_prefix" name="mr_phone_prefix" value="<?php if(isset($_POST['mr_phone_prefix'])){echo $prefix; }?>" maxlength="3" readonly="readonly" class="form-control" style="width:10%; float:left;"></span>
                            <input type="number"  autocomplete="off" id="mr_phone_no"  name="mr_phone_no" placeholder="Enter number here" class="form-control"  onblur="(document.getinfoform.submit())" value="<?php if(isset($_POST['mr_phone_no'])){echo $fno; }?>" style="width:90%; float:left;">
                            <span class="form-text text-muted">Please Click Outside after enter mobile number</span>
                        </div>    
				</div>
				
                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12"><?php echo gettext("Operator");?></label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <?php
				                if(!is_null($res_array["operatorid"]) && !empty($res_array["operatorid"]))
					                {
					                    echo "<img src='https://fm.transfer-to.com/logo_operator/logo-".$res_array["operatorid"]."-1.png' style='border:#194772 2px solid; padding:2px; ' />" ;
		                            }
				            ?>
				        </div>
                </div>    
         
                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12"><?php echo gettext("Amount");?></label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <?php
                                if(!is_null($res_array["product_list"]) && !empty($res_array["product_list"]))
                                    {
                                        $usd_arr = array();
                                        if(!is_null($res_array["wholesale_price_list"]) && !empty($res_array["wholesale_price_list"]))
                                            {
                                                $usd_arr = explode(",",$res_array["wholesale_price_list"]);
                                            }
                                            $product_arr = explode(",",$res_array["product_list"]);
                                            echo "<table><tr>";
                  
                                            for ($i = 0; $i < count($product_arr); $i++) {
                        
                                                $CONVERTED_AMOUNT_GBP_TO_EUR = convert_currency($currencies_list, (float)$usd_arr[$i], "GBP", "EUR");
                                                $CONVERTED_AMOUNT_GBP_TO_EUR_WITH_PERCENTAGE = $CONVERTED_AMOUNT_GBP_TO_EUR + (float)($CONVERTED_AMOUNT_GBP_TO_EUR*$percentage_toadd*0.01);
                         
                                                echo "<td class='main_button color1 large_btn bottom_space' style='margin-right:3%'><input class='btn btn-success btn-small' style='margin:5px;'  type='button' name='".$product_arr[$i]."' value='".$product_arr[$i]."' onclick='setAmount(this.value,".round($CONVERTED_AMOUNT_GBP_TO_EUR_WITH_PERCENTAGE,3).")' ></td> ";
                                                if (($i + 1) % 3 == 0) {
                                                    echo "</tr>";
                                                }
                                                    //if($product_arr[$i] <= 1000){
                                                    //}-->
                                            }
                                            echo "</table>"; 
                                    }
                            ?>
                        </div>
                    <input type="hidden" name="hidden_amount" id="hidden_amount" value="0" /> 
                </div>

                <!--start :: inner-Form --> 
                <form name="minform" class="kt-form" method="post" action="<?php echo $_SERVER['PHP_SELF']."?pr_login=".$first_login."&pr_password=".$password ?>">
                <div class="kt-portlet__body"  style="padding-left : 0px;">
					<div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-2 col-sm-12"><?php echo gettext("Selected Amount");?></label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <input type="button" class="btn btn-info" id="amount_value" name="amount_value" value="0" style="border:0px; margin-left: 9px;">
                                <?php
                                    if(!is_null($res_array["destination_currency"]) && !empty($res_array["destination_currency"]))
                                    {
                                        //echo "<input type='button' class='btn btn-info' style='margin-left: -10px;z-index: 0;border-left: none;' value='".$res_array["destination_currency"]."'/>";
                                    }
                                ?>
                                <input type="hidden" class="btn btn-info" id="mix_value" name="mix_value"  >
                                <input type="hidden" name="mix_number" id="mix_number" value="<?php if(!is_null($mixnum) && $mixnum!=""){ echo $mixnum;}  ?>" />
                                <input type="hidden" name="clear_get" id="clear_get" value="<?php echo md5("49%#%&fadsdbxhi") ?>" />
                            </div>
                    </div>
                </div>
                </form>
                <!-- end :: inner-Form -->

                <div align="center" class="kt-portlet__foot">
                    <div class="form-actions">
			            <input class="btn btn-brand" type="submit" name="btnSubmit" value="<?php echo gettext("Continue");?>" id="btnSubmit" onclick="return confirm('Are you sure you want to continue..')" /> &nbsp; &nbsp;
                        <input type="reset" name="cancel" value="&nbsp;Reset&nbsp;" class="btn btn-secondary">
                    </div>
                </div>                        

			</form>
			<!--end::Form-->
    </div>
		<!--end::Portlet-->
  </div>
</div>
<!-- end:: Content -->
<!-- <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Mobile Top Up</h5>
        </div>
       
		<form name="getinfoform" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']."?pr_login=".$first_login."&pr_password=".$password;?>" method="post">
            <div class="control-group" style="margin-left: 50px;">
              <label class="control-label"><?php echo gettext("Select Country :");?></label>
              <div class="controls">
				<select onchange="countrycode(this.value)" name="mr_country" class="span4">
                        <?php
                         
                            for($i=0;$i<count($country_array);$i++)
                            {
                                echo "<option value='".$country_array[$i]["cccode"]."'";
                                if(isset($_POST['mr_country'])){if($_POST['mr_country']==$country_array[$i]["cccode"]){ echo "selected ";} }
                                echo ">".$country_array[$i]["ccname"]."</option>";
                            }
                        ?> 
						</select>
              </div>
            </div>
			
			<div class="control-group" style="margin-left: 50px;">
              <label class="control-label"><?php echo gettext("Mobile No :"); ?></label>
              <div class="controls">
                <div class="input-prepend">
				<input type="hidden" name="action" id="action" value="msisdn_info"/>
                <span class="add-on"> <input type="text" placeholder="prefix"  id="mr_phone_prefix" name="mr_phone_prefix" value="<?php if(isset($_POST['mr_phone_prefix'])){echo $prefix; }?>" maxlength="3" readonly="readonly" class="span3" style="width:40px;background: transparent;border:none;text-align: right;"></span>
                <input type="text"  autocomplete="off" id="mr_phone_no"  name="mr_phone_no" placeholder="Enter Number" class="span3"  onblur="(document.getinfoform.submit())" value="<?php if(isset($_POST['mr_phone_no'])){echo $fno; }?>">
                </div><br/>
				<span id="number_format_example" style="padding-top:5px;color:green; font-style:italic;cursor:pointer" >Please Click Outside after enter mobile number.</span>
              </div>
            </div>
			</form>
            <div class="control-group" style="margin-left: 50px;">
              <label class="control-label" style="padding-left:115px;"><?php echo gettext("Operator :"); ?></label>
			  <div class="controls" id="ans1" >
			  
				<?php
				if(!is_null($res_array["operatorid"]) && !empty($res_array["operatorid"]))
					{
					echo "<img src='https://fm.transfer-to.com/logo_operator/logo-".$res_array["operatorid"]."-1.png' style='border:#194772 2px solid; padding:2px; ' />" ;
		   
					}
				?>
				</div>
				
            </div>
            <div class="control-group" style="margin-left: 50px;">
              <label class="control-label" style="padding-left:125px;"><?php echo gettext("Amount :");?></label>
              
				<div class="controls" id="ans">
            <?php
                if(!is_null($res_array["product_list"]) && !empty($res_array["product_list"]))
                {
                    $usd_arr = array();
                    if(!is_null($res_array["wholesale_price_list"]) && !empty($res_array["wholesale_price_list"]))
                    {
                         $usd_arr = explode(",",$res_array["wholesale_price_list"]);
                         
                    }
                    
                    
                   $product_arr = explode(",",$res_array["product_list"]);
                   echo "<table><tr>";
                  
                   for ($i = 0; $i < count($product_arr); $i++) {
                        
                         $CONVERTED_AMOUNT_GBP_TO_EUR = convert_currency($currencies_list, (float)$usd_arr[$i], "GBP", "EUR");
                         $CONVERTED_AMOUNT_GBP_TO_EUR_WITH_PERCENTAGE = $CONVERTED_AMOUNT_GBP_TO_EUR + (float)($CONVERTED_AMOUNT_GBP_TO_EUR*$percentage_toadd*0.01);
                         
                                echo "<td class='main_button color1 large_btn bottom_space' style='margin-right:3%'><input class='btn btn-success btn-small' style='margin:5px;'  type='button' name='".$product_arr[$i]."' value='".$product_arr[$i]."' onclick='setAmount(this.value,".round($CONVERTED_AMOUNT_GBP_TO_EUR_WITH_PERCENTAGE,3).")' ></td> ";
                                if (($i + 1) % 3 == 0) {
                                echo "</tr>";
                                }
                            //if($product_arr[$i] <= 1000){
                            //}-->
                     }
                 echo "</table>"; 
                }
                
            ?>
           </div> 
           <input type="hidden" name="hidden_amount" id="hidden_amount" value="0" /> 
           </div>   
            <div class="control-group" style="margin-left: 50px;">
              <label class="control-label" style="padding-left:67px;"><?php echo gettext("Send to recipient :");?></label> 
                  <input type="checkbox" name="sneder_response" id="sneder_response" style="margin-left: 213px;" checked /> 
            </div> 
			<div class="others">
			<form name="getupdate_characters" class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']."?pr_login=".$first_login."&pr_password=".$password ?>">
            <div class="control-group" style="margin-left: 50px;">
              <label class="control-label" style="padding-left:5px;"><?php echo gettext("Selected Amount :");?></label>
              <div class="controls">
                <input type="button" class="btn btn-info" id="amount_value" name="amount_value" value="0" style="border:0px; margin-left: 9px;">
                <?php
                                if(!is_null($res_array["destination_currency"]) && !empty($res_array["destination_currency"]))
                                {
                                     //echo "<input type='button' class='btn btn-info' style='margin-left: -10px;z-index: 0;border-left: none;' value='".$res_array["destination_currency"]."'/>";
                                }
                            ?>
                            <input type="hidden" class="btn btn-info" id="mix_value" name="mix_value"  >
                            <input type="hidden" name="mix_number" id="mix_number" value="<?php if(!is_null($mixnum) && $mixnum!=""){ echo $mixnum;}  ?>" />
                            <input type="hidden" name="clear_get" id="clear_get" value="<?php echo md5("49%#%&fadsdbxhi") ?>" />
            </div>
            <div class="form-actions"> 
			  <input class="btn btn-success" type="submit" name="btnSubmit" style="margin-left: 19%;" value="<?php echo gettext("Continue");?>" id="btnSubmit" class="btn btn-primary" onclick="return confirm('Are you sure you want to continue..')" />
            </div>
          </form>
             
		  </div>
        </div>
      </div>
      </div>-->
<!--end -->

 <?php
$smarty->display('footer.tpl');
?>
     <script type="text/javascript">
    function setAmount(setvalue,setname) {
        document.getElementById("hidden_amount").value = setvalue;
        document.getElementById("amount_value").value = setname;
        document.getElementById("mix_value").value = setvalue; 
    }
</script>
<script type="text/javascript">
    function countrycode(code) {
        document.getElementById("mr_phone_prefix").value = code;
    }
</script>


 
<script type="text/javascript">
//Function to allow only numbers to textbox
function validate(key)
{
//getting key code of pressed key
var keycode = (key.which) ? key.which : key.keyCode;
var phn = document.getElementById('mr_phone_no');
//comparing pressed keycodes
if (!(keycode==8 || keycode==46)&&(keycode < 48 || keycode > 57))
{
return false;
}
else
{
//Condition to check textbox contains ten numbers or not
if (phn.value.length <10)
{
return true;
}
else
{
alert("Enter only 10 digits.");    
return false;
}
}
}
</script>
 