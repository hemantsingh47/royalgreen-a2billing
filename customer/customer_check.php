<?php
include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'lib/Form/Class.FormHandler.inc.php';
include 'lib/customer.smarty.php';
$DBHandle = DbConnect();
 if (!empty ($agentkey)) 
 {
    $table_key = new Table("cc_agent_signup", "*");
    $data = $table_key->Get_list($HD_Form->DBHandle, "code= '" . $agentkey . "'");
    if (is_array($data)) {
        $groupid = $data[0]['id_group'];
        $tariffid = $data[0]['id_tariffgroup'];
        $agentid = $data[0]['id_agent'];
        // check if groupid and tarif in already in this agent
        $instance_table_agent_check = new Table("cc_agent JOIN cc_agent_tariffgroup ON cc_agent_tariffgroup.id_agent =  cc_agent.id JOIN cc_card_group ON cc_card_group.id_agent =  cc_agent.id  ", "cc_agent.id");
        $list_agent_check = $instance_table_agent_check->Get_list($HD_Form->DBHandle, "cc_agent.id= $agentid AND cc_agent_tariffgroup.id_tariffgroup = $tariffid  AND cc_card_group.id = $groupid", "cc_agent.id", "ASC", null, null, null, null);
        if (is_array($list_agent_check)) {
            if (!empty ($groupid) && is_numeric($groupid) && !empty ($tariffid) && is_numeric($tariffid)) {
                $agent_conf = true;
            } else {
                $agent_conf = false;
            }
        } else {
            $agent_conf = false;
        }
    }
}
if (!$agent_conf) {
    $callPlan_idlist = isset ($A2B->config["signup"]['callplan_id_list']) ? $A2B->config["signup"]['callplan_id_list'] : null;
    //print_r($callPlan_idlist);
    if (strlen($callPlan_idlist) == 0) {
        exit ("No Call Plan Defined.");
    }
    $call_table = new Table("cc_tariffgroup", "tariffgroupname,id");
    
}
//check subscriber

$table_key = new Table("cc_agent_signup", "*");

//end check subscriber

$currency_list = array ();
$currency_list_r = array ();
$indcur = 0;
$currencies_list = get_currencies();
foreach ($currencies_list as $key => $cur_value) {
    $currency_list[$key] = array (
        $cur_value[1] . ' (' . $cur_value[2] . ')',
        $key
    );
    $currency_list_r[$key] = array (
        $key,
        $cur_value[1]
    );
    $currency_list_key[$key][0] = $key;
}
    $array_card_generated = gen_card_with_alias();
    //$maxi = $array_card_generated[0];
    //$maxi2 = $array_card_generated[1];
    //$pass = MDP_NUMERIC(5);
$loginkey = MDP_STRING(20);
$addvalue = new Table("cc_card","*");
$sipbuddies = new Table("cc_sip_buddies","*");
 $DBHandle = DbConnect();
 if(isset($_POST['submit123']))
  {
        $mail=$_POST['email'];
        $QUEY="SELECT email FROM cc_card where email='$mail' ";
		$res = $DBHandle->Execute($QUEY);
        $num = 0;
        if ($res)
          echo $num = $res->RecordCount();

        if ($num!=0) {
             $ans= "Email Already exists. Try another";
             //header("location:customer_signup.php?msg=$ans");
			 Header("Location: customer_signup.php?msg=$ans");
				die();
        }
		
		$comp_zero = $comp_date_plus = '';
        $begin_date = date("Y");
        $begin_date_plus = date("Y") + 10;
        $end_date = date("-m-d H:i:s");
        $comp_date = "value='" . $begin_date . $end_date . "'";
        $comp_date_plus =  $begin_date_plus . $end_date ;
        $comp_zero = "value='0'";
        $myexpirationdate = $comp_date_plus;
        $mytariff = $callPlan_idlist;
        $myactivated = $A2B->config["signup"]['activated'] ? 't' : 'f';
        $mysimultaccess = $A2B->config["signup"]['simultaccess'];
        $mytypepaid = $A2B->config["signup"]['typepaid'];
        $mycreditlimit = $A2B->config["signup"]['creditlimit'];
        $myrunservice = $A2B->config["signup"]['runservice'];
        $myenableexpire = $A2B->config["signup"]['enableexpire'];
        $myexpiredays = $A2B->config["signup"]['expiredays'];
        $mycredit = $A2B->config["signup"]['credit'];
        $sip_account = $A2B->config["signup"]['sip_account'] ? 1 : 0;
        $iax_account = $A2B->config["signup"]['iax_account'] ? 1 : 0;
        $amaflag = $A2B->config["signup"]['amaflag'];
        $context = $A2B->config["signup"]['context'];
   
        if ($A2B->config["signup"]['activated']) {
            // Status : 1 - Active
            $status = $A2B->config["signup"]['activatedbyuser'] ? '1' : '3';
        } else {
            // Status : 2 - New
            $status = $A2B->config["signup"]['activatedbyuser'] ? '2' : '3';
        }    
            $lastname  = $_POST['lastname'];
			$firstname = $_POST['firstname'];
			$city      = $_POST['city'];
			$email     = $_POST['email'];
			$languag   =  $_POST['languag'];
			$country   = $_POST['country'];
			$currency   = $_POST['currency'];
			$state     = $_POST['state'];
			$address   = $_POST['address'];
			$zipcode   = $_POST['zipcode'];
			$phone     = $_POST['phone'];
			$fax       = $_POST['fax']; 
            
          
            $callplan=explode(',',$callPlan_idlist);
            $callerid=$callplan[0];
            $_SESSION["cardnumber_signup"] = $phone;
            $_SESSION["cardnumber_pass"] = $phone;
            $_SESSION["cardnumber_alias"] = $phone;
            
             "session".  $_SESSION["cardnumber_signup"];
             
            $fields="username,useralias,uipass,lastname,firstname,address,city,state,country,zipcode,phone,email,fax,currency,language,redial,loginkey,tag,email_notification,company_name,company_website,traffic_target,callshop_status,signup_page";
			$values="'$phone','$phone','$phone','$lastname','$firstname','$address','$city','$state','$country','$zipcode','$phone','$email','$fax','$currency','$languag','1','$loginkey','adore','$email','adore','adoreinoftech.com','NULL','NULL','Signup_customer'";
			$result=$addvalue->Add_table($DBHandle, $values, $fields); 
			
			$QUERY = "SELECT id FROM cc_card WHERE username='".$_SESSION["cardnumber_signup"]."'";   
			$res = $DBHandle->Execute($QUERY);
			$num = 0;
			if ($res)
			$num = $res->RecordCount();
		    for ($i = 0; $i < $num; $i++) {
				$list[] = $res->fetchRow();
			}
            $cc_card_id=$list[0]['id'];
			$_SESSION["id_signup"]=$list[0]['id'];
            
			   //code use for cc_sip_buddies
              $fields_cc_sip = "id_cc_card,name,accountcode,regexten,amaflags,callgroup,callerid,canreinvite,context,DEFAULTip,dtmfmode,fromuser,fromdomain,host,insecure,language,mailbox,md5secret,nat,deny,permit,mask,pickupgroup,port,qualify,restrictcid,rtptimeout,rtpholdtimeout,secret,type,username,disallow,allow,musiconhold,regseconds,ipaddr,cancallforward,fullcontact,setvar,regserver,lastms,defaultuser,auth,subscribemwi,vmexten,cid_number,callingpres,usereqphone,incominglimit,subscribecontext,musicclass,mohsuggest,allowtransfer,autoframing,maxcallbitrate,outboundproxy,rtpkeepalive,useragent,callbackextension";
              $values        = "'$cc_card_id','$phone','$phone','$phone','billing',NULL,'','NO','ittech',NULL,'RFC2833','','','dynamic','','en','','','force_rport,comedia','',NULL,'',NULL,'','no',NULL,NULL,NULL,'$phone','friend','$phone','ALL','g729,gsm,ulaw,alaw','','0','','yes','','',NULL,NULL,'','','','','','','','','','','','','','','','0',NULL,NULL ";
              $result=$sipbuddies->Add_table($DBHandle, $values, $fields_cc_sip);
              //end the cc_sip_buddies//
            
            
			header("location:customer_confirmation.php");
         
      }  
   
?>
         
                
                    
              



