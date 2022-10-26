<?php



include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/customer.smarty.php';
include './lib/support/classes/receipt.php';
include './lib/support/classes/receiptItem.php';

if (! has_rights (ACX_INVOICES)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id'));

if (empty($id)) {
Header ("Location: A2B_entity_receipt.php?atmenu=payment&section=13");
}

$receipt = new receipt($id);
if ($receipt->getCard() != $_SESSION["card_id"]) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}
$items = $receipt->loadItems();

//load customer
$DBHandle  = DbConnect();
$card_table = new Table('cc_card','*');
$card_clause = "id = ".$_SESSION["card_id"];
$card_result = $card_table -> Get_list($DBHandle, $card_clause, 0);
$card = $card_result[0];

if (empty($card)) {
    echo "Customer doesn't exist or is not correctly defined for this receipt !";
    die();
}

$smarty->display('main.tpl');
//Load receipt conf
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

//Currencies check
$curr = $card['currency'];
$currencies_list = get_currencies();
if (!isset($currencies_list[strtoupper($curr)][2]) || !is_numeric($currencies_list[strtoupper($curr)][2])) {$mycur = 1;$display_curr=strtoupper(BASE_CURRENCY);} else {$mycur = $currencies_list[strtoupper($curr)][2];$display_curr=strtoupper($curr);}

function amount_convert($amount)
{
    global $mycur;

    return $amount/$mycur;
}

if (!$popup_select) {
?>





&nbsp;&nbsp;
<?php
}
?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Receipt Info                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                      </a>
                                  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Invoice                      </a>
                                  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            View Receipts                         </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>


<div class="kt-portlet">
  <div class="kt-portlet__head">
    <div class="kt-portlet__head-label">
		  <h1 class="kt-portlet__head-title">
            <?php echo gettext('RECEIPT'); ?>
	    </h1>
    </div>
  </div>
  
  
  <table>
  
  <tr>
  <td width="75%">
  </td>
  <td width="15%"><a href="javascript:;" onClick="window.open('<?php echo $PHP_SELF ?>?popup_select=1&id=<?php echo $id ?>','','scrollbars=yes,resizable=yes,width=700,height=500')" class="btn btn-primary pull-right"> <i class="flaticon2-fax"></i> Print Receipt</a>
  </td>
  <td width="10%" align="center"><a href="javascript:;" onClick="window.open('billing_receipt_detail.php?popup_select=1&id=<?php echo $id ?>','','scrollbars=yes,resizable=yes,width=700,height=500')" class="btn btn-primary"> <i class="flaticon2-search-1"></i> View</a>
  </td>
  </tr>
  </table>


 


<div class="receipt-wrapper">
  <table class="table">
  <thead>
  <tr class="one">
    <td class="one">
   
     <div class="client-wrapper">
         <div class="company-name break"><?php echo $card['company_name'] ?></div>

         <div class="fullname"><?php echo $card['lastname']." ".$card['firstname'] ?></div>
           <div class="address"><span class="street"><?php echo $card['address'] ?></span> </div>
           <div class="zipcode-city"><span class="zipcode"><?php echo $card['zipcode'] ?></span> <span class="city"><?php echo $card['city'] ?></span></div>
          <div class="country break"><?php echo $card['country'] ?></div>
           <div class="vat-number"><?php echo gettext("VAT nr.")." : ".$card['VAT_RN']; ?></div>
     </div>
    </td>
    <td class="two">

    </td>
    <td class="three">
     <div class="supplier-wrapper">
       <div class="company-name"><?php echo $company_name ?></div>
       <div class="address"><span class="street"><?php echo $address ?></span> </div>
       <div class="zipcode-city"><span class="zipcode"><?php echo $zipcode ?></span> <span class="city"><?php echo $city ?></span></div>
       <div class="country break"><?php echo $country ?></div>
       <div class="phone"><?php echo $phone ?></div>
       <div class="fax"><?php echo $fax ?> </div>
       <div class="email"><?php echo $email ?></div>
       <div class="web"><?php echo $web ?></div>
     </div>
    </td>
  </tr>
  <tr class="two">
    <td colspan="3" class="receipt-details">
      <table class="table">
        <tbody>
          <tr>
          <td class="one">
            <strong><?php echo gettext("Date"); ?></strong>
            <div><?php echo $receipt->getDate() ?></div>
          </td>

           <?php if ($display_account==1) { ?>
          <td class="three">
              <strong><?php echo gettext("Client Account Number"); ?></strong>
            <div><?php echo $card['username'] ?></div>
          </td>
          <?php } ?>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="3" class="items">
        <table class="table">
          <tbody>
          <tr class="one">
              <th style="text-align:left;" width="20%"><?php echo gettext("Date"); ?></th>
              <th class="description" width="60%"><?php echo gettext("Description"); ?></th>
              <th width="20%" ><?php echo gettext("Cost"); ?></th>
          </tr>
          <?php
          $i=0;
          foreach ($items as $item) { ?>
            <tr style="vertical-align:top;" class="<?php if($i%2==0) echo "odd"; else echo "even";?>" >
                <td style="text-align:left;">
                    <?php echo $item->getDate(); ?>
                </td>
                <td class="description">
                    <?php echo $item->getDescription(); ?>
                </td>
                <td align="right">
                    <?php echo number_format(amount_convert($item->getPrice()),3); ?>
                </td>
            </tr>
             <?php  $i++;} ?>

        </tbody></table>
      </td>
    </tr>
    <?php
    $price = 0;
    foreach ($items as $item) {
        $price = $price + $item->getPrice();
    }
    ?>

    <tr>
      <td colspan="3">
        <table class="table">
          <tbody>
            <tr class="inctotal">
              <td class="one" width="20%"></td>
              <td class="two" width="60%" style="text-align:center;"><?php echo gettext("Total:") ?></td>
              <td class="three" width="20%" style="text-align:center;">
                <div class="inctotal inner">
                  <?php echo number_format(amount_convert($price),3)." $display_curr"; ?>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
    <td colspan="3" class="additional-information">
      <div class="receipt-description">
          <?php echo $receipt->getDescription() ?>
     </div></td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3" class="footer">
        <?php echo $company_name." | ".$address.", ".$zipcode." ".$city." ".$country." | VAT nr.".$vat_invoice; ?>
      </td>
    </tr>
  </tfoot>
  </table></div>
  
  
  </div>
<?php

$smarty->display('footer.tpl');
