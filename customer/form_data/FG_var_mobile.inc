<?php

$HD_Form = new FormHandler("cc_friend_recharge", "MOBILE");
$HD_Form -> FG_TABLE_ID = 'id';


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "msdest";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_VIEW_TABLE_WITDH = "95%";

$HD_Form -> AddViewElement(gettext("USER ID"), "id", "10%", "center", "sort", "");
$HD_Form -> AddViewElement(gettext("ACCOUNT NUMBER"), "mdused", "15%", "center", "sort", "");
$HD_Form -> AddViewElement(gettext("MOBILE NUMBER"), "msdest", "25%", "center", "sort", "40");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "amt", "30%", "center", "sort", "40");
$HD_Form -> AddViewElement(gettext("TIME"), "trans_time", "20%", "center", "sort");

$HD_Form -> FieldViewElement ('id, mdused,msdest,amt,trans_time');



$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO RATECARD CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;




