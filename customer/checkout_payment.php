<?php

include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './lib/epayment/classes/payment.php';
include './lib/epayment/classes/order.php';
include './lib/epayment/classes/currencies.php';
include './lib/epayment/includes/general.php';
include './lib/epayment/includes/html_output.php';
include './lib/epayment/includes/loadconfiguration.php';
include './lib/epayment/includes/configure.php';
include './lib/customer.smarty.php';

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array ('payment_error'));

$currencies_list = get_currencies();
$two_currency = false;

if ( !isset($currencies_list[strtoupper($_SESSION['currency'])][2]) || !is_numeric($currencies_list[strtoupper($_SESSION['currency'])][2]) ) {
    $mycur = 1;
} else {
    $mycur = $currencies_list[strtoupper($_SESSION['currency'])][2];
    $display_currency =strtoupper($_SESSION['currency']);
    if (strtoupper($_SESSION['currency'])!=strtoupper(BASE_CURRENCY))
        $two_currency=true;
}

$HD_Form = new FormHandler("cc_payment_methods","payment_method");

getpost_ifset(array('item_id','item_type'));
$DBHandle =DbConnect();
$HD_Form -> setDBHandler ($DBHandle);
$HD_Form -> init();

$static_amount = false;
$amount=0;
if ($item_type == "invoice" && is_numeric($item_id)) {
    $table_invoice = new Table("cc_invoice", "status, paid_status");
    $clause_invoice = "id = ".$item_id;
    $result= $table_invoice -> Get_list($DBHandle,$clause_invoice);
    if (is_array($result) && $result[0]['status']==1 && $result[0]['paid_status']==0 ) {
        $table_invoice_item = new Table("cc_invoice_item","COALESCE(SUM(price*(1+(vat/100))),0)");
        $clause_invoice_item = "id_invoice = ".$item_id;
        $result= $table_invoice_item -> Get_list($DBHandle,$clause_invoice_item);
        $amount = ceil($result[0][0] * 100) / 100;
        $static_amount = true;
    } else {
        Header ("Location: userinfo.php");
        die;
    }
}
// #### HEADER SECTION
$smarty->display( 'main.tpl');

$HD_Form -> create_toppage ($form_action);

$payment_modules = new payment;

 

?>
<script language="javascript">

function checkamount() {
    if (document.checkout_amount.amount == "") {
        alert('Please enter some amount.');
        return false;
    }
    return true;
}

var selected;

function selectRowEffect(object, buttonSelect) {
    if (!selected) {
        if (document.getElementById) {
            selected = document.getElementById('defaultSelected');
        } else {
            selected = document.all['defaultSelected'];
        }
    }

    if (selected) selected.className = 'moduleRow';
    object.className = 'moduleRowSelected';
    selected = object;
    // one button is not an array
    if (document.checkout_payment.payment[0]) {
        document.checkout_payment.payment[buttonSelect].checked=true;
    } else {
        document.checkout_payment.payment.checked=true;
    }
}

function rowOverEffect(object) {
    if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
    if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>



<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                User                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Checkout Payment                    </a>
							 
                         
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
       
    </div>
</div>

<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h1 class="kt-portlet__head-title">
				<?php echo gettext("Please confirm your order"); ?>
				
			</h1>
		</div>
	</div>
	
	 <div class="kt-portlet__body">
	 
	 <div class="col-md-12">







<?php echo $payment_modules->javascript_validation(); ?>



<?php
    echo $PAYMENT_METHOD;
?>
<br>
<?php
$form_action_url = tep_href_link("checkout_confirmation.php", '');
echo tep_draw_form('checkout_amount', $form_action_url, 'post', 'onsubmit="checkamount()"');
?>
    <?php
        if ($HD_Form->FG_CSRF_STATUS == true) {
    ?>
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
        <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
    <?php
        }
    ?>

    <input name="item_id" type="hidden" value="<?php echo $item_id?>">
    <input name="item_type" type="hidden" value="<?php echo $item_type?>">
	
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="row">
	<div class="col-md-12">

    <table width="80%" cellspacing="0" cellpadding="2" align="center">
    <?php
    if (isset($payment_error) && is_object(${$payment_error}) && ($error = ${$payment_error}->get_error())) {
          write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR ".$error['title']." ".$error['error']);
    ?>

      <tr>
        <td ><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" ><b><?php echo tep_output_string_protected($error['title']); ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
          <tr class="infoBoxNoticeContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('clear.gif', '10', '1'); ?></td>
                <td class="main" width="100%" valign="top"><?php echo tep_output_string_protected($error['error']); ?></td>
                <td><?php echo tep_draw_separator('clear.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('clear.gif', '100%', '10'); ?></td>
      </tr>
      </table>
	  
	  </div>
	  </div>
	  </div>
<?php
    }
?>

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="row">
<div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		
			<form action="" method="" class="kt-form">
				
          <table class="" width="80%" cellspacing="0" cellpadding="2" align=center style="background:#ffffff">

            <?php
                $selection = $payment_modules->selection();

                if (sizeof($selection) > 1) {
            ?>

            <tr height=10>
                <td class="infoBoxHeading">&nbsp;</td>
                <td class="infoBoxHeading" width="15%" valign="top" align="center"><b><?php echo "Please Select"; ?></b></td>
                <td class="infoBoxHeading" width="10%" >&nbsp;</td>
                <td class="infoBoxHeading"  width="75%" valign="top"><b><?php echo "Payment Method"; ?><b></td>
            </tr>
            <?php
                } else {
            ?>
            <tr>
                <td>&nbsp;</td>
                <td class="main" width="100%" colspan="3"><?php echo "This is currently the only payment method available to use on this order."; ?></td>
            </tr>

            <?php
                }

                $radio_buttons = 0;
                for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
            ?>

            <tr>
                <td colspan="3">&nbsp; </td>
            </tr>

            <?php
                if ( ($selection[$i]['id'] == $payment) || ($n == 1) ) {
                  echo '             <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                } else {
                  echo '             <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                }
            ?>
            <td >&nbsp;</td>
            <td width="15%" align="center">
            <?php
                if (sizeof($selection) > 1) {
                  echo tep_draw_radio_field('payment', $selection[$i]['id']);
                } else {
                  echo tep_draw_hidden_field('payment', $selection[$i]['id']);
                }
            ?>
            </td>
            <td width="10%" >&nbsp;</td>
            <td width="75%" ><b><?php echo $selection[$i]['module']; ?></b></td>
        </tr>

       <tr>
        <td colspan="3">&nbsp;</td>
        <td >
            <table border="0" width="100%" cellspacing="0" cellpadding="2" class="" style="background:#ffffff">

<?php
    if (isset($selection[$i]['error'])) {
?>
      <tr>
            <td class="main" ><?php echo $selection[$i]['error']; ?></td>
      </tr>
<?php
    } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
?>
      <tr>
            <td ><table border="0" cellspacing="0" cellpadding="2">

<?php
      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
?>
          <tr>
            <td width="10">&nbsp;</td>
            <td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
            <td>&nbsp;</td>
            <td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
          </tr>
<?php
      }
?>
            </table></td>
            <td>&nbsp;</td>
          </tr>
<?php
    }
?>
        </table></td>
        <td>&nbsp;</td>
      </tr>
<?php
    $radio_buttons++;
  }
?>
      </table>
      <br>
      <?php  if (sizeof($selection) > 0) { ?>

      <table width=80% align=center class="" style="background:#ffffff">
            <tr height="15">
                 <td class="main" align="center" style="width:50%"><?php echo gettext("Please select the order amount")?>:</td>
             
            	<td>&nbsp;</td>
                <!--<td align=right><?php echo gettext("Total Amount")?>: &nbsp;</td>-->
                <td align=left>
                <?php
                if ($static_amount) {
                   // echo round($amount,2)." ".strtoupper(BASE_CURRENCY);
                    //if ($two_currency) {
                        echo round($amount/$mycur,2)." ".strtoupper($_SESSION['currency']);
                    //}
                ?>
                <input name="amount" type=hidden value="<?php echo $amount?>">
                <?php
                  } else { ?>
                <select name="amount" class="form-control" style="width:40%"  >
                <?php
                $arr_purchase_amount = preg_split("/:/", EPAYMENT_PURCHASE_AMOUNT);
                        if (!is_array($arr_purchase_amount)) $arr_purchase_amount[0]=10;

                        foreach ($arr_purchase_amount as $value) {
                ?>
                <option value="<?php echo $value?>">
                    <?php
                echo round($value,2);
                if ($two_currency) {
                    echo " ".strtoupper(BASE_CURRENCY);
					//echo round($value/$mycur,2)." ".strtoupper($_SESSION['currency']);

                }
                ?>
                </option>

                <?php }?></select>
                &nbsp;<?php if(!$two_currency) echo strtoupper(BASE_CURRENCY);
                  }
                ?>

                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
         <br/>

         <div align="center" width=80% class="kt-portlet__foot" style="background:#ffffff;">
                <div class="form-actions">
                    <b><?php echo gettext("Continue Checkout Procedure to confirm this order")?></b>&nbsp;
                    <input type="submit" name="confirm" alt="Confirm Order" value="&nbsp;<?php echo gettext("Confirm Order")?>&nbsp;" class="btn btn-brand">
					<b> <?php echo "OR";?> </b>
				<a href="authorize_payment.php"><input type="button"  alt="Pay using Authorize" border="0" title="Pay using Authorize" class="btn btn-brand" value="Pay using Authorize"></a>
                </div>
            </div>                
				</div>


      <!-- <table class="infoBox" width="80%" cellspacing="0" cellpadding="2" align=center>
        <tr height="20">
          <td  align=left class="main">
              <b>Continue Checkout Procedure</b><br>to confirm this order.
          </td>
          <td align=right halign=center >
              <input type="image" src="<?php echo Images_Path;?>/button_continue.gif" alt="Continue" border="0" title="Continue">
              &nbsp;
          </td>
        </tr>
      </table> -->
         <?php } ?>
     </form>
                </div>
     </div> 
</div>
</div>
<!-- end:: Content -->
<?php

// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
