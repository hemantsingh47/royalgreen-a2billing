<?php                                                                                               
include ("lib/customer.defines.php");

 

 $DBHandle = DbConnect();
 $userid=$_SESSION['card_id'];
//UPDATING LOGO
 if(isset($_POST['submit6']))
 {
     $result=imageUpload($_FILES,"customer_".$userid,CUSTIMGDIR."dp","Display image successfully uploaded",$DBHandle);
     
      if(empty($result["error"]))
      {         $name=$result['img'][0];
                $instance_table = new Table("cc_card","*");
                $values="display_image='$name'";
                $clause = "id='$userid'";
                $return=$instance_table -> Update_table($DBHandle, $values, $clause);
                //  print_r($return);
                 if (($return))
                 {
                      
                     $msg = gettext("---Profile Picture Updated Successfully---"); 
                    
                 }
                 else
                 {
                      $msg = gettext("Profile Picture  has not been inserted due to some error, Please Try Again");
                 }
      }       
     
     
 }
  

 

include_once ("lib/customer.module.access.php");
include ("lib/customer.smarty.php");

if (!has_rights(ACX_ACCESS)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}
$smarty->display('main.tpl');

?>


<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Change Picture  </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Dashboard                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Display Image                       </a>
                </div>
            </span>
        </div>
    </div>
</div>
 
 <div class="col-md-12" style="margin: 0 auto;">
    <div class="md-card-content">  
        <center >  
            <font style="font-size:15px; font-weight:bold; color:green;"><?php echo $msg; ?></font>
         
         <br/>
         <fieldset style="text-align: left;font-size: large;">
            <legend> <?php echo gettext("Preview")?></legend>
            <div align="center"><img src="templates/default/images/dp/<?php echo CUSTOMERDP;?>" width="203" height="62" border="0" ></div>
            
         </fieldset>
         <fieldset style="text-align: left;font-size: large;">
          <form name="myform" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"  enctype="multipart/form-data">
           
                       <table   cellpadding="5" cellspacing="5" style="font-size:16px; " >
                          
                       <tr>          
                        <td><input type="file" name="image" value="Browse" class="md-input" style=""></td>
                        </tr>  
                       <tr>   
                      <td >
                            <strong style=""><?php echo gettext("Preferred Size logo is 203 X 62 File size range must within 2MB."); ?> </strong>     
                             
                      </td>
                      
                      </tr>   
                       
                       <tr >
                       <td ><input name="submit6" type="submit" value="<?php echo gettext("Update Profile"); ?>" class="md-btn md-btn-primary"  style=""> </td>
                       </tr>
                        
                      </table>  
                    </form>
         </fieldset>
         
         
        </center>  
  </div>
 </div> 
 </div>
 <?php
    // #### FOOTER SECTION
$smarty->display('footer.tpl');

 ?>