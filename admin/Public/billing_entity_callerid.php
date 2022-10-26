<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_callerid.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');



// begin:: Subheader
echo '<div class="kt-subheader   kt-grid__item" id="kt_subheader">';
    echo '<div class="kt-container  kt-container--fluid ">';
        echo '<div class="kt-subheader__main">';
            echo '<h3 class="kt-subheader__title"> Caller ID </h3>';
                echo '<span class="kt-subheader__separator kt-hidden"></span>';
                    echo '<div class="kt-subheader__breadcrumbs">';
                        echo '<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>';
                        echo '<a href="" class="kt-subheader__breadcrumbs-link">
                            User                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>';
                        echo '<a href="" class="kt-subheader__breadcrumbs-link">
                            Customer                         </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>';
                        echo '<a href="" class="kt-subheader__breadcrumbs-link">
                            Caller ID                        </a>';
                echo '</div>';
            echo '</span>';
        echo '</div>';
    echo '</div>';
echo '</div>';
//end:: Subheader

echo '<div class="col-md-12">';



// #### HELP SECTION
echo $CC_help_callerid_list;
if (isset($atmenu)) {

?>

<?php   
} 
 

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;
?>

<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
