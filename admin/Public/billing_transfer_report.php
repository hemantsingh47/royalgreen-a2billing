<?php

 

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_balance_report.inc';
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
<SCRIPT LANGUAGE="javascript">
function sendValue(selvalue) {
    window.opener.document.<?php echo $popup_formname ?>.<?php echo $popup_fieldname ?>.value = selvalue;
    window.close();
}
</script>
<?php

}
?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Reports                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Reports                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                             Other Reports                     </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_transfer_report.php" class="kt-subheader__breadcrumbs-link">
                             Balance Transfer Report              </a>
                                        <!--			
									<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
	
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h1 class="kt-portlet__head-title">
            <?php echo gettext('Balance Transfer Report'); ?>
	      </h1>
        </div>
    </div>
	<br>
	
	<?php

// #### HELP SECTION
if (!$popup_select) {
    echo $CC_help_provider;
}
      
//echo $CALL_LABS;

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

//echo "<br/>";

$HD_Form->create_form($form_action, $list, $id = null);
     echo"</div>";  
	 ?>
	 </div>
	 <br><BR><BR><BR><br><BR><BR><BR><br><BR><BR><BR><br><BR><BR><BR>
	 <?php
// #### FOOTER SECTION
if (!$popup_select) {
    $smarty->display('footer.tpl');
}
