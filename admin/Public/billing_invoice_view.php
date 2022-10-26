<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';
include '../lib/support/classes/invoice.php';
include '../lib/support/classes/invoiceItem.php';

if (! has_rights (ACX_INVOICING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id','curr'));

if (empty($id)) {
    Header ("Location: billing_entity_invoice.php?atmenu=payment&section=13");
}

$invoice = new invoice($id);
$items = $invoice->loadItems();
//load customer
$DBHandle  = DbConnect();
$card_table = new Table('cc_card','*');
$card_clause = "id = ".$invoice->getCard();
$card_result = $card_table -> Get_list($DBHandle, $card_clause, 0);
$card = $card_result[0];

if (empty($card)) {
    echo "Customer doesn't exist or is not correctly defined for this invoice !";
    die();
}
$smarty->display('main.tpl');
//Load invoice conf
$invoice_conf_table = new Table('cc_invoice_conf','value');
$conf_clause = "key_val = 'company_name'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$company_name = $result[0][0];

$conf_clause = "key_val = 'address'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$address = $result[0][0];

$conf_clause = "key_val = 'zipcode'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$zipcode = $result[0][0];

$conf_clause = "key_val = 'city'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$city = $result[0][0];

$conf_clause = "key_val = 'country'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$country = $result[0][0];

$conf_clause = "key_val = 'web'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$web = $result[0][0];

$conf_clause = "key_val = 'phone'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$phone = $result[0][0];

$conf_clause = "key_val = 'fax'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$fax = $result[0][0];

$conf_clause = "key_val = 'email'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$email = $result[0][0];

$conf_clause = "key_val = 'vat'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$vat_invoice = $result[0][0];

$conf_clause = "key_val = 'display_account'";
$result = $invoice_conf_table -> Get_list($DBHandle, $conf_clause, 0);
$display_account = $result[0][0];

//country convert
$table_country= new Table('cc_country','countryname');
$country_clause = "countrycode = '".$card['country']."'";
$result = $table_country -> Get_list($DBHandle, $country_clause, 0);
$card_country = $result[0][0];

//Currencies check
$currencies_list = get_currencies();
if (!isset($currencies_list[strtoupper($curr)][2]) || !is_numeric($currencies_list[strtoupper($curr)][2])) {$mycur = 1;$display_curr=strtoupper(BASE_CURRENCY);} else {$mycur = $currencies_list[strtoupper($curr)][2];$display_curr=strtoupper($curr);}

function amount_convert($amount)
{
    global $mycur;

    return $amount/$mycur;
}

if (!$popup_select) {
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
                        <a href="billing_entity_invoice.php?atmenu=payment&section=11#" class="kt-subheader__breadcrumbs-link">
                            Invoice                     </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                         <a href="billing_invoice_edit.php?id=1" class="kt-subheader__breadcrumbs-link">
                           View Invoice                     </a>
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
           <?php echo gettext("Invoice"); ?>
	    </h5>
        </div>
	</div>

<div style="text-align:right;">
<a href="javascript:;" onClick="window.open('<?php echo $PHP_SELF ?>?popup_select=1&id=<?php echo $id ?><?php if(!empty($curr)) echo "&curr=".$curr; ?>','','scrollbars=yes,resizable=yes,width=700,height=500')" > <img src="../Public/templates/default/images/printer.png" title="Print" alt="Print" border="0"></a></div>
 
<?php if (strtoupper(BASE_CURRENCY)!=strtoupper($card['currency'])) { ?>

    <select id="currency" class="form_input_select" name="curr" onChange="openURL('<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?id=$id"?>')">
        <option value="<?php echo BASE_CURRENCY;?>" <?php if(BASE_CURRENCY==$curr) echo "selected";?>  ><?php echo gettext('SYSTEM CURRENCY')." : ".strtoupper(BASE_CURRENCY); ?> </option>
        <option value="<?php echo $card['currency'];?>" <?php if($card['currency']==$curr) echo "selected";?>   ><?php echo gettext('CUSTOMER CURRENCY')." : ".strtoupper($card['currency']); ?></option>
    </select>

<?php
    }

} else {
?>
<p ALIGN="right"> <a href="javascript:window.print()"> <img src="./templates/default/images/printer.png" title="Print" alt="Print" border="0"> <?php echo gettext("Print"); ?></a> &nbsp; &nbsp;</p>
<?php
}
?>

<div class="invoice-wrapper">
  <table class="table">
  <thead>
  <tr class="one">
    <td class="one" width="40%">
     <h1><?php echo gettext("INVOICE"); ?></h1>
     <div class="client-wrapper">
         <div class="company-name break"><?php echo $card['company_name'] ?></div>
         <div class="fullname"><?php echo $card['lastname']." ".$card['firstname'] ?></div>
           <div class="address"><span class="street"><?php echo $card['address'] ?></span> </div>
           <div class="zipcode-city"><span class="zipcode"><?php echo $card['zipcode'] ?></span> <span class="city"><?php echo $card['city'] ?></span></div>
          <div class="country break"><?php echo $card_country; ?></div>
           <?php if (!empty($card['VAT_RN'])) { ?>
               <div class="vat-number"><?php echo gettext("VAT nr.")." : ".$card['VAT_RN']; ?></div>
           <?php } ?>
     </div>
    </td>
    <td class="two" width="30%">

    </td>
    <td class="three" width="30%">
     <div class="supplier-wrapper">
       <div class="company-name"><?php echo $company_name ?></div>
       <div class="address"><span class="street"><?php echo $address ?></span> </div>
       <div class="zipcode-city"><span class="zipcode"><?php echo $zipcode ?></span> <span class="city"><?php echo $city ?></span></div>
       <div class="country break"><?php echo $country ?></div>
       <div class="phone"><?php echo gettext("tel").": ".$phone ?></div>
       <div class="fax"><?php echo gettext("fax").": ".$fax ?> </div>
       <div class="email"><?php echo gettext("mail").": ".$email ?></div>
       <div class="web"><?php echo $web ?></div>
       <div class="vat-number"><?php echo gettext("VAT nr.")." : ".$vat_invoice; ?></div>
     </div>
    </td>
  </tr>
  <tr class="two">
    <td colspan="3" class="invoice-details">
    <br/>
      <table class="table">
        <tbody><tr>
          <td class="one">
            <strong><?php echo gettext("Date"); ?></strong>
            <div><?php echo $invoice->getDate() ?></div>
          </td>
          <td class="two">
            <strong><?php echo gettext("Invoice number"); ?></strong>
            <div><?php echo $invoice->getReference() ?></div>
          </td>
          <?php if ($display_account==1) { ?>
          <td class="three">
              <strong><?php echo gettext("Client Account Number"); ?></strong>
            <div><?php echo $card['username'] ?></div>
          </td>
          <?php } ?>
                 </tr>
      </tbody></table>
    </td>
  </tr>
  </thead>
  <tbody>
      <td colspan="3" class="items">
        <table class="table">
          <tbody>
          <tr class="one">
              <th style="text-align:left;"><?php echo gettext("Date"); ?></th>
              <th style="text-align:left;" class="description"><?php echo gettext("Description"); ?></th>
              <th style="text-align:left;"><?php echo gettext("Cost excl. VAT"); ?></th>
              <th style="text-align:left;" ><?php echo gettext("VAT"); ?></th>
              <th style="text-align:left;"><?php echo gettext("Cost incl. VAT"); ?></th>
          </tr>
          <?php
          $i=0;
          foreach ($items as $item) { ?>
            <tr style="vertical-align:top;" class="<?php if($i%2==0) echo "odd"; else echo "even";?>" >
                <td style="text-align:left;">
                    <?php echo $item->getDate(); ?>
                </td>
                <td class="description" style="text-align:left;">
                    <?php echo $item->getDescription(); ?>
                </td>
                <td style="text-align:left;">
                    <?php echo number_format(round(amount_convert($item->getPrice()),2),2); ?>
                </td>
                <td style="text-align:left;">
                    <?php echo number_format(round($item->getVAT(),2),2)."%"; ?>
                </td>
                <td style="text-align:left;">
                    <?php echo number_format(round(amount_convert($item->getPrice())*(1+($item->getVAT()/100)),2),2); ?>
                </td>
            </tr>
             <?php  $i++;} ?>

        </tbody></table>
      </td>
    </tr>
    <?php
        $price_without_vat = 0;
        $price_with_vat = 0;
        $vat_array = array();
        foreach ($items as $item) {
             $price_without_vat = $price_without_vat + $item->getPrice();
            $price_with_vat = $price_with_vat + ($item->getPrice()*(1+($item->getVAT()/100)));
            if (array_key_exists("".$item->getVAT(),$vat_array)) {
                $vat_array[$item->getVAT()] = $vat_array[$item->getVAT()] + $item->getPrice()*($item->getVAT()/100) ;
            } else {
                $vat_array[$item->getVAT()] =  $item->getPrice()*($item->getVAT()/100) ;
            }
         }

         ?>

    <tr>
      <td colspan="3">
        <table class="table">
         <tbody><tr class="extotal">
           <td class="one"></td>
           <td class="two"><?php echo gettext("Subtotal excl. VAT:"); ?></td>
           <td class="three"><?php echo number_format(round(amount_convert($price_without_vat)*100)/100,2)." $display_curr"; ?></td>
         </tr>

         <?php foreach ($vat_array as $key => $val) { ?>
                 <tr class="vat">
                   <td class="one"></td>
                   <td class="two"><?php echo gettext("VAT ") . "$key%:" ?></td>
                   <td class="three"><?php echo number_format(round(amount_convert($val),2),2)." $display_curr"; ?></td>
                 </tr>
         <?php } ?>
         <tr class="inctotal">
           <td class="one"></td>
           <td class="two"><?php echo gettext("Total incl. VAT:") ?></td>
           <td class="three"><div class="inctotal"><div class="inctotal inner"><?php echo number_format(round(amount_convert($price_with_vat)*100)/100,2)." $display_curr"; ?></div></div></td>
         </tr>
        </tbody></table>
      </td>
    </tr>
    <tr>
    <td colspan="3" class="additional-information">
      <div class="invoice-description">
      <?php echo $invoice->getDescription() ?>
     </div></td>
    </tr>
  </tbody>
  
    <tr>
      <td colspan="3" class="footer">
        <?php echo $company_name." | ".$address.", ".$zipcode." ".$city." ".$country." | ".gettext("VAT nr.").$vat_invoice; ?>
      </td>
    </tr>
   
  </table></div>
  </div>
  </div>
<?php
      $smarty->display('footer.tpl');
  ?>