<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_card.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array('popup_select', 'popup_formname', 'popup_fieldname', 'upd_inuse', 'upd_status', 'upd_language', 'upd_tariff', 'upd_credit', 'upd_credittype', 'upd_simultaccess', 'upd_currency', 'upd_typepaid', 'upd_creditlimit', 'upd_enableexpire', 'upd_expirationdate', 'upd_expiredays', 'upd_runservice', 'upd_runservice', 'batchupdate', 'check', 'type', 'mode', 'addcredit', 'cardnumber','description'));

// CHECK IF REQUEST OF BATCH UPDATE
if ($batchupdate == 1 && is_array($check)) {

    $HD_Form->prepare_list_subselection('list');

    // Array ( [upd_simultaccess] => on [upd_currency] => on )
    $loop_pass = 0;
    $SQL_UPDATE = '';
    foreach ($check as $ind_field => $ind_val) {
        //echo "<br>::> $ind_field -";
        $myfield = substr($ind_field,4);
        if ($loop_pass!=0) $SQL_UPDATE.=',';

        $authorized_field = array("upd_inuse", "upd_status", "upd_language", "upd_simultaccess", "upd_currency", "upd_enableexpire",
                            "upd_expirationdate", "upd_expiredays", "upd_runservice");

        if (in_array($ind_field, $authorized_field)) {
            // Standard update mode
            if (!isset($mode["$ind_field"]) || $mode["$ind_field"]==1) {
                if (!isset($type["$ind_field"])) {
                    $SQL_UPDATE .= " $myfield='".$$ind_field."'";
                } else {
                    $SQL_UPDATE .= " $myfield='".$type["$ind_field"]."'";
                }
            // Mode 2 - Equal - Add - Subtract
            } elseif ($mode["$ind_field"]==2) {
                if (!isset($type["$ind_field"])) {
                    $SQL_UPDATE .= " $myfield='".$$ind_field."'";
                } else {
                    if ($type["$ind_field"] == 1) {
                        $SQL_UPDATE .= " $myfield='".$$ind_field."'";
                    } elseif ($type["$ind_field"] == 2) {
                        $SQL_UPDATE .= " $myfield = $myfield +'".$$ind_field."'";
                    } else {
                        $SQL_UPDATE .= " $myfield = $myfield -'".$$ind_field."'";
                    }
                }
            }
        }
        $loop_pass++;
    }

    $SQL_UPDATE = "UPDATE $HD_Form->FG_TABLE_NAME SET $SQL_UPDATE";
    if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) {
        $SQL_UPDATE .= ' WHERE ';
        $SQL_UPDATE .= $HD_Form->FG_TABLE_CLAUSE;
    }

    if (! $res = $HD_Form -> DBHandle -> Execute($SQL_UPDATE)) {
        $update_msg = '<center><font color="red"><b>'.gettext('Could not perform the batch update!').'</b></font></center>';
    } else {
        $update_msg = '<center><font color="green"><b>'.gettext('The batch update has been successfully perform!').'</b></font></center>';
    }

}
/********************************* END BATCH UPDATE ***********************************/

if (($form_action == "addcredit") && ($addcredit > 0) && ($id > 0 || $cardnumber > 0)) {

    $instance_table = new Table("cc_card", "username, id");

    if ($cardnumber>0) {
        /* CHECK IF THE CARDNUMBER IS ON THE DATABASE */
        $FG_TABLE_CLAUSE_card = "username='".$cardnumber."'";
        $list_tariff_card = $instance_table -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE_card, null, null, null, null, null, null);
        if ($cardnumber == $list_tariff_card[0][0]) $id = $list_tariff_card[0][1];
    }

    if ($id > 0) {

        $instance_check_card_agent = new Table("cc_card LEFT JOIN cc_card_group ON cc_card.id_group=cc_card_group.id", " cc_card_group.id_agent");
        $FG_TABLE_CLAUSE_check = "cc_card.id= ".$id;
        $list_check= $instance_check_card_agent -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE_check, null, null, null, null, null, null);
        if ($list_check[0][0] == $_SESSION['agent_id']) {

            //check if enought credit
            $instance_table_agent = new Table("cc_agent", "credit, currency");
            $FG_TABLE_CLAUSE_AGENT = "id = ".$_SESSION['agent_id'] ;
            $agent_info = $instance_table_agent -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE_AGENT, null, null, null, null, null, null);
            $credit_agent = $agent_info[0][0];
            if ($credit_agent >= $addcredit) {
               //Substract credit for agent
                $param_update_agent = "credit = credit - '".$addcredit."'";
                $instance_table_agent -> Update_table ($HD_Form -> DBHandle, $param_update_agent, $FG_TABLE_CLAUSE_AGENT, $func_table = null);

               // Add credit to Customer
                $param_update .= "credit = credit + '".$addcredit."'";
                if ($HD_Form->FG_DEBUG == 1)  echo "<br><hr> $param_update";

                $FG_EDITION_CLAUSE = " id='$id'" ; // AND id_agent=".$_SESSION['agent_id'];

                if ($HD_Form->FG_DEBUG == 1)  echo "<br>-----<br>$param_update<br>$FG_EDITION_CLAUSE";
                $instance_table = new Table("cc_card", "username, id");
                $instance_table -> Update_table ($HD_Form -> DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);

                $update_msg ='<b><font color="green">'.gettext("Refill executed ").'!</font></b>';
                $id_agent = $_SESSION['agent_id'];
                $field_insert = "date, credit, card_id, description, refill_type,agent_id";
                $value_insert = "now(), '$addcredit', '$id','$description','3','$id_agent'";
                $instance_sub_table = new Table("cc_logrefill", $field_insert);
                $id_refill = $instance_sub_table -> Add_table ($HD_Form -> DBHandle, $value_insert, null, null,'id');

                $agent_table = new Table("cc_agent", "commission");

                $agent_clause = "id = ".$id_agent;
                $result_agent= $agent_table -> Get_list($HD_Form -> DBHandle,$agent_clause);

                if (is_array($result_agent) && is_numeric($result_agent[0]['commission']) && $result_agent[0]['commission']>0) {
                    $field_insert = "id_payment, id_card, amount,description,id_agent";
                    $commission = a2b_round($addcredit * ($result_agent[0]['commission']/100));
                    $description_commission = gettext("GENERATED COMMISSION OF AN CUSTOMER REFILLED BY AN AGENT!");
                    $description_commission.= "\nID CARD : ".$id;
                    $description_commission.= "\nID REFILL : ".$id_refill;
                    $description_commission.= "\REFILL AMOUNT: ".$addcredit;
                    $description_commission.= "\nCOMMISSION APPLIED: ".$result_agent[0]['commission'];
                    $value_insert = "'-1', '$id', '$commission','$description_commission','$id_agent'";
                    $commission_table = new Table("cc_agent_commission", $field_insert);
                    $id_commission = $commission_table -> Add_table ($HD_Form -> DBHandle, $value_insert, null, null,"id");
                    $table_agent = new Table('cc_agent');
                    $param_update_agent = "com_balance = com_balance + '".$commission."'";
                    $clause_update_agent = " id='".$id_agent."'";
                    $table_agent -> Update_table ($HD_Form -> DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
                }


                if (!$id_refill) {
                    $update_msg ="<b>".$instance_sub_table -> errstr."</b>";
                }

            } else {

                $currencies_list = get_currencies();

                if (!isset($currencies_list[strtoupper($agent_info [0][1])][2]) || !is_numeric($currencies_list[strtoupper($agent_info [0][1])][2]))
                    $mycur = 1;
                else
                    $mycur = $currencies_list[strtoupper($agent_info [0][1])][2];

                $credit_cur = $agent_info[0][0] / $mycur;
                $credit_cur = round($credit_cur,3);

                $update_msg ='<b> <font color="red">'.gettext("You don't have enough credit to do this refill. You have ").$credit_cur.' '.$agent_info[0][1].' </font></b>';
            }

        } else {
                $update_msg ='<b><font color="red">'.gettext("Impossible to refill this card ").'</font></b>';
        }
    }
}

if ($form_action == "addcredit")
    $form_action='list';


if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl');

if ($popup_select) {
?>

<SCRIPT LANGUAGE="javascript">
<!-- Begin
function sendValue(selvalue)
{
    window.opener.document.<?php echo $popup_formname ?>.<?php echo $popup_fieldname ?>.value = selvalue;
    window.close();
}
// End -->
</script>
<?php
}

// #### HELP SECTION
if ($form_action=='list' && !($popup_select>=1)) {
    echo $CC_help_list_customer;

?>

 <h3><?php echo gettext("Customers");?></h3><hr/>
<div class="row-fluid">
      <div class="span12">
  <p style="display: inline-block;">
        <?php if ( $form_action == "list" && (!($popup_select>=1)) ) { ?>
                    
                <a href="#"   onclick="toggle_visibility('tohide2')" class="btn btn-label-sucess btn-bold" onmouseover="this.style.cursor='hand';"><i class="uk-icon-search"></i>&nbsp;<?php echo gettext("Batch Update");?> 
                    
                </a>
                
           
            <?php } ?>
               
             <a href="#" id="" target="_self"  onclick="toggle_visibility('tohide1')" class="btn btn-primary  btn-small btn-wave-light" onmouseover="this.style.cursor='hand';">
                   <i class="uk-icon-search"></i>&nbsp;<?php echo gettext("Search Customers");?> 
                   <?php if (!empty($_SESSION['entity_card_selection'])) { ?>&nbsp;(<font style="color:#EE6564;" > <?php echo gettext("search activated"); ?> </font> ) <?php } ?> 
             </a>
             <?php if (!($popup_select>=1)) { ?>
                            <a href="billing_entity_card_multi.php?stitle=Card&section=<?php echo $_SESSION['menu_section']; ?>" class="btn btn-primary  btn-small btn-wave-light" onmouseover="this.style.cursor='hand';">
                                 
                                    <i class="uk-icon-users "></i>&nbsp;<?php echo gettext("Generate Customers");?> 
                               
             </a>
             <?php }?>
             <?php if (!($popup_select>=1)) { ?>
                            <a href="billing_entity_card.php?form_action=ask-add&atmenu=card&stitle=Card&section=<?php echo $_SESSION['menu_section'];?>" class="btn btn-primary  btn-small btn-wave-light" onmouseover="this.style.cursor='hand';" >
                                
                                    <i class="uk-icon-user-plus"></i>&nbsp;<?php echo gettext("Add Customer");?> 
                               
                            </a>
                         <?php }?>
                   
  </p>          
 </div>
 </div>     
        <!--search-->
        
   <div class="row-fluid">
    <div id="tohide1" name="tohide1" class="tohide" style="display:none;">
        <?php

        // #### CREATE SEARCH form
        if ($form_action == "list") {
            $HD_Form -> create_search_form();
        }
        ?>
        </div>
   </div>     
   <div class="row-fluid">     
        
      
     <div id="tohide2" name="tohide2" class="tohide" style="display:none;">   
       
        <?php

/********************************* BATCH UPDATE ***********************************/
    if ( $form_action == "list" && (!($popup_select>=1)) ) 
    {

        $instance_table_tariff = new Table("cc_tariffgroup", "id, tariffgroupname");
        $FG_TABLE_CLAUSE = "";
        $list_tariff = $instance_table_tariff -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE, "tariffgroupname", "ASC", null, null, null, null);
        $nb_tariff = count($list_tariff);

        $instance_table_group=  new Table("cc_card_group"," id, name ");
        $list_group = $instance_table_group  -> Get_list ($HD_Form ->DBHandle, $FG_TABLE_CLAUSE, "name", "ASC", null, null, null, null);

        $instance_table_agent=  new Table("cc_agent"," id, login ");
        $list_agent = $instance_table_agent  -> Get_list ($HD_Form ->DBHandle, $FG_TABLE_CLAUSE, "login", "ASC", null, null, null, null);

        $instance_table_seria=  new Table("cc_card_seria"," id, name");
        $list_seria  = $instance_table_seria -> Get_list ($HD_Form ->DBHandle, $FG_TABLE_CLAUSE, "name", "ASC", null, null, null, null);

        $list_refill_type=Constants::getRefillType_List();
        $list_refill_type["-1"]=array("NO REFILL","-1");

        $instance_table_country = new Table("cc_country", " countrycode, countryname ");
        $list_country = $instance_table_country->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "countryname", "ASC", null, null, null, null);

    ?>
    <style type="text/css">
     input, textarea, .uneditable-input 
     {
        width: 217px;  
     }
	 a {
    color: #;
       }
    </style> 
     <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5><?php echo gettext("Batch Update");?> <b>&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?></b> <?php echo gettext("cards selected!"); ?>
                                                &nbsp;<?php echo gettext("Use the options below to batch update the selected cards.");?>
                                               </h5>
        </div>
        <div class="widget-content nopadding">
           <form name="updateForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>" method="post" class="form-horizontal">
                         <INPUT type="hidden" name="batchupdate" value="1">
                        <?php
                            if ($HD_Form->FG_CSRF_STATUS == true) {
                        ?>
                            <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
                            <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
                        <?php
                            }
                ?>
                                                    
                    <div class="control-group">
                      <label class="kt-checkbox kt-checkbox--brand"><input name="check[upd_inuse]" type="checkbox"   <?php if ($check["upd_inuse"]=="on") echo "checked"?>  />
                      &nbsp;<?php echo gettext("In use"); ?>
                      </label>
                      <div class="controls">
                        <input class="input  "   name="upd_inuse" size="10" maxlength="6" value="<?php if (isset($upd_inuse)) echo $upd_inuse; else echo '0';?>">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label">
                       <input name="check[upd_status]" type="checkbox"  <?php if ($check["upd_status"]=="on") echo "checked"?> >
                      &nbsp;<?php echo gettext("Status"); ?>
                      </label>
                      <div class="controls">
                        <select NAME="upd_status" size="1" class="input ">
                                            <?php foreach ($cardstatus_list as $key => $cur_value) { ?>
                                                <option value='<?php echo $cur_value[1] ?>' <?php if ($upd_status==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>
                                            <?php } ?>
                                            </select>
                      </div>
                    </div>
                    <div class="control-group">
                                    <label class="control-label">
                                        <input name="check[upd_language]" type="checkbox"  <?php if ($check["upd_language"]=="on") echo "checked"?>>
                                              &nbsp;<?php echo gettext("Language");?>
                                              </label>
                                    <div class="controls">          
                                            <select NAME="upd_language" size="1" class="input ">
                                            <?php foreach ($language_list as $key => $cur_value) { ?>
                                                <option value='<?php echo $cur_value[1] ?>' <?php if ($upd_language==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>
                                            <?php } ?>
                                        </select>
                                       </div>
                                </div>       
                    <div class="control-group">
                                                    <label class="control-label">          
                                                          <input name="check[upd_tariff]" type="checkbox"  <?php if ($check["upd_tariff"]=="on") echo "checked"?> >
                                                           &nbsp;<?php echo gettext("Tariff");?>&nbsp;
                                                </label>
                                                <div class="controls"> 
                                                            <select NAME="upd_tariff" size="1" class="input">
                                                                <?php foreach ($list_tariff as $recordset) { ?>
                                                                    <option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_tariff==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
                                                                <?php } ?>
                                                            </select>
                                                            
                                                      </div>
                                                </div>
                    <div class="control-group">
                                                    <label class="control-label">    
                                                      
                                                                <input name="check[upd_credit]" type="checkbox"  <?php if ($check["upd_credit"]=="on") echo "checked"?>>
                                                                <input name="mode[upd_credit]" type="hidden" value="2">
                                                           &nbsp;<?php echo gettext("Credit");?>&nbsp;
                                                      </label>
                                                      <div class="controls">
                                                                <input class="input" name="upd_credit" size="10" maxlength="10"  value="<?php if (isset($upd_credit)) echo $upd_credit; else echo '0';?>">
                                                                    <br>
                                                                    <font class="version">
                                                                    <input type="radio"  NAME="type[upd_credit]" value="1" <?php if ((!isset($type["upd_credit"]))|| ($type["upd_credit"]==1) ) {?>checked<?php }?>><?php echo gettext("Equals");?>
                                                                    <input type="radio"  NAME="type[upd_credit]" value="2" <?php if ($type["upd_credit"]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                                                                    <input type="radio"  NAME="type[upd_credit]" value="3" <?php if ($type["upd_credit"]==3) {?>checked<?php }?>> <?php echo gettext("Subtract");?>
                                                                    </font>
                                                               </div>     
                                                 </div>               
                    <div class="control-group">
                                                    <label class="control-label">             
                                                                &nbsp;<?php echo gettext("Refill");?>
                                                     </label>          
                                                               <div class="controls"> 
                                                                <select NAME="upd_refill_type" size="1" class="input ">
                                                                <?php foreach ($list_refill_type as $recordset) { ?>
                                                                    <option class=input value='<?php echo $recordset[1]?>'  <?php if ($upd_refill_type==$recordset[1]) echo 'selected="selected"'?>><?php echo $recordset[0]?></option>
                                                                <?php } ?>
                                                            </select> 
                                                            </div>
                                                            </div>
                    <div class="control-group">
                                                    <label class="control-label"> 
                                                            <?php echo gettext("Description");?>
                                                            </label>
                                                             <div class="controls">
                                                             <input class="input " name="upd_description"  size="20" maxlength="20"  value="<?php if (isset($upd_description)) echo $upd_description;?>"><br/>
                                                            
                                                      </div>
                                                      </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                             <input name="check[upd_simultaccess]" type="checkbox"  <?php if ($check["upd_simultaccess"]=="on") echo "checked"?>>
                                                              &nbsp;<?php echo gettext("Access");?>&nbsp;
                                                      </label>
                                                      <div class="controls">
                                                            <select NAME="upd_simultaccess" size="1" class="input ">
                                                                <option value='0'  <?php if ($upd_simultaccess==0) echo 'selected="selected"'?>><?php echo gettext("INDIVIDUAL ACCESS");?></option>
                                                                <option value='1'  <?php if ($upd_simultaccess==1) echo 'selected="selected"'?>><?php echo gettext("SIMULTANEOUS ACCESS");?></option>
                                                        </select>
                                                        </div>
                                                        </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                              <input name="check[upd_currency]" type="checkbox"  <?php if ($check["upd_currency"]=="on") echo "checked"?>>
                                                              &nbsp;<?php echo gettext("Currency");?>&nbsp;
                                                      </label>
                                                      <div class="controls">
                                                            <select NAME="upd_currency" size="1" class="input ">
                                                            <?php
                                                                foreach ($currencies_list as $key => $cur_value) {
                                                            ?>
                                                                <option value='<?php echo $key ?>'  <?php if ($upd_currency==$key) echo 'selected="selected"'?>><?php echo $cur_value[1].' ('.$cur_value[2].')' ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        </div>
                                                        </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                              <input name="check[upd_creditlimit]" type="checkbox"  <?php if ($check["upd_creditlimit"]=="on") echo "checked"?>>
                                                              <input name="mode[upd_creditlimit]" type="hidden" value="2">
                                                              &nbsp;<?php echo gettext("Credit limit");?>&nbsp;
                                                      </label>
                                                      <div class="controls">
                                                                 <input class="input " name="upd_creditlimit" size="10" maxlength="10"  value="<?php if (isset($upd_creditlimit)) echo $upd_creditlimit; else echo '0';?>" >
                                                                    <br>
                                                                    <font class="version">
                                                                    <input type="radio"  NAME="type[upd_creditlimit]" value="1" <?php if ((!isset($type[upd_creditlimit]))|| ($type[upd_creditlimit]==1) ) {?>checked<?php }?>> <?php echo gettext("Equals");?>
                                                                    <input type="radio"  NAME="type[upd_creditlimit]" value="2" <?php if ($type[upd_creditlimit]==2) {?>checked<?php }?>><?php echo gettext("Add");?>
                                                                    <input type="radio"  NAME="type[upd_creditlimit]" value="3" <?php if ($type[upd_creditlimit]==3) {?>checked<?php }?>> <?php echo gettext("Subtract");?>
                                                                    </font>
                                                                 
                                                      </div>   
                                                      </div>   
                    <div class="control-group">
                                                    <label class="control-label">      
                                                      <input name="check[upd_enableexpire]" type="checkbox"  <?php if ($check["upd_enableexpire"]=="on") echo "checked"?>>
                                                      &nbsp;<?php echo gettext("Enable expire");?>&nbsp;
                                                      </label>
                                                      <div class="controls">
                                                            <select name="upd_enableexpire"  >
                                                                <option value="0"  <?php if ($upd_enableexpire==0) echo 'selected="selected"'?>> <?php echo gettext("NO EXPIRY");?></option>
                                                                <option value="1"  <?php if ($upd_enableexpire==1) echo 'selected="selected"'?>> <?php echo gettext("EXPIRE DATE");?></option>
                                                                <option value="2"  <?php if ($upd_enableexpire==2) echo 'selected="selected"'?>> <?php echo gettext("EXPIRE DAYS SINCE FIRST USE");?></option>
                                                                <option value="3"  <?php if ($upd_enableexpire==3) echo 'selected="selected"'?>> <?php echo gettext("EXPIRE DAYS SINCE CREATION");?></option>
                                                            </select>
                                                           
                                                      </div>
                                                      </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                      <input name="check[upd_expirationdate]" type="checkbox"  <?php if ($check["upd_expirationdate"]=="on") echo "checked"?>>
                                                            <?php
                                                                $begin_date = date("Y");
                                                                $begin_date_plus = date("Y") + 10;
                                                                $end_date = date("-m-d H:i:s");
                                                                $comp_date = "value='".$begin_date.$end_date."'";
                                                                $comp_date_plus = "value='".$begin_date_plus.$end_date."'";
                                                            ?>
                                                             &nbsp;<?php echo gettext("Expiry date");?>&nbsp;
                                                    </label>
                                                    <div class="controls">
                                                        <input class="input "  name="upd_expirationdate" size="20" maxlength="30" <?php echo $comp_date_plus; ?>> <font class="version"><i style="font-size: 12px"><?php echo gettext("(Format YYYY-MM-DD HH:MM:SS)");?></i></font>
                                                        </div>
                                                        </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                      
                                                              <input name="check[upd_expiredays]" type="checkbox"  <?php if ($check["upd_expiredays"]=="on") echo "checked"?>>
                                                              &nbsp;<?php echo gettext("Expiration days");?>&nbsp;
                                                      </label>
                                                      <div class="controls">
                                                              
                                                            <input class="input "  name="upd_expiredays" size="10" maxlength="6" value="<?php if (isset($upd_expiredays)) echo $upd_expiredays; else echo '0';?>">
                                                      </div> 
                                                      </div> 
                    <div class="control-group">
                                                    <label class="control-label">      
                                                            <input name="check[upd_runservice]" type="checkbox"  <?php if ($check["upd_runservice"]=="on") echo "checked"?>>
                                                            &nbsp;<?php echo gettext("Run service");?>&nbsp;
                                                            </label>
                                                            <div class="controls">
                                                              <font class="version">
                                                              <input type="radio"  NAME="type[upd_runservice]" value="1" <?php if ((!isset($type[upd_runservice]))|| ($type[upd_runservice]=='1') ) {?>checked<?php }?>>
                                                              <?php echo gettext("Yes");?> 
                                                              <input type="radio"  NAME="type[upd_runservice]" value="0" <?php if ($type[upd_runservice]=='0') {?>checked<?php }?>><?php echo gettext("No");?>
                                                              </font>
                                                             
                                                      </div>
                                                      </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                            <input name="check[upd_id_group]" type="checkbox"  <?php if ($check["upd_id_group"]=="on") echo "checked"?> >
                                                            &nbsp;<?php echo gettext("Group this batch belongs to");?>&nbsp;</label>
                                                            <div class="controls">
                                                            <select NAME="upd_id_group" size="1" class="input ">
                                                                    <?php
                                                                     foreach ($list_group as $recordset) {
                                                                    ?>
                                                                            <option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_id_group==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
                                                                    <?php } ?>
                                                            </select>
                                                            
                                                      </div>
                                                      </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                            <input name="check[upd_discount]" type="checkbox"  <?php if ($check["upd_discount"]=="on") echo "checked"?> >
                                                            &nbsp;<?php echo gettext("Set discount to");?>&nbsp;</label>
                                                            <div class="controls">
                                                          <select NAME="upd_discount" size="1" class="input ">
                                                              <option class=input value="0" ><?php echo gettext("NO DISCOUNT");?></option>
                                                              <?php for ($i=1;$i<99;$i++) { ?>
                                                                    <option class=input value='<?php echo $i;?>'  <?php if ($upd_discount==$i) echo 'selected="selected"';echo '>'. $i; ?>%</option>
                                                              <?php } ?>
                                                          </select>
                                                          
                                                      </div>
                                                      </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                            <input name="check[upd_id_seria]" type="checkbox"  <?php if ($check["upd_id_seria"]=="on") echo "checked"?> >
                                                            &nbsp;<?php echo gettext("Move to Seria");?>&nbsp;</label>
                                                       <div class="controls">     
                                                            <select NAME="upd_id_seria" size="1" class="input ">
                                                                    <?php
                                                                     foreach ($list_seria as $recordset) {
                                                                    ?>
                                                                            <option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_id_seria==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
                                                                    <?php } ?>
                                                            </select>
                                                       
                                                      </div>
                                                      </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                                <input name="check[upd_vat]" type="checkbox"  <?php if ($check["upd_vat"]=="on") echo "checked"?>>
                                                                &nbsp;<?php echo gettext("VAT"); ?>&nbsp;</label>
                                                                <div class="controls">
                                                                <input class="input  "  name="upd_vat" size="10" maxlength="6" value="<?php if (isset($upd_vat)) echo $upd_vat;?>">
                                                             
                                                      </div> 
                                                      </div> 
                    <div class="control-group">
                                                    <label class="control-label">
                                                            <input name="check[upd_country]" type="checkbox"  <?php if ($check["upd_country"]=="on") echo "checked"?> >
                                                        &nbsp;<?php echo gettext("Country");?>&nbsp;</label>
                                                        <div class="controls">
                                                            <select NAME="upd_country" size="1" class="input ">
                                                                    <?php
                                                                     foreach ($list_country as $recordset) {
                                                                    ?>
                                                                            <option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_country==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
                                                                    <?php } ?>
                                                            </select>
                                                            
                                                      </div>
                                                      </div>
                    <div class="control-group">
                        <div class="controls">
                            <input class="btn btn-primary"  value=" <?php echo gettext("BATCH UPDATE CARD");?>  " type="submit">
                        </div>
                                                    
                                                            
                                                    </div> 
                </form>
           </div>
        </div>
     <!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
        <?php
        } // END if ($form_action == "list")
        ?>
        </div> 
                                                
  </div>                                              
                                                
  <div class="row-fluid">                                              
                                                  
                <?php  if (!USE_REALTIME && isset($_SESSION["is_sip_iax_change"]) && $_SESSION["is_sip_iax_change"]) { ?>
              <table width="<?php echo $HD_Form -> FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0" >
                <TR><TD style="border-bottom: medium dotted #ED2525" align="center"> <?php echo gettext("Changes detected on SIP/IAX Friends");?></TD></TR>
                <TR>
                <form NAME="sipfriend">
                    <?php
                        if ($HD_Form->FG_CSRF_STATUS == true) {
                    ?>
                        <INPUT type="hidden" name="<?php echo $HD_Form->FG_form_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_form_UNIQID; ?>" />
                        <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
                    <?php
                        }
                    ?>
                    
                    <td height="31" class="bgcolor_013" style="padding-left: 5px; padding-right: 3px;" align="center">
                    <font color=white><b>
                    <?php  if ( isset($_SESSION["is_sip_changed"]) && $_SESSION["is_sip_changed"] ) { ?>
                    SIP : <input class="form_input_button"  TYPE="button" VALUE="<?php echo gettext("GENERATE ADDITIONAL_A2BILLING_SIP.CONF");?>"
                    onClick="self.location.href='./CC_generate_friend_file.php?atmenu=sipfriend';">
                    <?php }
                    if ( isset($_SESSION["is_iax_changed"]) && $_SESSION["is_iax_changed"] ) { ?>
                    IAX : <input class="form_input_button"  TYPE="button" VALUE="<?php echo gettext("GENERATE ADDITIONAL_A2BILLING_IAX.CONF");?>"
                    onClick="self.location.href='./CC_generate_friend_file.php?atmenu=iaxfriend';">
                    <?php } ?>
                    </b></font></td></form>
                </TR>
        </table> 
        <?php  } // endif is_sip_iax_change
        ?>
</div>

<?php

}elseif (!($popup_select>=1)) echo $CC_help_create_customer;

if (isset($update_msg) && strlen($update_msg)>0) {echo $update_msg;}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);
if (!$popup_select && $form_action == "ask-add") {
?>


    <script language="javascript">
    function submitform()
    {
        document.cardform.submit();
    }
    </script>
	<h2 class="kt-portlet__head-label"><?php echo gettext("Add Customer");?></h2>
	<div class="kt-portlet">
		<div class="kt-form">
			<form action="billing_entity_card.php" method="get" name="cardform" class="kt-form">
				<div class="kt-portlet">
	
	<!--begin::Form-->
	<form class="kt-form kt-form--label-right">
		<div class="kt-portlet__body">
			<div class="form-group row">
			<input type="hidden" name="form_action" value="ask-add">
				<input type="hidden" name="section" value="1">
				<label for="example-text-input" class="col-2 col-form-label"><?php echo gettext("Change the Account Number Length")?>&nbsp; : &nbsp;</label>
				<div class="col-10">
					<div class="controls">
						<select class="form-control form-control-sm" id="exampleSelects" name="cardnumberlenght_list" size="1" class="input" onChange="submitform()" title="Select your card length">
							<?php foreach ($A2B -> cardnumber_range as $value) { ?>
								<option value='<?php echo $value ?>'
								<?php if ($value == $cardnumberlenght_list) echo "selected";
								?>> <?php echo $value." ".gettext("Digits");?> </option>
							<?php } ?>
						</select> 
					</div>
					</div>
			</div>
			
			
			</form>

		</div>
	</div>
<?php
}

if ($form_action=='ask-edit') {
    echo '<div class="row-fluid"><div class="widget-box">'.Display_Login_Button ($HD_Form -> DBHandle, $id).'</div></div>';
}

$HD_Form -> create_form ($form_action, $list, $id=null) ;
?>
     
<?php
// Code for the Export Functionality
 $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";

if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";

if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";
   //echo  $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR];
    ?>
<?php

    $smarty->display('footer.tpl');  
 
