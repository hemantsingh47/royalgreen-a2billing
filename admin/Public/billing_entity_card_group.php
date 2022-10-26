<?php



include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_card_group.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_CUSTOMER)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('popup_select', 'popup_formname', 'popup_fieldname'));

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


echo '<table class="" style="width:98%; margin:0 auto;"><tr><td style=" border:0">';

// #### HELP SECTION
echo $CC_help_list_group;
if ( ($form_action)=='list' && $form_action != 'ask-add' && $form_action != 'ask-edit' ){
?>

<style type="text/css">
 input, textarea, .uneditable-input 
 {
    width: auto;  
 }
</style> 
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
            Group Information                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            User                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="billing_entity_card.php" class="kt-subheader__breadcrumbs-link">
                            Customer                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_card_group.php" class="kt-subheader__breadcrumbs-link">
                            Groups                        </a>
                       
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>

<div class="kt-portlet">
<div class="kt-portlet__head">
      <div class="kt-portlet__head-label">
		<h1 class="kt-portlet__head-title">
            <?php echo gettext("Group Information");?>
	      </h1>
        </div>
    </div>
	<br>
<?php
}
// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);
?>
</div>

<?php
// #### FOOTER SECTION
if (!$popup_select)
    $smarty->display('footer.tpl');
