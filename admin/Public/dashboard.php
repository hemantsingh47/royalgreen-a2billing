<?php
 
include '../lib/admin.defines.php';
include_once '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';
if (!has_rights(ACX_DASHBOARD)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}  
 
$smarty->display('main.tpl');
 
 $date = date('Y-m-d');
 //print_r($date); 
 //die();
// SELECT *  FROM `cc_card` WHERE `creationdate` LIKE '%2017-02-23%' '%".$country."%'    
$inst_table = new Table();
$query_card = "SELECT count(username) as username from cc_card";
$total_customer = $inst_table -> SQLExec($DBHandle, $query_card); 
//print_r($total_customer); 

$query_card_new = "SELECT count(username) as  username  FROM `cc_card` WHERE `creationdate` LIKE  '%".$date."%'";  
//SELECT count(username) as  username  FROM `cc_card` WHERE `creationdate` LIKE '%2017-12-01%'
$new_customer = $inst_table -> SQLExec($DBHandle, $query_card_new); 
//print_r($new_customer); 
//die();
$query_payment = "SELECT count(id) as id from `cc_payments`";
$total_payment = $inst_table -> SQLExec($DBHandle, $query_payment); 
//print_r($total_payment); 
 
$query_voucher = "SELECT count(voucher) as  voucher  FROM `cc_voucher` WHERE `used` = 1;";
$total_voucher_used = $inst_table -> SQLExec($DBHandle, $query_voucher); 
//print_r($total_customer);  
 
$left = array();
$center = array();
$right = array();
function put_dislay($position, $title, $links)
{
    global $left;
    global $center;
    global $right;
    if ($position=="LEFT") {
        $idx = count($left);
        $left[$idx] = array();
        $left[$idx]["title"] = $title;
        $left[$idx]["links"] = $links;
    } elseif ($position=="CENTER") {
        $idx = count($center);
        $center[$idx] = array();
        $center[$idx]["title"] = $title;
        $center[$idx]["links"] = $links;
    } elseif ($position=="RIGHT") {
        $idx = count($right);
        $right[$idx] = array();
        $right[$idx]["title"] = $title;
        $right[$idx]["links"] = $links;
    }
}
if ( !empty($A2B->config["dashboard"]["customer_info_enabled"]) && $A2B->config["dashboard"]["customer_info_enabled"]!="NONE") {
    put_dislay($A2B->config["dashboard"]["customer_info_enabled"],gettext("ACCOUNTS INFO"),array("./modules/customers_numbers.php","./modules/customers_lastmonth.php"));
}
if ( !empty($A2B->config["dashboard"]["refill_info_enabled"]) && $A2B->config["dashboard"]["refill_info_enabled"]!="NONE") {
    put_dislay($A2B->config["dashboard"]["refill_info_enabled"],gettext("REFILLS INFO"),array("./modules/refills_lastmonth.php"));
}

/*if ( !empty($A2B->config["dashboard"]["payment_info_enabled"]) && $A2B->config["dashboard"]["payment_info_enabled"]!="NONE") {
    put_dislay($A2B->config["dashboard"]["payment_info_enabled"],gettext("PAYMENTS INFO"),array("./modules/payments_lastmonth.php")); 
} */ 

if ( !empty($A2B->config["dashboard"]["call_info_enabled"]) && $A2B->config["dashboard"]["call_info_enabled"]!="NONE") {
    put_dislay($A2B->config["dashboard"]["call_info_enabled"],gettext("CALLS INFO TODAY"),array("./modules/calls_counts.php","./modules/calls_lastmonth.php"));
    //put_dislay($A2B->config["dashboard"]["call_info_enabled"],gettext("CALLS INFO TODAY"),array("./modules/calls_counts.php","./modules/calls_lastmonth.php"));
} 

/*
if ( !empty($A2B->config["dashboard"]["system_info_enable"]) && $A2B->config["dashboard"]["system_info_enable"]!="NONE") {
    put_dislay($A2B->config["dashboard"]["system_info_enable"],gettext("SYSTEM INFO"),array("./modules/system_info.php"));
}
if ( !empty($A2B->config["dashboard"]["news_enabled"]) && $A2B->config["dashboard"]["news_enabled"]!="NONE") {
    put_dislay($A2B->config["dashboard"]["news_enabled"],gettext("LATEST NEWS"),array("./modules/news.php"));
}  */

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Dashboard                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Home                        </a>
                                            
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                                
                <!-- <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="" data-placement="left" data-original-title="Quick actions">
                    <a href="#" class="btn btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success kt-svg-icon--md">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon id="Shape" points="0 0 24 0 24 24 0 24"></polygon>
        <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" id="Combined-Shape" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
        <path d="M11,14 L9,14 C8.44771525,14 8,13.5522847 8,13 C8,12.4477153 8.44771525,12 9,12 L11,12 L11,10 C11,9.44771525 11.4477153,9 12,9 C12.5522847,9 13,9.44771525 13,10 L13,12 L15,12 C15.5522847,12 16,12.4477153 16,13 C16,13.5522847 15.5522847,14 15,14 L13,14 L13,16 C13,16.5522847 12.5522847,17 12,17 C11.4477153,17 11,16.5522847 11,16 L11,14 Z" id="Combined-Shape" fill="#000000"></path>
    </g>
</svg> -->                       <!--<i class="flaticon2-plus"></i>-->
                    <!-- </a>
                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-md dropdown-menu-right"> -->
                        <!--begin::Nav-->
                        <!-- <ul class="kt-nav">
                            <li class="kt-nav__head">
                                Add anything or jump to:                                   
                                <i class="flaticon2-information" data-toggle="kt-tooltip" data-placement="right" title="" data-original-title="Click to learn more..."></i>
                            </li>
                            <li class="kt-nav__separator"></li>
                            <li class="kt-nav__item">
                                <a href="#" class="kt-nav__link">
                                    <i class="kt-nav__link-icon flaticon2-drop"></i>
                                    <span class="kt-nav__link-text">Order</span>
                                </a>
                            </li>
                            <li class="kt-nav__item">
                                <a href="#" class="kt-nav__link">
                                    <i class="kt-nav__link-icon flaticon2-calendar-8"></i>
                                    <span class="kt-nav__link-text">Ticket</span>
                                </a>
                            </li>
                            <li class="kt-nav__item">
                                <a href="#" class="kt-nav__link">
                                    <i class="kt-nav__link-icon flaticon2-link"></i>
                                    <span class="kt-nav__link-text">Goal</span>
                                </a>
                            </li>
                            <li class="kt-nav__item">
                                <a href="#" class="kt-nav__link">
                                    <i class="kt-nav__link-icon flaticon2-new-email"></i>
                                    <span class="kt-nav__link-text">Support Case</span>
                                    <span class="kt-nav__link-badge">
                                        <span class="kt-badge kt-badge--success">5</span>
                                    </span>
                                </a>
                            </li>
                            <li class="kt-nav__separator"></li>
                            <li class="kt-nav__foot">
                                <a class="btn btn-label-brand btn-bold btn-sm" href="#">Upgrade plan</a>                                    
                                <a class="btn btn-clean btn-bold btn-sm" href="#" data-toggle="kt-tooltip" data-placement="right" title="" data-original-title="Click to learn more...">Learn more</a>
                            </li>
                        </ul> -->
                        <!--end::Nav-->
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->					
					<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<!--Begin::Dashboard 1-->
<!--Begin::Row-->
<div class="row">
	<div class="col-lg-12 col-xl-12 order-lg-1 order-xl-1">
		<!--begin:: Widgets/Activity-->
<div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid">
	<div class="kt-portlet__head kt-portlet__head--noborder kt-portlet__space-x">
		<div class="kt-portlet__head-label">
		   <h3 class="kt-portlet__head-title">
	            Activity
	       </h3>
		</div>
		<div class="kt-portlet__head-toolbar">
			<!--<a href="#" class="btn btn-label-light btn-sm btn-bold dropdown-toggle" data-toggle="dropdown">
				Export
			</a>-->
			<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
				<ul class="kt-nav">
    <li class="kt-nav__section kt-nav__section--first">
        <span class="kt-nav__section-text">Finance</span>
    </li>
    <li class="kt-nav__item">
        <a href="#" class="kt-nav__link">
            <i class="kt-nav__link-icon flaticon2-graph-1"></i>
            <span class="kt-nav__link-text">Statistics</span>
        </a>
    </li>
    <li class="kt-nav__item">
        <a href="#" class="kt-nav__link">
            <i class="kt-nav__link-icon flaticon2-calendar-4"></i>
            <span class="kt-nav__link-text">Events</span>
        </a>
    </li>
    <li class="kt-nav__item">
        <a href="#" class="kt-nav__link">
            <i class="kt-nav__link-icon flaticon2-layers-1"></i>
            <span class="kt-nav__link-text">Reports</span>
        </a>
    </li>
    <li class="kt-nav__section">
        <span class="kt-nav__section-text">Customers</span>
    </li>
    <li class="kt-nav__item">
        <a href="#" class="kt-nav__link">
            <i class="kt-nav__link-icon flaticon2-calendar-4"></i>
            <span class="kt-nav__link-text">Notifications</span>
        </a>
    </li>
    <li class="kt-nav__item">
        <a href="#" class="kt-nav__link">
            <i class="kt-nav__link-icon flaticon2-file-1"></i>
            <span class="kt-nav__link-text">Files</span>
        </a>
    </li>
</ul>			</div>
		</div>
	</div>
	<div class="kt-portlet__body kt-portlet__body--fit">
		<div class="kt-widget17">
			<div class="kt-widget17__visual kt-widget17__visual--chart kt-portlet-fit--top kt-portlet-fit--sides" style="background-color: #fd397a">
				<div class="kt-widget17__chart" style="height:320px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
					<canvas id="kt_chart_activities" width="515" height="216" class="chartjs-render-monitor" style="display: block; width: 515px; height: 216px;"></canvas>
				</div>
			</div>
			<div class="kt-widget17__stats">
				<div class="kt-widget17__items" >
					<div class="kt-widget17__item">
						<a href="billing_entity_card.php"><span class="kt-widget17__icon">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"></rect>
        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" id="Combined-Shape" fill="#000000"></path>
        <rect id="Rectangle-Copy-2" fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
    </g>
</svg>						</span> </a>
<a href="billing_entity_card.php"><span class="kt-widget17__subtitle">
							Total Users
						</span> </a>
						<span class="kt-widget17__desc">
							<?php echo $total_customer[0]['username']; ?> Users
						</span>  
					</div>

					<div class="kt-widget17__item">
						<a href="billing_entity_card.php"><span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon id="Shape" points="0 0 24 0 24 24 0 24"/>
        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" id="Mask" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" id="Mask-Copy" fill="#000000" fill-rule="nonzero"/>
    </g>
</svg>						</span>  </a>
						<a href="billing_entity_card.php"><span class="kt-widget17__subtitle">
							New Users
						</span> </a>
						<span class="kt-widget17__desc">
							<?php echo $new_customer[0]['username']; ?> Users
						</span>  
					</div>	

					<div class="kt-widget17__item">
						<a href="live-call-summery.php"><span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--warning">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M13.0799676,14.7839934 L15.2839934,12.5799676 C15.8927139,11.9712471 16.0436229,11.0413042 15.6586342,10.2713269 L15.5337539,10.0215663 C15.1487653,9.25158901 15.2996742,8.3216461 15.9083948,7.71292558 L18.6411989,4.98012149 C18.836461,4.78485934 19.1530435,4.78485934 19.3483056,4.98012149 C19.3863063,5.01812215 19.4179321,5.06200062 19.4419658,5.11006808 L20.5459415,7.31801948 C21.3904962,9.0071287 21.0594452,11.0471565 19.7240871,12.3825146 L13.7252616,18.3813401 C12.2717221,19.8348796 10.1217008,20.3424308 8.17157288,19.6923882 L5.75709327,18.8875616 C5.49512161,18.8002377 5.35354162,18.5170777 5.4408655,18.2551061 C5.46541191,18.1814669 5.50676633,18.114554 5.56165376,18.0596666 L8.21292558,15.4083948 C8.8216461,14.7996742 9.75158901,14.6487653 10.5215663,15.0337539 L10.7713269,15.1586342 C11.5413042,15.5436229 12.4712471,15.3927139 13.0799676,14.7839934 Z" id="Path-76" fill="#000000"/>
        <path d="M14.1480759,6.00715131 L13.9566988,7.99797396 C12.4781389,7.8558405 11.0097207,8.36895892 9.93933983,9.43933983 C8.8724631,10.5062166 8.35911588,11.9685602 8.49664195,13.4426352 L6.50528978,13.6284215 C6.31304559,11.5678496 7.03283934,9.51741319 8.52512627,8.02512627 C10.0223249,6.52792766 12.0812426,5.80846733 14.1480759,6.00715131 Z M14.4980938,2.02230302 L14.313049,4.01372424 C11.6618299,3.76737046 9.03000738,4.69181803 7.1109127,6.6109127 C5.19447112,8.52735429 4.26985715,11.1545872 4.51274152,13.802405 L2.52110319,13.985098 C2.22450978,10.7517681 3.35562581,7.53777247 5.69669914,5.19669914 C8.04101739,2.85238089 11.2606138,1.72147333 14.4980938,2.02230302 Z" id="Combined-Shape" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
    </g>
</svg>						</span>  </a>
						<a href="live-call-summery.php"><span class="kt-widget17__subtitle">
							Active Calls
						</span> </a>
						<span class="kt-widget17__desc">
							<b id="mycall"> </b>
						</span>  
					</div>	

					<div class="kt-widget17__item">
						<a href="billing_entity_transactions.php"><span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--danger">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M4,6 L20,6 C20.5522847,6 21,6.44771525 21,7 L21,8 C21,8.55228475 20.5522847,9 20,9 L4,9 C3.44771525,9 3,8.55228475 3,8 L3,7 C3,6.44771525 3.44771525,6 4,6 Z M5,11 L10,11 C10.5522847,11 11,11.4477153 11,12 L11,19 C11,19.5522847 10.5522847,20 10,20 L5,20 C4.44771525,20 4,19.5522847 4,19 L4,12 C4,11.4477153 4.44771525,11 5,11 Z M14,11 L19,11 C19.5522847,11 20,11.4477153 20,12 L20,19 C20,19.5522847 19.5522847,20 19,20 L14,20 C13.4477153,20 13,19.5522847 13,19 L13,12 C13,11.4477153 13.4477153,11 14,11 Z" id="Combined-Shape" fill="#000000"/>
        <path d="M14.4452998,2.16794971 C14.9048285,1.86159725 15.5256978,1.98577112 15.8320503,2.4452998 C16.1384028,2.90482849 16.0142289,3.52569784 15.5547002,3.83205029 L12,6.20185043 L8.4452998,3.83205029 C7.98577112,3.52569784 7.86159725,2.90482849 8.16794971,2.4452998 C8.47430216,1.98577112 9.09517151,1.86159725 9.5547002,2.16794971 L12,3.79814957 L14.4452998,2.16794971 Z" id="Path-31" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
    </g>
</svg>						</span>  </a>
						<a href="billing_entity_transactions.php"><span class="kt-widget17__subtitle">
							Total Orders
						</span> </a>
						<span class="kt-widget17__desc">
							<?php echo $total_payment[0]['id']; ?>
						</span>  
					</div>

					<div class="kt-widget17__item">
						<a href="billing_entity_voucher.php"><span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M3,10.0500091 L3,8 C3,7.44771525 3.44771525,7 4,7 L9,7 L9,9 C9,9.55228475 9.44771525,10 10,10 C10.5522847,10 11,9.55228475 11,9 L11,7 L21,7 C21.5522847,7 22,7.44771525 22,8 L22,10.0500091 C20.8588798,10.2816442 20,11.290521 20,12.5 C20,13.709479 20.8588798,14.7183558 22,14.9499909 L22,17 C22,17.5522847 21.5522847,18 21,18 L11,18 L11,16 C11,15.4477153 10.5522847,15 10,15 C9.44771525,15 9,15.4477153 9,16 L9,18 L4,18 C3.44771525,18 3,17.5522847 3,17 L3,14.9499909 C4.14112016,14.7183558 5,13.709479 5,12.5 C5,11.290521 4.14112016,10.2816442 3,10.0500091 Z M10,11 C9.44771525,11 9,11.4477153 9,12 L9,13 C9,13.5522847 9.44771525,14 10,14 C10.5522847,14 11,13.5522847 11,13 L11,12 C11,11.4477153 10.5522847,11 10,11 Z" id="Combined-Shape-Copy" fill="#000000" opacity="0.3" transform="translate(12.500000, 12.500000) rotate(-45.000000) translate(-12.500000, -12.500000) "/>
    </g>
</svg>					</span></a>  
						<a href="billing_entity_voucher.php"><span class="kt-widget17__subtitle">
							Used Vouchers
						</span> </a>
						<span class="kt-widget17__desc">
							<?php echo $total_voucher_used[0]['voucher']; ?>
						</span>  
					</div>
                </div>
                </div><br><br><br><br>

					<div class="kt-widget17__stats">
				    <div class="kt-widget17__items">
                    <div class="kt-widget17__item">
						<a href="billing_entity_def_ratecard.php"><span class="kt-widget17__icon">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"></rect>
        <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" id="Combined-Shape" fill="#000000" opacity="0.3"></path>
        <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" id="Rectangle-102-Copy" fill="#000000"></path>
    </g>
</svg>						</span>  </a>
						<a href="billing_entity_def_ratecard.php"><span class="kt-widget17__subtitle">
							Rates
						</span> </a>
						<span class="kt-widget17__desc">
							
						</span>  
					</div>		

					<div class="kt-widget17__item">
						<a href="call-log-customers.php"><span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--warning">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" id="Path-95" fill="#000000" fill-rule="nonzero"/>
        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" id="Path-97" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
    </g>
</svg>						</span>  </a>
						<a href="call-log-customers.php"><span class="kt-widget17__subtitle">
							CDR Reports
						</span> </a>
						<span class="kt-widget17__desc">
							
						</span>  
					</div>

					<div class="kt-widget17__item">
						<a href="credit_transfer.php"><span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--danger">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <circle id="Oval-47" fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
        <rect id="Rectangle-162" fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" id="Combined-Shape" fill="#000000"/>
    </g>
</svg>						</span>  </a>
						<a href="credit_transfer.php"><span class="kt-widget17__subtitle">
							Transfer Balance
						</span> </a>
						<span class="kt-widget17__desc">
							
						</span>  
					</div>

					<div class="kt-widget17__item">
						<a href="billing_entity_trunk.php"><span class="kt-widget17__icon">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--band">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"></rect>
        <path d="M12.7037037,14 L15.6666667,10 L13.4444444,10 L13.4444444,6 L9,12 L11.2222222,12 L11.2222222,14 L6,14 C5.44771525,14 5,13.5522847 5,13 L5,3 C5,2.44771525 5.44771525,2 6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,13 C19,13.5522847 18.5522847,14 18,14 L12.7037037,14 Z" id="Combined-Shape" fill="#000000" opacity="0.3"></path>
        <path d="M9.80428954,10.9142091 L9,12 L11.2222222,12 L11.2222222,16 L15.6666667,10 L15.4615385,10 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 L9.80428954,10.9142091 Z" id="Combined-Shape" fill="#000000"></path>
    </g>
</svg>						</span>  </a>
						<a href="billing_entity_trunk.php"><span class="kt-widget17__subtitle">
							Active Trunk
						</span> </a>
						<span class="kt-widget17__desc">
							
						</span>  
					</div>

					<div class="kt-widget17__item">
						<a href="CC_ticket.php"><span class="kt-widget17__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" id="Combined-Shape" fill="#000000"/>
        <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
    </g>
</svg>						</span>  </a>
						<a href="CC_ticket.php"><span class="kt-widget17__subtitle">
							Support
						</span> </a>
						<span class="kt-widget17__desc">
							
						</span>  
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<!--end:: Widgets/Activity-->	</div>	
	</div>
</div>
<!--End::Row-->
<!--End::Dashboard 1-->	<!--</div>-->
<!-- end:: Content -->				</div>

<!--// for deshboard Icon-->
<div class="container-fluid">  
 <!-- // -->   
 <script type="text/javascript">
 $(document).ready(function(){
  setInterval(function() {
      $.ajax({
                type: "GET",
                url: "live-call-total_data_live.php",
                data:"section=6&totalcall=totalcall",
                success: function(response) {
                   //alert(response);
                    $("#mycall").html(response);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }                    
        });
  
   }, 1000);
 });
</script>             
<script type="text/javascript">
$(function () {
    // we use an inline data source in the example, usually data would
    // be fetched from a server
    var data = [], totalPoints = 300;
    calls = 0;
    function getRandomData() {
        if (data.length > 0)
            data = data.slice(1);
            
            $.ajax({
                type: "GET",
               url: "live-call-total_data_live.php",
                data:"section=6&totalcall=all",
                success: function(response) {
                   
                    calls = parseInt(response);
                    //alert(calls);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    //calls = 0;
                    //alert("failed"+errorThrown+textStatus);
                   
                }

        });
        //alert(calls);
        // do a random walk
        while (data.length < totalPoints) {
            var prev = data.length > 0 ? data[data.length - 1] : 5;
            //var y = prev + Math.random() * 8 - 5;
            var y =  (calls);
            $('#mydiv').html(y+" : "+calls);
            if (y < 0)
                y = 0;
            if (y > 100)
                y = 100;
            data.push(y);
        }

        // zip the generated y values with the x values
       var res = [];
        for (var i = 0; i < data.length; ++i)
            res.push([i, data[i]])
        return res;
    }

    // setup control widget
    var updateInterval = 30;
    $("#updateInterval").val(updateInterval).change(function () {
        var v = $(this).val();
        if (v && !isNaN(+v)) {
            updateInterval = +v;
            if (updateInterval < 1)
                updateInterval = 1;
            if (updateInterval > 2000)
                updateInterval = 2000;
            $(this).val("" + updateInterval);
        }
    });

   

    function update() {
        
         // setup plot
          numbery = 5;
         if(calls >1)
         {
             numbery = calls*5;
         }
    var options = {
        series: { shadowSize: 3 }, // drawing is faster without shadows
        yaxis: { min: 0, max: (numbery) },
        xaxis: { show: true }
    };
    
    var plot = $.plot($("#placeholder2"), [ getRandomData() ], options);
        plot.setData([ getRandomData() ]);
        // since the axes don't change, we don't need to call plot.setupGrid()
        plot.draw();
        
        setTimeout(update, updateInterval);
    }

    update();
});
</script> 


          
<div class="kt-portlet kt-portlet--fit kt-portlet--head-noborder kt-portlet--height-fluid-half">
      <div class="kt-portlet__head kt-portlet__space-x">
        <div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Realtime Active Calls
			</h3>
		</div>
		<div class="kt-portlet__body kt-portlet__body--fluid">
		<div class="kt-widget20">
			<div class="kt-widget20__chart" style="height:130px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
			<canvas id="kt_chart_bandwidth1" width="515" height="130" class="chartjs-render-monitor" style="display: block; width: 515px; height: 130px;"></canvas>
			</div>
			
            
            <p>Time between updates:
              <input id="updateInterval" type="text" value="" style="text-align: right; width:5em">
              milliseconds</p>
        </div>
        </div>
      </div>
    </div> 
 


    
           
<?php
$smarty->display('footer.tpl');