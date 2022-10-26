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

<style type="text/css">
img.baba{
border-radius: 100px;
    background-repeat: repeat;
    width: 125px;
    height: 125px;
}	
.error_message{
	color:green;
}
</style>


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
    <div class="md-card-content" style="background: #ffffff;padding: 10px;">  
        <center >  
            
         <fieldset style="text-align: left;">
            <div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
            		<font class="kt-portlet__head-title"><?php echo gettext("Preview"); ?></font>
				</div>
			</div>
            </div>
            
            <font style=" text-align: center; font-size:15px; font-weight:bold; color:green;"><?php echo $msg; ?></font>
            <div align="center"><img src="templates/default/images/dp/<?php echo CUSTOMERDP;?>" width="203" height="62" class="baba" border="0" ></div>
            
         </fieldset>
         <fieldset style="text-align: left;">
         
         
         
         <form name="myform" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"  enctype="multipart/form-data">
				 <!-- <div class="">
				    <label for="file">Select a file to upload</label>
				    <input type="file" name="file">
				    <p class="help-block">Only jpg,jpeg,png and gif file is allowed. </br>Preferred Size for the logo is 203 X 62 and File size range must be within 50 KB is allowed.</p>
				</div>
				<input type="submit" class="btn btn-brand" value="Upload">-->
				
				<div class="kt-portlet__body">
					<div class="form-group row">
					<div class="col-lg-1"></div>
						<font class="col-lg-2 col-sm-12"><?php echo gettext("Select a file to upload");?></font>
              			<div class="col-lg-6 col-md-9 col-sm-12">
                          <input type="file" name="image" value="Browse" class="custom-file-input" style="">
                              
                            <!--  <input type="file" class="custom-file-input" name="file">-->
				  			<label class="custom-file-label form-control" for="file">Choose file</label>
		    				<p class="help-block">Only jpg,jpeg,png and gif file is allowed. </br>Preferred Size for the logo is 203 X 62 and File size range must be within 50 KB is allowed.</p>
              			</div>
					</div>
				</div>
				<div align="center" class="kt-portlet__foot">
            		<div class="form-actions">
						<table>  
							<tr align="center"><td colspan="7"><input name="submit6" type="submit" value="<?php echo gettext("Update Profile"); ?>" class="btn btn-brand"  style="">  </td></tr>
						</table>
					</div>
				</div>
			</form>
         
         
         
         
        <!--  <form name="myform" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"  enctype="multipart/form-data">
           
                       <table   cellpadding="5" cellspacing="5" style="font-size:16px; " class="table" align="center">
                          
                       <tr>          
                        <td style="text-align: center;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input type="file" name="image" value="Browse" class="md-input" style=""></td>
                        </tr>

						
                       <tr>   
                      <td style="text-align: center;">
                            <strong style=""><?php echo gettext("Preferred Size logo is 203 X 62 File size range must within 2MB."); ?> </strong>     
                             
                      </td>
                      
                      </tr>   
                       
                       <tr >
                       <td style="text-align: center;"><input name="submit6" type="submit" value="<?php echo gettext("Update Profile"); ?>" class="btn btn-brand"  style=""> </td>
                       </tr>
                        
                      </table>  
                    </form>-->
         </fieldset>
         
         
        </center>  
  </div>
 </div> 
 </div>
 <?php
    // #### FOOTER SECTION
$smarty->display('footer.tpl');

 ?>