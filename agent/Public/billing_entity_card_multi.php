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

$HD_Form -> FG_FILTER_SEARCH_FORM = false;
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_OTHER_BUTTON1 = false;
$HD_Form -> FG_OTHER_BUTTON2 = false;
$HD_Form -> FG_FILTER_APPLY = false;
$HD_Form -> FG_LIST_ADDING_BUTTON1 = false;
$HD_Form -> FG_LIST_ADDING_BUTTON2 = false;

getpost_ifset(array('nb_to_create', 'creditlimit', 'cardnum', 'choose_tariff', 'gen_id', 'cardnum', 'choose_simultaccess',
    'choose_currency', 'choose_typepaid', 'creditlimit', 'enableexpire', 'expirationdate', 'expiredays', 'runservice', 'sip', 'iax',
    'cardnumberlenght_list', 'tag', 'id_group', 'discount', 'id_seria'));

$HD_Form -> setDBHandler (DbConnect());

$nb_error = 0;
$msg_error = '';
$group_error = false;
$tariff_error=false;
$number_error=false;
$expdate_error=false;
$expday_error=false;

if ($action=="generate") {
    if (!is_numeric($id_group) || $id_group<1) {
        $nb_error++;
        $group_error=true;
        $msg_error = gettext("- Choose a GROUP for the customers!");
    }
    if (!is_numeric($choose_tariff) || $choose_tariff<1) {
        $nb_error++;
        $tariff_error=true;
        if(!empty($msg_error)) $msg_error .= "<br/>";
        $msg_error .= gettext("- Choose a CALL PLAN for the customers!");
    }
    if (!is_numeric($expiredays) || $expiredays<0) {
        $nb_error++;
        $expday_error=true;
        if(!empty($msg_error)) $msg_error .= "<br/>";
        $msg_error .= gettext("- Choose an EXPIRATIONS DAYS  equal or higher than 0 for the customers!");
    }
    if (empty($expirationdate) || strtotime($expirationdate)===FALSE) {
        $nb_error++;
        $expdate_error=true;
        if(!empty($msg_error)) $msg_error .= "<br/>";
        $msg_error .= gettext("- EXPIRATION DAY inserted is invalid, it must respect the date format YYYY-MM-DD HH:MM:SS (time is optional) !");
    }
    if (!is_numeric($nb_to_create) || $nb_to_create < 1 || $nb_to_create > 100) {
        $nb_error++;
        $number_error = true;
        if (!empty ($msg_error))
            $msg_error .= "<br/>";
        $msg_error .= gettext("- Choose the number of customers (b/w 0-100) that you want generate!");
    }
}
$nbcard = $nb_to_create;
if ($nbcard>0 && $action=="generate" && $nb_error==0) {

    check_demo_mode();

    $FG_ADITION_SECOND_ADD_TABLE  = "cc_card";
    $FG_ADITION_SECOND_ADD_FIELDS = "username, useralias, credit, tariff, activated, lastname, firstname, email, address, city, state, country, zipcode, phone, simultaccess, currency, typepaid , creditlimit, enableexpire, expirationdate, expiredays, uipass, runservice, tag,id_group, discount, id_seria";

    if (DB_TYPE != "postgres") {
        $FG_ADITION_SECOND_ADD_FIELDS .= ",creationdate ";
    }

    $FG_TABLE_SIP_NAME="cc_sip_buddies";
    $FG_TABLE_IAX_NAME="cc_iax_buddies";

    $FG_QUERY_ADITION_SIP_IAX_FIELDS = "name, accountcode, regexten, amaflags, callerid, context, dtmfmode, host, type, username, allow, secret, id_cc_card, nat,  qualify";
    if (isset($sip)) {
        $FG_ADITION_SECOND_ADD_FIELDS .= ", sip_buddy";
        $instance_sip_table = new Table($FG_TABLE_SIP_NAME, $FG_QUERY_ADITION_SIP_IAX_FIELDS);
    }

    if (isset($iax)) {
        $FG_ADITION_SECOND_ADD_FIELDS .= ", iax_buddy";
        $instance_iax_table = new Table($FG_TABLE_IAX_NAME, $FG_QUERY_ADITION_SIP_IAX_FIELDS);
    }

    if (isset($sip) ||  isset($iax)) {
        $list_names = explode(",",$FG_QUERY_ADITION_SIP_IAX);
        $type = FRIEND_TYPE;
        $allow = FRIEND_ALLOW;
        $context = FRIEND_CONTEXT;
        $nat = FRIEND_NAT;
        $amaflags = FRIEND_AMAFLAGS;
        $qualify = FRIEND_QUALIFY;
        $host = FRIEND_HOST;
        $dtmfmode = FRIEND_DTMFMODE;
    }

    $instance_sub_table = new Table($FG_ADITION_SECOND_ADD_TABLE, $FG_ADITION_SECOND_ADD_FIELDS);
    $gen_id = time();
    $_SESSION["IDfilter"]=$gen_id;

    $creditlimit = is_numeric($creditlimit) ? $creditlimit : 0;
    //initialize refill parameter
    $description_refill = gettext("CREATION CARD REFILL");
    $field_insert_refill = " credit,card_id, description";
    $instance_refill_table = new Table("cc_logrefill", $field_insert_refill);

    for ($k=0; $k<$nbcard; $k++) {
        $arr_card_alias = gen_card_with_alias("cc_card", 0, $cardnumberlenght_list);
        $cardnum = $arr_card_alias[0];
        $useralias = $arr_card_alias[1];
        $addcredit=0;
        $passui_secret = MDP_NUMERIC(5).MDP_STRING(10).MDP_NUMERIC(5);
        $FG_ADITION_SECOND_ADD_VALUE  = "'$cardnum', '$useralias', '$addcredit', '$choose_tariff', 't', '$gen_id', '', '', '', '', '', '', '', '', $choose_simultaccess, '$choose_currency', $choose_typepaid, $creditlimit, $enableexpire, '$expirationdate', $expiredays, '$passui_secret', '$runservice', '$tag', '$id_group', '$discount', '$id_seria'";

        if (DB_TYPE != "postgres") {
            $FG_ADITION_SECOND_ADD_VALUE .= ",now() ";
        }
        if (isset($sip)) $FG_ADITION_SECOND_ADD_VALUE .= ", 1";
        if (isset($iax)) $FG_ADITION_SECOND_ADD_VALUE .= ", 1";

        $id_cc_card = $instance_sub_table -> Add_table ($HD_Form -> DBHandle, $FG_ADITION_SECOND_ADD_VALUE, null, null, $HD_Form -> FG_TABLE_ID);
        //create refill for each cards

        if ($addcredit > 0) {
            $value_insert_refill = "'$addcredit', '$id_cc_card', '$description_refill' ";
            $instance_refill_table -> Add_table ($HD_Form -> DBHandle, $value_insert_refill, null, null);
        }

        // Insert data for sip_buddy
        if (isset($sip)) {
            $FG_QUERY_ADITION_SIP_IAX_VALUE = "'$cardnum', '$cardnum', '$cardnum', '$amaflags', '$cardnum', '$context', '$dtmfmode','$host', '$type', '$cardnum', '$allow', '".$passui_secret."', '$id_cc_card', '$nat', '$qualify'";
            $result_query1 = $instance_sip_table -> Add_table ($HD_Form ->DBHandle, $FG_QUERY_ADITION_SIP_IAX_VALUE, null, null, null);
            if (USE_REALTIME) {
                  $_SESSION["is_sip_iax_change"]=1;
                  $_SESSION["is_sip_changed"]=1;
            }
        }

        // Insert data for iax_buddy
        if (isset($iax)) {
            //$FG_QUERY_ADITION_SIP_IAX_VALUE = "'$cardnum', '$cardnum', '$cardnum', '$amaflag', '$cardnum', '$context', 'RFC2833','dynamic', 'friend', '$cardnum', 'g729,ulaw,alaw,gsm','".$passui_secret."'";
            $FG_QUERY_ADITION_SIP_IAX_VALUE = "'$cardnum', '$cardnum', '$cardnum', '$amaflags', '$cardnum', '$context', '$dtmfmode','$host', '$type', '$cardnum', '$allow', '".$passui_secret."', '$id_cc_card', '$nat', '$qualify'";
            $result_query2 = $instance_iax_table -> Add_table ($HD_Form ->DBHandle, $FG_QUERY_ADITION_SIP_IAX_VALUE, null, null, null);
            if (USE_REALTIME) {
                $_SESSION["is_sip_iax_change"]=1;
                $_SESSION["is_iax_changed"]=1;
            }
        }
    }

    // Save Sip accounts to file
    if (isset($sip)) {
        $buddyfile = BUDDY_SIP_FILE;

        $instance_table_friend = new Table($FG_TABLE_SIP_NAME,'id, '.$FG_QUERY_ADITION_SIP_IAX);
        $list_friend = $instance_table_friend -> Get_list ($HD_Form ->DBHandle, '', null, null, null, null);
        if (is_array($list_friend)) {
            $fd=fopen($buddyfile,"w");
            if (!$fd) {
                $error_msg= "</br><center><b><font color=red>".gettext("Could not open buddy file")." ". $buddyfile."</font></b></center>";
            } else {
                foreach ($list_friend as $data) {
                    $line="\n\n[".$data[1]."]\n";
                    if (fwrite($fd, $line) === FALSE) {
                        echo "Impossible to write to the file ($buddyfile)";
                        break;
                    } else {
                        for ($i=1;$i<count($data)-1;$i++) {
                            if (strlen($data[$i+1])>0) {
                                if (trim($list_names[$i]) == 'allow') {
                                    $codecs = explode(",",$data[$i+1]);
                                    $line = "";
                                    foreach ($codecs as $value)
                                        $line .= trim($list_names[$i]).'='.$value."\n";
                                } else {
                                    $line = (trim($list_names[$i]).'='.$data[$i+1]."\n");
                                }
                                if (fwrite($fd, $line) === FALSE) {
                                    echo gettext("Impossible to write to the file")." ($buddyfile)";
                                    break;
                                }
                            }
                        }
                    }
                }
                fclose($fd);
            }
        }//end if is_array
    } // END SAVE SIP ACCOUNTS

    // Save IAX accounts to file
    if (isset($iax)) {
        $buddyfile = BUDDY_IAX_FILE;

        $instance_table_friend = new Table($FG_TABLE_IAX_NAME,'id, '.$FG_QUERY_ADITION_SIP_IAX);
        $list_friend = $instance_table_friend -> Get_list ($HD_Form ->DBHandle, '', null, null, null, null);

        if (is_array($list_friend)) {
            $fd=fopen($buddyfile,"w");
            if (!$fd) {
                $error_msg= "</br><center><b><font color=red>".gettext("Could not open buddy file"). $buddyfile."</font></b></center>";
            } else {
                foreach ($list_friend as $data) {
                    $line="\n\n[".$data[1]."]\n";
                     if (fwrite($fd, $line) === FALSE) {
                        echo "Impossible to write to the file ($buddyfile)";
                        break;
                    } else {
                        for ($i=1;$i<count($data)-1;$i++) {
                            if (strlen($data[$i+1])>0) {
                                if (trim($list_names[$i]) == 'allow') {
                                    $codecs = explode(",",$data[$i+1]);
                                    $line = "";
                                    foreach ($codecs as $value)
                                        $line .= trim($list_names[$i]).'='.$value."\n";
                                } else {
                                    $line = (trim($list_names[$i]).'='.$data[$i+1]."\n");
                                }
                                if (fwrite($fd, $line) === FALSE) {
                                    echo gettext("Impossible to write to the file")." ($buddyfile)";
                                    break;
                                }
                            }
                        }
                    }
                }
                fclose($fd);
            }
        }// end if is_array
    } // END SAVE IAX ACCOUNTS

}
if (!isset($_SESSION["IDfilter"])) $_SESSION["IDfilter"]='NODEFINED';

$HD_Form -> FG_TABLE_CLAUSE = " lastname='".$_SESSION["IDfilter"]."'";

// END GENERATE CARDS

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
echo $CC_help_generate_customer;

$instance_table_tariff = new Table("cc_tariffgroup LEFT JOIN cc_agent_tariffgroup ON cc_agent_tariffgroup.id_tariffgroup = cc_tariffgroup.id ", "id, tariffgroupname");
$FG_TABLE_CLAUSE = "cc_agent_tariffgroup.id_agent = ".$_SESSION['agent_id'];
$list_tariff = $instance_table_tariff -> Get_list ($HD_Form ->DBHandle, $FG_TABLE_CLAUSE, "tariffgroupname", "ASC", null, null, null, null);
$nb_tariff = count($list_tariff);
$FG_TABLE_CLAUSE =  "cc_card_group.id_agent=".$_SESSION['agent_id'] ;
$instance_table_group=  new Table("cc_card_group"," id, name ");
$list_group = $instance_table_group  -> Get_list ($HD_Form ->DBHandle, $FG_TABLE_CLAUSE, "name", "ASC", null, null, null, null);

// FORM FOR THE GENERATION
?>


<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                User                          </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_card.php?section=1" class="kt-subheader__breadcrumbs-link">
                            List Customers                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Generate Customers                       </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>



        <!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="row">
<div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"><?php echo gettext("Generate Customer"); ?></h3>
				</div>
			</div>
		
		
		
		<!--begin::Portlet-->
		<!-- <div class="kt-portlet"> -->
			<!--<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Generate Customers
					</h3>
				</div>
			</div>-->
			<!--begin::Form-->
			
			<?php if (!empty($msg_error) && $nb_error>0 ) { ?>
    <div class="msg_error" style="width:70%;text-align:left; color:#ff0000;">
        <?php echo $msg_error ?>
    </div>
<?php } else { ?>
   
<?php }?>
			
			<form class="kt-form" name="theForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL) ?>" method="POST">
			<?php
    if ($HD_Form->FG_CSRF_STATUS == true) {
?>
    <INPUT type="hidden" name="<?php echo $HD_Form->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $HD_Form->FG_FORM_UNIQID; ?>" />
    <INPUT type="hidden" name="<?php echo $HD_Form->FG_CSRF_FIELD ?>" value="<?php echo $HD_Form->FG_CSRF_TOKEN; ?>" />
<?php
    }
?>
			
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<div class="form-group">
							<label class="control-label-ag"> <strong>1)</strong>  <?php echo gettext("Length of card number :");?></label>
							<div class="controls" style="width:75%; padding:10px;">
							<select name="cardnumberlenght_list" size="1" class="form-control">
    <?php
    foreach ($A2B -> cardnumber_range as $value) {
    ?>
        <option value='<?php echo $value ?>'
        <?php if ($value == $cardnumberlenght_list) echo "selected";
        ?>> <?php echo $value." ".gettext("Digits");?> </option>

    <?php
    }
    ?>
    </select>
	</div>
							 
						</div>
						<div class="form-group">
							<label class="control-label-ag"><strong>2)</strong>
     <?php echo gettext("Number of customers to create")?> :</label>
	 <div class="controls" style="width:75%; padding:10px;">
							<input class="form-control" name="nb_to_create" size="5" maxlength="3" value="<?php echo $nb_to_create; ?>" >
        <?php echo gettext("(max 100)");?>
		</div>
						</div>			
						<div class="form-group">
							<label class="control-label-ag"><strong>3)</strong>
    <?php echo gettext("Call plan");?> :</label>
							
							 <div class="controls" style="width:75%; padding:10px;">
								<select NAME="choose_tariff" size="1" class="form-control" >
        <option value=''><?php echo gettext("Choose a Call Plan");?></option>
    <?php foreach ($list_tariff as $recordset) { ?>
        <option class=input value='<?php echo $recordset[0]?>' <?php if($recordset[0]==$choose_tariff) echo "selected"; ?> ><?php echo $recordset[1]?></option>
    <?php } ?>
    </select>
    <?php if ($tariff_error) { ?>
        <img style="vertical-align:middle;" src="<?php echo Images_Path;?>/exclamation.png" />
    <?php } ?></div>
							
						</div>		
						<div class="form-group">
							<label class="control-label-ag"><strong>4)</strong>
                        <?php echo gettext("Initial amount of credit");?> :</label>
							<div class="controls" style="width:75%; padding:10px;">
								<input class="form-control" value="<?php if(is_numeric($addcredit) && $addcredit>0) echo $addcredit; else echo 0;?>" name="addcredit" size="10" maxlength="10" >
                        <?php if ($credit_error) { ?>
                         <i class="material-icons" style="color: #f00">&#xE002;</i>
                        <?php } ?>
		                    </div>
		                </div>
						
						
						<div class="form-group">
							<label class="control-label-ag"><strong>5)</strong>
                        <?php echo gettext("Simultaneous access");?> :</label>
							<div class="controls" style="width:75%; padding:10px;">
								 <select NAME="choose_simultaccess" size="1" class="form-control" >
            <option value='0' <?php if($choose_simultaccess== 0 || empty($choose_simultaccess)) echo "selected"; ?>><?php echo gettext("INDIVIDUAL ACCESS");?></option>
            <option value='1' <?php if($choose_simultaccess== 1) echo "selected"; ?>><?php echo gettext("SIMULTANEOUS ACCESS");?></option>
            </select>
		                    </div>
		                </div>
						
						
						<div class="form-group">
							<label class="control-label-ag"><strong>6)</strong>
                        <?php echo gettext("Currency");?> :</label>
							<div class="controls" style="width:75%; padding:10px;">
								<select NAME="choose_currency" size="1" class="form-control" >
            <?php foreach ($currencies_list as $key => $cur_value) { ?>
            <option value='<?php echo $key ?>' <?php if($choose_currency== $key) echo "selected"; ?>><?php echo $cur_value[1].' ('.$cur_value[2].')' ?></option>
            <?php } ?>
            </select>
		                    </div>
		                </div>
						
						
										
						
						<div class="form-group">
							<label class="control-label-ag"><strong>7)</strong>
                        <?php echo gettext("Card type");?> :</label>
							<div class="controls" style="width:75%; padding:10px;">
								 <select NAME="choose_typepaid" size="1" class="form-control">
        <option value='0' <?php if($choose_typepaid== 0 || empty($choose_typepaid)) echo "selected"; ?>><?php echo gettext("PREPAID CARD");?></option>
        <option value='1' <?php if($choose_typepaid== 1) echo "selected"; ?>><?php echo gettext("POSTPAY CARD");?></option>
       </select>
		                    </div>
		                </div>
						
						
						<div class="form-group">
							<label class="control-label-ag"><strong>8)</strong>
                        <?php echo gettext("Credit Limit of postpay");?> :</label>
							<div class="controls" style="width:75%; padding:10px;">
								 <input class="form-control" value="<?php if(is_numeric($creditlimit) && $creditlimit>0) echo $creditlimit; else echo 0;?>" name="creditlimit" size="10" maxlength="16" >
		                    </div>
		                </div>
						
						
						<div class="form-group">
							<label class="control-label-ag"><strong>9)</strong>
                        <?php echo gettext("Enable expire");?>:</label>
							<div class="controls" style="width:75%; padding:10px;">
								  <select name="enableexpire" class="form-control" >
        <option value="0" <?php if($enableexpire== 0 || empty($enableexpire)) echo "selected"; ?>> <?php echo gettext("NO EXPIRATION");?> </option>
        <option value="1" <?php if($enableexpire== 1) echo "selected";?> > <?php echo gettext("EXPIRE DATE");?> </option>
        <option value="2" <?php if($enableexpire== 2) echo "selected";?> > <?php echo gettext("EXPIRE DAYS SINCE FIRST USE");?> </option>
        <option value="3" <?php if($enableexpire== 3) echo "selected"; ?> > <?php echo gettext("EXPIRE DAYS SINCE CREATION");?> </option>
    </select>
    <br/>
    <?php
        $begin_date = date("Y");
        $begin_date_plus = date("Y")+10;
        $end_date = date("-m-d H:i:s");
        $comp_date = "value='".$begin_date.$end_date."'";
        $comp_date_plus = "value='".$begin_date_plus.$end_date."'";
    ?>
		                    </div>
		                </div>
						
						
						<div class="form-group">
							<label class="control-label-ag"><strong>10)</strong>
                        <?php echo gettext("Expiry Date");?> :</label>
							<div class="controls" style="width:75%; padding:10px;">
								<input class="form-control"  name="expirationdate" size="40" maxlength="40" <?php if(!empty($expirationdate)) echo "value='$expirationdate'"; else echo $comp_date_plus;?> > <?php echo gettext("(Format YYYY-MM-DD HH:MM:SS)");?>
    <?php if ($expdate_error) { ?>
        <img style="vertical-align:middle;" src="<?php echo Images_Path;?>/exclamation.png" />
    <?php } ?>
		                    </div>
		                </div>
						
						<div class="form-group">
							<label class="control-label-ag"><strong>11)</strong>
                        <?php echo gettext("Expiry days");?> : </label>
							<div class="controls" style="width:75%; padding:10px;">
								<input class="form-control"  name="expiredays" size="10" maxlength="6" value="<?php if(is_numeric($expiredays) && $expiredays>0) echo $expiredays; else echo 0;?>">
    <?php if ($expday_error) { ?>
        <img style="vertical-align:middle;" src="<?php echo Images_Path;?>/exclamation.png" />
    <?php } ?>
		                    </div>
		                </div>
						
						<div class="form-group">
							<label class="control-label-ag"><strong>12)</strong>
                         <?php echo gettext("Run service");?> : </label>
							<div class="controls" style="width:75%; padding:15px;">
								<?php echo gettext("Yes");?> <input name="runservice" value="1" <?php if($runservice==1) echo "checked='checked'" ?> type="radio"> - <?php echo gettext("No");?> <input name="runservice" value="0" <?php if($runservice==0 || empty($runservice) ) echo "checked='checked'" ?>  type="radio">
		                    </div>
		                </div>
						
						
						<div class="form-group">
							<label class="control-label-ag"><strong>13)</strong>
                         <?php echo gettext("Create SIP/IAX Friends");?>: </label>
							<div class="controls" style="width:75%; padding:15px;">
								<?php echo gettext("SIP")?> <input type="checkbox" name="sip" value="1" <?php if($sip==1) echo "checked" ?>> <?php echo gettext("IAX")?> : <input type="checkbox" name="iax" value="1" <?php if($iax==1 ) echo "checked" ?> >
		                    </div>
		                </div>
						
						<div class="form-group">
							<label class="control-label-ag"><strong>14)</strong>
                         <?php echo gettext("Tag");?> : </label>
							<div class="controls" style="width:75%; padding:10px;">
								<input class="form-control"  name="tag" size="40" maxlength="40" <?php if(!empty($tag)) echo "value='$tag'"; ?> >
		                    </div>
		                </div>
						
						<div class="form-group">
							<label class="control-label-ag"><strong>15)</strong>
                          <?php echo gettext("Customer group");?> : </label>
							<div class="controls" style="width:75%; padding:10px;">
								<select NAME="id_group" size="1" class="form-control">
    <option value=''><?php echo gettext("Choose a group");?></option>
    <?php foreach ($list_group as $recordset) { ?>
        <option class=input value='<?php echo $recordset[0]?>' <?php if($recordset[0]==$id_group) echo "selected"; ?> ><?php echo $recordset[1]?></option>
    <?php } ?>
    </select>
    <?php if ($group_error) { ?>
        <img style="vertical-align:middle;" src="<?php echo Images_Path;?>/exclamation.png" />
    <?php } ?>
		                    </div>
		                </div>
						
						<div class="form-group">
							<label class="control-label-ag"><strong>16)</strong>
                         <?php echo gettext("Discount");?> :</label>
							<div class="controls" style="width:75%; padding:10px;">
								 <select NAME="discount" size="1" class="form-control" >
    <option value='0'><?php echo gettext("NO DISCOUNT");?></option>
    <?php for ($i=1;$i<99;$i++) { ?>
        <option class=input value='<?php echo $i; ?>' <?php if($i==$discount) echo "selected"; ?> ><?php echo $i;?>%</option>
    <?php } ?>
    </select>
		                    </div>
		                </div>
						
						
		            </div>
	            </div>
	            <div class="kt-portlet__foot">
					<div class="kt-form__actions" style="text-align:right;">
						<input name="action"  value="generate" type="hidden" class="btn btn-primary" />
                        <input class="btn btn-primary"  value=" GENERATE CUSTOMERS " type="submit"/>
					</div>
				</div>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		 
	</div>
	 


</div>	</div>



<?php
// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
    $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";
?>

</div>	</div>

<br>
<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
