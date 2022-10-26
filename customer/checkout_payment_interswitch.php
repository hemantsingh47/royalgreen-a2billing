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

if (! has_rights (ACX_ACCESS)) 
{
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

<?php echo $payment_modules->javascript_validation(); ?>


<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                User                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Checkout Payment                        </a>
                                             
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
            <?php echo gettext('Payment'); ?>
	      </h1>
        </div>
    </div>

<div class="kt-portlet__body">


<div class="col-md-12">
        
 <table class="infoBox" width="100%" cellspacing="0" cellpadding="2" align=center>


       <tr >      
	  <td style="text-align:center;"> <?php
            //echo $PAYMENT_METHOD;
            echo '<img src="'. KICON_PATH . '/interswitch.png" alt="interswitch.com"/>';
        ?>
      </td>
	  </tr>
	  </table>

    
        <table class="infoBox" width="57%" cellspacing="0" cellpadding="2" align=center>


       <tr >
            
            <td class="infoBoxHeading" valign="top" align="center"><b>Please Select</b></td>
            
            <td class="infoBoxHeading"  valign="top"><b>Payment Method<b></td>
       </tr>

             <tr>
                     <td colspan="2">&nbsp; </td>
             </tr>

            <tr>
             
            <td  align="center">
            <input type="radio" name="payment" value="Interswitch" CHECKED> </td>
             
            <td  ><b>Interswitch</b></td>
       </tr>
          </table>
                      
         
        <br>
        <?php
			$form_action_url = tep_href_link("payment.php", '');
			echo tep_draw_form('checkout_amount', $form_action_url, 'post', 'onsubmit="checkamount()"');
		?>
       
        <table class="infoBox" width="50%" cellspacing="0" cellpadding="2" align="center">
            <input type="hidden" name="cus_name" value ="abc">
            <input type="hidden" name="cus_email" value ="abc@gmail.com">
            <input type="hidden" name="phone_number" value ="<?php echo $_SESSION['pr_login'] ?>"> 
            <input type="hidden" name="cus_address" value ="abc">
            <input type="hidden" name="currency" value="<?php echo $_SESSION['currency'] ?>">
            <input type="hidden" name="username" value="<?php echo $_SESSION['pr_login'] ?>">   

           <tr>
                 
                <td class="infoBoxHeading"  valign="top" align="center"><b>Select Amount</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                
                <td class="infoBoxHeading"  valign="top">
                  <select name ="total_payable_amount" class="form-control">
                    <option value=" ">--Select Amount--</option> 
                    <option value="1">1</option> 
                    <option value="5">5</option> 
                    <option value="10">10</option> 
                    <option value="15">15</option> 
                    <option value="20">20</option> 
                    <option value="25">25</option> 
                  </select>
                </td>
           </tr>

             <tr>
               <td colspan="2">&nbsp; </td>
             </tr>

            <tr>
            
            <td align="center">
              
           
            <td><input type="submit"  alt="Continue" border="0" title="Continue" class="btn btn-brand" value="continue"></td>
			
		
       </tr>
          </table>
                      
         
        </form>
	 <br />
	</div>


</div>
</div>
</div>

<?php

// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
