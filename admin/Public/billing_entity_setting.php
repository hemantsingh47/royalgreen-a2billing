<?php



include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include '../lib/config_functions.php';
include './form_data/FG_var_config.inc';
include '../lib/admin.smarty.php';

if (!has_rights (ACX_ACXSETTING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form -> setDBHandler(DbConnect());
$HD_Form -> init();

if ($id!="" || !is_null($id)) {
    
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list";
if (!isset($action)) $action = $form_action;

if($form_action != "list")
    check_demo_mode();

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

echo $CC_help_list_configuration;
?>

<?php
// #### TOP SECTION PAGE
if ($id!="" || !is_null($id)) {
//echo'<h2>'. gettext("Modify Group Values ").'</h2>';
}
$HD_Form -> create_toppage ($form_action);

if ($form_action == "list") {

?>

<script language="javascript">
function go(URL) {
    if (Check()) {
        document.searchform.action = URL;
        alert(document.searchform.action);
        document.searchform.submit();
    }
}

function Check() {
    if (document.searchform.filterradio[1].value == "payment") {
        if (document.searchform.paymenttext.value < 0) {
            alert("Payment amount cannot be less than Zero.");
            document.searchform.paymenttext.focus();

            return false;
        }
    }
    return true;
}
</script>
  <style type="text/css">
 input, textarea, .uneditable-input 
 {
    width: 217px;  
 }
</style> 

<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
               Administrator                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Administrator                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Settings                      </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_setting.php" class="kt-subheader__breadcrumbs-link">
                            Configuration list                     </a>
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
				<?php echo gettext("CONFIGURATION LIST"); ?>
				
			</h1>
		</div>
	</div>
	<br>


<div class="col-md-12">
	<form name="searchform" id="searchform" method="post" action="billing_entity_setting.php">
    <input type="hidden" name="searchenabled" value="yes">
    <input type="hidden" name="posted" value="1">
    <?php
        if ($HD_Form->FG_CSRF_STATUS == true) {
    ?>
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
    <?php
        }
    ?>
	
	<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">
		
		<tbody>
			<tr>
				<td class="widget-title" colspan="5" style="border-top: 1px solid #CDCDCD; padding: 0px;  ">
				
					<label class="control-label" style="margin-bottom: 0px; width:200px;">Configuration Settings</label>
				</td>
			</tr>
			  
	




        <tr>
            <td width="19%" align="left" valign="top" class="fontstyle_searchoptions">
                <label class="col-form-label">&nbsp;&nbsp;<?php echo gettext("VALUE");?></label>
            </td>

            <td align="left"  class="bgcolor_003">
            <table>
                <tr>
                    <td align="left" valign="top"  width="10%">
                        <input class="form-control"  name="filterValue" size="20">
                    </td>

                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
						<input type="radio" NAME="rbValue" value="1" checked>
						<label class="btn btn-default" for="id1type"> <?php echo gettext("Exact");?></label>
                        <span></span>
						</label>
                    </td>
                    <td width="10%">
                         <label class="kt-radio" style="top:6px;">
						<input type="radio" NAME="rbValue" value="2"> 
						<label class="btn btn-default" for="id1type"><?php echo gettext("Begins with");?></LABEL>
                        <span></span>
						</label>
                    </td>
                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
                        <input type="radio" NAME="rbValue" value="3"> 
						<label class="btn btn-default" for="id1type"><?php echo gettext("Contains");?></label>
                        <span></span>
						</label>
                    </td>
                    <td width="10%">
                       <label class="kt-radio" style="top:6px;">
                        <input type="radio" NAME="rbValue" value="4"> 
						<label class="btn btn-default" for="id1type"><?php echo gettext("Ends with");?></label>
                        <span></span>
						</label>
                    </td>
                </td>
            </table>
            </td>
        </tr>

        <tr>
            <td width="19%" align="left" valign="top" class="bgcolor_004">
                <label class="col-form-label">&nbsp;&nbsp;<?php echo gettext("KEY");?></label>
            </td>

            <td align="left"  class="bgcolor_005">
            <table>
                <tr>
                    <td width="10%" align="left" valign="top" >
                        
						<input class="form-control" name="filterKey" size="20">
                    </td>

                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
                        <input type="radio" NAME="rbKey" value="1" checked>
							<label class="btn btn-default" for="id1type"><?php echo gettext("Exact");?></label>
                        <span></span>
						</label>
                    </td>
                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
                        <input type="radio" NAME="rbKey" value="2"> <label class="btn btn-default" for="id1type"><?php echo gettext("Begins with");?></label>
                       <span></span>
						</label>
                    </td>
                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
                        <input type="radio" NAME="rbKey" value="3"> <label class="btn btn-default" for="id1type"><?php echo gettext("Contains");?></label>
                        <span></span>
						</label>
                    </td>
                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
						
						<input type="radio" NAME="rbKey" value="4"> <label class="btn btn-default" for="id1type"><?php echo gettext("Ends with");?></label>
                        <span></span>
						</label>
                    </td>
                </td>
            </table>
            </td>
        </tr>

        <tr>
            <td width="19%" align="left" valign="top" class="bgcolor_004">
                <label class="col-form-label">&nbsp;&nbsp;<?php echo gettext("DESCRIPTION");?></label>
            </td>

            <td align="left"  class="bgcolor_003">
            <table>
                <tr>
                    <td width="10%" align="left" valign="top" width="10%">
                        <input class="form-control" name="filterDescription" size="20">
                    </td>

                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
                        <input type="radio" NAME="rbDesc" value="1" checked> <label class="btn btn-default" for="id1type"><?php echo gettext("Exact");?></label>
                        <span></span>
						</label>
                    </td>
                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
                        <input type="radio" NAME="rbDesc" value="2"> <label class="btn btn-default" for="id1type"><?php echo gettext("Begins with");?></label>
                        <span></span>
						</label>
                    </td>
                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
                        <input type="radio" NAME="rbDesc" value="3"> <label class="btn btn-default" for="id1type"><?php echo gettext("Contains");?></label>
                        <span></span>
						</label>
                    </td>
                    <td width="10%">
                        <label class="kt-radio" style="top:6px;">
                        <input type="radio" NAME="rbDesc" value="4"> <label class="btn btn-default" for="id1type"><?php echo gettext("Ends with");?></label>
                        <span></span>
						</label>
                    </td>
                </td>
            </table>
            </td>
        </tr>

        <tr>
            <td width="19%" align="left" valign="top" class="bgcolor_004">
                <label class="col-form-label">&nbsp;&nbsp;<?php echo gettext("SELECT GROUP");?></label>
            </td>
            <td width="81%" align="left" >
            <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
              <td class="fontstyle_searchoptions">
              <?php
                $instance_table = new Table();
                $QUERY = "SELECT * from cc_config_group";
                $list_total_groups  = $instance_table->SQLExec ($HD_Form -> DBHandle, $QUERY);
               ?>
            <select name="groupselect" class="form-control">
            <option value="-1" ><?php echo gettext("Select Group");?></option>
            <?php
            foreach ($list_total_groups as $groupname) {
            ?>
            <option value="<?php echo $groupname[1]?>" <?php if($groupselect == $groupname[1] || $groupname[1] == $_SESSION['grpselect']) echo "selected"?>><?php echo $groupname[1]?></option>
            <?php
            }
            ?>
            </select>
                </td>
            </tr></table></td>
        </tr>

        <tr>
            <td  align="left">&nbsp;</td>
            <td align="left">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="fontstyle_searchoptions">					<div align="right"><span class="bgcolor_005">
                  <input type="submit"  name="image16" align="left" border="0" value="<?php echo gettext("Search") ?>" class="btn btn-small btn-primary" />
                    </span> </div></td>
                </tr>
                </table>
            </td>
        </tr>
		<tbody>
    </table>
</FORM>
</center>
</div>
</div>
<?php
}

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');
