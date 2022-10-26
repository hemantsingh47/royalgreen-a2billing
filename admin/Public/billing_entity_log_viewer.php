<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_log_viewer.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_MAINTENANCE)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_list_log;

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

if ($form_action=="list") {
?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Others                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Others                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Tools                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_log_viewer.php?section=16" class="kt-subheader__breadcrumbs-link">
                            User Activity                      </a>
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
				<?php echo gettext("User Activity"); ?>
				
			</h1>
		</div>
	</div>
	<br>

<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>" class="kt-form">
    <INPUT TYPE="hidden" NAME="posted" value=1>
    <INPUT TYPE="hidden" NAME="current_page" value=0>
    <?php
        if ($HD_Form->FG_CSRF_STATUS == true) {
    ?>
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
    <?php
        }
    ?>
    <table class="table" border="0" cellspacing="1" cellpadding="2" align="center">
        <tbody>
        <?php  if ($_SESSION["pr_groupID"]==2 && is_numeric($_SESSION["pr_IDCust"])) { ?>
        <?php  } else { ?>
        <tr>
            <td align="left" valign="top" class="bgcolor_004" style="border:0">
                <font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CUSTOMERS");?></font>
            </td>
            <td class="bgcolor_005" align="left" style="border:0">
            <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
                <td class="fontstyle_searchoptions">
                     <INPUT TYPE="text" NAME="enteradmin" value="<?php echo $enteradmin?>" class="form-control" style="width:50%;">
                    <a href="#" onclick="window.open('billing_entity_card.php?popup_select=1&popup_formname=myForm&popup_fieldname=enteradmin' , 'AdminSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><i class="flaticon2-fast-next"></i></a>
                </td>
            </tr></table></td>
        </tr>
        <?php  }?>
        <tr>
            <td class="" align="left">

                <input type="radio" name="Period" value="Month" <?php  if (($Period=="Month") || !isset($Period)) { ?>checked="checked" <?php  } ?>>
                <font class="fontstyle_003"><?php echo gettext("SELECT MONTH");?></font>
            </td>
              <td class="bgcolor_003" align="left">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td class="fontstyle_searchoptions" style="border:0;">
                  <input type="checkbox" name="frommonth" value="true" <?php  if ($frommonth) { ?>checked<?php }?>>
                <?php echo gettext("From");?> : <select name="fromstatsmonth" class="form-control" style="width:85%;">
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
                </td>
				
				<td  class="fontstyle_searchoptions" style="border:0;">&nbsp;&nbsp;
                <input type="checkbox" name="tomonth" value="true" <?php  if ($tomonth) { ?>checked<?php }?>>
                <?php echo gettext("To");?> : <select name="tostatsmonth" class="form-control" style="width:85%;">
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
                <input type="radio" name="Period" value="Day" <?php  if ($Period=="Day") { ?>checked="checked" <?php  } ?>>
                <font class="fontstyle_003"><?php echo gettext("SELECT DAY");?></font>
            </td>
              <td align="left" class="bgcolor_005">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td class="fontstyle_searchoptions" style="border:0;">
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
                </td>
				 	
				<td class="fontstyle_searchoptions" style="border:0;">&nbsp;&nbsp;
                <input type="checkbox" name="today" value="true" <?php  if ($today) { ?>checked<?php }?>>
                <?php echo gettext("To");?>  :
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
            <td class="" align="left">
                <font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("LOG LEVEL");?></font>
            </td>
            <td class="bgcolor_003" align="left">
            <select name="loglevel" class="form-control13">
            <option value="0" <?php if ($loglevel == 0) echo "selected"?>>ALL</option>
            <option value="1" <?php if ($loglevel == 1) echo "selected"?>>Level-1</option>
            <option value="2" <?php if ($loglevel == 2) echo "selected"?>>Level-2</option>
            <option value="3" <?php if ($loglevel == 3) echo "selected"?>>Level-3</option>
            </select>
            </td>
        </tr>
        <tr>
            <td class="bgcolor_004" align="left" > </td>

            <td class="bgcolor_005" align="right" >
                <input type="submit"  name="image16" align="top" border="0" class="btn btn-primary" value="Search" />

              </td>
        </tr>
    </tbody></table>
</FORM>

<?php
}
$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');
