<?php
include('../confi.php');
$inst_table = new Table();
$cc_table = new Table('cc_card','*');
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['cust_id'] && $_POST['cust_pass'] && $_POST['credit'] && $_POST['transferaccount'])
{       
        $username = Security::decrypt(sanitize_data($_POST['cust_id']), KEY_SECURE);
        $password = Security::decrypt(sanitize_data($_POST['cust_pass']), KEY_SECURE);
        $credit = Security::decrypt(sanitize_data($_POST['credit']), KEY_SECURE);
        $transferaccount = Security::decrypt(sanitize_data($_POST['transferaccount']), KEY_SECURE);
        
        $QUERY = "SELECT username, credit FROM cc_card WHERE username = '" .$username. "' AND uipass = '" . $password . "'";
        $result_message = "failure";
        $customer_res = $inst_table -> SQLExec($DBHandle, $QUERY);
       if($customer_res)
        {
            
            if($customer_res[0]['credit'] > 0 && (($customer_res[0]['credit'])-$credit)>=0 && $credit>0)
            {
                //Finding transfer account
                $query_transfer = "SELECT username,credit FROM cc_card WHERE username = '" .$transferaccount. "' ";
                $customer_transfer = $inst_table -> SQLExec($DBHandle, $query_transfer);
               
                if($customer_transfer)
                {
                    //update customer
                    $query_card_update_result = $cc_table -> Update_table($DBHandle, "credit =credit-'".$credit."'", "username='".$username."' AND uipass = '" . $password . "'");
                    if( $query_card_update_result)
                    {             
                      // update transfer account
                         $query_transfer_card_update_result = $cc_table -> Update_table($DBHandle, "credit =credit+'".$credit."'", "username='".$transferaccount."' ");
                        if($query_transfer_card_update_result)
                        {
                            $result = array("result"=>"success","msg" => "The balance was transferred successfully.");
                            $result_message = "success";
                      
                            $cdate = date("Y-m-d");
                            $ctime = date("h:i:s", time() + 9060);
                            $query_transfer_credit= "INSERT INTO cc_credit(transferFrom,transferTo,Amount,date,time,result_value)VALUES('".$username."','".$transferaccount."','".$credit."','".$cdate."','".$ctime."','".$result_message."')";
                           $credit_transfer = $inst_table -> SQLExec($DBHandle, $query_transfer_credit);

                             }
                        else
                        {
                            
                            //update customer
                             
                            $query_card_update_result_rollback = $cc_table -> Update_table($DBHandle, "credit =credit+'".$credit."'", "username='".$username."' AND uipass = '" . $password . "'");  
                            if($query_card_update_result_rollback)
                            {
                                  $result = array("result"=>"failure","msg" => "The Transfer account credit not updated rollback!");
                            }
                            else
                            {
                                 $result = array("result"=>"failure","msg" => "The Transfer account credit not updated!");
                            }
                           
                        }
                         
                    }
                    else
                    {
                          $result = array("result"=>"failure","msg" => "The user credit not updated!");
                    }  
                    
                }
                else
                {
                     $result = array("result"=>"failure","msg" => "The account number entered has not been found.");
                }
                   
            }
            else
            {
                $result = array("result"=>"failure","msg" => "Not enough balance available, please top up.");    
            } 
        }
        else
        {
            $result = array("result"=>"failure","msg" => "Customer not Found!");
        }
         /*
         $cdate = date("Y-m-d");
         $ctime = date("h:i:s", time() + 9060);
         $query_transfer_credit= "INSERT INTO cc_credit(transferFrom,transferTo,Amount,date,time,result_value)VALUES('".$username."','".$transferaccount."','".$credit."','".$cdate."','".$ctime."','".$result_message."')";
         $credit_transfer = $inst_table -> SQLExec($DBHandle, $query_transfer_credit);
       */
          
        echo json_encode($result);
        die;
}
else
{
    echo json_encode(array("result"=>"failure","msg"=> "Invalid data!"));
    die;
}

?>
