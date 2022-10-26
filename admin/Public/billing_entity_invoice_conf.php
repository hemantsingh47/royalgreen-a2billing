<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_INVOICING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

/***********************************************************************************/

$DBHandle  = DbConnect();
if ($form_action=="ask-modif") {

    getpost_ifset(array('company_name','address','zipcode','country','city','phone','fax','email','vat','web','display_account'));

    $table_invoice_conf= new Table("cc_invoice_conf");
    $param_update_conf = "value ='".$company_name."'";
    $clause_update_conf = "key_val = 'company_name'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$address."'";
    $clause_update_conf = "key_val = 'address'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$zipcode."'";
    $clause_update_conf = "key_val = 'zipcode'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$country."'";
    $clause_update_conf = "key_val = 'country'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$city."'";
    $clause_update_conf = "key_val = 'city'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$phone."'";
    $clause_update_conf = "key_val = 'phone'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$fax."'";
    $clause_update_conf = "key_val = 'fax'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$phone."'";
    $clause_update_conf = "key_val = 'phone'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$email."'";
    $clause_update_conf = "key_val = 'email'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$vat."'";
    $clause_update_conf = "key_val = 'vat'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$web."'";
    $clause_update_conf = "key_val = 'web'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

    $param_update_conf = "value ='".$display_account."'";
    $clause_update_conf = "key_val = 'display_account'";
    $table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

}

// #### HEADER SECTION
$smarty->display( 'main.tpl');

$table_invoice_conf= new Table("cc_invoice_conf","value");
$clause_update_conf = "key_val = 'company_name'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$company_name=$result[0][0];

$clause_update_conf = "key_val = 'address'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$address=$result[0][0];

$clause_update_conf = "key_val = 'zipcode'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$zipcode=$result[0][0];

$clause_update_conf = "key_val = 'country'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$country=$result[0][0];

$clause_update_conf = "key_val = 'city'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$city=$result[0][0];

$clause_update_conf = "key_val = 'phone'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$phone=$result[0][0];

$clause_update_conf = "key_val = 'fax'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$fax=$result[0][0];

$clause_update_conf = "key_val = 'phone'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$phone=$result[0][0];

$clause_update_conf = "key_val = 'email'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$email=$result[0][0];

$clause_update_conf = "key_val = 'vat'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$vat=$result[0][0];

$clause_update_conf = "key_val = 'web'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$web=$result[0][0];

$clause_update_conf = "key_val = 'display_account'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$display_account=$result[0][0];

?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
            Invoices Configuration                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_invoice.php?atmenu=payment&section=11#" class="kt-subheader__breadcrumbs-link">
                            Invoices                     </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_invoice_conf.php?atmenu=payment&section=11" class="kt-subheader__breadcrumbs-link">
                            Invoices  Configuration                   </a>
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
           <?php echo gettext("Invoices Configuration"); ?>
	    </h5>
        </div>
	</div>
<br>
<form method="post" action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-modif"?>" name="frmPass" class="kt-form">
<table width="100%">
    <tr>
        <td align="center" valign="middle">
            <span class="form-text text-muted"><?php echo gettext("Here you can configure information that you want to use to generate the invoice") ?></span>
        </td>
    </tr>
</table>
<br/>
<table class="editform_table1" cellspacing="2">
<tr>
    <td class="form_head" width="25%" valign="middle">
        <label class="control-label-ag"><?php echo gettext("Company Name")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" >
        <input name="company_name" type="text" class="form-control" <?php if(!empty($company_name)) echo 'value="'.$company_name.'"';?> > <br/>
        <span class="form-text text-muted"><?php echo gettext("Insert your company name"); ?></span>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
       <label class="control-label-ag"> <?php echo gettext("Address")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" >
        <input name="address" type="text" class="form-control" <?php if(!empty($address)) echo 'value="'.$address.'"';?> > <br/>
        <span class="form-text text-muted"><?php echo gettext("Insert your address"); ?></span>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
        <label class="control-label-ag"><?php echo gettext("Zip Code")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" >
        <input name="zipcode" type="text" class="form-control" <?php if(!empty($zipcode)) echo 'value="'.$zipcode.'"';?> > <br/>
        <span class="form-text text-muted"><?php echo gettext("Insert your zip code"); ?></span>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
       <label class="control-label-ag"> <?php echo gettext("City")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" >
        <input name="city" type="text" class="form-control" <?php if(!empty($city)) echo 'value="'.$city.'"';?> > <br/>
       <span class="form-text text-muted"> <?php echo gettext("Insert your city"); ?></span>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
        <label class="control-label-ag"><?php echo gettext("Phone number")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" >
        <input name="phone" type="text" class="form-control" <?php if(!empty($phone)) echo 'value="'.$phone.'"';?> > <br/>
        <span class="form-text text-muted"><?php echo gettext("Insert your phone number"); ?></span>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
         <label class="control-label-ag"><?php echo gettext("Fax number")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" >
        <input name="fax" type="text" class="form-control" <?php if(!empty($fax)) echo 'value="'.$fax.'"';?> > <br/>
        <span class="form-text text-muted"><?php echo gettext("Insert your fax number"); ?></span>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
        <label class="control-label-ag"><?php echo gettext("Email")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" >
        <input name="email" type="text" class="form-control" <?php if(!empty($email)) echo 'value="'.$email.'"';?> > <br/>
        <span class="form-text text-muted"><?php echo gettext("Insert your email"); ?></span>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
        <label class="control-label-ag"><?php echo gettext("Web Site")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" >
        <input name="web" type="text" class="form-control" <?php if(!empty($web)) echo 'value="'.$web.'"';?> > <br/>
        <span class="form-text text-muted"><?php echo gettext("Insert your Web site"); ?></span>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
       <label class="control-label-ag"> <?php echo gettext("VAT number")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
        <input name="vat" type="text" class="form-control" <?php if(!empty($vat)) echo 'value="'.$vat.'"';?> > <br/>
       <span class="form-text text-muted"> <?php echo gettext("Insert your vat number"); ?></span>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
        <label class="control-label-ag"><?php echo gettext("Display Account number")?></label>
    </td>
    <td class="tableBodyRight" width="75%" valign="top" >
        <select name="display_account" class="form-control">
            <option value="1" <?php if($display_account==1) echo "selected"; ?> > <?php echo gettext("YES")?></option>
            <option value="0" <?php if($display_account==0) echo "selected"; ?> ><?php echo gettext("NO")?></option>
        </select>
       <span class="form-text text-muted">  <?php echo gettext("Choose if you want display the account number on the invoices"); ?></span>
    </td>
</tr>

<tr>
    <br><td align=right colspan=2 ><input type="submit" name="submitPassword" value="&nbsp;<?php echo gettext("Save")?>&nbsp;" class="btn btn-primary" onclick="return CheckPassword();" ></td>
</tr>

</table>
</form>
<br>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');
