<?php

 

include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './lib/epayment/classes/payment.php';
include './lib/epayment/classes/order.php';
include './lib/epayment/classes/currencies.php';
include './lib/epayment/includes/general.php';
include './lib/epayment/includes/html_output.php';
include './lib/epayment/includes/loadconfiguration.php';
include './lib/epayment/includes/configure.php';
include './lib/customer.smarty.php';

if (! has_rights (ACX_ACCESS)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}
$username = $_SESSION["pr_login"];
$password = $_SESSION["pr_password"]; 
if ($A2B->config["webcustomerui"]['cdr']) exit();

$QUERY ="SELECT  ccc.credit, ccc.currency FROM cc_card as ccc,cc_sip_buddies as ccs  WHERE ccc.username = '".$first_login."' AND ccs.secret = '".$password."'";

$DBHandle = $DBHandle_max  = DbConnect();
$inst_table = new Table();
$cc_table = new Table('cc_card','*');
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['raccno']  && $_POST['amt'] )
{       
        
        $credit = sanitize_data($_POST['amt']);
        $transferaccount = sanitize_data($_POST['raccno']);
        
        $QUERY = "SELECT username, credit FROM cc_card WHERE username = '" .$username. "' AND uipass = '" . $password . "'";
        $result_message = "failure";
        $customer_res = $inst_table -> SQLExec($DBHandle, $QUERY);
       if($customer_res)
        {
            
            if(($customer_res[0]['username'])!=$transferaccount && $customer_res[0]['credit'] > 0 && (($customer_res[0]['credit'])-$credit)>=0 && $credit>0)
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
                            //$result = array("result"=>"success","msg" => "The Transfer account has been credited!");
                            $result_message = "The Transfer account has been credited!";
                            
                        }
                        else
                        {
                            
                            //update customer
                             
                            $query_card_update_result_rollback = $cc_table -> Update_table($DBHandle, "credit =credit+'".$credit."'", "username='".$username."' AND uipass = '" . $password . "'");  
                            if($query_card_update_result_rollback)
                            {
                                  //$result = array("result"=>"failure","msg" => "The Transfer account credit not updated rollback!");
                                  $result_message = "The Transfer account credit not updated rollback!" ;
                            }
                            else
                            {
                                 //$result = array("result"=>"failure","msg" => "The Transfer account credit not updated!");
                                  $result_message = "The Transfer account credit not updated!";
                            }
                           
                        }
                         
                    }
                    else
                    {
                          //$result = array("result"=>"failure","msg" => "The user credit not updated!");
                          $result_message = "The user credit not updated!";
                    }  
                    
                }
                else
                {
                     //$result = array("result"=>"failure","msg" => "The Account which has to be recharged not found!");
                     $result_message = "The Account which has to be recharged not found!";
                }
                   
            }
            else
            {
                //$result = array("result"=>"failure","msg" => "Do not have enough credit!");    
                if(($customer_res[0]['username'])!=$transferaccount)
                            {
                                  //$result = array("result"=>"failure","msg" => "Do not have enough credit!");
                                  $result_message = "Do not have enough credit!";
                            }
                            else
                            {
                                 //$result = array("result"=>"failure","msg" => "Can't transfer balance to same account.");
                                 $result_message = "Can't transfer balance to same account.";
                            }    
            
            } 
            
        }
        else
        {
            //$result = array("result"=>"failure","msg" => "Customer not Found!");
            $result_message = "Customer not Found!";
        }
         
         $cdate = date("Y-m-d");
         $ctime = date("h:i:s", time() + 9060);
         $query_transfer_credit= "INSERT INTO cc_credit(transferFrom,transferTo,Amount,date,time,result_value)VALUES('".$username."','".$transferaccount."','".$credit."','".$cdate."','".$ctime."','".$result_message."')";
         $credit_transfer = $inst_table -> SQLExec($DBHandle, $query_transfer_credit);
       
 
       
}
 
$smarty->display('main.tpl');




?>
 
 
<HEAD>
    <script LANGUAGE="JavaScript">
        function validate(evt) {
						var theEvent = evt || window.event;
						var key = theEvent.keyCode || theEvent.which;
						key = String.fromCharCode( key );
						var regex = /[0-9]|\./;
						if( !regex.test(key) ) {
						theEvent.returnValue = false;
						if(theEvent.preventDefault) 
						theEvent.preventDefault();
						alert("Please Enter Numbers Only");
				       }
				}
		
		
		
    </script>
    <script src="./javascript/stuHover.js" type="text/javascript"></script>
</head>
 
 <!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Balance Transfer </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Services                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Balance Transfer                        </a>
                </div>
            </span>
        </div>
    </div>
</div>
<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
            <font class="kt-portlet__head-title"><?php echo gettext("Balance transfer"); ?></font>
				</div>
			</div>
            
      <!--begin::Form-->
			<form name="theForm" class="kt-form" action="<?php $_SERVER['PHP_SELF']?>" method="post" onsubmit="javascript:return pinVal(this);">
				<div class="kt-portlet__body">
          <div class="form-group row">
          <div class="col-lg-1"></div>
						<font class="col-lg-2 col-sm-12"><?php echo gettext("Receiver Account/PIN");?></font>
                <div class="col-lg-6 col-md-9 col-sm-12">
                <input type="number" class="form-control" onkeypress='validate(event)' name="raccno" placeholder="Enter Pin here">
                <span align="left" class="form-text text-muted">Please enter Receiver PIN here</span>
                </div>    
					</div>
         
          <div class="form-group row">
          <div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12"><?php echo gettext("Amount");?></label>
              <div class="col-lg-6 col-md-9 col-sm-12">
                <input type="number" class="form-control" placeholder="Enter Amount here" onkeypress='validate(event)' name="amt" />
                <span align="left" class="form-text text-muted">Please enter Amount here</span>
              </div>    
					</div>

          <div class="form-group row">
						<label class="col-lg-4 col-sm-12"></label>
              <div class="col-lg-6 col-md-9 col-sm-12">
                <font style='color:#FF0000; font-size:15px; font-family:Verdana, Arial, Helvetica, sans-serif'>
                  <?php  
                      if($result_message){ echo $result_message;} 
                  ?>
			          </font>
              </div>    
					</div>
				</div>
        

        <div  align="center" class="kt-portlet__foot">
          <div class="form-actions">
              <input type="submit" name="submit" value="&nbsp;<?php echo gettext("Transfer")?>&nbsp;" class="btn btn-brand">&nbsp;&nbsp;
              <input type="reset" name="cancel" value="&nbsp;Clear&nbsp;" class="btn btn-secondary">
          </div>
        </div>
			</form>
			<!--end::Form-->
    </div>
		<!--end::Portlet-->
  </div>
</div>
<!-- end:: Content -->
 <?php
 $smarty->display('footer.tpl');
 
 ?>
 
 
 
   