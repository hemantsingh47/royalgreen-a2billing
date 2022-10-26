<?php

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_ticket_agent.inc';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_SUPPORT)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('title', 'description', 'priority' , 'component'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

// ADD Ticket
if (strlen($description)>0  && is_numeric($priority) && strlen($title)>0  && is_numeric($component)) {

        $fields = "creator,creator_type,title, description, id_component, priority, viewed_agent";
        $ticket_table = new Table('cc_ticket', $fields);
        $values = "'".$_SESSION["agent_id"]."',1, '".$title."', '".$description."', '".$component."', '".$priority ."' ,'0'";
        $id_ticket = $ticket_table ->Add_table($HD_Form -> DBHandle, $values, null, null, "id");
        NotificationsDAO::AddNotification("ticket_added_agent",Notification::$MEDIUM,Notification::$AGENT,$_SESSION['agent_id'],Notification::$LINK_TICKET_AGENT,$id_ticket);
        $table_agent =new Table("cc_agent", "firstname,lastname,language,email");
        $agent_clause = "id = ".$_SESSION["agent_id"];
        $result=$table_agent ->Get_list($HD_Form -> DBHandle, $agent_clause);
        $owner = $_SESSION["pr_login"]." (".$result[0]['firstname']." ".$result[0]['lastname'].")";
        try {
            $mail = new Mail(Mail::$TYPE_TICKET_NEW, null, $result[0]['language']);
            $mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
            $mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $id_ticket);
            $mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($priority));
            $mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $description);
            $mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,"NEW");
            $mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $title);
            $mail->send($result[0]['email']);
        } catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
        $component_table = new Table('cc_support_component LEFT JOIN cc_support ON id_support = cc_support.id', "email");
        $component_clause = "cc_support_component.id = ".$component;
        $result= $component_table -> Get_list($HD_Form -> DBHandle, $component_clause);

        try {
            $mail = new Mail(Mail::$TYPE_TICKET_NEW, null, $result[0]['language']);
            $mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
            $mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $id_ticket);
            $mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($priority));
            $mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $description);
            $mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,"NEW");
            $mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $title);
            $mail->send($result[0]['email']);
        } catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
        $update_msg = gettext("Ticket added successfully");

}

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;
$list = $HD_Form -> perform_action($form_action);
// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_support_list;

if ($form_action == "list") {
    // My code for Creating two functionalities in a page
    $HD_Form -> create_toppage ("ask-add");

?>
      
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Ticket Support </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Support                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Create & View Tickets                         </a>
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
                    <font class="kt-portlet__head-title"><?php echo gettext("Create a New Support Ticket"); ?></font>
				</div>
			</div>
			<!--begin::Form-->
			<form method="post" class="kt-form" action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-modif"?>" name="frmPass">
				<div class="kt-portlet__body">
					<div class="form-group row">
                    <div class="col-lg-1"></div>
						<font class="col-lg-2 col-sm-12"><?php echo gettext("Title");?> </font>
                        <div class="col-lg-6 col-md-9 col-sm-12">
						    <input type="text" name="title" size="100" maxlength="100" class="form-control" placeholder="Enter Title here">
						    <span class="form-text text-muted">Please enter your Title here.</span>
                        </div>    
					</div>

                    <div class="form-group row">
                    <div class="col-lg-1"></div>
						<font class="col-lg-2 col-sm-12"><?php echo gettext("Priority");?> </font>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <select name="component" class="form-control">
                            <td>
                                    <option class=input value='0' >NONE </option>
                                    <option class=input value='1' >LOW </option>
                                    <option class=input value='2' >MEDIUM </option>
                                    <option class=input value='3' >HIGH </option>
                                </select>
                            </td>
                        </div>    
					</div>
					
                    <div class="form-group row">
                    <div class="col-lg-1"></div>
						<font class="col-lg-2 col-sm-12"><?php echo gettext("Component");?> :</font>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <select name="component" class="form-control">
                            <?php
                                $DBHandle  = DbConnect();
                                $instance_sub_table = new Table("cc_support_component", "*");
                                $QUERY = " activated = 1 AND (type_user = 1 OR type_user = 2)";
                                $return = null;
                                $return = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);
                                foreach ($return as $value) {
                                    echo	'<option class=input value=" '. $value["id"].'"  > ' . $value["name"]. '  </option>' ;
                                }
                            ?>
                            </select>
                        </div>    
					</div>

                    <div class="form-group row">
                    <div class="col-lg-1"></div>
						<font class="col-lg-2 col-sm-12"><?php echo gettext("Description");?> :</font>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <textarea class="form-control" name="description" cols="10" rows="6" maxlength="200" placeholder="Enter description here...." style="width: 100% !important;"></textarea>
                        </div>    
					</div>
				</div>

                <div align="center" class="kt-portlet__foot">
                <div class="form-actions">
                    <input type="submit" name="create" value="&nbsp;<?php echo gettext("Create")?>&nbsp;" class="btn btn-brand" onclick="return CheckPassword();" >&nbsp;&nbsp;
                    <input type="reset" name="cancel" value="&nbsp;Cancel&nbsp;" class="btn btn-secondary">
                </div>
                </div>
			</form>
			<!--end::Form-->			
		</div>
		<!--end::Portlet-->
	</div>
<!-- begin:: Content -->
</div>
<br>
<center><font class="error_message"><?php if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; ?></font></center> 
    <?php 
    // END END END My code for Creating two functionalities in a page
    
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');
