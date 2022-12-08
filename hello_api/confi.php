
<?php
//hearder files
include ("/var/www/html/crm/common/lib/customer.defines.php");
include ("/var/www/html/crm/common/lib/customer.module.access.php");
include ("/var/www/html/crm/common/lib/customer.smarty.php");
include ('/var/www/html/crm/common/lib/security/security.php');
define("KEY_SECURE","h5r@mg7upu@ch$#u");   
define("DIAL_PREFIX","");//+31
define("COMPANY_NAME","Valuetel");
define("SMS_USERNAME","mohamed.salim");
define("SMS_PASSWORD","@Valuetel2019@#");
$DBHandle  = DbConnect();
$callplan_table = new Table();
$call_plan = $callplan_table -> SQLExec($DBHandle, "SELECT config_value FROM cc_config WHERE config_key='callplan_id' AND config_group_title='global'");  
define("CALLPLAN",$call_plan[0]['config_value']);
?>
