

<?php

getpost_ifset(array('id', 'did_number','country','country','creationdate'));
$HD_Form = new FormHandler("cc_fcm_notification","FCM Notification");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;//No work of this
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "import_notification_number.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Import Numbers");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/brick_add.png" ;


// Code Here for Deleting the Dependent Records
// Dependent Tables
$HD_Form -> FG_FK_DELETE_ALLOWED = false;
$HD_Form -> FG_FK_DELETE_CONFIRM = false;
$HD_Form -> FG_FK_WARNONLY = true;
$HD_Form -> FG_FK_TABLENAMES = array("cc_fcm_notification");
$HD_Form -> FG_FK_EDITION_CLAUSE = array("id");

$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("Please comfirm that you really want to remove Notification Content? ");

$cnts=new Constants();
$user_type = $cnts->getComponentUserTypeList();
$activated_list = $cnts->getActivationList();

$HD_Form -> AddViewElement(gettext("ID"), "id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("Sending Date"), "created_date", "20%", "center", "sort");
$HD_Form -> AddViewElement(gettext("Message"), "messagedata",  "35%", "center", "sort");
//$HD_Form -> AddViewElement(gettext("Users"), "users", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("Result"), "users", "35%", "center", "sort");

$HD_Form -> FieldViewElement ('id, created_date,messagedata,result');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = true;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;


$HD_Form -> AddEditElement (gettext("Enter Message"),
	"messagedata",
	'$value',
	"INPUT",
	"size=50 maxlength=100",
	"9",
	gettext("Insert the message to send "),
	"" , "", "", "", "", "", "", "" );                                            


$HD_Form -> FieldEditElement ('messagedata');



$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";



