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
$smarty->display('main.tpl');
 

 $DBHandle = DbConnect();
 $userid=$_SESSION['admin_id'];
//UPDATING LOGO


// Check if the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if file was uploaded without errors
    if(isset($_FILES["audio"]) && $_FILES["audio"]["error"] == 0){
        $allowed = array("gsm" => "audio/GSM");
        $filename = $_FILES["audio"]["name"];
        $filetype = $_FILES["audio"]["type"];
        $filesize = $_FILES["audio"]["size"];
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
    
        // Verify file size - 5MB maximum
        //$maxsize = 5 * 1024 * 1024;
        //if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            //if(file_exists("upload/" . $filename)){
              //  echo $filename . " is already exists.";
           // } else{
                move_uploaded_file($_FILES["auio"]["tmp_name"], "en/" . $filename);
				
                echo "Your file was uploaded successfully.";
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } else{
        echo "Error: " . $_FILES["audio"]["error"];
    }
}


/*

if(isset($_POST['submit6']))
{
	$tname = $_FILES["tphoto"]["name"];
	$ttmp_name = $_FILES["tphoto"]["tmp_name"];
	//Audio File Uploading....  
	if($_FILES['tphoto']['type'] == 'audio/GSM')
	{
		$dir="../en";
		move_uploaded_file($ttmp_name,"$dir"."/"."$tname");
		$msg = gettext("---IVR Updated Successfully---");
	}
	
	else
	{
	  $msg = gettext("---Try again---");
	}
}   

*/  
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
                        <a href="change_ivr.php" class="kt-subheader__breadcrumbs-link">
                            Change IVR                        </a>
                       
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
				  <?php echo gettext("Change IVR");?>
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
		 
          <form style="" name="myform" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"  enctype="multipart/form-data">
           
                       <table   cellpadding="5" cellspacing="5" style="font-size:16px; " >
                          
                       					   
					   <div class="custom-file">
						
							<!--<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $my_max_file_size?>">-->
							<input type="hidden" name="task" value="upload">
							<input name="the_file" type="file" accept="audio/GSM" class="custom-file-input" size="50" onFocus=this.select()>
							<label class="custom-file-label" for="customFile"></label>
							<div class="kt-form__actions" align="right">
							<br><input type="submit" value="Upload IVR" onFocus=this.select() class="btn btn-primary btn-small" name="submit6" ><br>
							</div>
						</div>
						
						
                       
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