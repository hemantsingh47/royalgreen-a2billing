<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
**/

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

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Billing                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                         <a href="" class="kt-subheader__breadcrumbs-link">
                            Invoice	                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="CC_entity_sim_ratecard.php?atmenu=ratecard&section=6" class="kt-subheader__breadcrumbs-link">
                            Edit Receipt Item                 </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>


<div class="kt-portlet">
	<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h5 class="kt-portlet__head-title">
           <?php echo gettext("Edit Receipt Items"); ?>
	    </h5>
        </div>
	</div>


<div class="kt-portlet__body">   





 
<table class="table">
    <tr class="form_invoice_head">
        <td colspan="2"><?php echo gettext("RECEIPT: "); ?><b><?php echo $receipt->getTitle();  ?></b></td>
    </tr>
    
    <tr>
        <td >
            <font style="font-weight:bold;" ><?php echo gettext("FOR : "); ?></font>  <?php echo $receipt->getUsernames();  ?>
        </td>
        <td align="right">
            <font style="font-weight:bold;"><?php echo gettext("DATE : "); ?></font>  <?php echo $receipt->getDate();  ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
        <br/>
        <font style="font-weight:bold; " ><?php echo gettext("DESCRIPTION : "); ?></font>   <?php echo $receipt->getDescription();  ?></td>
    </tr>

    <tr >
    <td colspan="2">
        <table width="100%" cellspacing="10">
            <tr>
                <th  width="20%">
              &nbsp;
              </th>
			    <th  width="20%">
              &nbsp;
              </th>
              
              
			  <th width="40%" style="text-align:right;">
                  <font style="font-weight:bold; " >
                      <?php echo gettext("PRICE"); ?>
                  </font>
              </th>
			  <th  width="20%">
              &nbsp;
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

            <tr>
                 <td colspan="4">
                     &nbsp;
                 </td>
             </tr>
        <?php
        $totalprice = 0;
        foreach ($items as $item) {
             $totalprice = $totalprice + $item->getPrice();
         }

         ?>
             <tr>
                 <td >
                     &nbsp;
                 </td>
                 <td  align="right">
                     
                 </td>
                 <td align="right" >
                  <b> <?php echo gettext("TOTAL") ?>: </b> <?php echo number_format(round($totalprice,2),2)." ".strtoupper(BASE_CURRENCY); ?>
                 </td>
                 <td >
                     &nbsp;
                 </td>
             </tr>
        </table>
    </td>
    </tr>
</table>

<?php if (!empty($error_msg)) { ?>
    <div class="msg_error" style="width:70%; margin-left:auto;margin-right:auto;">
        <?php echo $error_msg ?>
    </div>
<?php } ?>

  <form action="<?php echo $PHP_SELF.'?id='.$receipt->getId(); ?>" method="post" class="kt-form">
     <input id="action" type="hidden" name="action" value="<?php if(!empty($idc)) echo "update"; else echo "add" ?>"/>
    <input id="idc" type="hidden" name="idc" value="<?php if(!empty($idc)) echo $idc;?>"/>
    <table class="table">
        <tr class="form_invoice_head">
            <th colspan="2" style="text-align: left;
    color: #000;"><b><?php echo gettext("ADD RECEIPT ITEM "); ?></b></th>
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
             <input type="text" class="form-control13" name="date" size="20" maxlength="20" <?php if(!empty($date)) echo 'value="'.$date.'"';?>/>
             </td>
        </tr>
        <tr>
            <td ><font style="font-weight:bold; " ><?php echo gettext("PRICE : "); ?>
             </td>
             <td>
             <input type="text" class="form-control13" name="price" size="10" maxlength="10" <?php if(!empty($price)) echo 'value="'.$price.'"';?>/>
             </td>
        </tr>
        <tr>
            <td style="vertical-align: top;"><font style="font-weight:bold; " ><?php echo gettext("DESCRIPTION : "); ?>
             </td>
            <td>
             <textarea class="form-control13" name="description" cols="50" rows="5" style="height:90px;"><?php if(!empty($description)) echo $description ;?></textarea>
             </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <input type="submit" class="btn btn-success" value="<?php if(!empty($idc)) echo gettext("UPDATE"); else echo gettext("ADD"); ?>"/>
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