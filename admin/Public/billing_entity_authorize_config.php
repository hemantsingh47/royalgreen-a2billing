<?php


include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_authorize_confi.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_TRUNK)) {
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
if ($popup_select) {
?> 
<style type="text/css">
 input, textarea, .uneditable-input 
 {
    width: 217px;  
 }
</style> 
 
<SCRIPT LANGUAGE="javascript">
function sendValue(selvalue) {
    window.opener.document.<?php echo $popup_formname ?>.<?php echo $popup_fieldname ?>.value = selvalue;
    window.close();
}
</script>
<?php

}

// #### HELP SECTION
if (!$popup_select) {
    echo $CC_help_provider;
}

//echo $CALL_LABS;
if ($form_action == "list") {
    // begin:: Subheader
    echo '<div class="kt-subheader   kt-grid__item" id="kt_subheader">';
        echo '<div class="kt-container  kt-container--fluid ">';
            echo '<div class="kt-subheader__main">';
                echo '<h3 class="kt-subheader__title"> Authorize Configuration </h3>';
            echo '<span class="kt-subheader__separator kt-hidden"></span>';
                echo '<div class="kt-subheader__breadcrumbs">';
                    echo '<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>';
                    echo '<a href="" class="kt-subheader__breadcrumbs-link">
                        Billing                       </a>
                                <span class="kt-subheader__breadcrumbs-separator"></span>';
                    echo '<a href="" class="kt-subheader__breadcrumbs-link">
                        Payment Methods                         </a>
                                <span class="kt-subheader__breadcrumbs-separator"></span>';
                    echo '<a href="" class="kt-subheader__breadcrumbs-link">
                        Authorize.net Config                        </a>';
            echo '</div>';
        echo '</span>';
    echo '</div>';
echo '</div>';
echo '</div>';
//end:: Subheader
}

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);

// #### FOOTER SECTION
if (!$popup_select) {
    $smarty->display('footer.tpl');
}
