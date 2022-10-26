<?php                                                                                               
include ("../lib/admin.defines.php");

 

 $DBHandle = DbConnect();
 $userid=$_SESSION['admin_id'];
//UPDATING LOGO
 if(isset($_POST['submit6']))
 {
     $result=imageUpload($_FILES,"admin_".$userid,array(IMGDIR."admin_logo",AGENTIMGDIR."agent_logo",CUSTIMGDIR."agent_logo"),"image successfully uploaded",$DBHandle);
       //print_r($result);
      if(empty($result["error"]))
      {         $name=$result['img'][0];
                $listadmin_table = new Table('cc_ui_authen','userid');
                $listadminresult = $listadmin_table -> Get_list($DBHandle, NULL, 0);
                $listadmins="";
               for($i=0;$i<count($listadminresult);$i++)
               {
                   if($i==0){$listadmins.="'".$listadminresult[$i]['userid']."'";}else{$listadmins.=",'".$listadminresult[$i]['userid']."'";}
               }
                $instance_table = new Table("cc_ui_authen","*");
                $values="logopath='$name'";
                //$clause = "userid='$userid'";
                 $clause = "userid  IN (".$listadmins.")";
                $return=$instance_table -> Update_table($DBHandle, $values, $clause);
                //  print_r($return);
                 if (($return))
                 {
                      
                     $msg = gettext("---Logo Updated Successfully---"); 
                    
                 }
                 else
                 {
                      $msg = gettext("Logo has not been inserted due to some error, Please Try Again");
                 }
      }       
     /* if ($_FILES['image'])
         {
           $image = $_FILES['image'];
           $path = $_FILES['image']['tmp_name'];
           if(filesize($path) >0)
           {
            if(filesize($path)>50000)
            {
                    $msg= gettext("--File Size of the logo is more than 50 KB--");
                     
            }
        else
        {
            $type=mysql_real_escape_string($image['type']);
            $size=$image['size'] ;
            $name=mysql_real_escape_string($image['name']);
            $data=base64_encode(file_get_contents($image['tmp_name']));
                
                $clause = "id='-1'";
                $instance_table = new Table("cc_image","*");
                $values="logo_name='$name', mime_type='$type', logo_size='$size', logo_data='$data'";
                $return=$instance_table -> Update_table($DBHandle, $values, $clause);
                //  print_r($return);
                 if (($return))
                 {
                      
                     $msg = gettext("---Logo Updated Successfully---"); 
                    
                 }
                 else
                 {
                      $msg = gettext("Logo has not been inserted due to some error, Please Try Again");
                 }
           }
        }
         } */
     
 }
  //query for collecting value from the table
   /*
   $query="SELECT * FROM cc_admin_maincss WHERE  userid='$userid' " ;
   $result = mysql_query($query) or die(mysql_error());
   while ($row = mysql_fetch_array($result)) 
    {
    
    } */

 

include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
if (!$ACXACCESS) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");       
    die();       
}
$smarty->display('main.tpl');

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Admin                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Admin                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Settings                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="CC_tempsubstitutelogocurrent.php" class="kt-subheader__breadcrumbs-link">
                            Change Logo                        </a>
                       
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>
<!-- end:: Subheader -->					


<!------------------------------------------------------------MAIN PAGE BEGIN-------------------------------------------------------------->
 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		
	<!--begin::Portlet-->
	<div class="kt-portlet">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h1 class="kt-portlet__head-title">
				  <?php echo gettext("Change Logo");?>
				</h1>
			</div>
		</div>
 
 
 <div class="kt-form">
        <div class="kt-form__head-label"> <span class="icon"> <i class="icon-align-justify"></i> </span>
         
		  <center><font style="font-size:12px; font-weight:bold; color:green;"><?php echo $msg; ?></font></center>
        </div> 
        <center >  
            
         
         <br/>
		 
         <fieldset style="text-align: left;font-size: large;">
          <h3> <p style="padding-left:200px;"><?php echo gettext("Logo Preview")?></p></h3>
            <div align="center"><img src="<?php echo IMGNAME?>" width="203" height="62" border="0" ></div>
            
         </fieldset>
		 <hr />
         <fieldset style="text-align: left;font-size: large;">
		 
          <form style="padding-left:250px;" name="myform" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"  enctype="multipart/form-data">
           
                       <table   cellpadding="5" cellspacing="5" style="font-size:16px; " >
                          
                       <tr>          
                        <td><input type="file" name="image" value="Browse" class="input" style=""></td>
                        </tr>  
                       <tr>   
                      <td >
                            <strong style=""><?php echo gettext("Preferred Size logo is 220 X 77 File size range must within 2MB."); ?> </strong>     
                             
                      </td>
                      
                      </tr>   
                       <br />
                       <tr style="text-align : center">
                       <td ><input name="submit6" type="submit" value="<?php echo gettext("Update Logo"); ?>" class="btn btn-brand"  style=""> </td>
                       </tr>
                      </table>  
					  <br />
                    </form>
         </fieldset>
         
         
        </center>  
  </div>

 <?php
    // #### FOOTER SECTION
$smarty->display('footer.tpl');

 ?>