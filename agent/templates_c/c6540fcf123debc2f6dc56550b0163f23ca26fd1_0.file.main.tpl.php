<?php
/* Smarty version 3.1.33, created on 2019-11-25 20:29:56
  from '/var/www/html/crm/agent/Public/templates/default/main.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ddc39c4e99196_16191694',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c6540fcf123debc2f6dc56550b0163f23ca26fd1' => 
    array (
      0 => '/var/www/html/crm/agent/Public/templates/default/main.tpl',
      1 => 1574439142,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5ddc39c4e99196_16191694 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php if (($_smarty_tpl->tpl_vars['popupwindow']->value == 0)) {?>
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="dashboard.php">
					<!-- <img alt="Logo" src="templates/<?php echo $_smarty_tpl->tpl_vars['SKIN_NAME']->value;?>
/newtheme/theme/default/src/assets/media/logos/admin_25.png" /> -->
					<img  src="<?php  echo AGENTIMGNAME?>" style="height:77px;width:220px">
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>





<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- begin:: Aside -->
				<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
				<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

					<!-- begin:: Aside -->
					<div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
						<div class="kt-aside__brand-logo">
							<a href="dashboard.php">
								<img  src="<?php  echo AGENTIMGNAME?>" style="height:77px;width:220px">							</a>
						</div>
						<div class="kt-aside__brand-tools">
							<button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler"><span></span></button>
						</div>
					</div>

					<!-- end:: Aside -->

					<!-- begin:: Aside Menu -->
					
					<?php if (($_smarty_tpl->tpl_vars['popupwindow']->value == 0)) {?>
					<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
						<div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1">
							<ul class="kt-menu__nav ">
							
							
								<li class="kt-menu__item  kt-menu__item--active" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="dashboard.php" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-icon flaticon2-architecture-and-city"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Dashboard");?></span></a></li>
								
								<li class="kt-menu__item  kt-menu__item--submenu <?php  if(strpos($_SERVER['REQUEST_URI'],("billing_entity_card billing_entity_agent billing_entity_user")) != 0) {echo "open active";} ?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover" ><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon2-user"></i><span class="kt-menu__link-text"><?php  echo gettext("User");?></span><span class="kt-menu__link-badge"></span><i class="kt-menu__ver-arrow la la-angle-right"> </i></a>
    									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
											<ul class="kt-menu__subnav">
												<?php if (($_smarty_tpl->tpl_vars['ACXCUSTOMER']->value > 0)) {?>
        											<li <?php  if(strpos($_SERVER['REQUEST_URI'],"billing_entity_card") != 0) ?> class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Customer");?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
															<ul class="kt-menu__subnav">
																<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_card.php?section=1" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("List Customers");?></span></a></li>
														
																<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_callerid.php?section=1" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Caller Id");?></span></a></li>

																<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="card-history.php?section=1" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Card History");?></span></a></li>

                                                        		<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_group_logo.php" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Group Logo");?></span></a></li>
															</ul>
														</div>
        											</li>
        
         											<li <?php  if(strpos($_SERVER['REQUEST_URI'],"billing_entity_card") != 0) ?> class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Customer Signup");?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
														<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
															<ul class="kt-menu__subnav">
																<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Agents</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_signup_agent.php?section=8" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Signup URL List");?></span></a></li>
																<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Agents</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_signup_agent.php?section=8" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Add new signup URL");?></span></a></li>
															</ul>
														</div>
         											</li>
         										<?php }?>                                      
    										</ul>
										</div>
								</li>
<?php if (($_smarty_tpl->tpl_vars['ACXBILLING']->value > 0)) {?>
								<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover" ><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-support"></i><span class="kt-menu__link-text"><?php  echo gettext("CallShop");?></span><span class="kt-menu__link-badge"></span><i class="kt-menu__ver-arrow la la-angle-right"> </i></a>
    									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
											<ul class="kt-menu__subnav">
												
        											 
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_callshop.php" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("CallShop Billing");?></span></a></li>
													 
        
         											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("CallShop Config");?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
														<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
															<ul class="kt-menu__subnav">
																<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Agents</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="callshop_logo_upload.php" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Receipt Logo Upload");?></span></a></li>
																<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Agents</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="callshop_receipt_template.php" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Receipt Template");?></span></a></li>
															</ul>
														</div>
         											</li>
         										                                     
    										</ul>
										</div>
								</li>
<?php }?> 
								<li class="kt-menu__item  kt-menu__item--submenu <?php  if(strpos($_SERVER['REQUEST_URI'],("billing_entity_card billing_entity_agent billing_entity_user")) != 0) {echo "open active";} ?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover" ><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon2-browser-2"></i><span class="kt-menu__link-text"><?php  echo gettext("Rates");?></span><span class="kt-menu__link-badge"></span><i class="kt-menu__ver-arrow la la-angle-right"> </i></a>
    									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
											<ul class="kt-menu__subnav">
												<?php if (($_smarty_tpl->tpl_vars['ACXCUSTOMER']->value > 0)) {?>
													<ul class="kt-menu__subnav">
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_def_ratecard.php?section=3" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Browse Rates");?></span></a></li>
													</ul>
         										<?php }?>                                      
    										</ul>
										</div>
								</li>
								<?php if (($_smarty_tpl->tpl_vars['ACXBILLING']->value > 0)) {?>
								<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover" ><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon2-writing"></i><span class="kt-menu__link-text"><?php  echo gettext("Billing");?></span><span class="kt-menu__link-badge"></span><i class="kt-menu__ver-arrow la la-angle-right"> </i></a>
    									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
											<ul class="kt-menu__subnav">
												
													<ul class="kt-menu__subnav">
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_moneysituation.php?section=2" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Account Balance");?></span></a></li>
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_logrefill_agent.php?section=2" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Agent's Refills");?></span></a></li>
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_payment_agent.php?section=2" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Agent's Payments");?></span></a></li>
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_logrefill.php?section=2" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Customer's Refills");?></span></a></li>
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_payment.php?section=2" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Customer's Payments");?></span></a></li>
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_paymentlog.php?section=2" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Payment Logs");?></span></a></li>
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_entity_commission.php?section=2" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Commission");?></span></a></li>
													</ul>
         										                                    
    										</ul>
										</div>
								</li>
								<?php }?>  
								<li class="kt-menu__item  kt-menu__item--submenu <?php  if(strpos($_SERVER['REQUEST_URI'],("billing_entity_card billing_entity_agent billing_entity_user")) != 0) ?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover" ><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text"><?php  echo gettext("Reports");?></span><span class="kt-menu__link-badge"></span><i class="kt-menu__ver-arrow la la-angle-right"> </i></a>
    									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
											<ul class="kt-menu__subnav">
												<?php if (($_smarty_tpl->tpl_vars['ACXCUSTOMER']->value > 0)) {?>
													<ul class="kt-menu__subnav">
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="call-log-customers.php?section=1" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("CDR Reports");?></span></a></li>
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="call-last-month.php?section=1" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Monthly Traffic");?></span></a></li>
													</ul>
         										<?php }?>                                      
    										</ul>
										</div>
								</li>

								<li class="kt-menu__item  kt-menu__item--submenu <?php  if(strpos($_SERVER['REQUEST_URI'],("billing_entity_card billing_entity_agent billing_entity_user")) != 0) ?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover" ><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-chat"></i><span class="kt-menu__link-text"><?php  echo gettext("Support");?></span><span class="kt-menu__link-badge"></span><i class="kt-menu__ver-arrow la la-angle-right"> </i></a>
    									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
											<ul class="kt-menu__subnav">
												<?php if (($_smarty_tpl->tpl_vars['ACXCUSTOMER']->value > 0)) {?>
													<ul class="kt-menu__subnav">
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_ticket.php?section=1" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Customer Tickets");?></span></a></li>
															<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Customer</span></span></li><li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover"><a  href="billing_support.php?section=1" class="kt-menu__link kt-menu__toggle "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text"><?php  echo gettext("Create Tickets");?></span></a></li>
													</ul>
         										<?php }?>                                      
    										</ul>
										</div>
								</li>
							 
							</ul>
						</div>
					</div>
					<?php }?>

					<!-- end:: Aside Menu -->
				</div>
				
				
				
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

						<!-- begin: Header Menu -->
						<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
						<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
							<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
								<ul class="kt-menu__nav ">
									<!--<li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="demo12/index.html" class="kt-menu__link "><span class="kt-menu__link-text">Application</span></a></li> -->
								</ul>
							</div>
						</div>

						<!-- end: Header Menu -->

						<!-- begin:: Header Topbar -->
						<div class="kt-header__topbar">

								

							<!--begin: Quick Actions -->
							<div class="kt-header__topbar-item dropdown">
								<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px" aria-expanded="true">
									<span class="kt-header__topbar-icon"><i class="flaticon2-gear"></i></span>
								</div>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
									<form>

										<!--begin: Head -->
										<div class="kt-head kt-head--skin-light">
											<h3 class="kt-head__title">
												User Quick Actions
												<span class="kt-space-15"></span>
											</h3>
										</div>

										<!--end: Head -->

										<!--begin: Grid Nav -->
										<div class="kt-grid-nav kt-grid-nav--skin-light">
																			
											<div class="kt-grid-nav__row">
												<a href="agentinfo.php" class="kt-grid-nav__item">
													<span class="kt-grid-nav__icon">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success kt-svg-icon--lg">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<polygon id="Shape" points="0 0 24 0 24 24 0 24" />
																<path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" id="Combined-Shape" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																<path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" id="Combined-Shape" fill="#000000" fill-rule="nonzero" />
															</g>
														</svg> </span>
													<span class="kt-grid-nav__title">My Profile</span>
													<span class="kt-grid-nav__desc"></span>
												</a>
												<a href="billing_entity_password.php" class="kt-grid-nav__item">
													<span class="kt-grid-nav__icon">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success kt-svg-icon--lg">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect id="bound" x="0" y="0" width="24" height="24" />
																<path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" id="Combined-Shape" fill="#000000" />
																<path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" id="Path" fill="#000000" opacity="0.3" />
															</g>
														</svg> </span>
													<span class="kt-grid-nav__title">Change password</span>
													<span class="kt-grid-nav__desc"></span>
												</a>
											</div>
										</div>

										<!--end: Grid Nav -->
									</form>
								</div>
							</div>

							<!--end: Quick Actions -->


							<!--begin: Language bar -->
							<!-- <div class="kt-header__topbar-item kt-header__topbar-item--langs">
								<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
									<span class="kt-header__topbar-icon">
										<img class="" src="templates/default/newtheme/theme/classic/assets/media/flags/012-uk.svg" alt="" />
									</span>
								</div>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround">
									<ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
										<li class="kt-nav__item kt-nav__item--active">
											<a href="#" class="kt-nav__link">
												<span class="kt-nav__link-icon"><img src="templates/default/newtheme/theme/classic/assets/media/flags/020-flag.svg" alt="" /></span>
												<span class="kt-nav__link-text">English</span>
											</a>
										</li>
										<li class="kt-nav__item">
											<a href="#" class="kt-nav__link">
												<span class="kt-nav__link-icon"><img src="templates/default/newtheme/theme/classic/assets/media/flags/016-spain.svg" alt="" /></span>
												<span class="kt-nav__link-text">Spanish</span>
											</a>
										</li>
										<li class="kt-nav__item">
											<a href="#" class="kt-nav__link">
												<span class="kt-nav__link-icon"><img src="templates/default/newtheme/theme/classic/assets/media/flags/017-germany.svg" alt="" /></span>
												<span class="kt-nav__link-text">German</span>
											</a>
										</li>
									</ul>
								</div>
							</div> -->

							<!--end: Language bar -->

							<!--begin: User Bar -->
							<div class="kt-header__topbar-item kt-header__topbar-item--user">
								<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
									<div class="kt-header__topbar-user">
										<span class="kt-hidden kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
										<span class="kt-hidden kt-header__topbar-username kt-hidden-mobile">Sean</span>
										<img alt="Pic" class="kt-radius-100" src="templates/default/newtheme/theme/classic/assets/media/users/300_25.png" />

										<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
										<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold kt-hidden">S</span>
									</div>
								</div>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

									<!--begin: Head -->
									<div class="kt-user-card kt-user-card--skin-light kt-notification-item-padding-x">
										<!--<div class="kt-user-card__avatar">
											<!--<img class="kt-hidden-" alt="Pic" src="templates/default/newtheme/theme/classic/assets/media/users/300_25.jpg" /> -->

											<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) 
											<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold kt-hidden">S</span> 
										</div>-->
										<div class="kt-user-card__name">
											Welcome, Agent
										</div>
										<div class="kt-user-card__badge">
											<span class="btn btn-label-primary btn-sm btn-bold btn-font-md">0 messages</span> 
										</div>
									</div>

									<!--end: Head -->

									<!--begin: Navigation -->
									<div class="kt-notification">
										<!--<a href="#" class="kt-notification__item">
											<div class="kt-notification__item-icon">
												<i class="flaticon2-calendar-3 kt-font-success"></i>
											</div>
											<div class="kt-notification__item-details">
												<div class="kt-notification__item-title kt-font-bold">
													My Profile
												</div>
												<div class="kt-notification__item-time">
													Account settings and more
												</div>
											</div>
										</a>
										<a href="#" class="kt-notification__item">
											<div class="kt-notification__item-icon">
												<i class="flaticon2-mail kt-font-warning"></i>
											</div>
											<div class="kt-notification__item-details">
												<div class="kt-notification__item-title kt-font-bold">
													My Messages
												</div>
												<div class="kt-notification__item-time">
													Inbox and tasks
												</div>
											</div>
										</a>
										<a href="#" class="kt-notification__item">
											<div class="kt-notification__item-icon">
												<i class="flaticon2-rocket-1 kt-font-danger"></i>
											</div>
											<div class="kt-notification__item-details">
												<div class="kt-notification__item-title kt-font-bold">
													My Activities
												</div>
												<div class="kt-notification__item-time">
													Logs and notifications
												</div>
											</div>
										</a>
										<a href="#" class="kt-notification__item">
											<div class="kt-notification__item-icon">
												<i class="flaticon2-hourglass kt-font-brand"></i>
											</div>
											<div class="kt-notification__item-details">
												<div class="kt-notification__item-title kt-font-bold">
													My Tasks
												</div>
												<div class="kt-notification__item-time">
													latest tasks and projects
												</div>
											</div>
										</a>
										<a href="#" class="kt-notification__item">
											<div class="kt-notification__item-icon">
												<i class="flaticon2-cardiogram kt-font-warning"></i>
											</div>
											<div class="kt-notification__item-details">
												<div class="kt-notification__item-title kt-font-bold">
													Billing
												</div>
												<div class="kt-notification__item-time">
													billing & statements <span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">2 pending</span>
												</div>
											</div>
										</a>-->
										<div class="kt-notification__custom kt-space-between">
											<a href="logout.php?logout=true" target="_top" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
											
										</div>
									</div> 


									<!--end: Navigation -->
								</div>
							</div>

							<!--end: User Bar -->
						</div>

						<!-- end:: Header Topbar -->
					</div>
					
					<!--begin:: scrolltop-->

					<div id="kt_scrolltop" class="kt-scrolltop">
						<i class="fa fa-arrow-up"></i>
					</div>
					
					<!--end:: scrolltop-->
				

<!--new Code-->







<?php } else { ?>
<div>





<?php }?>

<?php if (($_smarty_tpl->tpl_vars['LCMODAL']->value > 0)) {
echo '<script'; ?>
 type="text/javascript">
    <!--loadLicenceModal();-->
<?php echo '</script'; ?>
>
<?php }?>

<!--<?php echo $_smarty_tpl->tpl_vars['MAIN_MSG']->value;?>
-->

<div>&nbsp;</div>
<?php }
}
