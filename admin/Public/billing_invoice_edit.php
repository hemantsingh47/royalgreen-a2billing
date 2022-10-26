<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';
include '../lib/support/classes/invoice.php';
include '../lib/support/classes/invoiceItem.php';

if (! has_rights (ACX_INVOICING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('date','id','action','price','description','vat','idc'));

if (empty($id)) {
    Header ("Location: billing_entity_invoice.php?atmenu=payment&section=13");
}

$error_msg ='';
if (!empty($action)) {
    switch ($action) {
        case 'add':
            if (empty($date) || strtotime($date)===FALSE) {
                $error_msg.= gettext("Date inserted is invalid, it must respect a date format YYYY-MM-DD HH:MM:SS (time is optional).<br/>");
            }
            if ( !is_numeric($vat)) {
                $error_msg.= gettext("VAT inserted is invalid, it must be a number. Check the format.<br/>");
            }
            if (empty($price) || !is_numeric($price)) {
                $error_msg .= gettext("Amount inserted is invalid, it must be a number. Check the format.");
            }
            if(!empty($error_msg)) break;
            $DBHandle = DbConnect();
            $invoice = new Invoice($id);
            $invoice->insertInvoiceItem($description,$price,$vat);
            Header ("Location: billing_invoice_edit.php?"."id=".$id);
            break;
        case 'edit':
             if (!empty($idc) && is_numeric($idc)) {
                $DBHandle = DbConnect();
                $instance_sub_table = new Table("cc_invoice_item", "*");
                $result=$instance_sub_table -> Get_list($DBHandle, "id = $idc" );
                if (!is_array($result) || (sizeof($result)==0)) {
                     Header ("Location: billing_invoice_edit.php?"."id=".$id);
                } else {
                    $description=$result[0]['description'];
                    $vat=$result[0]['VAT'];
                    $price=$result[0]['price'];
                    $date =$result[0]['date'];
                }
             }
            break;
        case 'delete':
            if (!empty($idc) && is_numeric($idc)) {
                $DBHandle  = DbConnect();
                $instance_sub_table = new Table("cc_invoice_item", "*");
                $instance_sub_table -> Delete_Selected($DBHandle, "id = $idc" );
            }
            Header ("Location: billing_invoice_edit.php?"."id=".$id);
            break;

           case 'update':
            if (!empty($idc) && is_numeric($idc)) {
                if (empty($date) || strtotime($date)===FALSE) {
                    $error_msg.= gettext("Date inserted is invalid, it must respect a date format YYYY-MM-DD HH:MM:SS (time is optional).<br/>");
                }
                if ( !is_numeric($vat)) {
                    $error_msg.= gettext("VAT inserted is invalid, it must be a number. Check the format.<br/>");
                }
                if (empty($price) || !is_numeric($price)) {
                    $error_msg .= gettext("Amount inserted is invalid, it must be a number. Check the format.");
                }
                if(!empty($error_msg)) break;
                $DBHandle = DbConnect();
                $instance_sub_table = new Table("cc_invoice_item", "*");
                $instance_sub_table -> Update_table($DBHandle,"date='$date',description='$description',price='$price',vat='$vat'", "id = $idc" );
                Header ("Location: billing_invoice_edit.php?"."id=".$id);

             }
            break;
    }
}

$invoice = new invoice($id);
$table_card = new Table("cc_card","vat");
$result_vat = $table_card->Get_list(DbConnect(),"id=".$invoice->getCard());
$card_vat =  $result_vat[0][0];
$items = $invoice->loadItems();

$smarty->display('main.tpl');

?>
<style>
.kt-invoice-2{border-top-left-radius:4px;border-top-right-radius:4px}.kt-invoice-2 .kt-invoice__container{width:100%;margin:0;padding:0 30px}.kt-invoice-2 .kt-invoice__head{border-top-left-radius:4px;border-top-right-radius:4px;background-size:cover;background-repeat:no-repeat;padding:80px 0}.kt-invoice-2 .kt-invoice__head .kt-invoice__container{border-top-left-radius:4px;border-top-right-radius:4px}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand{display:flex;justify-content:space-between;flex-wrap:wrap}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__title{font-weight:700;font-size:2.7rem;margin-right:10px;margin-top:5px;color:#595d6e;vertical-align:top}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo{display:flex;flex-direction:column;margin-top:5px;text-align:right}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo img{text-align:right}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo .kt-invoice__desc{display:flex;flex-direction:column;text-align:right;font-weight:400;padding:1rem 0 1rem 0;color:#74788d}.kt-invoice-2 .kt-invoice__head .kt-invoice__items{display:flex;flex-wrap:wrap;margin-top:50px;width:100%;border-top:1px solid #ebedf2}.kt-invoice-2 .kt-invoice__head .kt-invoice__items .kt-invoice__item{display:flex;flex-direction:column;flex:1;color:#595d6e;margin-right:10px;margin-top:20px}.kt-invoice-2 .kt-invoice__head .kt-invoice__items .kt-invoice__item:last-child{margin-right:0}.kt-invoice-2 .kt-invoice__head .kt-invoice__items .kt-invoice__item .kt-invoice__subtitle{font-weight:500;padding-bottom:.5rem}.kt-invoice-2 .kt-invoice__head .kt-invoice__items .kt-invoice__item .kt-invoice__text{font-weight:400;color:#74788d}.kt-invoice-2 .kt-invoice__body{padding:3rem 0}.kt-invoice-2 .kt-invoice__body table{background-color:transparent}.kt-invoice-2 .kt-invoice__body table thead tr th{background-color:transparent;padding:1rem 0 .5rem 0;color:#74788d;border-top:0;border-bottom:1px solid #ebedf2}.kt-invoice-2 .kt-invoice__body table thead tr th:not(:first-child){text-align:right}.kt-invoice-2 .kt-invoice__body table tbody tr td{background-color:transparent;padding:1rem 0 1rem 0;border-top:none;font-weight:700;font-size:1.1rem;    text-align: center; color:#595d6e}.kt-invoice-2 .kt-invoice__body table tbody tr td:not(:first-child){text-align:right}.kt-invoice-2 .kt-invoice__body table tbody tr:first-child td{padding-top:1.8rem}.kt-invoice-2 .kt-invoice__footer{padding:3rem 0;background-color:#f7f8fa}.kt-invoice-2 .kt-invoice__footer .kt-invoice__container{display:flex;flex-direction:row;justify-content:space-between;flex-wrap:wrap}.kt-invoice-2 .kt-invoice__footer .table{background-color:transparent;padding:0}.kt-invoice-2 .kt-invoice__footer .table th{font-size:1.1rem;text-transform:capitalize;font-weight:500;color:#74788d;border-top:0;border-bottom:1px solid #ebedf2;padding:10px 10px 10px 0;background-color:transparent;     text-align: left; }.kt-invoice-2 .kt-invoice__footer .table th:last-child{padding-right:0}.kt-invoice-2 .kt-invoice__footer .table td{font-size:1.1rem;text-transform:capitalize;background-color:transparent;font-weight:500;color:#595d6e;padding:10px 10px 10px 0}.kt-invoice-2 .kt-invoice__footer .table td:last-child{padding-right:0}.kt-invoice-2 .kt-invoice__actions{padding:2rem 0}.kt-invoice-2 .kt-invoice__actions .kt-invoice__container{display:flex;flex-direction:row;justify-content:space-between}@media (min-width:1025px){.kt-invoice-2 .kt-invoice__container{width:90%;margin:0 auto}}@media (max-width:768px){.kt-invoice-2 .kt-invoice__container{width:100%;margin:0;padding:0 20px}.kt-invoice-2 .kt-invoice__head{padding:20px 0}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand{display:flex;flex-direction:column}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__title{font-weight:700;font-size:2rem;margin-bottom:30px}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo{text-align:left}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo img{text-align:left}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo .kt-invoice__desc{text-align:left}.kt-invoice-2 .kt-invoice__head .kt-invoice__items{margin-top:20px}.kt-invoice-2 .kt-invoice__body{padding:2rem 0}.kt-invoice-2 .kt-invoice__footer{padding:2rem 0}}@media print{.kt-invoice-2{border-top-left-radius:0;border-top-right-radius:0}.kt-invoice-2 .kt-invoice__head{border-top-left-radius:0;border-top-right-radius:0}.kt-invoice-2 .kt-invoice__head .kt-invoice__container{border-top-left-radius:0;border-top-right-radius:0}.kt-invoice-2 .kt-invoice__actions{display:none!important}.kt-invoice-2 .kt-invoice__footer{background-color:transparent!important}.kt-invoice-2 .kt-invoice__container{width:100%;padding:0 10px}}

</style>

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
                        <a href="billing_entity_invoice.php?atmenu=payment&section=11#" class="kt-subheader__breadcrumbs-link">
                            Invoice                     </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                         <a href="billing_invoice_edit.php?id=1" class="kt-subheader__breadcrumbs-link">
                            Edit Invoice                     </a>
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
           <?php echo gettext("Edit Invoice"); ?>
	    </h5>
        </div>
	</div>
	
<br>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-portlet">
<div class="kt-portlet__body kt-portlet__body--fit">
	<div class="kt-invoice-2">
		<div class="kt-invoice__head">
			<div class="kt-invoice__container">
				<div class="kt-invoice__brand">
					<h1 class="kt-invoice__title">INVOICE</h1>
					
					<!--<div href="#" class="kt-invoice__logo">
						<a href="#"><img src="templates/default/newtheme/theme/classic/assets/media/company-logos/admin_25.png"></a>

						<span class="kt-invoice__desc">
							<span>915 Spaze ITech park</span>
							<span>Sector 49 Gurugram, India</span>
						</span>
					</div>-->
				</div>
				
				<div class="kt-invoice__items">
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("INVOICE "); ?></span>
						<span class="kt-invoice__text"><?php echo $invoice->getTitle();  ?></span>
					</div>
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("REF "); ?> </span>
						<span class="kt-invoice__text"><?php echo $invoice->getReference(); ?></span>
					</div>
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("DESCRIPTION"); ?></span>
						<span class="kt-invoice__text"><?php echo $invoice->getDescription();  ?></span>
					</div>
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("FOR  "); ?></span>
						<span class="kt-invoice__text"><?php echo $invoice->getUsernames();  ?></span>
					</div>
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("DATE "); ?></span>
						<span class="kt-invoice__text"><?php echo $invoice->getDate();  ?></span>
					</div>
									
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("STATUS "); ?> </span>
						<span class="kt-invoice__text"><?php echo $invoice->getStatusDisplay($invoice->getStatus());  ?></span>
					</div>
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("PAID STATUS "); ?> </span>
						<span class="kt-invoice__text"><?php echo $invoice->getPaidStatusDisplay($invoice->getPaidStatus());  ?></span>
					</div>
					
				</div>
			</div>
		</div>
		

<div class="row-fluid">
<div class="uk-grid" data-uk-grid-margin="">
<div class="uk-width-1-1 uk-row-first">
	<div class="kt-invoice__body">
		<div class="kt-invoice__container">
			<table class="table">
				<thead>
				
        <table width="100%" cellspacing="10" class="table">
            <tr>
              <th style="text-align:left;">
             Date
              </th>
			  <th style="text-align:left;">
            Description
              </th>
			  <th style="text-align:left;">
              Amount
              </th>
              
              <th style="text-align:left;">
                  <font style="font-weight:bold; " >
                      <?php echo gettext("VAT"); ?>
                  </font>
              </th>
               <th style="text-align:left;" >
                  <font style="font-weight:bold; " >
                      <?php echo gettext("PRICE INCL. VAT"); ?>
                  </font>
              </th>
              <th style="text-align:left;">
              Action
              </th>
            </tr>

            <?php foreach ($items as $item) { ?>
            <tr>
                <td style="text-align:left;padding: 0.75rem;">
                    <?php echo $item->getDate(); ?>
                </td>
                <td style="text-align:left;padding: 0.75rem;">
                    <?php echo $item->getDescription(); ?>
                </td>
                <td style="text-align:left;padding: 0.75rem;">
                    <?php echo number_format(round($item->getPrice(),2),2)." ".strtoupper(BASE_CURRENCY); ?>
                </td>
                <td style="text-align:left;padding: 0.75rem;">
                    <?php echo number_format(round($item->getVAT(),2),2)." %" ?>
                </td>
                <td style="text-align:left;padding: 0.75rem;">
                    <?php echo number_format(round($item->getPrice()*(1+($item->getVAT()/100)),2),2)." ".strtoupper(BASE_CURRENCY); ?>
                </td>
                <td style="text-align:left;padding: 0.75rem;">
                    <a href="<?php echo $PHP_SELF ?>?id=<?php echo $id; ?>&action=edit&idc=<?php echo $item->getId();?>"><img src="<?php echo Images_Path ?>/edit.png" title="<?php echo gettext("Edit Item") ?>" alt="<?php echo gettext("Edit Item") ?>" border="0"></a>
                    <a href="<?php echo $PHP_SELF ?>?id=<?php echo $id; ?>&action=delete&idc=<?php echo $item->getId();?>"><img src="<?php echo Images_Path ?>/delete.png" title="<?php echo gettext("Delete Item") ?>" alt="<?php echo gettext("Delete Item") ?>" border="0"></a>
                </td>
            </tr>
             <?php } ?>

            
        <?php
        $price_without_vat = 0;
        $price_with_vat = 0;
        $vat_array = array();
        foreach ($items as $item) {
             $price_without_vat = $price_without_vat + $item->getPrice();
            $price_with_vat = $price_with_vat + ($item->getPrice()*(1+($item->getVAT()/100)));
            if (array_key_exists("".$item->getVAT(),$vat_array)) {
                $vat_array[$item->getVAT()] = $vat_array[$item->getVAT()] + $item->getPrice()*($item->getVAT()/100) ;
            } else {
                $vat_array[$item->getVAT()] =  $item->getPrice()*($item->getVAT()/100) ;
            }
         }

         ?>
             <tr>
                 
                 <td colspan="6" align="">
				 
				 <table>
				 <tr>
				 <td><?php echo gettext("TOTAL EXCL. VAT") ?>&nbsp;:
				 </td>
				 <td><?php echo number_format(round($price_without_vat,2),2)." ".strtoupper(BASE_CURRENCY); ?>
				 </td>
				 </tr>
				 </table>
                     
                 </td>
                  
                 
             </tr>
             <?php foreach ($vat_array as $key => $val) { ?>

             <tr>
                 
                 <td colspan="6" align="">
                    
<table>
				 <tr>
				 <td><?php echo gettext("TOTAL VAT ($key%)") ?>&nbsp;:
				 </td>
				 <td><?php echo number_format(round($val,2),2)." ".strtoupper(BASE_CURRENCY); ?>
				 </td>
				 </tr>
				 </table>


					
                 </td>
                 
                 
             </tr>

             <?php } ?>
             <tr>
                 
                 <td colspan="6" align="right">
				 
				 <table>
				 <tr>
				 <td><?php echo gettext("TOTAL INCL. VAT") ?>&nbsp;:
				 </td>
				 <td> <?php echo number_format(round($price_with_vat,2),2)." ".strtoupper(BASE_CURRENCY); ?>
				 </td>
				 </tr>
				 </table>
				 
				 
                     
                 </td>
                 
                 
             </tr>

        </table>

    
    </tr>
</table>
<hr/>
<br/>
<?php if (!empty($error_msg)) { ?>
    <div class="msg_error" style="width:70%; margin-left:auto;margin-right:auto;">
        <?php echo $error_msg ?>
    </div>
<?php } ?>
  <form action="<?php echo $PHP_SELF.'?id='.$invoice->getId(); ?>" method="post" >
     <input id="action" type="hidden" name="action" value="<?php if(!empty($idc)) echo "update"; else echo "add" ?>"/>
    <input id="idc" type="hidden" name="idc" value="<?php if(!empty($idc)) echo $idc;?>"/>
    <table class="table" >
        <tr class="form_invoice_head">
            <td colspan="2" align="center" style="background: #efefef;"><?php echo gettext("ADD INVOICE ITEM "); ?></td>
        </tr>
        <tr >
            <td colspan="2">&nbsp;</td>
        </tr>
        <?php
            if (empty($date)) {
                $date = date("Y-m-d H:i:s");
            }
        ?>
        <tr>
            <td style="text-align:left;"><font style="font-weight:bold; " ><?php echo gettext("DATE : "); ?>
             </td>
             <td>
             <input type="text" class="form-control" name="date" size="20" maxlength="20" <?php if(!empty($date)) echo 'value="'.$date.'"';?>/>
             </td>
        </tr>
        <tr>
            <td style="text-align:left;"><font style="font-weight:bold; " ><?php echo gettext("AMOUNT : "); ?>
             </td>
             <td>
             <input type="text" class="form-control" name="price" size="10" maxlength="10" <?php if(!empty($price)) echo 'value="'.$price.'"';?>/>
             </td>
        </tr>
        <tr>
            <td style="text-align:left;"><font style="font-weight:bold; " ><?php echo gettext("VAT : "); ?>
             </td>
             <td>
             <input type="text" class="form-control" name="vat" size="5" maxlength="5" <?php if(!empty($vat)) echo 'value="'.$vat.'"'; else echo 'value="'.$card_vat.'"';?> />
             </td>
        </tr>
        <tr>
            <td style="text-align:left;"><font style="font-weight:bold; " ><?php echo gettext("DESCRIPTION : "); ?>
             </td>
            <td style="text-align:left;">
             <textarea class="form-control" name="description" cols="50" rows="5"><?php if(!empty($description)) echo $description ;?></textarea>
             </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;">
                <input class="btn btn-primary btn-small" type="submit" value="<?php if(!empty($idc)) echo gettext("UPDATE"); else echo gettext("ADD"); ?>"/>
             </td>
        </tr>

    </table>
  </form>
  </div>
  </div>
  </div>
  <?php
      $smarty->display('footer.tpl');
  ?>
