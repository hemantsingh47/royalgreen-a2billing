<?php
/* Smarty version 3.1.33, created on 2019-09-23 10:09:35
  from '/var/www/html/crm/agent/Public/templates/default/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d8899dfdcd5f3_95503136',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b4655794532ce93ffd5874de95f2e66f0278cecc' => 
    array (
      0 => '/var/www/html/crm/agent/Public/templates/default/header.tpl',
      1 => 1563263292,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d8899dfdcd5f3_95503136 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HEAD>
	<link rel="shortcut icon" href="images/ico/billing-icon-32x32.ico">
	<title>..:: <?php echo $_smarty_tpl->tpl_vars['CCMAINTITLE']->value;?>
 ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!--<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/css/main.css" rel="stylesheet" type="text/css">-->
	<!--[if lt IE 7]>
		<link rel="stylesheet" type="text/css" href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/css/style-ie.css" />
	<![endif]-->
	<!--<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/css/menu.css" rel="stylesheet" type="text/css">
	<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/css/style-def.css" rel="stylesheet" type="text/css">
	<link href="./javascript/jquery/osx.css" rel="stylesheet" type="text/css">
	<?php if (($_smarty_tpl->tpl_vars['popupwindow']->value != 0)) {?>
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/css/popup.css" rel="stylesheet" type="text/css">
 	<?php }?>-->
	<?php echo '<script'; ?>
 type="text/javascript">	
		var IMAGE_PATH = "templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/images/";
	<?php echo '</script'; ?>
>
	<!--<?php echo '<script'; ?>
 type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="./javascript/jquery/jquery.debug.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="./javascript/jquery/ilogger.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="./javascript/jquery/handler_jquery.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="./javascript/misc.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="./javascript/jquery/jquery.simplemodal.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="./javascript/jquery/osx.js"><?php echo '</script'; ?>
>-->
	
	<!-- new theme code-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		<?php echo '</script'; ?>
>

		<!--end::Fonts -->


		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/css/demo12/matrix-style.css" rel="stylesheet" type="text/css" />



		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<!--begin:: Global Mandatory Vendors -->
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/tether/dist/css/tether.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/nouislider/distribute/nouislider.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/summernote/dist/summernote.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/classic/assets/css/demo12/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="./assets/media/logos/favicon.ico" />

	
	<!--new theme code end-->
	
	
</HEAD>
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">

<!--
<div id="page-wrap">
	<div id="inside">-->




<?php }
}
