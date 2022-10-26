<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CALL_REPORT)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('customer','sellrate','buyrate','entercustomer', 'enterprovider', 'entertariffgroup', 'entertrunk', 'enterratecard', 'posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday','fromtime','totime','fromstatsday_hour', 'tostatsday_hour' ,'fromstatsday_min', 'tostatsday_min' , 'dsttype', 'srctype','dnidtype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src','dnid', 'clid', 'choose_currency', 'terminatecauseid', 'choose_calltype'));

if (($_GET[download]=="file") && $_GET[file] ) {

    $value_de=base64_decode($_GET[file]);
    $pos = strpos($value_de, '../');
    if ($pos === false) {
        $dl_full = MONITOR_PATH."/".$value_de;
        $dl_name=$value_de;

        if (!file_exists($dl_full)) {
            echo gettext("ERROR: Cannot download file ".$dl_full.", it does not exist.<br>");
            exit();
        }

        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$dl_name");
        header("Content-Length: ".filesize($dl_full));
        header("Accept-Ranges: bytes");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-transfer-encoding: binary");

        @readfile($dl_full);
        exit();
    }
}

if (!isset ($current_page) || ($current_page == "")) {
    $current_page=0;
}

// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME="cc_call t1 LEFT OUTER JOIN cc_trunk t3 ON t1.id_trunk = t3.id_trunk LEFT OUTER JOIN  cc_ratecard as rc ON rc.id=t1.id_ratecard";

if ($_SESSION["is_admin"]==0) {
     $FG_TABLE_NAME.=", cc_card t2";
}

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";

$yesno = array();
$yesno["1"]  = array( "Yes", "1");
$yesno["0"]  = array( "No", "0");

// 0 = NORMAL CALL ; 1 = VOIP CALL (SIP/IAX) ; 2= DIDCALL + TRUNK ; 3 = VOIP CALL DID ; 4 = CALLBACK call
$list_calltype = array(); 	$list_calltype["0"]  = array( gettext("STANDARD"), "0");	 $list_calltype["1"]  = array( gettext("SIP/IAX"), "1");
$list_calltype["2"]  = array( gettext("DIDCALL"), "2"); $list_calltype["3"]  = array( gettext("DID_VOIP"), "3"); $list_calltype["4"]  = array( gettext("CALLBACK"), "4");
$list_calltype["5"]  = array( gettext("PREDICT"), "5");

$DBHandle  = DbConnect();

$FG_TABLE_COL = array();
$FG_TABLE_COL[]=array (gettext("DNID"), "dnid", "25%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("COUNT"),"count","15%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("AVG Rate"), "buyrate", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");
$FG_TABLE_COL[]=array (gettext("AVG Sale"), "calledrate", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");
$FG_TABLE_COL[]=array (gettext("Duration"), "sessiontime", "10%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$FG_TABLE_COL[]=array (gettext("Buy"), "buycost", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");
$FG_TABLE_COL[]=array (gettext("Sell"), "sessionbill", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");

$FG_TABLE_DEFAULT_ORDER = "count";
$FG_TABLE_DEFAULT_SENS = "DESC";

// This Variable store the argument for the SQL query
$FG_COL_QUERY='t1.dnid ,count(*) as count,avg(rc.buyrate) as buyrate ,avg(rc.rateinitial) as calledrate,
     sum(t1.sessiontime) as sessiontime ,sum(t1.buycost) as buycost, sum(t1.sessionbill) as sessionbill';

$FG_COL_QUERY_GRAPH='t1.callstart, t1.duration';

$FG_LIMITE_DISPLAY=10000;
$FG_NB_TABLE_COL=count($FG_TABLE_COL);
$FG_EDITION=true;
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
if ($FG_DELETION || $FG_EDITION) $FG_TOTAL_TABLE_COL++;
$FG_HTML_TABLE_TITLE = gettext(" - DNID REPORT - ");
$FG_HTML_TABLE_WIDTH = '70%';

if ($FG_DEBUG == 3) echo "<br>Table : $FG_TABLE_NAME  	- 	Col_query : $FG_COL_QUERY";
$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);
$instance_table_graph = new Table($FG_TABLE_NAME, $FG_COL_QUERY_GRAPH);

if ( is_null ($order) || is_null($sens) ) {
    $order = $FG_TABLE_DEFAULT_ORDER;
    $sens  = $FG_TABLE_DEFAULT_SENS;
}

if ($posted==1) {
    $SQLcmd = '';
    $SQLcmd = do_field($SQLcmd, 'src', 'src');
    $SQLcmd = do_field($SQLcmd, 'dst', 'calledstation');
    $SQLcmd = do_field($SQLcmd, 'dnid', 'dnid');
}

$date_clause='';
normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);
if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) {
    if ($fromtime) {
        $date_clause.=" AND UNIX_TIMESTAMP(t1.starttime) >= UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday $fromstatsday_hour:$fromstatsday_min')";
    } else {
        $date_clause.=" AND UNIX_TIMESTAMP(t1.starttime) >= UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
    }
}
if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) {
    if ($totime) {
        $date_clause.=" AND UNIX_TIMESTAMP(t1.starttime) <= UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." $tostatsday_hour:$tostatsday_min:59')";
    } else {
        $date_clause.=" AND UNIX_TIMESTAMP(t1.starttime) <= UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
    }
}

if (strpos($SQLcmd, 'WHERE') > 0) {
    $FG_TABLE_CLAUSE = substr($SQLcmd,6).$date_clause;
} elseif (strpos($date_clause, 'AND') > 0) {
    $FG_TABLE_CLAUSE = substr($date_clause,5);
}

if (!isset ($FG_TABLE_CLAUSE) || strlen($FG_TABLE_CLAUSE)==0) {
    $cc_yearmonth = sprintf("%04d-%02d-%02d",date("Y"),date("n"),date("d"));
    $FG_TABLE_CLAUSE=" UNIX_TIMESTAMP(t1.starttime) >= UNIX_TIMESTAMP('$cc_yearmonth')";
}

if (isset($customer)  &&  ($customer>0)) {
    if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
    $FG_TABLE_CLAUSE.="t1.username='$customer'";
} else {
    if (isset($entercustomer)  &&  ($entercustomer>0)) {
        if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
        $FG_TABLE_CLAUSE.="t1.username='$entercustomer'";
    }
}

if ($_SESSION["is_admin"] == 1) {
    if (isset($enterprovider) && $enterprovider > 0) {
        if (strlen($FG_TABLE_CLAUSE) > 0) $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= "t3.id_provider = '$enterprovider'";
    }
    if (isset($entertrunk) && $entertrunk > 0) {
        if (strlen($FG_TABLE_CLAUSE) > 0) $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= "t3.id_trunk = '$entertrunk'";
    }
    if (isset($entertariffgroup) && $entertariffgroup > 0) {
        if (strlen($FG_TABLE_CLAUSE) > 0) $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= "t1.id_tariffgroup = '$entertariffgroup'";
    }
    if (isset($enterratecard) && $enterratecard > 0) {
        if (strlen($FG_TABLE_CLAUSE) > 0) $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= "t1.id_ratecard = '$enterratecard'";
    }
    if (isset($buyrate)&&!empty($buyrate)) {	if (strlen($FG_TABLE_CLAUSE) > 0) $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= "t1.buyrate = '$buyrate'";
    }
    if (isset($sellrate)&&!empty($sellrate)) {	if (strlen($FG_TABLE_CLAUSE) > 0) $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= "t1.calledrate = '$sellrate'";
    }

}

if ($_SESSION["is_admin"]==0) {
    if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
    $FG_TABLE_CLAUSE.="t1.cardID=t2.IDCust AND t2.IDmanager='".$_SESSION["pr_reseller_ID"]."'";
}

if (isset($choose_calltype) && ($choose_calltype!=-1)) {
    if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
    $FG_TABLE_CLAUSE .= " t1.sipiax='$choose_calltype' ";
}

$FG_ASR_CIC_CLAUSE = $FG_TABLE_CLAUSE;
//To select just terminatecause=ANSWER

if (! isset ( $terminatecauseid )) {
        $terminatecauseid = "ANSWER";
}
if ($terminatecauseid == "ANSWER") {
        if (strlen ( $FG_TABLE_CLAUSE ) > 0)
                $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=1) ";
}
if ($terminatecauseid == "INCOMPLET") {
        if (strlen ( $FG_TABLE_CLAUSE ) > 0)
                $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= " (t1.terminatecauseid !=1) ";
}
if ($terminatecauseid == "CONGESTION") {
        if (strlen ( $FG_TABLE_CLAUSE ) > 0)
                $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=5) ";
}
if ($terminatecauseid == "NOANSWER") {
        if (strlen ( $FG_TABLE_CLAUSE ) > 0)
                $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=3) ";
}
if ($terminatecauseid == "BUSY") {
        if (strlen ( $FG_TABLE_CLAUSE ) > 0)
                $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=2) ";
}
if ($terminatecauseid == "CHANUNAVAIL") {
        if (strlen ( $FG_TABLE_CLAUSE ) > 0)
                $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=6) ";
}
if ($terminatecauseid == "CANCEL") {
        if (strlen ( $FG_TABLE_CLAUSE ) > 0)
                $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=4) ";
}

if (!$nodisplay) {
    $list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY," GROUP  BY dnid ");
}

// EXPORT
// NOTE : MAYBE REWRITE THIS PAGE FROM THE FRAMEWORK
$FG_EXPORT_SESSION_VAR = "pr_export_entity_call";

// Query Preparation for the Export Functionality
$_SESSION[$FG_EXPORT_SESSION_VAR]= "SELECT $FG_COL_QUERY FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE";

if (!is_null ($order) && ($order!='') && !is_null ($sens) && ($sens!='')) {
    $_SESSION[$FG_EXPORT_SESSION_VAR].= " ORDER BY $order $sense";
}

/************************/
$QUERY = "SELECT DATE(t1.starttime) AS day, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) as nbcall, sum(t1.buycost) AS buy FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE." GROUP BY day ORDER BY day"; //extract(DAY from calldate)

if (!$nodisplay) {
    $res = Connection::CleanExecute($QUERY);
    if ($res) {
        $num = $res -> RecordCount();
        for ($i=0;$i<$num;$i++) {
            $list_total_day [] =$res -> fetchRow();
        }
    }

    if ($FG_DEBUG == 3) echo "<br>Clause : $FG_TABLE_CLAUSE";
    $nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE);
    if ($FG_DEBUG >= 1) var_dump ($list);

}//end IF nodisplay

if ($nb_record<=$FG_LIMITE_DISPLAY) {
    $nb_record_max=1;
} else {
    if ($nb_record % $FG_LIMITE_DISPLAY == 0) {
        $nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY));
    } else {
        $nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY)+1);
    }
}

if ($FG_DEBUG == 3) echo "<br>Nb_record : $nb_record";
if ($FG_DEBUG == 3) echo "<br>Nb_record_max : $nb_record_max";

$smarty->display('main.tpl');

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                DNID Reports                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                           Reports                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Other Report                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            DNID                      </a>
							   
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h1 class="kt-portlet__head-title">
				<?php echo gettext("DNID Report"); ?>
				
			</h1>
		</div>
	</div>
	 


     
       
    <div class="kt-portlet__body">









<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
<!--<h3 class="heading_b uk-margin-bottom"><?php echo gettext("DNID");?></h3>
<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5><?php echo gettext("DNID");?></h5>
        </div>
		<br />
        <div class="widget-content nopadding">-->
		
		
<FORM class="kt-form" METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
<INPUT TYPE="hidden" NAME="posted" value=1>
<INPUT TYPE="hidden" NAME="current_page" value=0>
     <div class="col-md-12">
		
		<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="5" style="border-top: 1px solid #CDCDCD; padding: 0px;">
	<?php
        if ($_SESSION ["pr_groupID"] == 2 && is_numeric ( $_SESSION ["pr_IDCust"] )) {
            ?>
        <?php
        } else {
            ?>
              <label class="control-label"><?php echo gettext ( "CUSTOMERS" ); ?></label>
			  </td>
			  </tr>
			  <tr>
			  
              <td class="fontstyle_searchoptions"  align="left">
                <?php echo gettext ( "Enter the customer ID" ); ?>:</td>
				<td>
				<INPUT TYPE="text"
                    NAME="entercustomer" value="<?php echo $entercustomer?>"
                    class="form-control" style="width:90%"> <a href="#"
                    onclick="window.open('billing_entity_card.php?popup_select=1&popup_formname=myForm&popup_fieldname=entercustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><i class="flaticon2-fast-next"></i></a></td>
					
              <td width="5%">
               <b>OR</b></td>
               <td align="left"> 
                <?php echo gettext ( "Enter the customer number" );?>:</td>
				<td>
				<INPUT TYPE="text" NAME="entercustomer_num"
                    value="<?php echo $entercustomer_num?>" class="form-control" style="width:90%"> <a href="#"
                                        onclick="window.open('billing_entity_card.php?popup_select=2&popup_formname=myForm&popup_fieldname=entercustomer_num' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><i class="flaticon2-fast-next"></i></a>
              </td>
			  
			  </tr>
			  <tr>
			  <td align="left">
                <?php echo gettext ( "CallPlan" ); ?> : </td>
				<td><INPUT TYPE="text" NAME="entertariffgroup" value="<?php echo $entertariffgroup?>" size="4" class="form-control" style="width:90%">&nbsp;<a href="#" onclick="window.open('billing_entity_tariffgroup.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertariffgroup' , 'CallPlanSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><i class="flaticon2-fast-next"></i></a></td>
                 <td width="5%">
                <b>OR</b> </td>
               <td align="left">
                <?php echo gettext ( "Provider" ); ?> : </td>
				<td><INPUT
                            TYPE="text" NAME="enterprovider"
                            value="<?php echo $enterprovider?>" size="4" class="form-control" style="width:90%">&nbsp;<a href="#"
                            onclick="window.open('billing_entity_provider.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterprovider' , 'ProviderSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><i class="flaticon2-fast-next"></i></a></td>
							
              </tr>
			  <tr>
			  <td align="left">
                <?php echo gettext ( "Trunk" ); ?> : </td>
				<td>
				<INPUT
                            TYPE="text" NAME="entertrunk" value="<?php echo $entertrunk?>"
                            size="4" class="form-control" style="width:90%">&nbsp;<a href="#"
                            onclick="window.open('billing_entity_trunk.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertrunk' , 'TrunkSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><i class="flaticon2-fast-next"></i></a></td>
			
                 <td width="5%">
                <b>OR</b> </td>
               <td align="left"> 
                <?php echo gettext ( "Rate" ); ?> : </td>
				<td><INPUT
                            TYPE="text" NAME="enterratecard"
                            value="<?php echo $enterratecard?>" size="4"
                            class="form-control" style="width:90%">&nbsp;<a href="#"
                            onclick="window.open('billing_entity_def_ratecard.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterratecard' , 'RatecardSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><i class="flaticon2-fast-next"></i></a></td>
               
			   <?php
        }
        ?>
             </tr>
                <tr>

<td align="left" >
				   <?php echo gettext("Buy Rate");?> : </td>
				   <td>
                            <INPUT TYPE="text" NAME="buyrate" value="<?php echo $buyrate?>" size="8" class="form-control" style="width:90%"></td>
							<td width="5%">
                <b>OR</b> </td>
               <td align="right"> 
                            <?php echo gettext("Sell Rate");?> :</td>
							<td>
                            <INPUT TYPE="text" NAME="sellrate" value="<?php echo $sellrate?>" size="8" class="form-control" style="width:90%">
                   </td>
				   </tr>
				   </table>
				   </div>
				   
				   
				   
				   
       <div class="col-md-12">
	   
	   <table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="4" style="border-top: 1px solid #CDCDCD; padding: 0px;">
              <label class="control-label"><?php echo gettext ( "Date" ); ?></label></td>
			  </tr>
			  <tr>
			  <td align="left">
               
                <input type="checkbox"
                    name="fromday" value="true" <?php
                    if ($fromday) {
                        ?> checked
                    <?php
                    }
                    ?>> <?php
                    echo gettext ( "From" );
                    ?> :</td>
					<td>
                <select name="fromstatsday_sday" class="form-control11">
                    <?php
                    for ($i = 1; $i <= 31; $i ++) {
                        if ($fromstatsday_sday == sprintf ( "%02d", $i ))
                            $selected = "selected";
                        else
                            $selected = "";
                        echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                    }
                    ?>
                </select> <select name="fromstatsmonth_sday"
                    class="form-control13">
                <?php
                $year_actual = date ( "Y" );
                $monthname = array (gettext ( "January" ), gettext ( "February" ), gettext ( "March" ), gettext ( "April" ), gettext ( "May" ), gettext ( "June" ), gettext ( "July" ), gettext ( "August" ), gettext ( "September" ), gettext ( "October" ), gettext ( "November" ), gettext ( "December" ) );

                for ($i = $year_actual; $i >= $year_actual - 1; $i --) {
                    if ($year_actual == $i) {
                        $monthnumber = date ( "n" ) - 1; // Month number without lead 0.
                    } else {
                        $monthnumber = 11;
                    }
                    for ($j = $monthnumber; $j >= 0; $j --) {
                        $month_formated = sprintf ( "%02d", $j + 1 );
                        if ($fromstatsmonth_sday == "$i-$month_formated")
                            $selected = "selected";
                        else
                            $selected = "";
                        echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                    }
                }
                ?>
                </select>
				</td>
				<td align="left">
				<input type="checkbox" name="fromtime" value="true"
                    <?php
                    if ($fromtime) {
                        ?> checked <?php
                    }
                    ?>>
                <?php
                echo gettext ( "Time :" )?></td>
				<td>
                <select name="fromstatsday_hour" class="form-control11">
                <?php
                for ($i = 0; $i <= 23; $i ++) {
                    if ($fromstatsday_hour == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select> : <select name="fromstatsday_min"
                    class="form-control13">
                <?php
                for ($i = 0; $i < 60; $i = $i + 5) {
                    if ($fromstatsday_min == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select>
                   </td>
				   
				   </tr>
				   <tr>
				   <td align="left">
                   
					<!--to end-->
					 
                <input type="checkbox"
                    name="today" value="true" <?php
                    if ($today) {
                        ?> checked <?php
                    }
                    ?>>
                <?php
                echo gettext ( "To" );
                ?>  :</td>
                <td><select name="tostatsday_sday" class="form-control11">
                <?php
                for ($i = 1; $i <= 31; $i ++) {
                    if ($tostatsday_sday == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select> <select name="tostatsmonth_sday" class="form-control13">
                <?php
                $year_actual = date ( "Y" );
                for ($i = $year_actual; $i >= $year_actual - 1; $i --) {
                    if ($year_actual == $i) {
                        $monthnumber = date ( "n" ) - 1; // Month number without lead 0.
                    } else {
                        $monthnumber = 11;
                    }
                    for ($j = $monthnumber; $j >= 0; $j --) {
                        $month_formated = sprintf ( "%02d", $j + 1 );
                        if ($tostatsmonth_sday == "$i-$month_formated")
                            $selected = "selected";
                        else
                            $selected = "";
                        echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                    }
                }
                ?>
                </select> </td>
				<td align="left">
                <input type="checkbox" name="totime" value="true"
                    <?php
                    if ($totime) {
                        ?> checked <?php
                    }
                    ?>>
                <?php
                echo gettext ( "Time :" )?></td>
				<td>
                <select name="tostatsday_hour" class="form-control11">
                <?php
                for ($i = 0; $i <= 23; $i ++) {
                    if ($tostatsday_hour == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select> : <select name="tostatsday_min" class="form-control13">
                <?php
                for ($i = 0; $i < 60; $i = $i + 5) {
                    if ($tostatsday_min == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</div>
				
            
                <div class="col-md-12">
	   
	   <table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="5" style="border-top: 1px solid #CDCDCD; padding: 0px;">
              <label class="control-label"><?php echo gettext ( "Called Number" ); ?></label>
			  </tr>
			  <tr>
			  <td >
               
			       <INPUT TYPE="text" NAME="dst"
                    value="<?php
                    echo $dst?>" class="form-control">
                </td>
				
				<td><input
                    type="radio" NAME="dsttype" value="1"
                    <?php
                    if ((! isset ( $dsttype )) || ($dsttype == 1)) {
                        ?> checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Exact" );
                    ?>
                </td>
				<td><input
                    type="radio" NAME="dsttype" value="2" <?php
                    if ($dsttype == 2) {
                        ?>
                    checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Begins with" );
                    ?>
                </td>
				<td><input
                    type="radio" NAME="dsttype" value="3" <?php
                    if ($dsttype == 3) { 
					?>
                    checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Contains" );
                    ?>
               </td>
			   <td><input
                    type="radio" NAME="dsttype" value="4" <?php
                    if ($dsttype == 4) {
                        ?>
                    checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Ends with" );
                    ?></td>
</tr>
</table>					
			  </div>
			 
			 
			 
                <div class="col-md-12">
	   
	   <table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="5" style="border-top: 1px solid #CDCDCD; padding: 0px;">
              <label class="control-label"><?php echo gettext("SOURCE");?></label></td>
			  </tr>
			  <tr>
			  <td>
             
			<INPUT TYPE="text" NAME="src" value="<?php echo "$src";?>" class="form-control"></td>
            <td><input type="radio" NAME="srctype" value="1" <?php if ((!isset($srctype))||($srctype==1)) {?>checked<?php }?>> <?php echo gettext("Exact");?></td>
			<td>
            <input type="radio" NAME="srctype" value="2" <?php if ($srctype==2) {?>checked<?php }?>> <?php echo gettext("Begins with");?></td>
			<td>
            <input type="radio" NAME="srctype" value="3" <?php if ($srctype==3) {?>checked<?php }?>> <?php echo gettext("Contains");?></td>
			<td>
            <input type="radio" NAME="srctype" value="4" <?php if ($srctype==4) {?>checked<?php }?>> <?php echo gettext("Ends with");?>
            </td>
			</tr>
			</table>
			</div>
			
			
			
            <div class="col-md-12">
	   
	   <table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="5" style="border-top: 1px solid #CDCDCD; padding: 0px;">
              <label class="control-label"><?php echo gettext ( "DNID" ); ?></label></td></tr>
			  <tr>
			  <td>
               
			       <INPUT TYPE="text" NAME="dnid"
                    value="<?php
                    echo "$dnid";
                    ?>" class="form-control">
                </td>
				<td><input
                    type="radio" NAME="dnidtype" value="1"
                    <?php
                    if ((! isset ( $dnidtype )) || ($dnidtype == 1)) {
                        ?> checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Exact" );
                    ?>
                </td>
				<td><input
                    type="radio" NAME="dnidtype" value="2" <?php
                    if ($dnidtype == 2) {
                        ?>
                    checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Begins with" );
                    ?>
                </td>
				<td><input
                    type="radio" NAME="dnidtype" value="3" <?php
                    if ($dnidtype == 3) {
                        ?>
                    checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Contains" );
                    ?>
                </td>
				<td><input
                    type="radio" NAME="dnidtype" value="4" <?php
                    if ($dnidtype == 4) {
                        ?>
                    checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Ends with" );
                    ?>					
			  </td>
			  </tr>
			  </table>
			  </div>

            <!-- Select Calltype: -->
           <div class="col-md-12">
	   
	   <table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="5" style="border-top: 1px solid #CDCDCD; padding: 0px;">
              <label class="control-label"><?php echo gettext ( "CALL TYPE" ); ?></label></td>
			  </tr>
			  <tr>
			  <td>
               
			       <select NAME="choose_calltype"
                    size="1" class="form-control">
                    <option value='-1'
                        <?php
                        if (($choose_calltype == - 1) || (! isset ( $choose_calltype ))) {
                            ?>
                        selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'ALL CALLS' )?>
                                </option>
                            <?php
                            foreach ($list_calltype as $key => $cur_value) {
                                ?>
                                <option value='<?php
                                echo $cur_value [1]?>'
                        <?php
                                if ($choose_calltype == $cur_value [1]) {
                                    ?> selected <?php
                                }
                                ?>><?php
                                echo gettext ( $cur_value [0] )?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>					
			  </td>
			  <td>
			  </td>
			  <td>
			  </td>
			  <td>
			  </td>
			  <td>
			  </td>
			  </tr>
			  </table>
			  </div>
			  <!--jkljfgkldsga ldskfalkdsfalkds-->
			  <div class="col-md-12">
	   
	   <table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="6" style="border-top: 1px solid #CDCDCD; padding: 0px;">
              <label class="control-label"><?php echo gettext ( "OPTIONS" ); ?></label></td>
			  </tr>
			  <tr>
			  <td align="right">
               
			       <?php
                    echo gettext ( "SHOW CALLS" ); ?> :</td>
					<td>
					<select NAME="terminatecauseid" size="1" class="form-control">
                    <option value='ANSWER'
                        <?php
                        if ((! isset ( $terminatecauseid )) || ($terminatecauseid == "ANSWER")) {
                            ?>
                        selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'ANSWERED' )?>
                            </option>

                    <option value='ALL' <?php
                    if ($terminatecauseid == "ALL") {
                        ?> selected
                        <?php
                    }
                    ?>><?php
                        echo gettext ( 'ALL' )?>
                            </option>

                    <option value='INCOMPLET'
                        <?php
                        if ($terminatecauseid == "INCOMPLET") {
                            ?> selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'NOT COMPLETED' )?>
                            </option>

                    <option value='CONGESTION'
                        <?php
                        if ($terminatecauseid == "CONGESTION") {
                            ?> selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'CONGESTIONED' )?>
                            </option>

                    <option value='BUSY' <?php if ($terminatecauseid == "BUSY") { ?>
                        selected <?php
                    } ?>><?php
                        echo gettext ( 'BUSIED' )?>
                            </option>

                    <option value='NOANSWER'
                        <?php
                        if ($terminatecauseid == "NOANSWER") {
                            ?> selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'NOT ANSWERED' )?>
                            </option>

                    <option value='CHANUNAVAIL'
                        <?php
                        if ($terminatecauseid == "CHANUNAVAIL") {
                            ?> selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'CHANNEL UNAVAILABLE' )?>
                            </option>

                    <option value='CANCEL' <?php if ($terminatecauseid == "CANCEL") { ?>
                        selected <?php
                    }
                    ?>><?php
                        echo gettext ( 'CANCELED' )?>
                            </option>

                </select>				
			  </td>
			  <td align="right">
			   
			  <?php echo gettext ( "RESULT" ); ?> : </td>
			  <td>
			  <?php
                    echo gettext ( "mins" );
                    ?> <input type="radio" NAME="resulttype"
                    value="min"
                    <?php
                    if ((! isset ( $resulttype )) || ($resulttype == "min")) {
                        ?> checked
                    <?php
                    }
                    ?>> - <?php
                    echo gettext ( "secs" )?> <input type="radio"
                    NAME="resulttype" value="sec" <?php
                    if ($resulttype == "sec") {
                        ?>
                    checked <?php
                    }
                ?>>
			  </td>
			  <td>
			  <?php
                    echo gettext ( "CURRENCY" ); ?> : </td>
<td>					<select NAME="choose_currency"
                    size="1" class="form-control">
                        <?php
                        $currencies_list = get_currencies ();
                        foreach ($currencies_list as $key => $cur_value) {
                            ?>
                            <option value='<?php
                            echo $key?>'
                        <?php
                            if (($choose_currency == $key) || (! isset ( $choose_currency ) && $key == strtoupper ( BASE_CURRENCY ))) {
                                ?>
                        selected <?php
                            }
                            ?>><?php
                            echo $cur_value [1] . ' (' . $cur_value [2] . ')'?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
			  </td>
			  </tr>
			  </table>
			  </div>
   
	<div class="col-md-12">
             <input type="submit"  class="btn btn-primary pull-right"  name="image16" align="top" border="0"
            value="<?php
            echo gettext("Search");
            ?>" />
            </div>

</FORM>
<br>
<br>
<!-- ** ** ** ** ** Part to display the CDR ** ** ** ** ** -->

<div class="col-md-12">
<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title"  style="border-top: 1px solid #CDCDCD; padding: 0px;">
            <?php echo $FG_HTML_TABLE_TITLE?></td>
			</tr>
			<tr>
			<td>
             <span class="label label-info"><?php
                    echo gettext ( "Number of call" );
                    ?> : <?php
                    if (is_array ( $list ) && count ( $list ) > 0) {
                        echo $nb_record;
                    } else {
                        echo "0";
                    }
                    ?></span></td>
					</tr>
					</table>
          <div class="col-md-12">
<table >
    
    <TR>
        <TD>
        <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
            <TR class="bgcolor_008">
                <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"></TD>

                  <?php
                        if (is_array ( $list ) && count ( $list ) > 0) {

                            for ($i = 0; $i < $FG_NB_TABLE_COL; $i ++) {
                                ?>
                    <TD width="<?php echo $FG_TABLE_COL [$i] [2]?>" align=middle class="tableBody" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px">
                        <center><strong>
                        <?php if (strtoupper ( $FG_TABLE_COL [$i] [4] ) == "SORT") { ?>
                        <a href="<?php
                                echo $PHP_SELF . "?entercustomer_num=$entercustomer_num&s=1&t=0&stitle=$stitle&atmenu=$atmenu&current_page=$current_page&order=" . $FG_TABLE_COL [$i] [1] . "&sens=";
                                if ($sens == "ASC") {
                                    echo "DESC";
                                } else {
                                    echo "ASC";
                                }
                                echo "&entercustomer=$entercustomer&enterprovider=$enterprovider&entertrunk=$entertrunk&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&dsttype=$dsttype&srctype=$srctype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&dst=$dst&src=$src&clid=$clid&terminatecauseid=$terminatecauseid&choose_calltype=$choose_calltype";
                                    ?>">
<span class="liens"><?php
                                }
                                ?>
<?php echo $FG_TABLE_COL [$i] [0]?>
<?php if ($order == $FG_TABLE_COL [$i] [1] && $sens == "ASC") { ?>
&nbsp;<img src="<?php echo Images_Path; ?>/icon_up_12x12.GIF" width="12"
height="12" border="0">
<?php
 } elseif ($order == $FG_TABLE_COL [$i] [1] && $sens == "DESC") {
?>
&nbsp;<img src="<?php echo Images_Path; ?>/icon_down_12x12.GIF" width="12" height="12" border="0">
<?php } ?>
<?php if (strtoupper ( $FG_TABLE_COL [$i] [4] ) == "SORT") { ?>
</span></a>
<?php } ?>
</strong></center>
</TD>
   <?php } ?>
   <?php if ($FG_DELETION || $FG_EDITION) { ?>
   <?php } ?>
</TR>
<?php

$ligne_number = 0;
//print_r($list);
foreach ($list as $recordset) {
    $ligne_number ++;
    ?>

<TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$ligne_number % 2]?>" onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$ligne_number % 2]?>'">
<TD vAlign=top align="<?php echo $FG_TABLE_COL [$i] [3]?>" class=tableBody><?php echo $ligne_number + $current_page * $FG_LIMITE_DISPLAY . ".&nbsp;"; ?></TD>

<?php for ($i = 0; $i < $FG_NB_TABLE_COL; $i ++) { ?>

    <?php if ($FG_TABLE_COL [$i] [6] == "lie") {

                    $instance_sub_table = new Table ( $FG_TABLE_COL [$i] [7], $FG_TABLE_COL [$i] [8] );
                    $sub_clause = str_replace ( "%id", $recordset [$i], $FG_TABLE_COL [$i] [9] );
                    $select_list = $instance_sub_table->Get_list ( $DBHandle, $sub_clause, null, null, null, null, null, null, null, 10);

                    $field_list_sun = preg_split('/,/', $FG_TABLE_COL [$i] [8] );
                    $record_display = $FG_TABLE_COL [$i] [10];

                    if (is_array($select_list)) {
                        for ($l = 1; $l <= count ( $field_list_sun ); $l ++) {
                            $record_display = str_replace ( "%$l", $select_list [0] [$l - 1], $record_display );
                        }
                    } else {
                        $record_display = $recordset [$i];
                    }
                } elseif ($FG_TABLE_COL[$i][6]=="lie_link") {
                    $instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
                    $sub_clause = str_replace ( "%id", $recordset [$i], $FG_TABLE_COL [$i] [9] );
                    $select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null, null, 10);
                    if (is_array($select_list)) {
                        $field_list_sun = preg_split('/,/',$FG_TABLE_COL[$i][8]);
                        $record_display = $FG_TABLE_COL[$i][10];
                        $link = $FG_TABLE_COL[$i][12]."?form_action=ask-edit&id=".$select_list[0][1];
                        for ($l=1;$l<=count($field_list_sun);$l++) {
                            $val = str_replace("%$l", $select_list[0][$l-1], $record_display);
                            $record_display = "<a href='$link'>$val</a>";
                        }
                    } else {
                        $record_display="";
                    }
                } elseif ($FG_TABLE_COL [$i] [6] == "list") {
                    $select_list = $FG_TABLE_COL [$i] [7];
                    $record_display = $select_list [$recordset [$i]] [0];

                } else {
                    $record_display = $recordset [$i];
                }

                if (is_numeric ( $FG_TABLE_COL [$i] [5] ) && (strlen ( $record_display ) > $FG_TABLE_COL [$i] [5])) {
                    $record_display = substr ( $record_display, 0, $FG_TABLE_COL [$i] [5] - 3 ) . "";

                }

                ?>
        <TD vAlign=top align="<?php echo $FG_TABLE_COL [$i] [3]?>" class=tableBody><?php
            if (isset ( $FG_TABLE_COL [$i] [11] ) && strlen ( $FG_TABLE_COL [$i] [11] ) > 1) {
                call_user_func ( $FG_TABLE_COL [$i] [11], $record_display );
            } elseif (strlen($record_display)>0) {
                echo stripslashes ( $record_display );
            } else {
                echo '&nbsp;';
            }
        ?></TD>
     <?php } ?>

    </TR>
    <?php } //foreach ($list as $recordset)
        if ($ligne_number < $FG_LIMITE_DISPLAY)
            $ligne_number_end = $ligne_number + 2;
        while ($ligne_number < $ligne_number_end) {
            $ligne_number ++;
            ?>
        <TR
        bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$ligne_number % 2]?>">
        <?php for ($i = 0; $i < $FG_NB_TABLE_COL; $i ++) { ?>
         <TD vAlign=top class=tableBody>&nbsp;</TD>
         <?php } ?>
         <TD align="center" vAlign=top class=tableBodyRight>&nbsp;</TD>
        </TR>

        <?php } //END_WHILE

                } else {
                    echo gettext ( "No data found !!!" );
                } //end_if
        ?>
        </TABLE>
        </td>
    </tr>
    <TR bgcolor="#ffffff">
        <TD class="bgcolor_005" height="16" style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px">
            <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                <TR>
                    <TD align="right"><SPAN class="fontstyle_003">
                    <?php if ($current_page > 0) { ?>
                    <img src="<?php echo Images_Path; ?>/fleche-g.gif"
                    width="5" height="10"> <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo ($current_page - 1)?><?php
                                    if (! is_null ( $letter ) && ($letter != "")) {
                                        echo "&letter=$letter";
                                    }
                                    echo "&entercustomer_num=$entercustomer_num&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&dsttype=$dsttype&srctype=$srctype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&dst=$dst&src=$src&clid=$clid&terminatecauseid=$terminatecauseid&choose_calltype=$choose_calltype&entercustomer=$entercustomer&enterprovider=$enterprovider&entertrunk=$entertrunk";
                                    ?>">
                    <?php echo gettext ( "Previous" ); ?> </a> - <?php } ?><?php echo ($current_page + 1); ?> / <?php echo $nb_record_max; ?>
                    <?php if ($current_page < $nb_record_max - 1) { ?>
                    - <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo ($current_page + 1)?><?php
                                    if (! is_null ( $letter ) && ($letter != "")) {
                                        echo "&letter=$letter";
                                    }
                                    echo "&entercustomer_num=$entercustomer_num&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&dsttype=$dsttype&srctype=$srctype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&dst=$dst&src=$src&clid=$clid&terminatecauseid=$terminatecauseid&choose_calltype=$choose_calltype&entercustomer=$entercustomer&enterprovider=$enterprovider&entertrunk=$entertrunk";
                                    ?>">
                    <?php echo gettext ( "Next" ); ?></a> <img src="<?php echo Images_Path; ?>/fleche-d.gif" width="5" height="10"> </SPAN>
                    <?php } ?>
                  </TD>
        </TABLE>
        </TD>
    </TR>
</table>

<!-- ** ** ** ** ** Part to display the GRAPHIC ** ** ** ** ** -->
<br>

<?php
if (is_array ( $list_total_day ) && count ( $list_total_day ) > 0) {

    $mmax = 0;
    $totalcall == 0;
    $totalminutes = 0;
    $totalsuccess = 0;
    $totalfail = 0;
    foreach ($list_total_day as $data) {
        if ($mmax < $data [1])
            $mmax = $data [1];
        $totalcall += $data [3];
        $totalminutes += $data [1];
        $totalcost += $data [2];
        $totalbuycost += $data [4];
        $totalsuccess += $data [5];
    }
    $max_fail = 0;
?>

</div>
<div class="col-md-12">

<!-- END TITLE GLOBAL MINUTES //-->

<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tbody>
        <tr>
            <td style="background: #000;color:#FFF;">
            <table border="0" cellspacing="1" cellpadding="2" width="100%">
                <tbody>
                    <tr>
                        <td align="center" class="bgcolor_019"></td>
                        <td class="bgcolor_020" align="center" colspan="10"><font
                            class="fontstyle_003"><?php echo gettext ( "TRAFFIC SUMMARY" ); ?></font></td>
                    </tr>
                    <tr class="bgcolor_019">
                        <td align="center" class="bgcolor_020"><font class="fontstyle_003"><?php echo gettext ( "DATE" ); ?></font></td>
                        <td align="center"><font class="fontstyle_003"><acronym
                            title="<?php echo gettext ( "DURATION" ); ?>"><?php	echo gettext ( "DUR" );	?></acronym></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "GRAPHIC" ); ?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "CALLS" ); ?></font></td>
                        <td align="center"><font class="fontstyle_003"><acronym
                            title="<?php echo gettext ( "AVERAGE LENGTH OF CALL" );	?>"><?php echo gettext ( "ALOC" );	?></acronym></font></td>
                        <td align="center"><font class="fontstyle_003"><acronym
                            title="<?php echo gettext ( "ANSWER SEIZE RATIO" );	?>"><?php echo gettext ( "ASR" ); ?></acronym></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "SELL" );	?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "BUY" );	?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "PROFIT" );	?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "MARGIN" );	?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "MARKUP" );	?></font></td>

                        <!-- LOOP -->
    <?php
    $i = 0;
    $j = 0;
    foreach ($list_total_day as $data) {
        $i = ($i + 1) % 2;
        $tmc = $data [1] / $data [3];

        if ((! isset ( $resulttype )) || ($resulttype == "min")) {
            $tmc = sprintf ( "%02d", intval ( $tmc / 60 ) ) . ":" . sprintf ( "%02d", intval ( $tmc % 60 ) );
        } else {

            $tmc = intval ( $tmc );
        }

        if ((! isset ( $resulttype )) || ($resulttype == "min")) {
            $minutes = sprintf ( "%02d", intval ( $data [1] / 60 ) ) . ":" . sprintf ( "%02d", intval ( $data [1] % 60 ) );
        } else {
            $minutes = $data [1];
        }
        if ($mmax > 0)
            $widthbar = intval ( ($data [1] / $mmax) * 150 );
        ?>
        </tr>
                    <tr>
                        <td align="right" class="sidenav" nowrap="nowrap"><font
                            class="fontstyle_003"><?php
        echo $data [0]?></font></td>
                        <td bgcolor="<?php
        echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php
        echo $minutes?> </font></td>
                        <td bgcolor="<?php
        echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="left" nowrap="nowrap" width="<?php
        echo $widthbar + 40?>">
                        <table cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td bgcolor="#e22424"><img
                                        src="<?php
        echo Images_Path;
        ?>/spacer.gif"
                                        width="<?php echo $widthbar?>" height="6"></td>
                                </tr>
                            </tbody>
                        </table>
                        </td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font style="color:black;"><?php echo $data [3]?></font></td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font style="color:black;"><?php echo $tmc?> </font></td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font style="color:black;"><?php display_2dec_percentage ( $data [5] * 100/ ($data [3]) )?> </font></td>
                        <!-- SELL -->
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font style="color:black;"><?php display_2bill ( $data [2] )?>
                        </font></td>
                        <!-- BUY -->
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font style="color:black;"><?php display_2bill ( $data [4] )?>
                        </font></td>
                        <!-- PROFIT -->
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font style="color:black;"><?php display_2bill ( $data [2] - $data [4] )?>
                        </font></td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font style="color:black;"><?php
                            if ($data [2] != 0) {
                                display_2dec_percentage ( (($data [2] - $data [4]) / $data [2]) * 100 );
                            } else {
                                echo "NULL";
                            }
                            ?>
                        </font></td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font style="color:black;"><?php
                            if ($data [4] != 0) {
                                display_2dec_percentage ( (($data [2] - $data [4]) / $data [4]) * 100 );
                            } else {
                                echo "NULL";
                            }
                            ?>
                        </font></td>
                 <?php
                    $j ++;
                }

                if ((! isset ( $resulttype )) || ($resulttype == "min")) {
                    $total_tmc = sprintf ( "%02d", intval ( ($totalminutes / $totalcall) / 60 ) ) . ":" . sprintf ( "%02d", intval ( ($totalminutes / $totalcall) % 60 ) );
                    $totalminutes = sprintf ( "%02d", intval ( $totalminutes / 60 ) ) . ":" . sprintf ( "%02d", intval ( $totalminutes % 60 ) );
                } else {
                    $total_tmc = intval ( $totalminutes / $totalcall );
                }

                ?>
                </tr>
                    <!-- END DETAIL -->

                    <!-- END LOOP -->

                    <!-- TOTAL -->
                    <tr bgcolor="black">
                        <td align="right" nowrap="nowrap"><font class="fontstyle_003"><?php
                        echo gettext ( "TOTAL" );
                        ?></font></td>
                        <td align="center" nowrap="nowrap" colspan="2"><font
                            class="fontstyle_003"><?php echo $totalminutes?> </font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php echo $totalcall?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php echo $total_tmc?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php display_2dec_percentage ( $totalsuccess*100 / $totalcall )?> </font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php display_2bill ( $totalcost )?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php display_2bill ( $totalbuycost )?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php display_2bill ( $totalcost - $totalbuycost )?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php
                            if ($totalcost != 0) {
                                display_2dec_percentage ( (($totalcost - $totalbuycost) / $totalcost) * 100 );
                            } else {
                                echo "NULL";
                            }
                            ?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php
                            if ($totalbuycost != 0) {
                                display_2dec_percentage ( (($totalcost - $totalbuycost) / $totalbuycost) * 100 );
                            } else {
                                echo "NULL";
                            }
                            ?></font></td>
                    </tr>
                    <!-- END TOTAL -->

                </tbody>
            </table>
            <!-- END ARRAY GLOBAL //--></td>
        </tr>
    </tbody>
</table>
</div>
 <?php
// Code for the Export Functionality
/* $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";

if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";

if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";
   echo  $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]; */
    ?>
<br>
<!-- SECTION EXPORT //--> &nbsp; &nbsp;

<div class="col-md-12">

 <a class="md-btn md-btn-success md-btn-mini " href="export_csv.php?var_export=<?php echo FG_EXPORT_SESSION_VAR ?>&var_export_type=type_csv" target="_blank" >
                         
                         <i class="uk-icon-file-excel-o"></i>
                         <?php echo gettext("Export CSV");?></a>
- &nbsp; &nbsp;
<a class="md-btn md-btn-success md-btn-mini " href="export_csv.php?var_export=<?php echo FG_EXPORT_SESSION_VAR ?>&var_export_type=type_xml" target="_blank" >
                         
                         <i class="uk-icon-file-code-o"></i>
                         <?php echo gettext("Export XML");?></a>
<a class="md-btn md-btn-success md-btn-mini " href="export_pdf.php?var_export=<?php echo FG_EXPORT_SESSION_VAR ?>&var_export_type=type_pdf" target="_blank" >
                        
                         <i class="uk-icon-file-pdf-o"></i>
                         <?php echo gettext("Export PDF");?></a>
<?php } else { ?>

</div>
<div class="col-md-12">
<center>
<h3><?php echo gettext ( "No calls in your selection");?>.</h3>
<?php  } ?>
</center>
</div>



</div>
</div>
</div>
</div>

<?php
    $smarty->display('footer.tpl');
