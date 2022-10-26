<?php
include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'lib/customer.smarty.php';
include 'lib/epayment/includes/configure.php';
include 'lib/NexmoMessage.php';



if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$inst_table = new Table();

$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status, " .
"freetimetocall, label, packagetype, billingtype, startday, id_cc_package_offer, cc_card.id, currency,cc_card.useralias,UNIX_TIMESTAMP(cc_card.creationdate) creationdate  FROM cc_card " .
"LEFT JOIN cc_tariffgroup ON cc_tariffgroup.id=cc_card.tariff LEFT JOIN cc_package_offer ON cc_package_offer.id=cc_tariffgroup.id_cc_package_offer " .
"LEFT JOIN cc_card_group ON cc_card_group.id=cc_card.id_group WHERE username = '" . $_SESSION["pr_login"] .
"' AND uipass = '" . $_SESSION["pr_password"] . "'";

$DBHandle = DbConnect();
$customer_res = $inst_table -> SQLExec($DBHandle, $QUERY);

if (!$customer_res || !is_array($customer_res)) {
    echo gettext("Error loading your account information!");
    exit ();
}

 
$customer_info= $customer_res[0];


if ($customer_info[14] != "1" && $customer_info[14] != "8") {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

//FOR COUNTRY NAME
$country_table = new Table('cc_country','countryname,countryprefix');
$country_clause = null;
$country_result = $country_table -> Get_list($DBHandle, $country_clause, 0);
  
$smarty->display('main.tpl');

if(isset($_POST['submit']))
{
	$card=    $_POST['accno'];
	$phone  = $_POST['recipient'];
	$country = $_POST['country'];
	$message = $_POST['message'];
	$mobile=$country.$phone;
		if($customer_info[credit]=='0'){
			
			echo $update_msg ="<div class=\"uk-alert uk-alert-success\" style=\"width:450px;height:25px; margin-left: 250px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">You don't have sufficient balance to send the msg..</font></b></div>";
		    die;
		}
	    $nexmo_sms = new NexmoMessage(NEXMO_USERNAME,NEXMO_PASSWORD);
	    $info = $nexmo_sms->sendText( $mobile, 'ALizabath', $message );
	    $data=(array)$info;
	    $val=(array)$data['cost'];
	    $amt =$val[0];  //amount value
		$instance_table = new Table("cc_sms_log","*");
		
		$credit=$customer_info[credit]-$amt;
		$clause = "id = '".$_SESSION["card_id"]."' ";
        $instance_cr = new Table("cc_card","credit");
	    $values="credit='$credit'";
	    $credit=$instance_cr -> Update_table($DBHandle, $values, $clause);
		
		$fields="card_number,ccode,phone,msg,smsCharges";
		$values="'$card','$country','$phone','$message','$amt'";
		$result=$instance_table->Add_table($DBHandle, $values, $fields); 
	    
	
		$myvalue= (array)$info;
		$final_value = (array)$myvalue['messages'][0];
		$final_value['status'];
		if($final_value['status']=='0'){
			echo $update_msg ="<div class=\"uk-alert uk-alert-success\" style=\"width:450px;height:25px; margin-left: 250px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">Your Message has been send successfully..</font></b></div>";
		}else{
			echo $update_msg ="<div class=\"uk-alert uk-alert-success\" style=\"width:450px;height:25px; margin-left: 250px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">Sorry!! has been not send successfully..</font></b></div>";
		}
	
}


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>



<SCRIPT type="text/javascript">

             function ValidEditor(register)
				{
					var txt="";
					if(!register.recipient.value)
					{
						txt+="Mobile should not be empty.\n"
					}

					   if(txt)
						{
							   alert("Sorry!! Following errors has been occured :\n\n"+ txt +"\n     Please Check");
							   return false
						}
					return true
				}//-->
				
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



            function textCounter(field,cntfield,maxlimit) {
				
			   if (field.value.length > maxlimit) {
			   field.value = field.value.substring(0, maxlimit);
			   alert('Max lenght is 200 characters. You have typed ' + field.value.length + ' charachters.');
			   }
			   // otherwise, update 'characters left' counter
			   else
			   cntfield.value = maxlimit - field.value.length;
			}



			function minform(value) {
				
					if(value.message.value.length >200) 
					{
						alert("The Abstract Contents must be between in 200 characters.");
						value.message.focus();
						return false;
					}
					else 
					{
						return true;
					}
	        } 
</SCRIPT>
<style type = "text/css">
.popup .content .w-section input, textarea, .uneditable-input 
{
    width: 40% !important;
}
</style>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Send Messages </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Services                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Send SMS                        </a>
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
            <font class="kt-portlet__head-title"><?php echo gettext("Send Messages"); ?></font>
				</div>
			</div>
            
      <!--begin::Form-->
			<form name="minform" action="sendsms.php" class="kt-form" method="post" onSubmit="javascript:return ValidEditor(this);">
				<div class="kt-portlet__body">
					<div class="form-group row">
					<div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12">Account Number</label>
              <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="number" maxlength="20" class="form-control" placeholder="Account Number">
						    <span align="left" class="form-text text-muted">Please enter your account number here</span>
              </div>    
					</div>
					
					<div class="form-group row">
					<div class="col-lg-1"></div>
            <label class="col-lg-2 col-sm-12">Country Name</label>
              <div class="col-lg-6 col-md-9 col-sm-12">
								<?php 
                      ?> <select id="country" class="form-control" tabindex="" name="country">
                                     
                             <?php     for($i=0;$i<count($country_result);$i++)
                                {
                               ?>
                             <option value="<?php echo $country_result[$i]['countryprefix'] ?>"><?php echo $country_result[$i]['countryname'] ?></option>
                                    
                             <?php

                               }
                             ?>    
                        </select>            
              </div>
        	</div>

          <div class="form-group row">
					<div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12">Phone Number</label>
              <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="number" maxlength="20" class="form-control" placeholder="Phone Number">
						    <span align="left" class="form-text text-muted">Please enter your phone number here</span>
              </div>    
					</div>
					
          <div class="form-group row">
					<div class="col-lg-1"></div>
						<font class="col-lg-2 col-sm-12"><?php echo gettext("Message");?> :</font>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <textarea class="form-control" name="description" cols="100" rows="6" placeholder="Enter message here...." style="width: 100% !important;"></textarea>
                </div>    
					</div>
         
          <div class="form-group row">
					<div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12">Message Length</label>
              <div class="col-lg-6 col-md-9 col-sm-12">
								<input name="remLen2" type="text" class="form-control" value="200" size="5" maxlength="40" readonly />
								<span align="left" class="form-text text-muted">Message length should not exceed than 200</span>
              </div>    
					</div>
				</div>

        <div  align="center" class="kt-portlet__foot">
          <div class="form-actions">
              <input type="submit" name="submit" value="&nbsp;<?php echo gettext("Send")?>&nbsp;" class="btn btn-brand"> &nbsp; &nbsp;
							<input type="reset" name="cancel" value="&nbsp;Reset&nbsp;" class="btn btn-secondary">
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
  
  $smarty->display( 'footer.tpl');
?>


   