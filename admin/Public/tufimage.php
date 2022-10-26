<?php
include ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
 
 if(isset($_GET['theme']))
 {
    $theme=$_GET['theme'];
 }

$DBHandle=DbConnect();

$desired_width = 203;
$desired_height = 62;
$query = "SELECT theme_data,theme_type FROM cc_theme WHERE ID='$theme'";  
$result = mysql_query($query) or die(mysql_error()); 
   while ($row = mysql_fetch_array($result)) 
    {
        $blobcontents = $row['theme_data'];
       
       $imagetype= $row['theme_type'];
}
 
 header("Content-type: {$imagetype}"); 
    echo base64_decode($blobcontents);
   
?>