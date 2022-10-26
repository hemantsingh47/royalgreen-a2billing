<?php

include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'lib/customer.smarty.php';
include 'lib/epayment/includes/configure.php';
include 'lib/epayment/includes/html_output.php';
include './lib/epayment/includes/general.php';

if (!has_rights(ACX_ACCESS)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$inst_table = new Table();

$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status, " .
"freetimetocall, label, packagetype, billingtype, startday, id_cc_package_offer, cc_card.id, currency,cc_card.useralias,UNIX_TIMESTAMP(cc_card.creationdate) creationdate  FROM cc_card " .
"LEFT JOIN cc_tariffgroup ON cc_tariffgroup.id=cc_card.tariff LEFT JOIN cc_package_offer ON cc_package_offer.id=cc_tariffgroup.id_cc_package_offer " .
"LEFT JOIN cc_card_group ON cc_card_group.id=cc_card.id_group WHERE username = '" . $_SESSION["pr_login"] .
"' AND uipass = '" . $_SESSION["pr_password"] . "'";

$DBHandle = DbConnect();

$customer_res = $inst_table -> SQLExec($DBHandle, $QUERY);

if (!$customer_res || !is_array($customer_res)) {
    echo gettext("Error loading your account information!");
    exit ();
}

$customer_info = $customer_res[0];
if ($customer_info[14] != "1" && $customer_info[14] != "8") {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$customer = $_SESSION["pr_login"];

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid','subscribe'));

$currencies_list = get_currencies();

$two_currency = false;
if (!isset ($currencies_list[strtoupper($customer_info[22])][2]) || !is_numeric($currencies_list[strtoupper($customer_info[22])][2])) {
    $mycur = 1;
} else {
    $mycur = $currencies_list[strtoupper($customer_info[22])][2];
    $display_currency = strtoupper($customer_info[22]);
    if (strtoupper($customer_info[22]) != strtoupper(BASE_CURRENCY))
        $two_currency = true;
}

$credit_cur = $customer_info[1] / $mycur;
$credit_cur = round($credit_cur, 3);
$useralias = $customer_info['useralias'];
$creation_date = $customer_info['creationdate'];
$username = $customer_info['username'];

$smarty->display('main.tpl');
?>

<div>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Customer Information </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Dashboard                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer Details                        </a>
                </div>
        </div>
    </div>
    <div align="right" class="kt-portlet__foot">
        <div class="form-actions">
            <button type="button" class="btn btn-brand">
				<i class="flaticon2-edit"></i> 
                    <!-- <span class="kt-hidden-mobile">Save</span> -->
                    <?php if (has_rights (ACX_PERSONALINFO)) { ?>
                        <a href="billing_entity_edit.php"><span class="kt-hidden-mobile"><font color="white"><?php echo gettext("Edit Profile");?></font></span></a>
                    <?php } ?>
			</button>
        </div>
    </div>
</div> 
<!-- end:: Subheader -->

<!-- end:: Content Head -->			
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<!-- begin:: Content -->
<div class="kt-portlet kt-portlet--tabs">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-toolbar">
            <ul class="nav nav-tabs nav-tabs-space-xl nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-brand" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#kt_apps_user_edit_tab_1" role="tab" aria-selected="true">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon id="Shape" points="0 0 24 0 24 24 0 24"></polygon>
        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" id="Mask" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" id="Mask-Copy" fill="#000000" fill-rule="nonzero"></path>
    </g>
</svg>                        Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_apps_user_edit_tab_2" role="tab" aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <circle id="Oval-47" fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
        <rect id="Rectangle-162" fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" id="Combined-Shape" fill="#000000"/>
    </g>
</svg>                        Buy Now
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="kt-portlet__body">
        <form action="" method="">
            <div class="tab-content">
                <div class="tab-pane active" id="kt_apps_user_edit_tab_1" role="tabpanel">
                    <div class="kt-form kt-form--label-right">
                        <div class="kt-form__body">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="row">
                                        <label class="col-xl-3"></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <h3 class="kt-section__title kt-section__title-sm">Customer Information</h3>
                                        </div>
                                        <div class="col-lg-3 col-xl-3">
                                                    <div class="kt-widget__item">
                                                        <div class="kt-widget__details"><br>
                                                            <i class="flaticon-piggy-bank"></i>
                                                            <span class="kt-widget__title"><b><?php echo gettext("BALANCE REMAINING");?> :</b>&nbsp;</span>
                                                                <font class="kt-widget__value"><?php echo $credit_cur.' '.$customer_info[22]; ?></font>
                                                        </div>
                                                        <div class="kt-widget__details">
                                                            <i class="flaticon-user-ok"></i>
                                                            <span class="kt-widget__title"><b><?php echo gettext("ACCOUNT NUMBER");?> :</b>&nbsp;</span>
                                                                <font class="kt-widget__value"><?php echo $customer_info[0]; ?></font>
                                                        </div>
                                                    </div>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">

                                    <div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("Picture");?></font>
                                        <div class="col-lg-6 col-md-9 col-sm-12">
                                            <div class="kt-avatar kt-avatar--outline kt-avatar--circle" id="kt_apps_user_add_avatar">
                                                <div class="kt-avatar__holder" title="Upload Picture" style="background-image: url(&quot;http://209.126.64.172/a2billing/customer/templates/default/images/dp/user.png&quot;);"></div>
                                                <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Choose Picture">
                                                    <i class="fa fa-pen"></i>
                                                    <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg">
                                                </label>
                                                <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                            </div>
                                                <div class="form-actions">
                                                    <table>
                                                    <tr>
                                                        <td><input name="submit6" type="submit" value="<?php echo gettext("Update Profile"); ?>" class="btn btn-success" /> </td></tr>
                                                        <em><?php echo gettext("Preferred Size for the logo is 203 X 62 and File size range must be within 2 MB."); ?> </em>
                                                    </table>
                                                </div>
                                        </div>
                                    </div>
                                    
									<div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("First Name");?></font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                            <font class="form-control"><?php echo $customer_info[3]; ?></font>
										</div>
									</div>
                                    <div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("Email");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
                                                <font class="form-control" placeholder="example@gmail.com"><?php echo $customer_info[10]; ?></font>
											</div>
										</div>
									</div>
									<div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("Phone");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
                                                <font class="form-control"><?php echo $customer_info[9]; ?></font>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("Fax");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-fax"></i></span></div>
                                                <font class="form-control"><?php echo $customer_info[11]; ?></font>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("Address");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                            <font class="form-control"><?php echo $customer_info[4]; ?></font>
										</div>
                                    </div>
                                    <div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("Country");?> </font>
									    <div class="col-lg-6 col-md-9 col-sm-12">
                                            <font class="form-control"><?php echo $customer_info[7]; ?></font>	
									    </div>
							        </div>
									<div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("State");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                        <font class="form-control"><?php echo $customer_info[6]; ?></font>
										</div>
									</div>
									<div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("City");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                            <font class="form-control"><?php echo $customer_info[5]; ?></font>
										</div>
									</div>
									<div class="form-group row">
                                    <div class="col-lg-1"></div>
                                        <font class="col-lg-2 col-sm-12"><?php echo gettext("Zipcode");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                        <font class="form-control"><?php echo $customer_info[8]; ?></font>
										</div>
                                    </div>
                                </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="tab-pane" id="kt_apps_user_edit_tab_2" role="tabpanel">
                    <div class="kt-form kt-form--label-right">
                        <div class="kt-form__body">
                        <br>
<?php
												echo $PAYMENT_METHOD;
									  ?>
									  
									<?php if ($A2B->config["epayment_method"]['enable']) { ?>

									<br>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>	
<!-- begin:: Content -->
</div>




									<table style="width:100%;margin:0 auto; background:#ffffff;" cellspacing="0"  align="center" >
										<tr>
											
											

												<?php

									$arr_purchase_amount = preg_split("/:/", EPAYMENT_PURCHASE_AMOUNT);
									if (!is_array($arr_purchase_amount)) {
										$to_echo = 10;
									} else {
										if ($two_currency) {
											$purchase_amounts_convert = array ();
											for ($i = 0; $i < count($arr_purchase_amount); $i++) {
												$purchase_amounts_convert[$i] = round($arr_purchase_amount[$i] / $mycur, 2);
											}
											$to_echo = join(" - ", $purchase_amounts_convert);

											echo $to_echo;
									?>
												<font size="2">
												<?php echo $display_currency; ?> </font>
												<br/>
												<?php } ?>
												<?php //echo join(" - ", $arr_purchase_amount); ?>
												<font size="2"><?php //echo strtoupper(BASE_CURRENCY);?> </font>
												<?php } ?>

											
										</tr>
										<tr>
											<td align="center" style="padding:25px;">
												<form action="checkout_payment.php" method="post">
						
													<input type="submit" class="btn btn-brand" style="" value="<?php echo gettext("BUY NOW");?>">
													<br>
												</form>
											</td>
											
											<td align="center" style="padding:25px;">
												<form action="checkout_payment_interswitch.php" method="POST">                         
                                                        
                          <button type="submit" class="btn btn-brand" value="Submit">INTERSWITCH BUY</button> 
													<br>
												</form>
											</td>  
										</tr>
									</table>


<br/>
<table style="width:80%;margin:0 auto;" cellspacing="0"  align="center" >
    <?php
    if ($A2B->config['epayment_method']['paypal_subscription_enabled']==1) {
        $vat= $_SESSION['vat'];
         $amount_subscribe = $A2B->config['epayment_method']['paypal_subscription_amount'];
        ?>
    <tr background="<?php echo Images_Path; ?>/background_cells.gif" >
        <TD  valign="top" align="right" class="tableBodyRight"   >
            <font size="2"><?php echo gettext("Click below to subscribe an automated refill : ");?> </font>
        </TD>
        <td class="tableBodyRight" >
            <?php
            $head_desc= $amount_subscribe." ".strtoupper(BASE_CURRENCY);
             if($vat>0)$head_desc .= " + ".(($vat/100)*$amount_subscribe)." ".strtoupper(BASE_CURRENCY)." of ".gettext("VAT")."";
             echo $head_desc;
             echo " (".gettext("for each")." ".$A2B->config['epayment_method']['paypal_subscription_period_number']." ";
             switch (strtoupper($A2B->config['epayment_method']['paypal_subscription_time_period'])) {
             case "D": echo gettext("Days");
                 ;
                 break;
             case "M": echo gettext("Months");
                 break;
             case "Y": echo gettext("Years");
                 break;
             default:
                 break;
             }
             echo ")";
           ?>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2" class="tableBodyRight" >
            <img src="<?php echo Images_Path ?>/payments_paypal.gif" />
        </td>
    </tr>

    <?php
        $desc = gettext("Automated refill")." ".$A2B->config['epayment_method']['paypal_subscription_amount']." ".strtoupper(BASE_CURRENCY);
        if($vat>0)$desc .= " + ".(($vat/100)*$amount_subscribe)." ".strtoupper(BASE_CURRENCY)." of ".gettext("VAT");
        $amount_subscribe = $amount_subscribe +(($vat/100)*$amount_subscribe);
        $key = securitykey(EPAYMENT_TRANSACTION_KEY, $username."^".$_SESSION["card_id"]."^".$useralias."^".$creation_date);
        $link= tep_href_link("billing_recurring_payment.php?id=".$_SESSION["card_id"]."&key=".$key, '', 'SSL');
        $link_return= tep_href_link("userinfo.php?subscribe=true", '', 'SSL');
        $link_cancel= tep_href_link("userinfo.php?subscribe=false", '', 'SSL');
    ?>

    <tr>
        <td align="center" colspan="2" class="tableBodyRight" >
            <form name="_xclick" action="<?php echo PAYPAL_PAYMENT_URL?>" method="post">
            <input type="hidden" name="cmd" value="_xclick-subscriptions">
            <input type="hidden" name="business" value="<?php echo $A2B->config['epayment_method']['paypal_subscription_account']?>">
            <input type="hidden" name="currency_code" value="<?php echo strtoupper(BASE_CURRENCY);?>">
            <input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="no_note" value="1">
            <input type="hidden" name="notify_url" value="<?php echo $link?>">
            <input type="hidden" name="return" value="<?php echo $link_return?>">
            <input type="hidden" name="cancel_return" value="<?php echo $link_cancel?>">
            <input type="hidden" name="item_name" value="<?php echo $desc?>">
            <input type="hidden" name="a3" value="<?php echo $amount_subscribe?>">
            <input type="hidden" name="p3" value="<?php echo $A2B->config['epayment_method']['paypal_subscription_period_number']?>">
            <input type="hidden" name="t3" value="<?php echo $A2B->config['epayment_method']['paypal_subscription_time_period']?>">
            <input type="hidden" name="src" value="1">
            <input type="hidden" name="sra" value="1">
            <input type="submit" class="form_input_button" value="<?php echo gettext("SUBSCRIPTION");?>">
            </form>
        </td>
    </tr>

    <?php } ?>
</table>

<?php } else { ?>
<br></br><br></br>

<?php } ?>
</div>

<?php
$smarty->display('footer.tpl');
