<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CALL_REPORT)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'lst_time','trunks'));

$DBHandle  = DbConnect();
$instance_table = new Table();

//     Initialization of variables	///////////////////////////////

$condition = "";
$QUERY = '';
$ALOC = 0;
$ASR = 0;
$CIC = 0;
$Total_calls = 0;
$CIC_TIME_DIFF = 10;
$from_to = '';
$bool = false;

//     Generating WHERE CLAUSE		///////////////////////////////
normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);

if ($Period=="Time" && $lst_time != "") {
    if (strlen($condition)>0) $condition.=" AND ";
    switch ($lst_time) {
        case 1:
            $condition .= "DATE_SUB(NOW(),INTERVAL 1 HOUR) <= (c.starttime)";
        break;
        case 2:
            $condition .= "DATE_SUB(NOW(),INTERVAL 6 HOUR) <= (c.starttime)";
        break;
        case 3:
            $condition .= "DATE_SUB(NOW(),INTERVAL 1 DAY) <= (c.starttime)";
        break;
        case 4:
            $condition .= "DATE_SUB(NOW(),INTERVAL 7 DAY) <= (c.starttime)";
        break;
    }
} elseif ($Period=="Day" && $fromday && $today) {
    if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) {
        if (strlen($condition)>0) $condition.=" AND ";
        $condition.=" UNIX_TIMESTAMP(c.starttime) >= UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
    }
    if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) {
        if (strlen($condition)>0) $condition.=" AND ";
        $condition.=" UNIX_TIMESTAMP(c.starttime) <= UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
    }
} else {
    $bool = true;
    $condition .= "DATE_SUB( NOW( ) , INTERVAL 1 DAY ) <= c.starttime";
}

if ($trunks != "") {
    if (strlen($condition) > 0 && !$bool) {
        $condition .=" AND ";
        $condition .="c.id_trunk = '$trunks'";
    } else {
        $condition ="c.id_trunk = '$trunks'";
    }
}
///////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////
//     QUERIES FOR GETTING ALOC AND CIC  //////////////////////////
///////////////////////////////////////////////////////////////////

$QUERY_ALOC = "SELECT (SUM( TIME_TO_SEC( TIMEDIFF( c.stoptime, c.starttime ) ) ) / count( c.id ) ) AS ALOC, count( c.id ) AS total_calls FROM cc_call c WHERE ". $condition;
$QUERY_CIC = "SELECT count( c.id ) AS CIC FROM cc_call c WHERE TIME_TO_SEC( TIMEDIFF( c.stoptime, c.starttime ) ) <= $CIC_TIME_DIFF AND ". $condition;
$res_ALOC  = $instance_table->SQLExec ($DBHandle, $QUERY_ALOC);
foreach ($res_ALOC as $val) {
    $ALOC =  $val[0];
    $Total_calls = $val[1];
}

$res_CIC  = $instance_table->SQLExec ($DBHandle, $QUERY_CIC);
foreach ($res_CIC as $val) {
    $CIC =  $val[0];
}

///////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////
//     QUERIES FOR GETTING ASR      ///////////////////////////////
///////////////////////////////////////////////////////////////////

if ($Total_calls > 0) {
    $QUERY_ASR = "SELECT (count( c.id ) / $Total_calls) AS ASR FROM cc_call c WHERE c.terminatecauseid = 1 AND ". $condition;
    $res_ASR  = $instance_table->SQLExec ($DBHandle, $QUERY_ASR);
    foreach ($res_ASR as $val) {
        $ASR =  $val[0];
    }
} else {
    $ASR = 0;
}

///////////////////////////////////////////////////////////////////

if ($ASR == NULL) {
    $ASR = 0;
}

// #### HEADER SECTION
$smarty->display('main.tpl');

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Reports                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Reports                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                             Trunk & Traffic                      </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_trunk_report.php?section=5" class="kt-subheader__breadcrumbs-link">
                             Trunk Reports               </a>
                                        <!--			
									<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
	
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h1 class="kt-portlet__head-title">
            <?php echo gettext('Trunk Reports'); ?>
	      </h1>
        </div>
    </div>
	<br>

<div align="center">
<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
    <INPUT TYPE="hidden" NAME="posted" value=1>
    <INPUT TYPE="hidden" NAME="current_page" value=0>
        <table class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
            <tbody>
            <tr>
                <td align="left" class="" >
                    <input type="radio" name="Period" value="Day" <?php  if ($Period=="Day" or $Period=="") { ?>checked="checked" <?php  } ?>>
                    <font class=""><?php echo gettext("Select Day");?></font>
                </td>
                  <td align="left" class="bgcolor_003">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
					</tr>
					<tr>
						<td> &nbsp;</td>
					</tr>
					<tr>
					<td class="fontstyle_searchoptions"> 
                    <input type="checkbox" name="today" value="true" <?php  if ($today) { ?>checked<?php }?>>
                    <?php echo gettext("To  ");?>  : &nbsp; &nbsp; &nbsp;
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
                    </td></tr></table>
                  </td>
            </tr>
            <tr>
                <td class="bgcolor_004" align="left">
                <input type="radio" name="Period" value="Time" <?php  if (($Period=="Time")) { ?>checked="checked" <?php  } ?>>
                    <font class="fontstyle_003"><?php echo gettext("Select Time");?></font>
                </td>
                <td class="bgcolor_005" align="left">
                <select name="lst_time" class="form-control" style="width:91%;">
                <option value="" selected>Select Time</option>
                <option value="1" <?php if ($lst_time == 1) echo "selected"?>>Last 1 hour</option>
                <option value="2" <?php if ($lst_time == 2) echo "selected"?>>Last 6 hours</option>
                <option value="3" <?php if ($lst_time == 3) echo "selected"?>>Last day</option>
                <option value="4" <?php if ($lst_time == 4) echo "selected"?>>Last week</option>
                </select>
                </td>
            </tr>
            <tr>
                <td class="" align="left">
                    <font class="">&nbsp;&nbsp;<?php echo gettext("Select Trunk");?></font>
                </td>
                <td class="bgcolor_003" align="left">
                <?php
                $QUERY = "SELECT id_trunk, trunkcode from cc_trunk";
                $list_trunks  = $instance_table->SQLExec ($DBHandle, $QUERY);
                 ?>
                <select name="trunks" class="form-control" style="width:91%;">
                <option value="" selected ><?php echo gettext("Select Trunk");?></option>
                <?php
                foreach ($list_trunks as $val) {
                ?>
                <option value="<?php echo $val[0]?>" <?php if($trunks == $val[0]) echo "selected"?>><?php echo $val[1]?></option>
                <?php
                }
                ?></select>
                </td>
            </tr>

            <tr>
                <td class="bgcolor_004" align="left" > </td>

                <td class="bgcolor_005" align="right" >
                    <input type="submit"  name="image16" align="top" value="Submit" border="0" class="btn btn-primary  btn-small btn-wave-light"/>

                  </td>
            </tr>
        </tbody></table>
</FORM>

<br/>
            <table border="0" cellpadding="2" cellspacing="2" align="center" class="table">
                <tbody>
                <?php $num = 1; if ($num > 0) {?>
                    <tr class="form_head">
                     <th class="tableBody" style="padding: 2px;" align="center" width="4%">
                        <strong>
                            <font >ASR</font>
                        </strong>
                    </th>
                     <th class="tableBody" style="padding: 2px;" align="center" width="4%">
                        <strong>
                            <font >ALOC</font>
                        </strong>
                    </th>
                     <th class="tableBody" style="padding: 2px;" align="center" width="4%">
                        <strong>
                            <font >CIC</font>
                        </strong>
                    </th>
                     <th class="tableBody" style="padding: 2px;" align="center" width="4%">
                        <strong>
                            <font >Total Calls</font>
                        </strong>
                    </th>

                </tr>
                <?php
                $i=0;
                if ($i % 2 == 0) {
                    $bgcolor = "bgcolor='#Ffffff'";$mouseout = "bgColor='#ffffff'";} else {$bgcolor = "bgcolor='#FCFBFB'";$mouseout = "bgColor='#FCFBFB'";
                }
                ?>
                    <tr onmouseover="bgColor='#FFDEA6'" onmouseout=<?php echo $mouseout?> <?php echo $bgcolor?>>
                    <td class="tableBody" align="center" valign="top"><?php echo $ASR?></td>
                    <td class="tableBody" align="center" valign="top"><?php echo round($ALOC)?>&nbsp;sec</td>
                    <td class="tableBody" align="center" valign="top"><?php echo $CIC?></td>
                    <td class="tableBody" align="center" valign="top"><?php echo $Total_calls?></td>
                    </tr>
                     
                <tr>
                    <td class="tableDivider" colspan="4"><img src="../Public/templates/default/images/clear.gif" height="1" width="1"></td>
                </tr>
            <?php } else {?>
                <tr>
                    <td colspan="5" align="center">No Record Found!</td>
                </tr>
            <?php }?>
            </tbody>
</table>
</div>
<?php

$smarty->display('footer.tpl');
