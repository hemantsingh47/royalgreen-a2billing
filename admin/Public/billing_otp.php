<?php
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_otp.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_TRUNK)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();


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
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Customer OTP                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                             Customer Billing                       </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_otp.php" class="kt-subheader__breadcrumbs-link">
                            Customer OTP                   </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
	
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
	<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h5 class="kt-portlet__head-title">
           <?php echo gettext("Customer's OTP"); ?>
	    </h5>
        </div>
	</div>
	<?php
       
	


 if ($form_action == "list") {
    $HD_Form->create_search_form();
}   
if ($popup_select) {
?>
<?php

}

// #### HELP SECTION
    

$HD_Form->create_form($form_action, $list, $id = null);

// Code for the Export Functionality
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM  $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
   $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";          
?>
</div>
<br><br><br><br><br><br>

<?php 

// #### FOOTER SECTION
if (!$popup_select) {
    $smarty->display('footer.tpl');
}



