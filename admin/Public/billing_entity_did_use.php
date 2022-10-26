<?php

include_once '../lib/admin.defines.php';
include_once '../lib/admin.module.access.php';
include_once '../lib/Form/Class.FormHandler.inc.php';
include_once '../lib/admin.smarty.php';
include_once './form_data/FG_var_diduse.inc';

if (!has_rights(ACX_DID)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form->setDBHandler(DbConnect());

$HD_Form->init();

if ($id != "" || !is_null($id)) {
    $HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
    $form_action = "list";
if (!isset ($action))
    $action = $form_action;

$smarty->display('main.tpl');
?>
	<script language="JavaScript" src="javascript/card.js"></script>


<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                DID                          </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
						DID                        </a>
                                            
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_did_use.php?atmenu=did_use&section=8" class="kt-subheader__breadcrumbs-link">
						DID in Use                        </a>
                       
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
			<?php echo gettext("DID Usage"); ?>
		</h1>
	</div>
</div>

<?php
// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

switch ($actionbtn) {
    case "release_did":
    echo $CC_help_release_did;
    ?>
	
<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
<center>
    <FORM action=<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?> id=form1 method=post name=form1 class="kt-form">
        <INPUT type="hidden" name="did" value="<?php echo $did?>">
        <INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
        <INPUT type="hidden" name="actionbtn" value="ask_release">
        <?php
            if ($HD_Form->FG_CSRF_STATUS == true) {
        ?>
            <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
            <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
        <?php
            }
        ?>
        <br><br>
        <br><br>
        <TABLE cellspacing="0" class="delform_table5">
            <tr>
                <td width="434" class="text_azul"><?php echo gettext("If you really want release this DID , Click on the 	release button.")?>
                </td>
            </tr>
            <tr height="2">
                <td style="border-bottom: medium dotted rgb(255, 119, 102);">&nbsp; </td>
            </tr>
            <tr>
                    <td width="190" align="right" class="text"><INPUT value="<?php echo gettext("Release the DID ");?> " alt="<?php echo gettext("Release the DID "); ?>" hspace=2 id=submit22 name=submit22 class="btn btn-primary" type="submit"></td>
            </tr>
        </TABLE>
    </FORM>
<?php
    break;
    case "ask_release":
        $instance_table = new Table();
        $QUERY = "UPDATE cc_did set iduser = 0 ,reserved=0 where id=$did" ;
        $result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

        $QUERY = "UPDATE cc_did_use set releasedate = now() where id_did =$did and activated = 1" ;
        $result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

        $QUERY = "insert into cc_did_use (activated, id_did) values ('0','".$did."')";
        $result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

        $QUERY = "delete FROM cc_did_destination where id_cc_did =".$did;
        $result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

    break;
}

if (!isset($actionbtn) || $actionbtn=="ask_release") {

echo $CC_help_list_did_use;

if (!isset($inuse) || $inuse=="")$inuse=1;
/*<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->*/?>
    <center>
    <FORM METHOD=POST class="kt-form" name="myForm" ACTION="<?php echo $PHP_SELF?>?order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
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

        <table class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tbody>
			
				<tr>
					<td align="left" valign="top" class="bgcolor_004" style="border:0">
					   <label class="">
							<?php echo gettext("Enter the DID id");?>: </label>
					</td>
					<td class="bgcolor_005" align="left" style="border:0">
                		<INPUT TYPE="text" NAME="did" value="<?php echo $did?>" class="form-control13" style="width: 66%;">
					</td>
				</tr>
        
				<tr>
					<td align="left" valign="top" class="bgcolor_004" style="border:0">
						<label class="">
						<?php echo gettext("Options");?>: </label>
					</td>
					
					<td class="bgcolor_004" align="left" style="border-top:0;">
						<b><?php echo gettext("Show")?>:</b>
					
						<input name="inuse" type=radio value=1 <?php if ($inuse) {?>checked<?php } ?>>
						<?php echo gettext("Dids in use")?>
					
						<input name="inuse" type="radio" value=0 <?php if (!$inuse) {?>checked<?php } ?>>
						<?php echo gettext("All Dids")?>
					</td>
				</tr>
				
				<tr>
                <td class="bgcolor_004" align="left" > </td>
                <td class="bgcolor_005" align="right" >
                    <input type="submit"  name="image16" align="top" border="0" value="Search" class="btn btn-primary" />

                  </td>
            </tr>
        </tbody></table>
    </FORM>
</center>

<br><br>
   
        <!--<tr>
                <td class="bgcolor_004" align="left" >
            </td>
            <td class="bgcolor_005" align="center" >
                <input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
              </td>
            </tr>
        </tbody></table>
    </FORM>
</center>-->
<?php

$list = $HD_Form -> perform_action($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

}
$smarty->display('footer.tpl');
