<?php
include ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
 
echo $admin = $_SESSION['pr_login'];

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
   
?>
<?php
   if(isset($_FILES['image'])){
        
     $result=imageUpload($_FILES,$admin,FSROOT."images/company_logo/admin","image successfully uploaded",$DBHandle);
      print_r($result);
   }
?>
<html>
   <body>
      
      <form action = "" method = "POST" enctype = "multipart/form-data">
         <input type = "file" name = "image" />
         <input type = "submit"/>
            
         <ul>
            <li>Sent file: <?php echo $_FILES['image']['name'];  ?>
            <li>File size: <?php echo $_FILES['image']['size'];  ?>
            <li>File type: <?php echo $_FILES['image']['type'] ?>
         </ul>
            
      </form>
      
   </body>
</html>