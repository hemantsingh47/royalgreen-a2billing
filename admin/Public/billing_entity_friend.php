<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_friend.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array('upd_callerid', 'upd_context', 'batchupdate', 'check', 'type', 'mode', 'atmenu'));


// CHECK IF REQUEST OF BATCH UPDATE
if ($batchupdate == 1 && is_array($check)) {
    $SQL_REFILL = "";
    $HD_Form -> prepare_list_subselection('list');

    // Array ( [upd_simultaccess] => on [upd_currency] => on )
    $loop_pass = 0;
    $SQL_UPDATE = '';
    foreach($check as $ind_field => $ind_val) {
        //echo "<br>::> $ind_field -";
        $myfield = substr($ind_field,4);
        if ($loop_pass != 0) {
            $SQL_UPDATE.=',';
        }
        // Standard update mode
        if (!isset($mode["$ind_field"]) || $mode["$ind_field"]==1) {
            if (!isset($type["$ind_field"])) {
                $SQL_UPDATE .= " $myfield='".$$ind_field."'";
            } else {
                $SQL_UPDATE .= " $myfield='".$type["$ind_field"]."'";
            }
        // Mode 2 - Equal - Add - Subtract
        } elseif ($mode["$ind_field"]==2) {
            if (!isset($type["$ind_field"])) {
                $SQL_UPDATE .= " $myfield='".$$ind_field."'";
            } else {
                if ($type["$ind_field"] == 1) {
                    $SQL_UPDATE .= " $myfield='".$$ind_field."'";
                } elseif ($type["$ind_field"] == 2) {
                    $SQL_UPDATE .= " $myfield = $myfield +'".$$ind_field."'";
                } else {
                    $SQL_UPDATE .= " $myfield = $myfield -'".$$ind_field."'";
                }
            }
        }
        $loop_pass++;
    }

    $SQL_UPDATE = "UPDATE $HD_Form->FG_TABLE_NAME SET $SQL_UPDATE";
    if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) {
        $SQL_UPDATE .= ' WHERE ';
        $SQL_UPDATE .= $HD_Form->FG_TABLE_CLAUSE;
    }
    $update_msg_error = '<center><font color="red"><b>'.gettext('Could not perform the batch update!').'</b></font></center>';

    if (!$HD_Form -> DBHandle -> Execute("begin")) {
        $update_msg = $update_msg_error;
    } else {
        if (!$HD_Form -> DBHandle -> Execute($SQL_UPDATE)) {
            $update_msg = $update_msg_error;
        }
        if (! $res = $HD_Form -> DBHandle -> Execute("commit")) {
            $update_msg = '<center><font color="green"><b>'.gettext('The batch update has been successfully perform!').'</b></font></center>';
        }
    };
}

/********************************* ADD SIP / IAX FRIEND ***********************************/
getpost_ifset(array("id_cc_card", "cardnumber", "useralias"));

if ( (isset ($id_cc_card) && (is_numeric($id_cc_card)  != "")) && ( $form_action == "add_sip" || $form_action == "add_iax") ) {

    $HD_Form -> FG_GO_LINK_AFTER_ACTION = "billing_entity_card.php?atmenu=card&stitle=Customers_Card&id=";

    if ($form_action == "add_sip") {
        $friend_param_update=" sip_buddy='1' ";
        if (!USE_REALTIME) {
            $key = "sip_changed";
        }
    } else {
        $friend_param_update=" iax_buddy='1' ";
        if (!USE_REALTIME) {
            $key = "iax_changed";
        }
    }

    if (!USE_REALTIME) {
        $who= Notification::$ADMIN;$who_id=$_SESSION['admin_id'];
        NotificationsDAO::AddNotification($key,Notification::$HIGH,$who,$who_id);
    }

    $instance_table_friend = new Table('cc_card');
    $instance_table_friend -> Update_table ($HD_Form -> DBHandle, $friend_param_update, "id='$id_cc_card'", $func_table = null);

    if ($form_action == "add_sip") {
        $TABLE_BUDDY = 'cc_sip_buddies';
    } else {
        $TABLE_BUDDY = 'cc_iax_buddies';
    }

    $instance_table_friend = new Table($TABLE_BUDDY,'*');
    $list_friend = $instance_table_friend -> Get_list ($HD_Form -> DBHandle, "id_cc_card='$id_cc_card'", null, null, null, null);

    if (is_array($list_friend) && count($list_friend)>0) { Header ("Location: ".$HD_Form->FG_GO_LINK_AFTER_ACTION); exit();}

    $form_action = "add";

    $_POST['accountcode'] = $_POST['username']= $_POST['name']= $_POST['cardnumber'] = $cardnumber;
    $_POST['allow'] = FRIEND_ALLOW;
    $_POST['context'] = FRIEND_CONTEXT;
    $_POST['nat'] = FRIEND_NAT;
    $_POST['amaflags'] = FRIEND_AMAFLAGS;
    $_POST['regexten'] = $cardnumber;
    $_POST['id_cc_card'] = $id_cc_card;
    $_POST['callerid'] = $useralias;
    $_POST['qualify'] = FRIEND_QUALIFY;
    $_POST['host'] = FRIEND_HOST;
    $_POST['dtmfmode'] = FRIEND_DTMFMODE;
    $_POST['secret'] = MDP_NUMERIC(5).MDP_STRING(10).MDP_NUMERIC(5);

    // for the getProcessed var
    $HD_Form->_vars = array_merge((array) $_GET, (array) $_POST);
}

$HD_Form -> FG_EDITION_LINK = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?form_action=ask-edit&atmenu=$atmenu&id=";
$HD_Form -> FG_DELETION_LINK = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?form_action=ask-delete&atmenu=$atmenu&id=";

if ($id != "" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

if (!USE_REALTIME) {
    // CHECK THE ACTION AND SET THE IS_SIP_IAX_CHANGE IF WE ADD/EDIT/REMOVE A RECORD
    if ($form_action == "add" || $form_action == "edit" || $form_action == "delete") {
        if ($atmenu=='sip') {
            $key = "sip_changed";
        } else {
            $key = "iax_changed";
        }
        if ($_SESSION["user_type"]=="ADMIN") {$who= Notification::$ADMIN;$id=$_SESSION['admin_id'];} elseif ($_SESSION["user_type"]=="AGENT") {$who= Notification::$AGENT;$id=$_SESSION['agent_id'];} else {$who=Notification::$UNKNOWN;$id=-1;}
        NotificationsDAO::AddNotification($key,Notification::$HIGH,$who,$id);
    }
}

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

?>

<script language="JavaScript" src="javascript/card.js"></script>


<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Customer                          </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
						User                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
						 Customer                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_friend.php" class="kt-subheader__breadcrumbs-link">
						Voip Settings                     </a>
                       
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
				<?php echo gettext("VOIP Settings"); ?>
				
			</h1>
		</div>
	</div>
	<br>
<?php

// #### HELP SECTION
if ($form_action=='list') {

    echo $CC_help_sipfriend_list;

    if (!USE_REALTIME) {
    ?>
          <table width="<?php echo $HD_Form -> FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0" >
            <TR>
                <TD  align="center"> <?php echo gettext("Link to Generate on SIP/IAX Friends")?> &nbsp;:&nbsp;</TD>
            </TR>
            <TR>
                <TD  align="center">
                <b><?php echo gettext("Realtime not active, you have to use the conf file for your system"); ?></b>
                </TD>
            </TR>
            <TR>
            <FORM NAME="sipfriend">
                <?php
                    if ($HD_Form->FG_CSRF_STATUS == true) {
                ?>
                    <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
                    <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
                <?php
                    }
                ?>
                <td height="31" style="padding-left: 5px; padding-right: 3px;" align="center" >
                <b>
                SIP : <input class="form_input_button"  TYPE="button" VALUE=" <?php echo gettext("GENERATE ADDITIONAL_A2BILLING_SIP.CONF"); ?> "
                onClick="self.location.href='./CC_generate_friend_file.php?atmenu=sipfriend';">
                IAX : <input class="form_input_button"  TYPE="button" VALUE=" <?php echo gettext("GENERATE ADDITIONAL_A2BILLING_IAX.CONF"); ?> "
                onClick="self.location.href='./CC_generate_friend_file.php?atmenu=iaxfriend';">
                </b></td></FORM>
            </TR>
           </table>
           <br/>
    <?php
    } else { ?>
        <center><a href="<?php  echo "CC_generate_friend_file.php?action=reload";?>"><img src="<?php echo Images_Path;?>/icon_refresh.gif"/>
            <?php echo gettext("Reload Asterisk"); ?></a>
        </center>
    <?php
    }
} else {
    echo $CC_help_sipfriend_edit;
}

if ($form_action=='list') {
?>

<div class="col-md-12">

	<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">
		<tbody>
			<tr>
				<td class="widget-title" colspan="5" style="border-top: 1px solid #CDCDCD; padding: 0px;  ">
				
					<label class="control-label" style="margin-bottom: 0px; width:200px;">Configuration Settings</label>
				</td>
			</tr>
			
			<tr>
				<FORM name="form1" method="post" action="" class="kt-form">
					<?php
					  if ($HD_Form->FG_CSRF_STATUS == true) {
					?>
						<INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
						<INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
					<?php
						}
					?>
					<td width="19%" align="left" valign="top" class="bgcolor_004">
						<label class="col-form-label">&nbsp;&nbsp;<?php echo gettext("CONFIGURATION TYPE");?></label>
					</td>
					
					<td width="81%" align="left" >
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="fontstyle_searchoptions">
									<select class="form-control" name="atmenu" id="col_configtype" onChange="window.document.form1.elements['PMChange'].value='Change';window.document.form1.submit();">
										
										<option value="iax" <?php if($atmenu == "iax")echo "selected"?>><?php echo gettext("IAX")?></option>
										<option value="sip" <?php if($atmenu == "sip")echo "selected"?>><?php echo gettext("SIP")?></option>
						
									</select>
									 <input name="PMChange" type="hidden" id="PMChange" class="form-control">
								</td>
							</tr>
						</table>
					</td>
				</FORM>
			</tr>
		</tbody>
	</table>
</div>
            
<br/>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->

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
				<i class=""></i>
				<?php echo gettext("BATCH UPDATE");?> 
			</button>
			
		</div>
	</center>
	
	<div class="kt-portlet" id="myDIV" style="display:none;" >
		
		<center>
			
			<h3 class="kt-portlet__head-title">
				&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("cards selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected cards.");?>
			</h3><br />
			<table border="0" cellspacing="1" cellpadding="2" class="table">
				<tbody>
					<FORM name="updateForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>" method="post" class="kt-form">
						<?php
							if ($HD_Form->FG_CSRF_STATUS == true) 
							{
								
						?>
							<INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
							<INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
						<?php
							}
						?>
						 <INPUT type="hidden" name="batchupdate" value="1">
						<tr>
							<td></td>
							<td align="right"  class="bgcolor_001">
								<label class="">
								<span class="icheck-inline">
									<input name="check[upd_callerid]" type="checkbox" data-icheck="" <?php if ($check["upd_callerid"]=="on") echo "checked"?>>
								
								</span>
								
								</label>
							</td>
							<td>
								<label class="col-12 col-form-label" >&nbsp; 1) <?php echo gettext("Caller ID"); ?></label>
							</td>
							<td align="left"  class="bgcolor_001">
								<input input class="form-control" name="upd_callerid" size="30" maxlength="40"  value="<?php if (isset($upd_callerid)) echo $upd_callerid;?>" > 
							</td>
							<td></td>
						</tr>
						
						<tr>
							<td></td>
							<td align="right"  class="bgcolor_001">
								<label class="">
								<span class="icheck-inline">
									<input name="check[upd_context]" type="checkbox" data-icheck="" <?php if ($check["upd_context"]=="on") echo "checked"?>>
								</span>
								
								</label>
							</td>
							<td>
								<label class="col-12 col-form-label" >&nbsp; 2) <?php echo gettext("Context"); ?></label>
							</td>
							<td align="left"  class="bgcolor_001">
								<input input class="form-control" name="upd_context" size="30" maxlength="40"  value="<?php if (isset($upd_context)) echo $upd_context;?>"> 
							</td>
							<td></td>
						</tr>
	
						<tr>
							<td align="right"  class="bgcolor_001" colspan="5">
								<br><input class="btn btn-success"  value=" <?php echo gettext("BATCH UPDATE VOIP SETTINGS");?> " type="submit">
								<br>
							</td>
						</tr>
					</form>
				</tbody>
			</table>
		</center>
    </div>
    
    
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->

<?php
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');
