<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_MAIL)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array (
    'id',
    'languages',
    'subject',
    'mailtext',
    'translate_data',
    'id_language',
    'mailtype'
));

$handle = DbConnect();
$instance_table = new Table();

// #### HEADER SECTION
$smarty->display('main.tpl');

if (isset ($translate_data) && $translate_data == 'translate') {
    //print check_translated($id, $languages);
    if (check_translated($id, $languages, $mailtype)) {
        update_translation($id, $languages, $subject, $mailtext, $mailtype);
    } else {
        insert_translation($id, $languages, $subject, $mailtext, $mailtype);
    }
}

// Query to get mail template information
$QUERY = "SELECT id, mailtype, subject, messagetext, id_language FROM cc_templatemail WHERE mailtype = '$mailtype'";
if (isset ($languages))
    $QUERY .= " and id_language = '$languages'";
$mail = $instance_table->SQLExec($handle, $QUERY);

// #### HELP SECTION
echo $CC_help_list_misc;

// Query to get all languages with ids
$QUERY = "SELECT code, name FROM cc_iso639 ORDER BY code";
$result = $instance_table->SQLExec($handle, $QUERY);
if (is_array($result)) {
    $num_cur = count($result);
    for ($i = 0; $i < $num_cur; $i++) {
        $languages_list[$result[$i][0]] = array (
            0 => $result[$i][0],
            1 => $result[$i][1]
        );
    }
}

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Others                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Others                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Mail                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_translate.php" class="kt-subheader__breadcrumbs-link">
                            Template Translation </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
<div class="kt-portlet__head">
	<div class="kt-portlet__head-label">
		<h1 class="kt-portlet__head-title">
			<?php echo gettext("Template Translation"); ?>
			
		</h1>
	</div>
</div>

 <br>
 
<FORM name="theForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL) ?>" METHOD="POST">
<INPUT name="mailtype" value="<?php echo $mailtype; ?>" type="hidden">

<?php
    if ($HD_Form->FG_CSRF_STATUS == true) {
?>
    <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
    <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
<?php
    }
?>

<table cellspacing="2" class="addform_table1">
    <TBODY>
    <TR>
        <TD width="%25" valign="middle" class="form_head"> 
			<label class="col-2 col-form-label"><?php echo gettext('Language');?> </label>
		</TD>
        <TD width="%75" valign="top" class="tableBodyRight" class="text">
            <select NAME="languages" size="1" class="form-control" onChange="form.submit()">
            <?php
                foreach ($languages_list as $key => $lang_value) {
            ?>
            <option value='<?php echo $lang_value[0];?>'
                <?php
                if ($mail[0][4] != '') {
                    if ($lang_value[0]==$mail[0][4]) {print "selected";}
                } else {
                    if ($lang_value[0]==$languages) {print "selected";}
                }?>><?php echo $lang_value[1]; ?></option>
            <?php }?>
            </select>
            <span class="liens">
        </span>
        </TD>
    </TR>

    <TR>
        <TD width="%25" valign="middle" class="form_head">
			<label class="col-2 col-form-label"><?php echo gettext('Subject');?> </label>
		</TD>
        <TD width="%75" valign="top" class="tableBodyRight"class="text">
        <INPUT class="form-control" name="subject"  size=30 maxlength=30 value="<?php echo $mail[0][2]?>">
        <span class="liens">
        </span>
        </TD>
    </TR>

    <TR>
        <TD width="%25" valign="middle" class="form_head">
			<label class="col-2 col-form-label"><?php echo gettext('Mail Text');?> </label>
		</TD>
        <TD width="%75" valign="top" class="tableBodyRight" class="text">
        <TEXTAREA class="form-control" name="mailtext" cols=60 rows=12><?php echo $mail[0][3]?></TEXTAREA>
        <span class="liens">
        </span>
        </TD>
    </TR>
    </table>
    <TABLE cellspacing="0" class="editform_table8">
    <tr>
     <td colspan="2" class="editform_dotted_line">&nbsp; </td>
    </tr>

    <tr>
        <td width="50%" class="text_azul"><span class="form-text text-muted"><?php echo gettext('Once you have completed the form above, click on the Translate button.');?></span></td>
        <td width="50%" align="right" class="text">
    <input class="btn btn-primary btn-small" TYPE="submit" name="translate_data" VALUE="TRANSLATE">
        </td>
    </tr>

    </TABLE>
    <INPUT type="hidden" name="id" value="<?php echo $id?>">
</form>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');
