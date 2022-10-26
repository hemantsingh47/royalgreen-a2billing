<?php

// use Factory\SmartyFactory;

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

define( 'FULL_PATH', dirname(__FILE__) . '/' );
define( 'SMARTY_DIR', FULL_PATH . '../../vendor/smarty/smarty/libs/' );
define( 'TEMPLATE_DIR',  './templates/' );
define( 'TEMPLATE_C_DIR', './templates_c/' );

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
$smarty->assign("SIGNUPLINK", SIGNUP_LINK);

$smarty->assign("ACXPASSWORD", $ACXPASSWORD);
$smarty->assign("ACXSIP_IAX", $ACXSIP_IAX);
$smarty->assign("ACXCALL_HISTORY", $ACXCALL_HISTORY);
$smarty->assign("ACXPAYMENT_HISTORY", $ACXPAYMENT_HISTORY);
$smarty->assign("ACXVOUCHER", $ACXVOUCHER);
$smarty->assign("ACXINVOICES", $ACXINVOICES);
$smarty->assign("ACXDID", $ACXDID);
$smarty->assign("ACXSPEED_DIAL", $ACXSPEED_DIAL);
$smarty->assign("ACXRATECARD", $ACXRATECARD);
$smarty->assign("ACXSIMULATOR", $ACXSIMULATOR);
$smarty->assign("ACXWEB_PHONE", $ACXWEB_PHONE);
$smarty->assign("ACXCALL_BACK", $ACXCALL_BACK);
$smarty->assign("ACXCALLER_ID", $ACXCALLER_ID);
$smarty->assign("ACXSUPPORT", $ACXSUPPORT);
$smarty->assign("ACXNOTIFICATION", $ACXNOTIFICATION);
$smarty->assign("ACXAUTODIALER", $ACXAUTODIALER);
$smarty->assign("ACXVOICEMAIL", $ACXVOICEMAIL);

if ($exporttype != "" && $exporttype != "html") {
    $smarty->assign("EXPORT", 1);
} else {
    $smarty->assign("EXPORT", 0);
}

getpost_ifset(array('section'));

if (!empty($section)) {
    $_SESSION["menu_section"] = intval($section);
} else {
    $section = $_SESSION["menu_section"];
}
$smarty->assign("section", $section);

$smarty->assign("SKIN_NAME", $skin_name);
$smarty->assign("adminname", $_SESSION["pr_login"]);
// if it is a pop window
if (!is_numeric($popup_select)) {
    $popup_select=0;
}
// for menu
$smarty->assign("popupwindow", $popup_select);

if (!empty($msg)) {
    switch ($msg) {
        case "nodemo": 	$smarty->assign("MAIN_MSG", '<center><b><font color="red">'.gettext("This option is not available on the Demo!").'</font></b></center><br>');
    }
}

// OPTION FOR THE MENU
$smarty->assign("A2Bconfig", $A2B->config);


$smarty->assign("PAGE_SELF", $PHP_SELF);

//for display image for user.png
$image_table = new Table('cc_card','display_image');
$imaget_clause = "id='".$_SESSION['card_id']."' LIMIT 1";
$imageresult = $image_table -> Get_list($DBHandle, $imaget_clause, 0);
define("CUSTOMERDP",$imageresult[0]['display_image']);

//for customer logo
$card_table = new Table('cc_card','id_group');
$card_clause = "id='".$_SESSION['card_id']."' LIMIT 1";
$card_result = $card_table -> Get_list($DBHandle, $card_clause, 0);
//print_r($card_result[0]['id_group']);
$image=NULL;
if($card_result)
{
   $instance_table = new Table("cc_card_group","id,id_agent,group_logo_path");
    $clause="id='".$card_result[0]['id_group']."'";
    $resultgroup=$instance_table -> Get_list($DBHandle, $clause);
    //print_r($resultgroup[0]["group_logo_path"]);
     $image=$resultgroup[0]["group_logo_path"];
    if($image==NULL)
    {
        $image_table = new Table('cc_agent','logopath');
        $imaget_clause = "id='".$resultgroup[0]["id_agent"]."' LIMIT 1";
        $imageresult = $image_table -> Get_list($DBHandle, $imaget_clause, 0);
        $image=$imageresult[0]['logopath'];
        if($image==NULL)
        {
          $aimage_table = new Table('cc_ui_authen','logopath');
          $aimageresult = $aimage_table -> Get_list($DBHandle, null, 0);  
          $image=$aimageresult[0]['logopath'];
            
        }
    } 
}

 define("CUSTIMGNAME",CUSTIMGPATH.$image);
