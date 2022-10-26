<?php

include_once '../lib/admin.defines.php';
include_once '../lib/admin.module.access.php';
include_once '../lib/Form/Class.FormHandler.inc.php';
include_once '../lib/admin.smarty.php';
include_once './form_data/FG_var_charge.inc';

if (!has_rights(ACX_BILLING)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form_c->setDBHandler(DbConnect());

$HD_Form_c->init();

// To fix internal links due $_SERVER["PHP_SELF"] from parent include that fakes them
if ($wantinclude == 1) {
    $HD_Form_c->FG_EDITION_LINK = "billing_entity_charge.php?form_action=ask-edit&id=";
    $HD_Form_c->FG_DELETION_LINK = "billing_entity_charge.php?form_action=ask-delete&id=";
}

if ($id != "" || !is_null($id)) {
    $HD_Form_c->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form_c->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
    $form_action = "list"; //ask-add
if (!isset ($action))
    $action = $form_action;

$list = $HD_Form_c->perform_action($form_action);

if ($wantinclude != 1) {
    // #### HEADER SECTION
    $smarty->display('main.tpl');

    // #### HELP SECTION
    echo $CC_help_edit_charge;
}
?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Customer Charges List                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer Billing                       </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_charge.php?section=10" class="kt-subheader__breadcrumbs-link">
                            Charges                 </a>
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
           <?php if ($form_action == 'ask-add'){echo gettext("Add Customer Charges"); }else if( $form_action == 'ask-delete'){echo gettext("Delete Customer Charges");} else if( $form_action == 'ask-edit'){echo gettext("Modify Customer Charges");} else{echo gettext("Customer Charges List"); }?>
		   
	    </h5>
        </div>
	</div>
<br>
<?php
// #### TOP SECTION PAGE
$HD_Form_c->create_toppage($form_action);

$HD_Form_c->create_form($form_action, $list, $id = null);
?>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php 
if ($wantinclude != 1) {
    $smarty->display('footer.tpl');
}
