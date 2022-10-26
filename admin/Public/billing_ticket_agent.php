<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';
include '../lib/support/classes/support_service.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_ticket_agent.inc';

if (!has_rights(ACX_SUPPORT)) {
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
$smarty->display('main.tpl');

?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Agent Tickets                          </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Support                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_ticket_agent.php" class="kt-subheader__breadcrumbs-link">
                            Agent Tickets                       </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>

<!-- end:: Subheader -->

<?php
//#### HELP SECTION
echo $CC_help_support_list_agent;

if ($form_action == "list") {
    echo "<h2>".gettext("Agent Ticket List")."</h2>";
   
    $HD_Form->create_search_form();
    
}

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);
?>

<?php
if ($form_action == "ask-add"){
    echo "<div class='kt-portlet' style='margin-bottom: 0px;'><div class='kt-portlet__head'><div class='kt-portlet__head-label'><h1 class='kt-portlet__head-title'>".gettext("Add a new Agent Ticket")."</h1></div></div></div>";
}
if ($form_action == "ask-edit"){
    echo "<div class='kt-portlet' style='margin-bottom: 0px;'><div class='kt-portlet__head'><div class='kt-portlet__head-label'><h1 class='kt-portlet__head-title'>".gettext("Modify the Agent Ticket")."</h1></div></div></div>";
}
$HD_Form->create_form($form_action, $list, $id = null);
// #### FOOTER SECTION
$smarty->display('footer.tpl');
