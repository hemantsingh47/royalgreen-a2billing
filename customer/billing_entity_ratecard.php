<?php


include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_ratecard.inc';
include './lib/customer.smarty.php';

if (! has_rights (ACX_RATECARD)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('letter', 'posted_search'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

if (strlen($letter)==1) $HD_Form -> FG_TABLE_CLAUSE .= " AND (SUBSTRING(destination,1,1)='".strtolower($letter)."' OR SUBSTRING(destination,1,1)='".$letter."')"; // sort by first letter

$FG_LIMITE_DISPLAY=10;
if (isset($mydisplaylimit) && (is_numeric($mydisplaylimit) || ($mydisplaylimit=='ALL'))) {
    if ($mydisplaylimit=='ALL') {
        $FG_LIMITE_DISPLAY=5000;
    } else {
        $FG_LIMITE_DISPLAY=$mydisplaylimit;
    }
}

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

if ( ($form_action == "list") &&  ($HD_Form->FG_FILTER_SEARCH_FORM) && ($posted_search == 1 ) && isset($mytariff_id) ) {
    $HD_Form->FG_TABLE_CLAUSE = "idtariffplan='$mytariff_id'";
}

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if ($form_action == 'list') {
    echo $CC_help_ratecard.'';
}

 // #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

?>
    <!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Rate Card </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Rates                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            View Rates                         </a>
                </div>
            </span>
        </div>
    </div>
</div>
<!-- end:: Subheader -->

    <table width="80%" border=0 cellspacing=1 cellpadding=3 bgcolor="#000033" align="center">
        <tr>
       <td bgcolor="#fff" width="100%" valign="top" align="center" class="bb2">
              <a href="billing_entity_ratecard.php?form_action=list&letter="><?php echo gettext("NONE")?></a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=A">A</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=B">B</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=C">C</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=D">D</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=E">E</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=F">F</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=G">G</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=H">H</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=I">I</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=J">J</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=K">K</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=L">L</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=M">M</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=N">N</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=O">O</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=P">P</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=Q">Q</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=R">R</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=S">S</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=T">T</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=U">U</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=V">V</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=W">W</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=X">X</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=Y">Y</a> -
              <a href="billing_entity_ratecard.php?form_action=list&letter=Z">Z</a>
       </td>
        </tr>
    </table>
<?php

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### CREATE SEARCH FORM
if ($form_action == "list") {
    $HD_Form -> create_search_form();
}

// #### FOOTER SECTION
$smarty->display('footer.tpl');
