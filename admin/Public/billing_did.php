<?php


include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';
include '../lib/support/classes/support_service.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_did.inc';

if (!has_rights(ACX_SUPPORT)) {
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
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_support_component;
if (($form_action)=="ask-add") {
 ?>
 <h2><?php echo gettext("ADD DID");?></h2>
 <?php     
} 

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);

	$did_table = new Table('cc_did_no','max(id) as t');
	$did_clause = null;
	$did_result= $did_table -> Get_list($DBHandle, $did_clause, 0);
    //print_r($did_result);
	
	 $maxid =$did_result[0]['t'];   //9 id
	 
	 $did_table = new Table('cc_did_no','didno');
	$did_clause = "id=".$maxid;
	$didno= $did_table -> Get_list($DBHandle, $did_clause, 0);
	//print_r($didno['didno']);
	$final=$didno[0]['didno'];
	$file = "/etc/asterisk/extensions.conf";
	$content = file($file); //Read the file into an array. Line number => line content
	foreach($content as $lineNumber => &$lineContent) { //Loop through the array (the "lines")
    if($lineNumber == 674) { //Remember we start at line 0.
     $lineContent .= "exten => ".$final.',1,Goto(ittech-phone-to-phone,${EXTEN},1)' . PHP_EOL; //Modify the line. (We're adding another line by using PHP_EOL)
    }
  }
   $allContent = implode("", $content); //Put the array back into one string
	file_put_contents($file, $allContent); //Overwrite the file with the new content

// #### FOOTER SECTION
$smarty->display('footer.tpl');
