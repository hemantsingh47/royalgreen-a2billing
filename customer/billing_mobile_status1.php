<?php


include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'lib/customer.smarty.php';
include './lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_mobile.inc';

if (!has_rights(ACX_PAYMENT_HISTORY)) {
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
    $form_action = "list"; //ask-add
if (!isset ($action))
    $action = $form_action;

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

    echo '<h3 class="heading_b uk-margin-bottom">MOBILE STATUS</h3>';
         echo '<div class="md-card">';
               
                    echo '<div class="uk-grid" data-uk-grid-margin="">';
                           echo '<div class="uk-width-1-1 uk-row-first">';




// #### HELP SECTION
echo $CC_help_view_payment;

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);
                        echo '</div>';
                    echo '</div>';
                    echo '</div>';
                 
                 

    
// #### FOOTER SECTION
$smarty->display('footer.tpl');
