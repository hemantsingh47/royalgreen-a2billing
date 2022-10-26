<?php
       
include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_card.inc';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}       
$HD_Form -> setDBHandler (DbConnect());

// SECURTY CHECK FOR AGENT
if ($form_action != "list" && isset($id)) {
    if (!empty($id)&& $id>0) {
        $table_agent_security = new Table("cc_card LEFT JOIN cc_card_group ON cc_card.id_group=cc_card_group.id ", " cc_card_group.id_agent");
        $clause_agent_security = "cc_card.id= ".$id;
        $result_security= $table_agent_security -> Get_list ($HD_Form -> DBHandle, $clause_agent_security, null, null, null, null, null, null);
        if ($result_security[0][0] != $_SESSION['agent_id']) {
            Header ("Location: billing_entity_card.php?section=1");
            die();
        }
    }
}
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
                $nameagent=$_SESSION['pr_login'];
                $field_insert = "date, credit, card_id, description, refill_type,agent_id,user_name";
                $value_insert = "now(), '$addcredit', '$id','$description','3','$id_agent','$nameagent'";
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
  echo  $form_action='list';

 // print_r($form_action);
if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);
  //print_r($form_action);
 //$HD_Form -> create_form($form_action, $list, $id=null) ;  

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
<h2><?php echo gettext("Customers");?></h2> 
<script language="JavaScript" src="javascript/card.js"></script>

   <div class="row-fluid"> 
    <div class="form-actions" style="padding: 10px 10px 10px; margin-top: 0px;margin-bottom: 0px;">  
        <?php if ( $form_action == "list" && (!($popup_select>=1)) ) { ?>
        <!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
          
               <div class="" name="top"  style="display: inline;" >
                    <a href="#" id="" target="_self" class="toggle_menu" onclick="toggle_visibility('tohide3')">
                        <div class="btn btn-primary btn-small" onmouseover="this.style.cursor='hand';"> 
                            <i class="uk-icon-search"></i>&nbsp;<?php echo gettext("Refill");?> 
                        </div>
                    </a>
                </div>
                <?php } ?>
                <!--update button ends-->    
                     
                   <a href="#" target="_self" class="toggle_menu" id="button" onclick="toggle_visibility('tohide2')">   
                     <div class="btn btn-primary btn-small" onmouseover="this.style.cursor='hand';">
                      <i class="uk-icon-search"></i>&nbsp;<?php echo gettext("Batch Update");?>
                     </div>
                   </a>  
                <!--search ends-->
        
               <!--update button ends-->   
                <a href="#" target="_self" class="toggle_menu" id="button" onclick="toggle_visibility('tohide1')">    
                    <div class="btn btn-primary btn-small" onmouseover="this.style.cursor='hand';">
                        <i class="uk-icon-search"></i>&nbsp;<?php echo gettext("Search Cards");?>
                    </div>
                 </a>
                 <?php if (!empty($_SESSION['entity_card_selection'])) { ?>&nbsp;(<font style="color:#EE6564;" > <?php echo gettext("search activated"); ?> </font> ) <?php } ?> 
             
             <!--search ends-->   
         
           <?php if (!($popup_select>=1)) { ?>    
            <a href="billing_entity_card_multi.php?stitle=Card&section=".$_SESSION["menu_section"]>
                <div class="btn btn-primary btn-small" onmouseover="this.style.cursor='hand';"> 
                    <i class="uk-icon-users "></i>&nbsp;<?php echo gettext("Generate Customers");?> 
                </div>
            </a>  
                                        
            <a href="billing_entity_card.php?form_action=ask-add&atmenu=card&stitle=Card&section=".$_SESSION["menu_section"]>
                <div class="btn btn-primary btn-small" onmouseover="this.style.cursor='hand';"> 
                    <i class="uk-icon-user-plus"></i>&nbsp;<?php echo gettext("Add Customer");?> 
                </div>
            </a>   
     <?php }?>  
  </div>
</div>
                
    <!-- for rill account  -->
      <div class="row-fluid">
     <div id="tohide3" name="tohide3" class="tohide" style="display:none;"> 
       <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5><?php echo gettext("Refill");?></h5>   
         </div>      
           <form name="theForm" action="billing_entity_card.php?section=1" class="form-horizontal"> 
           <table width="60%" border="0" align="center">  
            <tr>  
            <td>
               <?php echo gettext("CARD ID");?>     : </td>
               <td>
               <input class="md-input" name="choose_list" onfocus="clear_textbox2();" size="15" maxlength="16" value=" "  style="width :60%"> 
                <a href="#" onclick="window.open('billing_entity_card.php?nodisplay=1&popup_select=1&popup_formname=theForm&popup_fieldname=choose_list' , 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a>
                       <?php echo gettext("or");?>
            </td> 
            </tr>
            <tr> 
                <td >
                <?php echo gettext("CARDNUMBER");?>:
                </td>
                <td>
                <input class="md-input"  name="cardnumber" onfocus="clear_textbox();" size="15" maxlength="16" value=" " style="width :60%">
            </td> 
            
        </td> 
        </tr>          
                    <tr>   
                    <td>
                        <?php echo gettext("CREDIT");?>&nbsp;:
                    </td>
                    <td>
                        <input class="md-input"  name="addcredit" size="15" maxlength="6" value="" style="width:60%"> <?php echo strtoupper($A2B->config['global']['base_currency']); ?>
                    </td>
                     </tr>
                     <tr>  
                    <td>
                        <?php echo gettext("DESCRIPTION");?>&nbsp;:
                    </td>
                    <td>
                        <textarea class="form_input_textarea" name="description" cols="50" rows="4"></textarea>
                    </td>
                     </tr>
                     <tr>
                    <td colspan="2" align="center">
                    <input class="btn btn-primary btn-small"     
                TYPE="button" VALUE="<?php echo gettext("ADD CREDIT");?>" onClick="openURL('<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>?form_action=addcredit&stitle=Card_Refilled&current_page=<?php echo $current_page?>&order=<?php echo $order?>&sens=<?php echo $sens?>&id=')">
                   
                    </td> 
                    </tr> 
      </table>
        </form>
      </div>
     </div>
    <!--  end refill-->
      
 <div class="row-fluid">
 <!--start search  -->
  <div id="tohide1" name="tohide1" class="tohide" style="display:none;"> 
    <?php
    // #### CREATE SEARCH FORM
    if ($form_action == "list") {
        $HD_Form -> create_search_form();
    }
    ?> 
    </div>
</div>
     <!-- end search-->
   <!--for batch update-->
   
<div class="row-fluid"> 
  <div id="tohide2" name="tohide2" class="tohide" style="display:none;">  
        <?php

        /********************************* BATCH UPDATE ***********************************/
        if ($form_action == "list" && (!($popup_select>=1))) {

            $FG_TABLE_CLAUSE = "";

        ?>
        <!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->  
                                                                     
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
                      <label class="control-label"><input name="check[upd_inuse]" type="checkbox"   <?php if ($check["upd_inuse"]=="on") echo "checked"?>  />
                      &nbsp;<?php echo gettext("In use"); ?>
                      </label>
                      <div class="controls">
                        <input class="input  " name="upd_inuse" size="10" maxlength="6" value="<?php if (isset($upd_inuse)) echo $upd_inuse; else echo '0';?>" style="width:216px">
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
                                                        <input class="input " style="width:216px"  name="upd_expirationdate" size="20" maxlength="30" <?php echo $comp_date_plus; ?>> <font class="version"><i style="font-size: 12px"><?php echo gettext("(Format YYYY-MM-DD HH:MM:SS)");?></i></font>
                                                        </div>
                                                        </div>
                    <div class="control-group">
                                                    <label class="control-label">
                                                      
                                                              <input name="check[upd_expiredays]" type="checkbox"  <?php if ($check["upd_expiredays"]=="on") echo "checked"?>>
                                                              &nbsp;<?php echo gettext("Expiration days");?>&nbsp;
                                                      </label>
                                                      <div class="controls">
                                                              
                                                            <input class="input " style="width:216px" name="upd_expiredays" size="10" maxlength="6" value="<?php if (isset($upd_expiredays)) echo $upd_expiredays; else echo '0';?>">
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
     
    
    <?php  if ( !USE_REALTIME && isset($_SESSION["is_sip_iax_change"]) && $_SESSION["is_sip_iax_change"]) { ?>
     <div class="row-fluid">
      <table width="<?php echo $HD_Form -> FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0" >
        <TR><TD style="border-bottom: medium dotted #ED2525" align="center"> <?php echo gettext("Changes detected on SIP/IAX Friends");?></TD></TR>
        <TR><FORM NAME="sipfriend">
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
            </b></font></td></FORM>
        </TR>
</table>
</div>
      <?php  }  
        ?>
   
  
 <?php

}else if (!($popup_select>=1)) { echo $CC_help_create_customer; }


if (isset($update_msg) && strlen($update_msg)>0) { echo "<br/><center>$update_msg</center>";   }



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);
if (!$popup_select && $form_action == "ask-add") {
?>
 
    <script language="javascript">
    public function submitform()
    {
        document.cardform.submit();
    }
    </script>
    <h2><?php echo gettext("Add Customer");?></h2>
    <form action="billing_entity_card.php?form_action=ask-add&section=1" method="post" name="cardform">
    
        <div class="md-card">
            <div class="md-card-content" style="text-align: center;">
                    <div class="uk-width-medium-3-3">
                          <center>  <table>
                                <tr>
                                    <td><?php echo gettext("Change the Account Number Length")?> :</td>
                                    <td> <div class="md-input-wrapper md-input-filled">
                                    <select name="cardnumberlenght_list" size="1" class="md-input" onChange="submitform()" data-uk-tooltip="pos:'middle'" title="Select your card length">
                                <?php foreach ($A2B -> cardnumber_range as $value) { ?>
                                    <option value='<?php echo $value ?>'
                                    <?php if ($value == $cardnumberlenght_list) echo "selected";
                                    ?>> <?php echo $value." ".gettext("Digits");?> </option>
                                <?php } ?>
                                </select> 
                               <span class="md-input-bar "></span> </div>
                               </td>
                                </tr>
                            </table>  
                          </center>
                    </div>
           </div>
        </div>
 
    </form>
                   
        
<?php
}
                             
if ($form_action=='ask-edit') {
    echo '<div class="md-card"><div class="md-card-content">'.Display_Login_Button ($HD_Form -> DBHandle, $id).'</div></div>';
}

$HD_Form -> create_form($form_action, $list, $id=null) ;  
 echo'</div>'; 
      
// Code for the Export Functionality
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";



// #### FOOTER SECTION
//if (!($popup_select>=1)) $smarty->display('footer.tpl');
$smarty->display('footer.tpl');
