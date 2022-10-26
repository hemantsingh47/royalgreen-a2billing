<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_voucher.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_BILLING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('choose_list', 'addcredit', 'gen_id', 'cardnum', 'choose_currency', 'expirationdate', 'addcredit','tag_list'));

$HD_Form -> setDBHandler (DbConnect());

$HD_Form -> FG_FILTER_SEARCH_FORM = false;
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_OTHER_BUTTON1 = false;
$HD_Form -> FG_OTHER_BUTTON2 = false;
$HD_Form -> FG_FILTER_APPLY = false;
$HD_Form -> FG_LIST_ADDING_BUTTON1 = false;
$HD_Form -> FG_LIST_ADDING_BUTTON2 = false;

$nbvoucher = $choose_list;

if ($nbvoucher>0) {

        check_demo_mode();

        $FG_ADITION_SECOND_ADD_TABLE  = "cc_voucher";
        $FG_ADITION_SECOND_ADD_FIELDS = "voucher, credit, activated, tag, currency, expirationdate";
        $instance_sub_table = new Table($FG_ADITION_SECOND_ADD_TABLE, $FG_ADITION_SECOND_ADD_FIELDS);

        $gen_id = time();
        $_SESSION["IDfilter"]=$tag_list;

        for ($k=0;$k < $nbvoucher;$k++) {
            $vouchernum = generate_unique_value($FG_ADITION_SECOND_ADD_TABLE, LEN_VOUCHER, 'voucher');
            $FG_ADITION_SECOND_ADD_VALUE  = "'$vouchernum', '$addcredit', 't', '$tag_list', '$choose_currency', '$expirationdate'";

            $result_query = $instance_sub_table -> Add_table ($HD_Form -> DBHandle, $FG_ADITION_SECOND_ADD_VALUE, null, null);
        }
}

if (!isset($_SESSION["IDfilter"])) $_SESSION["IDfilter"]='NODEFINED';
$HD_Form -> FG_TABLE_CLAUSE = "tag='".$_SESSION["IDfilter"]."'";

$HD_Form -> init();

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');
// #### HELP SECTION
echo $CC_help_generate_voucher;

?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Billing                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                             Vouchers                     </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_voucher_multi.php?section=10" class="kt-subheader__breadcrumbs-link">
                             Generate Vouchers                </a>
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
            <?php echo gettext("Generate Vouchers"); ?>
			
	      </h1>
        </div>
    </div>

<div align="center">
<table align="center" class="bgcolor_001" border="0" width="85%" cellpadding="10" cellspacing="10">
<tbody><tr>
<form name="theForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL) ?>">
    <?php
        if ($HD_Form->FG_CSRF_STATUS == true) {
    ?>
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
    <?php
        }
    ?>
    <td align="left">
        <label class="col-12 col-form-label">1)</label></td>
		<td>
        <select name="choose_list" size="1" class="form-control">
            <option value=""><?php echo gettext("Choose the number of vouchers to create");?></option>
            <option class="input" value="5"><?php echo gettext("5 Voucher");?></option>
            <option class="input" value="10"><?php echo gettext("10 Vouchers");?></option>
            <option class="input" value="50"><?php echo gettext("50 Vouchers");?></option>
            <option class="input" value="100"><?php echo gettext("100 Vouchers");?></option>
            <option class="input" value="200"><?php echo gettext("200 Vouchers");?></option>
            <option class="input" value="500"><?php echo gettext("500 Vouchers");?></option>
        </select>
       </td>
	   </tr>
	   <tr>
	   <td>

        <label class="col-12 col-form-label">2)</td>
		<td>
        <?php echo gettext("Amount of credit");?> : </label>	<input class="form-control" name="addcredit" size="10" maxlength="10" >
        </td>
		</tr>
		<tr>
		<td>

        <label class="col-12 col-form-label">3)</label></td>
		<td>
        <select NAME="choose_currency" size="1" class="form-control">
        <?php
        foreach ($currencies_list as $key => $cur_value) {
        ?>
        <option value='<?php echo $key ?>'><?php echo $cur_value[1].' ('.$cur_value[2].')' ?></option>
        <?php } ?>
        </select>
       </td>
	   </tr>
	   
	   <tr>
	   <td>

        <?php
            $begin_date = date("Y");
            $begin_date_plus = date("Y") + 10;
            $end_date = date("-m-d H:i:s");
            $comp_date = "value='".$begin_date.$end_date."'";
            $comp_date_plus = "value='".$begin_date_plus.$end_date."'";
        ?>
        <label class="col-12 col-form-label">4) </td>
		<td><?php echo gettext(" Expiration date");?> :</label> 
			<input class="form-control"  name="expirationdate" size="40" maxlength="40" <?php echo $comp_date_plus; ?>> 
				<span class="form-text text-muted"><?php echo gettext("(respect the format YYYY-MM-DD HH:MM:SS)");?></span>
        </td>
		</tr>
		<tr>
		<td>
        <label class="col-12 col-form-label">5) <?php echo gettext(" Tag");?> :</label></td>
		<td>
			<input class="form-control"  name="tag_list" size="40" maxlength="40">
        </td>
		
		</tr>
		<tr>
        <td align="right" valign="bottom" colspan="2">
            <input class="btn btn-primary" value=" GENERATE VOUCHER " type="submit">
        </td>
</form>
</tr>
</tbody></table>
<br>
</div>

<?php

$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) {
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
}
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!='')) {
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";
}

// #### FOOTER SECTION
$smarty->display('footer.tpl');
