<?php


getpost_ifset(array('destination', 'dialprefix', 'rateinitial','posted','tariffplan'));


$HD_Form = new FormHandler("cc_callplan_lcr", "Rates");
$HD_Form -> FG_TABLE_ID = 'ratecard_id';


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "destination";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_VIEW_TABLE_WITDH = "65%";
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'entity_ratecard_selection';




$HD_Form -> FG_TABLE_CLAUSE = "tariffgroup_id = '".$_SESSION["tariff"]."' ";
$HD_Form -> SQL_GROUP = "GROUP BY dialprefix";

$HD_Form -> AddViewElement("<acronym title=\"DIALING PREFIX\">".gettext("PREFIX")."</acronym>", "dialprefix", "20%", "center", "sort", "");
$HD_Form -> AddViewElement(gettext("DESTINATION"), "destination", "60%", "center", "sort", "40");
$HD_Form -> AddViewElement("<acronym title=\"SELLING RATE\">".gettext("SR")."</acronym>", "rateinitial", "20%", "center", "sort");

$HD_Form -> FieldViewElement ('dialprefix, destination, MIN(rateinitial) as rateinitial');



$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO RATECARD CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;




