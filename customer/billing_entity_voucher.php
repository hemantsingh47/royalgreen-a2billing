<?php
 
include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_voucher.inc';
include './lib/customer.smarty.php';

if (! has_rights (ACX_VOUCHER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();
$currencies_list = get_currencies();
//print($currencies_list[0]);

if (strlen($voucher)>0) {

    if (is_numeric($voucher)) {

        sleep(2);
        $FG_VOUCHER_TABLE  = "cc_voucher";
        $FG_VOUCHER_FIELDS = "voucher, credit, activated, tag, currency, expirationdate,used";
        $instance_sub_table = new Table($FG_VOUCHER_TABLE, $FG_VOUCHER_FIELDS);

        $FG_TABLE_CLAUSE_VOUCHER = "expirationdate >= CURRENT_TIMESTAMP AND activated='t' AND voucher='$voucher'";

        $list_voucher = $instance_sub_table -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE_VOUCHER, $order, $sens, null, null, $limite, $current_record);
		
        if ($list_voucher[0][0]==$voucher) {
            if (!isset ($currencies_list[strtoupper($list_voucher[0][4])][2])) {
                $error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("System Error : the currency table is incomplete!").'</b></font><br><br>';
            } else {
                $add_credit = $list_voucher[0][1]*$currencies_list[strtoupper($list_voucher[0][4])][2];
                $QUERY = "UPDATE cc_voucher SET activated='f', used='1', usedcardnumber='".$_SESSION["pr_login"]."', usedate=now() WHERE voucher='".$voucher."'";
                $result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

                $QUERY = "UPDATE cc_card SET credit=credit+'".$add_credit."' WHERE username='".$_SESSION["pr_login"]."'";
                $result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);
				
				//$error_msg = '<div class=\"uk-alert uk-alert-success\" data-uk-alert=\"\"><a href=\"#\" class=\"uk-alert-close uk-close"></a>'.gettext("The voucher").'('.$voucher.') '.gettext("has been used, We added").' '.$add_credit.' '.gettext("credit on your account!").'</div>';
                $error_msg = "<div class='uk-alert uk-alert-success' style='width:450px;height:30px;' data-uk-alert=''> <a href='#' class='uk-alert-close uk-close'></a><b>The voucher $voucher has been used, $add_credit has been credited to your account!.</b></div>";
			}
        } else {
            $error_msg = "<div class='uk-alert uk-alert-danger' style='width:350px;height:25px;' data-uk-alert=''> <a href='#' class='uk-alert-close uk-close'></a><b>This voucher doesn't exist !</b></div>";
			            
        }
    } else {
        
		$error_msg = "<div class='uk-alert uk-alert-danger' style='width:350px;height:25px;' data-uk-alert=''> <a href='#' class='uk-alert-close uk-close'></a><b>The voucher should be a number !</b></div>";
    }
}

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display( 'main.tpl');

// begin:: Subheader 
echo '<div class="kt-subheader   kt-grid__item" id="kt_subheader">';
    echo '<div class="kt-container  kt-container--fluid ">';
        echo '<div class="kt-subheader__main">';
            echo '<h3 class="kt-subheader__title"> Use Vouchers </h3>';
                echo '<span class="kt-subheader__separator kt-hidden"></span>';
                    echo '<div class="kt-subheader__breadcrumbs">';
                        echo '<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>';
                        echo '<a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>';
                        echo '<a href="" class="kt-subheader__breadcrumbs-link">
                            Vouchers                         </a>';
                    echo '</div>';
                echo '</span>';
        echo '</div>';
    echo '</div>';
echo '</div>';
// end:: Subheader 
                    
// #### HELP SECTION
if ($form_action=='list') {
    echo $CC_help_list_voucher;
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

?>


<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
            <div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
                    <font class="kt-portlet__head-title"><?php echo gettext("Use Voucher"); ?></font>
				</div>
			</div>
                <form action="billing_entity_voucher.php" class="kt-form">
                    <div class="kt-portlet__body">
					  <div class="form-group row">
                          <div class="col-lg-1"></div>
                        <label class="col-lg-2 col-sm-12"><?php echo gettext("Voucher");?> </label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <input type="number" class="form-control" name="voucher" placeholder="Enter voucher here">
                            <span class="form-text text-muted">Please enter your voucher no. here</span>
                        </div>
                    </div>
                    <div align="center" class="kt-portlet__foot">
                        <div class="form-actions">
                            <input type="submit" class="btn btn-brand" value=" <?php echo gettext("Use Voucher");?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
</div>
<center><h5 style="color:red;"><?php echo $error_msg ?></h5> </center>


<?php

// #### CREATE FORM OR LIST

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// Code for the Export Functionality
//* Query Preparation.
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";

if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";
    
					
                //echo "</div>";
            //echo "</div>";
       

    //echo "</div>";


// #### FOOTER SECTION
$smarty->display('footer.tpl');
