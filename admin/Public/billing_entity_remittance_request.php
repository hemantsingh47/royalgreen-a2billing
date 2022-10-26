<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_remittance_request.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_BILLING)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}
getpost_ifset(array (
    'id',
    'action'
));

$DBHandle = DbConnect();

if ($action == "accept") {
    if (!empty ($id) && is_numeric($id)) {
        $instance_table_remittance = new Table("cc_remittance_request","*");
        $param_update_remittance = "status = '1'";
        $clause_update_remittance = " id ='$id'";
        $instance_table_remittance->Update_table($DBHandle, $param_update_remittance, $clause_update_remittance, $func_table = null);
        // load
        $result=$instance_table_remittance -> Get_list($DBHandle,$clause_update_remittance);
        $type = $result[0]['type'];
        $agent_id = $result[0]['id_agent'];
        $credit = $result[0]['amount'];
        if ($type==0) {
            // insert refill
            $field_insert = " credit, agent_id, description";
            $value_insert = "'".$credit."', '$agent_id', '".gettext('REFILL BY REMITTANCE REQUEST')."'";
            $instance_sub_table = new Table("cc_logrefill_agent", $field_insert);
            $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null, 'id');

            //REFILL... UPDATE AGENT
            $instance_table_agent = new Table("cc_agent");
            $param_update_agent = "credit = credit + '".$credit."' , com_balance = com_balance - $credit ";
            $clause_update_agent = " id='$agent_id'";
            $instance_table_agent -> Update_table ($DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
        } else {
            //UPDATE AGENT
            $instance_table_agent = new Table("cc_agent");
            $param_update_agent = " com_balance = com_balance - $credit ";
            $clause_update_agent = " id='$agent_id'";
            $instance_table_agent -> Update_table ($DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
        }
    }
    die();
}

if ($action == "refuse") {
    if (!empty ($id) && is_numeric($id)) {
        $instance_table_remittance = new Table("cc_remittance_request");
        $param_update_remittance = "status = '2'";
        $clause_update_remittance = " id ='$id'";
        $instance_table_remittance->Update_table($DBHandle, $param_update_remittance, $clause_update_remittance, $func_table = null);
    }
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

?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Remittance request List                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                             Agent Billing                       </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_commission_agent.php?atmenu=payment&section=10" class="kt-subheader__breadcrumbs-link">
                            Remittance request                </a>
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
           <?php if ($form_action == 'ask-add'){echo gettext("Add Remittance request"); }else if( $form_action == 'ask-delete'){echo gettext("Delete Remittance request");} else if( $form_action == 'ask-edit'){echo gettext("Modify Remittance request");} else{echo gettext("Remittance request List"); }?>
		   
	    </h5>
        </div>
	</div>
<br>
<?php

// #### HELP SECTION
echo $CC_help_view_remittance_agent;

if ($form_action == "list") {
    $HD_Form->create_search_form();
}
// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);
?>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
?>
<script type="text/javascript">
$(document).ready(function () {
    $('.accept_click').click(function () {
        $.get("billing_entity_remittance_request.php", { id: ""+ this.id, action: "accept" },
              function(data){
                location.reload(true);
              });
        });
    $('.refuse_click').click(function () {
        $.get(billing_entity_remittance_request.php", { id: ""+ this.id, action: "refuse" },
              function(data){
                location.reload(true);
              });
        });
});
</script>
