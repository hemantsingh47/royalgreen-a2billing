<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/regular_express.inc");
include ("../lib/phpagi/phpagi-asmanager.php");
include ("../lib/admin.smarty.php");

$astman = new AGI_AsteriskManager();
$res = $astman->connect(MANAGER_HOST,MANAGER_USERNAME,MANAGER_SECRET);
if(isset($_REQUEST["sipnum"]))
{
     
     $command = " channel request hangup ".$_REQUEST["sipnum"];
     $out = $astman->Command($command);
     print_r("success : ".$out["data"]);
}