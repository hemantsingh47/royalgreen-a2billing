<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id'));

if (empty($id)) {
    header("Location: billing_entity_card.php?atmenu=card&stitle=Customers_Card&section=1");
}

$DBHandle  = DbConnect();

$card_table = new Table('cc_card','*');
$card_clause = "id = ".$id;
$card_result = $card_table -> Get_list($DBHandle, $card_clause, 0);
$card = $card_result[0];

if (empty($card)) {
    header("Location: billing_entity_card.php?atmenu=card&stitle=Customers_Card&section=1");
}

// #### HEADER SECTION
$smarty->display('main.tpl');

echo $CC_help_info_customer;



?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                User                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            List Customer                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_card_info.php" class="kt-subheader__breadcrumbs-link">
                            Customer Information                        </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>

<!-- end:: Subheader -->					
					<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<div class="row">
    <div class="kt-portlet">
       <div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h1 class="kt-portlet__head-title">
					<?php echo gettext("Customer's information");?>
				</h1>
			</div>
		</div>
		
		<div class="kt-portlet__body">
			<div class="row" style="    padding: 10px 10px 10px; margin-top: 1px;margin-bottom: 0px;">
			<div>
				<?php
					echo Display_Login_Button ($DBHandle, $id);
				?>
			</div>
			<a href="billing_entity_card.php?section=1">
				<div class="btn btn-primary  btn-small btn-wave-light">
				<?php echo gettext("CUSTOMERS LIST"); ?>
				</div>
			</a>
			</div>
		</div>
	



<div class="row">
    <div class="col-lg-12">
	
	 <div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
		
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("ACCOUNT INFO") ?>
					</h3>
				</div>
			</div>
            <div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<table class="table table-bordered table-hover">
						  	 
						  	<tbody>
						    	<tr>
							      	 
							      	<td style="font-weight:600; width:50%;"><?php echo gettext("STATUS") ?> :</td>
							      	<td>
										<?php
											$list_typepaid = Constants::getPaidTypeList();
											echo $list_typepaid[$card['typepaid']][0];
										?>
									</td>
						    	</tr>
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("ACCOUNT NUMBER") ?> :</td>
							      	<td> <?php echo $card['username']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("SERIAL NUMBER") ?> :</td>
							      	<td>
										<?php echo str_pad($card['serial'], $A2B->config["webui"]['card_serial_length'] , "0", STR_PAD_LEFT); ?>
									</td>
						    	</tr>
               
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("WEB ALIAS") ?></td>
							      	<td><?php echo $card['useralias']?></td>
						    	</tr>
               
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("WEB PASSWORD") ?></td>
							      	<td><?php echo $card['uipass']?></td>
						    	</tr>
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("LANGUAGE") ?> :</td>
							      	<td><?php echo $card['language']?></td>
						    	</tr>
			   
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("STATUS") ?> :</td>
							      	<td>
										<?php
											$list_status = Constants::getCardStatus_List();
											echo $list_status[$card['status']][0];
										?>
									</td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("CREATION DATE") ?> :</td>
							      	<td><?php echo $card['creationdate']?></td>
						    	</tr>
								
								<tr>
							      	<td style="font-weight:600"><?php echo gettext("EXPIRATION DATE") ?> :</td>
							      	<td><?php echo $card['expirationdate']?></td>
						    	</tr>
								
								<tr>
							      	<td style="font-weight:600"><?php echo gettext("FIRST USE DATE") ?> :</td>
							      	<td> <?php echo $card['firstusedate']?></td>
						    	</tr>
				   
								<tr>
							      	<td style="font-weight:600"><?php echo gettext("LAST USE DATE") ?> :</td>
							      	<td> <?php echo $card['lastuse']?></td>
						    	</tr>
                   
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("CALLBACK") ?> :</td>
							      	<td> <?php echo $card['callback']?></td>
						    	</tr>
                   
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("LOCK") ?> :</td>
							      	<td><?php echo ($card['block'] ? gettext("LOCK") : gettext("UNLOCK")) ?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("LOCK PIN") ?> :</td>
							      	<td><?php echo $card['lock_pin']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("LOCK DATE") ?> :</td>
							      	<td><?php echo $card['lock_date']?></td>
						    	</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->	 
    </div>
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("CUSTOMER INFO") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<table class="table table-bordered table-hover">
						  	 
						  	<tbody>
						    	<tr>
							      	 
							      	<td style="font-weight:600; width:50%;"><?php echo gettext("LAST NAME") ?> :</td>
							      	<td><?php echo $card['lastname']?></td>
						    	</tr>
                <tr>
							      	 
							      	<td style="font-weight:600">  <?php echo gettext("FIRST NAME") ?> :</td>
							      	<td><?php echo $card['firstname']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("ADDRESS") ?> :</td>
							      	<td><?php echo $card['address']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("ZIP CODE") ?> :</td>
							      	<td><?php echo $card['zipcode']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("CITY") ?> :</td>
							      	<td><?php echo $card['city']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("STATE") ?> :</td>
							      	<td><?php echo $card['state']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("COUNTRY") ?> :</td>
							      	<td><?php echo $card['country']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("EMAIL") ?> :</td>
							      	<td><?php echo $card['email']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600">  <?php echo gettext("PHONE") ?> :</td>
							      	<td><?php echo $card['phone']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("FAX") ?> :</td>
							      	<td><?php echo $card['fax']?></td>
						    	</tr>
						  	</tbody>
						</table>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	<div class="col-xs-12">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
			<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						<?php echo gettext("SIP-DETAILS") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<table class="table table-bordered table-hover">
						  	<?php
								$sip_buddies_table = new Table('cc_sip_buddies','*');
								$sip_buddies_clause = "id_cc_card  = ".$id;
								$sip_buddies_result = $sip_buddies_table -> Get_list($DBHandle, $sip_buddies_clause, 0);
								$sip_buddies = $sip_buddies_result[0];
								if (sizeof($sip_buddies_result)>0 && $sip_buddies_result[0]!=null) 
								{
									
							?>
						
		
							<thead>
								<th style="text-align:left; width:50%;"><?php echo gettext("USERNAME"); ?>
								</th>
								<th style="text-align:left; width:50%;"> <?php echo gettext("SECRET"); ?>
								</th>
							</thead>
		
						  	<tbody>
							<?php
								$i=0;
								foreach ($sip_buddies_result as $sip_buddies) 
								{
									if($i%2==0) $bg="#fcfbfb";
									else  $bg="#f2f2ee";
								?>
						    	<tr role="row" class="odd" bgcolor="<?php echo $bg; ?>">
							      	 
							      	<td style="font-weight:600; text-align:left; width:50%;" class="sorting_1">
										<?php echo $sip_buddies['username']; ?>
									</td>
							      	<td style="font-weight:600; text-align:left; width:50%;" class="sorting_1">
										<?php echo $sip_buddies['secret']; ?>
									</td>
								</tr>
								<?php
									$i++;
									}
								?>
							</tbody>
						</table>
						<?php
          }
        ?>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	
	
	</div>
	
<div class="col-lg-12">
     
		<div class="col-xs-12">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
					 <?php echo gettext("ACCOUNT STATUS") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<table class="table table-bordered table-hover">
						  	 
						  	<tbody>
						    	<tr>
							      	 
							      	<td style="font-weight:600; width:50%;"><?php echo gettext("BALANCE") ?> :</td>
							      	<td style="width:50%;"><?php echo $card['credit']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("CURRENCY") ?></td>
							      	<td><?php echo $card['currency']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("CREDIT LIMIT") ?></td>
							      	<td><?php echo $card['creditlimit']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("AUTOREFILL") ?> :</td>
							      	<td><?php echo $card['autorefill']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"> <?php echo gettext("INVOICE DAY") ?> :</td>
							      	<td><?php echo $card['invoiceday']?></td>
						    	</tr>
						  	</tbody>
						</table>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("COMPANY INFO") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<table class="table table-bordered table-hover">
						  	 
						  	<tbody>
						    	<tr>
							      	 
							      	<td style="font-weight:600; width:50%;"><?php echo gettext("COMPANY NAME") ?> :</td>
							      	<td><?php echo $card['company_name']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("COMPANY WEBSITE") ?> :</td>
							      	<td><?php echo $card['company_website']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("VAT REGISTRATION NUMBER") ?> :</td>
							      	<td><?php echo $card['vat_rn']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("TRAFFIC PER MONTH") ?> :</td>
							      	<td><?php echo $card['traffic']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("TARGET TRAFIC") ?> :</td>
							      	<td><?php echo $card['traffic_target']?></td>
						    	</tr>
								
								 
						  	</tbody>
						</table>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("CALLER-ID LIST ") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<table class="table table-bordered table-hover">
						  	 <?php
        $callerid_table = new Table('cc_callerid','*');
        $callerid_clause = "id_cc_card  = ".$id;
        $callerid_result = $callerid_table -> Get_list($DBHandle, $callerid_clause, 0);
        $callerid = $callerid_result[0];
        if (sizeof($callerid_result)>0 && $callerid_result[0]!=null) {
        ?>
		
		
		<thead>
		<th style="text-align:left; width:50%;"><?php echo gettext("CID"); ?>
		</th>
		<th style="text-align:left; width:50%;"><?php echo gettext("ACTIVATED"); ?>
		</th>
		 
		</thead>
		
						  	<tbody>
							<?php
            $i=0;
            foreach ($callerid_result as $callerid) {
                if($i%2==0) $bg="#fcfbfb";
                else  $bg="#f2f2ee";
       ?> 
						    	<tr bgcolor="<?php echo $bg; ?>">
							      	 
							      	<td style="font-weight:600; text-align:left; width:50%;"><?php echo $callerid['cid']; ?></td>
							      	<td style="font-weight:600; text-align:left; width:50%;"><?php echo ($callerid['activated']=="t"?"Active":"Inactive"); ?></td>
									 
						    	</tr>
								
								 <?php
        $i++;
        }
        ?>
								
								 
						  	</tbody>
						</table>
						<?php
          }
        ?>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("SPEED-DIAL LIST ") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<table class="table table-bordered table-hover">
						  	 <?php
        $speeddial_table = new Table('cc_speeddial','*');
        $speeddial_clause = "id_cc_card  = ".$id;
        $speeddial_result = $speeddial_table -> Get_list($DBHandle, $speeddial_clause, 0);
        $speeddial = $speeddial_result[0];
        if (sizeof($speeddial_result)>0 && $speeddial_result[0]!=null) {
        ?>
		
		
		<thead>
		<th style="text-align:left; width:33%;"><?php echo gettext("PHONE"); ?>
		</th>
		<th style="text-align:left; width:34%;"><?php echo gettext("NAME"); ?>
		</th>
		<th style="text-align:left; width:33%;"> <?php echo gettext("SPEEDDIAL"); ?>
		</th>
		 
		</thead>
		
						  	<tbody>
							<?php
            $i=0;
            foreach ($callerid_result as $callerid) {
                if($i%2==0) $bg="#fcfbfb";
                else  $bg="#f2f2ee";
       ?> 
						    	<tr bgcolor="<?php echo $bg; ?>">
							      	 
							      	<td style="font-weight:600; text-align:left; width:33%;"><?php echo $speeddial['phone']; ?></td>
							      	<td style="font-weight:600; text-align:left; width:34%;"> <?php echo $speeddial['name']; ?></td>
									
									<td style="font-weight:600; text-align:left; width:33%;"><?php echo $speeddial['speeddial']; ?></td>
									 
						    	</tr>
								
								 <?php
        $i++;
        }
        ?>
								
								 
						  	</tbody>
						</table>
						<?php
          }
        ?>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("IAX-BUDDIES") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<table class="table table-bordered table-hover">
						  	 <?php
        $iax_buddies_table = new Table('cc_iax_buddies','*');
        $iax_buddies_clause = "id_cc_card  = ".$id;
        $iax_buddies_result = $iax_buddies_table -> Get_list($DBHandle, $iax_buddies_clause, 0);
        $iax_buddies = $iax_buddies_result[0];
        if (sizeof($iax_buddies_result)>0 && $iax_buddies_result[0]!=null) {
        ?>
		
		
		<thead>
		<th style="text-align:left; width:50%;"><?php echo gettext("USERNAME"); ?>
		</th>
		<th style="text-align:left; width:50%;"> <?php echo gettext("SECRET"); ?>
		</th>
		 
		 
		</thead>
		
						  	<tbody>
							<?php
                $i=0;
                foreach ($iax_buddies_result as $iax_buddies) {
                    if($i%2==0) $bg="#fcfbfb";
                    else  $bg="#f2f2ee";
               ?>
						    	<tr bgcolor="<?php echo $bg; ?>">
							      	 
							      	<td style="font-weight:600; text-align:left; width:50%;"><?php echo $iax_buddies['username']; ?></td>
							      	<td style="font-weight:600; text-align:left; width:50%;"><?php echo $iax_buddies['secret']; ?></td>
									
									 
									 
						    	</tr>
								
								 <?php
        $i++;
        }
        ?>
								
								 
						  	</tbody>
						</table>
						<?php
          }
        ?>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	
	
	
	
	</div>
	
</div>	

<div class="row">
    <div class="col-lg-12">
	
	 
	
	
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet" >
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("Recent Payments"); ?>	
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<?php

$payment_table = new Table('cc_logpayment','*');
$payment_clause = "card_id = ".$id;
$payment_result = $payment_table -> Get_list($DBHandle, $payment_clause, 'date', 'DESC', NULL, NULL, 10, 0);
if (sizeof($payment_result)>0 && $payment_result[0]!=null) {
?>
						
						<table class="table table-bordered table-hover">
						  	
		
		
		<thead>
		<th style="text-align:left; width:20%;"><?php echo gettext("ID"); ?>
		</th>
		<th style="text-align:left; width:20%;"> <?php echo gettext("PAYMENT DATE"); ?>
		</th>
		<th style="text-align:left; width:20%;">  <?php echo gettext("PAYMENT AMOUNT"); ?>
		</th>
		<th style="text-align:left; width:20%;"> <?php echo gettext("DESCRIPTION"); ?>
		</th>
		<th style="text-align:left; width:20%;"><?php echo gettext("ID REFILL"); ?>
		</th>
		 
		 
		</thead>
		
						  	<tbody>
							<?php
                $i=0;
                foreach ($iax_buddies_result as $iax_buddies) {
                    if($i%2==0) $bg="#fcfbfb";
                    else  $bg="#f2f2ee";
               ?>
						    	<tr bgcolor="<?php echo $bg; ?>">
							      	 
							      	<td style="font-weight:600; text-align:left;  width:20%;"> <?php echo $payment['id']; ?></td>
							      	<td style="font-weight:600; text-align:left;  width:20%;"><?php echo $payment['date']; ?></td>
									<td style="font-weight:600; text-align:left;  width:20%;"> <?php echo $payment['payment']; ?></td>
									<td style="font-weight:600; text-align:left;  width:20%;"><?php echo $payment['description']; ?></td>
									<td style="font-weight:600; text-align:left;  width:20%;"><?php echo $sip_buddies['secret']; ?></td>
									
									 
									 
						    	</tr>
								
								 <?php
        $i++;
        }
        ?>
								
								 
						  	</tbody>
						</table>
						<?php
          }
        ?>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	
	
	</div>
	
	
	
	  
	
</div>	



<div class="row">
    <div class="col-lg-12">
	
	 
	
	
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("Recent Refills"); ?>	
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<?php

$refill_table = new Table('cc_logrefill','*');
$refill_clause = "card_id = ".$id;
$refill_result = $refill_table -> Get_list($DBHandle, $refill_clause, 'date', 'DESC', NULL, NULL, 10, 0);

if (sizeof($refill_result)>0 && $refill_result[0]!=null) {
?>
						
						<table class="table table-bordered table-hover">
						  	
		
		
		<thead>
		<th style="text-align:left; width:25%;"><?php echo gettext("ID"); ?>
		</th>
		<th style="text-align:left; width:25%;">  <?php echo gettext("REFILL DATE"); ?>
		</th>
		<th style="text-align:left; width:25%;"> <?php echo gettext("REFILL AMOUNT"); ?>
		</th>
		<th style="text-align:left; width:25%;"> <?php echo gettext("DESCRIPTION"); ?>
		</th>
		 
		 
		 
		</thead>
		
						  	<tbody>
							 <?php
        $i=0;
        foreach ($refill_result as $refill) {
            if($i%2==0) $bg="#fcfbfb";
            else  $bg="#f2f2ee";
    ?>
						    	<tr bgcolor="<?php echo $bg; ?>">
							      	 
							      	<td style="font-weight:600; text-align:left; width:25%;"> <?php echo $refill['id']; ?></td>
							      	<td style="font-weight:600; text-align:left; width:25%;"> <?php echo $refill['date']; ?></td>
									<td style="font-weight:600; text-align:left; width:25%;"> <?php echo $refill['credit']; ?></td>
									<td style="font-weight:600; text-align:left; width:25%;"><?php echo $refill['description']; ?></td>
									 
									
									 
									 
						    	</tr>
								
								 <?php
        $i++;
        }
        ?>
								
								 
						  	</tbody>
						</table>
						
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	
	
	</div>
	
	
	
	  
	
</div>	








<div class="row">
    <div class="col-lg-12">
	
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("Recent Calls"); ?>	
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<?php
}

$call_table = new Table('cc_call,cc_prefix','*');
$call_clause = "card_id = ".$id." AND cc_call.destination = cc_prefix.prefix";
$call_result = $call_table -> Get_list($DBHandle, $call_clause, 'starttime', 'DESC', NULL, NULL, 10, 0);

if (sizeof($call_result)>0 && $call_result[0]!=null) {
?>
						
						<table class="table table-bordered table-hover">
						  	
		
		
		<thead>
		<th style="text-align: left;"><?php echo gettext("CALL DATE"); ?>
		</th>
		<th style="text-align: left;">  <?php echo gettext("CALLED NUMBER"); ?>
		</th>
		<th style="text-align: left;"> <?php echo gettext("DESTINATION"); ?>
		</th>
		<th style="text-align: left;"> <?php echo gettext("DURATION"); ?>
		</th>
		<th style="text-align: left;"><?php echo gettext("TERMINATE CAUSE"); ?>
		</th>
		<th style="text-align: left;"><?php echo gettext("BUY"); ?>
		</th>
		<th style="text-align: left;"><?php echo gettext("SELL"); ?>
		</th style="text-align: left;">
		<th style="text-align: left;"> <?php echo gettext("LINK TO THE RATE"); ?>
		</th>
		 
		 
		 
		</thead>
		
						  	<tbody>
							<?php
        $dialstatus_list = Constants::getDialStatusList ();
        $i=0;
        foreach ($call_result as $call) {
            if($i%2==0) $bg="#fcfbfb";
            else  $bg="#f2f2ee";
    ?>
						    	<tr bgcolor="<?php echo $bg; ?>">
							      	 
							      	<td > <?php echo $call['starttime']; ?></td>
							      	<td > <?php echo $call['calledstation']; ?></td>
									<td > <?php echo $call['destination']; ?></td>
									<td > <?php echo display_minute($call['sessiontime']); ?></td>
									
									<td > <?php echo $dialstatus_list[$call['terminatecauseid']][0]; ?></td>
							      	<td > <?php echo display_2bill($call['buycost']); ?></td>
									<td> <?php echo display_2bill($call['sessionbill']); ?></td>
									<td > <?php if (!empty($call['id_ratecard'])) { ?>
                    <a href="billing_entity_def_ratecard.php?form_action=ask-edit&id=<?php echo $call['id_ratecard']?>"> <img src="<?php echo Images_Path."/link.png"?>" border="0" title="<?php echo gettext("Link to the used rate")?>" alt="<?php echo  gettext("Link to the used rate")?>"></a>
                     <?php } ?></td>
									 
									
									 
									 
						    	</tr>
								
								 <?php
        $i++;
        }
        ?>
								
								 
						  	</tbody>
						</table>
						
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	
	
	</div>
	
	
	
	  
	
</div>	




<div class="row">
    <div class="col-lg-12">
	
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet" >
			
		<div class="kt-portlet__head" style="background: rgba(184, 188, 223, 0.41); min-height: 40px;">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("DIDs & DID Destination"); ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						<?php
$did_destination_table = new Table('cc_did_destination,cc_did ','*');
$did_destination_clause = " cc_did_destination.id_cc_did = cc_did.id and cc_did_destination.id_cc_card  = ".$id;
$did_destination_result = $did_destination_table -> Get_list($DBHandle, $did_destination_clause, 0);
$did_destination = $did_destination_result[0];
if (sizeof($did_destination_result)>0 && $did_destination_result[0]!=null) {
?>
						
						<table class="table table-bordered table-hover">
						  	
		
		
		<thead>
		<th style="text-align:left; width:25%;"><?php echo gettext("DID"); ?>
		</th>
		<th style="text-align:left; width:25%;">  <?php echo gettext("DESTINATION"); ?>
		</th>
		<th style="text-align:left; width:25%;"><?php echo gettext("ACTIVATED"); ?>
		</th>
		<th style="text-align:left; width:25%;"><?php echo gettext("VoIP"); ?>
		</th>
		 
		 
		 
		 
		</thead>
		
						  	<tbody>
							 <?php
        $i=0;
        foreach ($did_destination_result as $did_destination) {
            if($i%2==0) $bg="#fcfbfb";
            else  $bg="#f2f2ee";
    ?>
						    	<tr bgcolor="<?php echo $bg; ?>">
							      	 
							      	<td style="font-weight:600; text-align:left; width:25%;"> <?php echo $did_destination['did']; ?></td>
							      	<td style="font-weight:600; text-align:left; width:25%;"> <?php echo $did_destination['destination']; ?></td>
									<td style="font-weight:600; text-align:left; width:25%;"> <?php echo ($did_destination['activated']=="1"?"Active":"Inactive"); ?></td>
									<td style="font-weight:600; text-align:left; width:25%;"> <?php echo ($did_destination['voip_call']=="1"?"Active":"Inactive"); ?></td>
									
									 
									 
									
									 
									 
						    	</tr>
								
								 <?php
        $i++;
        }
        ?>
								
								 
						  	</tbody>
						</table>
						
						<?php
}
?>
<?php
} ?>
						
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		 

		<!--begin::Portlet-->
		 
    </div>
	
	
	
	</div>
	
	
	
	  
	
</div>	




</div>






</div>








<!-- end:: Content -->				</div>
			
			
			
		 
 
 
 
<?php

$smarty->display( 'footer.tpl');
