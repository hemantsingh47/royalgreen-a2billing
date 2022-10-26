<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_def_ratecard.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_RATECARD)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('package','popup_select', 'popup_formname', 'popup_fieldname','posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'removeallrate', 'removetariffplan', 'definecredit', 'IDCust', 'mytariff_id', 'destination', 'dialprefix', 'buyrate1', 'buyrate2', 'buyrate1type', 'buyrate2type', 'rateinitial1', 'rateinitial2', 'rateinitial1type', 'rateinitial2type', 'id_trunk', "check", "type", "mode"));

/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array ( 'batchupdate', 'upd_id_trunk', 'upd_idtariffplan', 'upd_id_outbound_cidgroup', 'upd_tag', 'upd_inuse', 'upd_activated', 'upd_language',
    'upd_tariff', 'upd_credit', 'upd_credittype', 'upd_simultaccess', 'upd_currency', 'upd_typepaid', 'upd_creditlimit', 'upd_enableexpire', 'upd_expirationdate',
    'upd_expiredays', 'upd_runservice', 'filterprefix', 'filterfield'
));

$update_fields = array (
    "upd_buyrate",
    "upd_buyrateinitblock",
    "upd_buyrateincrement",
    "upd_rateinitial",
    "upd_initblock",
    "upd_billingblock",
    "upd_connectcharge",
    "upd_disconnectcharge",
    "upd_rounding_calltime",
    "upd_rounding_threshold",
    "upd_additional_block_charge",
    "upd_additional_block_charge_time"
);
$update_fields_info = array (
    "BUYING RATE",
    "BUYRATE MIN DURATION",
    "BUYRATE BILLING BLOCK",
    "SELLING RATE",
    "SELLRATE MIN DURATION",
    "SELLRATE BILLING BLOCK",
    "CONNECT CHARGE",
    "DISCONNECT CHARGE",
    "ROUNDING CALLTIME",
    "ROUNDING THRESHOLD",
    "ADDITIONAL BLOCK CHARGE",
    "ADDITIONAL BLOCK CHARGE TIME"
);
$charges_abc = array ();
$charges_abc_info = array ();
if (ADVANCED_MODE) {
    $charges_abc = array (
        "upd_stepchargea",
        "upd_chargea",
        "upd_timechargea",
        "upd_stepchargeb",
        "upd_chargeb",
        "upd_timechargeb",
        "upd_stepchargec",
        "upd_chargec",
        "upd_timechargec",
        "upd_announce_time_correction"
    );
    $charges_abc_info = array (
        "ENTRANCE CHARGE A",
        "COST A",
        "TIME FOR A",
        "ENTRANCE CHARGE B",
        "COST B",
        "TIME FOR B",
        "ENTRANCE CHARGE C",
        "COST C",
        "TIME FOR C",
        "ANNOUNCE TIME CORRECTION"
    );
};

getpost_ifset($update_fields);

if (ADVANCED_MODE) {
    getpost_ifset($charges_abc);
};

/***********************************************************************************/

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

// CHECK IF REQUEST OF BATCH UPDATE
if ($batchupdate == 1 && is_array($check)) {

    check_demo_mode();

    $HD_Form->prepare_list_subselection('list');

    // Array ( [upd_simultaccess] => on [upd_currency] => on )
    $loop_pass = 0;
    $SQL_UPDATE = '';
    $PREFIX_FIELD = 'cc_ratecard.';

    foreach ($check as $ind_field => $ind_val) {
        //echo "<br>::> $ind_field -";
        $myfield = substr($ind_field, 4);
        if ($loop_pass != 0)
            $SQL_UPDATE .= ',';

        // Standard update mode
        if (!isset ($mode["$ind_field"]) || $mode["$ind_field"] == 1) {
            if (!isset ($type["$ind_field"])) {
                $SQL_UPDATE .= " $PREFIX_FIELD$myfield='" . $$ind_field . "'";
            } else {
                $SQL_UPDATE .= " $PREFIX_FIELD$myfield='" . $type["$ind_field"] . "'";
            }
            // Mode 2 - Equal - Add - Substract
        } elseif ($mode["$ind_field"] == 2) {
            if (!isset ($type["$ind_field"])) {
                $SQL_UPDATE .= " $PREFIX_FIELD$myfield='" . $$ind_field . "'";
            } else {
                if ($type["$ind_field"] == 1) {
                    $SQL_UPDATE .= " $PREFIX_FIELD$myfield='" . $$ind_field . "'";
                } elseif ($type["$ind_field"] == 2) {
                    if (substr($$ind_field, -1) == "%") {
                        $SQL_UPDATE .= " $PREFIX_FIELD$myfield = ROUND($PREFIX_FIELD$myfield + (($PREFIX_FIELD$myfield * " . substr($$ind_field, 0, -1) . ") / 100)+0.00005,4)";
                    } else {
                        $SQL_UPDATE .= " $PREFIX_FIELD$myfield = $PREFIX_FIELD$myfield +'" . $$ind_field . "'";
                    }
                } else {
                    if (substr($$ind_field, -1) == "%") {
                        $SQL_UPDATE .= " $PREFIX_FIELD$myfield = ROUND($PREFIX_FIELD$myfield - (($PREFIX_FIELD$myfield * " . substr($$ind_field, 0, -1) . ") / 100)+0.00005,4)";
                    } else {
                        $SQL_UPDATE .= " $PREFIX_FIELD$myfield = $PREFIX_FIELD$myfield -'" . $$ind_field . "'";
                    }
                }
            }
        }

        $loop_pass++;
    }

    $SQL_UPDATE = "UPDATE $HD_Form->FG_TABLE_NAME SET $SQL_UPDATE";
    if (strlen($HD_Form->FG_TABLE_CLAUSE) > 1) {
        $SQL_UPDATE .= ' WHERE ';
        $SQL_UPDATE .= $HD_Form->FG_TABLE_CLAUSE;
    }
    $instance_table = new Table();
    $res = $instance_table->ExecuteQuery($HD_Form->DBHandle, $SQL_UPDATE);
    if (!$res)
        $update_msg = "<center><font color=\"red\"><b>" . gettext("Could not perform the batch update") . "!</b></font></center>";
    else
        $update_msg = "<center><font color=\"green\"><b>" . gettext("The batch update has been successfully perform") . " !</b></font></center>";

}
/********************************* END BATCH UPDATE ***********************************/

if ($id != "" || !is_null($id)) {
    $HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
    $form_action = "list"; //ask-add
if (!isset ($action))
    $action = $form_action;

if ($form_action != "list") {
    check_demo_mode();
}

if (is_string($tariffgroup) && strlen(trim($tariffgroup)) > 0) {
    list ($mytariffgroup_id, $mytariffgroupname, $mytariffgrouplcrtype) = preg_split('/-:-/', $tariffgroup);
    $_SESSION["mytariffgroup_id"] = $mytariffgroup_id;
    $_SESSION["mytariffgroupname"] = $mytariffgroupname;
    $_SESSION["tariffgrouplcrtype"] = $mytariffgrouplcrtype;
} else {
    $mytariffgroup_id = $_SESSION["mytariffgroup_id"];
    $mytariffgroupname = $_SESSION["mytariffgroupname"];
    $mytariffgrouplcrtype = $_SESSION["tariffgrouplcrtype"];
}

if (($form_action == "list") && ($HD_Form->FG_FILTER_SEARCH_FORM) && ($_POST['posted_search'] == 1) && is_numeric($mytariffgroup_id)) {
    if (!empty ($HD_Form->FG_TABLE_CLAUSE))
        $HD_Form->FG_TABLE_CLAUSE .= ' AND ';

    $HD_Form->FG_TABLE_CLAUSE = "idtariffplan='$mytariff_id'";
}

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if (!$popup_select) {
    if (($form_action == 'ask-add') || ($form_action == 'ask-edit'))
        echo $CC_help_rate;
    else
        echo $CC_help_def_ratecard;
}

// DISPLAY THE UPDATE MESSAGE
if (isset ($update_msg) && strlen($update_msg) > 0)
    echo $update_msg;

if ($popup_select && empty($package) && !is_numeric($package) ) {
?>
<SCRIPT LANGUAGE="javascript">
function sendValue(selvalue) {
    window.opener.document.<?php echo $popup_formname ?>.<?php echo $popup_fieldname ?>.value = selvalue;
    window.close();
}
</script>
<?php

}

if ($popup_select && is_numeric($package)) {
$HD_Form-> CV_FOLLOWPARAMETERS .= "&package=".$package;
?>
<script language="JavaScript" src="javascript/card.js"></script>
<SCRIPT LANGUAGE="javascript">
function sendValue(selvalue)
{
     // redirect browser to the grabbed value (hopefully a URL)
    window.opener.location.href= <?php echo '"billing_package_manage_rates.php?id='.$package.'&addrate="'; ?>+selvalue;
}


</script>
<?php
}
?>
 <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Admin Rates                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Rates                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_def_ratecard.php?atmenu=ratecard&section=6" class="kt-subheader__breadcrumbs-link">
                            List Rate |                       </a>
                        <a href="billing_entity_def_ratecard.php?form_action=ask-add&atmenu=ratecard&section=6" class="kt-subheader__breadcrumbs-link">
                            Add Rate                        </a>
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
			<?php if ($form_action == 'ask-add'){echo gettext("Add Rate"); }else if( $form_action == 'ask-delete'){echo gettext("Delete Rate");} else if( $form_action == 'ask-edit'){echo gettext("Modify Rate");} else{echo gettext("List Rate"); }?>
		</h1>
	</div>
</div>

 <br>
<?php
    
if (!$popup_select) {
    // #### CREATE SEARCH FORM
    if ($form_action == "list") {
        $HD_Form->create_search_form();
     
    }
}
 ?>
 
 <?php
/********************************* BATCH UPDATE ***********************************/
if ($form_action == "list" && !$popup_select) {

    $instance_table = new Table("cc_tariffplan", "id, tariffname");
    $FG_TABLE_CLAUSE = "";
    $list_tariffname = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);
    $nb_tariffname = count($list_tariffname);

    $instance_table = new Table("cc_trunk", "id_trunk, trunkcode, providerip");
    $FG_TABLE_CLAUSE = "";
    $list_trunk = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "trunkcode", "ASC", null, null, null, null);
    $nb_trunk = count($list_trunk);

    $instance_table = new Table("cc_outbound_cid_group", "id, group_name");
    $FG_TABLE_CLAUSE = "";
    $list_cid_group = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "group_name", "ASC", null, null, null, null);
    $nb_cid_group = count($list_cid_group);

    // disable Batch update if LCR Export
    if (empty($_SESSION['def_ratecard_tariffgroup'])) {

?>

<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<br />

<script>
function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>

	<center>
		<div class="kt-form__actions">
			<button onclick="myFunction()" class="btn btn-primary">
				<i class="flaticon2-search-1 "></i>
				<?php echo gettext("BATCH UPDATE");?> 
			</button>
			
		</div>
	
		
	</center>
    
	<div class="kt-portlet" id="myDIV" style="display:none;" >
		
		<center>
			
			<h3 class="kt-portlet__head-title">
				&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("rates selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected rates.");?>
			</h3><br />
			<table border="0" cellspacing="1" cellpadding="2" class="table">
				<tbody>
					<FORM name="updateForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>" method="post" class="kt-form">
						<INPUT type="hidden" name="batchupdate" value="1">
						<INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
						<INPUT type="hidden" name="popup_select" value="<?php echo $popup_select?>">
						<INPUT type="hidden" name="popup_formname" value="<?php echo $popup_formname?>">
						<INPUT type="hidden" name="popup_fieldname" value="<?php echo $popup_fieldname?>">
						<INPUT type="hidden" name="form_action" value="<?php echo $form_action?>">
						<INPUT type="hidden" name="filterprefix" value="<?php echo $filterprefix?>">
						<INPUT type="hidden" name="filterfield" value="<?php echo $filterfield?>">
						
						<?php
							if ($HD_Form->FG_CSRF_STATUS == true) 
							{
						?>
						<INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
						<INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
						<?php
							}
						?>
						<tr>
						
						<td></td>
							<td align="right"  class="bgcolor_001">
								<label class="">
								<span class="icheck-inline">
									<input name="check[upd_id_trunk]" type="checkbox" data-icheck="" <?php if ($check["upd_id_trunk"]=="on") echo "checked"?>>
								
								</span>
								
								</label>
							</td>
							<td><label class="col-12 col-form-label" >&nbsp; 1) <?php echo gettext("TRUNK"); ?></label></td>
							<td align="left"  class="bgcolor_001">
								 
									
									<select NAME="upd_id_trunk" size="1" class="form-control" >
										<OPTION  value="-1" selected><?php echo gettext("NOT DEFINED");?></OPTION>
										<?php
											foreach ($list_trunk as $recordset) {
										?>
										<option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_id_trunk==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1].' ('.$recordset[2].')'?></option>
										<?php } ?>
									</select>
									<span class="input-bar "></span>
								 
							</td>
							<td></td>
						</tr>
		
						<tr>
						
						<td></td>
							<td align="right"  class="bgcolor_001">
								<label class="">
									<span class="icheck-inline">
									<input name="check[upd_idtariffplan]" type="checkbox" data-icheck=""  <?php if ($check["upd_idtariffplan"]=="on") echo "checked"?> >
									
									</span>
									<span></span>
								</label>
							</td>
							<td><label class="col-12 col-form-label" >&nbsp; 2) <?php echo gettext("RATECARD"); ?>
									</label></td>
							
							<td align="left"  class="bgcolor_001">
								
									
									<select NAME="upd_idtariffplan" size="1" class="form-control" >
										<?php
										   foreach ($list_tariffname as $recordset) {
										?>
											<option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_idtariffplan==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
										<?php 	 }
										?>
									</select>
									<span class="input-bar "></span>
								
							</td>
							<td></td>
						</tr>
						
						<tr>
						
						<td></td>
							<td align="right"  class="bgcolor_001">
								<label class="">
									<span class="icheck-inline">
									<input name="check[upd_id_outbound_cidgroup]" type="checkbox"  <?php if ($check["upd_id_outbound_cidgroup"]=="on") echo "checked"?> >
								
									</span>
									<span></span>
								</label>
							</td>
							
							<td>	 <label class="col-12 col-form-label" >&nbsp; 3) <?php echo gettext("CIDGroup"); ?>
									</label></td>
							
							<td align="left"  class="bgcolor_001">
								
									
									<select NAME="upd_id_outbound_cidgroup" size="1" class="form-control" >
										<OPTION  value="-1" selected><?php echo gettext("NOT DEFINED");?></OPTION>
										<?php
										   foreach ($list_cid_group as $recordset) {
										?>
											<option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_id_outbound_cidgroup==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
										<?php 	 }
										?>
									</select>
									<span class="input-bar "></span>
								 
							</td>
							<td></td>
						</tr>
						
						<tr>
							<?php
								$index=0;
								foreach ($update_fields as $value) 
								{
							?>
							
							<td></td>
							<td align="right" class="bgcolor_001">
								<label class="">
									<span class="icheck-inline">
										<input name="check[<?php echo $value;?>]" type="checkbox" <?php if ($check[$value]=="on") echo "checked"?>>										
										<label class="">
											<input name="mode[<?php echo $value;?>]" type="hidden" value="2">
										</label>
									</span>
									<span></span>
								</label>
							</td>
							
							<td align="left"  class="bgcolor_001">
								
									<label class="col-12 col-form-label" > &nbsp;
										<?php echo ($index + 4).") ".gettext($update_fields_info[$index]);?> :
									</label>
								
									</td>
									<td>
									
									<input input class="form-control" name="<?php echo $value;?>" size="10" maxlength="10"  value="<?php if (isset(${$value})) echo ${$value}; else echo '0';?>" >
									</td>
									<td>
									
									<font class="version">
										 
											<label class="">
												<input name="radio2" type="radio" NAME="type[<?php echo $value;?>]" value="1" <?php if ((!isset($type[$value]))|| ($type[$value]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>
												<span></span>
											</label>
											
											<label class="">
												<input name="radio2" type="radio" data-icheck="" NAME="type[<?php echo $value;?>]" value="2" <?php if ($type[$value]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
												<span></span>
											</label>
											<label class="">
												<input name="radio2" type="radio" data-icheck="" NAME="type[<?php echo $value;?>]" value="3" <?php if ($type[$value]==3) {?>checked<?php }?>> <?php echo gettext("Subtract");?>
												<span></span>
											</label>
										
									</font>
								<span class="input-bar "></span>
							 
						</td>
					</tr>
					<?php $index=$index+1;
						}
					?>       
					
					<tr>
					
					<td></td>
						<td align="right"  class="bgcolor_001">
							<label class="">
								<span class="icheck-inline">
								<input name="check[upd_tag]" type="checkbox" <?php if ($check["upd_tag"]=="on") echo "checked"?>>
								
								</span>
								
							</label>
						</td>
						
						<td align="left"  class="bgcolor_001">
							
								<label class="col-12 col-form-label">&nbsp; 16) <?php echo gettext("TAG");?> :</label></td>
								<td>
								<input input class="form-control" name="upd_tag" size="20"  value="<?php if (isset($upd_tag)) echo $upd_tag; else echo '';?>" >
							      
						</td>
						<td></td>
					</tr>

					<tr>
						<?php
						$index=0;
						foreach ($charges_abc as $value) {
						?>
						
						<td></td>
					  <td align="right" class="bgcolor_001">
						  <input name="check[<?php echo $value;?>]" type="checkbox" <?php if ($check[$value]=="on") echo "checked"?>>
						  <input name="mode[<?php echo $value;?>]" type="hidden" value="2">
					  </td>
						  <td align="left"  class="bgcolor_001">
							<font class="fontstyle_009"><?php echo ($index+16).") ".gettext($charges_abc_info[$index]);?> :</font></td>
							<td>
								<input class="form_input_text" name="<?php echo $value;?>" size="10" maxlength="10"  value="<?php if (isset(${$value})) echo ${$value}; else echo '0';?>" ></td>
								<td>
							<font class="version">
							<input type="radio" NAME="type[<?php echo $value;?>]" value="1" <?php if ((!isset($type[$value]))|| ($type[$value]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>
							<input type="radio" NAME="type[<?php echo $value;?>]" value="2" <?php if ($type[$value]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
							<input type="radio" NAME="type[<?php echo $value;?>]" value="3" <?php if ($type[$value]==3) {?>checked<?php }?>> <?php echo gettext("Subtract");?>
							</font>
						  </td>
					</tr>
					<?php $index=$index+1;
						}
					?>
					
					<tr>
						 
							<td align="right"  class="bgcolor_001" colspan="5">
								<br><input class="btn btn-success"  value=" <?php echo gettext("BATCH UPDATE RATECARD");?> " type="submit">
								<br>
						</td>
					</tr>
				</form>
			</table>
		</center>
	</div>

<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
 

<?php

    } // disable Batch update if LCR Export

} // END if ($form_action == "list")

/********************************* BATCH ASSIGNED ***********************************/
if ($popup_select) {

    $instance_table = new Table("cc_prefix GROUP BY destination", "destination");
    $FG_TABLE_CLAUSE = "";
    $list_destination = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, null, "ASC", null, null, null, null);
    $destination = $list_destination[0];

    $instance_table = new Table("cc_tariffplan", "id, tariffname");
    $FG_TABLE_CLAUSE = "";
    $list_tariffname = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);
    $nb_tariffname = count($list_tariffname);

    $instance_table = new Table("cc_trunk", "id_trunk, trunkcode, providerip");
    $FG_TABLE_CLAUSE = "";
    $list_trunk = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "trunkcode", "ASC", null, null, null, null);
    $nb_trunk = count($list_trunk);

    $instance_table = new Table("cc_outbound_cid_group", "id, group_name");
    $FG_TABLE_CLAUSE = "";
    $list_cid_group = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "group_name", "ASC", null, null, null, null);
    $nb_cid_group = count($list_cid_group);

?>


<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->

<div class="kt-portlet">
	<center>
		<a href="#" target="_self" class="kt-wizard-v4__nav-item nav-item">
			<span class="btn btn-default"><?php echo gettext("BATCH ASSIGNED");?></span> 
		</a>
	</center>
    
	<div class="kt-portlet" id="myDIV" style="display:none;" >
		<center>

			<h3 class="kt-portlet__head-title">
				&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("rates selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected rates.");?>
			</h3><br />
			
			<table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
				<tbody>
					<FORM name="updateForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>" method="post" class="kt-form">
						<INPUT type="hidden" name="batchupdate" value="1">
						<INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
						<INPUT type="hidden" name="popup_select" value="<?php echo $popup_select?>">
						<INPUT type="hidden" name="popup_formname" value="<?php echo $popup_formname?>">
						<INPUT type="hidden" name="popup_fieldname" value="<?php echo $popup_fieldname?>">
						<INPUT type="hidden" name="form_action" value="<?php echo $form_action?>">
						<INPUT type="hidden" name="filterprefix" value="<?php echo $filterprefix?>">
						<INPUT type="hidden" name="filterfield" value="<?php echo $filterfield?>">
						<?php
							if ($HD_Form->FG_CSRF_STATUS == true) {
						?>
						<INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
						<INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
						<?php
							}
						?>
						
						<tr>
							<td align="left"  class="bgcolor_001">
								<label class="kt-checkbox kt-checkbox--bold">
								<span class="icheck-inline">
									<input name="check[upd_id_trunk]" type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"  <?php if ($check["upd_id_trunk"]=="on") echo "checked"?>>
									<label class="col-2 col-form-label">1)</label>
								</span>
								<span></span>
								</label>
							</td>
							<td align="left"  class="bgcolor_001">
								<div class="form-group form-group-last">
									<label class="col-2 col-form-label" > &nbsp;<?php echo gettext("TRUNK"); ?></label>
									<select NAME="assign_id_trunk" size="1" class="form-control" >
										<OPTION  value="-1" selected><?php echo gettext("NOT DEFINED");?></OPTION>
										<?php
										 foreach ($list_trunk as $recordset) {
										?>
											<option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_id_trunk==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1].' ('.$recordset[2].')'?></option>
										<?php } ?>
									</select>
									<span class="input-bar "></span>
								</div>
							</td>
						</tr>
						
						<tr>
							<td align="left"  class="bgcolor_001">
								<label class="kt-checkbox kt-checkbox--bold">
									<span class="icheck-inline">
									<input name="check[upd_idtariffplan]" type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"   <?php if ($check["upd_idtariffplan"]=="on") echo "checked"?> >
									<label class="col-2 col-form-label">2)</label>
									</span>
									<span></span>
								</label>
							</td>
							<td align="left"  class="bgcolor_001">
								<div class="form-group form-group-last">
									<label class="col-2 col-form-label" > &nbsp;
										<?php echo gettext("RATECARD"); ?>
									</label>
									<select NAME="upd_idtariffplan" size="1" class="form-control" >
										<?php
										   foreach ($list_tariffname as $recordset) {
										?>
											<option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_idtariffplan==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
										<?php 	 }
										?>
									</select>
									<span class="input-bar "></span>
								</div>
							</td>
						</tr>
						
						<tr>
						<td align="left"  class="bgcolor_001">
							<label class="kt-checkbox kt-checkbox--bold">
								<span class="icheck-inline">
								<input name="check" type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);">
								
								</span>
								<span></span>
							</label>
						</td>
						
						<td align="left"  class="bgcolor_001">
							<div class="form-group form-group-last">
								<label class="col-12 col-form-label">3) <?php echo gettext("TAG");?> :</label>
								<input input class="form-control" name="assign_tag" size="20" >
							</div>       
						</td>
					</tr>
					
					<tr>
						<td align="left"  class="bgcolor_001">
							<label class="kt-checkbox kt-checkbox--bold">
								<span class="icheck-inline">
								<input name="check" type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" >
								
								</span>
								<span></span>
							</label>
						</td>
						
						<td align="left"  class="bgcolor_001">
							<div class="form-group form-group-last">
								<label class="col-12 col-form-label">4) <?php echo gettext("PREFIX");?> :</label>
								<input input class="form-control" name="assign_prefix" size="20" ></br>
								
								<font class="version">
									<div class="kt-radio-inline">
										<label class="kt-radio">
											<input name="radio2" type="radio"style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" NAME="rbPrefix" value="1" checked> <?php echo gettext("Exact");?>
											<span></span>
										</label>
										
										<label class="kt-radio">
											<input name="radio2" type="radio"style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" NAME="rbPrefix" value="2" > <?php echo gettext("Begins with");?>
											<span></span>
										</label>
										<label class="kt-radio">
											<input name="radio2" type="radio"style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" NAME="rbPrefix" value="3" > <?php echo gettext("Contains");?>
											<span></span>
										</label>
										<label class="kt-radio">
											<input name="radio2" type="radio"style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" NAME="rbPrefix" value="4" > <?php echo gettext("Ends with");?>
											<span></span>
										</label>
										<label class="kt-radio">
											<input name="radio2" type="radio"style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" NAME="rbPrefix" value="5" > <?php echo gettext("Expression");?>
											<span></span>
										</label>
										</div>
									</font>
								<span class="input-bar "></span>
							</div>  
						</td>
						<td>
							<font class="fontstyle_009">
								<span class="form-text text-muted"><?php echo gettext("With 'Expression' you can define a range of prefixes. '32484-32487' adds all prefixes between 32484 and 32487. '32484,32386,32488' would add only the individual prefixes listed.");?></span>
							</font>
						</td>
					</tr>
					<tr>
						<td align="right" class="bgcolor_001">
						</td>
						
						<td align="right" class="bgcolor_001"></td>
							<td align="right"  class="bgcolor_001">
								<br><input onclick="javascript:sendOpener();" class="btn btn-success" value=" <?php echo gettext("BATCH ASSIGNED");?> " type="submit">
								<br>
						</td>
						 
					</tr>
				</form>
			</tbody>
		</table>
	</center>


<script language="javascript">
function sendOpener() {
    if (document.assignForm.check[0].checked==true) {
        var id_trunk = document.assignForm.assign_id_trunk.options[document.assignForm.assign_id_trunk.selectedIndex].value;
    }

    if (document.assignForm.check[1].checked==true) {
        var id_tariffplan = document.assignForm.assign_idtariffplan.options[document.assignForm.assign_idtariffplan.selectedIndex].value;
    }

    if (document.assignForm.check[2].checked==true) {
        var tag = document.assignForm.assign_tag.value;
    }

    if (document.assignForm.check[3].checked==true) {
        for (var j=0;j<document.assignForm.rbPrefix.length;j++) {
           if (document.assignForm.rbPrefix[j].checked)
              break;
        }
        var prefix = document.assignForm.assign_prefix.value+"&rbPrefix="+document.assignForm.rbPrefix[j].value;
    }
    window.opener.location.href = "A2B_package_manage_rates.php?id=<?php echo $package;?>&addbatchrate=true"+((id_trunk)? ('&id_trunk='+id_trunk):'')+((id_tariffplan)?('&id_tariffplan='+id_tariffplan):'')+((tag)? ('&tag='+tag):'')+((prefix)? ('&prefix='+prefix):'');
}
</script>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
    </div>
</div>

<?php
} // disable Batch update if not popup
/********************************* END BATCH ASSIGNED ***********************************/

?>

<?php

// Weird hack to create a select form
if ($form_action == "list" && !$popup_select) $HD_Form -> create_select_form();

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// Code for the Export Functionality
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null($HD_Form->SQL_GROUP) && ($HD_Form->SQL_GROUP != ''))
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " $HD_Form->SQL_GROUP ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";

if (strpos($_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR], 'cc_callplan_lcr')===false) {
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] = str_replace('destination,', 'cc_prefix.destination,', $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]);
}

// #### FOOTER SECTION
$smarty->display('footer.tpl');