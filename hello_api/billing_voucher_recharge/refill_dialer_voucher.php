<?php
include_once('../confi.php');  
$inst_table = new Table(); 
//with sms forget
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['username'] && $_POST['password'] && $_POST['voucher'])
{
    $username = Security::decrypt(sanitize_data($_POST['username']), KEY_SECURE);
    //$username = sanitize_data($_POST['username']);
    $password = Security::decrypt(sanitize_data($_POST['password']), KEY_SECURE);
    //$password = sanitize_data($_POST['password']);
    $voucher = Security::decrypt(sanitize_data($_POST['voucher']), KEY_SECURE);
    //$voucher = sanitize_data($_POST['voucher']);
    $username = ltrim(str_replace('+','',$username),'0');
    $username = ereg_replace("[^0-9]", "", $username);
    $query_card = "SELECT * FROM cc_card WHERE username='".$username."' AND uipass='".$password."' ";
    $result_card_info = $inst_table -> SQLExec($DBHandle, $query_card);
    if (! $result_card_info || ! is_array($result_card_info)) 
    {
       $result = (array("result" =>"", "msg"=> "Error loading your account information!"));
       
    }
    else
    {
       // print_r($result_card_info); die;
       $voucher_info = "SELECT credit,activated,expirationdate FROM cc_voucher WHERE expirationdate >= CURRENT_TIMESTAMP AND activated='t' AND voucher='".$voucher."'";
       $voucher_info_result = $inst_table -> SQLExec($DBHandle, $voucher_info);
      // print_r($voucher_info_result);die;
       if($voucher_info_result[0]['activated'] == 't')
       {
           $inst_credit_table = new Table("cc_card","*");
           $inst_voucher_table = new Table("cc_voucher","*");
           $total_credit_amount =  ($result_card_info[0]['credit'])+($voucher_info_result[0]['credit']);
           $return_credit = $inst_credit_table -> Update_table($DBHandle, "credit ='".$total_credit_amount."'", "id = '".($result_card_info[0]['id'])."'");
           if($return_credit)
            {
                $update_voucher = $inst_voucher_table -> Update_table($DBHandle, "activated='f', used='1', usedcardnumber='".($result_card_info[0]['username'])."', usedate='".date('Y-m-d- H:i:s')."'", "voucher='".$voucher."'");
                if($update_voucher)
                {
                    $result = (array("result"=>"success","msg"=>"successfully recharged by voucher."));
                }
                else
                {
                    $result = (array("result"=>"success","msg"=>"successfully recharged by voucher but voucher is not updated"));
                }
                
            }
            else 
            {
                $result = (array("result"=>"failure","msg"=>" recharged by voucher is unsuccessful"));
            } 
            
           
       }
       else
       {
            $result = (array("result"=>"failure","msg"=>" No voucher information found"));   
       }
        
    }
    echo json_encode($result);
    exit ();
               
} 
else
{
    echo json_encode(array("result" =>"failure","msg"=> "Wrong Information provided!"));
    exit ();
}
      
?>