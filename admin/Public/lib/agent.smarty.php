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

// for menu
$smarty->assign("ACXCUSTOMER", $ACXCUSTOMER);
$smarty->assign("ACXBILLING", $ACXBILLING);
$smarty->assign("ACXRATECARD", $ACXRATECARD);
$smarty->assign("ACXCALLREPORT", $ACXCALLREPORT);
$smarty->assign("ACXMYACCOUNT", $ACXMYACCOUNT);
$smarty->assign("ACXSUPPORT", $ACXSUPPORT);
$smarty->assign("ACXSIGNUP", $ACXSIGNUP);
$smarty->assign("ACXVOIPCONF", $ACXVOIPCONF);

$smarty->assign("LCMODAL", LCMODAL);

getpost_ifset(array('section'));

if (!empty($section)) {
    $_SESSION["menu_section"] = $section;
} else {
    $section = $_SESSION["menu_section"];
}
$smarty->assign("section", $section);

$smarty->assign("adminname", $_SESSION["pr_login"]);

// OPTION FOR THE MENU
$smarty->assign("A2Bconfig", $A2B->config);

$smarty->assign("PAGE_SELF", $PHP_SELF);


//for agent logo
$image_table = new Table('cc_agent','logopath');
$imaget_clause = "id='".$_SESSION['agent_id']."' LIMIT 1";

$imageresult = $image_table -> Get_list($DBHandle, $imaget_clause, 0);

if($imageresult[0]['logopath']==NULL)
{
  $aimage_table = new Table('cc_ui_authen','logopath');
  $aimageresult = $aimage_table -> Get_list($DBHandle, null, 0);  
  define("AGENTIMGNAME",AGENTIMGPATH.$aimageresult[0]['logopath']);  
}
else
{
    define("AGENTIMGNAME",AGENTIMGPATH.$imageresult[0]['logopath']);
}

