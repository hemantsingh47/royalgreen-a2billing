<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_currencies.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_BILLING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('updatecurrency'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

/********************************* BATCH UPDATE CURRENCY TABLE ***********************************/
$A2B -> DBHandle = $HD_Form -> DBHandle;

if ($updatecurrency == 1) {
    // Check demo mode
    check_demo_mode();
    // Update Currencies
    $instance_table = new Table();
    $A2B -> set_instance_table ($instance_table);
    $return = currencies_update_yahoo($A2B -> DBHandle, $A2B -> instance_table);
    $update_msg = '<center><font color="green"><b>'.$return.'</b></font></center>';
}

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_currency;

?>
 <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Currency Details                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_def_ratecard.php?atmenu=ratecard&section=6" class="kt-subheader__breadcrumbs-link">
                            Others                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_currencies.php?section=10" class="kt-subheader__breadcrumbs-link">
                            Currency Lists                       </a>
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
			<?php if ($form_action == 'ask-add'){echo gettext("Add Currency"); }else if( $form_action == 'ask-delete'){echo gettext("Delete Currency");} else if( $form_action == 'ask-edit'){echo gettext("Modify Currency");} else{echo gettext("Currency Details"); }?>
		</h1>
	</div>
</div>



<div align="center">
<table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
    <FORM name="updateForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>" method="post">
    <INPUT type="hidden" name="updatecurrency" value="1">
    <?php
        if ($HD_Form->FG_CSRF_STATUS == true) {
    ?>
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
    <?php
        }
    ?>
    <tr>
      <td align="center"  class="bgcolor_001">
        <span class="form-text text-muted"><?php echo gettext("THE CURRENCY LIST IS BASED FROM YAHOO FINANCE"); ?></span></td>
		<td>
            <input class="btn btn-success"  value=" <?php echo gettext("CLICK HERE TO UPDATE NOW");?>  " type="submit">
        </td>
    </tr>
	
    </form>
</table>
</div>
<br>
<?php

if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg;

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');
