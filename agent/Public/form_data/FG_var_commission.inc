<?php



getpost_ifset(array('id', 'card_id', 'date', 'payment','description','added_refill','refill_type'));


$HD_Form = new FormHandler("cc_agent_commission","Commission");

$HD_Form -> FG_TABLE_CLAUSE = " cc_agent_commission.id_agent=".$_SESSION['agent_id'] ;
$HD_Form -> FG_TABLE_ID = 'cc_agent_commission.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$com_list = Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_agent_commission.id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "id_card", "10%", "center", "sort", "", "30", "", "", "", "", "linktocustomer_id");
$HD_Form -> AddViewElement(gettext("AGENT"), "id_agent", "10%", "center", "sort", "", "30", "", "", "", "", "linktoagent");
$HD_Form -> AddViewElement(gettext("PAYMENT DATE"), "date", "15%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "amount", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAID STATUS"),"paid_status", "10%", "center", "sort", "", "list", $com_list);
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "20%", "center", "sort", 50);
$HD_Form -> AddViewElement(gettext("LINK TO PAYMENT"), "id_payment", "10%", "center", "sort", "", "lie_link", "cc_logpayment", "payment, id", "id='%id'", "%1 ".BASE_CURRENCY." (id:%2)", "", "billing_entity_payment.php");

$HD_Form -> FieldViewElement ('cc_agent_commission.id, id_card, id_agent, date, amount, commission_type , description, id_payment');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_ADDITION = false;


