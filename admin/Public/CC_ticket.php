<?php
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';
include '../lib/support/classes/support_service.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_ticket.inc';

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
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Customer Tickets                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Support                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="CC_ticket.php?section=4" class="kt-subheader__breadcrumbs-link">
                            Customer Tickets                       </a>
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
				<?php if ($form_action == 'ask-add'){echo gettext("Add Ticket"); }else if( $form_action == 'ask-delete'){echo gettext("Delete Ticket");} else if( $form_action == 'ask-edit'){echo gettext("Modify Ticket");} else{echo gettext("Customer Tickets"); }?>
				
			</h1>
		</div>
	</div>
	<br>
<?php
// #### HELP SECTION
echo $CC_help_support_list;
?>

<?php
if ($form_action == "list") {
    
    
    $HD_Form->create_search_form();
   
}
if ($form_action == "ask-add") {
    echo "<h2>".gettext("Add a new Ticket")."</h2>";
}
if ($form_action == "ask-edit") {
    echo "<h2>".gettext("Modify the Ticket")."</h2>";
}


// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);
?>

<?php
$HD_Form->create_form($form_action, $list, $id = null);
?>


<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
