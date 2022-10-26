<?php

include './lib/customer.defines.php';
include './lib/customer.smarty.php';

if (!$A2B->config["signup"]['enable_signup']) {
    echo ("No Signup page!");
    exit;
}

if (!isset ($_SESSION["date_mail"]) || (time() - $_SESSION["date_mail"]) > 60) {
    $_SESSION["date_mail"] = time();
} else {
    sleep(3);
    echo gettext("Sorry the confirmation email has been sent already, multi-signup are not authorized! Please wait 2 minutes before making any other signup!");
    exit ();
}

if (!isset ($_SESSION["cardnumber_signup"]) || strlen($_SESSION["cardnumber_signup"]) <= 1) {
    echo gettext("Error : No User Created.");
    exit ();
}

$FG_DEBUG = 0;
$DBHandle = DbConnect();

$activatedbyuser = $A2B->config["signup"]['activatedbyuser'];

echo $lang_code = $_SESSION["language_code"];
if (!$activatedbyuser) {
    $mailtype = Mail :: $TYPE_SIGNUP;
} else {
    $mailtype = Mail :: $TYPE_SIGNUPCONFIRM;
}

try {
    $mail = new Mail($mailtype, $_SESSION["id_signup"], $_SESSION["language_code"]);
} catch (A2bMailException $e) {
    echo "<br>" . gettext("Error : No email Template Found");
    exit ();
}

$QUERY = "SELECT username, lastname, firstname, email, uipass, credit, useralias, loginkey FROM cc_card WHERE id=" . $_SESSION["id_signup"];

$res = $DBHandle->Execute($QUERY);
$num = 0;
if ($res)
    $num = $res->RecordCount();

if (!$num) {
    echo "<br>" . gettext("Error : No such user found in database");
    exit ();
}

for ($i = 0; $i < $num; $i++) {
    $list[] = $res->fetchRow();
}

if ($FG_DEBUG == 1)
    echo "</br><b>BELOW THE CARD PROPERTIES </b><hr></br>";

list ($username, $lastname, $firstname, $email, $uipass, $credit, $cardalias, $loginkey) = $list[0];
if ($FG_DEBUG == 1)
    echo "<br># $username, $lastname, $firstname, $email, $uipass, $credit, $cardalias #</br>";

try {
    $mail->send();
} catch (A2bMailException $e) {
    $error_msg = $e->getMessage();
}

$smarty->display('signup_header.tpl');
?>
<!-- <body class="login_page">

    <div class="login_page_wrapper" style="width:400px;padding-left:500px;">
        <div class="md-card" id="login_card" style="opacity: 1; transform: scale(1);">
             <div class="md-card-content large-padding" id="register_form" style="display: block;">
                
                <h2 class="heading_a uk-margin-medium-bottom">Signup Confirmation</h2>
                <blockquote>
						  <br/><br/>

					<?php if (!$activatedbyuser) 
						 { ?>
						<font color="#00AA00"><?php echo $list[0][2]; ?> <font color="#00AA00"><?php echo $list[0][1]; ?>, <?php echo gettext("thank you for registering with us!");?><br>
						<?php echo gettext("An activation email has been sent to"); ?> <b><?php echo $list[0][3]; ?></b><br><br>
						<?php }
						else { ?>
						  <?php echo $list[0][2]; ?> <?php echo $list[0][1]; ?>, <?php echo gettext("Thank you for registering with us !");?><br>
						  <?php echo gettext("An email confirming your information has been sent to"); ?> <b><?php echo $list[0][3]; ?></b><br><br>
							<h3>
							  <?php echo gettext("Your cardnumber is "); ?> <b><font color="#00AA00"><?php echo $list[0][0]; ?></font></b><br><br><br>
							  <?php echo gettext("To login to your account :"); ?><br>
							  <?php echo gettext("Your card alias (login) is "); ?> <b><font color="#00AA00"><?php echo $list[0][6]; ?></font></b><br>
							  <?php echo gettext("Your password is "); ?> <b><font color="#00AA00"><?php echo $list[0][4]; ?></font></b><br>
							</h3>
						  <?php }
						  ?>
				</blockquote>
            </div>
        </div>
     </div>
 </div> -->
<head>
<!--begin::Global Theme Styles(used by all pages) -->
<link href="templates/default/newtheme/theme/classic/assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />
<link href="templates/default/newtheme/theme/classic/assets/css/demo12/pages/general/login/login-2.css" rel="stylesheet" type="text/css" />
    </head>
    <body style=" background:#e1e1ef;">
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
                    <div class="kt-portlet__head-title" style="text-align:center;"><?php echo gettext("SIGNUP CONFIRMATION"); ?></div>
				</div>
			</div>
				<div class="kt-portlet__body">
					<div class="form-group row">

					<?php if (!$activatedbyuser) 
						 { ?>
						<font color="#00AA00"><?php echo $list[0][2]; ?> <font color="#00AA00"><?php echo $list[0][1]; ?>, <?php echo gettext("thank you for registering with us!");?><br>
						<?php echo gettext("An activation email has been sent to"); ?> <b><?php echo $list[0][3]; ?></b><br><br>
						<?php }
						else { ?>
						  <?php echo $list[0][2]; ?> <?php echo $list[0][1]; ?>, <?php echo gettext("Thank you for registering with us !");?><br>
						  <?php echo gettext("An email confirming your information has been sent to"); ?> <b><?php echo $list[0][3]; ?></b><br><br>
							<h3>
							  <?php echo gettext("Your cardnumber is "); ?> <b><font color="#00AA00"><?php echo $list[0][0]; ?></font></b><br><br><br>
							  <?php echo gettext("To login to your account :"); ?><br>
							  <?php echo gettext("Your card alias (login) is "); ?> <b><font color="#00AA00"><?php echo $list[0][6]; ?></font></b><br>
							  <?php echo gettext("Your password is "); ?> <b><font color="#00AA00"><?php echo $list[0][4]; ?></font></b><br>
							</h3>
						  <?php }
						  ?>
                    </div>
				</div>		
		</div>
    </div>
</div>




