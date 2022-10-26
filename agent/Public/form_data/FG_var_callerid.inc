<?php




getpost_ifset(array('IDCust', 'id', 'cid', 'activated'));

if (!isset($form_action) || $form_action=="list") {
	$HD_Form = new FormHandler("cc_callerid LEFT JOIN cc_card ON cc_card.id=cc_callerid.id_cc_card LEFT JOIN cc_card_group ON cc_card.id_group=cc_card_group.id", "CallerID");
	$HD_Form -> FG_TABLE_CLAUSE = " cc_card_group.id_agent=".$_SESSION['agent_id'] ;
	$HD_Form -> FG_TABLE_ID = 'cc_callerid.id';
} else {
	$HD_Form = new FormHandler("cc_callerid", "CallerID");
}

$HD_Form -> FG_DEBUG = 0;

$HD_Form ->FG_LIST_ADDING_BUTTON2 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK2 = "billing_entity_callerid.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT2 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG2 = gettext("Add CallerID");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG2 = Images_Path ."/page_white_add.png" ;

$cnts = new Constants();
$actived_list = $cnts->getActivationTrueFalseList();
$yesno = $cnts->getYesNoList();

$HD_Form -> AddViewElement(gettext("CALLERID"), "cid", "20%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT NUMBER"), "id_cc_card", "25%", "center", "sort", "", "lie_link", "cc_card", "username, id", "id='%id'", "%1", "", "billing_entity_card.php");
$HD_Form -> AddViewElement(gettext("CUSTOMER NAME"), "id_cc_card", "25%", "center", "sort", "", "lie_link", "cc_card", "lastname, id, firstname", "id='%id'", "%1 %3", "", "billing_entity_card.php");
$HD_Form -> AddViewElement(gettext("ACTIVATED"), "activated", "15%", "center", "sort", "", "list", $actived_list);

$HD_Form -> FieldViewElement ('cid, id_cc_card, id_cc_card, cc_callerid.activated, cc_callerid.id');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ").strtoupper($HD_Form->FG_INSTANCE_NAME).gettext(" CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;


$HD_Form -> AddEditElement (gettext("CALLERID"),
			   "cid",
			   '$value',
			   "INPUT",
			   "size=20 maxlength=30",
			   "18",
			   gettext("Insert the CallerID"),
			   "" , "", "",	"", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("ACTIVATED"),
			   "activated",
			   't',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to activate this card"),
			   "" , "", "", "Yes :t, - No:f", "", "", "", "" );

$HD_Form -> AddEditElement (gettext("ID CARD"),
			   "id_cc_card",
			   "$value",
			   "POPUPVALUE",
			   "size=30 maxlength=50",
			   "4",
			   gettext("Insert the Id CardNumber"),
			    "" , "", "", "", "",
				"billing_entity_card.php?popup_select=1&"
				, ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Define the card number ID to use."));


$HD_Form -> FieldEditElement ('cid, activated, id_cc_card');


// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'callerid';
$HD_Form -> FG_FILTERFIELDNAME = gettext('CALLERID');

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="cid like '$filterprefix%'";
}


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify via the form below, the properties of the callerID");
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME."<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_OTHER_BUTTON3 = false;
$HD_Form -> FG_OTHER_BUTTON3_LINK = "billing_entity_logrefill.php?form_action=ask-add&card_id=|col_orig1|";
$HD_Form -> FG_OTHER_BUTTON3_IMG = Images_Path . "/brick_add.png";
$HD_Form -> FG_OTHER_BUTTON3_ALT = gettext('ADD REFILL');



$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click Confirm Data to apply your changes");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";



