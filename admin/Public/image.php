<?php
 include ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php"); 
$admin = $_SESSION['pr_login'];
$adminid= $_SESSION['admin_id'];
$DBHandle=DbConnect();
 /*
   //finding admin_id
$crid="SELECT userid FROM cc_ui_authen WHERE login='$username'";
$crresult= mysql_query($crid) or die(mysql_error());
 while($row=mysql_fetch_array($crresult))
 {
    $userid=$row['userid'];
 }

 */
/*$desired_width = 100;
$desired_height = 20;
//$query = "SELECT logo_data,logo_mime_type FROM cc_admin_maincss WHERE user_agent='$admin'"; 
$image_table = new Table('cc_image','logo_data,mime_type');
$imaget_clause = "id='-1' LIMIT 1";
$imageresult = $image_table -> Get_list($DBHandle, $imaget_clause, 0);
 $blobcontents = $imageresult[0]['logo_data']; 
 $imageData= $imageresult[0]['mime_type'];
  

 header("Content-type: {$imageData}"); 
 echo base64_decode($blobcontents);*/
$image_table = new Table('cc_ui_authen','logopath');
$imaget_clause = "userid='$adminid' LIMIT 1";
$imageresult = $image_table -> Get_list($DBHandle, $imaget_clause, 0);

   
?>
<img src="templates/default/images/admin_logo/<?php echo $imageresult[0]['logopath']?>" alt="" style="border-radius:50%;-webkit-border-radius:50%;-moz-border-radius:50%;-o-border-radius:50%;" height="62" width="203"/>