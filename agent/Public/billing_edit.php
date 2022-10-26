<?php             
include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/agent.smarty.php';
$DBHandle  = DbConnect();
if($_POST['submit'])
{
	
	$lastname = $_POST['lastname'];
	$firstname =$_POST['firstname'];
	$email =$_POST['email'];
	$city = $_POST['city'];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$address = $_POST['address'];
	$zipcode = $_POST['zipcode'];
	$phone = $_POST['phone'];
	$fax = $_POST['fax'];
	
											
	
	 
	$clause = "id = '".$_SESSION["agent_id"]."' ";
    $instance_table = new Table("cc_agent","*");
	$values="lastname='$lastname', firstname='$firstname', email='$email', city='$city', country='$country',address='$address',state='$state',zipcode='$zipcode',phone='$phone',fax='$fax'";
	$return=$instance_table -> Update_table($DBHandle, $values, $clause);
	
}

$QUERY = "SELECT  credit, currency, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, id, com_balance FROM cc_agent WHERE login = '".$_SESSION["pr_login"]."' AND passwd = '".$_SESSION["pr_password"]."'";
$numrow = 0;
$resmax = $DBHandle->Execute($QUERY);
if ($resmax)
$numrow = $resmax->RecordCount();

if($numrow==0)
	exit();
$customer_info =$resmax->fetchRow();
//FOR COUNTRY NAME
$country_table = new Table('cc_country','countryname,countrycode');
$country_clause = null;
$country_result = $country_table -> Get_list($DBHandle, $country_clause, 0);



$smarty->display( 'main.tpl');
?>
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Edit Agent Information </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Dashboard                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Edit Details                        </a>
                </div>
            </span>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
 <div class="user_content">
						<?php
																if($return==true){
	
																	echo $update_msg ="<div class=\"uk-alert uk-alert-success\" style=\"width:450px;height:25px; margin-left: 250px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">Your personal information has  been updated successfully .</font></b></div>";
	
																}
						?>
  
                        </div>

 
<!-- begin :: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="col-md-12" style="margin: 0 auto;">
	<!--begin::Portlet-->
	<div class="kt-portlet">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
                <font class="kt-portlet__head-title"><?php echo gettext("Edit Agent Information"); ?></font>
			</div>
		</div>
		<!--begin::Form-->
		<form id="myForm" action="billing_edit.php?message=success" method="post" class="kt-form" name="myForm">
			<div class="kt-portlet__body">
				<div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">First Name </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						<input type="text" name="firstname" size="100" maxlength="50" class="form-control" placeholder="Enter First Name here" value="<?php echo $customer_info[3];?>">
			            <span class="form-text text-muted">Please enter your first name here.</span>
                    </div>    
                </div>
          
                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Last Name </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						<input type="text" name="lastname" size="100" maxlength="50" class="form-control" placeholder="Enter Last Name here" value="<?php echo $customer_info[2];?>">
						<span class="form-text text-muted">Please enter your last name here.</span>
                </div>
                </div>
               
          
                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">E-mail </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
                            <input type="text" name="email" size="100" maxlength="50" class="form-control" placeholder="Enter E-mail ID here" value="<?php echo $customer_info[10];?>">
                        </div>
                    </div>    
                </div>
          
                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Address </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						<input type="text" name="address" size="100" maxlength="50" class="form-control" placeholder="Enter Address here" value="<?php echo $customer_info[4];?>">
						<span class="form-text text-muted">Please enter your address here</span>
                    </div>    
                </div>

                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">City </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						<input type="text" name="city" size="100" maxlength="50" class="form-control" placeholder="Enter City here" value="<?php echo $customer_info[5];?>">
						<span class="form-text text-muted">Please enter your city name here</span>
                    </div>    
                </div>

                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">State/Provience </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
				        <input type="text" name="state" size="100" maxlength="50" class="form-control" placeholder="Enter State/Provience here" value="<?php echo $customer_info[6];?>">
						<span class="form-text text-muted">Please enter your State/Provience name here</span>
                    </div>    
                </div>

                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Country </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <select id="country" class="form-control" tabindex="" name="country">
                            <?php     for($i=0;$i<count($country_result);$i++)
                                     {
                                    ?>
                                    <option value="<?php echo $country_result[$i]['countrycode'] ?>" <?php if($country_result[$i]['countrycode']==$customer_info['country']){ echo "selected";} ?>><?php echo $country_result[$i]['countryname'] ?></option>
                                    
                                     <?php

                                       }
                            ?>
                        </select>
                    </div>    
                </div>

                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Zip/Postal code </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						<input type="text" name="zipcode" size="100" maxlength="50" class="form-control" placeholder="Enter Zip/Postal code here" value="<?php echo $customer_info[8];?>">
						<span class="form-text text-muted">Please enter your Zip/Postal code here</span>
                    </div>    
                </div>


                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Phone Number </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
                            <input type="text" name="phone" size="100" maxlength="50" class="form-control" placeholder="Enter Phone Number here" value="<?php echo $customer_info[9];?>">
                        </div>    
                    </div>
                </div>
					
                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Fax </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-fax"></i></span></div>
                            <input type="text" name="fax" size="100" maxlength="50" class="form-control" placeholder="Enter Fax Number here" value="<?php echo $customer_info[11];?>">
                        </div>    
                    </div>
                </div>
            </div>
        
            <div align="center" class="kt-portlet__foot">
                <div class="form-actions">
                    <input type="submit" name="submit" value="Edit Profile" class="btn btn-brand">
                </div>
            </div>
		</form>
		<!--end::Form-->			
	</div>
	<!--end::Portlet-->
</div>
</div>
<!-- end :: Content -->

<?php
$smarty->display( 'footer.tpl');
