<?php header("Access-Control-Allow-Origin:*"); ?>
<?php //header("Access-Control-Allow-Headers: x-requested-with, Content-Type, origin, authorization, accept"); ?>
<?php header("Access-Control-Allow-Methods: GET, POST, PUT"); ?>
<?php header("Access-Control-Allow-Credentials: true"); ?>
<?php

include_once ("/var/www/html/common/lib/admin.defines.php");
include_once ("/var/www/html/common/lib/admin.module.access.php");
include_once ("/var/www/html/common/lib/regular_express.inc");
include_once ("/var/www/html/common/lib/phpagi/phpagi-asmanager.php");
include ("/var/www/html/common/lib/admin.smarty.php");
$DBHandle  = DbConnect();
$instance_table = new Table();
//QUERY FOR GETTING MAX LENGTH OF PREFIX FROM cc_country table

$astman = new AGI_AsteriskManager();
$res = $astman->connect(MANAGER_HOST,MANAGER_USERNAME,MANAGER_SECRET);
//$value= array("Active Channel(s)" => "core show channels concise");

                           
 if(isset($_REQUEST['totalcall']))
 {
     $response1 = $astman->send_request('Command',array( "Command" => "core show channels"));
// /print_r($response1);
     foreach(explode("\n", $response1['data']) as $line)
    {
        if ( preg_match("/active calls/", $line) || preg_match("/active call/", $line)) 
        {
            $result = explode(" ",$line);
            print_r($result[0]);die;
        }
    }
 }                                      
                                
                                
?>                                

