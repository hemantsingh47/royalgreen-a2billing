<?php
include ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
if (!$ACXACCESS) 
{
	Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");       
    die();       
}
//$smarty->display('main.tpl');
 
   
   if(isset($_FILES['audio'])){
      $errors= array();
      $file_name = $_FILES['audio']['name'];
      $file_size =$_FILES['audio']['size'];
      $file_tmp =$_FILES['audio']['tmp_name'];
      $file_type=$_FILES['audio']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['audio']['name'])));
      
      $expensions= array("gsm");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"en/".$file_name);
         echo "Success";
      }else{
         print_r($errors);
      }
   }
?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content" align="center">
<img src ="logo-adore.jpg">	<BR><BR>
<hr\>										
<!-- begin:: Subheader -->

<!-- end:: Subheader -->					


<!------------------------------------------------------------MAIN PAGE BEGIN-------------------------------------------------------------->
 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		
	<!--begin::Portlet-->
	<div class="kt-portlet">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h1 class="kt-portlet__head-title">
				  <?php echo gettext("Change IVR");?>
				</h1>
			</div>
		</div>
   
   
      
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="audio" />
         <input type="submit"/>
      </form>
      
