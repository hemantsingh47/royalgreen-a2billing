<?php

include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'lib/customer.smarty.php';
include 'lib/epayment/includes/configure.php';
include 'lib/epayment/includes/html_output.php';
include './lib/epayment/includes/general.php';

if (!has_rights(ACX_ACCESS)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
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

$customer_info = $customer_res[0];

if ($customer_info[14] != "1" && $customer_info[14] != "8") {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$customer = $_SESSION["pr_login"];
//var_dump($_SESSION);
getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid','subscribe'));

$currencies_list = get_currencies();

$two_currency = false;
if (!isset ($currencies_list[strtoupper($customer_info[22])][2]) || !is_numeric($currencies_list[strtoupper($customer_info[22])][2])) {
    $mycur = 1;
} else {
    $mycur = $currencies_list[strtoupper($customer_info[22])][2];
    $display_currency = strtoupper($customer_info[22]);
    if (strtoupper($customer_info[22]) != strtoupper(BASE_CURRENCY))
        $two_currency = true;
}

$credit_cur = $customer_info[1] / $mycur;
$credit_cur = round($credit_cur, 3);
$useralias = $customer_info['useralias'];
$creation_date = $customer_info['creationdate'];
$username = $customer_info['username'];

$smarty->display('main.tpl');

if($_POST['submit'])
{
	
	$lastname  = $_POST['lastname'];
	$firstname = $_POST['firstname'];
	$city      = $_POST['city'];
	$email     = $_POST['email'];
	$country   = $_POST['country'];
	$state     = $_POST['state'];
	$address   = $_POST['address'];
	$zipcode   = $_POST['zipcode'];
	$phone     = $_POST['phone'];
	$fax       = $_POST['fax'];
	
											
	
	 
	$clause = "id = '".$_SESSION["card_id"]."' ";
    $instance_table = new Table("cc_card","*");
	$values="lastname='$lastname', firstname='$firstname', city='$city', country='$country',address='$address',state='$state',zipcode='$zipcode',phone='$phone',fax='$fax',email='$email'";
	$return=$instance_table -> Update_table($DBHandle, $values, $clause);
	
}

$QUERY = "SELECT  username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status, id, tariff FROM cc_card WHERE username = '" . $_SESSION["pr_login"] . "' AND uipass = '" . $_SESSION["pr_password"] . "'";

$numrow = 0;
$resmax = $DBHandle->Execute($QUERY);
if ($resmax)
$numrow = $resmax->RecordCount();

if($numrow==0)
	exit();
    $customer_info =$resmax->fetchRow();
	//print_r($customer_info);
	
if ($customer_info[14] != "1" && $customer_info[14] != "8") {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}
//FOR COUNTRY NAME
$country_table = new Table('cc_country','countryname,countrycode');
$country_clause = null;
$country_result = $country_table -> Get_list($DBHandle, $country_clause, 0);

?>
<style type="text/css">
img.baba{
border-radius: 100px;
    background-repeat: repeat;
    width: 125px;
    height: 125px;
}	
.error_message{
	color:green;
}
</style>
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Edit Customer Information </h3>
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
                  <!-- <div class="md-card">
                        <div class="widget-box">
                            <table style="width:100%">
							 <tr style="background-color:#DC7633">
							    <td>
								    <div class="user_heading_avatar"> -->
										<!-- <div class="thumbnail"> -->
										<!-- <div>
										<a href="cc_displayimage.php" title="Upload Picture">	<img src="templates/default/images/dp/<?php echo CUSTOMERDP; ?>" alt="user avatar" class="baba" ></a>
										
										</div>
									</div>
									<div class="user_heading_content">
										<h4 style="color:white;margin:10px;">
										<?php echo strtoupper($customer_info[3]); ?></h4>								
									</div>
								     
								</td>
							    <td style="width:25%">
								   <table >
									  <tr>
									    <td style="background-color:#DC7633"><font color="white"><?php echo gettext("BALANCE - REMAINING");?> :</font></td>
									    <td style="background-color:#DC7633"><font color="white"><?php echo $credit_cur.' '.$customer_info[22]; ?></font></td>
									  </tr>
									  <tr>
									    <td style="background-color:#DC7633"><font color="white"><?php echo gettext("ACCOUNT NUMBER");?> :</font></td>
									    <td style="background-color:#DC7633"><font color="white"><?php echo $customer_info[0]; ?></font></td>
									  </tr>
								  </table>
								</tr>
								
								</td>
							
							
							
							</table>
                        </div><br/>
                        <div class="user_content">
                            </div> -->
						<?php
																if($return==true){
	
																	echo $update_msg ="<div class=\"uk-alert uk-alert-success\" style=\"width:450px;height:25px; margin-left: 250px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">Your personal information has successfully been updated.</font></b></div>";
	
																}
						?>
  
                        

<!-- begin:: Content1 -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<!--begin::Section-->
    <div class="col-xl-12">
        <!--begin:: Widgets/Applications/User/Profile3-->
        <div class="kt-portlet kt-portlet--height-fluid"  style="background-color: #ffffff;">
            <div class="kt-portlet__body">
                <div class="kt-widget kt-widget--user-profile-3">
                    <div class="kt-widget__top">
                        <div class="col-lg-9 col-xl-6">
                            <div class="kt-avatar kt-avatar--outline kt-avatar--circle" id="kt_apps_user_add_avatar">
                                <div class="kt-avatar__holder">
                                    <a href="cc_displayimage.php">
                                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Edit Picture">
                                        <i class="fa fa-pen"></i>
                                    </label>
                                    <img src="templates/default/images/dp/<?php echo CUSTOMERDP; ?>" alt="user avatar" class="baba" >
                                    </a>
                                </div>
                            </div>
                        </div>
                            
                        <div class="col-lg-6 col-xl-6">
                            <div class="kt-widget__item">
                                <div class="kt-widget__details"><br><br>
                                    <i class="flaticon-piggy-bank"></i>
                                        <span class="kt-widget__title"><b><?php echo gettext("BALANCE REMAINING");?> :</b>&nbsp;</span>
                                            <font class="kt-widget__value"><?php echo $credit_cur.' '.$customer_info[22]; ?></font>
                                </div>
                                
                                <div class="kt-widget__details">
                                    <i class="flaticon-user-ok"></i>
                                        <span class="kt-widget__title"><b><?php echo gettext("ACCOUNT NUMBER");?> :</b>&nbsp;</span>
                                            <font class="kt-widget__value"><?php echo $customer_info[0]; ?></font>
                                </div>
                            </div>
                        </div>
                        <div class="user_content">
						    <?php
								if($return==true){
	                                echo $update_msg ="<div class=\"uk-alert uk-alert-success\" style=\"width:450px;height:25px; margin-left: 250px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">Your personal information has successfully been updated.</font></b></div>";
	                            }
						    ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--ends:: Widgets/Applications/User/Profile3-->
		
		
		
    
    <div class="col-md-12">
        <div class="kt-portlet">
		    <form action="billing_entity_edit.php?message=success" id="myForm" method="post" class="form-horizontal">
		        <div class="kt-portlet__body">
		            <div class="kt-portlet__head">
			            <div class="kt-portlet__head-label">
                            <font class="kt-portlet__head-title"><?php echo gettext("Edit Customer Information"); ?></font>
			            </div>
		            </div>
		            <div class="form-group row">
                        <div class="col-lg-1"></div>
					        <label class="col-lg-2 col-sm-12">First Name  </label>
                                <div class="col-lg-6 col-md-9 col-sm-12">
						            <input type="text" class="form-control" name="firstname" maxlength="50" placeholder="Enter First Name here" value="<?php echo $customer_info[3];?>" />
                                    <span class="form-text text-muted">Please enter your first name here</span>
					            </div>    
                        </div>
				    <div class="form-group row">
                        <div class="col-lg-1"></div>
					        <label class="col-lg-2 col-sm-12">E-mail  </label>
                                <div class="col-lg-6 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>	
						                    <input type="text" class="form-control" placeholder="Enter E-mail here" name="email" maxlength="50" value="<?php echo $customer_info[10];?>" />
					                </div> 
                                </div>    
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-1"></div>
					        <label class="col-lg-2 col-sm-12">Phone  </label>
                                <div class="col-lg-6 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
						                <input type="number" class="form-control" placeholder="Enter Phone Number here" name="phone" maxlength="50" value="<?php echo $customer_info[9];?>"/>
						            </div>
			                    </div>    
                    </div>
				
				<div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Address  </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						 
						  <input type="text" class="form-control" placeholder="Enter Address here" name="address" maxlength="50" value="<?php echo $customer_info[4];?>"/>
						  <span class="form-text text-muted">Please enter your address here</span>
						
			           
                    </div>    
                </div>
				
				<div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Country  </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						 
						   <select class="form-control13" id="country" tabindex="" name="country">
                  <?php     
					for($i=0;$i<count($country_result);$i++)
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
					<label class="col-lg-2 col-sm-12">States  </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						 
						  <input type="text" class="form-control" placeholder="Enter State Name here" name="state" maxlength="50" value="<?php echo $customer_info[6];?>" />
                          <span class="form-text text-muted">Please enter your state name here</span>
                    </div>    
                </div>
		  
		 <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">City  </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						 
						 <input type="text" class="form-control" placeholder="Enter City Name here" name="city" maxlength="50" value="<?php echo $customer_info[5];?>"/>
                         <span class="form-text text-muted">Please enter your city name here.</span>
                    </div>    
                </div>
		
				   <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Fax  </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
					    <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text"><i class="la la-fax"></i></span></div>	 
						 <input type="number" class="form-control" placeholder="Enter Fax Number here" name="fax"  maxlength="50" value="<?php echo $customer_info[11];?>" />
                        </div>
                    </div>    
                </div>

                <div class="form-group row">
                <div class="col-lg-1"></div>
					<label class="col-lg-2 col-sm-12">Zipcode  </label>
                    <div class="col-lg-6 col-md-9 col-sm-12">
						  <input type="number" class="form-control" placeholder="Enter ZipCode here" name="zipcode" maxlength="50" value="<?php echo $customer_info[8];?>"/>
                          <span class="form-text text-muted">Please enter your Zip Code here.</span>
						
			           
                    </div>    
                </div>
		  
		  
             	</div>
			  
			 
			   <div align="center" class="kt-portlet__foot">
                <div class="form-actions">
                     <input type="submit" name="submit" class="btn btn-brand" value="Edit Profile" >
                </div>
            </div>
			 
			 
		 
			 
			   
			
		
			
          </form>
       </div>
                    </div> 
					</div> 
					</div>
					</div>

<?php
$smarty->display('footer.tpl');
