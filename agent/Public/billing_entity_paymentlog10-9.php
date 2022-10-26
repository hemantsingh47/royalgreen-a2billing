<?php


include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_paymentlog.inc';
include '../lib/agent.smarty.php';

if (!has_rights(ACX_BILLING)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form->setDBHandler(DbConnect());

if ($form_action != "list" && isset ($id)) {
    if (!empty ($id) && $id > 0) {
        $table_agent_security = new Table("cc_epayment_log,cc_card LEFT JOIN cc_card_group ON cc_card.id_group=cc_card_group.id ", " cc_card_group.id_agent");
        $clause_agent_security = "cc_epayment_log.id= " . $id . "AND cc_card.id=cc_epayment_log.cardid";
        $result_security = $table_agent_security->Get_list($HD_Form->DBHandle, $clause_agent_security, null, null, null, null, null, null);
        if ($result_security[0][0] != $_SESSION['agent_id']) {
            Header("HTTP/1.0 401 Unauthorized");
            Header("Location: PP_error.php?c=accessdenied");
            die();
        }
    }
}

$HD_Form->init();

if ($id != "" || !is_null($id)) {
    $HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
    $form_action = "list"; //ask-add
if (!isset ($action))
    $action = $form_action;

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if ($form_action == 'list')
    echo $CC_help_payment_log;

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

?>
<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
    <INPUT TYPE="hidden" NAME="posted" value=1>
    <INPUT TYPE="hidden" NAME="current_page" value=0>
	
	<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Agent Payment List                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Payment Logs                        </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>
	
	
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		

 
    	<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h2 class="kt-portlet__head-title">
						Payment Log
					</h2>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					 
					<div class="kt-section__content">
						<table class="table">
            <tbody>
            <tr>
                <td  align="left" style="border:0; vertical-align: middle;">

                    <input type="radio" name="Period" value="Month" <?php  if (($Period=="Month") || !isset($Period)) { ?>checked="checked" <?php  } ?>>
                    <font class="fontstyle_003"><?php echo gettext("SELECT MONTH");?></font>
                </td>
                  <td class="bgcolor_003" align="left" style="border:0;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr><td class="fontstyle_searchoptions" style="border:0;">
                      <input type="checkbox" name="frommonth" value="true" <?php  if ($frommonth) { ?>checked<?php }?>>
                    <?php echo gettext("From");?> : <select name="fromstatsmonth" class="form-control13">
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
                    </td><td  class="fontstyle_searchoptions" style="border:0;">&nbsp;&nbsp;
                    <input type="checkbox" name="tomonth" value="true" <?php  if ($tomonth) { ?>checked<?php }?>>
                    <?php echo gettext("To");?> : <select name="tostatsmonth" class="form-control13">
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
                <td align="left" style="vertical-align: middle;">
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
                    </td><td class="fontstyle_searchoptions" style="border:0;">&nbsp;&nbsp;
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
                <td align="left">
                    <font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("STATUS");?></font>
                </td>
                <td class="bgcolor_003" align="left" style="padding-left: 1.50rem;">
                <select name="status" class="form-control11">
                <option value="0" <?php if ($status == 0) echo "selected"?>>New</option>
                <option value="1" <?php if ($status == 1) echo "selected"?>>Proceed</option>
                <option value="2" <?php if ($status == 2) echo "selected"?>>In Progress</option>
                </select>
                </td>
            </tr>
            <tr>
                <td class="bgcolor_004" align="left" > </td>

                <td class="bgcolor_005" align="right" >
                    <input type="button"  name="image16" align="top" border="0" value="Search" class="btn btn-primary" />

                  </td>
            </tr>
        </tbody></table>
		
		
			 
		
</FORM>
<hr />
 <br><br>

<?php

$HD_Form -> create_form ($form_action, $list, $id=null) ;



// #### FOOTER SECTION
$smarty->display('footer.tpl');
