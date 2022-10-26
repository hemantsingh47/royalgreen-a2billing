<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_statuslog.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_CUSTOMER)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array (
    'id_cc_card'
));

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

if ($id != "" || !is_null($id)) {
    $HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
    $form_action = "list"; //ask-add
if (!isset ($action))
    $action = $form_action;

$list = $HD_Form->perform_action($form_action);

$smarty->display('main.tpl');

echo $CC_help_status_log;

$HD_Form->create_toppage($form_action);

?>
<div class="kt-portlet">
	<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h5 class="kt-portlet__head-title">
           <span class="icon"> <i class="flaticon-list-2"></i> </span> <?php echo gettext("Card Status");?>
	    </h5>
        </div>
	</div>
        
    <div class="kt-portlet__body">   
		<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>" class="kt-form">
		<script src="templates/default/newtheme/theme/classic/assets/js/demo12/pages/crud/metronic-datatable/advanced/row-details.js" type="text/javascript"></script>

    <INPUT TYPE="hidden" NAME="posted" value="1">
    <INPUT TYPE="hidden" NAME="current_page" value="0">
    <?php
        if ($HD_Form->FG_CSRF_STATUS == true) {
    ?>
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
    <?php
        }
    ?>
    <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
        <tbody>
        <?php  if ($_SESSION["pr_groupID"]==2 && is_numeric($_SESSION["pr_IDCust"])) { ?>
        <?php  } else { ?>
        <tr>
            <td align="right" valign="top" class="bgcolor_000">
                <label class="col-12 col-form-label"><?php echo gettext("CUSTOMERS");?></label>
            </td>
            <td class="bgcolor_005" align="left">
            <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
                <td class="fontstyle_searchoptions" >
                <INPUT TYPE="text" NAME="id_cc_card" value="<?php echo $id_cc_card?>" class="form_input_text">
                <a href="#" onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=myForm&popup_fieldname=id_cc_card' , 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a>
                </td>
            </tr></table></td>
        </tr>
        <?php  } ?>
        <tr>
            <td class=" dt-right" align="left">
				<label class="kt-radio">
                <input type="radio" name="Period" value="Month" <?php  if (($Period=="Month") || !isset($Period)) { ?>checked="checked" <?php  } ?>>
                <label class="col-12 col-form-label"><?php echo gettext("SELECT MONTH");?></label>
				<span></span>
				</label>
            </td>
              <td class="bgcolor_003" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td class="fontstyle_searchoptions">
                  <input type="checkbox" name="frommonth" value="true" <?php  if ($frommonth) { ?>checked<?php }?>>
                <?php echo gettext("From");?> : 
				<select name="fromstatsday_sday" class="form-control" style="float:right; width:70%;">
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
                           if ($fromstatsmonth=="$i-$month_formated")	$selected="selected";
                        else $selected="";
                        echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                       }
                    }
                ?>
                </select>
                </td><td  class="fontstyle_searchoptions">&nbsp;&nbsp;
                <input type="checkbox" name="tomonth" value="true" <?php  if ($tomonth) { ?>checked<?php }?>>
                <?php echo gettext("To");?> :
				<select name="tostatsmonth" class="form-control" style="float:right; width:70%;">
                <?php 	$year_actual = date("Y");
                    for ($i=$year_actual;$i >= $year_actual-1;$i--) {
                       if ($year_actual==$i) {
                        $monthnumber = date("n")-1; // Month number without lead 0.
                       } else {
                        $monthnumber=11;
                       }
                       for ($j=$monthnumber;$j>=0;$j--) {
                        $month_formated = sprintf("%02d",$j+1);
                           if ($tostatsmonth=="$i-$month_formated") $selected="selected";
                        else $selected="";
                        echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                       }
                    }
                ?>
                </select>
                </td></tr></table>
              </td>
        </tr>

        <tr>
            <td align="left" class="bgcolor_004">
                <label class="kt-radio">
				<input type="radio" name="Period" value="Day" <?php  if ($Period=="Day") { ?>checked="checked" <?php  } ?>>
                
				<label class="col-12 col-form-label"><?php echo gettext("SELECT DAY");?></label>
				<span></span>
				</label>
            </td>
              <td align="left" class="bgcolor_005">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td class="fontstyle_searchoptions">
                  <input type="checkbox" name="fromday" value="true" <?php  if ($fromday) { ?>checked<?php }?>> <?php echo gettext("From");?> :
                <select name="fromstatsday_sday" class="form-control" style="float:right; width:20%;">
                    <?php
                    for ($i=1;$i<=31;$i++) {
                        if ($fromstatsday_sday==sprintf("%02d",$i)) $selected="selected";
                        else	$selected="";
                        echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
                    }
                    ?>
                </select>
                 <select name="fromstatsmonth_sday" class="form-control" style="float:right; width:50%;">
                <?php 	$year_actual = date("Y");
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
                </td><td class="fontstyle_searchoptions">&nbsp;&nbsp;
                <input type="checkbox" name="today" value="true" <?php  if ($today) { ?>checked<?php }?>>
                <?php echo gettext("To");?>  :
                <select name="tostatsday_sday" class="form-control" style="float:right; width:20%;">
                <?php
                    for ($i=1;$i<=31;$i++) {
                        if ($tostatsday_sday==sprintf("%02d",$i)) {$selected="selected";} else {$selected="";}
                        echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
                    }
                ?>
                </select>
                 <select name="tostatsmonth_sday" class="form-control" style="float:right; width:50%;">
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
            </td>
              <td class="bgcolor_003" align="left">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td class="fontstyle_searchoptions">

                <select name="status" class="form-control" style="float:center; width:30%;">
                <?php
                        echo "<OPTION value=\"-1\">" . gettext("SELECT STATUS") . "</option>";
                    foreach ($cardstatus_list as $status) {
                        echo "<OPTION value=\"$status[1]\"> $status[0] </option>";
                    }
                ?>
                </select>
                </td></tr></table>
              </td>
        </tr>
        <tr>
            <td class="bgcolor_004" align="left" > </td>
            <td class="bgcolor_003" align="center" >
                <input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/images.png" />

              </td>
        </tr>
    </tbody></table>
</FORM>
</div>
</div>
<?php
