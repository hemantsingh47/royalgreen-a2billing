<?php
//hearder files
include ("/var/www/html/crm/common/lib/admin.defines.php");
include ("/var/www/html/crm/common/lib/admin.module.access.php");
include ("/var/www/html/crm/common/lib/admin.smarty.php");
include ('/var/www/html/crm/common/lib/security/security.php');
define("KEY_SECURE","f#to67fdmheiuqmv");   
define("DIAL_PREFIX","");//+31
define("COMPANY_NAME","Adore");
//define("NEXMO_USERAME","4dba443d");
//define("NEXMO_PASSORD","9d0f51fbeefd27df");
$DBHandle  = DbConnect();
$callplan_table = new Table();
$call_plan = $callplan_table -> SQLExec($DBHandle, "SELECT config_value FROM cc_config WHERE config_key='callplan_id' AND config_group_title='global'");  
define("CALLPLAN",$call_plan[0]['config_value']);

if($DBHandle)
{
    $inst_table = new Table();
	$query_card = "SELECT amount, cardid, currency FROM cc_epayment_log order by id DESC LIMIT 1";
	$result_card_info = $inst_table -> SQLExec($DBHandle, $query_card);
}

else
{
    // echo "not connected";
}
 
?>

