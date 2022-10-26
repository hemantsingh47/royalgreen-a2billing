<?php 
include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include '../lib/agent.smarty.php';

$DBHandle  = DbConnect();
$smarty->display('main.tpl');
$agentid = $_SESSION['agent_id'];
$update_msg ="";
if(isset($_POST['submit']))
{
 $company= $_POST['company'];
 $address = $_POST['address'];
 $email = $_POST['email'];
 $phone = $_POST['phone'];
 
 

    $clause = "agentid = '".$_SESSION["agent_id"]."' ";
    $instance_table = new Table("callshop_template","*");
	$values="company='$company', address='$address', email='$email', phone='$phone'";
	$return=$instance_table -> Update_table($DBHandle, $values, $clause); 
 
 
 if($return==true){
	 $update_msg ="<div class=\"uk-alert uk-alert-success\" style=\"width:450px;height:25px; margin-left: 250px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">Your Details has successfully been updated.</font></b></div>";	
}  
 } 
?> 

    <script LANGUAGE="JavaScript">
        function test(){
            if(document.theForm.company.value==""){
                alert("Please Enter Company Name!");
                return false;
            }
            if(document.theForm.address.value==""){
                alert("Please Enter Company Address!");
                return false;
            }
            if(document.theForm.email.value==""){
                alert("Please Enter Email-Id!");
                return false;
            }
            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            var address = document.theForm.email.value;
            if(reg.test(address) == false) {
            alert('Invalid Email Address');
            return false;
            }
            if(document.theForm.phone.value==""){
                alert("Please Enter Phone Number!");
                return false;
            }
            if (isNaN(document.theForm.phone.value)) {
            alert('Please Enter Only Numerical Values Into Phone Number.');
            return false;
            }
            if ((document.theForm.phone.value.length < 8) )
            {
            alert("Please Enter The At Least 8 Digit Phone Number");
            return false;
            }
            return true;
        }
    </script> 
 
  
  
  
  <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                CallShop                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            CallShop Config                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Receipt Template                      </a>
							  
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>


 <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  	<div class="" style="margin: 0 auto;">
	  	<!--begin::Portlet-->
		  <div class="kt-portlet">
			  <div class="kt-portlet__head">
				  <div class="kt-portlet__head-label">
            <font class="kt-portlet__head-title"><?php echo gettext("Details"); ?></font>
				  </div>
			  </div>
  
  
                                              
			<form name="theForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" onSubmit="return test()" class="kt-form" style="width:70%; margin:0 auto;">
				<div class="" style="padding-top:10px;"><?php echo $update_msg;?></div>
				<div class="">
					<label class="control-label" style="width: 30%;"><?php echo gettext("Company Name");?> </label>
					<div class="">
					  <input type="text" name="company" id="company" class="form-control13">
					</div>
                </div>
				<br style="clear:both">
				<div class="">
					<label class="control-label" style="width: 30%;"><?php echo gettext("Address");?> </label>
					<div class="">
					  <input type="text" name="address" id="address" class="form-control13">
					</div>
                </div>
				<br style="clear:both">
				<div class="">
					<label class="control-label" style="width: 30%;"><?php echo gettext("Email-Id ");?> </label>
					<div class="">
					  <input type="text" name="email" id="email" class="form-control13">
					</div>
                </div>
				<br style="clear:both">
				<div class="">
					<label class="control-label" style="width: 30%;"><?php echo gettext("Phone Number");?> </label>
					<div class="">
					  <input type="text" name="phone" id="phone" class="form-control13">
					</div>
        </div>
		<br style="clear:both">
				<div class="control-group">
					<div class="" style="text-align:center; padding:20px;">
						<input type="submit" name="submit" class="btn btn-brand" value="Update">
					</div>
                </div>


</form>

		   
<?php
$smarty->display('footer.tpl'); 
?> 
	


<?php
//getting  group_id from customer reference
$QUERY = "SELECT * FROM callshop_template WHERE agentid=$agentid"; 
$numrow = 0;
$resmax = $DBHandle->Execute($QUERY);
if ($resmax)
$numrow = $resmax->RecordCount(); 
if($numrow==0)
	exit();
    $customer_info =$resmax->fetchRow();                                         
?> 
<div class="row-fluid">
<table class="table table-bordered table-striped table-centered">
<thead>
	<tr><th>Company Name</th><th>Address</th><th>Email-Id</th><th>Phone Number</th></tr>
</thead>
<tbody>
	<tr>
		<td ><?php echo $customer_info['company']; ?></td>
		<td ><?php echo $customer_info['address']; ?></td>
		<td ><?php echo $customer_info['email']; ?></td>
		<td ><?php echo $customer_info['phone'];?></td>
	</tr>
</tbody>
</table> 
</div> 

</div>
	</div>
	</div>
	

</div>
       
	   </div>
	   
