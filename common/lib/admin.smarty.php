<?php


// use Factory\SmartyFactory;


error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

define( 'FULL_PATH', dirname(__FILE__) . '/' );
define( 'SMARTY_DIR', FULL_PATH . '../../vendor/smarty/smarty/libs/' );
define( 'TEMPLATE_DIR',  '../Public/templates/' );
define( 'TEMPLATE_C_DIR', '../templates_c/' );

// $smarty = SmartyFactory::getInstance();
#Remove the factory, for some reasons it doesnt work on PHP 5.3 / CentOs 6

require_once SMARTY_DIR . 'SmartyBC.class.php';
$smarty = new SmartyBC();

$skin_name = $_SESSION["stylefile"];

$smarty->template_dir = TEMPLATE_DIR . $skin_name.'/';

$smarty->compile_dir = TEMPLATE_C_DIR;
$smarty->plugins_dir= "./plugins/";

$smarty->assign("TEXTCONTACT", TEXTCONTACT);
$smarty->assign("EMAILCONTACT", EMAILCONTACT);
$smarty->assign("COPYRIGHT", COPYRIGHT);
$smarty->assign("CCMAINTITLE", CCMAINTITLE);

$smarty->assign("SKIN_NAME", $skin_name);
// if it is a pop window
if (!is_numeric($popup_select)) {
    $popup_select=0;
}
$smarty->assign("popupwindow", $popup_select);

if (!empty($msg)) {
    switch ($msg) {
        case "nodemo": 	$smarty->assign("MAIN_MSG", '<center><b><font color="red">'.gettext("This option is not available on the Demo!").'</font></b></center><br>');
    }
}

$smarty->assign("ACXACCESS", $ACXACCESS);
$smarty->assign("ACXDASHBOARD", $ACXDASHBOARD);
$smarty->assign("ACXCUSTOMER", $ACXCUSTOMER);
$smarty->assign("ACXBILLING", $ACXBILLING);
$smarty->assign("ACXRATECARD", $ACXRATECARD);
$smarty->assign("ACXTRUNK", $ACXTRUNK);
$smarty->assign("ACXDID", $ACXDID);
$smarty->assign("ACXMAIL", $ACXMAIL);
$smarty->assign("ACXCALLREPORT", $ACXCALLREPORT);
$smarty->assign("ACXCRONTSERVICE", $ACXCRONTSERVICE);
$smarty->assign("ACXMISC", $ACXMISC);
$smarty->assign("ACXADMINISTRATOR", $ACXADMINISTRATOR);
$smarty->assign("ACXMAINTENANCE", $ACXMAINTENANCE);
$smarty->assign("ACXSUPPORT", $ACXSUPPORT);
$smarty->assign("ACXCALLBACK", $ACXCALLBACK);
$smarty->assign("ACXOUTBOUNDCID", $ACXOUTBOUNDCID);
$smarty->assign("ACXPACKAGEOFFER", $ACXPACKAGEOFFER);
$smarty->assign("ACXPREDICTIVEDIALER", $ACXPREDICTIVEDIALER);
$smarty->assign("ACXINVOICING", $ACXINVOICING);
$smarty->assign("ACXSETTING", $ACXSETTING);
$smarty->assign("NEW_NOTIFICATION", $NEW_NOTIFICATION);

$smarty->assign("HTTP_HOST", $_SERVER['HTTP_HOST']);
$smarty->assign("ASTERISK_GUI_LINK", ASTERISK_GUI_LINK);

$smarty->assign("LCMODAL", LCMODAL);

$section = $_SESSION["menu_section"];

$smarty->assign("section", $section);

$smarty->assign("adminname", $_SESSION["pr_login"]);

// OPTION FOR THE MENU
$smarty->assign("A2Bconfig", $A2B->config);

$smarty->assign("PAGE_SELF", $PHP_SELF);
 //for admin logo
$image_table = new Table('cc_ui_authen','logopath');
$imaget_clause = "userid='".$_SESSION['admin_id']."' LIMIT 1";
$imageresult = $image_table -> Get_list($DBHandle, $imaget_clause, 0);
define("IMGNAME",ADMINIMGPATH.$imageresult[0]['logopath']);