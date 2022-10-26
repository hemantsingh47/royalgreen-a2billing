<?php


include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CALL_REPORT)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('inputtopvar','topsearch', 'posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'resulttype', 'stitle', 'atmenu', 'order', 'sens', 'choose_currency', 'terminatecauseid', 'nodisplay','grouped'));

if (!isset ($FG_TABLE_CLAUSE) || strlen($FG_TABLE_CLAUSE)==0) {
    $cc_yearmonth = sprintf("%04d-%02d-%02d",date("Y"),date("n"),date("d"));
    $FG_TABLE_CLAUSE=" UNIX_TIMESTAMP(starttime) <= UNIX_TIMESTAMP('$cc_yearmonth')";
}

$FG_DEBUG = 0;

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";

$DBHandle = DbConnect();

if ($order=="day" && $grouped==0) {
    $order="";
}

$FG_TABLE_COL = array();
switch ($topsearch) {
    case "topdestination":
        $FG_TABLE_COL[]=array (gettext("Destination"), "destination", "25%", "center", "SORT", "15", "lie", "cc_prefix", "destination", "id='%id'", "%1");
        $on_field1 = "cc_prefix.destination";
        $on_field2 = "destination";
        $FG_TABLE_DEFAULT_ORDER = "cc_prefix.destination";
        $FG_TABLE_NAME="cc_call LEFT JOIN cc_prefix ON cc_call.destination = cc_prefix.prefix";
        if($order=="card_id" || empty ($order))$order="destination";
        break;
    case "topuser":
    default:
        $FG_TABLE_COL[]=array (gettext("Account Used"), 'card_id', "25%", "center","SORT", "", "30", "", "", "", "", "linktocustomer_id");
        $on_field1 = $on_field2 = "card_id";
        $FG_TABLE_DEFAULT_ORDER = "card_id";
        $FG_TABLE_NAME="cc_call";
        if($order=="destination" || empty ($order))$order="card_id";
        break;
}

$FG_TABLE_COL[]=array (gettext("Duration"), "calltime", "15%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$FG_TABLE_COL[]=array (gettext("Sell"), "cost", "15%", "center","sort","","","","","","","display_2bill");
$FG_TABLE_COL[]=array (gettext("Buy"), "buy", "15%", "center","sort","","","","","","","display_2bill");
if ($grouped) $FG_TABLE_COL[]=array (gettext("Calldate"), "day", "10%", "center", "SORT", "19", "", "", "", "", "", "display_dateformat");
if ((isset($inputtopvar)) && ($inputtopvar!="") && (isset($topsearch)) && ($topsearch!="")) {
    $FG_TABLE_COL[]=array (gettext("NbrCall"), 'nbcall', "10%", "center", "SORT");
}

if ($grouped) {
    $FG_COL_QUERY=$on_field1.', sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy,DATE(starttime) AS day, count(*) as nbcall';
    $SQL_GROUP=" GROUP BY ".$on_field2.",DATE(starttime) ";
} else {
    $FG_COL_QUERY=$on_field1.', sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy, count(*) as nbcall';
    $SQL_GROUP=" GROUP BY ".$on_field2." ";
}

$FG_TABLE_DEFAULT_SENS = "DESC";

$FG_NB_TABLE_COL=count($FG_TABLE_COL);
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
$FG_HTML_TABLE_TITLE = gettext(" - Call Report - ");
$FG_HTML_TABLE_WIDTH="96%";

if ( empty ($order) || empty($sens) || ( $order == 'card_id' && $topsearch == 'topdestination') || ( $order == 'destination' && $topsearch == 'topuser')) {
    $order = $FG_TABLE_DEFAULT_ORDER;
    $sens  = $FG_TABLE_DEFAULT_SENS;
}

$date_clause='';
normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);

if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND UNIX_TIMESTAMP(starttime) >= UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday') ";
if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) $date_clause.=" AND UNIX_TIMESTAMP(starttime) <= UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59') ";

if (strpos($date_clause, 'AND') > 0) {
    $FG_TABLE_CLAUSE = substr($date_clause,5);
}

// To select just terminatecauseid=ANSWER
if (!isset($terminatecauseid)) {
    $terminatecauseid="ANSWER";
}
if ($terminatecauseid=="ANSWER") {
    if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
    $FG_TABLE_CLAUSE .=" (terminatecauseid=1) ";
}

$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);

if (!$nodisplay) {
    $list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null,$inputtopvar , 0,$SQL_GROUP);
}

$smarty->display('main.tpl');

?>

<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
 <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Reports                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Reports                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_def_ratecard.php?atmenu=ratecard&section=6" class="kt-subheader__breadcrumbs-link">
                            Call Reports                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="call-count-reporting.php?nodisplay=1&posted=1&section=5" class="kt-subheader__breadcrumbs-link">
                            Call Count Reporting                        </a>
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
				<?php echo gettext("Call Count Reporting"); ?>
			</h1>
		</div>
	</div>
	
	
    <center>
	
	
	
    <FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>" class="kt-form">
    <INPUT TYPE="hidden" NAME="posted" value=1>
    <INPUT TYPE="hidden" NAME="current_page" value=0>
       
<div class="col-md-12">

	   <table class="table widget-box" width="100%" border="0" cellspacing="1" cellpadding="2" class="table widget-box" style="    border-bottom: 1px solid #CDCDCD;">

            <tr>
                <td class="widget-title" colspan="2" style="border-top: 1px solid #CDCDCD; padding: 0px;">
                   
					
					<label class="control-label" style="margin-bottom: 0px;"><?php echo gettext("DATE");?></label>
            </td>
			</tr>
			<tr>
                  <td class="fontstyle_searchoptions">
                      <input type="checkbox" name="fromday" value="true" <?php  if ($fromday) { ?>checked<?php }?>> <?php echo gettext("From");?> :
                    <select name="fromstatsday_sday" class="form-control11">
                        <?php
                        for ($i=1;$i<=31;$i++) {
                            if ($fromstatsday_sday==sprintf("%02d",$i)) $selected="selected";
                            else	$selected="";
                            echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
                        }
                        ?>
                    </select>
                     <select name="fromstatsmonth_sday" class="form-control13">
                    <?php
                        $monthname = array( gettext("January"), gettext("February"),gettext("March"), gettext("April"), gettext("May"), gettext("June"), gettext("July"), gettext("August"), gettext("September"), gettext("October"), gettext("November"), gettext("December"));
                        $year_actual = date("Y");
                        for ($i=$year_actual;$i >= $year_actual-1;$i--) {
                            if ($year_actual==$i) {
                                $monthnumber = date("n")-1; // Month number without lead 0.
                            } else {
                                $monthnumber=11;
                            }
                            for ($j=$monthnumber;$j>=0;$j--) {
                                $month_formated = sprintf("%02d",$j+1);
                                if ($fromstatsmonth_sday=="$i-$month_formated") $selected="selected";
                                else $selected="";
                                echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                            }
                        }
                    ?>
                    </select>
                    </td>
					<td>
                    <input type="checkbox" name="today" value="true" <?php  if ($today) { ?>checked<?php }?>> <?php echo gettext("To");?>  :
                    <select name="tostatsday_sday" class="form-control11">
                    <?php
                        for ($i=1;$i<=31;$i++) {
                            if ($tostatsday_sday==sprintf("%02d",$i)) {$selected="selected";} else {$selected="";}
                            echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
                        }
                    ?>
                    </select>
                     <select name="tostatsmonth_sday" class="form-control13">
                    <?php 	$year_actual = date("Y");
                        for ($i=$year_actual;$i >= $year_actual-1;$i--) {
                            if ($year_actual==$i) {
                                $monthnumber = date("n")-1; // Month number without lead 0.
                            } else {
                                $monthnumber=11;
                            }
                            for ($j=$monthnumber;$j>=0;$j--) {
                                $month_formated = sprintf("%02d",$j+1);
                                   if ($tostatsmonth_sday=="$i-$month_formated") $selected="selected";
                                else	$selected="";
                                echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                            }
                        }
                    ?>
                    </select>
                    
                  </td>
            </tr>
			</table>
			</div>
			
			<div class="col-md-12">
			
			<table class="table widget-box" width="100%" border="0" cellspacing="1" cellpadding="2" class="table widget-box" style="    border-bottom: 1px solid #CDCDCD;">
			 
        <tr>
            <td class="widget-title" style="border-top: 1px solid #CDCDCD; padding: 0px;">
                   
					
					<label class="control-label" style="margin-bottom: 0px;">
				
				<?php echo strtoupper(gettext("Number in Result"));?></label>
            </td>
			
			</tr>
			<tr>
             
                    <td  align="left" class="fontstyle_searchoptions" >
                        <select name="inputtopvar" class="form-control14">
                        <option value="10" <?php if($inputtopvar==10) echo "selected"?>> 10&nbsp;<?php echo gettext('RESULTS');?>  </option>
                        <option value="20" <?php if($inputtopvar==20) echo "selected"?>> 20&nbsp;<?php echo gettext('RESULTS');?>   </option>
                        <option value="30" <?php if($inputtopvar==30) echo "selected"?>> 30&nbsp;<?php echo gettext('RESULTS');?>   </option>
                        <option value="40" <?php if($inputtopvar==40) echo "selected"?>> 40&nbsp;<?php echo gettext('RESULTS');?>   </option>
                        <option value="50" <?php if($inputtopvar==50) echo "selected"?>> 50&nbsp;<?php echo gettext('RESULTS');?>   </option>
                        </select>
                    </td>
                    
            
         </tr>
		 </table>
		 
		 </div>
		 
		 <div class="col-md-12">
			
			<table class="table widget-box" width="100%" border="0" cellspacing="1" cellpadding="2" class="table widget-box" style="    border-bottom: 1px solid #CDCDCD;">
		 
            <!-- Select Option : to show just the Answered Calls or all calls, Result type, currencies... -->
            <tr>
              <td class="widget-title" colspan="8" style="border-top: 1px solid #CDCDCD; padding: 0px;"> <label class="control-label" style="margin-bottom: 0px;"><?php echo gettext("OPTIONS");?></label></td>
					
					</tr>
					<tr>
              <td class="bgcolor_003" align="center" >
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td width="35%" class="fontstyle_searchoptions" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr class="bgcolor_005">
                    <td  class="fontstyle_searchoptions">
                        <label class="col-12 col-form-label"><?php echo gettext("GROUP BY");?> :</label>
                   </td>
                   <td  class="fontstyle_searchoptions">
                           <label class="col-form-label"><?php echo gettext("Calls by user");?></label>
                           <input type="radio" name="topsearch" value="topuser" <?php if ($topsearch=="topuser" || empty($topsearch)) { ?> checked="checked" <?php  } ?>> &nbsp; &nbsp;
&nbsp; &nbsp;                           <label class="col-form-label"><?php echo gettext("Calls by destination");?></label>
                           <input type="radio" name="topsearch" value="topdestination" <?php if ($topsearch=="topdestination") { ?> checked="checked" <?php  } ?>>
                   </td>
                </tr>
                <tr>
                    <td width="20%"  class="fontstyle_searchoptions">
                        <label class="col-12 col-form-label"><?php echo gettext("SHOW");?> :</label>
                   </td>
                   <td width="80%"  class="fontstyle_searchoptions">
					<label class="col-form-label"><?php echo gettext("Answered Calls")?> </label>
                  <input name="terminatecauseid" type="radio" value="ANSWER" <?php if ((!isset($terminatecauseid))||($terminatecauseid=="ANSWER")) {?>checked<?php }?> /> &nbsp; &nbsp;
&nbsp; &nbsp;  
                  <label class="col-form-label"><?php echo gettext("All Calls")?></label>
                   <input name="terminatecauseid" type="radio" value="ALL" <?php if ($terminatecauseid=="ALL") {?>checked<?php }?>/>
                    </td>
                </tr>
                <tr class="bgcolor_005">
                    <td  class="fontstyle_searchoptions">
                        <label class="col-12 col-form-label"><?php echo gettext("GROUP BY DAY");?> :</label>
                   </td>
                   <td>
                   <label class="col-form-label"><?php echo gettext("Yes")?></label>
                  <input name="grouped" type="radio" value="1" <?php if ($grouped) {?>checked<?php }?> /> &nbsp; &nbsp;
&nbsp; &nbsp;  
                  <label class="col-form-label"><?php echo gettext("NO")?></label>
                  <input name="grouped" type="radio" value="0" <?php if ((!isset($grouped))||(!$grouped)) {?>checked<?php }?>/>
                    </td>
                </tr>
                <tr>
                    <td  >
                        <label class="col-12 col-form-label"><?php echo gettext("RESULT");?> :</label>
                    </td>
                    <td  >

                    <label class="col-form-label"><?php echo gettext("Mins");?> <input type="radio" NAME="resulttype" value="min" <?php if ((!isset($resulttype))||($resulttype=="min")){?>checked<?php }?>> &nbsp; &nbsp;
&nbsp; &nbsp;  - <?php echo gettext("Secs")?> <input type="radio" NAME="resulttype" value="sec" <?php if ($resulttype=="sec") {?>checked<?php }?>></label>

                    </td>
                </tr>
                <tr class="bgcolor_005">
                    <td  class="fontstyle_searchoptions">
                        <label class="col-12 col-form-label"><?php echo gettext("CURRENCY");?> :</label>
                   </td>
                   <td >
                <select NAME="choose_currency" size="1" class="form-control14">
                            <?php
                                $currencies_list = get_currencies();
                                foreach ($currencies_list as $key => $cur_value) {
                            ?>
                                <option value='<?php echo $key ?>' <?php if (($choose_currency==$key) || (!isset($choose_currency) && $key==strtoupper(BASE_CURRENCY))) {?>selected<?php } ?>><?php echo $cur_value[1].' ('.$cur_value[2].')' ?>
                                </option>
                            <?php 	} ?>
                        </select>
                    </td>
                </tr>
                </table>
               </td>
                </tr>
                </table>
              </td>
              </tr>
			  
			  </table>
			  </div>
			  
			  <div class="col-md-12">
			
			<table class="" width="100%" border="0" cellspacing="1" cellpadding="2" class="table widget-box" style="    border-bottom: 0px solid #CDCDCD;">
			  
            <!-- Select Option : to show just the Answered Calls or all calls, Result type, currencies... -->
            <tr>
                <td  align="left" > </td>
                <td  align="right" >
                    <input type="submit" class="btn btn-primary"  name="image16" align="top" border="0" value="<?php echo gettext("Search"); ?>" />
                  </td>
            </tr>
         </table>
		 </div>
    </FORM>
</center>
</div>
</div>

<!-- ** ** ** ** ** Part to display the CDR ** ** ** ** ** -->
<?php if (!$nodisplay) { ?>
<div class="row-fluid">
   <div class="widget-box">
   <div class="">
        <table width="<?php echo $FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0">
    <TR bgcolor="#ffffff">
        <TD class="bgcolor_021" height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px">
        <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
            <TR>
                <TD><SPAN class="fontstyle_003"><?php echo $FG_HTML_TABLE_TITLE?></SPAN></TD>
            </TR>
        </TABLE>
        </TD>
    </TR>
        <TR>
          <TD> <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                <TR class="bgcolor_008">

                  <?php
                      if (is_array($list) && count($list)>0) {

                      for ($i=0;$i<$FG_NB_TABLE_COL;$i++) {
                    ?>

                  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle class="tableBody" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px">
                    <center><strong>
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT") {?>
                    <a href="<?php  echo $PHP_SELF."?s=1&t=0&stitle=$stitle&atmenu=$atmenu&current_page=$current_page&order=".$FG_TABLE_COL[$i][1]."&sens="; if ($sens=="ASC") {echo"DESC";} else {echo"ASC";}
                    echo "&topsearch=$topsearch&inputtopvar=$inputtopvar&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&resulttype=$resulttype&terminatecauseid=$terminatecauseid&grouped=$grouped";?>">
                    <span class="liens"><?php  } ?>
                    <?php echo $FG_TABLE_COL[$i][0]?>
                    <?php if ($order==$FG_TABLE_COL[$i][1] && $sens=="ASC") {?>
                    &nbsp;<img src="<?php echo Images_Path;?>/icon_up_12x12.GIF" width="12" height="12" border="0">
                    <?php } elseif ($order==$FG_TABLE_COL[$i][1] && $sens=="DESC") {?>
                    &nbsp;<img src="<?php echo Images_Path;?>/icon_down_12x12.GIF" width="12" height="12" border="0">
                    <?php }?>
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT") {?>
                    </span></a>
                    <?php }?>
                    </strong></center></TD>
                   <?php } ?>
                </TR>

                <?php
                       $ligne_number=0;
                       foreach ($list as $recordset) {
                         $ligne_number++;
                ?>
                        <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>'">

                    <?php
                        for ($i=0;$i<$FG_NB_TABLE_COL;$i++) {

                            $record_display = $recordset[$i];
                            if ( is_numeric($FG_TABLE_COL[$i][5]) && (strlen($record_display) > $FG_TABLE_COL[$i][5])  ) {
                                $record_display = substr($record_display, 0, $FG_TABLE_COL[$i][5]-3)."";
                            } ?>
                              <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody><?php
                             if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1) {
                                     call_user_func($FG_TABLE_COL[$i][11], $record_display);
                             } else {
                                     echo stripslashes($record_display);
                             }
                         ?></TD>
                     <?php
                         } ?>
                    </TR>
                <?php
                     }//foreach ($list as $recordset)
                     if ($ligne_number < $FG_LIMITE_DISPLAY)
                         $ligne_number_end=$ligne_number +2;

                     while ($ligne_number < $ligne_number_end) {
                         $ligne_number++;
                ?>
                    <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>">
                          <?php
                              for ($i=0;$i<$FG_NB_TABLE_COL;$i++) {
                          ?>
                          <TD vAlign=top class=tableBody>&nbsp;</TD>
                          <?php  } ?>
                          <TD align="center" vAlign=top class=tableBodyRight>&nbsp;</TD>
                    </TR>

                <?php
                     } //END_WHILE

                  } else {
                          echo gettext("No data found !!!");
                  }//end_if
                 ?>

            </TABLE></td>
        </tr>

      </table>
</div>
</div>
</div>
<?php
}

$smarty->display('footer.tpl');
