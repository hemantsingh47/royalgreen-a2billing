<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_did_billing.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_DID)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}
?>
<style type="text/css">
 input, textarea, .uneditable-input 
 {
    width: 217px;  
 }
</style> 
<?php

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
                DID                          </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
						DID                        </a>
                                            
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_did_billing.php?atmenu=did_billing&section=8" class="kt-subheader__breadcrumbs-link">
						DID Billing                        </a>
                       
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
			<?php if ($form_action == 'ask-add'){echo gettext("Add DID Billing"); }else if( $form_action == 'ask-delete'){echo gettext("Delete DID Billing");} else if( $form_action == 'ask-edit'){echo gettext("Modify DID Billing");} else{echo gettext("DID Billing List "); }?>
		</h1>
	</div>
</div>


<?php
// #### HELP SECTION
echo $CC_help_list_did_billing;

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);

// Include other file ( TODO , find a better way to include files )
$wantinclude = 1;
$order = 'id';
//include_once 'billing_entity_charge.php';

// #### FOOTER SECTION
$smarty->display('footer.tpl');
