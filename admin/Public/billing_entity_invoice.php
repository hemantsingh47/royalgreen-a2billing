<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_invoice.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_INVOICING)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array (
    'id',
    'action'
));

$DBHandle = DbConnect();

if ($action == "lock") {
    if (!empty ($id) && is_numeric($id)) {
        $instance_table_invoice = new Table("cc_invoice");
        $param_update_invoice = "status = '1'";
        $clause_update_invoice = " id ='$id'";
        $instance_table_invoice->Update_table($DBHandle, $param_update_invoice, $clause_update_invoice, $func_table = null);
    }
    die();
}

$HD_Form->setDBHandler($DBHandle);
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

// #### HELP SECTION
echo $CC_help_view_invoice;

?>
 <style type="text/css">
 input, textarea, .uneditable-input 
 {
    width: auto;  
 }
</style> 

<script language="JavaScript" src="javascript/card.js"></script>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
            Invoices List                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_invoice.php?atmenu=payment&section=11#" class="kt-subheader__breadcrumbs-link">
                            Invoices                     </a>
                        
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
           <?php echo gettext("Invoices List"); ?>
	    </h5>
        </div>
	</div>
	
<br>
<script type="text/javascript">
<!--
    function toggle_visibility(tohide) {
       
       $("#"+id).toggle("slidedown");
       
    }
//-->
</script>
<script language="JavaScript" src="javascript/card.js"></script>
<div class="toggle_hide2show">
<center>
	<div class="form-actions" style=" padding: 10px 10px 10px; margin-top: 0px;margin-bottom: 0px;">  
        <a href="#" target="_self" class="btn btn-primary btn-small" onclick="toggle_visibility('tohide')" > 
                <font class="fontstyle_002">
                <?php echo gettext("SEARCH INVOICE");?> </font>
        </a><?php if (!empty($_SESSION['entity_invoice_selection'])) { ?>&nbsp;(<font style="color:#EE6564;" > <?php echo gettext("search activated"); ?> </font> ) <?php } ?> 
    </div>

</center>
    
	
	<div class="tohide" id="tohide" name="tohide" style="display:none;">
<?php
// #### CREATE SEARCH FORM
if ($form_action == "list") {
    $HD_Form -> create_search_form();
}
?>
    </div>
</div>

<?php
// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);

// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>

<script type="text/javascript">
$(document).ready(function () {
    $('.lock').click(function () {
        $.get("billing_entity_invoice.php", { id: ""+ this.id, action: "lock" },
            function(data){
                location.reload(true);
            });
        });
});
</script>
 