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

//ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);  // 7 day cookie lifetime
  session_start();
  //$_SESSION["mac"] = "D3D1D05AFE42AD50818167EAC73C109168A0F108F32645C8B59E897FA930DA44F9230910DAC9E20641823799A107A02068F7BC0F4CC41D2952E249552255710F";
  //$_SESSION["mac"] = "E187B1191265B18338B5DEBAF9F38FEC37B170FF582D4666DAB1F098304D5EE7F3BE15540461FE92F1D40332FDBBA34579034EE2AC78B1A1B8D9A321974025C4";
  $_SESSION["txn_ref"] = "JB"  . intval( "0" . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) ); // random(ish) 7 digit int
 
/*$firstname = $_POST["FirstName"];
$lastname = $_POST["LastName"];
$transaction_amount = $_POST["amount"];

$sql = "INSERT INTO orders (first_name, last_name, amount, status, transaction_id)
VALUES ('$firstname', '$lastname', '$phone','$transaction_amount','Pending', '$_SESSION["txn_ref"]')";

$insert_data = $inst_table -> SQLExec($DBHandle, $sql);*/

?>
<!-- begin:: Subheader -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Fill Payment details </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            User                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Confirm Payment                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Payment Details                        </a>
                </div>
            </span>
        </div>
    </div>
  </div>
<!-- end:: Subheader -->

<!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="col-md-12" style="margin: 0 auto;">
	  	<!--begin::Portlet-->
		  <div class="kt-portlet">
			  <div class="kt-portlet__head">
				  <div class="kt-portlet__head-label">
                        <font class="kt-portlet__head-title"><?php echo gettext("Fill Payment Details"); ?></font>
				  </div>
			  </div>
			  <!--begin::Form-->
			  <form method="POST" action="confirm.php">
				  <div class="kt-portlet__body">
                    <div class="form-group row">
                    <div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12">First Name</label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="text" name="name" class="form-control" placeholder="Enter First Name here">
						    <span class="form-text text-muted">Please enter your first name here.</span>
                        </div>     
					</div>
                    <div class="form-group row">
                    <div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12">Last Name</label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="text" name="name" class="form-control" placeholder="Enter Last Name here">
						    <span class="form-text text-muted">Please enter your last name here.</span>
                        </div>     
					</div>
                    <div class="form-group row">
                    <div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12">Amount</label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="number" name="amount" class="form-control" placeholder="Enter Amount here">
						    <span class="form-text text-muted">Please enter amount here.</span>
                        </div>     
					</div>
                    <div class="form-group row">
                    <div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12">Customer ID</label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="number" name="cust_id" class="form-control" placeholder="Enter Customer ID here">
						    <span class="form-text text-muted">Please enter your Customer ID here.</span>
                        </div>     
					</div>
                  </div>

                <div align="center" class="kt-portlet__foot">
                <div class="form-actions">
                    <input type="submit" name="create" value="&nbsp;<?php echo gettext("Submit")?>&nbsp;" class="btn btn-brand">&nbsp;&nbsp;
                    <input type="reset" name="cancel" value="&nbsp;Clear&nbsp;" class="btn btn-secondary">
                </div>
                </div>
              </form>
            </div>
        </div>
    </div>


<!--<a href="http://localhost/demopay4_wpd/">Page 1</a>
<a href="http://localhost/demopay4_wpd/requery.php">Requery</a>-->
