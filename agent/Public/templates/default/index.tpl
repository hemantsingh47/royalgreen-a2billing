<HTML>
<HEAD>
	<link rel="shortcut icon" href="images/ico/billing-icon-32x32.ico">
	<meta charset="utf-8" />
	<title>..:: {$CCMAINTITLE} ::..</title>
	<meta name="description" content="Login page">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!--begin::Fonts -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>

		<!--end::Fonts -->
		<!--begin::Page Custom Styles(used by this page) -->
		<link href=" templates/default/newtheme/theme/classic/assets/css/demo1/pages/general/login/login-2.css" rel="stylesheet" type="text/css" />

		<!--end::Page Custom Styles -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="templates/default/newtheme/theme/angular/dist/skeleton/src/assets/vendors/global/vendors.bundle.css" rel="stylesheet" type="text/css" />
		<link href="templates/default/newtheme/theme/classic/assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->


		<!--begin::Layout Skins(used by all pages) -->
		<link href="templates/default/newtheme/theme/classic/assets/css/demo1/skins/header/base/light.css" rel="stylesheet" type="text/css" />
		<link href="templates/default/newtheme/theme/classic/assets/css/demo1/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="templates/default/newtheme/theme/classic/assets/css/demo1/skins/brand/dark.css" rel="stylesheet" type="text/css" />
		<link href="templates/default/newtheme/theme/classic/assets/css/demo1/skins/aside/dark.css" rel="stylesheet" type="text/css" />
		<!--end::Layout Skins -->

	</HEAD>

	<!-- end::Head -->

	
	<!-- deafault theme header
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		{if ($CSS_NAME!="" && $CSS_NAME!="default")}
			   <link href="templates/default/css/{$CSS_NAME}.css" rel="stylesheet" type="text/css">
		{else}
			   <link href="templates/default/css/main.css" rel="stylesheet" type="text/css">
			   <link href="templates/default/css/menu.css" rel="stylesheet" type="text/css">
			   <link href="templates/default/css/style-def.css" rel="stylesheet" type="text/css">
		{/if}
        <script type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"></script>
		
	-->
<style type="text/css">
	.kt-space-10 {
    display: block;
    height: 0;
    margin-bottom: 35px;
}
	
	
	
	</style>


<!--<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> -->

<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

{literal}
<script LANGUAGE="JavaScript">
<!--
	function test()
	{
		if(document.form.pr_login.value=="" || document.form.pr_password.value=="")
		{
			alert("You must enter an user and a password!");
			return false;
		}
		else
		{
			return true;
		}
	}
-->
</script>

{/literal}

<!-- new theme login page css -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background: url(templates/default/newtheme/theme/classic/assets/media//bg/bg-1.jpg) no-repeat center #66C8EB;">
					<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper" style="padding: 12% 2rem 1rem 2rem;">
						<div class="kt-login__container" style="background: #2E363F;width: 470px;margin-left: auto;margin-right: auto;">
							<div class="kt-login__logo" style="    margin: 0 auto 1rem auto;">
								<a href="#">
									<img src="templates/default/newtheme/theme/classic/assets/media/logos/retail-billing-logo.png">
								</a>
							</div>
							<div class="kt-login__signin">
								<div class="kt-login__head">
									<h3 class="kt-login__title">Sign In To Agent</h3>
								</div>
								<div style="color:#ffff00;font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:bold;text-align:center;padding-top:0px" >
      								{if ($error == 1)}
            							{php} echo gettext("AUTHENTICATION REFUSED, please check your user/password!");{/php}
    								{elseif ($error==2)}
            							{php} echo gettext("INACTIVE ACCOUNT, Please activate your account!");{/php}
    								{elseif ($error==3)}
            							{php} echo gettext("BLOCKED ACCOUNT, Please contact the administrator!");{/php}
    								{/if}
								<form name="form" method="POST" action="dashboard.php" onSubmit="return test()" class="kt-form"  onSubmit="return test()" style="margin: 1rem auto;">
								<input type="hidden" name="done" value="submit_log">
									<div class="input-group" style="width: 70%;">
										
										<div class="input-group date">
						<div class="input-group-append">
						<span class="input-group-text" style="background: #28b779;">
							<i class="la la-user" style="color:#ffffff;"></i>
							</span>
						</div><input class="form-control" type="text" id="login_username" name="pr_login"  placeholder="Username" style="color: #444444;height: 31px;border-radius: 0;border: none;padding-left: 1.5rem;padding-right: 1.5rem;margin: 0;background: #ffffff;
">
						
					</div>
										
										
									 
										
									</div>
									<div class="kt-space-10"></div>
									
									<div class="input-group" style="width: 70%;">
									 
										
										<div class="input-group-append">
						<span class="input-group-text" style="background: #ffb848;">
							<i class="la la-lock" style="color: #ffffff;"></i>
							</span>
						</div><input class="form-control" input type="password" id="login_password" name="pr_password" placeholder="Password" style="color: #444444; height: 31px;border-radius: 0;border: none;padding-left: 1.5rem;padding-right: 1.5rem;margin: 0;background: #ffffff;
">
						
					</div>
										
									</div>
									<div class="row kt-login__extra">
										<div class="col" style="text-align: center;">
											<label class="kt-checkbox" style="color: #fff;">
												<input type="checkbox" name="remember"> Remember me
												<span></span>
											</label>
										</div>
										</div>
										<hr style="border-top: 1px solid #3f4954;" />
										
										<div class="row kt-login__extra">
										<div class="col" style="margin: 10px;">
											<!--<a href="javascript:;" id="kt_login_forgot" class="kt-link kt-login__link">Forget Password ?</a> -->
										<select id="ui_language" name="ui_language" class="flip-link btn btn-info" style="    width: 220px;
    height: 30px;
    padding: 0;
    background: #2f96b4;
    border: 0;">
                                        <option  value="english" name="gb" {php} if(LANGUAGE=="english") echo "selected";{/php} >{php}echo gettext("English"){/php}</option>
                                        <option  value="french" name="fr" {php} if(LANGUAGE=="french") echo "selected";{/php} >{php}echo gettext("French"){/php}</option>
                                        <option  value="romanian" name="ro" {php} if(LANGUAGE=="romanian") echo "selected";{/php} >{php}echo gettext("Romanian"){/php}</option>
                                        <option  value="greek" name="gr" {php} if(LANGUAGE=="greek") echo "selected";{/php} >{php}echo gettext("Greek"){/php}</option>
                                        <option  value="brazilian" name="br" {php} if(LANGUAGE=="brazilian") echo "selected";{/php}>{php}echo gettext("Brazilian"){/php}</option>
                                    </select>
										</div>
									 
									 
									 <div class="col" style="margin: 10px;
    text-align: right;
   ">
										<button id="kt_login_signin_submit" type="submit" class="btn btn-success" style="background: #5bb75b;
    border: 0;
    padding: 4px 12px;
    border-radius: 0;
    height: 30px;
}">Sign In</button>
									</div>
									 
									 
								</form>
								</div>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>

<!-- new theme login css end-->




<!--default theme login -->

	<!--
	<form name="form" method="POST" action="dashboard.php" onsubmit="return test()">
	<input type="hidden" name="done" value="submit_log">


	<div id="login-wrapper" class="login-border-up">
	<div class="login-border-down">
	<div class="login-border-center">
	<center>
	<table border="0" cellpadding="3" cellspacing="12">
	<tr>
		<td class="login-title" colspan="2">
			 {php} echo gettext("AUTHENTICATION");{/php}
		</td>
	</tr>
	<tr>
		<td ><img src="templates/{$SKIN_NAME}/images/kicons/lock_bg.png"></td>
		<td align="center" style="padding-right: 10px">
			<table width="90%">
			<tr align="center">
				<td align="left"><font size="2" face="Arial, Helvetica, Sans-Serif"><b>{php} echo gettext("User");{/php}:</b></font></td>
				<td><input class="form_input_text" type="text" name="pr_login" size="15"></td>
			</tr>
			<tr align="center">
				<td align="left"><font face="Arial, Helvetica, Sans-Serif" size="2"><b>{php} echo gettext("Password");{/php}:</b></font></td>
				<td><input class="form_input_text" type="password" name="pr_password" size="15"></td>
			</tr>
            <tr >
                <td colspan="2"> &nbsp;</td>
            </tr>
			<tr align="right" >
            <td>
                <select name="ui_language"  id="ui_language" class="icon-menu form_input_select">
                    <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/gb.gif);" value="english" {php} if(LANGUAGE=="english") echo "selected";{/php} >English</option>
                    <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/br.gif);" value="brazilian" {php} if(LANGUAGE=="brazilian") echo "selected";{/php}>Brazilian</option>
                    <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/ro.gif);" value="romanian" {php} if(LANGUAGE=="romanian") echo "selected";{/php} >Romanian</option>
                    <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/fr.gif);" value="french" {php} if(LANGUAGE=="french") echo "selected";{/php} >French</option>
                    <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/gr.gif);" value="greek" {php} if(LANGUAGE=="greek") echo "selected";{/php} >Greek</option>
                </select>
            </td>
			<td><input type="submit" name="submit" value="{php} echo gettext("LOGIN");{/php}" class="form_input_button"></td>
			</tr>

			</table>
		</td>
	</tr>
  	</table>
  	</center>
  	</div>
  	</div>
  -->
  <!-- default theme login -->
  	<!-- <div style="color:#BC2222;font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:bold;padding-left:10px;" >
  	{if ($error == 1)}
			{php} echo gettext("AUTHENTICATION REFUSED, please check your user/password!");{/php}
    {elseif ($error==2)}
			{php} echo gettext("INACTIVE ACCOUNT, Please activate your account!");{/php}
    {elseif ($error==3)}
			{php} echo gettext("BLOCKED ACCOUNT, Please contact the administrator!");{/php}
    {/if}
    </div> -->

<!-- css for footer -->
  <!-- <div id="footer_index"><div style=" border: solid 1px #F4F4F4; text-align:center;">{$COPYRIGHT}</div></div>

  	</div>
	</form>

{literal}
<script LANGUAGE="JavaScript">
	document.form.pr_login.focus();
        $("#ui_language").change(function () {
          self.location.href= "index.php?ui_language="+$("#ui_language option:selected").val();
        });
</script>
{/literal}
-->

{literal}

<script LANGUAGE="JavaScript">
	  
    document.form.pr_login.focus();
        $("[name='ui_language']").change(function () {
         
          self.location.href= "index.php?ui_language="+$("#ui_language option:selected").val();
        });  
</script>
{/literal}