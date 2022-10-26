<?php

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('NewPassword','OldPassword'));

$DBHandle  = DbConnect();

if ($form_action=="ask-modif") {

    check_demo_mode();

    $instance_sub_table = new Table('cc_agent',"id");
    $check_old_pwd = "id = '".$_SESSION["agent_id"]."' AND passwd = '$OldPassword'";
    $result_check=$instance_sub_table -> Get_list ($DBHandle,$check_old_pwd);
    if (is_array($result_check)) {
        $QUERY = "UPDATE cc_agent SET passwd= '".$NewPassword."' WHERE ( ID = ".$_SESSION["agent_id"]."  ) ";
        $result = $instance_sub_table -> SQLExec ($DBHandle, $QUERY, 0);
    }
}

// #### HEADER SECTION
$smarty->display( 'main.tpl');

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
        alert('<?php echo gettext("Password length should be greater than or equal to 5")?>');
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

<?php
if ($form_action=="ask-modif") {

if (is_array($result_check)) {

?>
    <script language="JavaScript">
    alert("<?php echo gettext("Your password is updated successfully.")?>");
    </script>
<?php
} else {
?>
    <script language="JavaScript">
    alert("<?php echo gettext("Your old password is wrong.")?>");
    </script>

<?php
} }
?>
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Change Password </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Home                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Change Password                        </a>
                </div>
            </span>
        </div>
    </div>
</div>
<!-- end:: Subheader -->


<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="col-md-10">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Change Password
					</h3>
				</div>
			</div>
          <!--  <center>
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
		  </center> -->
			<!--begin::Form-->
			<form method="post" class="kt-form" action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-modif"?>" name="frmPass">
            <div align="center">&nbsp;<p class="liens"><?php echo gettext("Do not use \" or = characters in your password");?></p></div>
				<div class="kt-portlet__body">
					<div class="form-group row">
						<label class="col-lg-4 col-sm-12">Old Password </label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="password" maxlength="20" class="form-control" placeholder="Enter Old Password">
						    <span align="left" class="form-text text-muted">Please enter your old password here.</span>
                        </div>    
					</div>

                    <div class="form-group row">
						<label class="col-lg-4 col-sm-12">New Password </label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="password" maxlength="20" class="form-control" placeholder="Enter New Password">
						    <span align="left" class="form-text text-muted">Please enter your new password here.</span>
                        </div>    
					</div>
					
                    <div class="form-group row">
						<label class="col-lg-4 col-sm-12">Confirm Password </label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="password" maxlength="20" class="form-control" placeholder="Confirm New Password">
						    <span align="left" class="form-text text-muted">Please confirm your new password here.</span>
                        </div>    
					</div>
				</div>

                <div class="kt-portlet__foot">
                <div class="form-actions">
                    <input type="submit" name="submitPassword" value="&nbsp;<?php echo gettext("Save")?>&nbsp;" class="btn btn-brand" onclick="return CheckPassword();" >&nbsp;&nbsp;
                    <input type="reset" name="resetPassword" value="&nbsp;Reset&nbsp;" class="btn btn-secondary">
                </div>
                </div>
                <script language="JavaScript">

                        document.frmPass.NewPassword.focus();

                </script>
			</form>
			<!--end::Form-->			
		</div>
		<!--end::Portlet-->
	</div>
</div>
<!-- begin:: Content -->


<br><br><br>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');
