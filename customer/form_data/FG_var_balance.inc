<?php

$HD_Form = new FormHandler("cc_credit", "TRANSFER");
$HD_Form -> FG_TABLE_ID = 'Id';


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_VIEW_TABLE_WITDH = "100%";
$HD_Form -> AddViewElement(gettext("ID"), "Id", "5%", "center", "sort", "");
$HD_Form -> AddViewElement(gettext("TRANSFER FROM"), "transferFrom", "15%", "center", "sort", "");
$HD_Form -> AddViewElement(gettext("TRANSFER TO"), "transferTo", "20%", "center", "sort", "40");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "Amount", "10%", "center", "sort", "40");
$HD_Form -> AddViewElement(gettext("DATE"), "date", "20%", "center", "sort", "40");
$HD_Form -> AddViewElement(gettext("TIME"), "time", "10%", "center", "sort");

$HD_Form -> FieldViewElement ('Id, transferFrom,transferTo,Amount,date,time');



$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO RATECARD CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;




