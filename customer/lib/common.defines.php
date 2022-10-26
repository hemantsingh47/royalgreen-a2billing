<?php


// SETTINGS FOR DATABASE CONNECTION
define ("HOST", isset($A2B->config['database']['hostname'])?$A2B->config['database']['hostname']:null);
define ("PORT", isset($A2B->config['database']['port'])?$A2B->config['database']['port']:null);
define ("USER", isset($A2B->config['database']['user'])?$A2B->config['database']['user']:null);
define ("PASS", isset($A2B->config['database']['password'])?$A2B->config['database']['password']:null);
define ("DBNAME", isset($A2B->config['database']['dbname'])?$A2B->config['database']['dbname']:null);
define ("DB_TYPE", isset($A2B->config['database']['dbtype'])?$A2B->config['database']['dbtype']:null);
define ("CSRF_SALT", isset($A2B->config['csrf']['csrf_token_salt'])?$A2B->config['csrf']['csrf_token_salt']:'YOURSALT');

// SETTINGS FOR SMTP
define ("SMTP_SERVER", isset($A2B->config['global']['smtp_server'])?$A2B->config['global']['smtp_server']:null);
define ("SMTP_HOST", isset($A2B->config['global']['smtp_host'])?$A2B->config['global']['smtp_host']:null);
define ("SMTP_USERNAME", isset($A2B->config['global']['smtp_username'])?$A2B->config['global']['smtp_username']:null);
define ("SMTP_PASSWORD", isset($A2B->config['global']['smtp_password'])?$A2B->config['global']['smtp_password']:null);
define ("SMTP_PORT", isset($A2B->config['global']['smtp_port'])?$A2B->config['global']['smtp_port']:'25');
define ("SMTP_SECURE", isset($A2B->config['global']['smtp_secure'])?$A2B->config['global']['smtp_secure']:null);

// SETTING FOR REALTIME
define ("USE_REALTIME", isset($A2B->config['global']['use_realtime'])?$A2B->config['global']['use_realtime']:0);

// SIP IAX FRIEND CREATION
define ("FRIEND_TYPE", isset($A2B->config['peer_friend']['type'])?$A2B->config['peer_friend']['type']:null);
define ("FRIEND_ALLOW", isset($A2B->config['peer_friend']['allow'])?$A2B->config['peer_friend']['allow']:null);
define ("FRIEND_CONTEXT", isset($A2B->config['peer_friend']['context'])?$A2B->config['peer_friend']['context']:null);
define ("FRIEND_NAT", isset($A2B->config['peer_friend']['nat'])?$A2B->config['peer_friend']['nat']:null);
define ("FRIEND_AMAFLAGS", isset($A2B->config['peer_friend']['amaflags'])?$A2B->config['peer_friend']['amaflags']:null);
define ("FRIEND_QUALIFY", isset($A2B->config['peer_friend']['qualify'])?$A2B->config['peer_friend']['qualify']:null);
define ("FRIEND_HOST", isset($A2B->config['peer_friend']['host'])?$A2B->config['peer_friend']['host']:null);
define ("FRIEND_DTMFMODE", isset($A2B->config['peer_friend']['dtmfmode'])?$A2B->config['peer_friend']['dtmfmode']:null);

//DIDX.NET API
define ("DIDX_ID", isset($A2B->config['webui']['didx_id'])?$A2B->config['webui']['didx_id']:null);
define ("DIDX_PASS", isset($A2B->config['webui']['didx_pass'])?$A2B->config['webui']['didx_pass']:null);
define ("DIDX_MIN_RATING", isset($A2B->config['webui']['didx_min_rating'])?$A2B->config['webui']['didx_min_rating']:null);
define ("DIDX_SITE", "api.didx.net");
define ("DIDX_RING_TO", isset($A2B->config['webui']['didx_ring_to'])?$A2B->config['webui']['didx_ring_to']:null);

define ("API_LOGFILE", isset($A2B->config['webui']['api_logfile'])?$A2B->config['webui']['api_logfile']:"/var/log/a2billing/");

// BUDDY ASTERISK FILES
define ("BUDDY_SIP_FILE", isset($A2B->config['webui']['buddy_sip_file'])?$A2B->config['webui']['buddy_sip_file']:null);
define ("BUDDY_IAX_FILE", isset($A2B->config['webui']['buddy_iax_file'])?$A2B->config['webui']['buddy_iax_file']:null);

// VOICEMAIL
define ("ACT_VOICEMAIL", false);

// SHOW DONATION
define ("SHOW_DONATION", true);

// AGI
define ("ASTERISK_VERSION", isset($A2B->config['agi-conf1']['asterisk_version'])?$A2B->config['agi-conf1']['asterisk_version']:'1_4');

// Iridium info
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_ADDR1", gettext("Address line 1:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_ADDR2", gettext("Address line 2:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_ADDR3", gettext("Address line 3:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_POSTCODE", gettext("Postcode:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_COUNTRY", gettext("Country:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_TELEPHONE", gettext("Telephone:"));

# define the amount of emails you want to send per period. If 0, batch processing
# is disabled and messages are sent out as fast as possible
define("MAILQUEUE_BATCH_SIZE", 0);

# define the length of one batch processing period, in seconds (3600 is an hour)
define("MAILQUEUE_BATCH_PERIOD", 3600);

# to avoid overloading the server that sends your email, you can add a little delay
# between messages that will spread the load of sending
# you will need to find a good value for your own server
# value is in seconds (or you can play with the autothrottle below)
define('MAILQUEUE_THROTTLE', 0);

/*
 *		GLOBAL USED VARIABLE
 */
$PHP_SELF = $_SERVER["PHP_SELF"];

$CURRENT_DATETIME = date("Y-m-d H:i:s");

// Store script start time
$_START_TIME = time();
mt_start();

define ("COPYRIGHT", "");

define ("CCMAINTITLE", gettext("Billing Solution : CallingCard, CallBack & VOIP Billing Solution"));

/*
 *		CONNECT / DISCONNECT DATABASE
 */
function DbConnect()
{
    return Connection::GetDBHandler();
}

function DbDisconnect($DBHandle)
{
    $DBHandle ->disconnect();
}
//for uplading images  in admin templates
define ("IMGDIR", substr(dirname(__FILE__),0,-10)."admin/Public/templates/default/images/");
//for uplading images in agent  templates
define ("AGENTIMGDIR", substr(dirname(__FILE__),0,-10)."agent/Public/templates/default/images/");
///for uplading display picture in customer templates
define ("CUSTIMGDIR", substr(dirname(__FILE__),0,-10)."customer/templates/default/images/");

//for admin image path
define ("ADMINIMGPATH", "templates/default/images/admin_logo/");
//for agent image path
define ("AGENTIMGPATH", "templates/default/images/agent_logo/");
//for customer image path
define ("CUSTIMGPATH", "templates/default/images/agent_logo/");

 //FOR UPLOADING IMAGES WITH OR WITHOUT MULTIPLE LOCATIONS
function imageUploadMultiName($imgarray,$fileids,$location,$Successmsg,$DBHandle)
{
     
      $result= array();
      
      $file_name = $imgarray['image']['name'];
      $file_size =$imgarray['image']['size'];
      $file_tmp =$imgarray['image']['tmp_name'];
      $file_type=$imgarray['image']['type'];
      $file_ext=strtolower(end(explode('.',$imgarray['image']['name'])));
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
         $result["error"]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $result['error']='File size must be below 2 MB';
      }
      
      if(empty($result['error'])==true){
          
          
          $result["img"]= $fileids.".".$file_ext;
          
          if (!file_exists($location)) 
         {
            mkdir($location, 0777, true);
         }
        
         if(is_array($location))
         {
              for($i=0;$i<count($location);$i++)
              {
                 if($i==0)
                 {
                   $var= move_uploaded_file($file_tmp,$location[$i]."/".$result["img"]);  
                 }else
                 {
                     copy($location[0]."/".$result["img"], $location[$i]."/".$result["img"]);
                 }
                   
              }
         }
         else
         {
             move_uploaded_file($file_tmp,$location."/".$result["img"]);
         } 
         
         
         return $result;
      }else{
         return ($result);
      }
     
}

 //FOR UPLOADING IMAGES WITH OR WITHOUT MULTIPLE LOCATIONS AND DIFFERENT FILE NAMES
function imageUpload($imgarray,$fileids,$location,$Successmsg,$DBHandle)
{
     
      $result= array();
      //TAKING EVERYTHING FROM THE UPLOADED FILE
      $file_name = $imgarray['image']['name'];
      $file_size =$imgarray['image']['size'];
      $file_tmp =$imgarray['image']['tmp_name'];
      $file_type=$imgarray['image']['type'];
      $file_ext=strtolower(end(explode('.',$imgarray['image']['name'])));
      //ALLOWED EXTENSIONS YOU CAN INCREASE ACCOORDING TO NEED
      $expensions= array("jpeg","jpg","png");
      //CHECKING EXTENSION OF IMAGE
      if(in_array($file_ext,$expensions)=== false){
         $result["error"]="extension not allowed, please choose a JPEG or PNG file.";
      }
      //CHECKING SIZE OF IMAGE LIMITED TO 2MB
      if($file_size > 2097152){
         $result['error']='File size must be below 2 MB';
      }
      //IF ABOVE TWO CONDIOTIONS ARE FALSE THEN MOVE FOR CREATION
      if(empty($result['error'])==true){
          // IF FILENAMES ARE ARRAY 
          if(is_array($fileids)==true)
          {
              //CREATING LOOP FOR NAMES
              for($k=0;$k<count($fileids);$k++)
              {
                  $result["img"][$k]= $fileids[$k].".".$file_ext;
                   //CONDITION FOR CHANGING NAMES OF UPLOADED FILE TO AVOID GROUP IMAGE INTERFERENCE
                   if($k==0)
                   {
                       //CONDITION FOR MULTIPLE LOCATIONS
                       if(is_array($location))
                         {
                              for($i=0;$i<count($location);$i++)
                              {
                                 if (!file_exists($location[$i])){ mkdir($location[$i], 0777, true);}
                                 if($i==0)
                                 {
                                   $var= move_uploaded_file($file_tmp,$location[$i]."/".$result["img"][$k]);  
                                 }else
                                 {
                                     copy($location[0]."/".$result["img"][0], $location[$i]."/".$result["img"][$k]);
                                 }
                                   
                              }
                         }
                         else
                         {
                             if (!file_exists($location)){ mkdir($location, 0777, true);}
                             move_uploaded_file($file_tmp,$location."/".$result["img"][$k]);
                         }
                         // END CONDITION FOR MULTIPLE LOCATIONS
                   }
                   else
                   {
                      //CONDITION FOR MULTIPLE LOCATIONS
                       if(is_array($location))
                         {
                              for($i=0;$i<count($location);$i++)
                              {
                                 if (!file_exists($location[$i])){ mkdir($location[$i], 0777, true);}
                                 copy($location[0]."/".$result["img"][0], $location[$i]."/".$result["img"][$k]);
                                   
                              }
                         }
                         else
                         {
                             if (!file_exists($location)){ mkdir($location, 0777, true);}
                            copy($location."/".$result["img"][0], $location."/".$result["img"][$k]);
                         }
                         // END CONDITION FOR MULTIPLE LOCATIONS 
                   }
                  ////CONDITION FOR CHANGING NAMES OF UPLOADED FILE TO AVOID GROUP IMAGE INTERFERENCE ENDS
              }
          }
          else
          {
              
              $result["img"][0]=$fileids.".".$file_ext;
              //print_r($result);
              if(is_array($location))
                 {
                      for($i=0;$i<count($location);$i++)
                      {
                          if (!file_exists($location[$i])){ mkdir($location[$i], 0777, true);}
                         if($i==0)
                         {
                           $var= move_uploaded_file($file_tmp,$location[$i]."/".$result["img"][0]);  
                         }else
                         {
                             copy($location[0]."/".$result["img"][0], $location[$i]."/".$result["img"][0]);
                         }
                           
                      }
                 }
                 else
                 {
                     if (!file_exists($location)){ mkdir($location, 0777, true);}
                     move_uploaded_file($file_tmp,$location."/".$result["img"][0]);
                 }  
          }
          
           
         return $result;
         
      }else{
         return ($result);
      }
     
}