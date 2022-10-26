<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include '../lib/admin.smarty.php';
include '../lib/epayment/classes/payment.php';
include '../lib/epayment/classes/objectinfo.php';
include '../lib/epayment/classes/table_block.php';
include '../lib/epayment/classes/box.php';
include '../lib/epayment/includes/general.php';
include '../lib/epayment/includes/html_output.php';

if (!has_rights(ACX_BILLING)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('action', 'configuration', 'id', 'configuration', 'result'));

$nowDate = date("m/d/y");
$message = "";
if ($result == "success") {
    $message = gettext("Record updated successfully");
}
$instance_sub_table = new Table("cc_payment_methods", "payment_filename");
if (!empty ($id)) {
    $paymentMethodID = intval($id);
} else {
    exit (gettext("Payment module ID not found"));
}

$QUERY = " id = %u";
$QUERY = sprintf($QUERY, $paymentMethodID);

$DBHandle = DbConnect();
$return = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
$paymentMethod = substr($return[0][0], 0, strrpos($return[0][0], '.'));

$instance_sub_table = new Table("cc_configuration", "payment_filename");
$QUERY = " active = 't'";

$return = null;

if (tep_not_null($action)) {
    switch ($action) {
        case 'save' :
            while (list ($key, $value) = each($configuration)) {
                if ($key == 'MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC') {
                    $value = join($value, ', ');
                }
                $instance_sub_table->Update_table($DBHandle, "configuration_value = '" . $value . "'", "configuration_key = '" . $key . "'");
            }
            tep_redirect("billing_entity_payment_settings.php?" . 'method=' . $paymentMethod . "&id=" . $id . "&result=success");
            break;
    }
}
?>




<?php
$payment_modules = new payment($paymentMethod);
$GLOBALS['paypal']->enabled = true;
$GLOBALS['moneybookers']->enabled = true;
$GLOBALS['authorizenet']->enabled = true;
$GLOBALS['worldpay']->enabled = true;
$GLOBALS['plugnpay']->enabled = true;
$GLOBALS['iridium']->enabled = true;
$GLOBALS['ebspayment']->enabled = true;
$GLOBALS['stripe']->enabled = true;
$GLOBALS['sslcommerz']->enabled = true;

$module_keys = $payment_modules->keys();
echo '<pre>';print_r($module_keys);echo '</pre>';
$keys_extra = array ();
$instance_sub_table = new Table("cc_configuration", "configuration_title, configuration_value, configuration_description, use_function, set_function");

for ($j = 0, $k = sizeof($module_keys); $j < $k; $j++) {
    $QUERY_CLAUSE = " configuration_key = '" . $module_keys[$j] . "'";
    $key_value = $instance_sub_table->Get_list($DBHandle, $QUERY_CLAUSE, 0);
    $keys_extra[$module_keys[$j]]['title'] = $key_value[0]['configuration_title'];
    $keys_extra[$module_keys[$j]]['value'] = $key_value[0]['configuration_value'];
    $keys_extra[$module_keys[$j]]['description'] = $key_value[0]['configuration_description'];
    $keys_extra[$module_keys[$j]]['use_function'] = $key_value[0]['use_function'];
    $keys_extra[$module_keys[$j]]['set_function'] = $key_value[0]['set_function'];
}

$module_info['keys'] = $keys_extra;
$mInfo = new objectInfo($module_info);

$keys = '';
reset($mInfo->keys);
while (list ($key, $value) = each($mInfo->keys)) {
    $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';
    if ($value['set_function']) {
        eval ('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
    } else {
        $keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']);
    }
    $keys .= '<br><br>';
}

$keys = substr($keys, 0, strrpos($keys, '<br><br>'));
$heading[] = array (
    'text' => '<b>' . $mInfo->title . '</b>'
);
$contents = array (
    'form' => tep_draw_form('modules',
    "billing_entity_payment_settings.php?" . 'method=' . $paymentMethod . '&action=save&id=' . $id
));
$contents[] = array (
    'text' => $keys
);
$contents[] = array (
    'align' => 'center',
    'text' => '<br><input type=submit name=submitbutton value=Update class="btn btn-brand"> <a href="billing_entity_payment_configuration.php?atmenu=payment"><input type="button" name="cancelbutton" value="Cancel" class="btn btn-danger"></a>'
);

$smarty->display('main.tpl');

echo $CC_help_payment_config;

echo $PAYMENT_METHOD;

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
            Payment Settings                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Payment Methods                      </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Paypal & Others                  </a>
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
           <?php echo gettext("Payment method Settings"); ?>
	    </h5>
        </div>
	</div>

<table class="table" cellspacing="0" cellpadding="0">
 
<tr >
    <td>
		<label class="col-12 col-form-label">
			<?php echo $message ?>
		</label>
	</td>
</tr>

    <tr>
        <?php
        if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
            echo '            <td width="25%" valign="top">' . "\n";

            $box = new box;
            echo $box->infoBox($heading, $contents);
            echo '            </td>' . "\n";
        }
        ?>
    </tr>
</table>

<?php

$smarty->display('footer.tpl');
