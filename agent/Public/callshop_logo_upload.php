<?php
include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_signup_agent.inc';
include '../lib/agent.smarty.php';

/*if (! has_rights (ACX_SIGNUP)) {
       Header ("HTTP/1.0 401 Unauthorized");
       Header ("Location: PP_error.php?c=accessdenied");
       die();
}*/

?>
 
<?php
$HD_Form -> setDBHandler (DbConnect());
$first_login = $_SESSION["pr_login"];


// #### HEADER SECTION
$smarty->display('main.tpl');  
if (isset($_GET['Message'])) {
 
    $msg =  $_GET['Message'];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PHP File Uploader</title>

    <!-- Bootstrap core CSS -->
    <link href="boostrap/css/bootstrap.min.css" rel="stylesheet">
   
  </head>

  <body> 
  
  
  <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Receipt Logo Upload </h3>
            	<span class="kt-subheader__separator kt-hidden"></span>
                	<div class="kt-subheader__breadcrumbs">
                		<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
							<a href="" class="kt-subheader__breadcrumbs-link">
                        		CallShop                        </a>
                                <span class="kt-subheader__breadcrumbs-separator"></span>
                    		<a href="" class="kt-subheader__breadcrumbs-link">
                        		CallShop Config                        </a>
                                <span class="kt-subheader__breadcrumbs-separator"></span>
                    		<a href="" class="kt-subheader__breadcrumbs-link">
                       			Receipt Logo Upload                      </a>
            		</div>
        </div>
    </div>
	</div>
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  	<div class="col-md-12" style="margin: 0 auto;">
	  	<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
            		<font class="kt-portlet__head-title"><?php echo gettext("Receipt Logo Upload"); ?></font>
				</div>
			</div>
        
	       <?php 
	       	//scan "uploads" folder and display them accordingly
	       $folder = "uploads";
	       $results = scandir('uploads');
	       foreach ($results as $result) {
	       	if ($result === '.' or $result === '..') continue;
	       
	       	if (is_file($folder . '/' . $result)) {
	       		echo '
	       		<div class="col-md-12" style="border: 1px solid #eee;text-align: center;">
		       		<div class="thumbnail">
			       		<img src="'.$folder . '/' . $result.'" alt="...">
				       		<div class="caption">
				       		<br><p><a href="remove.php?name='.$result.'" class="btn btn-danger btn-xs" role="button">Remove</a></p>
			       		</div>
		       		</div>
	       		</div>';
	       	}
	       }
	       ?> 
	   
            <font style="font-size:15px; font-weight:bold; color:green; text-align:center;"><?php echo $msg; ?></font> 
	        <form action="upload.php" method="post" enctype="multipart/form-data"   class="well">
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
                  			<input type="file" class="custom-file-input" name="file">
				  			<label class="custom-file-label form-control" for="file">Choose file</label>
		    				<p class="help-block">Only jpg,jpeg,png and gif file is allowed. </br>Preferred Size for the logo is 203 X 62 and File size range must be within 50 KB is allowed.</p>
              			</div>
					</div>
				</div>
				<div align="center" class="kt-portlet__foot">
            		<div class="form-actions">
						<table>  
							<tr align="center"><td colspan="7"><input  type="submit" value="<?php echo gettext("Update Logo"); ?>" class="btn btn-brand" /> </td></tr>
						</table>
					</div>
				</div>
			</form>
       	</div>
    </div>
  	</div>   
  </div>
  
  </body>
</html>           
<?php
$smarty->display('footer.tpl'); 
?>