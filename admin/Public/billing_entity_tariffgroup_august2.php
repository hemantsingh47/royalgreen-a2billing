<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_tariffgroup.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_RATECARD)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

if ($id != "" || !is_null($id)) {
    $HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action)) {
    $form_action = "list"; //ask-add
}
if (!isset ($action)) {
    $action = $form_action;
}

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');
?>
<SCRIPT LANGUAGE="javascript">
<!--
function sendValue(selvalue) {
    window.opener.document.<?php echo $popup_formname ?>.<?php echo $popup_fieldname ?>.value = selvalue;
    window.close();
}
// -->
</script>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Rates                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Call Plan                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_def_ratecard.php?atmenu=ratecard&section=6" class="kt-subheader__breadcrumbs-link">
                             List Call Plan                       </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_tariffgroup.php" class="kt-subheader__breadcrumbs-link">
                            Call Plan Options                         </a>
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
			<?php if ($form_action == 'ask-add'){echo '<h3>'.gettext("Add Call Plan").'</h3>'; } else if( $form_action == 'ask-edit'){echo '<h3>'.gettext("Edit properties of Call Plan").'</h3>'; } else{echo '<h3>'.gettext("Call Plan").'</h3>'; } ?>
		</h1>
	</div>
</div>

<?php

// #### HELP SECTION
if ($form_action == 'list') {
    if (!$popup_select) {
        echo $CC_help_list_tariffgroup;
    }
} else {
    echo $CC_help_list_tariffgroup;
}

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);

// #### FOOTER SECTION
if (!$popup_select) {
    $smarty->display('footer.tpl');
}
