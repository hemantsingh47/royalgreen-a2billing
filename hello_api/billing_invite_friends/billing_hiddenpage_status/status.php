<?php
   
header('Content-type: application/json');
include '../confi.php';                         

if($_SERVER['REQUEST_METHOD'] == "GET" && $_GET['value'])
{
	$pay = $_GET['value'];
	if($pay=='true')
	{
	  echo json_encode(array("status"=>"0"));
	}else
	{
		echo json_encode(array("status"=>"0"));
	}
}
    
?>