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
	$values="lastname='$lastname', firstname='$firstname', city='$city', country='$country',address='$address',state='$state',zipcode='$zipcode',phone='$phone',fax='$fax'";
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
<h3 class="heading_b uk-margin-bottom">EDIT INFORMATION</h3>
 <div class="user_content">
						<?php
																if($return==true){
	
																	echo $update_msg ="<div class=\"uk-alert uk-alert-success\" style=\"width:450px;height:25px; margin-left: 250px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">Your personal information has  been updated successfully .</font></b></div>";
	
																}
						?>
  
                        </div>

<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Edit Information</h5>
        </div>
        <div class="widget-content nopadding">
		
          <form id="myForm" action="billing_edit.php?message=success" method="post" class="form-horizontal" name="myForm">
            <div class="control-group">
              <label class="control-label">First Name :</label>
              <div class="controls">
                <input type="text" name="firstname" size="30" maxlength="50" class="span3" placeholder="First name" value="<?php echo $customer_info[3];?>" />
				
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Last Name :</label>
              <div class="controls">
                <input type="text" name="lastname" size="30" maxlength="50" class="span3" placeholder="Last name" value="<?php echo $customer_info[2];?>"/>
              </div>
            </div>
			<div class="control-group">
              <label class="control-label">Email :</label>
              <div class="controls">
                <input type="text" class="span3" placeholder="Email" name="email" maxlength="50" value="<?php echo $customer_info[10];?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Address :</label>
              <div class="controls">
                <input type="text" name="address" size="30" maxlength="50" class="span3" placeholder="Address" value="<?php echo $customer_info[4];?>"/>  
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">City :</label>
              <div class="controls">
                <input type="text" name="city" size="30" maxlength="50"  class="span3" placeholder="City" value="<?php echo $customer_info[5];?>"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">State/Province :</label>
              <div class="controls">
                <input type="text" name="state" size="30" maxlength="50" class="span3" value="<?php echo $customer_info[6];?>"/>
              </div>  
            </div>
           <div class="control-group">
              <label class="control-label">Country :</label>
              <div class="controls">
			  <select id="country" class="md-input" tabindex="" name="country">
                                     
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
			<div class="control-group">
              <label class="control-label">Zip/Postal Code :</label>
              <div class="controls">
                <input type="text" name="zipcode" size="30" maxlength="50" class="span3" value="<?php echo $customer_info[8];?>"/>
              </div>  
            </div>
			<div class="control-group">
              <label class="control-label">Phone Number :</label>
              <div class="controls">
                <input type="text" name="phone" size="30" maxlength="50"  class="span3" value="<?php echo $customer_info[9];?>"/>
              </div>  
            </div>
			<div class="control-group">
              <label class="control-label">Fax :</label>
              <div class="controls">
                <input type="text" name="fax" size="30" maxlength="50" class="span3" value="<?php echo $customer_info[11];?>"/>
              </div>  
            </div>
            <div class="form-actions">
			<input type="submit" name="submit" value="Edit Profile" class="btn btn-success">
              <!--<input  name="submit" type="submit" class="btn btn-success">Edit Profile</button>-->
            </div>
          </form>
        </div>
      </div>
 
 
 
 
 
 
 
 
 
 
<?php
$smarty->display( 'footer.tpl');
