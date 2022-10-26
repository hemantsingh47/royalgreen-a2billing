<?php



include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_notify.inc';
include './lib/customer.smarty.php';

if (!has_rights(ACX_NOTIFICATION)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form->setDBHandler(DbConnect());

$HD_Form->init();

if ($id != "" || !is_null($id)) {
    $HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
    $form_action = "list"; //ask-add
if (!isset ($action))
    $action = $form_action;

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

//begin:: Subheader
echo '<div class="kt-subheader   kt-grid__item" id="kt_subheader">';
    echo '<div class="kt-container  kt-container--fluid ">';
        echo '<div class="kt-subheader__main">';
            echo '<h3 class="kt-subheader__title"> Notification </h3>';
                echo '<span class="kt-subheader__separator kt-hidden"></span>';
                    echo '<div class="kt-subheader__breadcrumbs">';
                        echo '<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>';
                        echo '<a href="" class="kt-subheader__breadcrumbs-link">
                            Support                      </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>';
                        echo '<a href="" class="kt-subheader__breadcrumbs-link">
                            Notifications                         </a>';
                    echo '</div>';
                echo '</span>';
        echo '</div>';
    echo'</div>';
echo'</div>';
//end:: Subheader
	
echo'<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">';
    echo'<div class="col-md-12" style="margin: 0 auto;">';
		//begin::Portlet
		echo'<div class="kt-portlet">';
            echo'<div class="kt-portlet__head">';
                echo'<div class="kt-portlet__head-label">';
                    echo'<h3 class="kt-portlet__head-title">
						Notification
					</h3>';
                echo'</div>';
            echo'</div>';
            
            //begin::Form
			echo'<form method="post" class="kt-form" action="/crm/customer/billing_notification.php?form_action=ask-modif" name="frmPass">';

                //echo'<div class="kt-portlet__foot">';
                //echo'<div class="form-actions">';
                    //echo'<input type="submit" name="submitPassword" value="&nbsp;Search&nbsp;" class="btn btn-brand">&nbsp;&nbsp;';
                    //echo'<input type="reset" name="cancel" value="&nbsp;Clear&nbsp;" class="btn btn-secondary">';
                //echo'</div>';
                //echo'</div>';

                if ($message == "success") {
                    ?>
                    <center>
                    <table  align="center" class="table table-bordered">
                        <tr height="100px">
                            <td align="center"><?php echo gettext("Your notification settings has successfully been updated.")?></td>
                        </tr>
                    </table>
                    </center>
                    </div>
                          
                    <?php
                    } else {
                        $HD_Form -> create_form ($form_action, $list, $id=null) ;
                    }
            echo'</form>';
        echo'</div>';
    echo'</div>';
echo'</div>';

// #### HELP SECTION
echo $CC_help_notification;

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

/*if ($message == "success") {
?>
<center>
<table  align="center" class="table table-bordered">
    <tr height="100px">
        <td align="center"><?php echo gettext("Your notification settings has successfully been updated.")?></td>
    </tr>
</table>
</center>
</div>
      
<?php
} else {
    $HD_Form -> create_form ($form_action, $list, $id=null) ;
}*/

// #### FOOTER SECTION
$smarty->display('footer.tpl');
