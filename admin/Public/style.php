
<?php

 header("Content-Type: text/css; charset=utf-8");
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

$DBHandle  = DbConnect();
$inst_table  = new Table();
//fetching data in descending order (lastest entry first)
$result = "SELECT * FROM users1 ORDER BY id"; // mysql_query is deprecated
$result_info  = $inst_table -> SQLExec($DBHandle, $result); 
$array = array();
foreach($result_info as $result)
      {
        $textColor = $res['textColor'];
	  
$array_value =  array_push($array, array('textColor' => $textColor));     
      }
	  foreach ( $array as $var ) {
		  $id=$var[id];
switch(true) {
    case ($var[id]=='1'):
	     if ($var[id]=='1') {
            $bgColor = $var['textColor'];
              }
	     break;
	 
	  case ($var[id]=='2'):
	      if ($var[id]=='2') {
            $bgColor = $var['textColor'];
              }
		 break;	  
            
     case ($var[id]=='3'):
	      if ($var[id]=='3') {
            $bgColor = $var['textColor'];
              }
		 break;	  
	case ($var[id]=='4'):
	      if ($var[id]=='4') {
            $bgColor = $var['textColor'];
              }
		 break;	  
}
	  }

?>
 
/** CSS begins **/
body{
    background-color: <?= $bgColor; ?>;
    
}
