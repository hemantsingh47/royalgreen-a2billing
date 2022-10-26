<?php

include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_speeddial.inc';
include './lib/customer.smarty.php';

if (!has_rights(ACX_SPEED_DIAL)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array ('destination',	'choose_speeddial',	'name'));

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

// ADD SPEED DIAL
if (strlen($destination) > 0 && is_numeric($choose_speeddial)) {

    $FG_SPEEDDIAL_TABLE = "cc_speeddial";
    $FG_SPEEDDIAL_FIELDS = "speeddial";
    $instance_sub_table = new Table($FG_SPEEDDIAL_TABLE, $FG_SPEEDDIAL_FIELDS);

    $QUERY = "INSERT INTO cc_speeddial (id_cc_card, phone, name, speeddial) VALUES ('" . $_SESSION["card_id"] . "', '" . $destination . "', '" . $name . "', '" . $choose_speeddial . "')";

    $result = $instance_sub_table->SQLExec($HD_Form->DBHandle, $QUERY, 0);
}

if ($id != "" || !is_null($id)) {
    $HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
    $form_action = "list";

if (!isset ($action))
    $action = $form_action;

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if ($form_action == 'list') {
    echo $CC_help_speeddial;
}

if ($form_action == "list") {
    // My code for Creating two functionalities in a page
    $HD_Form->create_toppage("ask-add");

     if (isset($update_msg) && strlen($update_msg)>0)
         echo $update_msg;
?>
      
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"> Speed Dial </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                    <div class="kt-subheader__breadcrumbs">
                        <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                                Services                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                                Speed Dial                        </a>
                    </div>
                </span>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
                    <font class="kt-portlet__head-title"><?php echo gettext("Speed Dial"); ?></font>
				</div>
			</div>

            <!--begin::Form-->
			<form class="kt-form" action="<?php  $_SERVER["PHP_SELF"]?>" name="theForm"> <br> 
            <div align="center"><b><?php echo gettext("Enter the number which you wish to assign to the code here");?>.</b></div><br/><br/>
				<div class="kt-portlet__body">
					<div class="form-group row">
                    <div class="col-lg-1"></div>
						<font class="col-lg-2 col-sm-12"><?php echo gettext("Speed Dial code");?> </font>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <select name="choose_speeddial" class="form-control">
                                <?php
                                    foreach ($speeddial_list as $recordset) {
                                    ?>
                                        <option class=input value='<?php echo $recordset[1]?>' ><?php echo $recordset[1]?> </option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                    <div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12">Destination</label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <input type="number" class="form-control" name="destination" size="15" maxlength="60" placeholder="Destination"/>
						        <span align="left" class="form-text text-muted">Please enter Destination here</span>
                            </div>    
					</div>

                    <div class="form-group row">
                    <div class="col-lg-1"></div>
						<label class="col-lg-2 col-sm-12">Country</label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <input type="text" class="form-control" name="name" size="15" maxlength="40" placeholder="Country name"/>
						        <span align="left" class="form-text text-muted">Please enter your Country here</span>
                            </div>    
					</div>
                </div>

                <div  align="center" class="kt-portlet__foot">
                    <div class="form-actions">
                        <input type="submit" name="submit" value="&nbsp;<?php echo gettext("Assign Number To SpeedDial")?>&nbsp;" class="btn btn-brand">&nbsp;&nbsp;
                        <input type="reset" name="reset" value="&nbsp;Reset&nbsp;" class="btn btn-secondary">
                    </div>
                </div>
            </div>
            </form>
            <!--begin::Form-->
        </div>
    </div>
    </div>

    
      <br>
    <?php
    // END END END My code for Creating two functionalities in a page
}


	echo ' <div class="col-md-12" style="padding-top:30px;">';

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');
