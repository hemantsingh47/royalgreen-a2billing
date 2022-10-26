<?php
include('../confi.php');
$inst_table = new Table();  

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'])
{       
        $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);     
        $username = ltrim(str_replace('+','',$username),'0');  
		
         $customerinfo = "SELECT id from cc_card where username = '".$username."'";
         $customer_id = $inst_table -> SQLExec($DBHandle, $customerinfo); 
		 $customer_id = $customer_id[0]['id'];
		 //echo $customer_id;
        
        $sender_account_report = "select count(id) as total from cc_call WHERE card_id ='".$customer_id."'";
        $customer_res = $inst_table -> SQLExec($DBHandle, $sender_account_report);  
        $called_user = $customer_res[0]['total'];
         
         
		 header('Content-Type: application/json');
         $result =  array("result"=>"success","msg" => $called_user);
         echo json_encode($result); 
		  
 }
		  else
{
     header('Content-Type: application/json');
    echo json_encode(array("result"=>"failure","msg"=> "Invalid data!"));
    die;
}

?>