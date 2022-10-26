<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_mailtemplate.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_MAIL)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('languages', 'id', 'action'));

if ($action=="load") {
    $DBHandle=DbConnect();
    if (!empty($id) && is_numeric($id)) {
        $instance_table_mail = new Table("cc_templatemail","messagetext, fromemail, fromname, subject");
        $clause_mail = " id ='$id'";
        $result=$instance_table_mail-> Get_list($DBHandle, $clause_mail);
        echo json_encode($result[0]);
    }
    die();
}

if ($popup_select) {
?>
<SCRIPT LANGUAGE="javascript">
function sendValue(selvalue) {
    $.getJSON("A2B_entity_mailtemplate.php", { id: ""+ selvalue, action: "load" },
    function(data){
        window.opener.document.getElementById('msg_mail').value = data.messagetext;
        window.opener.document.getElementById('from').value = data.fromemail;
        window.opener.document.getElementById('fromname').value = data.fromname;
        window.opener.document.getElementById('subject').value = data.subject;
        window.close();
    });
}
</script>
<?php
}

$HD_Form -> setDBHandler (DbConnect());

$HD_Form -> init();

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if (!$popup_select) echo $CC_help_list_misc;
if (isset($form_action) && $form_action=="list") {
?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
            Mail Templates List                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Others                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Mail                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_mailtemplate.php?atmenu=mailtemplate&section=17&languages=en" class="kt-subheader__breadcrumbs-link">
                            Mail Templates                        </a>
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
			<?php if ($form_action == 'ask-add'){echo gettext("Add Mail Templates"); }else if( $form_action == 'ask-delete'){echo gettext("Delete Mail Template");} else if( $form_action == 'ask-edit'){echo gettext("Modify Mail Template");} else{echo gettext("Mail Templates List"); }?>
			
		</h1>
	</div>
</div>

 <br>


<table align="center" class="bgcolor_001" border="0" width="30%">
    <tr>
        <form name="theForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL) ?>">
          <?php if ($popup_select) { ?>
                  <input type="hidden" name="popup_select" value="<?php echo $popup_select; ?>" />
          <?php } ?>
          <td align="left" width="75%">
                <?php
                    $handle = DbConnect();
                    $instance_table = new Table();
                    $QUERY =  "SELECT code, name FROM cc_iso639 order by code";
                    $result = $instance_table -> SQLExec ($handle, $QUERY);
                    if (is_array($result)) {
                        $num_cur = count($result);
                        for ($i=0;$i<$num_cur;$i++) {
                            $languages_list[$result[$i][0]] = array (0 => $result[$i][0], 1 => $result[$i][1]);
                        }
                    }
                ?>
                <select NAME="languages" size="1" class="form-control" onChange="form.submit()">
                    <?php
                    foreach ($languages_list as $key => $lang_value) {
                ?>
                    <option value='<?php echo $lang_value[0];?>' <?php if($lang_value[0]==$languages) print "selected";?>><?php echo $lang_value[1]; ?></option>
                <?php } ?>
        </td>
       </form>
   </tr>
</table>
<?php
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');
