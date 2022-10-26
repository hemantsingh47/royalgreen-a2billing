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

/*if (! has_rights (ACX_ACCESS)) 
{
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}*/

// #### HEADER SECTION
$smarty->display( 'main.tpl');
    
	?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Final Payment details </h3>
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
                            Final Payment                       </a>
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
                        <font class="kt-portlet__head-title"><?php echo gettext("Final Payment Details"); ?></font>
				  </div>
			  </div>
          
    
	<?php
	
	echo "Post Details:";	print_r($_POST);	echo "</br>";
    /*echo "Session Details:";	print_r($_SESSION);	*/
	
	$product_id = 6204;
	$pay_item_id = 103;
	$site_redirect_url = "https://billing.adoreinfotech.co.in/customer/redirect4.php";
    $txn_ref = "Ceritel "  . intval( "0" . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) ); // random(ish) 7 digit int
    $_SESSION["txn_ref"] = $txn_ref;	
    //$mac    = "631BD86968E1041A59727265BB56E512F59578D3D3E50F3BE69DF0D95858903C01F05E8DFBD20E07CB7D6794E06B75F48D18ED3AE531892D7FF11290716AC646";
    //$mac    = "E187B1191265B18338B5DEBAF9F38FEC37B170FF582D4666DAB1F098304D5EE7F3BE15540461FE92F1D40332FDBBA34579034EE2AC78B1A1B8D9A321974025C4";
    //$mac    = "23E9657CA37675EDAE2F6A59EB3B729557E3241AB3AFFDC136017C95ABECA4AB36D20F205479E42CEC10ACBFF834F0CC2CA2759355B70598F24033716F257242";
    $mac    = "E187B1191265B18338B5DEBAF9F38FEC37B170FF582D4666DAB1F098304D5EE7F3BE15540461FE92F1D40332FDBBA34579034EE2AC78B1A1B8D9A321974025C4";
    $amount = $_POST["amount"];
    $cust_id = $txn_ref;
    $hashv  = $txn_ref . $product_id . $pay_item_id . $amount . $site_redirect_url . $mac;
    $customerName = $_POST["FirstName"]." ".$_POST["LastName"];
    $hash  = hash('sha512',$hashv);       
    $_SESSION["amount"] = $amount;
	
?>

<!-- LIVE URL => https://webpay.interswitchng.com/paydirect/pay          -->
<!-- TEST URL => https://stageserv.interswitchng.com/test_paydirect/pay  -->

 <!--<form method="post" action="https://webpay.interswitchng.com/collections/w/pay">  <!-- Product id = 1335 & 101 -->
  <!--<form method="post" action="https://webpay.interswitchng.com/paydirect/pay">  -->
 <!-- <form method="post" action="https://stageserv.interswitchng.com/webpay/pay"> --> 
 <form method="post" action="https://sandbox.interswitchng.com/webpay/pay">
    <div class="kt-portlet__body">
    <!-- REQUIRED HIDDEN FIELDS -->
<!--COLLEGEPAY	<input name="product_id" type="hidden" value="6204" />  COLLEGEPAY-->
    <input name="product_id" type="hidden" value="<?php echo $product_id; ?>" />
    <input name="pay_item_id" type="hidden" value="<?php echo $pay_item_id; ?>" />
    <input name="amount" type="hidden" value="<?php echo $amount; ?>" />
    <input name="currency" type="hidden" value="566" />
    <!-- <input name="site_redirect_url" type="hidden" value="http://localhost/demopay4/redirect4.php" /> -->
    <input name="site_redirect_url" type="hidden" value="<?php echo $site_redirect_url; ?>" />
    <input name="txn_ref" type="hidden" value="<?php echo $txn_ref; ?>" />
    <input name="cust_id" type="hidden" value="<?php echo $cust_id; ?>"/>
    <input name="site_name" type="hidden" value=""/>
    <input name="cust_name" type="hidden" value="<?php echo $customerName; ?>" />
    <input name="hash" type="hidden" id="hash" value="<?php echo $hash;  ?>" />
    </br></br>
    <!--<a href="http://localhost/demopay4_wpd/">Back</a>
    <input type="submit" value="Make Payment"></input> -->
    </div>
	<div align="center" class="kt-portlet__foot">
        <div class="form-actions">
            <input type="submit"value="Make Payment" class="btn btn-brand">&nbsp;&nbsp;
            <input type="reset" name="cancel" value="&nbsp;Clear&nbsp;" class="btn btn-secondary">
        </div>
    </div>

	
	<?php
	$DBHandle_max  = DbConnect();
	$sql = "INSERT INTO orders (name, amount, status, transaction_id)
	VALUES ('$customerName', '$amount', 'Pending', '$txn_ref')";

	$insert_data = $inst_table -> SQLExec($DBHandle, $sql);
	
	?>
</form> 
</div>
</div>
