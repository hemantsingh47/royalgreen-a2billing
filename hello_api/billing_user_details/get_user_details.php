<?php
include_once('../confi.php');
$inst_table = new Table();
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['username'] && $_POST['password'] )
 {
       $username = Security::decrypt(sanitize_data($_POST['username']), KEY_SECURE);
       //$username = sanitize_data($_POST['username']);
       $password = Security::decrypt(sanitize_data($_POST['password']), KEY_SECURE);
       //$password = sanitize_data($_POST['password']);
        $query_card_info = "SELECT username, uipass, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status FROM cc_card WHERE username = '$username' AND uipass = '$password'" ;
        $customer_info = $inst_table -> SQLExec($DBHandle, $query_card_info);
        if($customer_info)
        {
            $result = (array("result"=>"success","msg"=>$customer_info[0]));
        }
        else
        {
            $result = (array("result"=>"failure","msg"=>"No data found"));
        }
        echo json_encode($result);
        die;
        
 }
 else
 {
     echo json_encode(array("result"=>"data invalid"));
     die;
 }  
?>