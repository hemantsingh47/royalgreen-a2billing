<?php
include_once('../confi.php');
$inst_table = new Table('cc_card','*');
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['username'] && $_POST['password'] && $_POST['firstname'] && $_POST['email']  )
 {
       $username = Security::decrypt(sanitize_data($_POST['username']), KEY_SECURE);
       $password = Security::decrypt(sanitize_data($_POST['password']), KEY_SECURE);
       $firstname = Security::decrypt(sanitize_data($_POST['firstname']), KEY_SECURE);
       //$lastname = Security::decrypt(sanitize_data($_POST['lastname']), KEY_SECURE);
       $email = Security::decrypt(sanitize_data($_POST['email']), KEY_SECURE);
       
       $clause = "username = '$username' AND uipass = '$password'";
       $values="";
       if($_POST['firstname'] != NULL OR trim($_POST['firstname']) !='')
       {
           $values .= ", firstname = '$firstname'";
       }
       /*if($_POST['lastname'] != NULL OR trim($_POST['lastname']) !='')
       {
           $values .= ", lastname = '$lastname'";
       } */
       if($_POST['email'] != NULL OR trim($_POST['email']) !='')
       {
           $values .= ", email = '$email'";
       }
       $values = ltrim($values,',');
       //print_r($values);die;
       $return = $inst_table -> Update_table($DBHandle, $values, $clause);
        //print_r($return);die;
        if($return)
        {
            //$result = (array("result"=>"success","msg"=>"Successfully updated"));
            $result = (array("result"=>"success","msg"=>"Records are successfully updated."));
        }
        else 
        {
            //$result = (array("result"=>"failure","msg"=>"Updation Failure"));
            $result = (array("result"=>"failure","msg"=>"Records not Updating."));
        } 
        echo json_encode($result);
        die;
        
        
 }
 else
 {
    // echo json_encode(array("result"=>"failure","msg"=>"data invalid"));
     echo json_encode(array("result"=>"failure","msg"=>"Records Not Found."));
     die;
 }  
?>
