<?php

include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'lib/customer.smarty.php';

if (!has_rights (ACX_PASSWORD)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

/***********************************************************************************/

getpost_ifset(array('NewPassword','OldPassword'));

$DBHandle  = DbConnect();

if ($form_action=="ask-modif") {
    $instance_sub_table = new Table('cc_card',"id");
    $check_old_pwd = "id = '".$_SESSION["card_id"]."' AND uipass = '$OldPassword'";
    $result_check=$instance_sub_table -> Get_list ($DBHandle,$check_old_pwd);
    if (is_array($result_check)) {
        $QUERY = "UPDATE cc_card SET  uipass= '".$NewPassword."' WHERE ( ID = ".$_SESSION["card_id"]." ) ";
        $result = $instance_sub_table -> SQLExec ($DBHandle, $QUERY, 0);
        // update Session password
        $_SESSION["pr_password"] = $NewPassword;
    }
}
// #### HEADER SECTION
$smarty->display( 'main.tpl');


// #### HELP SECTION
echo $CC_help_password_change."<br>";

?>
<script language="JavaScript">
function CheckPassword()
{
    if (document.frmPass.NewPassword.value =='') {
        alert('<?php echo gettext("No value in New Password entered")?>');
        document.frmPass.NewPassword.focus();

        return false;
    }
    if (document.frmPass.CNewPassword.value =='') {
        alert('<?php echo gettext("No Value in Confirm New Password entered")?>');
        document.frmPass.CNewPassword.focus();

        return false;
    }
    if (document.frmPass.NewPassword.value.length < 5) {
        alert('<?php echo gettext("Password length should be greater than or equal to 5 and less than 21")?>');
        document.frmPass.NewPassword.focus();

        return false;
    }
    if (document.frmPass.CNewPassword.value != document.frmPass.NewPassword.value) {
        alert('<?php echo gettext("Value mismatch, New Password should be equal to Confirm New Password")?>');
        document.frmPass.NewPassword.focus();

        return false;
    }

    return true;
}
</script>









<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content" >
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader" style="padding-left: 0px;">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> User </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                          Change Password                   </a>
                                  
                </div>
        </div>
    </div>
    
            </div>
        

<!-- end:: Subheader -->					
					<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<div class="row">
    <div class="kt-portlet">
       <div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h1 class="kt-portlet__head-title">
					<?php echo gettext("Change Password");?>
				</h1>
			</div>
		</div>
		
		 
	 
 



 
    <div class="col-lg-12">


<!-- start my code -->
      
          
		  <center>
		  <?php
			if ($form_action=="ask-modif") {
				if (is_array($result_check)) {
				   // echo '<font color="green">'.gettext("Your password is updated successfully.").'</font><br>';
					echo "<div class=\"uk-alert uk-alert-success\" style=\"width:350px;height:25px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">Your password is updated successfully.</font></b></div>";
				} else {
					//echo '<font color="red">'.gettext("Your old password is wrong.").'</font><br>';
					echo "<div class=\"uk-alert uk-alert-danger\" style=\"width:350px;height:25px;\" data-uk-alert=''> <a href='' class=\"uk-alert-close uk-close\"></a><b><font class=\"error_message\">Your old password is wrong.</font></b></div>";
				}
			}
			?>
		  </center>
      
        <div class="widget-content nopadding">
          <form method="post" action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-modif"?>" name="frmPass" class="form-horizontal">
			<div class="">
             <p class="" color="#000000" style="text-align:center;"><?php echo gettext("Do not use \" or = characters in your password");?></p>
            </div>
            <div class="control-group" style="margin-left:10%">
              <label class="control-label"><?php echo gettext("Old Password")?> :</label>
              <div class="">
                <input type="password" maxlength="20" name="OldPassword" class="form-control13" placeholder="Old Password" />
              </div>
            </div>
            <div class="control-group" style="margin-left:10%">
              <label class="control-label"><?php echo gettext("New Password")?> :</label>
              <div class="">
                <input type="password" maxlength="20" name="NewPassword" class="form-control13" placeholder="New Password" />
              </div>
            </div>
            <div class="control-group" style="margin-left:10%">
              <label class="control-label"><?php echo gettext("Confirm Password")?></label>
              <div class="">
                <input type="password" maxlength="20" name="CNewPassword"  class="form-control13" placeholder="Confirm New Password"  />
              </div>
            </div>
            <div class="control-group" style="text-align:center">
              <input type="submit" name="submitPassword" value="&nbsp;<?php echo gettext("Save")?>&nbsp;" class="btn btn-brand" onclick="return CheckPassword();" >&nbsp;&nbsp;<input type="reset" name="resetPassword" value="&nbsp;Reset&nbsp;" class="btn btn-secondary">
            </div>
			<script language="JavaScript">

document.frmPass.NewPassword.focus();

</script>
          </form>
      
<!-- end my code -->

</div>
</div>
</div>
</div>
</div>
<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');
