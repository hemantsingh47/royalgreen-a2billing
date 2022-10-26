<?php


include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/customer.smarty.php';
include './lib/support/classes/invoice.php';
include './lib/support/classes/invoiceItem.php';

if (! has_rights (ACX_INVOICES)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id'));

if (empty($id)) {
    Header ("Location: billing_entity_invoice.php?atmenu=payment&section=13");
}

$invoice = new invoice($id);
if ($invoice->getCard() != $_SESSION["card_id"]) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}
$items = $invoice->loadItems();

//load customer
$DBHandle  = DbConnect();
$card_table = new Table('cc_card','*');
$card_clause = "id = ".$_SESSION["card_id"];
$card_result = $card_table -> Get_list($DBHandle, $card_clause, 0);
$card = $card_result[0];

if (empty($card)) {
    echo "Customer doesn't exist or is not correctly defined for this invoice !";
    die();
}
if (!$popup_select) 
{
    $smarty->display('main.tpl');
}
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
<link rel="stylesheet" href="templates/default/css/main.css">
<!--
<a onClick="MM_openBrWindow('<?php echo $PHP_SELF ?>?popup_select=1&id=<?php echo $id ?>','','scrollbars=yes,resizable=yes,width=700,height=500')" > <img src="./templates/default/images/printer.png" title="Print" alt="Print" border="0"></a>
-->
&nbsp;&nbsp;

<?php
} else {
?>
<P ALIGN="left"> <a href="javascript:window.print()"> <img src="./templates/default/images/printer.png" title="Print" alt="Print" border="0"> <?php echo gettext("Print"); ?></a> &nbsp; &nbsp;</P>
<?php
}
?>


<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Invoice Info                           </h3>
            
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
                            View Invoices                         </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>

<div class="kt-portlet">
  <div class="kt-portlet__head">
    <div class="kt-portlet__head-label">
		  <h1 class="kt-portlet__head-title">
            <?php echo gettext('INVOICE'); ?>
	    </h1> 
    </div>
  </div>

<!--
<div class="md-card">            
<div class="uk-grid" data-uk-grid-margin="">
<div class="uk-width-1-1 uk-row-first">
<div class="invoice-wrapper">
<table class="invoice-table">
<thead>
  <tr class="one">
    <td class="one">
     <h1><?php echo gettext("INVOICE"); ?></h1>
     <div class="client-wrapper">
         <div class="company-name break"><?php echo $card['company_name'] ?></div>
         <div class="fullname"><?php echo $card['lastname']." ".$card['firstname'] ?></div>
           <div class="address"><span class="street"><?php echo $card['address'] ?></span> </div>
           <div class="zipcode-city"><span class="zipcode"><?php echo $card['zipcode'] ?></span> <span class="city"><?php echo $card['city'] ?></span></div>
          <div class="country break"><?php echo $card_country ?></div>
           <?php if (!empty($card['VAT_RN'])) { ?>
               <div class="vat-number"><?php echo gettext("VAT nr.")." : ".$card['VAT_RN']; ?></div>
           <?php } ?>
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
      <table class="invoice-details">
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
    <tr>
      <td colspan="3" class="items">
        <table class="items">
          <tbody>
          <tr class="one">
              <th style="text-align:left;"><?php echo gettext("Date"); ?></th>
              <th class="description"><?php echo gettext("Description"); ?></th>
              <th><?php echo gettext("Cost excl. VAT"); ?></th>
              <th><?php echo gettext("VAT"); ?></th>
              <th><?php echo gettext("Cost incl. VAT"); ?></th>
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
                    <?php echo number_format(amount_convert($item->getPrice()),2); ?>
                </td>
                <td align="right">
                    <?php echo number_format($item->getVAT(),2)."%"; ?>
                </td>
                <td align="right">
                    <?php echo number_format(amount_convert($item->getPrice())*(1+($item->getVAT()/100)),2); ?>
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
        <table class="total">
          <tbody><tr class="extotal">
            <td class="one"></td>
            <td class="two"><?php echo gettext("Subtotal excl. VAT:"); ?></td>
            <td class="three"><?php echo number_format(amount_convert($price_without_vat),2)." $display_curr"; ?></td>
          </tr>
          <?php foreach ($vat_array as $key => $val) { ?>
            <tr class="vat">
              <td class="one"></td>
              <td class="two"><?php echo gettext("VAT")."$key%:"; ?></td>
              <td class="three"><?php echo number_format(amount_convert($val),2)." $display_curr"; ?></td>
            </tr>
          <?php } ?>
          <tr class="inctotal">
            <td class="one"></td>
            <td class="two"><?php echo gettext("Total incl. VAT:") ?></td>
            <td class="three">
              <div class="inctotal inner">
                <?php echo number_format(amount_convert($price_with_vat),2)." $display_curr"; ?>
              </div>
             </td>
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
  <tfoot>
    <tr>
      <td colspan="3" class="footer">
        <?php echo $company_name." | ".$address.", ".$zipcode." ".$city." ".$country." | VAT nr.".$vat_invoice; ?>
      </td>
    </tr>
  </tfoot>
  </table>
  </div>
  </div>
  </div>
  </div> -->
  <!-- start invoice here by rohit -->
  
  <div class="receipt-wrapper" id="printableArea">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> 
            <h3 ><?php echo $company_name ?></h3> <button class="btn btn-primary pull-right" onclick="printDiv('printableArea')" style="margin-top: -100px;">Print this page</button>
          </div>     
          <div class="widget-content" >
            <div class="row-fluid">
              <div class="span6">
                <table class="table">
                  <tbody>
                    <tr>
                      <td><b>Company Name :</b>  </td>
					  <td><?php echo $company_name ?></td>
                    </tr>
                    <tr>
                     <td><b>Address :</b>  </td> <td><?php echo $address ?></td>
                    </tr>
                    <tr>
                      <td><b>ZIP Code :</b>  </td><td><span><?php echo $zipcode ?></span></td>
                    </tr>
					<tr>
					<td><b>City : </b>
					</td>
					<td> <span class="city"><?php echo $city ?></span></td>
					</tr>
                    <tr>
                      <td><b>Country : </b>
					</td> <td><?php echo $country ?></td>
                    </tr>
					<tr>
					<td><b>Mobile Phone : </b>
					</td>
                      <td><?php echo $phone ?></td>
                    </tr>
                    <tr>
					<td><b>Fax : </b>
					</td>
                      <td ><?php echo $fax ?></td>
                    </tr>
					<tr>
					<td><b>E-mail : </b>
					</td>
                      <td ><?php echo $email ?></td>
                    </tr>
					<tr>
					<td><b>Website : </b>
					</td>
                      <td ><?php echo $web ?></td>
                    </tr>
					<tr>
					<td width="50%"><b>VAT No. : </b>
					</td>
                      <td width="50%"><?php echo $vat_invoice; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="span6">
                <table class="table table-bordered table-invoice">
                  <tbody>
                    <tr>
                    <tr>
                      <td width="50%"><b><?php echo gettext("Date"); ?></b></td>
                      <td width="50%"><?php echo $invoice->getDate() ?></td>
                    </tr>
                    <tr>
                      <td><b><?php echo gettext("Invoice number"); ?></b></td>
                      <td><?php echo $invoice->getReference() ?></td>
                    </tr>
                    <tr>
					<?php if ($display_account==1) { ?>
                      <td><b><?php echo gettext("Client Account Number"); ?><b></td>
                      <td><?php echo $card['username'] ?></td>
				    <?php } ?>
                    </tr>
                  <td ><b><?php echo gettext("Client Name/Address "); ?></b></td>
                    <td ><strong><?php echo $card['lastname']." ".$card['firstname'] ?></strong> <br><strong><?php echo $card['address'] ?></strong> <br>
                      <?php echo $card['city'] ?> <br>
                      <?php echo $card['state'] ?> <br>
                      <?php echo $card_country ?> <br>
                      <?php echo $card['email'] ?> </td>
                  </tr>
                    </tbody>
                  
                </table>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span12">
                <table class="table table-bordered table-invoice-full">
                  <thead>
                    <tr>
                      <th class="head0"><?php echo gettext("Date"); ?></th>
                      <th class="head1"><?php echo gettext("Description"); ?></th>
                      <th class="head0 right"><?php echo gettext("Cost excl. VAT"); ?></th>
                      <th class="head1 right"><?php echo gettext("VAT"); ?></th>
                      <th class="head0 right"><?php echo gettext("Cost incl. VAT"); ?></th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				  $i=0;
				  foreach ($items as $item) { ?>
						<tr>
						  <td><?php echo $item->getDate(); ?></td>
						  <td><?php echo $item->getDescription(); ?></td>
						  <td class="right"><?php echo number_format(amount_convert($item->getPrice()),2); ?></td>
						  <td class="right"><?php echo number_format($item->getVAT(),2)."%"; ?></td>
						  <td class="right"><strong><?php echo number_format(amount_convert($item->getPrice())*(1+($item->getVAT()/100)),2); ?></strong></td>
						</tr>
				  <?php  $i++;} ?>
                  </tbody>
                </table>
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
                <table class="table table-bordered table-invoice-full">
                  <tbody>
                    <tr>
                      <td class="msg-invoice" width="65%"><h4><?php echo $invoice->getDescription() ?></h4>
                         <a href="#" class="tip-bottom" title="Adore infotech Pvt. Ltd."><?php echo $company_name?></a> | <?php echo $address.", ".$zipcode." ".$city." ".$country." | VAT nr.".$vat_invoice; ?>
					  <?php foreach ($vat_array as $key => $val) { ?></td>
					  <td class="right"><strong><?php echo gettext("Subtotal excl. VAT: "); ?></strong> <br>
                        <strong><?php echo gettext("VAT ")."$key%:"; ?></strong></td>
                      <td class="right"><strong><?php echo number_format(amount_convert($price_without_vat),2)." $display_curr"; ?><br />
                        <?php echo number_format(amount_convert($val),2)." $display_curr"; ?> <br>
                        </td>
						<?php } ?>
                    </tr>
					
					<tr>
					<td colspan="3">
					 <div class="pull-right">
                  <h4><span><?php echo gettext("Total incl. VAT:") ?></span><?php echo number_format(amount_convert($price_with_vat),2)." $display_curr"; ?></h4>
                 
                 <!-- <a class="btn btn-primary btn-large pull-right" href="">Pay Invoice</a> --> </div>
					</td>
					</tr>
                  </tbody>
                </table>
               
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	</div>
  </div>
  <script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>

  
  <?php
  if (!$popup_select) {
  $smarty->display('footer.tpl');  
  }