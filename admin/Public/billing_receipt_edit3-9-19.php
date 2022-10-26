<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';
include '../lib/support/classes/receipt.php';
include '../lib/support/classes/receiptItem.php';

if (! has_rights (ACX_INVOICING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('date','id','action','price','description','idc'));

if (empty($id)) {
    Header ("Location: billing_entity_receipt.php?atmenu=payment&section=13");
}

if (!empty($action)) {
    switch ($action) {
        case 'add':
            if (empty($date) || strtotime($date)===FALSE) {
                $error_msg.= gettext("Date inserted is invalid, it must respect a date format YYYY-MM-DD HH:MM:SS (time is optional).<br/>");
            }
            if (empty($price) || !is_numeric($price)) {
                $error_msg .= gettext("Amount inserted is invalid, it must be a number. Check the format.");
            }
            if(!empty($error_msg)) break;
            $DBHandle = DbConnect();
            $receipt = new Receipt($id);
            $receipt->insertReceiptItem($description,$price);
            Header ("Location: billing_receipt_edit.php?"."id=".$id);
            break;
        case 'edit':
             if (!empty($idc) && is_numeric($idc)) {
                $DBHandle = DbConnect();
                $instance_sub_table = new Table("cc_receipt_item", "*");
                $result=$instance_sub_table -> Get_list($DBHandle, "id = $idc" );
                if (!is_array($result) || (sizeof($result)==0)) {
                     Header ("Location: billing_receipt_edit.php?"."id=".$id);
                } else {
                    $description=$result[0]['description'];
                    $price=$result[0]['price'];
                    $date =$result[0]['date'];
                }
             }
            break;
        case 'delete':
            if (!empty($idc) && is_numeric($idc)) {
                $DBHandle  = DbConnect();
                $instance_sub_table = new Table("cc_receipt_item", "*");
                $instance_sub_table -> Delete_Selected($DBHandle, "id = $idc" );
            }
            Header ("Location: billing_receipt_edit.php?"."id=".$id);
            break;
            case 'update':
            if (!empty($idc) && is_numeric($idc)) {
                if (empty($date) || strtotime($date)===FALSE) {
                    $error_msg.= gettext("Date inserted is invalid, it must respect a date format YYYY-MM-DD HH:MM:SS (time is optional).<br/>");
                }
                if (empty($price) || !is_numeric($price)) {
                    $error_msg .= gettext("Amount inserted is invalid, it must be a number. Check the format.");
                }
                if(!empty($error_msg)) break;
                $DBHandle = DbConnect();
                $instance_sub_table = new Table("cc_receipt_item", "*");
                $instance_sub_table -> Update_table($DBHandle,"date='$date',description='$description',price='$price'", "id = $idc" );
                Header ("Location: billing_receipt_edit.php?"."id=".$id);

             }
            break;
    }
}

$receipt = new Receipt($id);
$items = $receipt->loadItems();

$smarty->display('main.tpl');

?>

<style>
.kt-invoice-2{border-top-left-radius:4px;border-top-right-radius:4px}.kt-invoice-2 .kt-invoice__container{width:100%;margin:0;padding:0 30px}.kt-invoice-2 .kt-invoice__head{border-top-left-radius:4px;border-top-right-radius:4px;background-size:cover;background-repeat:no-repeat;padding:80px 0}.kt-invoice-2 .kt-invoice__head .kt-invoice__container{border-top-left-radius:4px;border-top-right-radius:4px}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand{display:flex;justify-content:space-between;flex-wrap:wrap}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__title{font-weight:700;font-size:2.7rem;margin-right:10px;margin-top:5px;color:#595d6e;vertical-align:top}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo{display:flex;flex-direction:column;margin-top:5px;text-align:right}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo img{text-align:right}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo .kt-invoice__desc{display:flex;flex-direction:column;text-align:right;font-weight:400;padding:1rem 0 1rem 0;color:#74788d}.kt-invoice-2 .kt-invoice__head .kt-invoice__items{display:flex;flex-wrap:wrap;margin-top:50px;width:100%;border-top:1px solid #ebedf2}.kt-invoice-2 .kt-invoice__head .kt-invoice__items .kt-invoice__item{display:flex;flex-direction:column;flex:1;color:#595d6e;margin-right:10px;margin-top:20px}.kt-invoice-2 .kt-invoice__head .kt-invoice__items .kt-invoice__item:last-child{margin-right:0}.kt-invoice-2 .kt-invoice__head .kt-invoice__items .kt-invoice__item .kt-invoice__subtitle{font-weight:500;padding-bottom:.5rem}.kt-invoice-2 .kt-invoice__head .kt-invoice__items .kt-invoice__item .kt-invoice__text{font-weight:400;color:#74788d}.kt-invoice-2 .kt-invoice__body{padding:3rem 0}.kt-invoice-2 .kt-invoice__body table{background-color:transparent}.kt-invoice-2 .kt-invoice__body table thead tr th{background-color:transparent;padding:1rem 0 .5rem 0;color:#74788d;border-top:0;border-bottom:1px solid #ebedf2}.kt-invoice-2 .kt-invoice__body table thead tr th:not(:first-child){text-align:right}.kt-invoice-2 .kt-invoice__body table tbody tr td{background-color:transparent;padding:1rem 0 1rem 0;border-top:none;font-weight:700;font-size:1.1rem;    text-align: center; color:#595d6e}.kt-invoice-2 .kt-invoice__body table tbody tr td:not(:first-child){text-align:right}.kt-invoice-2 .kt-invoice__body table tbody tr:first-child td{padding-top:1.8rem}.kt-invoice-2 .kt-invoice__footer{padding:3rem 0;background-color:#f7f8fa}.kt-invoice-2 .kt-invoice__footer .kt-invoice__container{display:flex;flex-direction:row;justify-content:space-between;flex-wrap:wrap}.kt-invoice-2 .kt-invoice__footer .table{background-color:transparent;padding:0}.kt-invoice-2 .kt-invoice__footer .table th{font-size:1.1rem;text-transform:capitalize;font-weight:500;color:#74788d;border-top:0;border-bottom:1px solid #ebedf2;padding:10px 10px 10px 0;background-color:transparent;     text-align: left; }.kt-invoice-2 .kt-invoice__footer .table th:last-child{padding-right:0}.kt-invoice-2 .kt-invoice__footer .table td{font-size:1.1rem;text-transform:capitalize;background-color:transparent;font-weight:500;color:#595d6e;padding:10px 10px 10px 0}.kt-invoice-2 .kt-invoice__footer .table td:last-child{padding-right:0}.kt-invoice-2 .kt-invoice__actions{padding:2rem 0}.kt-invoice-2 .kt-invoice__actions .kt-invoice__container{display:flex;flex-direction:row;justify-content:space-between}@media (min-width:1025px){.kt-invoice-2 .kt-invoice__container{width:80%;margin:0 auto}}@media (max-width:768px){.kt-invoice-2 .kt-invoice__container{width:100%;margin:0;padding:0 20px}.kt-invoice-2 .kt-invoice__head{padding:20px 0}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand{display:flex;flex-direction:column}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__title{font-weight:700;font-size:2rem;margin-bottom:30px}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo{text-align:left}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo img{text-align:left}.kt-invoice-2 .kt-invoice__head .kt-invoice__brand .kt-invoice__logo .kt-invoice__desc{text-align:left}.kt-invoice-2 .kt-invoice__head .kt-invoice__items{margin-top:20px}.kt-invoice-2 .kt-invoice__body{padding:2rem 0}.kt-invoice-2 .kt-invoice__footer{padding:2rem 0}}@media print{.kt-invoice-2{border-top-left-radius:0;border-top-right-radius:0}.kt-invoice-2 .kt-invoice__head{border-top-left-radius:0;border-top-right-radius:0}.kt-invoice-2 .kt-invoice__head .kt-invoice__container{border-top-left-radius:0;border-top-right-radius:0}.kt-invoice-2 .kt-invoice__actions{display:none!important}.kt-invoice-2 .kt-invoice__footer{background-color:transparent!important}.kt-invoice-2 .kt-invoice__container{width:100%;padding:0 10px}}

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
                         <a href="billing_entity_receipt.php" class="kt-subheader__breadcrumbs-link">
                            Edit Receipt Items                     </a>
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
           <?php echo gettext("Edit Receipt Items"); ?>
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
					
					<div href="#" class="kt-invoice__logo">
						<a href="#"><img src="templates/default/newtheme/theme/classic/assets/media/company-logos/admin_25.png"></a>

						<span class="kt-invoice__desc">
							<span>915 Spaze ITech park</span>
							<span>Sector 49 Gurugram, India</span>
						</span>
					</div>
				</div>
				
				<div class="kt-invoice__items">
					
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("RECEIPT "); ?></span>
						<span class="kt-invoice__text"><?php echo $receipt->getTitle();  ?></span>
					</div>
					
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("FOR  "); ?></span>
						<span class="kt-invoice__text"><?php echo $receipt->getUsernames();  ?></span>
					</div>
					
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("DATE "); ?></span>
						<span class="kt-invoice__text"><?php echo $receipt->getDate();  ?></span>
					</div>
					
					<div class="kt-invoice__item">
						<span class="kt-invoice__subtitle"><?php echo gettext("DESCRIPTION"); ?></span>
						<span class="kt-invoice__text"><?php echo $receipt->getDescription();  ?></span>
					</div>
					
					
				</div>
				
			</div>
		</div>

		<div class="row-fluid">
			<div class="uk-grid" data-uk-grid-margin="">
			<div class="uk-width-1-1 uk-row-first">
				<div class="kt-invoice__body">
					<div class="kt-invoice__container">
						
							
					<table width="100%" cellspacing="10" align="center">
						<tr>
						  
						  <th align="center" width="100%">
							  <font style="font-weight:bold; " >
								  <?php echo gettext("PRICE "); ?>
							  </font>
						  </th>
						</tr>
						
						<?php foreach ($items as $item) { ?>
            <tr style="vertical-align:top;" >
                <td>
                    <?php echo $item->getDate(); ?>
                </td>
                <td >
                    <?php echo $item->getDescription(); ?>
                </td>
                <td align="right">
                    <?php echo number_format(round($item->getPrice(),2),2)." ".strtoupper(BASE_CURRENCY); ?>
                </td>
                <td align="center">
                    <a href="<?php echo $PHP_SELF ?>?id=<?php echo $id; ?>&action=edit&idc=<?php echo $item->getId();?>"><img src="<?php echo Images_Path ?>/edit.png" title="<?php echo gettext("Edit Item") ?>" alt="<?php echo gettext("Edit Item") ?>" border="0"></a>
                    <a href="<?php echo $PHP_SELF ?>?id=<?php echo $id; ?>&action=delete&idc=<?php echo $item->getId();?>"><img src="<?php echo Images_Path ?>/delete.png" title="<?php echo gettext("Delete Item") ?>" alt="<?php echo gettext("Delete Item") ?>" border="0"></a>
                </td>
            </tr>
             <?php } ?>

            
        <?php
        $totalprice = 0;
        foreach ($items as $item) {
             $totalprice = $totalprice + $item->getPrice();
         }

         ?>
             <tr>
                 
                 <td  align="ceneter">
                     <?php echo gettext("TOTAL: ") ?>
                 </td>
                 <td align="" >
                     <?php echo number_format(round($totalprice,2),2)." ".strtoupper(BASE_CURRENCY); ?>
                 </td>
                 
             </tr>
        </table>
		
		
   
<br/>
<?php if (!empty($error_msg)) { ?>
    <div class="msg_error" style="width:70%; margin-left:auto;margin-right:auto;">
        <?php echo $error_msg ?>
    </div>
<?php } ?>

  <form action="<?php echo $PHP_SELF.'?id='.$receipt->getId(); ?>" method="post" class="kt-form" >
     <input id="action" class="form-control" type="hidden" name="action" value="<?php if(!empty($idc)) echo "update"; else echo "add" ?>"/>
    <input id="idc" type="hidden" name="idc" value="<?php if(!empty($idc)) echo $idc;?>"/>
    <table class="invoice_table">
        <tr class="form_invoice_head">
            <td colspan="2" align="center"><font color="#FFFFFF"><?php echo gettext("ADD RECEIPT ITEM "); ?></font></td>
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
            <td ><font style="font-weight:bold; " ><?php echo gettext("DATE : "); ?>
             </td>
             <td>
             <input type="text" class="form-control" name="date" size="20" maxlength="20" <?php if(!empty($date)) echo 'value="'.$date.'"';?>/>
             </td>
        </tr>
        <tr>
            <td ><font style="font-weight:bold; " ><?php echo gettext("PRICE : "); ?>
             </td>
             <td>
             <input type="text" class="form-control" name="price" size="10" maxlength="10" <?php if(!empty($price)) echo 'value="'.$price.'"';?>/>
             </td>
        </tr>
        <tr>
            <td ><font style="font-weight:bold; " ><?php echo gettext("DESCRIPTION : "); ?>
             </td>
            <td>
             <textarea class="form-control" name="description" cols="50" rows="5"><?php if(!empty($description)) echo $description ;?></textarea>
             </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <input class="form_input_button" type="submit" value="<?php if(!empty($idc)) echo gettext("UPDATE"); else echo gettext("ADD"); ?>"/>
             </td>
        </tr>

    </table>
  </form>
</div>
		</div>
		</div></div></div>