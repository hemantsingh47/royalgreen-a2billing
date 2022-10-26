<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include '../lib/admin.smarty.php';

if (!$ACXACCESS) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array ('OldPassword','NewPassword'));

$DBHandle = DbConnect();

if ($form_action == "ask-modif") 
{
    check_demo_mode();

    $table_old_pwd = new Table("cc_ui_authen", " login");
    $OldPwd_encoded = hash('whirlpool', $OldPassword);
	$clause_old_pwd = "login = '" . $_SESSION["pr_login"] . "' AND pwd_encoded = '" . $OldPwd_encoded . "'";
    $result_old_pwd = $table_old_pwd->Get_list($DBHandle, $clause_old_pwd, null, null, null, null, null, null);

    if (!empty ($result_old_pwd)) 
	{
        $instance_sub_table = new Table('cc_ui_authen');
        $NewPwd_encoded = hash('whirlpool', $NewPassword);
        $QUERY = "UPDATE cc_ui_authen SET  pwd_encoded= '" . $NewPwd_encoded . "' WHERE ( login = '" . $_SESSION["pr_login"] . "' ) ";
		$result = $instance_sub_table->SQLExec($DBHandle, $QUERY, 0);
    }
	else 
	{
        $OldPasswordFaild = true;
    }
}

// #### HEADER SECTION
$smarty->display('main.tpl');
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

if($form_action == "ask-modif") 
{

  if(isset ($result)) 
  {
	?>
	<script language="JavaScript">
	alert("<?php echo gettext("Your password is updated successfully.")?>");
	</script>
	<?php
		} elseif (isset ($OldPasswordFaild)) {
	?>
	<script language="JavaScript">
	alert("<?php echo gettext("Wrong old password.")?>");
	</script>
	<?php
		} else {
	?>
	<script language="JavaScript">
	alert("<?php echo gettext("System is failed to update your password.")?>");
	</script>
	<?php
		}
	}
	?>
<br>
<style>
.kt-form {
	margin-left: 20%;
	margin-right: 20%;
}
.kt-portlet__head-label{
	margin-left: 20%;
}
</style>
<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Change Password
			</h3>
		</div>
	</div>
        <!--begin::Form-->
		<form method="post" class="kt-form" align="center"  margin-left:50% action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-modif"?>" name="frmPass">
			<div align="center">&nbsp;<p class="liens"><?php echo gettext("Do not use \" or = characters in your password");?></p></div>
			<div class="kt-portlet__body">
				
				<div class="kt-section kt-section--first">
					<div class="form-group">
						<label>Old Password:</label>
						<input type="password" maxlength="20" name="OldPassword" placeholder="Enter old password" class="form-control">
						<span class="form-text text-muted">Please enter your old password</span>
					</div>
					<div class="form-group">
						<label>New Password :</label>
						<input type="password" maxlength="20" name="NewPassword" class="form-control" placeholder="Enter new password">
						<span class="form-text text-muted">Please enter your new password</span>
					</div>
					<div class="form-group">
						<label>Confirm Password :</label>
						<input type="password" maxlength="20" name="CNewPassword" class="form-control" placeholder="Confirm password">
						<span class="form-text text-muted">Please confirm your entered password</span>
					</div>	
				</div>
			
         
            <div class="form-actions">
              <input type="submit" name="submitPassword" value="&nbsp;<?php echo gettext("Save")?>&nbsp;" class="btn btn-brand" onclick="return CheckPassword();" >&nbsp;&nbsp;<input type="reset" name="resetPassword" value="&nbsp;Reset&nbsp;" class="btn btn-secondary">
            </div>
			<script language="JavaScript">

document.frmPass.NewPassword.focus();

</script>
       </form>
     </div>
</div>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');
