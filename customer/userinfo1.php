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
            </span>
        </div>
    </div>
</div>
<!-- end:: Subheader --><br><br><br><br>

<div class="kt-container kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="col-md-12">
		<!--begin::Portlet-->
		<div class="kt-widget17">
            <div class="kt-widget17__stats">
            <div class="kt-widget17__items">
                <a href="billing_mobile.php?section=7"><div class="kt-widget17__item" style="background-color: #ffb822;">
					<span class="kt-widget17__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--danger">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z" id="Combined-Shape-Copy" fill="#000000" opacity="0.3" transform="translate(11.500000, 12.000000) rotate(-345.000000) translate(-11.500000, -12.000000) "/>
        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z M11.5,14 C12.6045695,14 13.5,13.1045695 13.5,12 C13.5,10.8954305 12.6045695,10 11.5,10 C10.3954305,10 9.5,10.8954305 9.5,12 C9.5,13.1045695 10.3954305,14 11.5,14 Z" id="Combined-Shape" fill="#000000"/>
    </g>
</svg>					</span>  
						</a><a href="billing_mobile.php?section=7" class="kt-widget17__subtitle">
							Mobile TopUp
						</a> 
                    </div> &nbsp;
                    </a>

					<a href="sendsms.php?section=7"><div class="kt-widget17__item" style="background-color: #1ff3b3;">
						<span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M4,16 L5,16 C5.55228475,16 6,16.4477153 6,17 C6,17.5522847 5.55228475,18 5,18 L4,18 C3.44771525,18 3,17.5522847 3,17 C3,16.4477153 3.44771525,16 4,16 Z M1,11 L5,11 C5.55228475,11 6,11.4477153 6,12 C6,12.5522847 5.55228475,13 5,13 L1,13 C0.44771525,13 6.76353751e-17,12.5522847 0,12 C-6.76353751e-17,11.4477153 0.44771525,11 1,11 Z M3,6 L5,6 C5.55228475,6 6,6.44771525 6,7 C6,7.55228475 5.55228475,8 5,8 L3,8 C2.44771525,8 2,7.55228475 2,7 C2,6.44771525 2.44771525,6 3,6 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
        <path d="M10,6 L22,6 C23.1045695,6 24,6.8954305 24,8 L24,16 C24,17.1045695 23.1045695,18 22,18 L10,18 C8.8954305,18 8,17.1045695 8,16 L8,8 C8,6.8954305 8.8954305,6 10,6 Z M21.0849395,8.0718316 L16,10.7185839 L10.9150605,8.0718316 C10.6132433,7.91473331 10.2368262,8.02389331 10.0743092,8.31564728 C9.91179228,8.60740125 10.0247174,8.9712679 10.3265346,9.12836619 L15.705737,11.9282847 C15.8894428,12.0239051 16.1105572,12.0239051 16.294263,11.9282847 L21.6734654,9.12836619 C21.9752826,8.9712679 22.0882077,8.60740125 21.9256908,8.31564728 C21.7631738,8.02389331 21.3867567,7.91473331 21.0849395,8.0718316 Z" id="Combined-Shape" fill="#000000"/>
    </g>
</svg>						</span> 
						</a><a href="sendsms.php?section=7" class="kt-widget17__subtitle">
							Send SMS
						</a>   
                    </div> &nbsp;
                    </a>

					<a href="credit_transfer.php?section=7"><div class="kt-widget17__item" style="background-color: #f5622c;">
						<span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <circle id="Oval-47" fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
        <rect id="Rectangle-162" fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" id="Combined-Shape" fill="#000000"/>
    </g>
</svg>						</span>  
						</a><a href="credit_transfer.php?section=7" class="kt-widget17__subtitle">
							Send Balance
						</a>  
                    </div>	&nbsp;
                    </a>
                    
                    <a href="simulator.php?section=7"><div class="kt-widget17__item" style="background-color: #245fc1;">
						<span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--warning">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M11,16 L11,10 C11,9.44771525 11.4477153,9 12,9 C12.5522847,9 13,9.44771525 13,10 L13,16 L19,16 C20.1045695,16 21,16.8954305 21,18 L21,19 C21,20.1045695 20.1045695,21 19,21 L5,21 C3.8954305,21 3,20.1045695 3,19 L3,18 C3,16.8954305 3.8954305,16 5,16 L11,16 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
        <circle id="Oval" fill="#000000" cx="12" cy="7" r="3"/>
    </g>
</svg>						</span>  
						</a><a href="simulator.php?section=7" class="kt-widget17__subtitle">
							Simulator
						</a> 
                    </div> &nbsp;
                    </a>

                    <a href="billing_entity_voucher.php"><div class="kt-widget17__item" style="background-color: #ffb822;">
						<span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--danger">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <polygon id="Combined-Shape" fill="#000000" opacity="0.3" points="12 20.0218549 8.47346039 21.7286168 6.86905972 18.1543453 3.07048824 17.1949849 4.13894342 13.4256452 1.84573388 10.2490577 5.08710286 8.04836581 5.3722735 4.14091196 9.2698837 4.53859595 12 1.72861679 14.7301163 4.53859595 18.6277265 4.14091196 18.9128971 8.04836581 22.1542661 10.2490577 19.8610566 13.4256452 20.9295118 17.1949849 17.1309403 18.1543453 15.5265396 21.7286168"/>
        <polygon id="Stroke-1" fill="#000000" points="14.0890818 8.60255815 8.36079737 14.7014391 9.70868621 16.049328 15.4369707 9.950447"/>
        <path d="M10.8543431,9.1753866 C10.8543431,10.1252593 10.085524,10.8938719 9.13585777,10.8938719 C8.18793881,10.8938719 7.41737243,10.1252593 7.41737243,9.1753866 C7.41737243,8.22551387 8.18793881,7.45690126 9.13585777,7.45690126 C10.085524,7.45690126 10.8543431,8.22551387 10.8543431,9.1753866" id="Fill-2" fill="#000000" opacity="0.3"/>
        <path d="M14.8641422,16.6221564 C13.9162233,16.6221564 13.1456569,15.8535438 13.1456569,14.9036711 C13.1456569,13.9520555 13.9162233,13.1851857 14.8641422,13.1851857 C15.8138085,13.1851857 16.5826276,13.9520555 16.5826276,14.9036711 C16.5826276,15.8535438 15.8138085,16.6221564 14.8641422,16.6221564 Z" id="Fill-4" fill="#000000" opacity="0.3"/>
    </g>
</svg>						</span>  
						<a href="billing_entity_voucher.php" class="kt-widget17__subtitle">
							Voucher
						</a>  
                    </div>	&nbsp;
                    </a>
                    
                    <a href="call-history.php"><div class="kt-widget17__item" style="background-color: #1ff3b3;">
						<span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <rect id="Rectangle-62-Copy" fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"/>
        <rect id="Rectangle-62-Copy-2" fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"/>
        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" id="Path-95" fill="#000000" fill-rule="nonzero"/>
        <rect id="Rectangle-62-Copy-4" fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"/>
    </g>
</svg>						</span>  
						<a href="call-history.php" class="kt-widget17__subtitle">
							Customer Report
						</a> 
                    </div>
                    </a>
        </div>
    </div>
</div>
</div>
<br>

<!-- begin:: Content1 -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<!--begin::Section-->
    <div class="col-xl-12">
        <!--begin:: Widgets/Applications/User/Profile3-->
        <div class="kt-portlet kt-portlet--height-fluid"  style="background-color: #c7c5d8;">
            <div class="kt-portlet__body">
                <div class="kt-widget kt-widget--user-profile-3">
                    <div class="kt-widget__top">
                        <div class="col-lg-9 col-xl-6">
                            <div class="kt-avatar kt-avatar--outline kt-avatar--circle" id="kt_apps_user_add_avatar">
                                <div class="kt-avatar__holder">
                                                
                                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Upload Picture">
                                        <i class="fa fa-pen"></i><!-- <a href="cc_displayimage.php"></a>
                                                    <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg"> -->
                                                    <!--<a href="cc_displayimage.php" title="Upload Picture">	
                                                        <img src="templates/default/images/dp/<?php echo CUSTOMERDP; ?>" alt="user avatar" class="baba" >
                                                    </a> -->
                                    </label> 
									<div class="user_heading_content">
										<h4 style="color:white;margin:10px;">
										<?php echo strtoupper($customer_info[3]); ?></h4>								
									</div>

                                    <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <div class="col-lg-6 col-xl-6">
                                                    <div class="kt-widget__item">
                                                        <div class="kt-widget__details"><br>
                                                            <i class="flaticon-piggy-bank"></i>
                                                            <span class="kt-widget__title"><b><?php echo gettext("BALANCE REMAINING");?> :</b>&nbsp;</span>
                                                                <font class="kt-widget__value"><?php echo $credit_cur.' '.$customer_info[22]; ?></font>
                                                        </div>
                                                        <br>
                                                        <div class="kt-widget__details">
                                                            <i class="flaticon-user-ok"></i>
                                                            <span class="kt-widget__title"><b><?php echo gettext("ACCOUNT NUMBER");?> :</b>&nbsp;</span>
                                                                <font class="kt-widget__value"><?php echo $customer_info[0]; ?></font>
                                                        </div>
                                                    </div>
                                        </div>

                        <!-- <table>
                                        <tr style="background-color:#DC7633">
							    <td>
								    <div class="user_heading_avatar"> -->
										<!--<div class="thumbnail"> -->
										<!-- <div>
										<a href="cc_displayimage.php" title="Upload Picture">	<img src="templates/default/images/dp/<?php echo CUSTOMERDP; ?>" alt="user avatar" class="baba" ></a>
										
										</div>
									</div>
									<div class="user_heading_content">
										<h4 style="color:white;margin:10px;">
										<?php echo strtoupper($customer_info[3]); ?></h4>								
									</div>
								     
								</td>
							    <td style="width:25%">
								   <table >
									  <tr>
									    <td style="background-color:#DC7633"><font color="white"><?php echo gettext("BALANCE - REMAINING");?> :</font></td>
									    <td style="background-color:#DC7633"><font color="white"><?php echo $credit_cur.' '.$customer_info[22]; ?></font></td>
									  </tr>
									  <tr>
									    <td style="background-color:#DC7633"><font color="white"><?php echo gettext("ACCOUNT NUMBER");?> :</font></td>
									    <td style="background-color:#DC7633"><font color="white"><?php echo $customer_info[0]; ?></font></td>
									  </tr>
								  </table>
								</tr>
								
								</td>
							
							
							
							</table> -->
                                
                            <!-- <a href="cc_displayimage.php" title="Upload Picture">	<img src="templates/default/images/dp/<?php echo CUSTOMERDP; ?>" alt="user avatar" class="baba" ></a> -->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!--ends:: Widgets/Applications/User/Profile3-->
    </div>
    <!--end::Section-->
</div>
<!-- end:: Content1 -->

<!-- begin:: Content2 -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
                    <font class="kt-portlet__head-title"><?php echo gettext("Customer Information"); ?></font>
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
			<!--begin::Form-->
			<form action="" method="" class="kt-form">
				<div class="kt-portlet__body">
									<div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("First Name");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                            <font class="form-control"><?php echo $customer_info[3]; ?></font>
										</div>
									</div>
									<div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("Last Name");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                            <font class="form-control"><?php echo $customer_info[2]; ?></font>
										</div>
									</div>
                                    <div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("Email");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
                                                <font class="form-control" placeholder="example@gmail.com"><?php echo $customer_info[10]; ?></font>
											</div>
										</div>
									</div>
									<div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("Phone");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
                                                <font class="form-control"><?php echo $customer_info[9]; ?></font>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("Fax");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-fax"></i></span></div>
                                                <font class="form-control"><?php echo $customer_info[8]; ?></font>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("Address");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                            <font class="form-control"><?php echo $customer_info[4]; ?></font>
										</div>
                                    </div>
                                    <div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("Country");?> </font>
									    <div class="col-lg-6 col-md-9 col-sm-12">
                                            <font class="form-control"><?php echo $customer_info[7]; ?></font>	
									    </div>
							        </div>
									<div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("State");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                        <font class="form-control"><?php echo $customer_info[6]; ?></font>
										</div>
									</div>
									<div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("City");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                            <font class="form-control"><?php echo $customer_info[5]; ?></font>
										</div>
									</div>
									<div class="form-group row">
                                        <font class="col-lg-4 col-sm-12"><?php echo gettext("Zipcode");?> </font>
										<div class="col-lg-6 col-md-9 col-sm-12">
                                        <font class="form-control"><?php echo $customer_info[11]; ?></font>
										</div>
                                    </div>
                                    
				</div>
            </form>
    </div> 
</div>
</div>
<!-- end:: Content2 -->
<br>
<?php
												echo $PAYMENT_METHOD;
									  ?>
									  
									<?php if ($A2B->config["epayment_method"]['enable']) { ?>

									<br>



									<table style="width:30%;margin:0 auto;" cellspacing="0"  align="center" >
										<tr background="<?php echo Images_Path; ?>/background_cells.gif" >
											
											

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
											<td align="center">
												<form action="checkout_payment.php" method="post">
						
													<input type="submit" class="btn btn-success" style="" value="<?php echo gettext("BUY NOW");?>">
													<br>
												</form>
											</td>
											
											<td align="center">
												<form action="checkout_payment_interswitch.php" method="POST">                         
                                                        
                          <button type="submit" class="btn btn-success" value="Submit">INTERSWITCH BUY</button> 
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
