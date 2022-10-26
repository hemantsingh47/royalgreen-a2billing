<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_user.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_ADMINISTRATOR)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

$HD_Form -> FG_EDITION_LINK= $_SERVER[PHP_SELF]."?form_action=ask-edit&groupID=$groupID&id=";
$HD_Form -> FG_DELETION_LINK= $_SERVER[PHP_SELF]."?form_action=ask-delete&groupID=$groupID&id=";

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

if ($form_action!="list") {
    check_demo_mode();
}

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if ($popup_select == "") {
    if ($form_action == 'ask-add') echo $CC_help_admin_edit;
    else echo $CC_help_admin_list;
}

if ($popup_select != "") {

?>

<style type="text/css">
 input, textarea, .uneditable-input 
 {
    width: 217px;  
 }

<SCRIPT LANGUAGE="javascript">
<!-- Begin
function sendValue(selvalue)
{
    window.opener.document.<?php echo $popup_formname ?>.<?php echo $popup_fieldname ?>.value = selvalue;
    window.close();
}
// End -->
</script>

<?php
}
?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                User                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Administrator                        </a>
                        
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
			<?php if ($form_action == 'ask-add'){echo '<h3>'.gettext("Add Administrator").'</h3>'; } else if( $form_action == 'ask-edit'){echo '<h3>'.gettext("Modify the properties of the Administrator	").'</h3>'; } else{echo '<h3>'.gettext("Administrator List").'</h3>'; } ?>
		</h1>
	</div>
</div>
<br>
<?php


// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;
?>

</div>
<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><br><BR><br><BR>
<?php
$smarty->display('footer.tpl');
