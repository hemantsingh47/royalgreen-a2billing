<?php
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_BILLING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id'));

if (empty($id)) {
    header("Location: billing_entity_payment_agent.php?atmenu=payment&section=10");
}

$DBHandle  = DbConnect();

$payment_table = new Table('cc_logpayment_agent','*');
$payment_clause = "id = ".$id;
$payment_result = $payment_table -> Get_list($DBHandle, $payment_clause, 0);
$payment = $payment_result[0];

if (empty($payment)) {
    header("Location: billing_entity_payment_agent.php?atmenu=payment&section=10");
}

// #### HEADER SECTION
$smarty->display('main.tpl');
?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Billing                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                             Agent Billing                       </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_payment_agent.php?atmenu=payment&section=10" class="kt-subheader__breadcrumbs-link">
                           Agent Payments Info                     </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
	
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
	<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h5 class="kt-portlet__head-title">
           <?php echo gettext("Agent's Payments Info"); ?>
	    </h5>
        </div>
	</div>
<table style="width : 80%;" class="editform_table1">
   
   <tr height="20px">
        <td>
            <label class="col-12 col-form-label">
				<?php echo gettext("ACCOUNT NUMBER") ?> :
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<?php
				if ( has_rights (ACX_CUSTOMER)) {
					echo infocustomer_id($refill['card_id']);
				} else {
					echo nameofcustomer_id($refill['card_id']);
				}
				?>
			</p>
		</td>
   </tr>
   <tr height="20px">
        <td>
            <label class="col-12 col-form-label">
				<?php echo gettext("AMOUNT") ?> :
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<?php echo $refill['credit']." ".strtoupper(BASE_CURRENCY);?>
			</p>
		</td>
		
   </tr>
    <tr height="20px">
         <td>
            <label class="col-12 col-form-label">
				<?php echo gettext("CREATION DATE") ?> :
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<?php echo $refill['date']?>
			</p>
		</td>
		
		
    </tr>
   <tr height="20px">
        <td>
            <label class="col-12 col-form-label">
				<?php echo gettext("PAYMENT TYPE") ?> :
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<?php
            $list_type = Constants::getRefillType_List();
            echo $list_type[$payment['payment_type']][0];?>
			</p>
		</td>
		
   </tr>
   <tr height="20px">
        <td>
            <label class="col-12 col-form-label">
				<?php echo gettext("DESCRIPTION") ?> :
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<?php echo $refill['description']?>
			</p>
		</td>
		
    </tr>
       <tr height="20px">
        <td>
            <label class="col-12 col-form-label">
				<?php echo gettext("LINK REFILL") ?> :
			</label>
        </td>
        <td>
            <p class="form-control-static">
			<a href="billing_entity_logrefill_agent.php"> <img src="<?php echo Images_Path."/link.png"?>" border="0" title="<?php echo gettext("Link to the refill")?>" alt="<?php echo  gettext("Link to the refill")?>"></a>
		</p>
		
        </td>
    </tr>
      

 </table>
 <br/>
 <div style="width : 80%; text-align : right; margin-left:auto;margin-right:auto;" >
     <a class="btn btn-primary"  href="<?php echo "billing_entity_payment_agent.php?atmenu=payment&section=10" ?>">
        
        <?php echo gettext("PAYMENTS LIST"); ?> 
    </a>
	<br>
</div>
</div>
 <br>
<?php

$smarty->display( 'footer.tpl');
