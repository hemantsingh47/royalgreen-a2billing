<?php


include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_voucher.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_BILLING)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array('popup_select','popup_formname','popup_fieldname','upd_tag','upd_currency','upd_credit','upd_activated','upd_used','upd_credittype','batchupdate','check','type','mode'));

// CHECK IF REQUEST OF BATCH UPDATE
if ($batchupdate == 1 && is_array($check)) {

    $HD_Form->prepare_list_subselection('list');

    // Array ( [upd_simultaccess] => on [upd_currency] => on )
    $loop_pass = 0;
    $SQL_UPDATE = '';
    foreach ($check as $ind_field => $ind_val) {
        //echo "<br>::> $ind_field -";
        $myfield = substr($ind_field, 4);
        if ($loop_pass != 0)
            $SQL_UPDATE .= ',';

        // Standard update mode
        if (!isset ($mode["$ind_field"]) || $mode["$ind_field"] == 1) {
            if (!isset ($type["$ind_field"])) {
                $SQL_UPDATE .= " $myfield='" . $$ind_field . "'";
            } else {
                $SQL_UPDATE .= " $myfield='" . $type["$ind_field"] . "'";
            }
            // Mode 2 - Equal - Add - Subtract
        } elseif ($mode["$ind_field"] == 2) {
            if (!isset ($type["$ind_field"])) {
                $SQL_UPDATE .= " $myfield='" . $$ind_field . "'";
            } else {
                if ($type["$ind_field"] == 1) {
                    $SQL_UPDATE .= " $myfield='" . $$ind_field . "'";
                } elseif ($type["$ind_field"] == 2) {
                    $SQL_UPDATE .= " $myfield = $myfield +'" . $$ind_field . "'";
                } else {
                    $SQL_UPDATE .= " $myfield = $myfield -'" . $$ind_field . "'";
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
    if (!$res = $HD_Form->DBHandle->Execute($SQL_UPDATE)) {
        $update_msg = '<center><font color="red"><b>' . gettext('Could not perform the batch update!') . '</b></font></center>';
    } else {
        $update_msg = '<center><font color="green"><b>' . gettext('The batch update has been successfully perform!') . '</b></font></center>';
    }

}
/********************************* END BATCH UPDATE ***********************************/

if ($id != "" || !is_null($id)) {
    $HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

echo'<div style="width:98%; margin:0 auto;">';




if (!isset ($form_action))
    $form_action = "list"; //ask-add
if (!isset ($action))
    $action = $form_action;

$list = $HD_Form->perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if ($form_action == 'list')
    echo $CC_help_list_voucher;
else
    echo $CC_help_create_voucher;

?>
<script language="JavaScript" src="javascript/card.js"></script>
<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       
       $("#"+id).toggle("slidedown");
       
    }
//-->
</script>

<?php


/********************************* BATCH UPDATE ***********************************/


if ($form_action == "list" && (!($popup_select>=1)) ) {
    $instance_table_tariff = new Table("cc_tariffgroup", "id, tariffgroupname");
    $FG_TABLE_CLAUSE = "";
    $list_tariff = $instance_table_tariff -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE, "tariffgroupname", "ASC", null, null, null, null);
    $nb_tariff = count($list_tariff);
?>


<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<style type="text/css">
input, textarea, .uneditable-input 
{
	width: auto;  
    
}
 
.btn-small {
     
}
</style> 

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Voucher's List                       </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                             Others                      </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_voucher.php?section=10#" class="kt-subheader__breadcrumbs-link">
                            Vouchers                </a>
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
            <?php if ($form_action == 'ask-add'){echo gettext("Add Vouchers"); }else if( $form_action == 'ask-delete'){echo gettext("Delete Voucher");} else if( $form_action == 'ask-edit'){echo gettext("Modify Voucher");} else{echo gettext(" Voucher's List"); }?>
			
	      </h1>
        </div>
    </div>
	<script language="JavaScript" src="javascript/card.js"></script>
	<div class="kt-portlet__body">
    
    
    <div class="form-actions" style="    padding: 10px 10px 10px; margin-top: 0px;margin-bottom: 0px;">  
           
             			
			
		
                
                    <button onclick="myFunction()" class="btn btn-primary">
					
						<i class="flaticon-add-label-button"></i>&nbsp;<?php echo gettext("Batch Update");?> 
					</button>
                    
                 
           
          
           <a href="#" id="" target="_self" class="toggle_menu" onclick="toggle_visibility('tohide1')" ><div class="btn btn-primary  btn-small btn-wave-light"  onmouseover="this.style.cursor='hand';"><i class="flaticon2-search-1 "></i>&nbsp;<?php echo gettext("Search Vouchers");?></div></a> 
           <?php if (!empty($_SESSION['entity_voucher_selection'])) { ?>&nbsp;(<font style="color:#EE6564;" > <?php echo gettext("search activated"); ?> </font> ) <?php } ?>
       
            <?php if (!($popup_select>=1)) { ?>
                <a href="billing_entity_voucher_multi.php?section=<?php echo $_SESSION['menu_section']; ?>" >
                    <div class="btn btn-primary  btn-small btn-wave-light" onmouseover="this.style.cursor='hand';"> 
                       <i class="flaticon-more-v4 "></i><?php echo gettext("Generate Voucher");?> 
                    </div>
                </a>
              <?php }?> 
         
            <?php if (!($popup_select>=1)) { ?>
                    <a href="billing_entity_voucher.php?form_action=ask-add&section=<?php echo $_SESSION['menu_section'];?>" >
                        <div class="btn btn-primary  btn-small btn-wave-light" onmouseover="this.style.cursor='hand';"> 
                            <i class="flaticon2-plus "></i><?php echo gettext("Add Voucher");?> 
                        </div>
                    </a>
                 <?php }?> 
           </div>
</div>           
           
        
    
                   
    <div class="kt-portlet">
 			<div class="tohide" id="tohide1" name="tohide1" style="display:none;">
				<?php
				// #### CREATE SEARCH FORM
				if ($form_action == "list") {
					$HD_Form -> create_search_form();
				}
				?>
			</div>	  
   </div>
   
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
 <!-- *************************************** Form for the BATCH Update ************************************** --> 
    <div 
	<div class="kt-portlet" id="myDIV" style="display:none;">  
 		
			<center>
                <b>&nbsp;<h3 class="kt-portlet__head-title"><?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("vouchers selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected vouchers.");?></b></center><hr />
                    <form name="updateForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>" method="post">
						<table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
                            <tbody>
								<tr>
									<td colspan="2">
										<INPUT type="hidden" name="batchupdate" value="1">
										<?php
											if ($HD_Form->FG_CSRF_STATUS == true) {
										?>
											<INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
											<INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
										<?php
											}
										?>
									</td>
								</tr>  
                                <tr>
									<td align="left"  class="bgcolor_001" style="width: 20%; vertical-align: top;">
										<span class="icheck-inline">
											<input name="check[upd_used]" type="checkbox" data-icheck="" style="top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php if ($check["upd_used"]=="on") echo "checked"?>>
											<label>1) &nbsp;<?php echo gettext("USED"); ?></label>
										</span>  
									</td>
									<td align="left"  class="bgcolor_001">
										<div class="form-group form-group-last">
											
											<select NAME="upd_used" size="1" class="form-control">
												<?php
													foreach ($used_list as $key => $cur_value) {
												?>
													<option value='<?php echo $cur_value[1] ?>'  <?php if ($upd_inuse==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>
												<?php } ?>
											</select>
											<span class="input-bar "></span>
										</div>
									</td>
                                </tr>
		                        
                                <tr>
									<td align="left"  class="bgcolor_001" style="width: 20%; vertical-align: top;">
										<span class="icheck-inline">
											<input name="check[upd_activated]" type="checkbox" data-icheck="" style="top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php if ($check["upd_activated"]=="on") echo "checked"?> >
											<label>2) &nbsp;<?php echo gettext("ACTIVATED");?></label>
										</span>
									</td>
									<td align="left" class="bgcolor_001">
										<div class="form-group form-group-last">
											 
											<select NAME="upd_activated" size="1" class="form-control ">
												<?php
												   foreach ($actived_list as $key => $cur_value) {
												?>
													<option value='<?php echo $cur_value[1] ?>' <?php if ($upd_status==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>
												<?php } ?>
											</select><br/>
											<span class="input-bar "></span>
										</div>			
									</td>
                                </tr>
		                        
                                <tr>
									<td align="left" class="bgcolor_001" style="vertical-align: top;">
										<span class="icheck-inline">
											<input name="check[upd_credit]" type="checkbox" data-icheck="" style="top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php if ($check["upd_credit"]=="on") echo "checked"?>>
											<input name="mode[upd_credit]" type="hidden" value="2">
											<label>3) &nbsp;<?php echo gettext("CREDIT");?></label>
										</span>
									</td>
									<td align="left"  class="bgcolor_001">
										<div class="form-group form-group-last">
											 
											<input class="form-control" name="upd_credit" size="10" maxlength="10"  <?php if ($check["upd_credit"]=="on") echo "checked"?>>
											
											<input class="form-control" name="mode[upd_credit]" type="hidden" value="2">
										
											<br>
											<font class="version">
												<div class="kt-radio-inline">
													<label class="">
																						
														<input type="radio"style="top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" NAME="type[upd_credit]" value="1"  <?php if ((!isset($type["upd_credit"]))|| ($type["upd_credit"]==1) ) {?>checked<?php }?>><?php echo gettext("Equals");?>
														<span></span>
													</label>
													<label class="">
														<input type="radio" style="top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" NAME="type[upd_credit]" value="2"  <?php if ($type["upd_credit"]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
														<span></span>
													</label>
													<label class="">
														<input type="radio" style=" top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" NAME="type[upd_credit]" value="3"  <?php if ($type["upd_credit"]==3) {?>checked<?php }?>> <?php echo gettext("Subtract");?>
														<span></span>
													</label>
												</div>
											</font>
				                        <span class="input-bar "></span>
									</div>  
								</td>
							</tr>
							<tr>
                                <td align="left" class="bgcolor_001" style="width: 20%;vertical-align: top;">
									<span class="icheck-inline">
										<input name="check[upd_currency]" type="checkbox" data-icheck="" style=" top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php if ($check["upd_currency"]=="on") echo "checked"?>>
										<label>4) &nbsp;<?php echo gettext("CURRENCY");?></label>
									</span>
								</td>
								<td align="left"  class="bgcolor_001">
									<div class="form-group form-group-last">
										 
										<select NAME="upd_currency" size="1" class="form-control ">
											<?php
												foreach ($currencies_list as $key => $cur_value) {
											?>
												<option value='<?php echo $key ?>'  <?php if ($upd_currency==$key) echo 'selected="selected"'?>><?php echo $cur_value[1].' ('.$cur_value[2].')' ?></option>
											<?php } ?>
										</select><br/>
										<span class="input-bar "></span>
									</div>	
								</td>
							</tr>
		                        
							<tr>
								<!--<td align="left" class="bgcolor_001" style="width: 20%">
									<span class="icheck-inline">
									<input name="check[upd_tag]" type="checkbox" data-icheck="" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php if ($check["upd_tag"]=="on") echo "checked"?>>
			                        <label class="col-2 col-form-label">5)</label>
			                        </span>
								</td>-->
								<td align="left" class="bgcolor_001" style="width: 20%;vertical-align: top;">
									<span class="icheck-inline">
									<input name="check[upd_tag]" type="checkbox" data-icheck="" style="top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php if ($check["upd_tag"]=="on") echo "checked"?>>
			                        <label>5) &nbsp; <?php echo gettext("CURRENCY TAG");?></label>
			                        </span>
								</td>
								<td align="left"  class="bgcolor_001">
									<div class="form-group form-group-last">		
										 
										<input class="form-control"  name="upd_tag" size="10" maxlength="6" value="<?php echo $upd_tag; ?>">
										<br/>
										<span class="input-bar "></span>
			                        </div>
                                </td>
                            </tr>
							<tr>
								<td align="right" class="bgcolor_001"></td>
								<td align="right"  class="bgcolor_001">
									<input class="btn btn-primary btn-small"  value=" <?php echo gettext("BATCH UPDATE VOUCHER");?>  " type="submit">
									
									<input class="btn btn-danger btn-small"  value=" <?php echo gettext("CANCEL");?>  " type="submit">
								</td>
							</tr> 
						</table>   
                    </form> 
                </center>
        </div>
</div>



<!-- ** ** ** ** **FORM END for the BATCH Update ** ** ** ** **** ** ** ** **** ** ** ** **** ** ** ** ** -->


<?php
} // END if ($form_action == "list")

if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg;

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// Code for the Export Functionality
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM  $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";
?>

</div>
<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>

<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
