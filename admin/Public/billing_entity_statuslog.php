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
<?php
    
?>
 
<!-- Start of the code-->
  <style type="text/css">
 input, textarea, .uneditable-input 
 {
    width: auto;  
 }
</style> 

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Card Status                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            User                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Status                        </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
	<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h5 class="kt-portlet__head-title">
           <?php echo gettext("Customer Card Status");?>
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
    <table class="table table-bordered" border="0" cellspacing="1" cellpadding="2" align="center">
        <tbody>
        <?php  if ($_SESSION["pr_groupID"]==2 && is_numeric($_SESSION["pr_IDCust"])) { ?>
        <?php  } else { ?>
        <tr>
            <td  valign="top" class="bgcolor_000">
                 <label class=""><?php echo gettext("CUSTOMERS");?></label> 
            </td>
            <td class="bgcolor_005" align="left">
            <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
                <td class="fontstyle_searchoptions" >
                
				 
					<input TYPE="text" NAME="entercustomer" value="<?php echo $entercustomer?>" class="form-control" />
					</td>
					<td>
					<a href="#" onclick="window.open('billing_entity_card.php?popup_select=1&popup_formname=myForm&popup_fieldname=entercustomer' , 'CardNumberSelection','scrollbars=1,scrollbars=1');">
						 
							<i class ="flaticon2-next" ></i>
						 
					</a>
					
							

						 
				
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
                <tr><td class="fontstyle_searchoptions" align="left">
                  
              
					<label class="">
						<input type="checkbox" name="frommonth" value="true" <?php  if ($frommonth) { ?>checked<?php }?>>
						<?php echo gettext("From");?> :
						<span></span>
					</label>
               
				
				<select name="fromstatsday_sday" class="form-control" style="width:75%">
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
				<td align="left">
                
					<label class="">
						<input type="checkbox" name="tomonth" value="true" <?php  if ($tomonth) { ?>checked<?php }?>>
						<?php echo gettext("To");?> :
						<span></span>
					</label>
                
				<select name="tostatsmonth" class="form-control" style="width:75%">
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
            <td align="left" class="bgcolor_004" style="vertical-align:top">
                <label class="kt-radio">
				<input type="radio" name="Period" value="Day" <?php  if ($Period=="Day") { ?>checked="checked" <?php  } ?>>
                
				 <label class="col-12 col-form-label"><?php echo gettext("SELECT DAY");?></label> 
				<span></span>
				</label>
            </td>
              <td align="left" class="bgcolor_005">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td class="fontstyle_searchoptions">
				 
					<label class="">
						<input type="checkbox" name="fromday" value="true" <?php  if ($fromday) { ?>checked<?php }?>>
						<?php echo gettext("From");?> :
						<span></span>
					</label>
				 
				
					
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
				</tr>
				<tr>
				
				<td class="fontstyle_searchoptions"> 
                
                
				
					<label class="">
						<input type="checkbox" name="today" value="true" <?php  if ($today) { ?>checked<?php }?>>
						<?php echo gettext("To");?> :
						<span></span>&nbsp; &nbsp; &nbsp; 
					</label>
               
				
                <select name="tostatsday_sday" class="form-control11" >
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
            <td class="bgcolor_004" align="left"><?php echo gettext("Status");?> :
            </td>
              <td class="bgcolor_003" align="left">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
					<td align="left">

                <select name="status" class="form-control13">
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
            <td class="bgcolor_005" align="right" >
                
					<input  type="submit" name="image16" class="btn btn-primary" value=" <?php echo gettext("Search");?>"><br>
				

              </td>
        </tr>
    </tbody></table>
</FORM>
</div>
</div>


<?php

$HD_Form -> create_form ($form_action, $list, $id=null) ;

$smarty->display('footer.tpl');
