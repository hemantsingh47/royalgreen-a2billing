<?php
include_once('../confi.php'); 
$inst_table = new Table();


if($_SERVER['REQUEST_METHOD'] == "GET" && $_GET['username'] )
{
 $username = $_GET['username'];
 
 $query_card="SELECT  username FROM cc_card WHERE username ='$username'";
 $card_result = $inst_table -> SQLExec($DBHandle, $query_card);
			if ($username!= 0)
			{
			$query_update="UPDATE  cc_card set status ='1' WHERE username ='$username'";
			$otp_update_result = $inst_table -> SQLExec($DBHandle, $query_update);
			$result = array("result"=>"success","msg"=>"Your account is active now."); 
			echo json_encode($result);
			
			Header("Location: https://www.voifone.biz/customer/index.php");
			die;
			}
			else
			{
				echo "Record not found" ;
			}
			
			
}
?>