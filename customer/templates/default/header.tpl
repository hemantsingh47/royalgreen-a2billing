<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HEAD>
	<link rel="shortcut icon" href="images/ico/billing-icon-32x32.ico">
	<title>..:: {$CCMAINTITLE} ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!--<link href="templates/{$SKIN_NAME}/css/main.css" rel="stylesheet" type="text/css">-->
	<!--[if lt IE 7]>
		<link rel="stylesheet" type="text/css" href="templates/{$SKIN_NAME}/css/style-ie.css" />
	<![endif]-->
	<!--<link href="templates/{$SKIN_NAME}/css/menu.css" rel="stylesheet" type="text/css">
	<link href="templates/{$SKIN_NAME}/css/style-def.css" rel="stylesheet" type="text/css">
	<link href="./javascript/jquery/osx.css" rel="stylesheet" type="text/css">
	{if ($popupwindow != 0)}
		<link href="templates/{$SKIN_NAME}/css/popup.css" rel="stylesheet" type="text/css">
 	{/if}-->
	<script type="text/javascript">	
		var IMAGE_PATH = "templates/{$SKIN_NAME}/images/";
	</script>
	<!--<script type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"></script>
	<script type="text/javascript" src="./javascript/jquery/jquery.debug.js"></script>
	<script type="text/javascript" src="./javascript/jquery/ilogger.js"></script>
	<script type="text/javascript" src="./javascript/jquery/handler_jquery.js"></script>
	<script type="text/javascript" src="./javascript/misc.js"></script>
	<script type="text/javascript" src="./javascript/jquery/jquery.simplemodal.js"></script>
	<script type="text/javascript" src="./javascript/jquery/osx.js"></script>-->
	
	<!-- new theme code-->
	
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

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/css/demo12/matrix-style.css" rel="stylesheet" type="text/css" />

		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<!--begin:: Global Mandatory Vendors -->
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/tether/dist/css/tether.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/nouislider/distribute/nouislider.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/summernote/dist/summernote.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="templates/{$SKIN_NAME}/newtheme/theme/classic/assets/css/demo12/style.bundle.css" rel="stylesheet" type="text/css" />

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