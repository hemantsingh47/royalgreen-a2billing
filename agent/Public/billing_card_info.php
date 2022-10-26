<?php

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id'));

$DBHandle  = DbConnect();
if (isset($id)) {
    if (!empty($id)&& $id>0) {
        $table_agent_security = new Table("cc_card LEFT JOIN cc_card_group ON cc_card.id_group=cc_card_group.id ", " cc_card_group.id_agent");
        $clause_agent_security = "cc_card.id= ".$id;
        $result_security= $table_agent_security -> Get_list ($DBHandle, $clause_agent_security, null, null, null, null, null, null);
        if ($result_security[0][0] !=$_SESSION['agent_id']) {
            Header ("HTTP/1.0 401 Unauthorized");
            Header ("Location: PP_error.php?c=accessdenied");
            die();
        }
    }
}

if (empty($id)) {
    header("Location: billing_entity_card.php?atmenu=card&stitle=Customers_Card&section=1");
}

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
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            List Customer                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
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
					<h2 class="heading_b uk-margin-bottom">
					<?php echo gettext("Customer's Information") ?>
					</h2>
				</div>
			</div>
    </div>
</div>


<div class="row">
    <div class="kt-portlet">
	
	
	
	
	
       <div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<?php


echo Display_Login_Button ($DBHandle, $id);
?>
 <a class="btn btn-primary btn-small"  href="billing_entity_card.php?section=1">
                
                <?php echo gettext("CUSTOMERS LIST"); ?>
            </a>
				</div>
			</div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
	
	 <div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
		
		<div class="kt-portlet__head">
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
							      	 
							      	<td style="font-weight:600; width:50%"><?php echo gettext("STATUS") ?> :</td>
							      	<td><?php
                        $list_typepaid = Constants::getPaidTypeList();
                        echo $list_typepaid[$card['typepaid']][0];?></td>
						    	</tr>
								
								
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("ACCOUNT NUMBER") ?> :</td>
							      	<td> <?php echo $card['username']?></td>
						    	</tr>
								
								<tr>
							      	 
							      	<td style="font-weight:600"><?php echo gettext("SERIAL NUMBER") ?> :</td>
							      	<td><?php echo str_pad($card['serial'], $A2B->config["webui"]['card_serial_length'] , "0", STR_PAD_LEFT); ?></td>
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
							      	<td><?php
                        $list_status = Constants::getCardStatus_List();
                        echo $list_status[$card['status']][0];?></td>
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
	</div>
	
	
	
	
	<div class="col-lg-12">
	<div class="col-xs-12">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
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
							      	 
							      	<td style="font-weight:600;width:50%"><?php echo gettext("BALANCE") ?> :</td>
							      	<td><?php echo $card['credit']?></td>
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
	</div>
	
	</div>
	
	<div class="row">
	<div class="col-lg-12">
	
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
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
							      	 
							      	<td style="font-weight:600; width:50%"><?php echo gettext("LAST NAME") ?> :</td>
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
	
	</div>
	
	
	
	
	
	 <div class="col-lg-12">
     
		<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
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
							      	 
							      	<td style="font-weight:600;width:50%"><?php echo gettext("COMPANY NAME") ?> :</td>
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
	
	</div>
	
	</div>
	
	
	<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
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
						 <?php
                                        $callerid_table = new Table('cc_callerid','*');
                                        $callerid_clause = "id_cc_card  = ".$id;
                                        $callerid_result = $callerid_table -> Get_list($DBHandle, $callerid_clause, 0);
                                        $callerid = $callerid_result[0];
                                        if (sizeof($callerid_result)>0 && $callerid_result[0]!=null) {
            ?>
              <table width="100%" class="table table-bordered table-hover">
            
                    <tr class="form_head">
                       <th class="tableBody"  style="text-align:left;">
                                    <?php echo gettext("CID"); ?>
                       </th>
                       <th class="tableBody"   align="left" style="text-align:left;">
                                <?php echo gettext("ACTIVATED"); ?>
                       </th>
                   </tr>
               <?php
                $i=0;
                foreach ($callerid_result as $callerid) {
                    if($i%2==0) $bg="#fcfbfb";
                    else  $bg="#f2f2ee";
               ?>
                <tr bgcolor="<?php echo $bg; ?>"  >
                    <td class="tableBodyRight"    align="left" width="50%">
                      <?php echo $callerid['cid']; ?>
                    </td>

                    <td class="tableBodyRight"    align="left" width="50%">
                      <?php echo ($callerid['activated']=="t"?"Active":"Inactive"); ?>
                    </td>
                </tr>
               <?php
               $i++;
               }
               ?>
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
	
	
	
	
	
	
	
	
	
	
	
	<!-- caller id end-->
	
	<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
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
						<?php
                                        $speeddial_table = new Table('cc_speeddial','*');
                                        $speeddial_clause = "id_cc_card  = ".$id;
                                        $speeddial_result = $speeddial_table -> Get_list($DBHandle, $speeddial_clause, 0);
                                        $speeddial = $speeddial_result[0];
                                        if (sizeof($speeddial_result)>0 && $speeddial_result[0]!=null) {
                                        ?>
                <table width="100%" class="table table-bordered table-hover">
                   
                              <tr class="form_head">
                                <th class="tableBody" style="text-align:left;">
                                         <?php echo gettext("PHONE"); ?>
                                </th>
                                <th class="tableBody"   style="text-align:left;">
                                        <?php echo gettext("NAME"); ?>
                                </th>
                                <th class="tableBody"   style="text-align:left;">
                                        <?php echo gettext("SPEEDDIAL"); ?>
                                </th>
                           </tr>
                           <?php
                        $i=0;
                        foreach ($speeddial_result as $speeddial) {
                            if($i%2==0) $bg="#fcfbfb";
                            else  $bg="#f2f2ee";
                        ?>
                            <tr bgcolor="<?php echo $bg; ?>"  >
                                <td class="tableBodyRight"    align="left">
                                          <?php echo $speeddial['phone']; ?>
                                </td>
                                <td class="tableBodyRight"   align="left">
                                          <?php echo $speeddial['name']; ?>
                                </td>
                                <td class="tableBodyRight"    align="left">
                                          <?php echo $speeddial['speeddial']; ?>
                                </td>
                            </tr>
                        <?php
                        $i++;
                        }
                        ?>
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
		 
    </div>
	</div>
	
	
	
	
	
<!-- speed- dial end-->


	
	
	
	</div>
	
	
	
	
	
	
	<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("SIP-CONFIG") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						 <?php
                                            $sip_buddies_table = new Table('cc_sip_buddies','*');
                                            $sip_buddies_clause = "id_cc_card  = ".$id;
                                            $sip_buddies_result = $sip_buddies_table -> Get_list($DBHandle, $sip_buddies_clause, 0);
                                            $sip_buddies = $sip_buddies_result[0];
                                            if (sizeof($sip_buddies_result)>0 && $sip_buddies_result[0]!=null) {
                                            ?>
                                            <table width="100%" class="table table-bordered table-hover">
                                               
                                                          <tr class="form_head">
                                                        <th class="tableBody"  style="text-align:left;">
                                                                 <?php echo gettext("USERNAME"); ?>
                                                        </th>
                                                        <th class="tableBody"  style="text-align:left;">
                                                                <?php echo gettext("SECRET"); ?>
                                                        </th>
                                                        </tr>
                                                       <?php
                                                    $i=0;
                                                    foreach ($sip_buddies_result as $sip_buddies) {
                                                        if($i%2==0) $bg="#fcfbfb";
                                                        else  $bg="#f2f2ee";
                                                    ?>
                                                        <tr bgcolor="<?php echo $bg; ?>"  >
                                                            <td class="tableBodyRight"   align="left" width="50%">
                                                                      <?php echo $sip_buddies['username']; ?>
                                                            </td>
                                                            <td class="tableBodyRight"  align="left" width="50%">
                                                                      <?php echo $sip_buddies['secret']; ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    $i++;
                                                    }
                                                    ?>
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
		 
    </div>
	</div>
	
	
	
	
	
<!-- speed- dial end-->


	
	
	
	</div>
	
	
	
	
	<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("IAX-CONFIG") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						 <?php
                                            $iax_buddies_table = new Table('cc_iax_buddies','*');
                                            $iax_buddies_clause = "id_cc_card  = ".$id;
                                            $iax_buddies_result = $iax_buddies_table -> Get_list($DBHandle, $iax_buddies_clause, 0);
                                            $iax_buddies = $iax_buddies_result[0];
                                            if (sizeof($iax_buddies_result)>0 && $iax_buddies_result[0]!=null) {
                                            ?>
                <table width="100%" class="table table-bordered table-hover">
                   
                       <tr class="form_head">
                            <th class="tableBody"  style="text-align:left;">
                                         <?php echo gettext("USERNAME"); ?>
                            </th>
                            <th class="tableBody" style="text-align:left;">
                                        <?php echo gettext("SECRET"); ?>
                            </th>
                       </tr>
                       <?php
                        $i=0;
                        foreach ($iax_buddies_result as $iax_buddies) {
                            if($i%2==0) $bg="#fcfbfb";
                            else  $bg="#f2f2ee";
                       ?>
                            <tr bgcolor="<?php echo $bg; ?>"  >
                                <td class="tableBodyRight"   align="left" width="50%">
                                              <?php echo $iax_buddies['username']; ?>
                                </td>
                                <td class="tableBodyRight"   align="left" width="50%">
                                              <?php echo $iax_buddies['secret']; ?>
                                </td>
                            </tr>
                        <?php
                        $i++;
                        }
                        ?>
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
		 
    </div>
	</div>
	
	
	
	
	
<!-- speed- dial end-->


	
	
	
	</div>
	
	
	
	
	<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("Current Subscriptions") ?>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin::Section-->
				<div class="kt-section">
					<div class="kt-section__content">
						 <?php

        // We need to list all required columns as both tables have an 'id' column
        $subscription_table = new Table('cc_card_subscription,cc_subscription_service','cc_card_subscription.id,id_cc_card,startdate,product_name,fee');
        $subscription_clause = "id_cc_card = ".$id." AND cc_card_subscription.id_subscription_fee = cc_subscription_service.id";
        $subscription_result = $subscription_table -> Get_list($DBHandle, $subscription_clause, 'startdate', 'DESC', NULL, NULL, 10, 0);
        if (sizeof($subscription_result)>0 && $subscription_result[0]!=null) 
        {
        ?>
                                    <table class="table table-bordered table-hover">

                                        <tr class="form_head">
                                            <td class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("ID"); ?>
                                            </td>
                                            <td class="tableBody"  style="text-align:left;">
                                            <?php echo gettext("DATE"); ?>
                                            </td>
                                            <td class="tableBody"  style="text-align:left;">
                                            <?php echo gettext("SUBSCRIPTION"); ?>
                                            </td>
                                            <td class="tableBody"  style="text-align:left;">
                                            <?php echo gettext("FEE"); ?>
                                            </td>
                                            <td class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("LINKS"); ?>
                                            </td>

                                        </tr><?php
                                            $i=0;
                                            foreach ($subscription_result as $subscription) {
                                                if($i%2==0) $bg="#fcfbfb";
                                                else  $bg="#f2f2ee";
                                        ?>
                                                <tr bgcolor="<?php echo $bg; ?>"  >
                                                    <td class="tableBody" align="left">
                                                      <?php echo $subscription['id']; ?>
                                                    </td>

                                                    <td class="tableBody" align="left">
                                                      <?php echo $subscription['startdate']; ?>
                                                    </td>

                                                    <td class="tableBody"  align="left">
                                                      <?php echo $subscription['product_name']; ?>
                                                    </td>

                                                    <td class="tableBody"  align="left">
                                                      <?php echo $subscription['fee']; ?>
                                                    </td>
                                                    <td class="tableBody"  align="left">
                                                        <?php if (!empty($subscription['id'])) { ?>
                                                        <a href="billing_entity_subscriber.php?form_action=ask-edit&id=<?php echo $subscription['id']?>"> <img src="<?php echo Images_Path."/link.png"?>" border="0" title="<?php echo gettext("Link to subscription")?>" alt="<?php echo  gettext("Link to subscription")?>"></a>
                                                        <a href="billing_entity_subscriber.php?form_action=ask-delete&id=<?php echo $subscription['id']?>"> <img src="<?php echo Images_Path."/delete.png"?>" border="0" title="<?php echo gettext("Delete subscription")?>" alt="<?php echo  gettext("Delete subscription")?>"></a>
                                                        <?php } ?>
                                                    </td>

                                                </tr>
                                            <?php
                                            $i++;
                                            }
                                            ?>
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
		 
    </div>
	</div>
	
	
	
	
	
<!-- speed- dial end-->


	
	
	
	</div>
	
	
	
	
	<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
				
				
					<h3 class="kt-portlet__head-title">
					<?php echo gettext("Current Payment Log"); ?>	
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
		<th class="tableBody"  style="text-align:left;"><?php echo gettext("ID"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"> <?php echo gettext("PAYMENT DATE"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;">  <?php echo gettext("PAYMENT AMOUNT"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"> <?php echo gettext("DESCRIPTION"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"><?php echo gettext("ID REFILL"); ?>
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
							      	 
							      	<td style="text-align:left;"> <?php echo $payment['id']; ?></td>
							      	<td style="text-align:left;"><?php echo $payment['date']; ?></td>
									<td style="text-align:left;"> <?php echo $payment['payment']; ?></td>
									<td style="text-align:left;"><?php echo $payment['description']; ?></td>
									<td style="text-align:left;"><?php echo $sip_buddies['secret']; ?></td>
									
									 
									 
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
		 
    </div>
	</div>
	
	</div>
	
	
	
	<!-- IAX Buddies end-->
	
	
	
	
	
<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
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

        $call_table = new Table('cc_call,cc_prefix','*');
        $call_clause = "card_id = ".$id." AND CAST(cc_call.destination AS CHAR) = cc_prefix.prefix";
        $call_result = $call_table -> Get_list($DBHandle, $call_clause, 'starttime', 'DESC', NULL, NULL, 10, 0);
        if (sizeof($call_result)>0 && $call_result[0]!=null) {
        ?>
                    
                  
                                        <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr class="form_head">
                                            <th class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("CALL DATE"); ?>
                                            </th>
                                            <th class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("CALLED NUMBER"); ?>
                                            </th>
                                            <th class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("DESTINATION"); ?>
                                            </th>
                                            <th class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("DURATION"); ?>
                                            </th>
                                            <th class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("TERMINATE CAUSE"); ?>
                                            </th>
                                            <th class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("BUY"); ?>
                                            </th>
                                            <th class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("SELL"); ?>
                                            </th>
                                            <th class="tableBody"  style="text-align:left;">
                                             <?php echo gettext("RATE"); ?>
                                            </th>

                                        </tr>
                                        </thead>
                                        

                                        <?php
                                            $dialstatus_list = Constants::getDialStatusList ();
                                            $i=0;
                                            foreach ($call_result as $call) {
                                                if($i%2==0) $bg="#fcfbfb";
                                                else  $bg="#f2f2ee";
                                        ?>
                                                <tr bgcolor="<?php echo $bg; ?>"  >
                                                    <td class="tableBody" align="left">
                                                      <?php echo $call['starttime']; ?>
                                                    </td>

                                                    <td class="tableBody" align="left">
                                                      <?php echo $call['calledstation']; ?>
                                                    </td>

                                                    <td class="tableBody"  align="left">
                                                      <?php echo $call['destination']; ?>
                                                    </td>
                                                    <td class="tableBody"  align="left">
                                                      <?php echo display_minute($call['sessiontime']); ?>
                                                    </td>
                                                    <td class="tableBody"  align="left">
                                                      <?php echo $dialstatus_list[$call['terminatecauseid']][0]; ?>
                                                    </td>
                                                    <td class="tableBody"  align="left">
                                                      <?php echo display_2bill($call['buycost']); ?>
                                                    </td>
                                                    <td class="tableBody"  align="left">
                                                      <?php echo display_2bill($call['sessionbill']); ?>
                                                    </td>
                                                    <td class="tableBody"  align="left">
                                                        <?php if (!empty($call['id_ratecard'])) { ?>
                                                        <a href="billing_entity_def_ratecard.php?form_action=ask-edit&id=<?php echo $call['id_ratecard']?>"> <img src="<?php echo Images_Path."/link.png"?>" border="0" title="<?php echo gettext("Link to the used rate")?>" alt="<?php echo  gettext("Link to the used rate")?>"></a>
                                                         <?php } ?>
                                                    </td>

                                                </tr>
                                            <?php
                                            $i++;
                                            }
                                    }
                                    ?>
                                    </table>
					</div>
				</div>
				<!--end::Section-->
			</div>
			<!--end::Form-->
		</div>
		 
    </div>
	</div>
	
	</div>








<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
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
		<th class="tableBody"  style="text-align:left;"><?php echo gettext("ID"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"> <?php echo gettext("PAYMENT DATE"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;">  <?php echo gettext("PAYMENT AMOUNT"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"> <?php echo gettext("DESCRIPTION"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"><?php echo gettext("ID REFILL"); ?>
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
							      	 
							      	<td style="text-align:left;"> <?php echo $payment['id']; ?></td>
							      	<td style="text-align:left;"><?php echo $payment['date']; ?></td>
									<td style="text-align:left;"> <?php echo $payment['payment']; ?></td>
									<td style="text-align:left;"><?php echo $payment['description']; ?></td>
									<td style="text-align:left;"><?php echo $sip_buddies['secret']; ?></td>
									
									 
									 
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
		 
    </div>
	</div>
	
	</div>

<!-- recent- payment end--->



<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
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
		<th class="tableBody"  style="text-align:left;"><?php echo gettext("ID"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;">  <?php echo gettext("REFILL DATE"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"> <?php echo gettext("REFILL AMOUNT"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"> <?php echo gettext("DESCRIPTION"); ?>
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
							      	 
							      	<td style="text-align:left;"> <?php echo $refill['id']; ?></td>
							      	<td style="text-align:left;"> <?php echo $refill['date']; ?></td>
									<td style="text-align:left;"> <?php echo $refill['credit']; ?></td>
									<td style="text-align:left;"><?php echo $refill['description']; ?></td>
									 
									
									 
									 
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
	
	</div>


 
	
	
	
	
	<div class="row">
	
	<div class="col-lg-12">
	<div class="col-xs-12">
     
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			
		<div class="kt-portlet__head">
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
		<th class="tableBody"  style="text-align:left;"><?php echo gettext("DID"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;">  <?php echo gettext("DESTINATION"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"><?php echo gettext("ACTIVATED"); ?>
		</th>
		<th class="tableBody"  style="text-align:left;"><?php echo gettext("VoIP"); ?>
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
							      	 
							      	<td style="text-align:left;"> <?php echo $did_destination['did']; ?></td>
							      	<td style="text-align:left;"> <?php echo $did_destination['destination']; ?></td>
									<td style="text-align:left;"> <?php echo ($did_destination['activated']=="1"?"Active":"Inactive"); ?></td>
									<td style="text-align:left;"> <?php echo ($did_destination['voip_call']=="1"?"Active":"Inactive"); ?></td>
									
									 
									 
									
									 
									 
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
		 
    </div>
	</div>
	
	</div>
	
	
	
	
	
	


</div>
</div>
	
		 
 
<?php

$smarty->display( 'footer.tpl');
