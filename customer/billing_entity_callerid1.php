<?php

include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_callerid.inc';
include 'lib/customer.smarty.php';

if (! has_rights (ACX_CALLER_ID)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('add_callerid'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

// ADD SPEED DIAL
if (strlen($add_callerid)>0  && is_numeric($add_callerid)) {
    $instance_sub_table = new Table('cc_callerid');
    $QUERY = "SELECT count(*) FROM cc_callerid WHERE id_cc_card='".$_SESSION["card_id"]."'";
    $result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 1);
    // CHECK IF THE AMOUNT OF CALLERID IS LESS THAN THE LIMIT
    if ($result[0][0] < $A2B->config["webcustomerui"]['limit_callerid']) {
        $QUERY = "INSERT INTO cc_callerid (id_cc_card, cid) VALUES ('".$_SESSION["card_id"]."', '".$add_callerid."')";
        $result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);
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

if ($form_action == "list") {
    // My code for Creating two functionalities in a page
    $HD_Form -> create_toppage ("ask-add");
?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Caller ID </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Services                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Add ANI                         </a>
                </div>
            </span>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
<?php

    if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg;

    $count_cid = is_array($list) ? sizeof($list) : 0;
    if ($count_cid < $A2B->config["webcustomerui"]['limit_callerid']) {

?>
       <table align="center"  border="0" width="55%" class="bgcolor_006">
        <form name="theForm" action="<?php  $_SERVER["PHP_SELF"]?>">
        <tr class="bgcolor_001" >

        <td align="center" valign="top">
                <?php gettext("CALLER ID :");?>
                <input class="form_input_text" name="add_callerid" size="15" maxlength="60">
            </td>
            <td align="center" valign="middle">
                        <input class="form_input_button"  value="<?php echo gettext("ADD NEW CALLERID"); ?>"  type="submit">
        </td>
        </tr>
        </form>
      </table>
      <br>
    <?php
    } else {

    ?>
        <table align="center"  border="0" width="70%" class="bgcolor_006">
            <tr class="bgcolor_001" >
                <td align="center" valign="middle">
                    <b><i> <?php  echo gettext("You are not allowed to add more CallerID.");
                    echo "<br/>";
                     echo gettext("Remove one if you are willing to use an other CallerID.");?> </i> </b>
                    <br/>
                    <?php echo gettext("Max CallerId");?> &nbsp;:&nbsp; <?php echo $A2B->config["webcustomerui"]['limit_callerid'] ?>
                  </td>
              </tr>
         </table>
    <?php
    }
    // END END END My code for Creating two functionalities in a page
}
?>
</center>
<?php

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
