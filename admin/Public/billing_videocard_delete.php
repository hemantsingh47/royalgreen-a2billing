<?php 
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_card_videoim.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}
//including the database connection file
include_once("config.php");
$smarty->display('main.tpl'); 
//getting id of the data from url
$username = $_GET['username'];
//deleting the row from table
$result = mysqli_query($mysqli, "DELETE FROM cc_card WHERE username=$username");


$result1 = mysqli_query($mysqli, "DELETE FROM cc_sip_buddies WHERE accountcode=$username");

//redirecting to the display page (index.php in our case)
//header("Location:billing_entity_card_videoim.php?section=1");

   $host        = "host = 127.0.0.1";
   $port        = "port = 5432";
   $dbname      = "dbname = synapse";
   $credentials = "user=synapse password=Rd8sl9k7o5iH";
   $db = pg_connect("host=127.0.0.1 port=5432 dbname=synapse user=synapse password=Rd8sl9k7o5iH");   
   //$db = pg_connect("$host $port $dbname $credentials");
   if(!$db) {
      //echo "Error : Unable to open database\n";
   } else {
     // echo "Opened database successfully\n";
   } 

$server_url = ':billing.adoreinfotech.co.in';
$prefix = '@';
$return_url = $prefix.$username.$server_url;
   
   $sql =<<<EOF
       DELETE FROM device_lists_stream WHERE user_id ='$return_url';
EOF;
   $ret = pg_query($db, $sql);
   if(!$ret) {
      echo pg_last_error($db);
      exit;
   } else {
      echo " ";
   }           
   
   $sql =<<<EOF
      DELETE FROM presence_stream WHERE user_id ='$return_url';
EOF;
   $ret = pg_query($db, $sql);
   if(!$ret) {
      echo pg_last_error($db);
      exit;
   } else {
      echo " ";
   }           
   
   $sql =<<<EOF
      DELETE FROM access_tokens WHERE user_id ='$return_url';
EOF;
   $ret = pg_query($db, $sql);
   if(!$ret) {
      echo pg_last_error($db);
      exit;
   } else {
      echo " ";
   }
   
    $sql =<<<EOF
      DELETE FROM users WHERE name ='$return_url';
EOF;
   $ret = pg_query($db, $sql);
   if(!$ret) {
      echo pg_last_error($db);
      exit;
   } else {
      echo " ";
   }
   
   
    $sql =<<<EOF
      DELETE FROM devices WHERE user_id ='$return_url';
EOF;
   $ret = pg_query($db, $sql);
   if(!$ret) {
      echo pg_last_error($db);
      exit;
   } else {
      echo " ";
   }   
   
   $sql =<<<EOF
      DELETE FROM profiles WHERE user_id ='$username';
EOF;
   $ret = pg_query($db, $sql);
   if(!$ret) {
      echo pg_last_error($db);
      exit;
   } else {
      echo " ";
   }
   
   $sql =<<<EOF
      DELETE FROM user_ips WHERE user_id ='$return_url';
EOF;
   $ret = pg_query($db, $sql);
   if(!$ret) {
      echo pg_last_error($db);
      exit;
   } else {

      echo "Record deleted successfully\n";
   }
                          
?>
<html>
<head>
<style>
	a {
    color: #f51010;
}
</style>
<body>

<button><a href="https://billing.adoreinfotech.co.in/admin/Public/billing_videoaccount_delete.php" style= 'align:center';'color:red';>Go Back to Page</a></button>
</html>
</head>
</body>

<?php
$smarty->display('footer.tpl'); 

