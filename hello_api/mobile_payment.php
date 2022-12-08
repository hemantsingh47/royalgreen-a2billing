<?php
include('confi.php');
if($_SERVER['REQUEST_METHOD'] == "GET" && $_GET['pr_login'] && $_GET['pr_password'] && $_GET['mobiledone'])
{
	echo CUSTOMER_UI_URL; 
	Header ("Location: ../customer/checkout_payment_mo.php");
	
}
