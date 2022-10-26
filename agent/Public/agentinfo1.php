<?php

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$QUERY = "SELECT  credit, currency, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, id, com_balance FROM cc_agent WHERE login = '".$_SESSION["pr_login"]."' AND passwd = '".$_SESSION["pr_password"]."'";
$table_remittance = new Table("cc_remittance_request",'*');
$remittance_clause = "id_agent = ".$_SESSION['agent_id']." AND status = 0";

$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
    $numrow = $resmax -> RecordCount();

if ($numrow == 0) exit();
$agent_info =$resmax -> fetchRow();

$currencies_list = get_currencies();

$two_currency = false;
if (!isset($currencies_list[strtoupper($agent_info [1])][2]) || !is_numeric($currencies_list[strtoupper($agent_info [1])][2])) {
    $mycur = 1;
} else {
    $mycur = $currencies_list[strtoupper($agent_info [1])][2];
    $display_currency =strtoupper($agent_info [1]);
    if(strtoupper($agent_info [1])!=strtoupper(BASE_CURRENCY))$two_currency=true;
}
$credit_cur = $agent_info[0] / $mycur;
$credit_cur = round($credit_cur,3);

$result_remittance = $table_remittance -> Get_list($DBHandle_max,$remittance_clause);
if (is_array($result_remittance) && sizeof($result_remittance)>=1 ) {
    $remittance_in_progress=true;
    $remittance_value = $result_remittance[0]['amount'];
} else {
    $remittance_in_progress=false;
}
$remittance_value_cur = $remittance_value/$mycur;
$commision_bal_cur  =  $agent_info[13] / $mycur;
$commision_bal_cur = round($commision_bal_cur,3);
$smarty->display( 'main.tpl');
?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                My Profile                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Home                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            My Profile                        </a>
                </div>
        </div>
    </div>
</div>

<!-- end:: Subheader -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
   	<div class="col-lg-10" style="margin: 0 auto;">	
		<!--begin::Portlet-->
		<div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile" id="kt_page_portlet">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Agent Information <small>try to scroll the page</small></h3>
				</div>
				<div class="kt-portlet__head-toolbar" style="padding-left:207px">
					<a href="#" class="btn btn-clean kt-margin-r-10">
						<i class="la la-arrow-left"></i>
						<span class="kt-hidden-mobile">Back</span>
					</a>
					<div class="btn-group">
						<button type="button" class="btn btn-brand">
							<i class="la la-check"></i> 
							<span class="kt-hidden-mobile">Save</span>
						</button>
						<button type="button" class="btn btn-brand dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						</button>
						<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(123px, 38px, 0px);">
							<ul class="kt-nav">
								<li class="kt-nav__item">
									<a href="#" class="kt-nav__link">
										<i class="kt-nav__link-icon flaticon2-reload"></i>
										<span class="kt-nav__link-text">Save &amp; continue</span>
									</a>
								</li>
								<li class="kt-nav__item">
									<a href="#" class="kt-nav__link">
										<i class="kt-nav__link-icon flaticon2-power"></i>
										<span class="kt-nav__link-text">Save &amp; exit</span>
									</a>
								</li>
								<li class="kt-nav__item">
									<a href="#" class="kt-nav__link">
										<i class="kt-nav__link-icon flaticon2-edit-interface-symbol-of-pencil-tool"></i>
										<span class="kt-nav__link-text">Save &amp; edit</span>
									</a>
								</li>
								<li class="kt-nav__item">
									<a href="#" class="kt-nav__link">
										<i class="kt-nav__link-icon flaticon2-add-1"></i>
										<span class="kt-nav__link-text">Save &amp; add new</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<form class="kt-form" id="kt_form">
					<div class="row">
						<div class="col-xl-8">
							<div class="kt-section kt-section--first">
									<div class="form-group row">
										<label class="col-3">First Name</label>
										<div class="col-9">
											<input class="form-control" type="text" placeholder="First name">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3">Last Name</label>
										<div class="col-9">
											<input class="form-control" type="text" placeholder="Last name">
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-3">Email</label>
										<div class="col-9">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
												<input type="text" class="form-control" placeholder="example@gmail.com" aria-describedby="basic-addon1">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3">Phone</label>
										<div class="col-9">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
												<input type="text" class="form-control" placeholder="Enter phone no." aria-describedby="basic-addon1">
											</div>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-3">Address</label>
										<div class="col-9">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text"><i class="la la-map-marker"></i></span></div>
												<input type="text" class="form-control" placeholder="Enter your address" aria-describedby="basic-addon1">
											</div>
										</div>
									</div>
							</div>
							<div class="form-group row">
								<label class="col-3">Country</label>
									<div class="col-9">
											<select class="form-control">
												<option value="AF">Afghanistan</option>
												<option value="AX">Åland Islands</option>
												<option value="AL">Albania</option>
												<option value="DZ">Algeria</option>
												<option value="AS">American Samoa</option>
												<option value="AD">Andorra</option>
												<option value="AO">Angola</option>
												<option value="AI">Anguilla</option>
												<option value="AQ">Antarctica</option>
												<option value="AG">Antigua and Barbuda</option>
												<option value="AR">Argentina</option>
												<option value="AM">Armenia</option>
												<option value="AW">Aruba</option>
												<option value="AU">Australia</option>
												<option value="AT">Austria</option>
												<option value="AZ">Azerbaijan</option>
												<option value="BS">Bahamas</option>
												<option value="BH">Bahrain</option>
												<option value="BD">Bangladesh</option>
												<option value="BB">Barbados</option>
												<option value="BY">Belarus</option>
												<option value="BE">Belgium</option>
												<option value="BZ">Belize</option>
												<option value="BJ">Benin</option>
												<option value="BM">Bermuda</option>
												<option value="BT">Bhutan</option>
												<option value="BO">Bolivia, Plurinational State of</option>
												<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
												<option value="BA">Bosnia and Herzegovina</option>
												<option value="BW">Botswana</option>
												<option value="BV">Bouvet Island</option>
												<option value="BR">Brazil</option>
												<option value="IO">British Indian Ocean Territory</option>
												<option value="BN">Brunei Darussalam</option>
												<option value="BG">Bulgaria</option>
												<option value="BF">Burkina Faso</option>
												<option value="BI">Burundi</option>
												<option value="KH">Cambodia</option>
												<option value="CM">Cameroon</option>
												<option value="CA">Canada</option>
												<option value="CV">Cape Verde</option>
												<option value="KY">Cayman Islands</option>
												<option value="CF">Central African Republic</option>
												<option value="TD">Chad</option>
												<option value="CL">Chile</option>
												<option value="CN">China</option>
												<option value="CX">Christmas Island</option>
												<option value="CC">Cocos (Keeling) Islands</option>
												<option value="CO">Colombia</option>
												<option value="KM">Comoros</option>
												<option value="CG">Congo</option>
												<option value="CD">Congo, the Democratic Republic of the</option>
												<option value="CK">Cook Islands</option>
												<option value="CR">Costa Rica</option>
												<option value="CI">Côte d'Ivoire</option>
												<option value="HR">Croatia</option>
												<option value="CU">Cuba</option>
												<option value="CW">Curaçao</option>
												<option value="CY">Cyprus</option>
												<option value="CZ">Czech Republic</option>
												<option value="DK">Denmark</option>
												<option value="DJ">Djibouti</option>
												<option value="DM">Dominica</option>
												<option value="DO">Dominican Republic</option>
												<option value="EC">Ecuador</option>
												<option value="EG">Egypt</option>
												<option value="SV">El Salvador</option>
												<option value="GQ">Equatorial Guinea</option>
												<option value="ER">Eritrea</option>
												<option value="EE">Estonia</option>
												<option value="ET">Ethiopia</option>
												<option value="FK">Falkland Islands (Malvinas)</option>
												<option value="FO">Faroe Islands</option>
												<option value="FJ">Fiji</option>
												<option value="FI">Finland</option>
												<option value="FR">France</option>
												<option value="GF">French Guiana</option>
												<option value="PF">French Polynesia</option>
												<option value="TF">French Southern Territories</option>
												<option value="GA">Gabon</option>
												<option value="GM">Gambia</option>
												<option value="GE">Georgia</option>
												<option value="DE">Germany</option>
												<option value="GH">Ghana</option>
												<option value="GI">Gibraltar</option>
												<option value="GR">Greece</option>
												<option value="GL">Greenland</option>
												<option value="GD">Grenada</option>
												<option value="GP">Guadeloupe</option>
												<option value="GU">Guam</option>
												<option value="GT">Guatemala</option>
												<option value="GG">Guernsey</option>
												<option value="GN">Guinea</option>
												<option value="GW">Guinea-Bissau</option>
												<option value="GY">Guyana</option>
												<option value="HT">Haiti</option>
												<option value="HM">Heard Island and McDonald Islands</option>
												<option value="VA">Holy See (Vatican City State)</option>
												<option value="HN">Honduras</option>
												<option value="HK">Hong Kong</option>
												<option value="HU">Hungary</option>
												<option value="IS">Iceland</option>
												<option value="IN"  selected="">India</option>
												<option value="ID">Indonesia</option>
												<option value="IR">Iran, Islamic Republic of</option>
												<option value="IQ">Iraq</option>
												<option value="IE">Ireland</option>
												<option value="IM">Isle of Man</option>
												<option value="IL">Israel</option>
												<option value="IT">Italy</option>
												<option value="JM">Jamaica</option>
												<option value="JP">Japan</option>
												<option value="JE">Jersey</option>
												<option value="JO">Jordan</option>
												<option value="KZ">Kazakhstan</option>
												<option value="KE">Kenya</option>
												<option value="KI">Kiribati</option>
												<option value="KP">Korea, Democratic People's Republic of</option>
												<option value="KR">Korea, Republic of</option>
												<option value="KW">Kuwait</option>
												<option value="KG">Kyrgyzstan</option>
												<option value="LA">Lao People's Democratic Republic</option>
												<option value="LV">Latvia</option>
												<option value="LB">Lebanon</option>
												<option value="LS">Lesotho</option>
												<option value="LR">Liberia</option>
												<option value="LY">Libya</option>
												<option value="LI">Liechtenstein</option>
												<option value="LT">Lithuania</option>
												<option value="LU">Luxembourg</option>
												<option value="MO">Macao</option>
												<option value="MK">Macedonia, the former Yugoslav Republic of</option>
												<option value="MG">Madagascar</option>
												<option value="MW">Malawi</option>
												<option value="MY">Malaysia</option>
												<option value="MV">Maldives</option>
												<option value="ML">Mali</option>
												<option value="MT">Malta</option>
												<option value="MH">Marshall Islands</option>
												<option value="MQ">Martinique</option>
												<option value="MR">Mauritania</option>
												<option value="MU">Mauritius</option>
												<option value="YT">Mayotte</option>
												<option value="MX">Mexico</option>
												<option value="FM">Micronesia, Federated States of</option>
												<option value="MD">Moldova, Republic of</option>
												<option value="MC">Monaco</option>
												<option value="MN">Mongolia</option>
												<option value="ME">Montenegro</option>
												<option value="MS">Montserrat</option>
												<option value="MA">Morocco</option>
												<option value="MZ">Mozambique</option>
												<option value="MM">Myanmar</option>
												<option value="NA">Namibia</option>
												<option value="NR">Nauru</option>
												<option value="NP">Nepal</option>
												<option value="NL">Netherlands</option>
												<option value="NC">New Caledonia</option>
												<option value="NZ">New Zealand</option>
												<option value="NI">Nicaragua</option>
												<option value="NE">Niger</option>
												<option value="NG">Nigeria</option>
												<option value="NU">Niue</option>
												<option value="NF">Norfolk Island</option>
												<option value="MP">Northern Mariana Islands</option>
												<option value="NO">Norway</option>
												<option value="OM">Oman</option>
												<option value="PK">Pakistan</option>
												<option value="PW">Palau</option>
												<option value="PS">Palestinian Territory, Occupied</option>
												<option value="PA">Panama</option>
												<option value="PG">Papua New Guinea</option>
												<option value="PY">Paraguay</option>
												<option value="PE">Peru</option>
												<option value="PH">Philippines</option>
												<option value="PN">Pitcairn</option>
												<option value="PL">Poland</option>
												<option value="PT">Portugal</option>
												<option value="PR">Puerto Rico</option>
												<option value="QA">Qatar</option>
												<option value="RE">Réunion</option>
												<option value="RO">Romania</option>
												<option value="RU">Russian Federation</option>
												<option value="RW">Rwanda</option>
												<option value="BL">Saint Barthélemy</option>
												<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
												<option value="KN">Saint Kitts and Nevis</option>
												<option value="LC">Saint Lucia</option>
												<option value="MF">Saint Martin (French part)</option>
												<option value="PM">Saint Pierre and Miquelon</option>
												<option value="VC">Saint Vincent and the Grenadines</option>
												<option value="WS">Samoa</option>
												<option value="SM">San Marino</option>
												<option value="ST">Sao Tome and Principe</option>
												<option value="SA">Saudi Arabia</option>
												<option value="SN">Senegal</option>
												<option value="RS">Serbia</option>
												<option value="SC">Seychelles</option>
												<option value="SL">Sierra Leone</option>
												<option value="SG">Singapore</option>
												<option value="SX">Sint Maarten (Dutch part)</option>
												<option value="SK">Slovakia</option>
												<option value="SI">Slovenia</option>
												<option value="SB">Solomon Islands</option>
												<option value="SO">Somalia</option>
												<option value="ZA">South Africa</option>
												<option value="GS">South Georgia and the South Sandwich Islands</option>
												<option value="SS">South Sudan</option>
												<option value="ES">Spain</option>
												<option value="LK">Sri Lanka</option>
												<option value="SD">Sudan</option>
												<option value="SR">Suriname</option>
												<option value="SJ">Svalbard and Jan Mayen</option>
												<option value="SZ">Swaziland</option>
												<option value="SE">Sweden</option>
												<option value="CH">Switzerland</option>
												<option value="SY">Syrian Arab Republic</option>
												<option value="TW">Taiwan, Province of China</option>
												<option value="TJ">Tajikistan</option>
												<option value="TZ">Tanzania, United Republic of</option>
												<option value="TH">Thailand</option>
												<option value="TL">Timor-Leste</option>
												<option value="TG">Togo</option>
												<option value="TK">Tokelau</option>
												<option value="TO">Tonga</option>
												<option value="TT">Trinidad and Tobago</option>
												<option value="TN">Tunisia</option>
												<option value="TR">Turkey</option>
												<option value="TM">Turkmenistan</option>
												<option value="TC">Turks and Caicos Islands</option>
												<option value="TV">Tuvalu</option>
												<option value="UG">Uganda</option>
												<option value="UA">Ukraine</option>
												<option value="AE">United Arab Emirates</option>
												<option value="GB">United Kingdom</option>
												<option value="US">United States</option>
												<option value="UM">United States Minor Outlying Islands</option>
												<option value="UY">Uruguay</option>
												<option value="UZ">Uzbekistan</option>
												<option value="VU">Vanuatu</option>
												<option value="VE">Venezuela, Bolivarian Republic of</option>
												<option value="VN">Viet Nam</option>
												<option value="VG">Virgin Islands, British</option>
												<option value="VI">Virgin Islands, U.S.</option>
												<option value="WF">Wallis and Futuna</option>
												<option value="EH">Western Sahara</option>
												<option value="YE">Yemen</option>
												<option value="ZM">Zambia</option>
												<option value="ZW">Zimbabwe</option>
											</select>
									</div>
							</div>
									<div class="form-group row">
										<label class="col-3">State</label>
										<div class="col-9">
											<input class="form-control" type="text" placeholder="Enter State">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3">City</label>
										<div class="col-9">
											<input class="form-control" type="text" placeholder="Enter City">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3">ZipCode</label>
										<div class="col-9">
											<input class="form-control" type="text" placeholder="Country Zipcode">
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-3">Fax</label>
										<div class="col-9">
											<input class="form-control" type="text" placeholder="Enter Fax no">
										</div>
									</div>
								</div>
						</div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end :: Portlet -->
    </div>
</div>

<!--<div>
<table  class="tablebackgroundblue"  align="center">
<tr>
    <td><img src="<?php echo KICON_PATH ?>/personal.gif" align="left" class="kikipic"/></td>
    <td width="50%"><font class="fontstyle_002">
    <?php echo gettext("LAST NAME");?> :</font>  <font class="fontstyle_007"><?php echo $agent_info[2]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("FIRST NAME");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[3]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("EMAIL");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[10]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("PHONE");?> :</font><font class="fontstyle_007"> <?php echo $agent_info[9]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("FAX");?> :</font><font class="fontstyle_007"> <?php echo $agent_info[11]; ?></font>
    </td>
    <td width="50%">
    <font class="fontstyle_002"><?php echo gettext("ADDRESS");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[4]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("ZIP CODE");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[8]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("CITY");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[5]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("STATE");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[6]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("COUNTRY");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[7]; ?></font>
    </td>
</tr>
<tr>
    <td></td>
    <td>
         &nbsp;
    </td>
    <td align="right">
        <?php if ($A2B->config["webagentui"]['personalinfo']) { ?>
        <a href="A2B_entity_agent.php?atmenu=password&form_action=ask-edit&stitle=Personal+Information"><span class="cssbutton"><font color="red"><?php echo gettext("EDIT PERSONAL INFORMATION");?></font></span></a>
        <?php } ?>
    </td>
</tr>
</table>

<br>
<table style="width:90%;margin:0 auto;" align="center">
<tr>
    <td align="center">
        <table width="80%" align="center" class="tablebackgroundcamel">
        <tr>
            <td rowspan="2"><img src="<?php echo KICON_PATH ?>/gnome-finance.gif" class="kikipic"/></td>
            <td width="50%">
            <br><font class="fontstyle_002"><?php echo gettext("AGENT ID");?> :</font><font class="fontstyle_007"> <?php echo $agent_info[12]; ?></font>
            <br></br>
            </td>
            <td width="50%">
            <br/><font class="fontstyle_002"><?php echo gettext("BALANCE REMAINING");?> :</font><font class="fontstyle_007"> <?php echo $credit_cur.' '.$agent_info[1]; ?> </font>

            </td>
            <td valign="bottom" align="right" rowspan="2"  ><img src="<?php echo KICON_PATH ?>/help_index.gif" class="kikipic"></td>
        </tr>
        <tr>
            <td>
                <?php  if ($remittance_in_progress) {?>
                    <font class="fontstyle_002"><?php echo gettext("REMITTANCE IN PROGRESS");?> :</font><font class="fontstyle_007"> <?php echo $remittance_value_cur.' '.$agent_info[1]; ?> </font>
                <?php } else {?>
                    &nbsp;
                <?php }?>
            </td>
            <td width="50%">
                <font class="fontstyle_002"><?php echo gettext("COMMISSION ACCRUED");?> :</font><font class="fontstyle_007"> <?php echo $commision_bal_cur.' '.$agent_info[1]; ?> </font>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<?php if ($A2B->config["webagentui"]['remittance_request'] && !$remittance_in_progress && $commision_bal_cur>0) { ?>
<div style="width:70%;margin:0 auto;text-align:center;">
    <a href="A2B_agent_remittance_req.php"><span class="form_input_button"><?php echo gettext("REMITTANCE REQUEST");?></span></a>
</div>
<?php
}
if ($A2B->config["epayment_method"]['enable']) { ?>

<br>

<?php
    echo $PAYMENT_METHOD;
?>

<table style="width:70%;margin:0 auto;" cellspacing="0" align="center" >
    <tr background="<?php echo Images_Path; ?>/background_cells.gif" >
        <TD  valign="top" align="right" class="tableBodyRight"   >
            <font size="2"><?php echo gettext("Click below to buy credit : ");?> </font>
        </TD>
        <td class="tableBodyRight" >
            <?php
            $arr_purchase_amount = preg_split("/:/", EPAYMENT_PURCHASE_AMOUNT);
            if (!is_array($arr_purchase_amount)) {
                $to_echo = 10;
            } else {
                if ($two_currency) {
                $purchase_amounts_convert= array();
                for ($i=0;$i<count($arr_purchase_amount);$i++) {
                    $purchase_amounts_convert[$i]=round($arr_purchase_amount[$i]/$mycur,2);
                }
                $to_echo = join(" - ", $purchase_amounts_convert);

                echo $to_echo;
            ?>
            <font size="2">
            <?php echo $display_currency; ?> </font>
            <br/>
            <?php } ?>
            <?php echo join(" - ", $arr_purchase_amount); ?>
            <font size="2"><?php echo strtoupper(BASE_CURRENCY);?> </font>
            <?php } ?>

        </TD>
    </tr>

    <tr>
        <td align="center" colspan="2" class="tableBodyRight" >
            <form action="checkout_payment.php" method="post">

                <input type="submit" class="form_input_button" value="BUY NOW">
            </form>
    </tr>
</table>

<?php

} else {
    echo '<br></br><br></br>';
}
?>
</div>-->
<?php

$smarty->display( 'footer.tpl');
