<?php

 include('../confi.php');
 if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['source'] )
 {
       
        $source = Security::encrypt(sanitize_data($_POST['source']), KEY_SECURE);
        
        echo json_encode(array("result"=>"success" , 'value'=>$source ));die;
        
 }
 else
 {
     echo json_encode(array("result"=>"data invalid"));
     die;
 } 
 
?>