<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_alarm.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_MAINTENANCE)) {
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
if($form_action!="ask-add" && $form_action!="ask-edit"){
?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Add Alarms                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Others                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Tools                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_alarm.php?section=16" class="kt-subheader__breadcrumbs-link">
                            Alarms                      </a>
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
				<?php if ($form_action == 'ask-add'){echo gettext("Add Alarm"); }else if( $form_action == 'ask-delete'){echo gettext("Delete Alarms");} else if( $form_action == 'ask-edit'){echo gettext("Modify Alarms");} else{echo gettext("List of Alarms"); }?>
				
			</h1>
		</div>
	</div>
	<br>
<?php


// #### HELP SECTION
if ($form_action == 'ask-add')
    echo $CC_help_edit_alarm;
else
    echo $CC_help_list_alarm;

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);
?>


<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
