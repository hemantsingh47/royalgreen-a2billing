<?php

$HD_Form = new FormHandler("cc_otp","OTP");

$HD_Form -> FG_DEBUG = 0;

if ($popup_select)

$HD_Form -> AddViewElement(gettext("ID"), "id", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("OTP"), "phone_otp", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("USER NAME"), "phone", "25%", "center", "sort");


$HD_Form -> AddViewElement(gettext("DATE"), "date", "20%", "center", "sort", "30"); 



//$HD_Form -> AddViewElement(gettext("Date"), "date", "20%", "center", "sort", "30"); 
$HD_Form -> FieldViewElement (($popup_select ? 'id,' : '') . 'phone_otp,phone,date');
   
if (!($popup_select>=1))     $HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'refill_cust_selection';
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for refills created.');



$HD_Form -> AddSearchElement_C1(gettext("ACCOUNT NUMBER"), 'phone','usernametype');
$HD_Form -> AddSearchElement_C1(gettext("OTP"),'phone_otp','lastnametype');
$HD_Form -> FG_FILTER_SEARCH_DELETE_ALL =false;
                                       

$HD_Form -> CV_NO_FIELDS  = gettext("NO OTP CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_VIEW_TABLE_WITDH = '95%';


if ($popup_select) {
	$HD_Form -> FG_LIMITE_DISPLAY = 7;
	$HD_Form -> CV_FOLLOWPARAMETERS = "&popup_select=" . $popup_select . "&popup_formname=" . $popup_formname . "&popup_fieldname=" . $popup_fieldname;

	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;select&gt;</font>';
	$HD_Form -> FG_OTHER_BUTTON1_IMG = '';
    
    if ($popup_select == 1)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|param|');";
	elseif ($popup_select == 2)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|col0|');";
} else {

	
}


