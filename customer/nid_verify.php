<style>
body {font-family: Roboto;}

/* The Modal (background) */
.modal {
	position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 80%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
}

/* Modal Content */
.modal-content {
	position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    /* border: 1px solid #c11b1b; */
    border-radius: 40px;
    width: 80%;
    height: 100%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s;
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}


.modal-body 
{
	padding: 2px 16px;
}


.outer {
    display: inline;
    zoom: 1;
    position: relative;
    clip: auto;
    overflow: hidden;
    width: -webkit-fill-available;
}
</style>

<?php

include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';  
include 'lib/Class.RateEngine.php';
include 'lib/customer.smarty.php';
include_once('lib/BulkSender.php');

$DBHandle  = DbConnect();

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$currencies_list = get_currencies();
$percentage_toadd = 47;
if(!isset($_REQUEST["pr_login"]) || !isset($_REQUEST["pr_password"]))
{
	echo "wrong username or password";
	die;
}

$inst_table = new Table();

$first_login = $_SESSION["pr_login"];
$password = $_SESSION["pr_password"];        

$QUERY ="SELECT id, credit, currency, nin_status FROM cc_card WHERE username = '".$first_login."' AND uipass = '".$password."'";
$result = $inst_table->SQLExec($DBHandle,$QUERY);

$UserID = $result["0"]["id"];
$credit = $result["0"]["credit"];
$currency = $result["0"]["currency"];
$ninStatus = $result["0"]["nin_status"];

$prefix = "880";

$session_id = str_pad(str_replace('.', '', microtime(true)), 15, '0', STR_PAD_RIGHT);

$flag_table = new Table();

$flag_query = "SELECT flag, iso_name from cc_country_flag WHERE ccode='$prefix'";
$flag_array = $flag_table->SQLExec($DBHandle, $flag_query);
$flag = $flag_array["0"]["flag"];


if(isset($_POST["nidVerify"]))
{
	$ninName = $_POST["mr_NIN_name"];
	$ninDOB = $_POST["mr_NIN_dob"];
	$ninNumber = $_POST["mr_NIN_no"];
	
	$NIDurl = "";
	
	$data = array(
		"person_dob" => $ninDOB,
		"national_id" => $ninNumber,
		"person_fullname" => $ninName,
		"match_name" => true
	);
	
	$data = json_encode($data);	
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  //CURLOPT_URL => 'https://api.porichoybd.com/api/kyc/nid-person',
	  CURLOPT_URL => 'https://api.porichoybd.com/api/kyc/test-nid-person',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $data,
	  CURLOPT_HTTPHEADER => array(
		'x-api-key: fdbe1e4e-9538-4b95-a6d6-865579cbf10e',
		'Content-Type: application/json'
	  ),
	));

	$response = curl_exec($curl);
	$curl_error = curl_error($curl);

	$res = json_decode($response);
	
	$response = json_decode(json_encode($res), true);
	
	$verified = $response["passKyc"];
	
	$errorCode = $response["errorCode"];
	
	if($verified == "yes" && is_null($errorCode))
	{
		echo "<div id='myModal' class='modal'>
			<center><div class='modal-content'>
				
				<br><br>
				
				<div style=''><img src='success.png' style='width:200px; height:200px; float:center;'></div>
				<p style='color: #52bf82;margin: 40px;'>NID has been sucessfully verified.</p>
				
				<p style='color: #0d47a1;margin: 40px;'>Redirecting....</p>
			
			</div></center>
		</div>";
		
		
		$cardTab = new Table();
		$cardQuery = "SELECT id FROM cc_card WHERE username='$first_login'";
		$cardRes = $cardTab->SQLExec($DBHandle, $cardQuery);
		
		$cardID = $cardRes["0"]["id"];
		
		$nidTab = new Table();
		$nidQuery = "INSERT INTO cc_nid_data(card_id, account, fullName, DOB, NID_number) VALUES ('$cardID', '$first_login', '$ninName', '$ninDOB', '$ninNumber')";
		$nidRes = $nidTab->SQLExec($DBHandle, $nidQuery);
		
		$Tab = new Table();
		$Query = "UPDATE cc_card set nid_status='Verified' WHERE username='$first_login'";
		$Res = $Tab->SQLExec($DBHandle, $Query);
		
		$RedirectURL = "success.php?pr_login=$first_login&pr_password=$password&mobiledone=submit_log";
		
		header("Refresh:5; url=success.php?pr_login=$first_login&pr_password=$password&mobiledone=submit_log");;
	}
	else
	{
		echo "<div id='myModal' class='modal'>
			<center><div class='modal-content'>
				
				<br><br>
				
				<div style=''><img src='failure.png' style='width:200px; height:200px; float:center;'></div>
				<p style='color: #ff6868; margin: 40px;'>NID verification has been failed. Please try again</p>
				
				<a href='nid_verify.php?pr_login=$first_login&pr_password=$password&mobiledone=submit_log'><input type='button' value='Try Again' style='padding: 15px;padding-left: 40px;padding-right: 40px;background: #ffffff;color: #534d4d;border: 2px solid #3a3b3c;border-radius: 8px;font-weight: 700;'></a>
				
			</div></center>
		</div>";
	}
	
}	

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Alap Phone">
    <noscript><meta http-equiv="refresh" content="0;url=nojs-version.php"></noscript>
    <title>Hello App</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3pro.css">
	<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-teal.css">
	

</head>

<form name="minform" action="<?php echo "nid_verify.php?pr_login=".$first_login."&pr_password=".$password."&sessionID=".$session_id; ?>" class="kt-form" method="post">

	<section height="100%" class="contact-section-wrapper" style="background-color:#ffffff;">

		<div class="w3-container w3-card" style="background-color: #126a90;">
			<!--<h5 style="font-weight: 400; margin: 20px 0; color:#ffffff;"> Mobile top-up</h5>-->
		</div>
		
		<br><br>
		
		<div class="contact-section" >  
			<div  id="resmessage" style="display:none;"></div>
			<div class="container contact-block active" data-style="0" style="background:none; border:0;">
				
				<center style=" font-size:7vw; font-family: auto; color:#9E9E9E;">Verify NIN</center>
				
				<br>
				
				<?php
					if(!is_null($prefix) && !empty($prefix))
					{
					?>	
						<center>
							<img src="<?php echo $flag; ?>" width="150px;" height="60px;"/>
						</center>
					<?php
					}
				?>
				
				<div style="font-family: auto;color: #0d47a1;margin: 20px;margin-top: 0px;">
					<label class="container" >
						Full Name
					</label>
				</div>
				<div id="DIVnin" style="width: -webkit-fill-available;margin: 20px;">
					<input type="text"  autocomplete="off" id="mr_NIN_name"  name="mr_NIN_name" class="form-control"  style="padding: 20px;border: 2px solid #126a90;border-radius: 8px; width: -webkit-fill-available;" placeholder="Enter Full Name" autocomplete="off" value="<?php if(isset($_POST['mr_NIN_name'])){echo $ninName; }?>" required>
				</div>
				
				<div style="font-family: auto;color: #0d47a1;margin: 20px;margin-top: 0px;">
					<label class="container" >
						Date of Birth
					</label>
				</div>
				<div id="DIVnin" style="width: -webkit-fill-available;margin: 20px;">
					<input type="date" id="mr_NIN_dob"  name="mr_NIN_dob" class="form-control"  style="padding: 20px;border: 2px solid #126a90;border-radius: 8px; width: -webkit-fill-available;" placeholder="Enter your Date of Birth" autocomplete="off" value="<?php if(isset($_POST['mr_NIN_dob'])){echo $ninDOB; }?>" required>
				</div>
				
				<div style="font-family: auto;color: #0d47a1;margin: 20px;margin-top: 0px;">
					<label class="container" >
						National ID Number
					</label>
				</div>
				<div id="DIVnin" style="width: -webkit-fill-available;margin: 20px;">
					<input type="text"  autocomplete="off" maxlength="10" minlength="10" id="mr_NIN_no"  name="mr_NIN_no" class="form-control"  style="padding: 20px;border: 2px solid #126a90;border-radius: 8px; width: -webkit-fill-available;" placeholder="Enter NID Number" autocomplete="off" value="<?php if(isset($_POST['mr_NIN_no'])){echo $ninNumber; }?>" >
				</div>
			</div>
		</div>
		
		<footer style="position:fixed; left: 0; bottom: 0px; padding-bottom: 0px; width: 100%; color: white; text-align: center;"> 

			<input type="submit" name="nidVerify" value="Verify your NID" id="topup" class="btn btn-primary btn-big btn-block" style="border: 2px solid #006a4e;padding:15px;border-radius:8px;width:-webkit-fill-available;margin-left:30px;margin-right:30px;background: #00796b;color: #ffffff;">

		</footer> 
		
	</section>
		
	
		
</form>					
						