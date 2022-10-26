<?php

getpost_ifset(array('id', 'username', 'keyused', 'msdest', 'amt', 'trans_time' ));

$HD_Form = new FormHandler("cc_friend_recharge","Mobile topup");

$HD_Form -> FG_DEBUG = 0;

/*$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "billing_entity_providernew.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Pin Transfer");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/world_add.png" ;   */

if ($popup_select)
	$HD_Form -> AddViewElement(gettext("ID"), "id", "15%", "center", "sort");
    $HD_Form -> AddViewElement(gettext("User ID"), "username", "16%", "center", "sort");
    $HD_Form -> AddViewElement(gettext("Account No"), "keyused", "16%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
    $HD_Form -> AddViewElement(gettext("Mobile No"), "msdest", "16%", "center", "sort", "30");
    $HD_Form -> AddViewElement(gettext("Amount(USD)"), "amt", "10%", "center", "sort", "30");  
    $HD_Form -> AddViewElement(gettext("Time"), "trans_time", "15%", "center", "sort", "30");  
    $HD_Form -> FieldViewElement (($popup_select ? 'id,' : '') . 'username, keyused, msdest,amt,trans_time');


    $HD_Form -> CV_NO_FIELDS  = gettext("NO MOBILE TOPUP CREATED!");
    $HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
    $HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
    $HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
    $HD_Form -> FG_VIEW_TABLE_WITDH = '99%';


if ($popup_select) {
	$HD_Form -> FG_LIMITE_DISPLAY = 7;
	$HD_Form -> CV_FOLLOWPARAMETERS = "&popup_select=" . $popup_select . "&popup_formname=" . $popup_formname . "&popup_fieldname=" . $popup_fieldname;

	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;select&gt;</font>';
	$HD_Form -> FG_OTHER_BUTTON1_IMG = '';

	//echo $popup_select;
	if ($popup_select == 1)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|param|');";
	elseif ($popup_select == 2)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|col0|');";
} else {
	//$HD_Form -> FG_EDITION = true;
	//$HD_Form -> FG_DELETION = true;
	$HD_Form -> FG_ADDITION = true;
}

$HD_Form -> AddEditElement ( gettext("User Id"),
		"username",	'$value',
		"INPUT",
		"size=30 maxlength=40",
		"0",
		gettext("Insert the User Id"),
		"" , "", "",	"", "", "", "", "" );


$HD_Form -> AddEditElement ( gettext("Account No"),
		"keyused",
		'$value',
		"INPUT",
        "size=30 maxlength=40",
        "0",
		gettext("Insert the amount"),
		"" , "", "",	"", "", "", "", "");
         
$HD_Form -> AddEditElement ( gettext("Mobile No"),
        "msdest",    '$value',
        "INPUT",
        "size=30 maxlength=40",
        "0",
        gettext("Insert the User Id"),
        "" , "", "",    "", "", "", "", "" );


$HD_Form -> AddEditElement ( gettext("Amount(USD)"),
        "amt",
        '$value',
        "INPUT",
        "size=30 maxlength=40",
        "0",
        gettext("Insert the amount"),
        "" , "", "",    "", "", "", "", "");
        
$HD_Form -> AddEditElement ( gettext("Time"),
        "trans_time",
        '$value',
        "INPUT",
        "size=30 maxlength=40",
        "0",
        gettext("Insert the amount"),
        "" , "", "",    "", "", "", "", "");

$HD_Form -> FieldEditElement ('username, keyused,msdest,amt,trans_time'); 



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add $HD_Form->FG_INSTANCE_NAME.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";



